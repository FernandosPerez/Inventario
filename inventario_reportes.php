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

            $stmt = $dbconn->prepare("SELECT
                id, codigo, nombre, medida,
                IFNULL(sanjuan,0)      sanjuan,
                IFNULL(sanjuan5,0)     sanjuan5,
                IFNULL(aculco,0)       aculco,
                IFNULL(tecamac,0)      tecamac,
                IFNULL(tepeji,0)       tepeji,
                IFNULL(atlacomulco,0)  atlacomulco,
                IFNULL(nopala,0)       nopala,
                IFNULL(enlinea,0)      enlinea,
                IFNULL(corporativo,0)  corporativo,
                IFNULL(sanjuan+sanjuan5+aculco+tecamac+tepeji+atlacomulco+nopala+enlinea+corporativo,0) AS total
                FROM inventario WHERE status=1 ORDER BY id ASC");
            $stmt->execute();
            $articulos = $stmt->fetchAll(PDO::FETCH_ASSOC);
            ?>

            <div class="container-fluid">

                <!-- Título + filtro de fechas -->
                <div class="d-flex flex-wrap align-items-center justify-content-between mb-4 gap-2">
                    <h1 class="h3 mb-0 text-gray-800">
                        <i class="fa-solid fa-chart-column mr-2" style="color:#4e73df"></i>
                        Reportes de Inventario
                    </h1>
                    <div class="d-flex align-items-center flex-wrap" style="gap:6px;">
                        <label class="mb-0 small font-weight-bold text-gray-600">Período:</label>
                        <input type="date" id="fDesde" class="form-control form-control-sm" style="width:140px">
                        <span class="text-muted">—</span>
                        <input type="date" id="fHasta" class="form-control form-control-sm" style="width:140px">
                        <button onclick="actualizarGraficas()" class="btn btn-sm btn-primary">
                            <i class="fas fa-sync-alt mr-1"></i>Actualizar
                        </button>
                    </div>
                </div>

                <!-- ── KPI Cards ─────────────────────────────────────────── -->
                <div class="row mb-4">
                    <!-- Artículos activos (no clickable, el dato ya está en la tabla) -->
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
                    <!-- Stock total (no clickable, la gráfica de barras lo muestra) -->
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
                    <!-- Movimientos del mes → abre modal con detalle -->
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
                    <!-- Egresos del mes → abre modal filtrado a tipo=2 -->
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
                                        <th title="San Juan">SJR</th>
                                        <th title="San Juan 5">SJR5</th>
                                        <th title="Aculco">ACU</th>
                                        <th title="Tecamac">TEC</th>
                                        <th title="Tepeji">TEP</th>
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

        <footer class="sticky-footer bg-white">
            <div class="container my-auto">
                <div class="copyright text-center my-auto">
                    <span style="color:#858796">Copyright &copy; SECUIEP 2024</span>
                </div>
            </div>
        </footer>
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
