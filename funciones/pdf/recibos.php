<?php
include("../../include/conn.php");
session_start();
$sesion = explode("|", $_SESSION["usuario"]);
require('../../librerias/fpdf/diag.php');

// ── Paleta base ───────────────────────────────────────────
define('AZ_OSC_R', 41);  define('AZ_OSC_G', 98);  define('AZ_OSC_B', 180);
define('AZ_MED_R', 99);  define('AZ_MED_G', 155); define('AZ_MED_B', 230);
define('ORO_R', 255);    define('ORO_G', 200);    define('ORO_B', 50);
define('GRS_R', 248);    define('GRS_G', 250);    define('GRS_B', 255);
define('LIN_R', 220);    define('LIN_G', 228);    define('LIN_B', 242);
define('IMG_BASE', '../../img/categorias/inventario/');

class PDF_Recibo extends PDF_Diag {

    public $finicio      = '';
    public $ffin         = '';
    public $emisor       = '';
    public $tipoActual   = 'INGRESO';
    public $receptorActual = '';

    // ── Color por tipo ────────────────────────────────────
    function colorByTipo($tipo, $parte = 'fill') {
        $mapa = [
            'INGRESO'              => [[39, 174, 96],   [225, 248, 235]],
            'EGRESO'               => [[231, 76, 60],   [255, 235, 233]],
            'TRANSFERENCIA'        => [[41, 128, 185],  [232, 244, 253]],
            'ACTUALIZACION'        => [[243, 156, 18],  [254, 245, 225]],
            'BAJA'                 => [[127, 140, 141], [240, 241, 241]],
            'REGISTRO DE ARTICULO' => [[142, 68, 173],  [245, 235, 251]],
        ];
        $key = strtoupper(trim($tipo));
        $key = str_replace(
            ['ACTUALIZACIÓN', 'REGISTRO DE ARTÍCULO'],
            ['ACTUALIZACION', 'REGISTRO DE ARTICULO'],
            $key
        );
        $col = isset($mapa[$key]) ? $mapa[$key] : [[99, 110, 114], [245, 246, 250]];
        return $parte === 'light' ? $col[1] : $col[0];
    }

    function setFillByTipo($tipo, $light = false) {
        $c = $this->colorByTipo($tipo, $light ? 'light' : 'fill');
        $this->SetFillColor($c[0], $c[1], $c[2]);
    }

    function setTextByTipo($tipo) {
        $c = $this->colorByTipo($tipo, 'fill');
        $this->SetTextColor($c[0], $c[1], $c[2]);
    }

    // ── RoundedRect ───────────────────────────────────────
    function RoundedRect($x, $y, $w, $h, $r, $style = '') {
        $s = strtoupper($style);
        if ($s === 'F') $op = 'f';
        elseif ($s === 'FD' || $s === 'DF') $op = 'B';
        else $op = 'S';
        $arc = 4/3 * (sqrt(2) - 1);
        $hp = $this->h; $k = $this->k;
        $this->_out(sprintf('%.2F %.2F m', ($x+$r)*$k, ($hp-$y)*$k));
        $this->_out(sprintf('%.2F %.2F l', ($x+$w-$r)*$k, ($hp-$y)*$k));
        $this->_Arc($x+$w-$r+$r*$arc, $y, $x+$w, $y+$r-$r*$arc, $x+$w, $y+$r);
        $this->_out(sprintf('%.2F %.2F l', ($x+$w)*$k, ($hp-($y+$h-$r))*$k));
        $this->_Arc($x+$w, $y+$h-$r+$r*$arc, $x+$w-$r+$r*$arc, $y+$h, $x+$w-$r, $y+$h);
        $this->_out(sprintf('%.2F %.2F l', ($x+$r)*$k, ($hp-($y+$h))*$k));
        $this->_Arc($x+$r-$r*$arc, $y+$h, $x, $y+$h-$r+$r*$arc, $x, $y+$h-$r);
        $this->_out(sprintf('%.2F %.2F l', $x*$k, ($hp-($y+$r))*$k));
        $this->_Arc($x, $y+$r-$r*$arc, $x+$r-$r*$arc, $y, $x+$r, $y);
        $this->_out($op);
    }

    function _Arc($x1, $y1, $x2, $y2, $x3, $y3) {
        $h = $this->h;
        $this->_out(sprintf('%.2F %.2F %.2F %.2F %.2F %.2F c',
            $x1*$this->k, ($h-$y1)*$this->k,
            $x2*$this->k, ($h-$y2)*$this->k,
            $x3*$this->k, ($h-$y3)*$this->k));
    }

    // ── HEADER ───────────────────────────────────────────
    function Header() {
        $pw = $this->w;

        $this->SetFillColor(AZ_OSC_R, AZ_OSC_G, AZ_OSC_B);
        $this->Rect(0, 0, $pw, 34, 'F');

        $this->SetFillColor(ORO_R, ORO_G, ORO_B);
        $this->Rect(0, 34, $pw, 2.5, 'F');

        $this->Image('../../img/logo-secuiep.png', 6, 5, 26);

        $this->SetTextColor(255, 255, 255);
        $this->SetFont('Helvetica', 'B', 15);
        $this->SetXY(38, 7);
        $this->Cell(100, 8, 'Recibo de ' . $this->tipoActual, 0, 1, 'L');

        $this->SetFont('Helvetica', '', 8);
        $this->SetTextColor(ORO_R, ORO_G, ORO_B);
        $this->SetXY(38, 16);
        $this->Cell(100, 5, 'Periodo: ' . $this->finicio . '  -  ' . $this->ffin, 0, 0, 'L');

        $this->SetTextColor(210, 228, 255);
        $this->SetXY(38, 23);
        $this->Cell(100, 5, 'Emitido por: ' . $this->emisor, 0, 0, 'L');

        // Badge tipo con color dinámico
        $this->setFillByTipo($this->tipoActual);
        $this->RoundedRect($pw - 52, 8, 45, 20, 3, 'F');
        $this->SetTextColor(255, 255, 255);
        $this->SetFont('Helvetica', 'B', 6.5);
        $this->SetXY($pw - 52, 10);
        $this->Cell(45, 5, $this->tipoActual, 0, 1, 'C');
        $this->SetFont('Helvetica', '', 6);
        $this->SetXY($pw - 52, 16);
        $this->Cell(45, 4, 'SECUIEP', 0, 0, 'C');
        $this->SetFont('Helvetica', 'B', 6);
        $this->SetXY($pw - 52, 21);
        $this->Cell(45, 4, date('d/m/Y'), 0, 0, 'C');

        $this->SetTextColor(0, 0, 0);
        $this->SetY(42);
    }

    // ── FOOTER ───────────────────────────────────────────
    function Footer() {
        $pw = $this->w;
        $this->SetY(-15);
        $yf = $this->GetY();

        $this->SetFillColor(AZ_OSC_R, AZ_OSC_G, AZ_OSC_B);
        $this->Rect(0, $yf, $pw, 20, 'F');
        $this->SetFillColor(ORO_R, ORO_G, ORO_B);
        $this->Rect(0, $yf, $pw, 1.5, 'F');

        $this->SetY($yf + 4);
        $this->SetTextColor(210, 228, 255);
        $this->SetFont('Helvetica', 'I', 7.5);
        $this->Cell(0, 5,
            'SECUIEP  -  Generado el ' . date('d/m/Y  H:i') .
            '     Pag. ' . $this->PageNo(), 0, 0, 'C');
        $this->SetTextColor(0, 0, 0);
    }

    // ── CAJA INFO DEL GRUPO ───────────────────────────────
    function InfoBox($label, $valor, $fecha, $tipo) {
        $pw = $this->w;
        $y  = $this->GetY();

        $this->setFillByTipo($tipo, true);
        $c = $this->colorByTipo($tipo);
        $this->SetDrawColor($c[0], $c[1], $c[2]);
        $this->SetLineWidth(0.5);
        $this->RoundedRect(10, $y, $pw - 20, 18, 3, 'FD');

        $this->setTextByTipo($tipo);
        $this->SetFont('Helvetica', 'B', 7);
        $this->SetXY(14, $y + 3);
        $this->Cell(30, 4, strtoupper($label) . ($label ? ':' : ''), 0, 0, 'L');

        $this->SetTextColor(AZ_OSC_R, AZ_OSC_G, AZ_OSC_B);
        $this->SetFont('Helvetica', 'B', 11);
        $this->SetXY(14, $y + 8);
        $this->Cell(130, 6, utf8_decode($valor), 0, 0, 'L');

        $this->SetTextColor(120, 130, 150);
        $this->SetFont('Helvetica', '', 8);
        $this->SetXY($pw - 70, $y + 3);
        $this->Cell(58, 4, 'Fecha:', 0, 0, 'R');
        $this->SetFont('Helvetica', 'B', 8);
        $this->SetXY($pw - 70, $y + 8);
        $this->Cell(58, 5, $fecha, 0, 0, 'R');

        $this->SetDrawColor(0, 0, 0);
        $this->SetLineWidth(0.2);
        $this->SetY($y + 22);
    }

    // ── ENCABEZADO TABLA ─────────────────────────────────
    function TablaHeader($tipo) {
        $this->setFillByTipo($tipo);
        $this->SetTextColor(255, 255, 255);
        $this->SetFont('Helvetica', 'B', 8);
        $this->SetX(10);
        $this->Cell(20, 7, 'Foto',     0, 0, 'C', true);
        $this->Cell(90, 7, 'Articulo', 0, 0, 'L', true);
        $this->Cell(25, 7, 'Cantidad', 0, 0, 'C', true);
        $this->Cell(25, 7, 'Unidad',   0, 0, 'C', true);
        $this->Cell(20, 7, 'Hora',     0, 1, 'C', true);
        $this->SetTextColor(0, 0, 0);
    }

    // ── FILA DE ARTÍCULO ─────────────────────────────────
    function TablaFila($row, $fill, $tipo) {
        $rowH = 18;
        $y    = $this->GetY();

        if ($y + $rowH > $this->PageBreakTrigger) {
            $this->AddPage('P', array(210, 297));
            $this->TablaHeader($tipo);
            $y = $this->GetY();
        }

        if ($fill) {
            $this->SetFillColor(GRS_R, GRS_G, GRS_B);
            $this->Rect(10, $y, $this->w - 20, $rowH, 'F');
        }

        $this->SetDrawColor(LIN_R, LIN_G, LIN_B);
        $this->SetLineWidth(0.2);
        $this->Line(10, $y + $rowH, $this->w - 10, $y + $rowH);

        // Imagen
        $fotoRaw = trim($row['foto'] ?? '');
        $imgPath = '';
        if ($fotoRaw !== '') {
            $imgPath = IMG_BASE . $fotoRaw;
        } elseif ((int)($row['area'] ?? 0) === 10) {
            $imgPath = IMG_BASE . 'BIBL.jpeg';
        }
        if ($imgPath && file_exists($imgPath)) {
            $this->Image($imgPath, 12, $y + 2, 14, 14);
        } else {
            $this->SetFillColor(220, 223, 235);
            $this->RoundedRect(12, $y + 2, 14, 14, 2, 'F');
            $this->SetTextColor(180, 185, 200);
            $this->SetFont('Helvetica', 'B', 5);
            $this->SetXY(12, $y + 7);
            $this->Cell(14, 4, 'SIN FOTO', 0, 0, 'C');
        }

        // Nombre artículo
        $this->SetTextColor(AZ_OSC_R, AZ_OSC_G, AZ_OSC_B);
        $this->SetFont('Helvetica', 'B', 8);
        $this->SetXY(30, $y + 3);
        $this->MultiCell(90, 4.5, utf8_decode($row['articuloName']), 0, 'L');

        // Cantidad
        $this->setTextByTipo($tipo);
        $this->SetFont('Helvetica', 'B', 11);
        $this->SetXY(120, $y + 5);
        $this->Cell(25, 7, number_format((float)$row['cantidad'], 1), 0, 0, 'C');

        // Unidad
        $this->SetTextColor(100, 110, 130);
        $this->SetFont('Helvetica', '', 7.5);
        $this->SetXY(145, $y + 5);
        $this->Cell(25, 7, utf8_decode($row['medida']), 0, 0, 'C');

        // Hora
        $this->SetTextColor(150, 160, 175);
        $this->SetFont('Helvetica', '', 6.5);
        $this->SetXY(170, $y + 5);
        $this->Cell(20, 7, substr($row['hora'], 11, 5), 0, 0, 'C');

        $this->SetY($y + $rowH);
        $this->SetDrawColor(0, 0, 0);
        $this->SetTextColor(0, 0, 0);
    }

    // ── TOTALES Y FIRMAS ─────────────────────────────────
    function SeccionFirmas($totalItems, $totalCantidad, $medida, $tipo, $receptor) {
        $pw = $this->w;
        $y  = $this->GetY() + 6;

        if ($y + 45 > $this->PageBreakTrigger) {
            $this->AddPage('P', array(210, 297));
            $y = $this->GetY();
        }

        // Caja totales
        $this->setFillByTipo($tipo, true);
        $this->setTextByTipo($tipo);
        $this->RoundedRect(10, $y, $pw - 20, 14, 3, 'F');
        $this->SetFont('Helvetica', 'B', 8);
        $this->SetXY(14, $y + 3);
        $this->Cell(60, 5, 'Total de articulos: ' . $totalItems, 0, 0, 'L');
        $this->SetFont('Helvetica', 'B', 9);
        $this->SetXY($pw - 80, $y + 3);
        $this->Cell(68, 5,
            'Cantidad total: ' . number_format($totalCantidad, 1) . ' ' . utf8_decode($medida),
            0, 0, 'R');

        // Firmas
        $y += 22;
        $this->SetDrawColor(LIN_R, LIN_G, LIN_B);
        $this->SetLineWidth(0.4);
        $this->SetTextColor(100, 110, 130);

        // Firma emisor siempre
        $this->SetFont('Helvetica', '', 7);
        $this->Line(14, $y + 14, 80, $y + 14);
        $this->SetXY(14, $y + 15);
        $this->Cell(66, 4, 'Firma del emisor', 0, 0, 'C');
        $this->SetFont('Helvetica', 'B', 7);
        $this->SetXY(14, $y + 20);
        $this->Cell(66, 4, utf8_decode($this->emisor), 0, 0, 'C');

        // Firma receptor solo en EGRESO y TRANSFERENCIA
        $tipoUp = strtoupper(trim($tipo));
        if (in_array($tipoUp, array('EGRESO', 'TRANSFERENCIA'))) {
            $this->SetFont('Helvetica', '', 7);
            $this->Line($pw - 80, $y + 14, $pw - 14, $y + 14);
            $this->SetXY($pw - 80, $y + 15);
            $this->Cell(66, 4, 'Firma de recibido', 0, 0, 'C');
            $this->SetFont('Helvetica', 'B', 7);
            $this->SetXY($pw - 80, $y + 20);
            $this->Cell(66, 4, utf8_decode($receptor), 0, 0, 'C');
        }

        $this->SetDrawColor(0, 0, 0);
        $this->SetTextColor(0, 0, 0);
    }
}

// ── Consulta ─────────────────────────────────────────────
$stmt = $dbconn->prepare("
    SELECT i.id, i.articulo, i.cantidad, i.tipo,
           inv.nombre   AS articuloName,
           inv.foto     AS foto,
           inv.area     AS area,
           i.usuario,
           i.usuario_final,
           CONCAT(u.nombre,' ',u.apellidoP,' ',u.apellidoM)   AS emisor,
           CONCAT(us.nombre,' ',us.apellidoP,' ',us.apellidoM) AS receptor,
           inm.medida   AS medida,
           DATE(i.hora) AS fecha,
           i.hora,i.comentario
    FROM inventario_movimientos i
    LEFT JOIN usuarios u             ON u.id   = i.usuario
    LEFT JOIN inventario inv         ON inv.id  = i.articulo
    LEFT JOIN inventario_medidas inm ON inm.id  = inv.medida
    LEFT JOIN usuarios us            ON us.id   = i.usuario_final
    WHERE i.usuario = ?
      AND i.hora BETWEEN ? AND ?
    ORDER BY i.tipo ASC, i.usuario_final ASC, i.hora ASC
");
$stmt->execute([
    $sesion[0],
    $_GET['finicio'] . ' 00:00:00',
    $_GET['ffin']    . ' 23:59:59',
]);
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

// ── Agrupar por tipo ──────────────────────────────────────
$grupos = [];
foreach ($rows as $row) {
    $t = (int)$row['tipo'];

    if ($t === 0) {
        $clave = 'REG_' . $row['fecha'];
        if (!isset($grupos[$clave])) {
            $grupos[$clave] = [
                'tipo'   => 'REGISTRO DE ARTICULO',
                'label'  => '',
                'nombre' => $row['comentario'],
                'fecha'  => $row['fecha'],
                'emisor' => $row['emisor'],
                'medida' => $row['medida'],
                'items'  => [],
            ];
        }
        $grupos[$clave]['items'][] = $row;

    } elseif ($t === 1) {
        $clave = 'ING_' . ($row['usuario_final'] ?? 'x');
        if (!isset($grupos[$clave])) {
            $grupos[$clave] = [
                'tipo'   => 'INGRESO',
                'label'  => 'Ingreso registrado por',
                'nombre' => $row['emisor'] ?? '',
                'fecha'  => $row['fecha'],
                'emisor' => $row['emisor'],
                'medida' => $row['medida'],
                'items'  => [],
            ];
        }
        $grupos[$clave]['items'][] = $row;

    } elseif ($t === 2) {
        $clave = 'EGR_' . ($row['usuario_final'] ?? 'x') . '_' . $row['fecha'];
        if (!isset($grupos[$clave])) {
            $grupos[$clave] = [
                'tipo'   => 'EGRESO',
                'label'  => 'Entregado a',
                'nombre' => $row['receptor'] ?? 'Sin receptor',
                'fecha'  => $row['fecha'],
                'emisor' => $row['emisor'],
                'medida' => $row['medida'],
                'items'  => [],
            ];
        }
        $grupos[$clave]['items'][] = $row;

    } elseif ($t === 3) {
        $clave = 'TRF_' . $row['fecha'];
        if (!isset($grupos[$clave])) {
            $grupos[$clave] = [
                'tipo'   => 'TRANSFERENCIA',
                'label'  => 'Transferencia a',
                'nombre' => $row['receptor'] ?? 'Sin receptor',
                'fecha'  => $row['fecha'],
                'emisor' => $row['emisor'],
                'medida' => $row['medida'],
                'items'  => [],
            ];
        }
        $grupos[$clave]['items'][] = $row;

    } elseif ($t === 4) {
        $clave = 'ACT_' . $row['fecha'];
        if (!isset($grupos[$clave])) {
            $grupos[$clave] = [
                'tipo'   => 'ACTUALIZACION',
                'label'  => 'Actualizacion del articulo',
                'nombre' => $row['comentario'],
                'fecha'  => $row['fecha'],
                'emisor' => $row['emisor'],
                'medida' => $row['medida'],
                'items'  => [],
            ];
        }
        $grupos[$clave]['items'][] = $row;

    } elseif ($t === 5) {
        $clave = 'BAJ_' . $row['fecha'];
        if (!isset($grupos[$clave])) {
            $grupos[$clave] = [
                'tipo'   => 'BAJA',
                'label'  => 'MOTIVO',
                'nombre' => $row['comentario'],
                'fecha'  => $row['fecha'],
                'emisor' => $row['emisor'],
                'medida' => $row['medida'],
                'items'  => [],
            ];
        }
        $grupos[$clave]['items'][] = $row;
    }
}

// ── Generar PDF ───────────────────────────────────────────
$pdf = new PDF_Recibo();
$pdf->finicio = $_GET['finicio'];
$pdf->ffin    = $_GET['ffin'];
$pdf->emisor  = utf8_decode(!empty($rows) ? ($rows[0]['emisor'] ?? '') : ($sesion[3] ?? ''));

$pdf->SetMargins(0, 0, 0);
$pdf->SetAutoPageBreak(true, 20);

if (empty($grupos)) {
    $pdf->tipoActual = 'INGRESO';
    $pdf->AddPage('P', array(210, 297));
    $pdf->SetFont('Helvetica', 'I', 11);
    $pdf->SetTextColor(170, 175, 190);
    $pdf->Ln(20);
    $pdf->Cell(0, 10, 'Sin movimientos en el periodo seleccionado.', 0, 1, 'C');
} else {
    foreach ($grupos as $grupo) {
        $tipo = $grupo['tipo'];

        $pdf->tipoActual     = $tipo;
        $pdf->receptorActual = $grupo['nombre'];
        $pdf->AddPage('P', array(210, 297));

        $pdf->InfoBox(
            $grupo['label'],
            $grupo['nombre'],
            $grupo['fecha'],
            $tipo
        );

        $pdf->TablaHeader($tipo);

        $fill          = false;
        $totalCantidad = 0;
        foreach ($grupo['items'] as $row) {
            $pdf->TablaFila($row, $fill, $tipo);
            $totalCantidad += (float)$row['cantidad'];
            $fill = !$fill;
        }

        $pdf->SeccionFirmas(
            count($grupo['items']),
            $totalCantidad,
            $grupo['medida'],
            $tipo,
            $grupo['nombre']
        );
    }
}

$pdf->Output();
?>