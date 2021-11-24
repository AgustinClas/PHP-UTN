<?php

include_once "AccesoDatos.php";
include_once "Venta.php";

class Pizza
{

    public $sabor;
    public $precio;
    public $tipo;
    public $cantidad;
    public $id;

    function __construct($id, $sabor, $precio, $tipo, $cantidad)
    {
        $this->sabor = $sabor;
        $this->precio = $precio;
        $this->tipo = $tipo;
        $this->cantidad = $cantidad;
        $this->id = $id;
    }

    static function VerificarTipo($tipo)
    {
        $tipo = strtolower($tipo);

        if($tipo == "molde" || $tipo == "piedra" ) return $tipo;

        echo "El tipo no es valido. La pizza sera hecha al molde";
        return "molde";
    }

    static function LeerJSON($ruta)
    {
        if (file_exists($ruta)) {
            $arch = fopen($ruta, "r");
            $pizzas = array();
            while (!feof($arch)) {
                $linea = fgets($arch);
                if ($linea != "") {
                    $stdObj = json_decode($linea);
                    if (isset($stdObj)) {
                        $pizza = new Pizza($stdObj->id, $stdObj->sabor, $stdObj->precio, $stdObj->tipo, $stdObj->cantidad);
                        array_push($pizzas, $pizza);
                    }
                }
            }
            fclose($arch);

            if (count($pizzas) > 0)
                return $pizzas;

            return null;
        }
    }

    static function VerificarRuta($ruta){
        if(file_exists($ruta)) return true;

        $archivo = fopen($ruta,"w");
        fclose($archivo);

        return file_exists($ruta);
    }

    
    static function VerificarStock($listaPizza, $nuevaPizza)
    {
        foreach($listaPizza as $pizzaAux) {
            if ($pizzaAux->tipo == $nuevaPizza->tipo && $pizzaAux->sabor == $nuevaPizza->sabor) {
                $pizzaAux->cantidad += $nuevaPizza->cantidad;
                $pizzaAux->precio = $nuevaPizza->precio;
                return true;
            }
        }

        return false;
    }

    static function GuardarEnJSON($ruta, $pizza, $imagen)
    {
        if (Pizza::VerificarRuta($ruta)) {
            $pizzas = Pizza::LeerJSON($ruta);

            if ($pizzas == null)
                $pizzas = array();
            else if(!Pizza::VerificarStock($pizzas,$pizza))
                    array_push($pizzas, $pizza);


            foreach ($pizzas as $key => $pi) {
                if ($key == 0) {
                    $arch = fopen($ruta, "w");
                    fwrite($arch, json_encode($pizzas[0]) . PHP_EOL);
                    fclose($arch);
                } else {
                    $arch = fopen($ruta, "a");
                    fwrite($arch, json_encode($pizzas[$key]) . PHP_EOL);
                    fclose($arch);
                }
            }

            if (!is_dir("ImagenesDePizzas/")) {
                mkdir("ImagenesDePizzas/", 0777, true); 
            }
    
            $destino = "ImagenesDePizzas/" . $pizza->tipo . "-" . $pizza->sabor . ".jpg";
    
            move_uploaded_file($imagen, $destino);

            return true;
        } else return false;
    }

    static function GuardarArrayEnJSON($ruta, $pizzas)
    {
        if (file_exists($ruta)) {

            foreach ($pizzas as $key => $pi) {
                if ($key == 0) {
                    $arch = fopen($ruta, "w");
                    fwrite($arch, json_encode($pizzas[0]) . PHP_EOL);
                    fclose($arch);
                } else {
                    $arch = fopen($ruta, "a");
                    fwrite($arch, json_encode($pizzas[$key]) . PHP_EOL);
                    fclose($arch);
                }
            }
            return true;
        } else return false;
    }

    static function VerificarTipoYSabor($tipo, $sabor)
    {

        $pizzas = Pizza::LeerJSON("Pizzas.json");
        $flagTipo = false;
        $flagSabor = false;

        foreach ($pizzas as $pizza) {
            if ($pizza->tipo == $tipo && $pizza->sabor == $sabor)
                return "Si hay";
            if ($pizza->tipo == $tipo)
                $flagTipo = true;
            else if ($pizza->sabor == $sabor)
                $flagSabor = true;
        }

        if ($flagTipo && $flagSabor)
            return "Hay pizza de este tipo y pizza de este sabor, pero no las dos";
        else if ($flagTipo)
            return "No hay pizza de este sabor";
        else if ($flagSabor)
            return "No hay pizza de este tipo";
        else
            return "No hay pizza de este tipo ni de este sabor";
    }

    static function VerificarCantidadStock($tipo, $sabor, $pizzas, $cantidad)
    {
        foreach ($pizzas as $pizza) {
            if ($pizza->tipo == $tipo && $pizza->sabor == $sabor && $pizza->cantidad >= $cantidad) {
                $pizza->cantidad = $pizza->cantidad - $cantidad;
                Pizza::GuardarArrayEnJSON("Pizzas.json", $pizzas);
                return true;
            }
        }
        echo "No hay stock disponible" . PHP_EOL;
        return false;
    }


    
}
