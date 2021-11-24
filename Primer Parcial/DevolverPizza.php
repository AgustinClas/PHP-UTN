<?php
    include_once "Venta.php";

    if(isset($_POST["numeroPedido"]) && isset($_POST["causa"]) &&  isset($_FILES["imagen"])){
        if(is_numeric($_POST["numeroPedido"]))
            Venta::DevolverPizza($_POST["numeroPedido"], $_POST["causa"], $_FILES["imagen"]["tmp_name"]);
        else   
            echo "Datos mal cargados" . PHP_EOL;
    }else{
        echo "Datos insuficientes" . PHP_EOL;
    }

?>