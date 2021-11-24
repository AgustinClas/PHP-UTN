<?php

use Slim\Psr7\Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\Handlers\Strategies\RequestHandler;
use Slim\Routing\RouteCollectorProxy;
use Slim\Routing\RouteContext;

class Logger
{
    public static function LogVendedor(Request $request, $handler)
    {
        $payload = "Perfil erroneo";

        try {

            $token = $request->getHeader("token")[0];

            AutentificadorJWT::VerificarToken($token);
            if (AutentificadorJWT::ObtenerData($token)->tipo == "vendedor") {
                return $handler->handle($request);
            }
        } catch (Exception $e) {
            $payload = json_encode(array("mensaje" => "Token no validado"));
            var_dump($e);
        }
        $response = new Response();
        $response->getbody()->write($payload);
        return $response;
    }

    public static function LogAdmin(Request $request, $handler)
    {
        $payload = json_encode(array("mensaje" => "Perfil Erroneo"));

        try {

            $token = $request->getHeader("token")[0];

            AutentificadorJWT::VerificarToken($token);
            if (AutentificadorJWT::ObtenerData($token)->tipo == "admin") {
                return $handler->handle($request);
            }
        } catch (Exception $e) {
            $payload = json_encode(array("mensaje" => "Token no validado"));
            var_dump($e);
        }
        $response = new Response();
        $response->getbody()->write($payload);
        return $response;
    }

   

    public static function LogUsuario(Request $request, $handler)
    {
        try {

            $token = $request->getHeader("token")[0];

            AutentificadorJWT::VerificarToken($token);

            return $handler->handle($request);

        } catch (Exception $e) {
            var_dump($e);
            $payload = json_encode(array("mensaje" => "Token no validado"));
            $response = new Response();
            $response->getbody()->write($payload);
            return $response;
        }
        
    }
}
