<?php
include_once "db/AccesoDatos.php";
include_once "CriptoMoneda.php";

class VentaCripto
{

    public $cliente;
    public $cantidad;
    public $fecha;
    public $nombre;
    public $id;
    public $nacionalidad;

    public function CargarVenta($imagen)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();

        $consulta = $objAccesoDatos->PrepararConsulta("INSERT INTO ventas (
                cliente, cantidad, fecha, nombre, nacionalidad)
                values (:cliente,  :cantidad,  :fecha, :nombre, :nacionalidad)");

        $consulta->bindValue(':cliente', $this->cliente, PDO::PARAM_STR);
        $consulta->bindValue(':cantidad', $this->cantidad, PDO::PARAM_INT);
        $consulta->bindValue(':nombre', $this->nombre, PDO::PARAM_INT);
        $consulta->bindValue(':nacionalidad', $this->nacionalidad, PDO::PARAM_INT);
        $consulta->bindValue(':fecha', date("y-m-d"), PDO::PARAM_STR);

        $consulta->execute();

        $this->GuardarImagenVenta($imagen);
    }

    function GuardarImagenVenta($imagen)
    {

        $cliente = explode("@", $this->cliente);

        if (!is_dir("FotosCripto/")) {
            mkdir("FotosCripto/", 0777, true);
        }

        $destino = "FotosCripto/" . $cliente[0] . "-" . $this->nombre . "-" . date("y-m-d") . ".jpg";

        move_uploaded_file($imagen, $destino);
    }

    public static function ObtenerListadoPorNacionalidadYFecha($nacionalidad, $fecha1, $fecha2){

        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT id, cliente, cantidad, fecha, nombre, nacionalidad FROM ventas where nacionalidad = :nacionalidad and fecha >= :fecha1 and fecha <= :fecha2");
        $consulta->bindValue(':nacionalidad', $nacionalidad);
        $consulta->bindValue(':fecha1', $fecha1);
        $consulta->bindValue(':fecha2', $fecha2);

        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'VentaCripto');
    }

    public static function ObtenerListadoPorNombre($nombre){
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT id, cliente, cantidad, fecha, nombre, nacionalidad FROM ventas where nombre = :nombre");
        $consulta->bindValue(':nombre', $nombre);

        $consulta->execute();

        $listado = $consulta->fetchAll(PDO::FETCH_CLASS, 'VentaCripto');
        $arrayUsuarios = [];

        foreach($listado as $venta){
            array_push($arrayUsuarios, $venta->cliente);
        }

        return $arrayUsuarios;
    }

    public static function ObtenerListado(){

        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT id, cliente, cantidad, fecha, nombre, nacionalidad FROM ventas");

        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'VentaCripto');
    }
}