<?php
/*
 * VERIFICACION DEL WEBHOOK
*/
//TOQUEN QUE QUERRAMOS PONER 
$token = 'Azulejo2025';
//RETO QUE RECIBIREMOS DE FACEBOOK
$palabraReto = $_GET['hub_challenge'];
//TOQUEN DE VERIFICACION QUE RECIBIREMOS DE FACEBOOK
$tokenVerificacion = $_GET['hub_verify_token'];
//SI EL TOKEN QUE GENERAMOS ES EL MISMO QUE NOS ENVIA FACEBOOK RETORNAMOS EL RETO PARA VALIDAR QUE SOMOS NOSOTROS
if ($token === $tokenVerificacion) {
    echo $palabraReto;
    exit;
}

$respuesta = file_get_contents("php://input");
//CONVERTIMOS EL JSON EN ARRAY DE PHP
$respuesta = json_decode($respuesta, true);

 file_put_contents("conversacion.txt", $respuesta);

/*
//EXTRAEMOS EL TELEFONO DEL ARRAY
$telefono=$respuesta['entry'][0]['changes'][0]['value']['messages'][0]['from'];
//EXTRAEMOS EL MENSAJE DEL ARRAY
$mensaje=$respuesta['entry'][0]['changes'][0]['value']['messages'][0]['text']['body'];

try{
if($telefono=="" && $mensaje==""){
    
}else{
    
include('includes/conexion.php');
date_default_timezone_set('America/Mexico_City');
        $hora = date('y-m-d h:i:s');
$whats = $dbconn->prepare("INSERT INTO whatsapp VALUES ('','".$telefono."','".$mensaje."','".$hora."')");
$whats->execute();
//GUARDAMOS EL MENSAJE Y LA RESPUESTA EN EL ARCHIVO text.txt
 } 
}catch(Exception $e){
    file_put_contents("conversacion.txt", $e);
}*/