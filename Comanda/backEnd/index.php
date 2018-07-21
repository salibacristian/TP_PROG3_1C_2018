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
/**
   * @api {post} /login/  login
   * @apiVersion 0.1.0
   * @apiName login
   * @apiGroup API
   * @apiParam {text} email correo electronico del usuario
 * @apiParam {text} password contraseña del usuario
 * @apiParamExample email:
 *     email: "chriseze21@gmail.com"
 *  * @apiParamExample password: 
 *     password: "a12345"
 *     
   */
  $app->post('/login/', function (Request $request, Response $response) {
    return $response;
}) ->add(\MWparaAutentificar::class . ':VerificarUsuario');
/**
   * @api {get} /logout/  logout
   * @apiVersion 0.1.0
   * @apiName logout
   * @apiGroup API
   
   */
$app->get('/logout/', function (Request $request, Response $response) {
      $data = Session::getInstance();
      $data->destroy();
    return $response->getBody()->write('<p>sesion cerrada</p>');
});
  
//--------------------------------------------------

  $app->group('/User', function () {
 /**
   * @api {get} /user/  user
   * @apiVersion 0.1.0
   * @apiName GetUser
   * @apiDescription obtiene un usuario por id
   * @apiGroup User
   * @apiParam {text} id id del usuario
 * @apiParamExample id:
 *     id: "1"
 *     
   */
    $this->get('/user/', \UserService::class . ':GetUser');
 /**
   * @api {get} / 
   * @apiVersion 0.1.0
   * @apiName GetAllUsers
   * @apiDescription obtiene todos los usuarios
   * @apiGroup User
 
   */
    $this->get('/', \UserService::class . ':GetAllUsers');
/**
   * @api {post} / 
   * @apiVersion 0.1.0
   * @apiName SaveUser
   * @apiDescription edita un usuario si se envia el parametro id, sino agrega uno nuevo
   * @apiGroup User
   * @apiParam {text} [id] id del usuario a editar
   * @apiParam {text} name nombre del usuario
   * @apiParam {text} email correo electronico del usuario 
   * @apiParam {text} password contraseña del usuario
   * @apiParam {text} [sectorId] 
   *  0: BarraTragosVinos
   *  1: BarraChoperasCervezaArtesanal
   *  2: Cocina
   *  3: CandyBar
   * @apiParam {text} role 
   *  1: Administrador
   *  2: moso
   *  3: operativo
 * @apiParamExample id:
 *     id: "1"
 * @apiParamExample name:
 *     name: "Cristian"
 * @apiParamExample email:
 *     email: "chriseze21@gmail.com"
 * @apiParamExample password: 
 *     password: "a12345"   
 * @apiParamExample sectorId:
 *     sectorId: "1"
 * @apiParamExample role:
 *     role: "1"
 *     
   */
    $this->post('/', \UserService::class . ':SaveUser');
/**
   * @api {delete} /  
   * @apiVersion 0.1.0
   * @apiName DeleteUser
   * @apiDescription elimina o recupera al usuario con el id enviado por parametro
   * @apiGroup User
   * @apiParam {text} id id del usuario
   * @apiParam {text} status 0: elimina 1: habilita
 * @apiParamExample id:
 *     id: "1"
 * @apiParamExample status:
 *     status: "0"    
   */
    $this->delete('/', \UserService::class . ':DeleteUser');
/**
   * @api {put} /
   * @apiVersion 0.1.0
   * @apiName SuspendUser
   * @apiDescription suspende o habilita  al usuario con el id enviado por parametro
   * @apiGroup User
   * @apiParam {text} id id del usuario
   * @apiParam {text} status 0: suspende 1: habilita
 * @apiParamExample id:
 *     id: "1"
 * @apiParamExample status:
 *     status: "0"    
   */
    $this->put('/', \UserService::class . ':SuspendUser');
/**
   * @api {get} /logs/  logs
   * @apiVersion 0.1.0
   * @apiName GetLogs
   * @apiDescription obtiene los ingresos de un usuario
   * @apiGroup User
   * @apiParam {text} id id del usuario
 * @apiParamExample id:
 *     id: "1"
  
   */
    $this->get('/logs/', \UserService::class . ':GetLogs');
     
})->add(\MWparaAutentificar::class . ':VerificarPerfil')->add(\MWparaAutentificar::class . ':VerificarToken');

$app->group('/Table', function () {
   /**
   * @api {get} /tables/  tables
   * @apiVersion 0.1.0
   * @apiName GetTables
   * @apiDescription obtiene las mesas por estado
   * @apiGroup Table
   * @apiParam {text} status 
   *  0: cerrada 
   *  1: con clientes esperando 
   *  2: con clientes comiendo 
   *  3: con clientes pagando 
 * @apiParamExample status:
 *     status: "1"
 *     
   */
     $this->get('/tables/', \RestaurantTableService::class . ':GetTables')->add(\MWparaAutentificar::class . ':VerificarPerfil');//bystatus admin or waiter only
  
    //  $this->get('/', \RestaurantTableService::class . ':GetTablesInfo')->add(\MWparaAutentificar::class . ':VerificarPerfil');//admin only
  /**
   * @api {post} /  
   * @apiVersion 0.1.0
   * @apiName SaveNewTable
   * @apiDescription guarda una nueva mesa
   * @apiGroup Table
   * @apiParam {text} code codigo de 5 caracteres
 * @apiParamExample code:
 *     code: "00001"
 *     
   */
     $this->post('/', \RestaurantTableService::class . ':SaveNewTable')->add(\MWparaAutentificar::class . ':VerificarPerfil');//admin only
   /**
   * @api {delete} / 
   * @apiVersion 0.1.0
   * @apiName DeleteTable
   * @apiDescription elimina una mesa
   * @apiGroup Table
   * @apiParam {text} id id de la mesa a borrar
 * @apiParamExample id:
 *     id: "1"
 *     
   */
     $this->delete('/', \RestaurantTableService::class . ':DeleteTable')->add(\MWparaAutentificar::class . ':VerificarPerfil');//admin only
      
 })->add(\MWparaAutentificar::class . ':VerificarToken');

 $app->group('/Order', function () {
  
      $this->get('/', \OrderService::class . ':GetOrders');
  
      $this->post('/', \OrderService::class . ':NewOrder');//waiter only

      $this->put('/', \OrderService::class . ':TakeOrder');//producer only

      $this->put('/finish/', \OrderService::class . ':FinishOrder');//producer only

      $this->put('/cancel/', \OrderService::class . ':CancelOrder');//admin only(cerrar mesa)

      
 })->add(\MWparaAutentificar::class . ':VerificarToken');

 $app->group('/Client', function () {
  
      $this->get('/', \OrderService::class . ':GetOrderForClient');
  
 });

$app->run();