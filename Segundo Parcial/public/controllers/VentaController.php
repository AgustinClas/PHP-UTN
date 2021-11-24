<?php
require_once './models/Venta.php';
require_once './models/PDF.php';
require_once './models/CriptoMoneda.php';

class VentaController extends VentaCripto
{
    public function CargarUno($request, $response, $args)
    {
        $payload = json_encode(array("mensaje" => "Datos insuficientes"));

        if (isset($_POST["cantidad"]) && isset($_POST["nombre"]) && isset($_POST["nacionalidad"]) && isset($_POST["cliente"]) && isset($_FILES["foto"])) {
            $parametros = $request->getParsedBody();

            $nombre = $parametros['nombre'];
            $nacionalidad = $parametros['nacionalidad'];
            $cliente = $parametros['cliente'];
            $cantidad = $parametros['cantidad'];


            if (CriptoMoneda::BuscarCriptoMoneda($nombre)) {

                $venta = new VentaCripto();
                $venta->nombre = $nombre;
                $venta->nacionalidad = $nacionalidad;
                $venta->cliente = $cliente;
                $venta->cantidad = $cantidad;

                $venta->CargarVenta($_FILES["foto"]["tmp_name"]);
                $payload = json_encode(array("mensaje" => "Venta cargada con exito"));
            } else {
                $payload = json_encode(array("mensaje" => "No existe esta criptoMoneda"));
            }
        }

        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function TraerPorNacionalidadYFecha($request, $response, $args)
    {

        $payload = "No hay ventas de nacionalidad alemana entre estas fechas";

        $listado = VentaCripto::ObtenerListadoPorNacionalidadYFecha("alemania", "2021-06-10", "2021-06-13");

        if ($listado != null) $payload = json_encode(array("listado" => $listado));

        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function TraerUsuariosPorNombre($request, $response, $args)
    {

        $payload = "No hay ventas de esta cripto";

        $nombre = $args["nombre"];

        $listado = VentaCripto::ObtenerListadoPorNombre($nombre);

        if ($listado != null) $payload = json_encode(array("listado" => $listado));

        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function DescargarPDF($request, $response){
        $listado = VentaCripto::ObtenerListado();
        $arrayVentas = array();

        foreach($listado as $venta){
            $arrV = array();
            $cliente = explode("@", $venta->cliente);
            array_push($arrV, $venta->nombre);
            array_push($arrV, $venta->nacionalidad);
            array_push($arrV, $cliente[0]);
            array_push($arrV, $venta->cantidad);
            array_push($arrV, $venta->fecha);
            array_push($arrayVentas, $arrV);
        } 

        $payload = json_encode($listado);
        $pdf = PDF::CrearPDF($arrayVentas);
       
        $pdf->Output("listado.pdf", 'F', true);


        $response->getBody()->write($payload);
        return $response
        ->withHeader('Content-Type', 'application/json');
    }


}