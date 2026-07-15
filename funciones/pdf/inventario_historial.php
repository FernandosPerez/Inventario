<?php 
//define('FPDF_FONTPATH', '../librerias/fpdf/font');
    include("../../include/conn.php");
    session_start();
    $sesion=explode("|",$_SESSION["usuario"]);
    require('../../librerias/fpdf/diag.php');
    // extend class

    // create document
    $pdf = new PDF_Diag();
    $pdf->AddPage(); 
    $pdf->SetFont('Helvetica', '', 12);
    //$pdf->Cell(190, 9, "      " . utf8_decode("FOLIO DE REQUISICIÓN: " . $folio), 0, 0, 'R');
    $pdf->Image('../../img/logo-secuiep.jpg', 8, 5, -650);

    //FILA 1
    //$pdf->Ln(20);

    $pdf->setFillColor(255, 255, 255);
    $pdf->Cell(0, 9, utf8_decode("Historial de movimientos"), 0, 1, 'C', FALSE);
    $pdf->SetFont('Helvetica', '', 10);
    $pdf->Cell(0, 9, utf8_decode($_GET['finicio']." a ".$_GET['ffin']), 0, 1, 'C', FALSE);

    $pdf->Ln(5);

    $stmt = $dbconn->prepare("SELECT i.id,i.articulo,inv.nombre as articuloName,i.usuario,CONCAT(u.nombre,' ',u.apellidoP,' ',u.apellidoM) AS nombre,if(i.tipo =1,CONCAT( im.nombre,' de ',i.cantidad,' ',inm.medida) ,CONCAT( im.nombre,' de ',i.cantidad,' ',inm.medida, ' a ',CONCAT(us.nombre,' ',us.apellidoP,' ',us.apellidoM))) AS movimiento,i.hora 
    FROM inventario_movimientos i
    LEFT JOIN usuarios u ON u.id=i.usuario
    LEFT JOIN inventario_motivos im ON im.tipo=i.tipo
    LEFT JOIN inventario inv ON inv.id=i.articulo
    LEFT JOIN inventario_medidas inm ON inm.id=inv.medida
    LEFT JOIN usuarios us ON us.id=i.usuario_final
    WHERE i.usuario='" . $sesion[0] . "' AND i.hora BETWEEN '" . $_GET['finicio'] . "' AND '" . $_GET['ffin'] . "'");
    $stmt->execute();
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($rows as $row) {
        $pdf->Cell(0, 9, utf8_decode("---".$row['hora']), 0, 1, 'L', false);
        $pdf->MultiCell(0, 9,utf8_decode("          ".$row['articuloName']." - ".$row['nombre']." - ".$row['movimiento']),  0, 'L');
        $pdf->Ln();

    }


    // $directorio = ("../assets/evaluaciones/evaluacionAdministrativa/".$campusNombre."/Evaluacion_" . $evaluacion . "_".$area.".pdf");

    // $pdf->Output($directorio, "F");

    $pdf->Output();

    ?>