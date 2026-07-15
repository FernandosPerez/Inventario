<?php
include("../include/conn.php");
session_start();
$sesion=explode("|",$_SESSION["usuario"]);
$eco=$_SESSION["eco"];


switch($_REQUEST['op']){
	case 1:
        $text = $_POST['texto'];
        $coordenadas = explode("|",$_POST['coordenadas']);

        $qr = $dbconn->prepare("SELECT * FROM registros WHERE usuario='" . $sesion[0] . "' and eco='" . $eco . "' and dia=CURDATE()");
        $qr->execute();

        if ($qr->rowCount()>=1) {
            
            try{
                $cont = $qr->fetchAll(PDO::FETCH_ASSOC);
                $query = "UPDATE registros SET isla='".$cont[0]["isla"]."|".$text."' ,chequeos = '".$cont[0]["chequeos"]."|".date("h:i:s")."', latitud = '".$cont[0]["latitud"]."|".$coordenadas[0]."', longitud ='".$cont[0]["longitud"]."|".$coordenadas[1]."' WHERE id='".$cont[0]['id']."'";
                    $exc_query = $dbconn->prepare($query);
                    $exc_query->execute();
                    echo $response = 2;
            } catch (Error $e) {
                echo $response = 0;
            }
        
        }else{

            $query = "INSERT INTO registros (id,usuario,eco,isla,dia,chequeos,latitud,longitud)values ('','".$sesion[0]."','".$eco."','".$text."',CURDATE(),'".date("h:i:s")."','".$coordenadas[0]."','".$coordenadas[1]."') ";
            $exc_query = $dbconn->prepare($query);
            $exc_query->execute();
            echo $response = 1;
        }
    break;

    case 2:
        $text = $_POST['texto'];
        $_SESSION['eco']=$text;
        echo $response = $_SESSION['eco'];
    break;

    case 3:
        $text = $_POST['texto'];
        unset($_SESSION['eco']); 
        $_SESSION['eco']=$text;
        echo $response = $_SESSION['eco'];
    break;

    case 4 :

        /*
        $qry = $dbconn->prepare("SELECT * FROM registros WHERE usuario='30' and eco='ECO-0123' and dia='2025-03-24'");
        //$qry = $dbconn->prepare("SELECT * FROM registros WHERE usuario='" . $sesion[0] . "' and eco='" . $eco . "' and dia='2025-03-24'");
        $qry->execute();
        $rows = $qry->fetchAll(PDO::FETCH_ASSOC);

        
        $var1=$rows[0]["chequeos"];
        $var2=$rows[0]["latitud"];
        $var3=$rows[0]["longitud"];
        $var4=$rows[0]["isla"];

        $chequeos=explode("|",$var1);
        $lat=explode("|",$var2);
        $long=explode("|",$var3);
        $isla=explode("|",$var4);

        $arreglo = array();

        foreach($chequeos as $i =>$key) {
            array_push($arreglo,["hora" => $key ,"coords" =>["lat" => $lat[$i],"long" => $long[$i]], "nombre" => $isla[$i] ]);
        }
        $response= json_encode($arreglo, JSON_PRETTY_PRINT);
        echo $response;

        */

        $qry = $dbconn->prepare("SELECT * FROM registros WHERE usuario='30' AND eco='ECO-0123' AND dia='2025-03-27'");
$qry->execute();
$rows = $qry->fetchAll(PDO::FETCH_ASSOC);

if (!empty($rows)) {
    $var1 = $rows[0]["chequeos"];
    $var2 = $rows[0]["latitud"];
    $var3 = $rows[0]["longitud"];
    $var4 = $rows[0]["isla"];

    $chequeos = explode("|", $var1);
    $lat = explode("|", $var2);
    $long = explode("|", $var3);
    $isla = explode("|", $var4);

    $arreglo = [];

    foreach ($chequeos as $i => $key) {
        array_push($arreglo, [
            "hora" => $key,
            "coords" => ["lat" => (float)$lat[$i], "lng" => (float)$long[$i]],
            "nombre" => $isla[$i],
        ]);
    }

    header('Content-Type: application/json');
    echo json_encode($arreglo, JSON_PRETTY_PRINT);
} else {
    // Si no se encuentran registros, devuelves un error o estructura vacía
    header('Content-Type: application/json');
    echo json_encode(["error" => "No se encontraron registros"]);
    exit;
}
    break;
}