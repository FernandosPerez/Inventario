<?php
include("../include/conn.php");
session_start();
$sesion = explode("|", $_SESSION["usuario"]);

date_default_timezone_set('America/Mexico_City');

$campusLabel = [
    0=>'—', 1=>'San Juan', 2=>'Aculco', 3=>'Tecamac', 4=>'Atlacomulco',
    5=>'Tepeji', 6=>'Nopala', 7=>'En Línea', 8=>'Corporativo', 13=>'San Juan 5'
];
$badgeClass = [0=>'success',1=>'primary',2=>'warning',3=>'info',4=>'secondary',5=>'danger'];
$tipoNombre = [0=>'Alta',1=>'Ingreso',2=>'Egreso',3=>'Transferencia',4=>'Actualización',5=>'Baja'];

// Reutilizable: genera una fila de movimiento para el modal
function rowMovimiento($r, $campusLabel, $badgeClass, $tipoNombre, $mostrarArticulo = false) {
    $ti    = (int)$r['tipo'];
    $badge = $badgeClass[$ti] ?? 'secondary';
    $tnomb = $tipoNombre[$ti] ?? '?';

    $ciIn  = $campusLabel[(int)$r['campusIngreso']]  ?? '—';
    $ciOut = $campusLabel[(int)$r['campusEgreso']]   ?? '—';

    $campus = '—';
    if ($ti === 1) $campus = $ciIn;
    elseif ($ti === 2) $campus = $ciOut;
    elseif ($ti === 3) $campus = $ciOut.' → '.$ciIn;

    $receptor = trim($r['receptor'] ?? '');
    $asignado = '';
    if ($ti === 2 && $receptor && $receptor !== '  ') {
        $asignado = '<strong>'.htmlspecialchars($receptor).'</strong>';
        if (!empty($r['comentario'])) $asignado .= '<br><small class="text-muted">'.htmlspecialchars($r['comentario']).'</small>';
    } elseif (!empty($r['comentario'])) {
        $asignado = '<small>'.htmlspecialchars($r['comentario']).'</small>';
    }

    $artCol = $mostrarArticulo
        ? '<td class="small text-nowrap"><strong>'.htmlspecialchars($r['codigo'] ?? '').'</strong><br><small>'.htmlspecialchars($r['articulo_nombre'] ?? '').'</small></td>'
        : '';

    return '<tr>
        <td class="text-nowrap small">'.$r['hora'].'</td>
        '.$artCol.'
        <td><span class="badge badge-'.$badge.'">'.$tnomb.'</span></td>
        <td class="small">'.htmlspecialchars($r['quien'] ?? '—').'</td>
        <td class="text-center font-weight-bold">'.$r['cantidad'].'</td>
        <td class="small text-nowrap">'.$campus.'</td>
        <td class="small">'.$asignado.'</td>
    </tr>';
}

switch ((int)$_REQUEST['op']) {

    case 1: // KPIs del mes actual
        $a = $dbconn->query("SELECT COUNT(*) FROM inventario WHERE status=1")->fetchColumn();
        $s = $dbconn->query("SELECT IFNULL(SUM(sanjuan+sanjuan5+aculco+tecamac+tepeji+atlacomulco+nopala+enlinea+corporativo),0) FROM inventario WHERE status=1")->fetchColumn();
        $m = $dbconn->query("SELECT COUNT(*) FROM inventario_movimientos WHERE MONTH(hora)=MONTH(NOW()) AND YEAR(hora)=YEAR(NOW())")->fetchColumn();
        $e = $dbconn->query("SELECT COUNT(*) FROM inventario_movimientos WHERE tipo=2 AND MONTH(hora)=MONTH(NOW()) AND YEAR(hora)=YEAR(NOW())")->fetchColumn();
        $mes = date('F Y'); // nombre del mes actual para mostrar en modales
        echo json_encode(['articulos'=>(int)$a,'stock'=>(int)$s,'movimientos'=>(int)$m,'egresos'=>(int)$e,'mes'=>$mes]);
        break;

    case 2: // Stock por campus (bar chart)
        $r = $dbconn->query("SELECT
            IFNULL(SUM(sanjuan),0) sanjuan, IFNULL(SUM(sanjuan5),0) sanjuan5,
            IFNULL(SUM(aculco),0) aculco,   IFNULL(SUM(tecamac),0) tecamac,
            IFNULL(SUM(tepeji),0) tepeji,   IFNULL(SUM(atlacomulco),0) atlacomulco,
            IFNULL(SUM(nopala),0) nopala,   IFNULL(SUM(enlinea),0) enlinea,
            IFNULL(SUM(corporativo),0) corporativo
            FROM inventario WHERE status=1")->fetch(PDO::FETCH_ASSOC);
        echo json_encode($r);
        break;

    case 3: // Tipos de movimiento (doughnut) — usa rango de fechas
        $desde = !empty($_POST['desde']) ? $_POST['desde'] : date('Y-01-01');
        $hasta = !empty($_POST['hasta']) ? $_POST['hasta'] : date('Y-m-d');
        $stmt  = $dbconn->prepare("SELECT tipo, COUNT(*) as total
            FROM inventario_movimientos
            WHERE hora >= CONCAT(?,' 00:00:00') AND hora <= CONCAT(?,' 23:59:59')
            GROUP BY tipo ORDER BY tipo");
        $stmt->execute([$desde, $hasta]);
        echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
        break;

    case 4: // Tendencia mensual (line chart) — usa rango de fechas
        $desde = !empty($_POST['desde']) ? $_POST['desde'] : date('Y-01-01');
        $hasta = !empty($_POST['hasta']) ? $_POST['hasta'] : date('Y-m-d');
        $stmt  = $dbconn->prepare("SELECT DATE_FORMAT(hora,'%Y-%m') mes, tipo, COUNT(*) total
            FROM inventario_movimientos
            WHERE hora >= CONCAT(?,' 00:00:00') AND hora <= CONCAT(?,' 23:59:59')
            GROUP BY mes, tipo ORDER BY mes ASC");
        $stmt->execute([$desde, $hasta]);
        echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
        break;

    case 5: // Historial completo de un artículo
        $articulo = (int)$_POST['articulo'];

        $stmtA = $dbconn->prepare("SELECT * FROM inventario WHERE id=?");
        $stmtA->execute([$articulo]);
        $art = $stmtA->fetch(PDO::FETCH_ASSOC);

        $stmt = $dbconn->prepare("
            SELECT im.*,
                CONCAT(u.nombre,' ',u.apellidoP,' ',u.apellidoM) AS quien,
                CONCAT(uf.nombre,' ',uf.apellidoP,' ',uf.apellidoM) AS receptor
            FROM inventario_movimientos im
            LEFT JOIN usuarios u  ON u.id = im.usuario
            LEFT JOIN usuarios uf ON uf.id = im.usuario_final
            WHERE im.articulo = ?
            ORDER BY im.hora DESC");
        $stmt->execute([$articulo]);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $foto = '';
        if (!empty($art['foto'])) {
            $ts   = @filemtime(__DIR__."/../img/categorias/inventario/".$art['foto']) ?: time();
            $foto = '<img src="img/categorias/inventario/'.htmlspecialchars($art['foto']).'?v='.$ts.'"
                         style="max-height:70px;border-radius:6px;object-fit:contain;" class="mr-3 d-none d-sm-block">';
        }

        $html = '
        <div class="modal-header bg-dark text-white py-3">
            <div class="d-flex align-items-center">
                '.$foto.'
                <div>
                    <h5 class="modal-title mb-0 text-white">'.htmlspecialchars($art['nombre']).'</h5>
                    <small class="text-white-50">'.htmlspecialchars($art['codigo']).' &bull; '.count($rows).' movimientos en total</small>
                </div>
            </div>
            <button type="button" class="close text-white ml-auto" data-dismiss="modal"><span>&times;</span></button>
        </div>
        <div class="modal-body p-0">
          <div style="overflow-y:auto;max-height:65vh;">
            <table class="table table-sm table-hover table-striped mb-0">
              <thead class="thead-dark" style="position:sticky;top:0;z-index:1;">
                <tr>
                  <th style="min-width:140px">Fecha / hora</th>
                  <th>Tipo</th>
                  <th style="min-width:150px">Responsable</th>
                  <th class="text-center">Cant.</th>
                  <th>Campus</th>
                  <th style="min-width:170px">Asignado a / Nota</th>
                </tr>
              </thead>
              <tbody>';

        foreach ($rows as $r) {
            $html .= rowMovimiento($r, $campusLabel, $badgeClass, $tipoNombre, false);
        }

        if (!$rows) {
            $html .= '<tr><td colspan="6" class="text-center text-muted py-5">Sin movimientos registrados</td></tr>';
        }

        $html .= '</tbody></table></div></div>
        <div class="modal-footer py-2">
            <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Cerrar</button>
        </div>';
        echo $html;
        break;

    case 6: // Movimientos del mes actual (para KPI cards)
        // tipo: -1 = todos, 0-5 = filtro específico
        $tipo = isset($_POST['tipo']) ? (int)$_POST['tipo'] : -1;

        $filtroTipo = $tipo >= 0 ? "AND im.tipo = $tipo" : '';
        $stmt = $dbconn->prepare("
            SELECT im.*,
                inv.codigo,
                inv.nombre AS articulo_nombre,
                CONCAT(u.nombre,' ',u.apellidoP,' ',u.apellidoM) AS quien,
                CONCAT(uf.nombre,' ',uf.apellidoP,' ',uf.apellidoM) AS receptor
            FROM inventario_movimientos im
            LEFT JOIN inventario inv ON inv.id = im.articulo
            LEFT JOIN usuarios u  ON u.id = im.usuario
            LEFT JOIN usuarios uf ON uf.id = im.usuario_final
            WHERE MONTH(im.hora)=MONTH(NOW()) AND YEAR(im.hora)=YEAR(NOW())
            $filtroTipo
            ORDER BY im.hora DESC");
        $stmt->execute();
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $titulos = [
            -1 => 'Todos los movimientos de '.date('F Y'),
             2 => 'Egresos de '.date('F Y'),
        ];
        $titulo = $titulos[$tipo] ?? 'Movimientos de '.date('F Y');

        $html = '
        <div class="modal-header bg-dark text-white py-3">
            <div>
                <h5 class="modal-title mb-0 text-white"><i class="fas fa-exchange-alt mr-2"></i>'.htmlspecialchars($titulo).'</h5>
                <small class="text-white-50">'.count($rows).' registro(s)</small>
            </div>
            <button type="button" class="close text-white ml-auto" data-dismiss="modal"><span>&times;</span></button>
        </div>
        <div class="modal-body p-0">
          <div style="overflow-y:auto;max-height:65vh;">
            <table class="table table-sm table-hover table-striped mb-0">
              <thead class="thead-dark" style="position:sticky;top:0;z-index:1;">
                <tr>
                  <th style="min-width:140px">Fecha / hora</th>
                  <th style="min-width:160px">Artículo</th>
                  <th>Tipo</th>
                  <th style="min-width:150px">Responsable</th>
                  <th class="text-center">Cant.</th>
                  <th>Campus</th>
                  <th style="min-width:170px">Asignado a / Nota</th>
                </tr>
              </thead>
              <tbody>';

        foreach ($rows as $r) {
            $html .= rowMovimiento($r, $campusLabel, $badgeClass, $tipoNombre, true);
        }

        if (!$rows) {
            $html .= '<tr><td colspan="7" class="text-center text-muted py-5">Sin movimientos este mes</td></tr>';
        }

        $html .= '</tbody></table></div></div>
        <div class="modal-footer py-2">
            <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Cerrar</button>
        </div>';
        echo $html;
        break;
}
?>
