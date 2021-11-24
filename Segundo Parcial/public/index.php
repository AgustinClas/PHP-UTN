<?php
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\Handlers\Strategies\RequestHandler;
use Slim\Routing\RouteCollectorProxy;
use Slim\Routing\RouteContext;

require __DIR__ . '/../vendor/autoload.php';
require_once './controllers/UsuarioController.php';
require_once './controllers/CriptoMonedaController.php';
require_once './controllers/VentaController.php';

require_once './db/AccesoDatos.php';
require_once './middlewares/TokenAutentificador.php';
require_once './middlewares/Logger.php';


$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->safeLoad();

$app = AppFactory::create();
$app->setBasePath('/public');
$app->addRoutingMiddleware();

$errorMiddleware = $app->addErrorMiddleware(true, true, true);
// peticiones
$app->group('/', function (RouteCollectorProxy $group) {
  $group->post('login', \UsuarioController::class . ':Login');
  $group->post('alta', \UsuarioController::class . ':CargarUno');
});

$app->group('/criptoMoneda', function (RouteCollectorProxy $group) {
  $group->post('/alta', \CriptoMonedaController::class . ':CargarUno')->add(\Logger::class . ':LogAdmin');
  $group->get('/listar', \CriptoMonedaController::class . ':TraerTodos');
  $group->get('/listarPorNacionalidad/{nacionalidad}', \CriptoMonedaController::class . ':TraerTodosPorNacionalidad');
  $group->get('/traerPorId/{id}', \CriptoMonedaController::class . ':TraerUnoPorId')->add(\Logger::class . ':LogUsuario');
  $group->delete('/borrar', \CriptoMonedaController::class . ':BorrarUno')->add(\Logger::class . ':LogAdmin');
  $group->put('/modificar', \CriptoMonedaController::class . ':ModificarUno')->add(\Logger::class . ':LogAdmin');



});

$app->group('/venta', function (RouteCollectorProxy $group) {
  $group->post('/alta', \VentaController::class . ':CargarUno')->add(\Logger::class . ':LogUsuario');
  $group->get('/nacionalidadAlemana', \VentaController::class . ':TraerPorNacionalidadYFecha')->add(\Logger::class . ':LogAdmin');
  $group->get('/usuariosPorCripto/{nombre}', \VentaController::class . ':TraerUsuariosPorNombre')->add(\Logger::class . ':LogAdmin');
  $group->get('/GenerarPDF', \VentaController::class . ':DescargarPDF');
});



// Run app
$app->run();

?>

