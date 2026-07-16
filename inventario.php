<?php 
include("include/error.php"); 
$sesion=explode("|",$_SESSION["usuario"]);
$campus=$sesion[2];
?>
<!DOCTYPE html>
<html lang="es">
    
<?php include("include/head.php"); ?>

<style>
img.lazy-foto {
    background: #f0f0f0;
    border-radius: 6px;
    object-fit: cover;
}
</style>

<body id="page-top">
<div id="wrapper">
    <?php include("include/menu.php"); ?>

    <div id="content-wrapper" class="d-flex flex-column">
        <div id="content">
            <?php
            session_start();
            include("include/perfil.php");
            include("include/conn.php");

            // Placeholder: 1x1 gif transparente mientras carga la imagen real
            $placeholder = "data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==";

            try {
                // ── Leer filtros GET ──────────────────────────────────────────
                $filtro_almacen     = isset($_GET["almacen"])     ? (int)$_GET["almacen"]     : 0;
                $filtro_plantel     = isset($_GET["plantel"])     ? (int)$_GET["plantel"]     : 0;
                $filtro_existencias = isset($_GET["existencias"]) ? (int)$_GET["existencias"] : 0;
                $filtro_foto        = isset($_GET["foto"])        ? (int)$_GET["foto"]        : 0;

                // Si no es admin (campus 8), forzar siempre su plantel
                if ((int)$campus != 8 && $filtro_plantel == 0) {
                    $filtro_plantel = (int)$campus;
                }

                // Nombre del plantel seleccionado (para encabezado de columna)
                $nombre_plantel_sel = "Stock";
                if ($filtro_plantel != 0) {
                    $stmtP = $dbconn->prepare("SELECT nombre FROM planteles WHERE id = ?");
                    $stmtP->execute([$filtro_plantel]);
                    $rowP = $stmtP->fetch(PDO::FETCH_ASSOC);
                    if ($rowP) $nombre_plantel_sel = $rowP["nombre"];
                }

                // ── Query: vista de UN plantel ────────────────────────────────
                if ($filtro_plantel != 0) {

                    $params = [$filtro_plantel];

                    $sql = "SELECT i.id, i.area, i.codigo, i.nombre, i.medida,
                                COALESCE(s.stock, 0) AS stock,
                                COALESCE(t.total, 0) AS total,
                                i.foto
                            FROM inventario i
                            LEFT JOIN inventario_stock s
                                ON s.articulo_id = i.id AND s.plantel_id = ?
                            LEFT JOIN (
                                SELECT articulo_id, SUM(stock) AS total
                                FROM inventario_stock
                                GROUP BY articulo_id
                            ) t ON t.articulo_id = i.id
                            WHERE i.status = 1";

                    if ($filtro_almacen != 0) { $sql .= " AND i.area = ?"; $params[] = $filtro_almacen; }
                    if ($filtro_existencias == 1) $sql .= " AND COALESCE(s.stock, 0) > 0";
                    if ($filtro_existencias == 2) $sql .= " AND COALESCE(s.stock, 0) = 0";
                    if ($filtro_foto == 1) $sql .= " AND (i.foto IS NOT NULL AND i.foto != '')";
                    if ($filtro_foto == 2) $sql .= " AND (i.foto IS NULL OR i.foto = '')";

                    $sql .= " ORDER BY SUBSTRING_INDEX(i.codigo, '-', 1),
         CAST(SUBSTRING_INDEX(i.codigo, '-', -1) AS UNSIGNED)";

                    $stmt = $dbconn->prepare($sql);
                    $stmt->execute($params);

                // ── Query: vista general todos los planteles (admin) ──────────
                } else {

                    $params = [];

                    $sql = "SELECT i.id, i.area, i.codigo, i.nombre, i.medida,
                                COALESCE(SUM(CASE WHEN s.plantel_id = 1  THEN s.stock END), 0) AS `SJR`,
                                COALESCE(SUM(CASE WHEN s.plantel_id = 13 THEN s.stock END), 0) AS `SJR5`,
                                COALESCE(SUM(CASE WHEN s.plantel_id = 2  THEN s.stock END), 0) AS `ACULCO`,
                                COALESCE(SUM(CASE WHEN s.plantel_id = 3  THEN s.stock END), 0) AS `TECAMAC`,
                                COALESCE(SUM(CASE WHEN s.plantel_id = 4  THEN s.stock END), 0) AS `ATLACOMULCO`,
                                COALESCE(SUM(CASE WHEN s.plantel_id = 5  THEN s.stock END), 0) AS `TEPEJI`,
                                COALESCE(SUM(CASE WHEN s.plantel_id = 6  THEN s.stock END), 0) AS `NOPALA`,
                                COALESCE(SUM(CASE WHEN s.plantel_id = 7  THEN s.stock END), 0) AS `EN LINEA`,
                                COALESCE(SUM(CASE WHEN s.plantel_id = 8  THEN s.stock END), 0) AS `CORPORATIVO`,
                                COALESCE(SUM(s.stock), 0) AS total,
                                i.foto
                            FROM inventario i
                            LEFT JOIN inventario_stock s ON s.articulo_id = i.id
                            WHERE i.status = 1";

                    if ($filtro_almacen != 0) { $sql .= " AND i.area = ?"; $params[] = $filtro_almacen; }
                    if ($filtro_foto == 1) $sql .= " AND (i.foto IS NOT NULL AND i.foto != '')";
                    if ($filtro_foto == 2) $sql .= " AND (i.foto IS NULL OR i.foto = '')";

                    $sql .= " GROUP BY i.id, i.area, i.codigo, i.nombre, i.medida";

                    if ($filtro_existencias == 1) $sql .= " HAVING COALESCE(SUM(s.stock), 0) > 0";
                    if ($filtro_existencias == 2) $sql .= " HAVING COALESCE(SUM(s.stock), 0) = 0";

                    $sql .= " ORDER BY SUBSTRING_INDEX(i.codigo, '-', 1),
         CAST(SUBSTRING_INDEX(i.codigo, '-', -1) AS UNSIGNED)";

                    $stmt = $dbconn->prepare($sql);
                    $stmt->execute($params);
                }

                $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

            } catch (Exception $e) {
                echo $e->getMessage();
            }

            // ── Helper: renderiza la celda de foto con lazy loading ───────────
            // Lógica igual que case 24:
            //   foto != ''           → imagen real (lazy)
            //   foto == '' y area 10 → BIBL.jpeg (lazy)
            //   foto == '' y otro    → image.svg (estático, sin lazy)
            function renderFoto($row, $placeholder) {
                $foto = $row["foto"] ?? '';
                $area = (int)($row["area"] ?? 0);
                ob_start();
                if ($foto != '') {
                    // Imagen real → lazy
                    echo '<div class="text-center">
                            <img src="' . $placeholder . '"
                                 data-src="img/categorias/inventario/' . htmlspecialchars($foto) . '"
                                 class="inv-thumb lazy-foto"
                                 width="80" height="80">
                          </div>';
                } elseif ($area == 10) {
                    // Biblioteca → BIBL.jpeg (lazy)
                    echo '<div class="text-center">
                            <img src="' . $placeholder . '"
                                 data-src="img/categorias/inventario/BIBL.jpeg"
                                 class="inv-thumb lazy-foto"
                                 width="80" height="80">
                          </div>';
                } else {
                    // Sin foto → placeholder estático image.svg
                    echo '<div class="text-center">
                            <img src="img/svg/image.svg"
                                 class="inv-thumb"
                                 width="80" height="80"
                                 style="opacity:0.4;">
                          </div>';
                }
                return ob_get_clean();
            }
            ?>

            <div class="container-fluid">

                <div class="d-sm-flex align-items-center justify-content-between mb-4">
                    <h1 class="h3 mb-0 text-gray-800">Inventario</h1>
                    <?php if($campus==8): ?>
                        <a class="d-sm-inline-block btn btn-sm btn-primary shadow-sm"
                           data-toggle="modal" data-target="#addProspectModal" onclick="agregarArticulo()">
                            <i class="fas fa-download fa-sm text-white-100"></i> Agregar articulo
                        </a>
                    <?php else: ?>
                        <a class="d-sm-inline-block btn btn-sm btn-primary shadow-sm" onclick="historialInventario()">
                            <i class="fas fa-download fa-sm text-white-100"></i> Recibos
                        </a>
                    <?php endif; ?>
                </div>

                <!-- Filtro -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Filtro</h6>
                    </div>
                    <div class="card-body">
                        <div class="container-fluid">
                            <div class="row">
                                <div class="col-md-4">
                                    <select name="almacen" id="almacen" class="form-control">
                                        <option value="0">Almacen...</option>
                                        <?php
                                        $stmtIdx = $dbconn->prepare("SELECT * FROM inventario_indices WHERE status=1");
                                        $stmtIdx->execute();
                                        foreach($stmtIdx->fetchAll(PDO::FETCH_ASSOC) as $a): ?>
                                            <option value="<?= $a["id"] ?>" <?= $a["id"] == $filtro_almacen ? "selected" : "" ?>>
                                                <?= $a["categoria"] ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <select name="plantel" id="plantel" class="form-control"
                                            <?= (int)$campus != 8 ? "disabled" : "" ?>>
                                        <option value="0">Plantel...</option>
                                        <?php
                                        $stmtPl = $dbconn->prepare("SELECT * FROM planteles WHERE status=1");
                                        $stmtPl->execute();
                                        foreach($stmtPl->fetchAll(PDO::FETCH_ASSOC) as $p):
                                            if ((int)$campus != 8) {
                                                $sel = $p["id"] == $campus ? "selected" : "";
                                            } else {
                                                $sel = $p["id"] == $filtro_plantel ? "selected" : "";
                                            }
                                        ?>
                                            <option value="<?= $p["id"] ?>" <?= $sel ?>><?= $p["nombre"] ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <select name="existencias" id="existencias" class="form-control">
                                        <option value="0">Existencias...</option>
                                        <option value="1" <?= $filtro_existencias == 1 ? "selected" : "" ?>>Con existencias</option>
                                        <option value="2" <?= $filtro_existencias == 2 ? "selected" : "" ?>>Sin existencias</option>
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <select name="foto" id="foto" class="form-control">
                                        <option value="0">Foto...</option>
                                        <option value="1" <?= $filtro_foto == 1 ? "selected" : "" ?>>Con foto</option>
                                        <option value="2" <?= $filtro_foto == 2 ? "selected" : "" ?>>Sin foto</option>
                                    </select>
                                </div>
                                <div class="col-md-1">
                                    <a class="d-sm-inline-block btn btn-sm btn-block btn-primary shadow-sm"
                                       onclick="filtrar()">
                                        <i class="fa-duotone fa-solid fa-filter"
                                           style="--fa-primary-color:#fff;--fa-secondary-color:#fff;"></i> Filtrar
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tabla -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Tabla de artículos</h6>
                    </div>
                    <div class="card-body">
                        <div class="container-fluid">
                            <table id="tcont" class="table table-striped table-bordered nowrap table-hover" style="width:100%;">
                                <thead>
                                    <tr>
                                        <th>id</th>
                                        <th>Código</th>
                                        <th>Nombre</th>
                                        <?php if ($filtro_plantel != 0): ?>
                                            <th><?= htmlspecialchars($nombre_plantel_sel) ?></th>
                                            <th>Total general</th>
                                            <th>Foto</th>
                                        <?php else: ?>
                                            <th>San Juan</th>
                                            <th>San Juan 5</th>
                                            <th>Aculco</th>
                                            <th>Tecámac</th>
                                            <th>Atlacomulco</th>
                                            <th>Tepeji</th>
                                            <th>Nopala</th>
                                            <th>En línea</th>
                                            <th>Corporativo</th>
                                            <th>Totales</th>
                                            <th>Foto</th>
                                        <?php endif; ?>
                                    </tr>
                                </thead>
                                <tbody id="tbodycont">
                                    <?php foreach ($rows as $row): ?>
                                        <tr>
                                            <td><?= $row["id"] ?></td>
                                            <td>
                                                <?php if($sesion[2]==8): ?>
                                                <a href="#" onclick="articulo(<?= $row['id'] ?>)" class="btn btn-primary btn-icon-split">
                                                <?php else: ?>
                                                <a href="#" onclick="movInvCampus(<?=$sesion[2]?>,<?=$row['id']?>)" class="btn btn-primary btn-icon-split">
                                                <?php endif; ?>
                                                    <span class="icon text-white-100">
                                                        <i class="fas fa-cogs fa-sm fa-fw"></i>
                                                    </span>
                                                    <span class="text"><?= $row["codigo"] ?></span>
                                                </a>
                                            </td>
                                            <td><?= $row["nombre"] ?></td>
                                            <?php if ($filtro_plantel != 0): ?>
                                                <?php if($sesion[2]==8): ?>
                                                <td>
                                                    <p onclick="movInv(<?= $filtro_plantel ?>,<?= $row['id'] ?>)"><?= $row["stock"] ?></p>
                                                </td>
                                                <?php else: ?>
                                                <td onclick="movInvCampus(<?=$sesion[2]?>,<?=$row['id']?>)" class="text-center">
                                                    <p><?= $row["stock"] ?></p>
                                                </td>
                                                <?php endif; ?>
                                                <td><?= $row["total"] ?></td>
                                                <td><?= renderFoto($row, $placeholder) ?></td>
                                            <?php else: ?>
                                                <td class="text-center"><p onclick="movInv(1,<?= $row['id'] ?>)"><?= $row["SJR"] ?></p></td>
                                                <td class="text-center"><p onclick="movInv(13,<?= $row['id'] ?>)"><?= $row["SJR5"] ?></p></td>
                                                <td class="text-center"><p onclick="movInv(2,<?= $row['id'] ?>)"><?= $row["ACULCO"] ?></p></td>
                                                <td class="text-center"><p onclick="movInv(3,<?= $row['id'] ?>)"><?= $row["TECAMAC"] ?></p></td>
                                                <td class="text-center"><p onclick="movInv(4,<?= $row['id'] ?>)"><?= $row["ATLACOMULCO"] ?></p></td>
                                                <td class="text-center"><p onclick="movInv(5,<?= $row['id'] ?>)"><?= $row["TEPEJI"] ?></p></td>
                                                <td class="text-center"><p onclick="movInv(6,<?= $row['id'] ?>)"><?= $row["NOPALA"] ?></p></td>
                                                <td class="text-center"><p onclick="movInv(7,<?= $row['id'] ?>)"><?= $row["EN LINEA"] ?></p></td>
                                                <td class="text-center"><p onclick="movInv(8,<?= $row['id'] ?>)"><?= $row["CORPORATIVO"] ?></p></td>
                                                <td><?= $row["total"] ?></td>
                                                <td><?= renderFoto($row, $placeholder) ?></td>
                                            <?php endif; ?>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            </div>
            <!-- /.container-fluid -->

        </div>

        <?php include("include/footer.php"); ?>

    </div>
</div>

<a class="scroll-to-top rounded" href="#page-top">
    <i class="fas fa-angle-up"></i>
</a>

<?php include("include/scripts.php"); ?>
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script src="js/inventario.js"></script>
<script>
    function cargarImagenesPagina(dtInstance) {
        dtInstance.rows({ page: 'current' }).nodes().each(function (row) {
            $(row).find('img.lazy-foto[data-src]').each(function () {
                if (!this.dataset.loaded) {
                    this.src = this.dataset.src;
                    this.dataset.loaded = '1';
                }
            });
        });
    }

    var table = new DataTable('#tcont', {
        responsive: true,
        order: [],
    columnDefs: [
        { targets: [0], visible: false }, // id siempre oculto
        <?php if ($filtro_plantel == 0): ?>
        // Vista general: 13 columnas (0=id,1=código,2=nombre,3-11=planteles,12=totales,13=foto... ajusta si difiere)
        { targets: [4, 5, 6, 8, 9], visible: false }
        <?php endif; ?>
    ],
        language: {
            "decimal": "",
            "emptyTable": "No hay información",
            "info": "Mostrando _START_ a _END_ de _TOTAL_ Entradas",
            "infoEmpty": "Mostrando 0 to 0 of 0 Entradas",
            "infoFiltered": "(Filtrado de _MAX_ total entradas)",
            "infoPostFix": "",
            "thousands": ",",
            "lengthMenu": "Mostrar _MENU_ Entradas",
            "loadingRecords": "Cargando...",
            "processing": "Procesando...",
            "search": "Buscar:",
            "zeroRecords": "Sin resultados encontrados"
        },
        lengthMenu: [[10, 25, 50, -1], ['10 filas', '25 filas', '50 filas', 'Todos']],
        layout: {
            topStart: { buttons: ['colvis', 'pageLength'] },
            topEnd: ['search'],
            bottomStart: [{
                buttons: [
                    { extend: 'excel', title: 'Inventario' },
                    { extend: 'pdfHtml5', title: 'InventarioPDF' },
                    { extend: 'print' }
                ]
            }, 'info'],
            bottomEnd: 'paging'
        }
    });

    cargarImagenesPagina(table);

    table.on('draw.dt', function () {
        cargarImagenesPagina(table);
    });

    table.on('responsive-display.dt', function (e, datatable, row, showHide) {
        if (showHide) {
            $(row.node()).next('tr.child').find('img.lazy-foto[data-src]').each(function () {
                if (!this.dataset.loaded) {
                    this.src = this.dataset.src;
                    this.dataset.loaded = '1';
                }
            });
        }
    });

    $("#tcont_wrapper").removeClass("form-inline").addClass("w-100");

    var savedPage = sessionStorage.getItem('dtInventarioPage');
    if (savedPage !== null) {
        table.page(parseInt(savedPage)).draw('page');
        sessionStorage.removeItem('dtInventarioPage');
    }

    document.addEventListener('DOMContentLoaded', function () {
        const params = new URLSearchParams(window.location.search);
        const fotoOk = params.get('foto_ok');
        if (fotoOk) {
            Swal.fire({
                icon: 'success',
                title: '¡Imagen actualizada!',
                text: 'La foto del artículo ' + fotoOk + ' se subió correctamente.',
                confirmButtonColor: '#3085d6',
                timer: 4000,
                timerProgressBar: true
            });
            window.history.replaceState({}, document.title, window.location.pathname);
        }
    });
</script>

<div id="impresion"></div>

</body>
</html>