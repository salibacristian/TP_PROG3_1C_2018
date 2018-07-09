<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;


require_once './composer/vendor/autoload.php';
require_once './AccesoDatos.php';
require_once './Model/User.php';
require_once './Aplication/UserService.php';
require_once './AutentificadorJWT.php';
require_once './MW/MWparaAutentificar.php';
require_once './Aplication/SessionService.php';

$config['displayErrorDetails'] = true;
$config['addContentLengthHeader'] = false;


$app = new \Slim\App(["settings" => $config]);

  $app->get('[/]', function (Request $request, Response $response) {    
    $response->getBody()->write("GET => Bienvenido!!! ,a SlimFramework");
    return $response;
  });

  //log---------------------------------------------

  $app->post('/login/', function (Request $request, Response $response) {
    return $response;
}) ->add(\MWparaAutentificar::class . ':VerificarUsuario');

$app->get('/logout/', function (Request $request, Response $response) {
      $data = Session::getInstance();
      $data->destroy();
    return $response->getBody()->write('<p>sesion cerrada</p>');
});
  
//--------------------------------------------------

  $app->group('/User', function () {
 
    $this->get('/user/', \UserService::class . ':GetUser');
 
    $this->get('/', \UserService::class . ':GetAllUsers');

    $this->post('/', \UserService::class . ':SaveUser');

    $this->delete('/', \UserService::class . ':DeleteUser');

    $this->put('/', \UserService::class . ':SuspendUser');

    $this->get('/logs/', \UserService::class . ':GetLogs');
     
})->add(\MWparaAutentificar::class . ':VerificarPerfil')->add(\MWparaAutentificar::class . ':VerificarToken');

// $app->group('/Empleado', function () {
  
//    $this->get('/', \EmpleadoService::class . ':traerTodos');
  
//    $this->get('/empleado/', \EmpleadoService::class . ':traerUno');
 
//    $this->post('/', \EmpleadoService::class . ':CargarUno');
 
//    $this->delete('/', \EmpleadoService::class . ':BorrarUno');
 
//    $this->put('/', \EmpleadoService::class . ':ModificarUno');

//    $this->get('/ingresos/', \EmpleadoService::class . ':TraerIngresos');

//    $this->get('/operaciones/', \EmpleadoService::class . ':TraerOperaciones');
      
//  })->add(\MWparaAutentificar::class . ':VerificarPerfil')->add(\MWparaAutentificar::class . ':VerificarToken');

// $app->get('/cocheras/', function (Request $request, Response $response) {
//       $params = $request->getParams();
//       $cocheras= Cochera::TraerCocheras($params['libres']); 
//       $newResponse = $response->withJson($cocheras, 200); 
//       return $newResponse;
//   });
// $app->get('/cocherasStatus/', function (Request $request, Response $response) {
//       $status= CocheraService::TraerStatus(); 
//       $newResponse = $response->withJson($status, 200); 
//       return $newResponse;
//   });

  



$app->run();