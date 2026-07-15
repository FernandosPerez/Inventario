<?php

error_reporting(E_ALL & ~E_NOTICE);
ini_set('display_errors', 1);


//echo(__DIR__);

include('../../include/conn.php');


$contenido= explode("|",$_REQUEST['contenido']);



$id=$contenido[0];
$pass=$contenido[1];

$query = "SELECT * from usuarios where id='".$id."' ";
                        $exc_query = $dbconn->prepare($query);
                        $exc_query->execute();
                        $usuario = $exc_query->fetchAll(PDO::FETCH_ASSOC);

/*
require('../../librerias/fpdf/fpdf.php');

// extend class
class PDF extends FPDF {
    protected $fontName = 'Arial';

    function renderTitle($text) {
        $this->Ln(10);
        $this->SetTextColor(0, 0, 0);
        $this->SetFont($this->fontName, 'B', 24);
        $this->Cell(0, 10, utf8_decode($text), 0, 1);
        $this->Ln();
    }
    function renderText($text) {
        $this->SetTextColor(51, 51, 51);
        $this->SetFont($this->fontName, '', 12);
        $this->MultiCell(0, 7, utf8_decode($text), 0, 1);
        $this->Ln();
    }
    function cabeceraIdentifiacion($text) { //funcion para texto de la cabezera con estilo
        $this->SetTextColor(3, 8, 143);
        $this->SetFont($this->fontName, 'B', 14);
        $this->Cell(0, 5,"                                        ".utf8_decode($text), 0, 1,'L');
        $this->Ln();
    }

    function renderSubTitle($text) {
        $this->SetTextColor(0, 0, 0);
        $this->SetFont($this->fontName, 'B', 16);
        $this->Cell(0, 10, utf8_decode($text), 0, 1);
        $this->Ln();
    }

    function renderTextTable($espacio,$text,$text2){
        $this->SetDrawColor(0, 66, 145);
        $this->SetTextColor(51, 51, 51);
        $this->SetFont($this->fontName,'', 12);
        $this->Cell($espacio, 7, utf8_decode($text), 0,0,'L');
        $this->SetFont($this->fontName, 'U');
        $this->Cell($espacio, 7, utf8_decode($text2), 0,0,'L');
        $this->SetFont($this->fontName,'', 12);
    }

    function renderTextTableDoc($Nombre,$Fecha,$Referencia,$Importe){
        $this->SetTextColor(51, 51, 51);
        $this->SetFillColor(245, 245, 245);//color azul claro
        $this->Cell(120, 5, utf8_decode($Nombre), 1,0,'L',$fill=true);
        $this->Cell(35, 5, utf8_decode($Fecha), 1,0,'C',$fill=true);
        $this->Cell(35, 5, utf8_decode($Referencia), 1,1,'C',$fill=true);
        $this->Cell(35, 5, utf8_decode($Importe), 1,1,'C',$fill=true);
    }
}

// create document
$pdf = new PDF();
$pdf->AddPage();

//$pdf->Image('assets/images/secuiep.png', 20, 10, -200);

$pdf->Ln(10);
$pdf->cabeceraIdentifiacion("Bienvenido");
$pdf->Ln();
$directorio=("../../librerias/fpdf/pdfs/promocion/bienvenida.pdf");

$pdf->Output($directorio,"F");  
//$pdf->Output();


*/

//$response= $correo;      //SOLO IMPRIMO PARA VER SI ME TRAE EL CORREO ELECTRÓNICO DEL PROSPECTO BIEN
//echo $response;

//----------------------------------- Aqui comienza el uso de la libreria phpmailer 

//z
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

require '../../librerias/PHPMailer/src/Exception.php';
require '../../librerias/PHPMailer/src/PHPMailer.php';
require '../../librerias/PHPMailer/src/SMTP.php';

//Create an instance; passing `true` enables exceptions
$mail = new PHPMailer(true);

try {
    //Server settings
    $mail->isSMTP();
$mail->Host = 'localhost';
$mail->SMTPAuth = false;
$mail->SMTPAutoTLS = false; 
$mail->Port = 25; 
    
    $mail->SMTPDebug = 0;                      //Enable verbose debug output
    
    $envia ='promocion@secuiep.mx';
    $mail->Username   =  $envia;                  
    $mail->Password   = '6x2HDdu+$KEE;?TU';
       
    $mail->setFrom($envia, 'SECUIEP');

    $mail->addAddress($usuario[0]["correo"]);     //Add a recipient
    $mail->addReplyTo($envia);
    $mail->addCC($envia);
    //$mail->addBCC('bcc@example.com');

    //Attachments
//    $mail->addAttachment($directorio);         //Add attachments
    //$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    //Optional name

    //Content
    $mail->isHTML(true);                //Set <zaemail format to HTML
    $mail->Subject = 'Mensaje de bienvenida';
    $mail->Body    = 'Hola '.$usuario[0]["nombre"].' '.$usuario[0]["apellidoP"].' '.$usuario[0]["apellidoM"].', espero estes teniendo un <b>EXCELENTE DIA!</b> te comparto tus datos de acceso a la plataforma: <br/>
    link de acceso aqui: <a href="https://sistemasecuiep.com/boostrap/index.php">Ir al enlace</a><br/>
    usuario de acceso: '.$usuario[0]["correo"].' <br/>
    contraseña : '.$pass.' <br/>
 estas dentro';
    //$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

    $mail->send();
    $response=1;
    echo $response;
} catch (Exception $e) {
    //echo "Ocurrio el siguiente error: {$mail->ErrorInfo}";
    //$response =$mail->ErrorInfo;
    $response=0;
   echo $response;
}

?>