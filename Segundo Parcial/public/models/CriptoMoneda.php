<?php
include_once "db/AccesoDatos.php";

class CriptoMoneda
{
    public $id;
    public $precio;
    public $nombre;
    public $nacionalidad;
    public $estado;

    public function AgregarCriptoMoneda($imagen)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("INSERT INTO criptoMonedas (precio, nombre, nacionalidad, estado) VALUES (:precio, :nombre, :nacionalidad, 'activa')");
        $consulta->bindValue(':precio', $this->precio);
        $consulta->bindValue(':nacionalidad', $this->nacionalidad);
        $consulta->bindValue(':nombre', $this->nombre);

        try{
        $consulta->execute();

        return $this->GuardarImagen($imagen);

        }catch(Exception $e){
            return false;
        }
    }

    public function GuardarImagen($imagen){

        if (!is_dir("CriptoMonedas/")) {
            mkdir("CriptoMonedas/", 0777, true); 
        }

        $destino = "CriptoMonedas/" . $this->nombre . ".jpg";

        return move_uploaded_file($imagen, $destino);
    }

    public static function ModificarImagen($imagen, $id){

        $cripto = CriptoMoneda::TraerCriptoMonedaPorId($id);
        if($cripto != null){

            if (!is_dir("BackUp/")) {
                mkdir("BackUp/", 0777, true); 
            }
    
            $destino = "BackUp/" . $cripto->nombre . ".jpg";
    
            try{
                move_uploaded_file($imagen, $destino);
            }catch(exception $e){
                return false;
            } 
        }

        return true;
    }

    public static function ObtenerListado(){

        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT id, nombre, nacionalidad, precio, estado FROM criptoMonedas where estado = 'activo'");

        $consulta->execute();

        
        $listado = $consulta->fetchAll(PDO::FETCH_CLASS, 'CriptoMoneda');
        
        return $listado;
    }

    public static function ObtenerListadoPorNacionalidad($nacionalidad){
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT id, nombre, nacionalidad, precio, estado FROM criptoMonedas where nacionalidad = :nacionalidad and estado = 'activo'");
        $consulta->bindValue(':nacionalidad', $nacionalidad);

        $consulta->execute();

        $listado = $consulta->fetchAll(PDO::FETCH_CLASS, 'criptoMoneda');

        return $listado;
    }

    public static function TraerCriptoMonedaPorId($id){

        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT id, nombre, nacionalidad, precio, estado FROM criptoMonedas where id = :id and estado = 'activo'");
        $consulta->bindValue(':id', $id);

        $consulta->execute();

        $criptoLista = $consulta->fetchAll(PDO::FETCH_CLASS, 'CriptoMoneda');

        if(count($criptoLista) > 0) return $criptoLista[0];

        return null;
    }

    public static function BuscarCriptoMoneda($nombre){

        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT id, nombre, nacionalidad, precio, estado FROM criptoMonedas where nombre = :nombre and estado = 'activo'");
        $consulta->bindValue(':nombre', $nombre);

        $consulta->execute();

        $lista = $consulta->fetchAll(PDO::FETCH_CLASS, 'CriptoMoneda');


        if(count($lista) > 0) return true;

        return false;
    }

    public static function BuscarCriptoMonedaPorId($id){

        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT id, nombre, nacionalidad, precio, estado FROM criptoMonedas where id = :id and estado = 'activo'");
        $consulta->bindValue(':id', $id);

        $consulta->execute();

        $lista = $consulta->fetchAll(PDO::FETCH_CLASS, 'CriptoMoneda');


        if(count($lista) > 0) return true;

        return false;
    }

    public static function BorrarCriptoMoneda($id){

        
        if(CriptoMoneda::BuscarCriptoMonedaPorId($id)){
            $objAccesoDatos = AccesoDatos::obtenerInstancia();
            $consulta = $objAccesoDatos->prepararConsulta("UPDATE criptoMonedas set  estado = 'baja' where id = :id");
            $consulta->bindValue(':id', $id);

            return $consulta->execute();
        }

        return false;
    }

    public static function ModificarCriptoMoneda($id, $value, $param){ 


        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("UPDATE criptoMonedas set " . $param ." = :valor where id = :id");

        $consulta->bindValue(':id', $id);
        $consulta->bindValue(':valor', $value);


        $consulta->execute();         
    }
}

?>