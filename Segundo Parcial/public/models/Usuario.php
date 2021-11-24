<?php
include_once "db/AccesoDatos.php";

class Usuario
{
    public $id;
    public $mail;
    public $clave;
    public $tipo;

    public function ValidarDatos(){
        
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT id, mail, clave, tipo FROM usuarios where mail = :mail");
        $consulta->bindValue(':mail', $this->mail, PDO::PARAM_STR);
        $consulta->execute();

        $usuario = $consulta->fetchAll(PDO::FETCH_CLASS, 'Usuario');

        if(count($usuario) > 0 && $usuario[0]->tipo == $this->tipo && password_verify($this->clave, $usuario[0]->clave))
            return true;

        return false;
    }

    public static function obtenerTodos()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT id, mail, clave, tipo FROM usuarios");
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Usuario');
    }

    public function AgregarUsuario()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("INSERT INTO usuarios (mail, clave, tipo) VALUES (:mail, :clave, :tipo)");
        $consulta->bindValue(':mail', $this->mail, PDO::PARAM_STR);
        $consulta->bindValue(':tipo', $this->tipo);
        $claveHash = password_hash($this->clave, PASSWORD_DEFAULT);
        $consulta->bindValue(':clave', $claveHash);
        $consulta->execute();

        return $objAccesoDatos->obtenerUltimoId();
    }


}