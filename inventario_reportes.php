<?php
include("include/error.php");
$sesion = explode("|", $_SESSION["usuario"]);
?>
<!DOCTYPE html>
<html lang="es">
<?php include("include/head.php"); ?>
<body id="page-top">

<div id="wrapper">
    <?php include("include/menu.php"); ?>

    <div id="content-wrapper" class="d-flex flex-column">
        <div id="content">
            <?php
            include("include/perfil.php");
            include("include/conn.php");

            /*
             * PIVOT de stock por campus calculado desde inventario_movimientos.
             *
             * Lógica de tipos:
             *   tipo = 1  → Ingreso:   +cantidad en campusIngreso
             *   tipo = 2  → Egreso:    -cantidad en campusEgreso
             *   tipo = 3  → Traspaso:  +cantidad en campusIngreso, -cantidad en campusEgreso
             *
             * Planteles:
             *   1  = San Juan del Río (SJR)
             *   13 = San Juan 5 de Mayo (SJR5)
             *   2  = Aculco (ACU)
             *   3  = Tecámac (TEC)
             *   5  = Tepeji del Río (TEP)
             *   4  = Atlacomulco (ATL)
             *   6  = Nopala (NOP)
             *   7  = En Línea (LIN)
             *   8  = Corporativo (COR)
             */
            $stmt = $dbconn->prepare("
                SELECT
                    i.id,
                    i.codigo,
                    i.nombre,
                    i.medida,

                    GREATEST(0, IFNULL(SUM(
                        CASE WHEN m.tipo IN (1,3) AND m.campusIngreso = 1  THEN  m.cantidad
                             WHEN m.tipo IN (2,3) AND m.campusEgreso  = 1  THEN -m.cantidad
                             ELSE 0 END
                    ), 0)) AS sanjuan,

                    GREATEST(0, IFNULL(SUM(
                        CASE WHEN m.tipo IN (1,3) AND m.campusIngreso = 13 THEN  m.cantidad
                             WHEN m.tipo IN (2,3) AND m.campusEgreso  = 13 THEN -m.cantidad
                             ELSE 0 END
                    ), 0)) AS sanjuan5,

                    GREATEST(0, IFNULL(SUM(
                        CASE WHEN m.tipo IN (1,3) AND m.campusIngreso = 2  THEN  m.cantidad
                             WHEN m.tipo IN (2,3) AND m.campusEgreso  = 2  THEN -m.cantidad
                             ELSE 0 END
                    ), 0)) AS aculco,

                    GREATEST(0, IFNULL(SUM(
                        CASE WHEN m.tipo IN (1,3) AND m.campusIngreso = 3  THEN  m.cantidad
                             WHEN m.tipo IN (2,3) AND m.campusEgreso  = 3  THEN -m.cantidad
                             ELSE 0 END
                    ), 0)) AS tecamac,

                    GREATEST(0, IFNULL(SUM(
                        CASE WHEN m.tipo IN (1,3) AND m.campusIngreso = 5  THEN  m.cantidad
                             WHEN m.tipo IN (2,3) AND m.campusEgreso  = 5  THEN -m.cantidad
                             ELSE 0 END
                    ), 0)) AS tepeji,

                    GREATEST(0, IFNULL(SUM(
                        CASE WHEN m.tipo IN (1,3) AND m.campusIngreso = 4  THEN  m.cantidad
                             WHEN m.tipo IN (2,3) AND m.campusEgreso  = 4  THEN -m.cantidad
                             ELSE 0 END
                    ), 0)) AS atlacomulco,

                    GREATEST(0, IFNULL(SUM(
                        CASE WHEN m.tipo IN (1,3) AND m.campusIngreso = 6  THEN  m.cantidad
                             WHEN m.tipo IN (2,3) AND m.campusEgreso  = 6  THEN -m.cantidad
                             ELSE 0 END
                    ), 0)) AS nopala,

                    GREATEST(0, IFNULL(SUM(
                        CASE WHEN m.tipo IN (1,3) AND m.campusIngreso = 7  THEN  m.cantidad
                             WHEN m.tipo IN (2,3) AND m.campusEgreso  = 7  THEN -m.cantidad
                             ELSE 0 END
                    ), 0)) AS enlinea,

                    GREATEST(0, IFNULL(SUM(
                        CASE WHEN m.tipo IN (1,3) AND m.campusIngreso = 8  THEN  m.cantidad
                             WHEN m.tipo IN (2,3) AND m.campusEgreso  = 8  THEN -m.cantidad
                             ELSE 0 END
                    ), 0)) AS corporativo,

                    GREATEST(0, IFNULL(SUM(
                        CASE WHEN m.tipo IN (1,3) THEN  m.cantidad
                             WHEN m.tipo = 2      THEN -m.cantidad
                             ELSE 0 END
                    ), 0)) AS total

                FROM inventario i
                LEFT JOIN inventario_movimientos m ON m.articulo = i.id
                WHERE i.status = 1
                GROUP BY i.id, i.codigo, i.nombre, i.medida
                ORDER BY i.id ASC
            ");
            $stmt->execute();
            $articulos = $stmt->fetchAll(PDO::FETCH_ASSOC);
            ?>

            <div class="container-fluid">

                <!-- Título + filtros -->
                <div class="d-flex flex-wrap align-items-center justify-content-between mb-4 gap-2">
                    <h1 class="h3 mb-0 text-gray-800">
                        <i class="fa-solid fa-chart-column mr-2" style="color:#4e73df"></i>
                        Reportes de Inventario
                    </h1>
                    <div class="d-flex align-items-center flex-wrap" style="gap:6px;">
                        <label class="mb-0 small font-weight-bold text-gray-600">Campus:</label>
                        <select id="fCampus" class="form-control form-control-sm" style="width:160px" onchange="actualizarTodo()">
                            <option value="">Todos</option>
                            <option value="1">San Juan del Río</option>
                            <option value="13">SJR 5 de Mayo</option>
                            <option value="2">Aculco</option>
                            <option value="3">Tecámac</option>
                            <option value="5">Tepeji del Río</option>
                            <option value="4">Atlacomulco</option>
                            <option value="6">Nopala</option>
                            <option value="7">En Línea</option>
                            <option value="8">Corporativo</option>
                        </select>
                        <label class="mb-0 small font-weight-bold text-gray-600 ml-2">Período:</label>
                        <input type="date" id="fDesde" class="form-control form-control-sm" style="width:140px">
                        <span class="text-muted">—</span>
                        <input type="date" id="fHasta" class="form-control form-control-sm" style="width:140px">
                        <button onclick="actualizarTodo()" class="btn btn-sm btn-primary">
                            <i class="fas fa-sync-alt mr-1"></i>Actualizar
                        </button>
                    </div>
                </div>

                <!-- ── KPI Cards ─────────────────────────────────────────── -->
                <div class="row mb-4">
                    <div class="col-xl-3 col-md-6 mb-3">
                        <div class="card border-left-primary shadow h-100 py-2">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Artículos activos</div>
                                        <div class="h4 mb-0 font-weight-bold text-gray-800" id="kpiArticulos">
                                            <span class="spinner-border spinner-border-sm text-primary"></span>
                                        </div>
                                        <div class="text-xs text-muted mt-1">Ver en la tabla de abajo</div>
                                    </div>
                                    <div class="col-auto"><i class="fas fa-boxes fa-2x text-gray-300"></i></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-md-6 mb-3">
                        <div class="card border-left-success shadow h-100 py-2">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Stock total (todas las sedes)</div>
                                        <div class="h4 mb-0 font-weight-bold text-gray-800" id="kpiStock">
                                            <span class="spinner-border spinner-border-sm text-success"></span>
                                        </div>
                                        <div class="text-xs text-muted mt-1">Ver gráfica de barras</div>
                                    </div>
                                    <div class="col-auto"><i class="fas fa-layer-group fa-2x text-gray-300"></i></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-md-6 mb-3">
                        <div class="card border-left-info shadow h-100 py-2"
                             onclick="verMovimientosMes(-1)"
                             style="cursor:pointer;" title="Ver detalle de movimientos de este mes">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Movimientos este mes</div>
                                        <div class="h4 mb-0 font-weight-bold text-gray-800" id="kpiMovimientos">
                                            <span class="spinner-border spinner-border-sm text-info"></span>
                                        </div>
                                        <div class="text-xs text-info mt-1">
                                            <i class="fas fa-mouse-pointer fa-xs"></i> Clic para ver detalle
                                        </div>
                                    </div>
                                    <div class="col-auto"><i class="fas fa-exchange-alt fa-2x text-gray-300"></i></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-md-6 mb-3">
                        <div class="card border-left-warning shadow h-100 py-2"
                             onclick="verMovimientosMes(2)"
                             style="cursor:pointer;" title="Ver detalle de egresos de este mes">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Egresos este mes</div>
                                        <div class="h4 mb-0 font-weight-bold text-gray-800" id="kpiEgresos">
                                            <span class="spinner-border spinner-border-sm text-warning"></span>
                                        </div>
                                        <div class="text-xs text-warning mt-1">
                                            <i class="fas fa-mouse-pointer fa-xs"></i> Clic para ver a quién se entregó
                                        </div>
                                    </div>
                                    <div class="col-auto"><i class="fas fa-arrow-circle-down fa-2x text-gray-300"></i></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- ── Gráficas fila 1 ────────────────────────────────────── -->
                <div class="row mb-4">
                    <div class="col-lg-8 mb-3">
                        <div class="card shadow h-100">
                            <div class="card-header py-3 d-flex align-items-center justify-content-between">
                                <h6 class="m-0 font-weight-bold text-primary">
                                    <i class="fas fa-chart-bar mr-1"></i>Stock actual por campus
                                </h6>
                            </div>
                            <div class="card-body">
                                <canvas id="chartCampus" height="120"></canvas>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 mb-3">
                        <div class="card shadow h-100">
                            <div class="card-header py-3">
                                <h6 class="m-0 font-weight-bold text-primary">
                                    <i class="fas fa-chart-pie mr-1"></i>Tipos de movimiento
                                    <small class="text-muted font-weight-normal d-block" style="font-size:11px">según período seleccionado</small>
                                </h6>
                            </div>
                            <div class="card-body d-flex align-items-center justify-content-center">
                                <canvas id="chartTipos" style="max-width:280px;max-height:280px;"></canvas>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- ── Gráfica tendencia ──────────────────────────────────── -->
                <div class="row mb-4">
                    <div class="col-12">
                        <div class="card shadow">
                            <div class="card-header py-3">
                                <h6 class="m-0 font-weight-bold text-primary">
                                    <i class="fas fa-chart-line mr-1"></i>Tendencia de movimientos por mes
                                    <small class="text-muted font-weight-normal ml-1">(según período)</small>
                                </h6>
                            </div>
                            <div class="card-body">
                                <canvas id="chartTendencia" height="70"></canvas>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- ── Tabla de artículos ─────────────────────────────────── -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">
                            <i class="fa-solid fa-table-list mr-1"></i>Artículos — stock por campus
                            <small class="text-muted font-weight-normal ml-2">
                                <i class="fas fa-mouse-pointer fa-xs"></i> Clic en fila para ver historial completo
                            </small>
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="tReportes" class="table table-striped table-bordered table-hover nowrap" style="width:100%;">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Código</th>
                                        <th>Nombre</th>
                                        <th title="San Juan del Río">SJR</th>
                                        <th title="San Juan 5 de Mayo">SJR5</th>
                                        <th title="Aculco">ACU</th>
                                        <th title="Tecámac">TEC</th>
                                        <th title="Tepeji del Río">TEP</th>
                                        <th title="Atlacomulco">ATL</th>
                                        <th title="Nopala">NOP</th>
                                        <th title="En Línea">LIN</th>
                                        <th title="Corporativo">COR</th>
                                        <th>TOTAL</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($articulos as $row): ?>
                                    <tr onclick="verHistorial(<?= $row['id'] ?>)"
                                        style="cursor:pointer;"
                                        title="Ver historial de <?= htmlspecialchars($row['nombre']) ?>">
                                        <td><?= $row['id'] ?></td>
                                        <td><?= htmlspecialchars($row['codigo']) ?></td>
                                        <td><?= htmlspecialchars($row['nombre']) ?></td>
                                        <td class="text-center"><?= $row['sanjuan'] ?></td>
                                        <td class="text-center"><?= $row['sanjuan5'] ?></td>
                                        <td class="text-center"><?= $row['aculco'] ?></td>
                                        <td class="text-center"><?= $row['tecamac'] ?></td>
                                        <td class="text-center"><?= $row['tepeji'] ?></td>
                                        <td class="text-center"><?= $row['atlacomulco'] ?></td>
                                        <td class="text-center"><?= $row['nopala'] ?></td>
                                        <td class="text-center"><?= $row['enlinea'] ?></td>
                                        <td class="text-center"><?= $row['corporativo'] ?></td>
                                        <td class="text-center font-weight-bold"><?= $row['total'] ?></td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                                <tfoot>
                                    <tr class="font-weight-bold bg-light">
                                        <td colspan="3" class="text-right">TOTAL</td>
                                        <?php
                                        $cols = ['sanjuan','sanjuan5','aculco','tecamac','tepeji','atlacomulco','nopala','enlinea','corporativo','total'];
                                        foreach ($cols as $c) {
                                            echo '<td class="text-center">'.array_sum(array_column($articulos,$c)).'</td>';
                                        }
                                        ?>
                                    </tr>
                                </tfoot>
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

<!-- Modal historial de artículo -->
<div class="modal fade" id="modalHistorial" tabindex="-1" role="dialog" data-backdrop="static">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content" id="contenidoHistorial"></div>
    </div>
</div>

<?php include("include/scripts.php"); ?>
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.3/dist/chart.umd.min.js"></script>
<script src="js/reportes_inventario.js"></script>
<script>
var tReportes = new DataTable('#tReportes', {
    responsive: true,
    language: {
        "decimal": "", "emptyTable": "No hay información",
        "info": "Mostrando _START_ a _END_ de _TOTAL_ entradas",
        "infoEmpty": "Mostrando 0 a 0 de 0 entradas",
        "infoFiltered": "(filtrado de _MAX_ total)", "thousands": ",",
        "lengthMenu": "Mostrar _MENU_ entradas", "loadingRecords": "Cargando...",
        "processing": "Procesando...", "search": "Buscar:", "zeroRecords": "Sin resultados"
    },
    lengthMenu: [[15, 25, 50, -1], ['15', '25', '50', 'Todos']],
    layout: {
        topStart: { buttons: ['colvis', 'pageLength'] },
        topEnd: ['search'],
        bottomStart: [{
            buttons: [
                { extend: 'excel',   title: 'Inventario-Reportes', className: 'btn-sm',
                  exportOptions: { columns: ':visible' } },
                { extend: 'pdfHtml5', title: 'Inventario-Reportes', className: 'btn-sm',
                  orientation: 'landscape', pageSize: 'A4',
                  exportOptions: { columns: ':visible' } },
                { extend: 'print', className: 'btn-sm',
                  exportOptions: { columns: ':visible' } }
            ]
        }, 'info'],
        bottomEnd: 'paging'
    }
});

$("#tReportes_wrapper").removeClass("form-inline");
$("#tReportes_wrapper").addClass("w-100");
</script>
</body>
</html>