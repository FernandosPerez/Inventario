<?php

$var1="04:03:47|04:04:11|04:04:30";
$var2="19.4951|19.4951|19.4951";
$var3="-99.1763|-99.1763|-99.1763";
$chequeos=explode("|",$var1);
$lat=explode("|",$var2);
$long=explode("|",$var3);

$arreglo = array();

foreach($chequeos as $i =>$key) {
    array_push($arreglo,["hora" => $key ,"coords" =>["lat" => $lat[$i],"long" => $long[$i]] ]);
}
header('Content-type:application/json;charset=utf-8');
echo json_encode($arreglo, JSON_PRETTY_PRINT);