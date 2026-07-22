<?php
include("../../include/conn.php");
session_start();
$sesion = explode("|", $_SESSION["usuario"]);
require('../../librerias/fpdf/diag.php');

// ── Paleta ────────────────────────────────────────────────
define('AZ_OSC_R', 41);  define('AZ_OSC_G', 98);  define('AZ_OSC_B', 180);  // azul medio
define('AZ_MED_R', 99);  define('AZ_MED_G', 155); define('AZ_MED_B', 230);  // azul claro
define('ORO_R', 255);    define('ORO_G', 200);    define('ORO_B', 50);      // amarillo suave
define('VER_R', 39);     define('VER_G', 174);    define('VER_B', 96);      // verde menta
define('ROJ_R', 231);    define('ROJ_G', 76);     define('ROJ_B', 60);      // rojo coral
define('GRS_R', 248);    define('GRS_G', 250);    define('GRS_B', 255);     // gris lavanda
define('LIN_R', 220);    define('LIN_G', 228);    define('LIN_B', 242);     // línea azulada

// ── Ruta base de imágenes de artículos ───────────────────
define('IMG_BASE', '../../img/categorias/inventario/');

class PDF_Recibo extends PDF_Diag {

    public $finicio = '';
    public $ffin    = '';
    public $usuario = '';
    public $total   = 0;

    // ── Rectángulo redondeado ─────────────────────────────
    function RoundedRect($x, $y, $w, $h, $r, $style = '') {
    // Compatibilidad PHP 7.x — sin match()
    $s = strtoupper($style);
    if ($s === 'F') {
        $op = 'f';
    } elseif ($s === 'FD' || $s === 'DF') {
        $op = 'B';
    } else {
        $op = 'S';
    }

    $arc = 4/3 * (sqrt(2) - 1);
    $hp  = $this->h;
    $k   = $this->k;

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

    function _Arc($x1,$y1,$x2,$y2,$x3,$y3) {
        $h = $this->h;
        $this->_out(sprintf('%.2F %.2F %.2F %.2F %.2F %.2F c',
            $x1*$this->k,($h-$y1)*$this->k,
            $x2*$this->k,($h-$y2)*$this->k,
            $x3*$this->k,($h-$y3)*$this->k));
    }

    // ── HEADER ───────────────────────────────────────────
    function Header() {
    $pw = $this->GetPageWidth(); // ← ancho real de la página

    $this->SetFillColor(AZ_OSC_R, AZ_OSC_G, AZ_OSC_B);
    $this->Rect(0, 0, $pw, 34, 'F');

    $this->SetFillColor(ORO_R, ORO_G, ORO_B);
    $this->Rect(0, 34, $pw, 2.5, 'F');

    $this->Image('../../img/logo-secuiep.png', 7, 6, 24);

    $this->SetTextColor(255, 255, 255);
    $this->SetFont('Helvetica', 'B', 17);
    $this->SetXY(36, 7);
    $this->Cell(100, 9, utf8_decode('Historial de Movimientos'), 0, 1, 'L');

    $this->SetFont('Helvetica', '', 8);
    $this->SetTextColor(ORO_R, ORO_G, ORO_B);
    $this->SetXY(36, 17);
    $this->Cell(100, 5, 'Periodo: ' . $this->finicio . '  -  ' . $this->ffin, 0, 0, 'L');

    $this->SetTextColor(210, 228, 255);
    $this->SetXY(36, 23);
    $this->Cell(100, 5, utf8_decode('Usuario: ') . $this->usuario, 0, 0, 'L');

    // Badge total (usa $pw para posicionarlo a la derecha correctamente)
    $this->SetFillColor(255, 255, 255);
    $this->RoundedRect($pw - 52, 10, 45, 14, 3, 'F');
    $this->SetTextColor(AZ_OSC_R, AZ_OSC_G, AZ_OSC_B);
    $this->SetFont('Helvetica', 'B', 16);
    $this->SetXY($pw - 52, 11);
    $this->Cell(45, 7, $this->total, 0, 1, 'C');
    $this->SetFont('Helvetica', '', 6.5);
    $this->SetXY($pw - 52, 18);
    $this->Cell(45, 4, 'movimientos', 0, 0, 'C');

    $this->SetTextColor(0, 0, 0);
    $this->SetY(42);
}

    // ── FOOTER ───────────────────────────────────────────
function Footer() {
    $pw = $this->GetPageWidth();
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

    // ── CARD DE MOVIMIENTO ────────────────────────────────
function MovCard($row) {
    $pw     = $this->w;
    $margin = 12;
    $cardW  = $pw - ($margin * 2);
    $cardH  = 34;
    $x      = $margin;
    $y      = $this->GetY();

    $esEntrega = (int)$row['tipo'] !== 1;

    if ($y + $cardH + 5 > $this->PageBreakTrigger) {
        $this->AddPage('P', array(210, 297));
        $y = $this->GetY();
    }

    // Sombra
    $this->SetFillColor(LIN_R, LIN_G, LIN_B);
    $this->RoundedRect($x + 1.2, $y + 1.8, $cardW, $cardH, 4, 'F');

    // Card blanca
    $this->SetFillColor(255, 255, 255);
    $this->RoundedRect($x, $y, $cardW, $cardH, 4, 'F');

    // Franja lateral
    if ($esEntrega) {
        $this->SetFillColor(ROJ_R, ROJ_G, ROJ_B);
    } else {
        $this->SetFillColor(VER_R, VER_G, VER_B);
    }
    $this->RoundedRect($x, $y, 5, $cardH, 4, 'F');
    $this->Rect($x + 2, $y, 3, $cardH, 'F');

    // ── Imagen ───────────────────────────────────────────
    $imgX  = $x + 9;
    $imgY  = $y + 6;
    $imgSz = 22;

    $fotoRaw = trim($row['foto'] ?? '');
    $imgPath = '';
    if ($fotoRaw !== '') {
        $imgPath = IMG_BASE . $fotoRaw;
    } elseif ((int)($row['area'] ?? 0) === 10) {
        $imgPath = IMG_BASE . 'BIBL.jpeg';
    }

    if ($imgPath && file_exists($imgPath)) {
        $this->SetFillColor(GRS_R, GRS_G, GRS_B);
        $this->RoundedRect($imgX - 1, $imgY - 1, $imgSz + 2, $imgSz + 2, 3, 'F');
        $this->Image($imgPath, $imgX, $imgY, $imgSz, $imgSz);
    } else {
        $this->SetFillColor(230, 233, 242);
        $this->RoundedRect($imgX - 1, $imgY - 1, $imgSz + 2, $imgSz + 2, 3, 'F');
        $this->SetTextColor(190, 195, 210);
        $this->SetFont('Helvetica', 'B', 6);
        $this->SetXY($imgX, $imgY + 8);
        $this->Cell($imgSz, 5, 'SIN FOTO', 0, 0, 'C');
    }

    // ── Contenido texto ───────────────────────────────────
    $cx = $imgX + $imgSz + 7;
    $cw = ($x + $cardW) - $cx - 4;

    // Nombre artículo
    $this->SetTextColor(AZ_OSC_R, AZ_OSC_G, AZ_OSC_B);
    $this->SetFont('Helvetica', 'B', 9);
    $this->SetXY($cx, $y + 5);
    $this->Cell($cw - 46, 5, utf8_decode($row['articuloName']), 0, 0, 'L');

    // ── Badge tipo (derecha arriba) ───────────────────────
    $badgeW = 42;
    $badgeX = ($x + $cardW) - $badgeW - 2;
    if ($esEntrega) {
        $this->SetFillColor(ROJ_R, ROJ_G, ROJ_B);
        $badgeTxt = 'ENTREGA';
    } else {
        $this->SetFillColor(VER_R, VER_G, VER_B);
        $badgeTxt = 'REABASTECIMIENTO';
    }
    $this->RoundedRect($badgeX, $y + 4.5, $badgeW, 6.5, 1.8, 'F');
    $this->SetTextColor(255, 255, 255);
    $this->SetFont('Helvetica', 'B', 6);
    $this->SetXY($badgeX, $y + 5.5);
    $this->Cell($badgeW, 4.5, $badgeTxt, 0, 0, 'C');

    // ── Badge cantidad (debajo del badge tipo) ────────────
    if ($esEntrega) {
        $this->SetFillColor(255, 235, 233);
        $this->SetTextColor(ROJ_R, ROJ_G, ROJ_B);
    } else {
        $this->SetFillColor(225, 248, 235);
        $this->SetTextColor(VER_R, VER_G, VER_B);
    }
    $this->RoundedRect($badgeX, $y + 12.5, $badgeW, 7, 1.8, 'F');
    $this->SetFont('Helvetica', 'B', 9);
    $this->SetXY($badgeX, $y + 13.5);
    $this->Cell($badgeW, 5,
        number_format((float)$row['cantidad'], 1) . ' ' . utf8_decode($row['medida']),
        0, 0, 'C');

    // Separador
    $this->SetDrawColor(LIN_R, LIN_G, LIN_B);
    $this->SetLineWidth(0.3);
    $this->Line($cx, $y + 12.5, $badgeX - 2, $y + 12.5);

    // Detalle movimiento
    $this->SetTextColor(80, 90, 115);
    $this->SetFont('Helvetica', 'I', 7.5);
    $this->SetXY($cx, $y + 14);
    $this->MultiCell($cw - $badgeW - 4, 4.5, utf8_decode($row['movimiento']), 0, 'L');

    // Chip inferior: responsable + hora
    $chipY = $y + $cardH - 8;
    $this->SetFillColor(GRS_R, GRS_G, GRS_B);
    $this->RoundedRect($cx, $chipY, $cw, 6, 2, 'F');

    $this->SetTextColor(AZ_MED_R, AZ_MED_G, AZ_MED_B);
    $this->SetFont('Helvetica', 'B', 6.5);
    $this->SetXY($cx + 2, $chipY + 0.8);
    $this->Cell($cw * 0.6, 4.5, utf8_decode($row['nombre']), 0, 0, 'L');

    $this->SetTextColor(140, 150, 170);
    $this->SetFont('Helvetica', '', 6.5);
    $this->SetXY($cx + $cw * 0.6, $chipY + 0.8);
    $this->Cell($cw * 0.4 - 2, 4.5, $row['hora'], 0, 0, 'R');

    $this->SetY($y + $cardH + 4);
    $this->SetTextColor(0, 0, 0);
    $this->SetDrawColor(0, 0, 0);
}
}

// ── Consulta (incluye foto y area) ───────────────────────
$stmt = $dbconn->prepare("
    SELECT i.id, i.articulo, i.cantidad, i.tipo,
           inv.nombre  AS articuloName,
           inv.foto    AS foto,
           inv.area    AS area,
           i.usuario,
           CONCAT(u.nombre,' ',u.apellidoP,' ',u.apellidoM) AS nombre,
           IF(i.tipo = 1,
               CONCAT(im.nombre,' de ',i.cantidad,' ',inm.medida),
               CONCAT(im.nombre,' de ',i.cantidad,' ',inm.medida,
                      ' a ',CONCAT(us.nombre,' ',us.apellidoP,' ',us.apellidoM))
           ) AS movimiento,
           inm.medida  AS medida,
           i.hora
    FROM inventario_movimientos i
    LEFT JOIN usuarios u             ON u.id   = i.usuario
    LEFT JOIN inventario_motivos im  ON im.tipo = i.tipo
    LEFT JOIN inventario inv         ON inv.id  = i.articulo
    LEFT JOIN inventario_medidas inm ON inm.id  = inv.medida
    LEFT JOIN usuarios us            ON us.id   = i.usuario_final
    WHERE i.usuario = ? AND i.tipo=1 OR i.tipo=2
      AND i.hora BETWEEN ? AND ?
    ORDER BY i.hora ASC
");
$stmt->execute([
    $sesion[0],
    $_GET['finicio'] . ' 00:00:00',
    $_GET['ffin']    . ' 23:59:59',
]);
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

// ── Generar PDF ───────────────────────────────────────────
$pdf = new PDF_Recibo();
$pdf->finicio = $_GET['finicio'];
$pdf->ffin    = $_GET['ffin'];
$pdf->usuario = utf8_decode($sesion[3] ?? '');
$pdf->total   = count($rows);

$pdf->SetAutoPageBreak(true, 18);
$pdf->SetMargins(0, 0, 0);   // ← quita márgenes que heredaba de diag.php
$pdf->SetAutoPageBreak(true, 18);
$pdf->AddPage('P', array(210, 297));  // A4 explícito en mm

if (count($rows) === 0) {
    $pdf->SetFont('Helvetica', 'I', 11);
    $pdf->SetTextColor(170, 175, 190);
    $pdf->Ln(20);
    $pdf->Cell(0, 10, utf8_decode('Sin movimientos en el período seleccionado.'), 0, 1, 'C');
} else {
    foreach ($rows as $row) {
        $pdf->MovCard($row);
    }
}

$pdf->Output();
?>