<?php
require_once './models/CriptoMoneda.php';
require_once './middlewares/TokenAutentificador.php';

class CriptoMonedaController extends CriptoMoneda
{

    public function CargarUno($request, $response, $args)
    {
        $payload = json_encode(array("mensaje" => "Datos insuficientes"));;

        if (isset($_POST["precio"]) && isset($_POST["nombre"]) && isset($_POST["nacionalidad"]) && isset($_FILES["foto"])) {
            $parametros = $request->getParsedBody();

            $nombre = $parametros['nombre'];
            $precio = $parametros['precio'];
            $nacionalidad = $parametros['nacionalidad'];

            $criptoMoneda = new CriptoMoneda();
            $criptoMoneda->nacionalidad = $nacionalidad;
            $criptoMoneda->nombre = $nombre;
            $criptoMoneda->precio = $precio;

            if ($criptoMoneda->AgregarCriptoMoneda($_FILES["foto"]["tmp_name"])) $payload = json_encode(array("mensaje" => "CriptoMoneda creada con exito"));
            else  $payload = json_encode(array("mensaje" => "No se pudo agregar la criptoMoneda"));
        }

        $response->getBody()->write($payload);
        return $response
            ->withHeader('Content-Type', 'application/json');
    }

    public function TraerTodos($request, $response, $args)
    {
        $payload = json_encode(array("mensaje" => "No hay CriptoMonedas cargadas"));

        $lista = CriptoMoneda::obtenerListado();
        if(count($lista) > 0) $payload = json_encode(array("listaCriptoMonedas" => $lista));        

        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function TraerTodosPorNacionalidad($request, $response, $args)
    {
        $payload = json_encode(array("mensaje" => "No hay CriptoMonedas de esta nacionalidad"));

        $nacionalidad = $args["nacionalidad"];
        $lista = CriptoMoneda::obtenerListadoPorNacionalidad($nacionalidad);

        if (count($lista) > 0)
            $payload = json_encode(array("listaCriptoMonedas" => $lista));


        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function TraerUnoPorId($request, $response, $args)
    {
        $payload = json_encode(array("mensaje" => "Id inexistente"));

        $id = $args['id'];

        if (is_numeric($id)) {
        $cripto = CriptoMoneda::TraerCriptoMonedaPorId($id);
        if($cripto != null) $payload = json_encode(array("CriptoMoneda" => $cripto));
        }

        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function BorrarUno($request, $response, $args)
    {
        $payload = json_encode(array("mensaje" => "Datos insuficientes"));

        $myEntireBody = file_get_contents('php://input');

        $arrayData = explode("&", $myEntireBody);
        $arrayId = explode("=", $arrayData[0]);

        if ($arrayId[0] == "id") {
            if (CriptoMoneda::BorrarCriptoMoneda($arrayId[1]))
                $payload = json_encode(array("mensaje" => "CriptoMoneda borrada"));
            else
                $payload = json_encode(array("mensaje" => "CriptoMoneda inexistente"));
        }


        $response->getBody()->write($payload);
        return $response
            ->withHeader('Content-Type', 'application/json');
    }

    public function ModificarUno($request, $response, $args)
    {
        $payload = json_encode(array("mensaje" => "Datos insuficientes o mal cargados"));

        $myEntireBody = file_get_contents('php://input');

        $arrayData = explode("&", $myEntireBody);

        $arrayId = explode("=", $arrayData[0]);
        
        if ($arrayId[0] == "id") {
            $id = $arrayId[1];
            if(CriptoMoneda::BuscarCriptoMonedaPorId($id)){

                array_splice($arrayData, 0, 1);
                $flag = false;
    
    
                foreach ($arrayData as $param) {
                    $arrayParam = explode("=", $param);
    
                    switch ($arrayParam[0]) {
                        case "nombre":
                            CriptoMoneda::ModificarCriptoMoneda($id, $arrayParam[1], "nombre");
                            $flag = true;
                            break;
                        case "nacionalidad":
                            CriptoMoneda::ModificarCriptoMoneda($id, $arrayParam[1], "nacionalidad");
                            $flag = true;
                            break;
                        case "precio":
                            CriptoMoneda::ModificarCriptoMoneda($id, $arrayParam[1], "precio");
                            $flag = true;
                            break;
                        case "foto":
                            $flag = CriptoMoneda::ModificarImagen($arrayParam[1],$id);
                            break;
                    }
                }

                if($flag) $payload = json_encode(array("mensaje" => "Datos modificados"));

            }else $payload = json_encode(array("mensaje" => "Id inexistente"));
        }

        $response->getBody()->write($payload);
        return $response
            ->withHeader('Content-Type', 'application/json');
    }

}

?>
