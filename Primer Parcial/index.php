<?php

if (isset($_GET["archivo"])) {
    $opcion = $_GET["archivo"];

    switch ($opcion) {
        case "carga":
            include_once "PizzaCarga.php";
            break;
        case "consulta":
            include_once "PizzaConsultar.php";
            break;
        case "venta":
            include_once "AltaVenta.php";
            break;
        case "ConsultaVentas":
            include_once "ConsultaVentas.php";
            break;
        case "modificar":
            include_once "modificarVenta.php";
            break;
        case "borrar":
            include_once "borrarVenta.php";
            break;
        case "devolver":
            include_once "DevolverPizza.php";
            break;
    }
}else{
    echo "Datos insuficientes" . PHP_EOL;
}

?>
