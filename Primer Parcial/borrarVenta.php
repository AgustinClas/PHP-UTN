<?php

include_once "Venta.php";

if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    $myEntireBody = file_get_contents('php://input');

    $array = explode("=", $myEntireBody);

    Venta::BorrarVenta($array[1]);
    
}
?>