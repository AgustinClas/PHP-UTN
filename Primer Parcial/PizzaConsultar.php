<?php
    include_once "pizza.php";

    if(isset($_POST["sabor"]) && isset($_POST["tipo"])){
        echo Pizza::VerificarTipoYSabor($_POST["tipo"], $_POST["sabor"]);
    }else{
        echo "Datos insuficientes";
    }


?>