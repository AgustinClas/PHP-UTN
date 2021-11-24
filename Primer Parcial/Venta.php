<?php
include_once "AccesoDatos.php";
include_once "pizza.php";
class Venta
{

    public $email;
    public $sabor;
    public $tipo;
    public $cantidad;
    public $fecha;
    public $numeroPedido;
    public $id;

    static function NuevaVenta($numPedido, $usuario, $sabor, $tipo, $cantidad)
    {
        $venta = new Venta();
        $venta->email = $usuario;
        $venta->sabor = $sabor;
        $venta->tipo = $tipo;
        $venta->numeroPedido = $numPedido;
        $venta->cantidad = $cantidad;

        return $venta;
    }

    static function GenerarVenta($tipo, $sabor, $usuario, $cantidad, $imagen)
    {

        $pizzas = Pizza::LeerJSON("Pizzas.json");

        if (Pizza::VerificarCantidadStock($tipo, $sabor, $pizzas, $cantidad)) {
            $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
            $consulta = $objetoAccesoDato->RetornarConsulta("INSERT into ventas (
                sabor,tipo,cantidad,numero_Pedido,fecha_de_registro, mail_usuario)
                values(:sabor,:tipo,:cantidad,:numero_Pedido,:fecha,:mail_usuario)");
            $consulta->bindValue(':sabor', $sabor, PDO::PARAM_STR);
            $consulta->bindValue(':tipo', $tipo, PDO::PARAM_STR);
            $consulta->bindValue(':cantidad', $cantidad, PDO::PARAM_INT);
            $consulta->bindValue(':numero_Pedido', rand(1, 10000), PDO::PARAM_INT);
            $consulta->bindValue(':fecha', date("y-m-d"), PDO::PARAM_STR);
            $consulta->bindValue(':mail_usuario', $usuario, PDO::PARAM_STR);

            $consulta->execute();

            Venta::GuardarImagenVenta($tipo, $sabor, $usuario, $imagen);
            

            if(is_file("cupon-" . $usuario . ".txt")){
                

                $precio = Venta::ObtenerPrecio($tipo, $sabor, $pizzas);

                $importeFinal["PrecioFinal"] = $precio * 0.9;
                $importeFinal["Descuento"] = $precio * 0.1;

                $cupon = fopen("cupon-" . $usuario . ".txt", "a");
                fwrite($cupon, json_encode($importeFinal));
                fclose($cupon);

                rename("cupon-" . $usuario . ".txt", "cupon-" . $usuario . ".txt");
            }
        }
    }

    static function ObtenerPrecio($tipo,$sabor,$listaPizza){
        foreach($listaPizza as $pizzaAux) {
            if ($pizzaAux->tipo == $tipo && $pizzaAux->sabor == $sabor) {
                return $pizzaAux->precio;
            }
        }
    }
    static function GuardarImagenVenta($tipo, $sabor, $usuario, $imagen)
    {

        $mail = explode("@", $usuario);

        if (!is_dir("ImagenesDeLaVenta/")) {
            mkdir("ImagenesDeLaVenta/", 0777, true);
        }

        $destino = "ImagenesDeLaVenta/" . $sabor . "-" . $tipo . "-" . $mail[0] . "-" . date("y-m-d") . ".jpg";

        move_uploaded_file($imagen, $destino);
    }


    static function VerificarTipo($tipo)
    {
        $tipo = strtolower($tipo);

        if($tipo == "molde" || $tipo == "piedra" ) return true;

        
        return false;
    }

    static function CantidadPizzasVendidas($fecha)
    {
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
        $consulta = $objetoAccesoDato->RetornarConsulta("SELECT SUM(cantidad) FROM ventas where fecha_de_registro = :fecha");
        $consulta->bindValue(':fecha', $fecha, PDO::PARAM_STR);
        $consulta->execute();

        $cantidad = $consulta->fetch();

        if(!is_numeric($cantidad["SUM(cantidad)"])) $cantidad["SUM(cantidad)"] = 0;

        echo "Cantidad de pizzas vendidas: " . $cantidad["SUM(cantidad)"] . PHP_EOL . PHP_EOL;
    }

    static function ListarVentasEntreFechas($fecha1, $fecha2)
    {
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();

        echo "Listado de ventas entre " . $fecha1 . " y " . $fecha2 . ": " . PHP_EOL;
        $consulta = $objetoAccesoDato->RetornarConsulta("Select id, mail_usuario as email, sabor, tipo, cantidad, fecha_de_registro as fecha, numero_Pedido as numeroPedido from ventas WHERE fecha_de_registro >= :fecha1 && fecha_de_registro <= :fecha2 order by sabor");
        $consulta->bindValue(':fecha1', $fecha1, PDO::PARAM_STR);
        $consulta->bindValue(':fecha2', $fecha2, PDO::PARAM_STR);

        $consulta->execute();
        $consulta->setFetchMode(PDO::FETCH_INTO, new Venta);

        $listadoVentas = $consulta->fetchAll(PDO::FETCH_CLASS, "Venta");
        Venta::ListarVentas($listadoVentas);
    }

    static function ListarSegunUsuario($usuario)
    {
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();

        $consulta = $objetoAccesoDato->RetornarConsulta("Select id, mail_usuario as email, sabor, tipo, cantidad, fecha_de_registro as fecha, numero_Pedido as numeroPedido from ventas WHERE mail_usuario = :usuario");
        $consulta->bindValue(':usuario', $usuario, PDO::PARAM_STR);

        $consulta->execute();
        $consulta->setFetchMode(PDO::FETCH_INTO, new Venta);

        $listadoVentas = $consulta->fetchAll(PDO::FETCH_CLASS, "Venta");

        if(count($listadoVentas) < 1) echo "Este usuario no efectuo ventas";
        else{
            echo "Listado de ventas del usuario " . $usuario . ": " . PHP_EOL;
            Venta::ListarVentas($listadoVentas);
        }
    }

    static function ListarSegunSabor($sabor)
    {
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();

        $consulta = $objetoAccesoDato->RetornarConsulta("Select id, mail_usuario as email, sabor, tipo, cantidad, fecha_de_registro as fecha, numero_Pedido as numeroPedido from ventas WHERE sabor = :sabor");
        $consulta->bindValue(':sabor', $sabor, PDO::PARAM_STR);

        $consulta->execute();
        $consulta->setFetchMode(PDO::FETCH_INTO, new Venta);

        $listadoVentas = $consulta->fetchAll(PDO::FETCH_CLASS, "Venta");
        
        if(count($listadoVentas) < 1) echo "No hay ventas de este sabor";
        else{
            echo "Listado de ventas del sabor " . $sabor . ": " . PHP_EOL;
            Venta::ListarVentas($listadoVentas);
        }
    }

    static function ListarVentas($ventas)
    {
        foreach ($ventas as $venta) {
            $venta->ListarVenta();
        }
    }

    function ListarVenta()
    {
        echo $this->numeroPedido . "-" . $this->tipo . "-" . $this->sabor . "-" . $this->email . "-" . $this->cantidad . "-" . $this->fecha . PHP_EOL;
    }

    function ModificarVenta()
    {
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
        $consulta = $objetoAccesoDato->RetornarConsulta("Select id, mail_usuario as email, sabor, tipo, cantidad, fecha_de_registro as fecha, numero_Pedido as numeroPedido from ventas WHERE numero_Pedido = :numPedido");
        $consulta->bindValue(':numPedido', $this->numeroPedido, PDO::PARAM_STR);

        $consulta->execute();
        $consulta->setFetchMode(PDO::FETCH_INTO, new Venta);
        $listadoVentas = $consulta->fetchAll(PDO::FETCH_CLASS, "Venta");

        if (count($listadoVentas) > 0) {
            $consulta = $objetoAccesoDato->RetornarConsulta("UPDATE `ventas` SET `mail_usuario`= :usuario ,`sabor`= :sabor,`tipo`= :tipo ,`cantidad`= :cantidad WHERE numero_Pedido = :numPedido");
            $consulta->bindValue(':sabor', $this->sabor, PDO::PARAM_STR);
            $consulta->bindValue(':tipo', $this->tipo, PDO::PARAM_STR);
            $consulta->bindValue(':cantidad', $this->cantidad, PDO::PARAM_INT);
            $consulta->bindValue(':usuario', $this->email, PDO::PARAM_STR);
            $consulta->bindValue(':numPedido', $this->numeroPedido, PDO::PARAM_STR);

            $consulta->execute();
        } else
            echo "Este numero de pedido no existe" . PHP_EOL;
    }

    static function BorrarVenta($numeroPedido)
    {
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
        $consulta = $objetoAccesoDato->RetornarConsulta("Select id, mail_usuario as email, sabor, tipo, cantidad, fecha_de_registro as fecha, numero_Pedido as numeroPedido from ventas WHERE numero_Pedido = :numPedido");
        $consulta->bindValue(':numPedido', $numeroPedido, PDO::PARAM_STR);

        $consulta->execute();
        $consulta->setFetchMode(PDO::FETCH_INTO, new Venta);
        $listadoVentas = $consulta->fetchAll(PDO::FETCH_CLASS, "Venta");

        if (count($listadoVentas) > 0) {
            $consulta = $objetoAccesoDato->RetornarConsulta("DELETE FROM `ventas` WHERE numero_Pedido = :numPedido");
            $consulta->bindValue(':numPedido', $numeroPedido, PDO::PARAM_STR);
            $consulta->execute();

            $mail = explode("@", $listadoVentas[0]->email);

            $imagen = "ImagenesDeLaVenta/" . $listadoVentas[0]->sabor . "-" . $listadoVentas[0]->tipo . "-" . $mail[0] . "-" . $listadoVentas[0]->fecha . ".jpg";
            $destino = "BACKUPVENTAS/" .  $listadoVentas[0]->sabor . "-" . $listadoVentas[0]->tipo . "-" . $mail[0] . "-" . $listadoVentas[0]->fecha . ".jpg";

            if (!is_dir("BACKUPVENTAS/")) {
                mkdir("BACKUPVENTAS/", 0777, true);
            }
    
            move_uploaded_file($imagen, $destino);

        } else
            echo "Este numero de pedido no existe" . PHP_EOL;
    }

    static function DevolverPizza($numeroPedido, $causa, $imagen){

        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
        $consulta = $objetoAccesoDato->RetornarConsulta("Select id, mail_usuario as email, sabor, tipo, cantidad, fecha_de_registro as fecha, numero_Pedido as numeroPedido from ventas WHERE numero_Pedido = :numPedido");
        $consulta->bindValue(':numPedido', $numeroPedido, PDO::PARAM_STR);

        $consulta->execute();
        $consulta->setFetchMode(PDO::FETCH_INTO, new Venta);
        $listadoVentas = $consulta->fetchAll(PDO::FETCH_CLASS, "Venta");

        if (count($listadoVentas) > 0){
            Venta::GenerarCupon($listadoVentas[0]->email,$imagen);
            Venta::RegistrarDevolucion($numeroPedido, $causa);
        }
    }

    static function RegistrarDevolucion($numeroPedido, $causa){
        Venta::VerificarRuta("devoluciones.json");
        $arch = fopen("devoluciones.json", "a");


        $queja["numeroPedido"] = $numeroPedido;
        $queja["causa"] = $causa;

        fwrite($arch, json_encode($queja) . PHP_EOL);
        fclose($arch);
    }

    static function GenerarCupon($cliente,$imagen){
        
        Venta::VerificarRuta("cupones.json");
        $arch = fopen("cupones.json", "a");

        if (!is_dir("ClientesEnojados/")) {
            mkdir("ClientesEnojados/", 0777, true); 
        }

        $destino = "ClientesEnojados/" . $cliente . ".jpg";

        $clienteEnojado["usuario"] = $cliente;
        $clienteEnojado["imagen"] = $destino;

        fwrite($arch, json_encode($clienteEnojado) . PHP_EOL);
        fclose($arch);

        move_uploaded_file($imagen, $destino);

        Venta::VerificarRuta("cupon-" . $cliente . ".txt");
        $cupon = fopen("cupon-" . $cliente . ".txt", "w");
        $textoCupon = "Usted tiene un 10% de descuento en su proxima compra, disculpe las molestias";
        fwrite($cupon, $textoCupon);
    }


    static function VerificarRuta($ruta){
        if(file_exists($ruta)) return true;

        $archivo = fopen($ruta,"w");
        fclose($archivo);

        return file_exists($ruta);
    }
}
