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

// ── Campus filter ────────────────────────────────────────────────────────────
$campusPost = isset($_POST['campus']) && $_POST['campus'] !== '' ? (int)$_POST['campus'] : 0;

$campusCol = [
    1=>'sanjuan', 13=>'sanjuan5', 2=>'aculco', 3=>'tecamac',
    5=>'tepeji', 4=>'atlacomulco', 6=>'nopala', 7=>'enlinea', 8=>'corporativo'
];

function campusMovWhere($campus) {
    if (!$campus) return '';
    $campus = (int)$campus;
    return "AND (campusIngreso = $campus OR campusEgreso = $campus)";
}

// ── Reutilizable: genera fila de movimiento para modal ───────────────────────
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

   case 1: // KPIs
    $a = $dbconn->query("SELECT COUNT(*) FROM inventario WHERE status=1")->fetchColumn();

    // Stock: calculado desde movimientos (igual que el pivot del main)
    if ($campusPost && isset($campusCol[$campusPost])) {
        $stmt = $dbconn->prepare("
            SELECT GREATEST(0, IFNULL(SUM(
                CASE WHEN m.tipo IN (1,3) AND m.campusIngreso = ? THEN  m.cantidad
                     WHEN m.tipo IN (2,3) AND m.campusEgreso  = ? THEN -m.cantidad
                     ELSE 0 END
            ), 0))
            FROM inventario i
            LEFT JOIN inventario_movimientos m ON m.articulo = i.id
            WHERE i.status = 1
        ");
        $stmt->execute([$campusPost, $campusPost]);
        $s = $stmt->fetchColumn();
    } else {
        $stmt = $dbconn->query("
            SELECT GREATEST(0, IFNULL(SUM(
                CASE WHEN m.tipo IN (1,3) THEN  m.cantidad
                     WHEN m.tipo = 2      THEN -m.cantidad
                     ELSE 0 END
            ), 0))
            FROM inventario i
            LEFT JOIN inventario_movimientos m ON m.articulo = i.id
            WHERE i.status = 1
        ");
        $s = $stmt->fetchColumn();
    }

    $wCampus = campusMovWhere($campusPost);
    $m = $dbconn->query("SELECT COUNT(*) FROM inventario_movimientos
                          WHERE MONTH(hora)=MONTH(NOW()) AND YEAR(hora)=YEAR(NOW()) $wCampus")->fetchColumn();

    if ($campusPost) {
        $stmtE = $dbconn->prepare("SELECT COUNT(*) FROM inventario_movimientos
                                    WHERE tipo=2 AND campusEgreso=?
                                    AND MONTH(hora)=MONTH(NOW()) AND YEAR(hora)=YEAR(NOW())");
        $stmtE->execute([$campusPost]);
        $e = $stmtE->fetchColumn();
    } else {
        $e = $dbconn->query("SELECT COUNT(*) FROM inventario_movimientos
                              WHERE tipo=2 AND MONTH(hora)=MONTH(NOW()) AND YEAR(hora)=YEAR(NOW())")->fetchColumn();
    }

    echo json_encode(['articulos'=>(int)$a,'stock'=>(int)$s,'movimientos'=>(int)$m,'egresos'=>(int)$e]);
    break;

case 2: // Stock por campus (bar chart) — pivot desde movimientos
    $stmt = $dbconn->query("
        SELECT
            GREATEST(0, IFNULL(SUM(CASE WHEN m.tipo IN (1,3) AND m.campusIngreso = 1  THEN  m.cantidad
                                        WHEN m.tipo IN (2,3) AND m.campusEgreso  = 1  THEN -m.cantidad ELSE 0 END),0)) AS sanjuan,
            GREATEST(0, IFNULL(SUM(CASE WHEN m.tipo IN (1,3) AND m.campusIngreso = 13 THEN  m.cantidad
                                        WHEN m.tipo IN (2,3) AND m.campusEgreso  = 13 THEN -m.cantidad ELSE 0 END),0)) AS sanjuan5,
            GREATEST(0, IFNULL(SUM(CASE WHEN m.tipo IN (1,3) AND m.campusIngreso = 2  THEN  m.cantidad
                                        WHEN m.tipo IN (2,3) AND m.campusEgreso  = 2  THEN -m.cantidad ELSE 0 END),0)) AS aculco,
            GREATEST(0, IFNULL(SUM(CASE WHEN m.tipo IN (1,3) AND m.campusIngreso = 3  THEN  m.cantidad
                                        WHEN m.tipo IN (2,3) AND m.campusEgreso  = 3  THEN -m.cantidad ELSE 0 END),0)) AS tecamac,
            GREATEST(0, IFNULL(SUM(CASE WHEN m.tipo IN (1,3) AND m.campusIngreso = 5  THEN  m.cantidad
                                        WHEN m.tipo IN (2,3) AND m.campusEgreso  = 5  THEN -m.cantidad ELSE 0 END),0)) AS tepeji,
            GREATEST(0, IFNULL(SUM(CASE WHEN m.tipo IN (1,3) AND m.campusIngreso = 4  THEN  m.cantidad
                                        WHEN m.tipo IN (2,3) AND m.campusEgreso  = 4  THEN -m.cantidad ELSE 0 END),0)) AS atlacomulco,
            GREATEST(0, IFNULL(SUM(CASE WHEN m.tipo IN (1,3) AND m.campusIngreso = 6  THEN  m.cantidad
                                        WHEN m.tipo IN (2,3) AND m.campusEgreso  = 6  THEN -m.cantidad ELSE 0 END),0)) AS nopala,
            GREATEST(0, IFNULL(SUM(CASE WHEN m.tipo IN (1,3) AND m.campusIngreso = 7  THEN  m.cantidad
                                        WHEN m.tipo IN (2,3) AND m.campusEgreso  = 7  THEN -m.cantidad ELSE 0 END),0)) AS enlinea,
            GREATEST(0, IFNULL(SUM(CASE WHEN m.tipo IN (1,3) AND m.campusIngreso = 8  THEN  m.cantidad
                                        WHEN m.tipo IN (2,3) AND m.campusEgreso  = 8  THEN -m.cantidad ELSE 0 END),0)) AS corporativo
        FROM inventario i
        LEFT JOIN inventario_movimientos m ON m.articulo = i.id
        WHERE i.status = 1
    ");
    echo json_encode($stmt->fetch(PDO::FETCH_ASSOC));
    break;

    case 3: // Tipos de movimiento (doughnut)
        $desde   = !empty($_POST['desde']) ? $_POST['desde'] : date('Y-01-01');
        $hasta   = !empty($_POST['hasta']) ? $_POST['hasta'] : date('Y-m-d');
        $wCampus = campusMovWhere($campusPost);
        $stmt    = $dbconn->prepare("SELECT tipo, COUNT(*) as total
            FROM inventario_movimientos
            WHERE hora >= CONCAT(?,' 00:00:00') AND hora <= CONCAT(?,' 23:59:59')
            $wCampus
            GROUP BY tipo ORDER BY tipo");
        $stmt->execute([$desde, $hasta]);
        echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
        break;

    case 4: // Tendencia mensual (line chart)
        $desde   = !empty($_POST['desde']) ? $_POST['desde'] : date('Y-01-01');
        $hasta   = !empty($_POST['hasta']) ? $_POST['hasta'] : date('Y-m-d');
        $wCampus = campusMovWhere($campusPost);
        $stmt    = $dbconn->prepare("SELECT DATE_FORMAT(hora,'%Y-%m') mes, tipo, COUNT(*) total
            FROM inventario_movimientos
            WHERE hora >= CONCAT(?,' 00:00:00') AND hora <= CONCAT(?,' 23:59:59')
            $wCampus
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

    case 6: // Movimientos del mes actual
        $tipo    = isset($_POST['tipo']) ? (int)$_POST['tipo'] : -1;
        $wTipo   = $tipo >= 0 ? "AND im.tipo = $tipo" : '';

        // Para egresos (tipo=2) filtra por campusEgreso, para el resto por cualquiera de los dos
        if ($campusPost) {
            $wCampus = $tipo === 2
                ? "AND im.campusEgreso = $campusPost"
                : "AND (im.campusIngreso = $campusPost OR im.campusEgreso = $campusPost)";
        } else {
            $wCampus = '';
        }

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
            $wTipo $wCampus
            ORDER BY im.hora DESC");
        $stmt->execute();
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $campusNombre = $campusPost && isset($campusLabel[$campusPost]) ? ' — '.$campusLabel[$campusPost] : '';
        $titulos = [
            -1 => 'Todos los movimientos de '.date('F Y').$campusNombre,
             2 => 'Egresos de '.date('F Y').$campusNombre,
        ];
        $titulo = $titulos[$tipo] ?? 'Movimientos de '.date('F Y').$campusNombre;

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