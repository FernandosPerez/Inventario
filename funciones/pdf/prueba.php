<?php
date_default_timezone_set("America/Mexico_City");   //USO LA ZONA HORARIA DE MEXICO
$hoy = date("F j, Y, g:i a");   //ESTA VARIABLE SIRVE PARA VER LA ULTIMA CREACIÓN DEL ARCHIVO TIEMPO EXACTO
session_start();

error_reporting(0);
ini_set('display_errors',0);
ini_set('display_startup_errors',0);
include('../includes/conexion.php');


$area=$_REQUEST['area'];

// if($check==1){
//     try{
//         require_once '../assets/fpdf/fpdf.php';
//     require_once '../assets/fpdf/autoload.php';
    
//     $pdf = new \setasign\Fpdi\Fpdi();
    
//     $archivo = '../assets/evaluaciones/evaluacionAdministrativa/Orden_'.$folio.'.pdf';
// $pageCount = $pdf->setSourceFile($archivo);
// $pageId = $pdf->importPage(1, \setasign\Fpdi\PdfReader\PageBoundaries::MEDIA_BOX);

// $pdf->addPage();
// $pdf->useImportedPage($pageId);

// $pdf->Output('I', 'generated.pdf');
//     }catch(Exception $e){
//         echo "Lo siento el archivo que intentas abrir no existe";
//     }
    
    
// }else{

    // $qryd = $dbconn->prepare("SELECT * FROM usrs where id='" .$datos[0]['solicita']. "'");
    // $qryd->execute();
    // $solicitante = $qryd->fetchAll(PDO::FETCH_ASSOC);

    define('FPDF_FONTPATH', '../assets/fpdf/font');

    require('../assets/fpdf/diag.php');
    // extend class

    // create document
    $pdf = new PDF_Diag();
    $pdf->AddPage();
    $pdf->SetFont('Arial', '', 10);
    //$pdf->Cell(190, 9, "      " . utf8_decode("FOLIO DE REQUISICIÓN: " . $folio), 0, 0, 'R');
    $pdf->Image('../assets/images/Logo_secuiep.jpg', 8, 5, -650);

    //FILA 1
    $pdf->Ln(20);

    $pdf->setFillColor(255, 255, 255);
    $pdf->Cell(0, 9, utf8_decode("EVALUACIÓN ADMINISTRATIVA"), 0, 1, 'L', TRUE);
    $pdf->Cell(0, 9, utf8_decode($dato_campus),0, 1, 'L', TRUE);
    $pdf->Cell(0, 9, utf8_decode($dato_area), 0, 1, 'L', TRUE);
    $pdf->Cell(0, 9, utf8_decode("Comprendida en las fechas: ".$ev[0]['inicio']." Y ".$ev[0]['termino']), 0, 0, 'L', TRUE);
    $pdf->setFillColor(255, 255, 255);

    $pdf->Ln(20);

    //FILA 5
    // $pdf->Cell(47.5, 7, "      " . utf8_decode("CONTACTO"),  1, 0, '', TRUE);
    // $pdf->Cell(0, 7, "      " . utf8_decode($solicitante[0]['name']),  1, 1, 'L', TRUE);

    // //FILA 6
    // $pdf->Cell(47.5, 7, "      " . utf8_decode("TELEFONO"), 1, 0, '', TRUE);
    // $pdf->Cell(47.5, 7, utf8_decode($solicitante[0]['phone1']),  1, 0, 'C', TRUE);
    // $pdf->Cell(30, 7, "      " . utf8_decode("CORREO"),  1, 0, '', TRUE);
    // $pdf->Cell(65, 7, utf8_decode($solicitante[0]['email']),  1, 1, 'C', TRUE);
    // $pdf->Ln(3);

    // $pdf->Cell(0, 7, "      " . utf8_decode("COMPRA AUTORIZADA POR ".$autoriza),  1, 1, '', TRUE);
    // $pdf->Ln(3);

    // $pdf->setFillColor(52, 140, 235);

    // $pdf->Cell(0, 7,  "      " .utf8_decode("DATOS DEL PROVEEDOR"),  1, 1, '', TRUE);

    // $pdf->Cell(27.5, 7,  utf8_decode("RAZÓN SOCIAL"), 1, 0, 'C', TRUE);
    // $pdf->Cell(67.5, 7,  utf8_decode($proveedor[0]['razonsocial']), 1, 0, 'C', FALSE);
    // $pdf->Cell(30, 7,  utf8_decode("RFC"), 1, 0, 'C', TRUE);
    // $pdf->Cell(65, 7,  utf8_decode($proveedor[0]['rfc']), 1, 1, 'C', FALSE);

    // $pdf->Cell(37.5, 7,  utf8_decode("NOMBRE COMERCIAL"), 1, 0, 'C', TRUE);
    // $pdf->Cell(57.5, 7,  utf8_decode($proveedor[0]['nombrecomercial']), 1, 0, 'C', FALSE);
    // $pdf->Cell(30, 7,  utf8_decode("BANCO"), 1, 0, 'C', TRUE);
    // $pdf->Cell(65, 7,  utf8_decode($proveedor[0]['banco']), 1, 1, 'C', FALSE);

    // $pdf->Cell(27.5, 7,  utf8_decode("DIRECCION"), 1, 0, 'C', TRUE);
    // $pdf->Cell(162.5, 7,  utf8_decode($proveedor[0]['direccionfiscal']), 1, 1, 'C', FALSE);

    // $pdf->Cell(27.5, 7,  utf8_decode("TELEFONO"), 1, 0, 'C', TRUE);
    // $pdf->Cell(67.5, 7,  utf8_decode($proveedor[0]['numerotelefonico']), 1, 0, 'C', FALSE);
    // $pdf->Cell(30, 7,  utf8_decode("CUENTA"), 1, 0, 'C', TRUE);
    // $pdf->Cell(65, 7,  utf8_decode($proveedor[0]['numerocuenta']), 1, 1, 'C', FALSE);    

    // $pdf->Cell(27.5, 7,  utf8_decode("CORREO"), 1, 0, 'C', TRUE);
    // $pdf->Cell(67.5, 7,  utf8_decode($proveedor[0]['email']), 1, 0, 'C', FALSE);
    // $pdf->Cell(30, 7,  utf8_decode("CLAVE I."), 1, 0, 'C', TRUE);
    // $pdf->Cell(65, 7,  utf8_decode($proveedor[0]['claveinterbancaria']), 1, 1, 'C', FALSE);

    // $pdf->Ln(3);
    //FILA7------------------------------------------------

    // $pdf->Cell(95, 7,  utf8_decode("ARTICULO"), 1, 0, 'C', TRUE);
    // $pdf->Cell(30, 7,  utf8_decode("CANTIDAD"), 1, 0, 'C', TRUE);
    // $pdf->Cell(30, 7,  utf8_decode("COSTO UNITARIO"), 1, 0, 'C', TRUE);
    // $pdf->Cell(35, 7,  utf8_decode("COSTO TOTAL"), 1, 1, 'C', TRUE);

    // $articulos = explode(";", $datos[0]['articulos']);

    // foreach ($articulos as $articulo) {

    //     $nombre = explode(":", $articulo);
    //     $cant = explode("$", $nombre[1]);
    //     if ($cant[0] != "") {

    //         $qryd = $dbconn->prepare("SELECT * FROM proveedores_inventario_ofertas where descripcion='".$nombre[0]. "'");
    //         $qryd->execute();
    //         $costo = $qryd->fetchAll(PDO::FETCH_ASSOC);

    //         $pdf->Cell(95, 7,  utf8_decode($nombre[0]), 1, 0, 'C', FALSE);
    //         $pdf->Cell(30, 7,  utf8_decode($cant[0]), 1, 0, 'C', FALSE);
    //         $pdf->Cell(30, 7,  utf8_decode("$ " . $costo[0]['costo'] . "     "), 1, 0, 'R', FALSE);
    //         $pdf->Cell(35, 7,  utf8_decode("$ " .  number_format($costo[0]['costo']*$cant[0], 2, '.', '') . "     "), 1, 1, 'R', FALSE);          
    //     }
    // }
    // $pdf->Ln(3);
    // $pdf->Cell(125, 7, utf8_decode("Mención de costo sin IVA")."      ", 0, 0, 'R', FALSE);
    // $pdf->Cell(30, 7,  utf8_decode("TOTAL"), 1, 0, 'C', TRUE);
    // $pdf->Cell(35, 7,  utf8_decode("$ " . $datos[0]['total'] . "     "), 1, 1, 'R', false);
    // $pdf->Ln(3);

    //$pdf->Cell(0, 7, "      " . utf8_decode("COMENTARIOS"),  0, 1, '', TRUE);
    //$pdf->MultiCell(0, 7, "      " . utf8_decode("traka"),  1, 'L');

    $pdf->Ln(3);

    $pdf->setFillColor(52, 140, 235);
    $Y = $pdf->GetY();
    $X = $pdf->GetX();

    $url="https://alumnos.sistemasecuiep.com/images/evaluacionAdministrativa/resultados/".$campus."/".$area.".png";
    

     $pdf->Image($url, 135, 10, 65,65,"PNG");

     $pdf->Ln(10);
    $pdf->SetFont('Arial', '', 9);

    // $pdf->MultiCell(0, 7,utf8_decode("traka"),  0, 'L');



       
//preguntas bueno, regular y malo

       

        
        
        // $fil = explode("-",$text);

                 $qry = $dbconn->prepare("SELECT e.*,p.tipo,COUNT(*) as totales FROM  evaluacion_administrativa_respuestas e LEFT JOIN alumno a ON a.id=e.alumno 
LEFT JOIN preguntas_ev_adm p ON p.id=e.pregunta WHERE (e.pregunta != 0 ) AND  e.AREA = '".$area."' and e.registro BETWEEN '".$ev[0]["inicio"]."' AND '".$ev[0]["termino"]."' 
        AND a.plantel='".$campus."' and p.tipo=0
        group BY e.pregunta ORDER BY e.pregunta");
        $qry->execute();

         if($qry->rowCount() !=0 ){
                         $rows = $qry->fetchAll(PDO::FETCH_ASSOC);



                         
    //$data = ['Bueno' => 1510, 'Regular' => 1610, 'Malo' => 1400];
    $data = array();



//Pie chart
// $pdf->SetFont('Arial', 'BIU', 12);
$pdf->Cell(0, 5, utf8_decode("Gráfica de interpretación"), 0, 1);

 $qry2 = $dbconn->prepare("SELECT e.*,
p.tipo,
COUNT(*) AS totales
FROM evaluacion_administrativa_respuestas e
LEFT JOIN alumno a ON a.id=e.alumno
LEFT JOIN preguntas_ev_adm p ON p.id=e.pregunta
WHERE e.AREA = '".$area."' and e.registro BETWEEN '".$ev[0]["inicio"]."' AND '".$ev[0]["termino"]."' 
        AND a.plantel='".$campus."' and p.tipo=0
        group BY e.alumno");
        $qry2->execute();

if($area=="consm" || $area=="acam"){
 $qry3 = $dbconn->prepare("SELECT * FROM alumno a 
LEFT JOIN alumno_carrera al  on al.id=a.id
left JOIN carreras c ON c.id_carrera=al.carrera
WHERE (a.activo=1 OR a.activo=2) AND c.carrera LIKE'%NUTRI%' AND a.plantel='".$campus."'");
}else{
 $qry3 = $dbconn->prepare("SELECT * FROM alumno WHERE plantel='".$campus."' AND (activo=1 OR activo=2)");
}


        $qry3->execute();
                

$pdf->Cell(0, 5, utf8_decode("Alumnos que evaluaron: ".$qry2->rowCount()." de ".$qry3->rowCount()), 0, 1);
$pdf->Ln(8);





// $pdf->SetFont('Arial', '', 10);
$valX = $pdf->GetX();
$valY = $pdf->GetY();

$totales=0;
 foreach($rows as $row){

    
                    $qry = $dbconn->prepare("SELECT e.*,p.pregunta as preg,pl.name as pname,p.tipo,COUNT(*) as totales FROM  evaluacion_administrativa_respuestas e LEFT JOIN alumno a ON a.id=e.alumno LEFT JOIN preguntas_ev_adm p on p.id=e.pregunta LEFT JOIN plts pl on pl.id=a.plantel WHERE  e.AREA = '".$area."' and e.registro BETWEEN '".$ev[0]["inicio"]."' AND '".$ev[0]["termino"]."' 
                    AND a.plantel='".$campus."' AND p.tipo=0
                    GROUP BY 
                    e.respuesta
                    ORDER BY e.pregunta,
                    e.respuesta");
                    $qry->execute();

                    //  if($qry->rowCount() ==1 ){
                    //     $totales=1;
                    //  }else if($qry->rowCount() ==2){
                    //     $totales=2;
                    //  }else if($qry->rowCount() ==3){
                    //     $totales=3;
                    //  }

                    $rows2 = $qry->fetchAll(PDO::FETCH_ASSOC);

                    foreach($rows2 as $fila){
                            // aqui
                            $numb = (int)$fila["totales"];
                     if($fila["respuesta"]==1){
                        $etiqueta="Bueno";
                     }else if($fila["respuesta"] ==2){
                        $etiqueta="Regular";
                     }else if($fila["respuesta"] ==3){
                        $etiqueta="Malo";
                     }


                        $data[$etiqueta]=$numb;
                        //  array_push($data,($fila["respuesta"]=>$fila["totales"]));
                                                
                        
                        }

                }

$pdf->Ln(8);

$pdf->SetXY(65, $valY);
$col1=array(2, 165, 224);
$col2=array(1, 112, 204);
$col3=array(6, 47, 109);

$pdf->PieChart(100, 100, $data, '%l  %v  (%p)',array($col1,$col2,$col3));

// if($totales==1){
// $pdf->PieChart(100, 35, $data, '%l (%p)',array($col1));

// }else if($totales==2){
// $pdf->PieChart(100, 35, $data, '%l (%p)',array($col1,$col2));

// }else if($totales==3){
// $pdf->PieChart(100, 35, $data, '%l (%p)',array($col1,$col2,$col3));

// }
$pdf->SetXY($valX, $valY + 40);

$pdf->Ln(25);

            
            $pdf->setFillColor(52, 140, 235);
            $pdf->Cell(130, 7, utf8_decode(""), 0, 0, 'C', FALSE);
            $pdf->Cell(20, 7, utf8_decode("Bueno"), 1, 0, 'C', FALSE);
            $pdf->Cell(20, 7, utf8_decode("Regular"), 1, 0, 'C', FALSE);
            $pdf->Cell(20, 7, utf8_decode("Malo"), 1, 1, 'C', FALSE);


                
                foreach($rows as $row){

                    $qry = $dbconn->prepare("SELECT e.*,p.pregunta as preg,pl.name as pname,p.tipo,COUNT(*) as totales FROM  evaluacion_administrativa_respuestas e LEFT JOIN alumno a ON a.id=e.alumno LEFT JOIN preguntas_ev_adm p on p.id=e.pregunta LEFT JOIN plts pl on pl.id=a.plantel WHERE  e.AREA = '".$area."' and e.registro BETWEEN '".$ev[0]["inicio"]."' AND '".$ev[0]["termino"]."' 
                    AND a.plantel='".$campus."' and e.pregunta='".$row["pregunta"]."'
                    group BY e.pregunta,e.respuesta ORDER BY e.pregunta,e.respuesta");
                    $qry->execute();
                    $rows2 = $qry->fetchAll(PDO::FETCH_ASSOC);
                    
                    // $boton='<p onclick="crearGrafica('.$rows2[0]["eid"]','.$area.','.$campus.','.$evaluacion.')" id="btnModal2">';

                    $pregunta=$rows2[0]["pregunta"];
                     
                   
                        $pdf->MultiCell(130, 7,chr(149)."  ".utf8_decode($rows2[0]["preg"]),  0, 'L');

    // $pdf->Cell(30, 7,  utf8_decode("TOTAL"), 1, 0, 'C', TRUE);
    // $pdf->Cell(35, 7,  utf8_decode("$ " . $datos[0]['total'] . "     "), 1, 1, 'R', false);


$Y= $pdf->GetY();
$X= $pdf->GetX();

if(strlen(chr(149)."  ".utf8_decode($rows2[0]["preg"]))>84){
$pdf->SetXY($X+130,$Y-10);
            
}else{
$pdf->SetXY($X+130,$Y-5);
}


                        foreach($rows2 as $fila){
                            //$response=$response.'<td>'.$fila["totales"].'</td>';
                        // $pdf->SetXY($X+20,$Y-10);
                        $pdf->Cell(20, 7, utf8_decode($fila["totales"]), 0, 0, 'C', FALSE);

                        }
                        $pdf->Ln();
                        
                        $pdf->Cell(0, 7, utf8_decode("__________________________________________________________________________________________________"), 0, 0, 'C', FALSE);


                        $pdf->Ln(5);
                }
                


         }else{

         }




         

//preguntas de si o no

                $qry = $dbconn->prepare("SELECT e.*,p.tipo,COUNT(*) as totales FROM  evaluacion_administrativa_respuestas e LEFT JOIN alumno a ON a.id=e.alumno 
                LEFT JOIN preguntas_ev_adm p ON p.id=e.pregunta WHERE (e.pregunta != 0 ) AND  e.AREA = '".$area."' and e.registro BETWEEN '".$ev[0]["inicio"]."' AND '".$ev[0]["termino"]."' 
                        AND a.plantel='".$campus."' and p.tipo=2
                        group BY e.pregunta ORDER BY e.pregunta");
                        $qry->execute();

                 if($qry->rowCount() !=0 ){
                         $rows = $qry->fetchAll(PDO::FETCH_ASSOC);
        
                         $pdf->Ln(15);
        $pdf->setFillColor(52, 140, 235);
            $pdf->Cell(150, 7, utf8_decode(""), 0, 0, 'C', FALSE);
            $pdf->Cell(20, 7, utf8_decode("Si"), 1, 0, 'C', FALSE);
            $pdf->Cell(20, 7, utf8_decode("No"), 1, 1, 'C', FALSE);
                 
                
                foreach($rows as $row){

                    $qry = $dbconn->prepare("SELECT e.*,p.pregunta as preg,pl.name as pname,p.tipo,COUNT(*) as totales FROM  evaluacion_administrativa_respuestas e LEFT JOIN alumno a ON a.id=e.alumno LEFT JOIN preguntas_ev_adm p on p.id=e.pregunta LEFT JOIN plts pl on pl.id=a.plantel WHERE  e.AREA = '".$area."' and e.registro BETWEEN '".$ev[0]["inicio"]."' AND '".$ev[0]["termino"]."' 
                    AND a.plantel='".$campus."' and e.pregunta='".$row["pregunta"]."'
                    group BY e.pregunta,e.respuesta ORDER BY e.pregunta,e.respuesta");
                    $qry->execute();
                    $rows2 = $qry->fetchAll(PDO::FETCH_ASSOC);

                        $pdf->MultiCell(130, 7,chr(149)."  ".utf8_decode($rows2[0]["preg"]),  0, 'L');

    // $pdf->Cell(30, 7,  utf8_decode("TOTAL"), 1, 0, 'C', TRUE);
    // $pdf->Cell(35, 7,  utf8_decode("$ " . $datos[0]['total'] . "     "), 1, 1, 'R', false);


$Y= $pdf->GetY();
$X= $pdf->GetX();

if(strlen(chr(149)."  ".utf8_decode($rows2[0]["preg"]))>93){
$pdf->SetXY($X+150,$Y-10);
            
}else{
$pdf->SetXY($X+150,$Y-5);
}

                        foreach($rows2 as $fila){
                            //$response=$response.'<td>'.$fila["totales"].'</td>';
                        // $pdf->SetXY($X+20,$Y-10);
                        $pdf->Cell(20, 7, utf8_decode($fila["totales"]), 0, 0, 'C', FALSE);

                        }
                        $pdf->Ln();
                        
                        $pdf->Cell(0, 7, utf8_decode("__________________________________________________________________________________________________"), 0, 0, 'C', FALSE);


                        $pdf->Ln(5);
                }
                
                }
                

                else{

                 }   
    

//preguntas abiertas



$qry = $dbconn->prepare("SELECT e.*,p.tipo,p.pregunta as preg,COUNT(*) as totales FROM  evaluacion_administrativa_respuestas e LEFT JOIN alumno a ON a.id=e.alumno 
                LEFT JOIN preguntas_ev_adm p ON p.id=e.pregunta WHERE (e.pregunta != 0 ) AND  e.AREA = '".$area."' and e.registro BETWEEN '".$ev[0]["inicio"]."' AND '".$ev[0]["termino"]."' 
                        AND a.plantel='".$campus."' and p.tipo=1
                        group BY e.pregunta ORDER BY e.pregunta");
                        $qry->execute();

                 if($qry->rowCount() !=0 ){
                         $rows = $qry->fetchAll(PDO::FETCH_ASSOC);

                $pdf->Ln(20);
                
                foreach($rows as $row){
                    $pdf->Ln(10);
                    $pdf->Cell(0, 7, utf8_decode($row["preg"]), 1, 1, 'C', FALSE);
$pdf->Ln(10);
                    $qry = $dbconn->prepare("SELECT e.*,p.pregunta as preg,pl.name as pname,p.tipo,COUNT(*) as totales FROM  evaluacion_administrativa_respuestas e LEFT JOIN alumno a ON a.id=e.alumno LEFT JOIN preguntas_ev_adm p on p.id=e.pregunta LEFT JOIN plts pl on pl.id=a.plantel WHERE  e.AREA = '".$area."' and e.registro BETWEEN '".$ev[0]["inicio"]."' AND '".$ev[0]["termino"]."' 
                    AND a.plantel='".$campus."' and e.pregunta='".$row["pregunta"]."'
                    group BY e.pregunta,e.respuesta ORDER BY e.pregunta,e.respuesta");
                    $qry->execute();
                    $rows2 = $qry->fetchAll(PDO::FETCH_ASSOC);

                    foreach($rows2 as $fila){
                        $pdf->MultiCell(190, 7,chr(149)."  ".utf8_decode($fila["respuesta"]),  0, 'L');
                }



                }
                

                }else{

                }   

                 




//datos de redes sociales
               

                $qry = $dbconn->prepare("SELECT e.respuesta,SUBSTRING(e.respuesta, 1, 1)AS sred,pv.opcion,COUNT(*) AS red
                FROM evaluacion_administrativa_respuestas e
                LEFT JOIN alumno a ON a.id=e.alumno
                LEFT JOIN preguntas_ev_adm p ON p.id=e.pregunta
                LEFT JOIN preguntas_ev_adm_seleccion pv ON pv.id=SUBSTRING(e.respuesta, 1, 1)
                WHERE e.respuesta REGEXP '^-?[0-9]+$' AND  e.AREA = 'red' AND e.pregunta=0 
                AND e.registro BETWEEN '".$ev[0]["inicio"]."' AND '".$ev[0]["termino"]."' 
                                AND a.plantel='".$campus."'
                GROUP BY sred");
                $qry->execute();


                if($qry->rowCount() !=0  && $area=="red"){
                         $rows = $qry->fetchAll(PDO::FETCH_ASSOC);
                         $pdf->Ln(20);
            $pdf->Cell(170, 7, utf8_decode("REDES"), 1, 0, 'C', FALSE);
            $pdf->Cell(20, 7, utf8_decode("CONTEO"), 1, 1, 'C', FALSE);

                     foreach($rows as $row){
                        $pdf->Cell(170, 7, utf8_decode($row["opcion"]), 1, 0, 'L', FALSE);
            $pdf->Cell(20, 7, utf8_decode($row["red"]), 1, 1, 'C', FALSE);
                }


                }else{

                }



                
                
//OBSERVACIONES
               

                $qry = $dbconn->prepare("SELECT e.*,COUNT(*) as totales FROM  evaluacion_administrativa_respuestas e LEFT JOIN alumno a ON a.id=e.alumno WHERE e.pregunta = 0 AND  e.AREA = '".$area."' and e.AREA!='red' and e.registro BETWEEN '".$ev[0]["inicio"]."' AND '".$ev[0]["termino"]."' 
                AND a.plantel='".$campus."'
                group BY e.pregunta,e.alumno ORDER BY e.pregunta");
                $qry->execute();


                if($qry->rowCount() !=0 ){

    
                $rows = $qry->fetchAll(PDO::FETCH_ASSOC);

                     $pdf->Ln(20);
                    $pdf->Cell(0, 7, utf8_decode("observaciones"), 1, 1, 'C', FALSE);
                    $pdf->Ln(10);
                     foreach($rows as $row){
                    // $response=$response.'<tr><td>'.$row["respuesta"].'</td></tr>';
                        $pdf->MultiCell(190, 7,chr(149)."  ".utf8_decode($row["respuesta"]),  0, 'L');

                }



                



                }else{

                }




    //$pdf->Code128($X,$Y+50,"0000S205625STC001609",110,20);
    // $archivo = '../assets/evaluaciones/evaluacionAdministrativa/Orden_'.$folio.'.pdf';
    
    //$directorio = ("../assets/evaluaciones/evaluacionAdministrativa/".$campusNombre."/Evaluacion_" . $evaluacion . "_".$area.".pdf");






    //$pdf->Output($directorio, "F");
    $pdf->Output();
// }

