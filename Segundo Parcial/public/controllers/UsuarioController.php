<?php
require_once './models/Usuario.php';
require_once './middlewares/TokenAutentificador.php';

class UsuarioController extends Usuario
{
  public function Login($request, $response, $args)
  {
    $payload = json_encode(array("mensaje" => "Datos insuficientes"));

    if (isset($_POST["clave"]) && isset($_POST["mail"]) && isset($_POST["tipo"])) {
      $parametros = $request->getParsedBody();

      $mail = $parametros['mail'];
      $clave = $parametros['clave'];
      $tipo = $parametros['tipo'];

      $usr = new Usuario();
      $usr->mail = $mail;
      $usr->clave = $clave;
      $usr->tipo = $tipo;

      if ($usr->ValidarDatos()) {

        $token = AutentificadorJWT::CrearToken(array("mail" => $mail, "tipo" => $tipo));

        $payload = json_encode(array("mensaje" => "OK", "Token" => $token, "Tipo" => $tipo));
      }else{
        $payload = json_encode(array("mensaje" => "Datos erroneos"));
      }
    }   
    $response->getBody()->write($payload);
    return $response
      ->withHeader('Content-Type', 'application/json');
  }

  public function CargarUno($request, $response, $args)
  {
    if (isset($_POST["mail"]) && isset($_POST["clave"]) && isset($_POST["tipo"])) {
      $parametros = $request->getParsedBody();

      $clave = $parametros['clave'];
      $mail = $parametros['mail'];
      $tipo = $parametros['tipo'];

      // Creamos el usuario
      $usr = new Usuario();
      $usr->clave = $clave;
      $usr->mail = $mail;
      $usr->tipo = $tipo;
      $usr->AgregarUsuario();

      $payload = json_encode(array("mensaje" => "Usuario creado con exito"));

      $response->getBody()->write($payload);
      return $response
        ->withHeader('Content-Type', 'application/json');
    } else {
      $payload = json_encode(array("mensaje" => "Datos insuficientes"));

      $response->getBody()->write($payload);
      return $response
        ->withHeader('Content-Type', 'application/json');
    }
  }

  
}
