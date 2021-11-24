<?php
include_once "Venta.php";
include_once "AccesoDatos.php";

if (isset($_GET["opcionConsulta"])) {
    $opcionConsulta = $_GET["opcionConsulta"];

    switch ($opcionConsulta) {
        case "a":
            if(isset($_POST["fecha"]))
                Venta::CantidadPizzasVendidas($_POST["fecha"]);
            else
                Venta::CantidadPizzasVendidas(date("y-m-d"));
            break;       
        case "b":
            if (isset($_POST["fecha1"]) && isset($_POST["fecha2"]))
                Venta::ListarVentasEntreFechas($_POST["fecha1"], $_POST["fecha2"]);
            else
                echo "Datos insuficientes" . PHP_EOL;
            break;
        case "c":
            if (isset($_POST["usuario"]))
                Venta::ListarSegunUsuario($_POST["usuario"]);
            else
                echo "Datos insuficientes" . PHP_EOL;
            break;
        case "d":
            if (isset($_POST["sabor"]))
                Venta::ListarSegunSabor($_POST["sabor"]);
            else
                echo "Datos insuficientes" . PHP_EOL;

            break;
    }
} else
    echo "Datos insuficientes" . PHP_EOL;

?>
