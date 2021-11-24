<?php
    include_once "Venta.php";

    if(isset($_POST["sabor"]) && isset($_POST["usuario"]) && isset($_POST["tipo"]) && isset($_POST["cantidad"]) &&  isset($_FILES["imagen"])){
        if(is_numeric($_POST["cantidad"]) && Venta::VerificarTipo($_POST["tipo"]))
            Venta::GenerarVenta($_POST["tipo"],$_POST["sabor"],$_POST["usuario"],$_POST["cantidad"], $_FILES["imagen"]["tmp_name"]);
        else   
            echo "Datos mal cargados" . PHP_EOL;
    }else{
        echo "Datos insuficientes" . PHP_EOL;
    }

?>