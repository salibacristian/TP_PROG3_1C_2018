<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;


require_once './composer/vendor/autoload.php';
require_once './AccesoDatos.php';
require_once './Model/User.php';
require_once './AutentificadorJWT.php';
require_once './MW/MWparaAutentificar.php';
require_once './Aplication/SessionService.php';
require_once './Aplication/UserService.php';
require_once './Aplication/RestaurantTableService.php';
require_once './Aplication/OrderService.php';

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

$app->group('/Table', function () {
  
     $this->get('/tables/', \RestaurantTableService::class . ':GetTables')->add(\MWparaAutentificar::class . ':VerificarPerfil');//bystatus admin or waiter only
  
     $this->get('/', \RestaurantTableService::class . ':GetTablesInfo')->add(\MWparaAutentificar::class . ':VerificarPerfil');//admin only
 
     $this->post('/', \RestaurantTableService::class . ':SaveNewTable')->add(\MWparaAutentificar::class . ':VerificarPerfil');//admin only
 
     $this->delete('/', \RestaurantTableService::class . ':DeleteTable')->add(\MWparaAutentificar::class . ':VerificarPerfil');//admin only
      
 })->add(\MWparaAutentificar::class . ':VerificarToken');

 $app->group('/Order', function () {
  
      $this->get('/', \OrderService::class . ':GetOrders');
  
      $this->post('/', \OrderService::class . ':NewOrder');//waiter only

      $this->put('/', \OrderService::class . ':TakeOrder');//producer only

      $this->put('/finish/', \OrderService::class . ':FinishOrder');//producer only

      $this->put('/cancel/', \OrderService::class . ':CancelOrder');//admin only(cerrar mesa)

      
 })->add(\MWparaAutentificar::class . ':VerificarToken');
 
 
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