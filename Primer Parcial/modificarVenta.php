<?php

include_once "Venta.php";

if ($_SERVER['REQUEST_METHOD'] === 'PUT') { 
    $myEntireBody = file_get_contents('php://input');

    $array = explode("&", $myEntireBody);

    
    for($i=0;$i<count($array);$i++){
        $auxArray = explode("=",$array[$i]);
        $obj[$auxArray[0]] = $auxArray[1]; 
    }
    if(is_numeric($obj["cantidad"]) && is_numeric($obj["numeroPedido"]) && Venta::VerificarTipo($obj["tipo"])){ 
        $obj["email"] = str_replace("%40", "@", $obj["email"]);
        $venta = Venta::NuevaVenta($obj["numeroPedido"], $obj["email"], $obj["sabor"], $obj["tipo"], $obj["cantidad"]);
        $venta->ModificarVenta();
    }else{
        echo "Datos mal cargados" . PHP_EOL;
    }
}



?>