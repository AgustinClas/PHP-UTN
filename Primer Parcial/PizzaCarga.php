<?php
    include_once "pizza.php";

    if(isset($_POST["sabor"]) && isset($_POST["precio"]) && isset($_POST["tipo"]) && isset($_POST["cantidad"]) && isset($_FILES["imagen"])){

        if(is_numeric($_POST["cantidad"]) && is_numeric($_POST["precio"])){ 
        $tipo = Pizza::VerificarTipo($_POST["tipo"]);
        $nuevaPizza = new Pizza(rand(1,100), $_POST["sabor"], $_POST["precio"], $tipo, $_POST["cantidad"]);

        Pizza::GuardarEnJSON("Pizzas.json", $nuevaPizza, $_FILES["imagen"]["tmp_name"]);
        }else{
            echo "Datos mal cargados" . PHP_EOL;
        }
        

    }else{
        echo "Datos insuficientes" . PHP_EOL;
    }



?>