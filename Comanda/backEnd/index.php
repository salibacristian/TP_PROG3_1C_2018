<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;


require_once './composer/vendor/autoload.php';
require_once './AccesoDatos.php';
require_once './Modelo/Operacion.php';
require_once './Modelo/Empleado.php';
require_once './Aplicacion/OperacionService.php';
require_once './Aplicacion/EmpleadoService.php';
require_once './Aplicacion/CocheraService.php';
require_once './AutentificadorJWT.php';
require_once './MW/MWparaAutentificar.php';
require_once './Aplicacion/SessionService.php';
require_once './Modelo/Cochera.php';



$config['displayErrorDetails'] = true;
$config['addContentLengthHeader'] = false;


$app = new \Slim\App(["settings" => $config]);

  // $app->get('[/]', function (Request $request, Response $response) {    
  //   $response->getBody()->write("GET => Bienvenido!!! ,a SlimFramework");
  //   return $response;
  // });
  
  $app->group('/Operacion', function () {
 
  $this->get('/', \OperacionService::class . ':traerTodos');
 
  $this->get('/operacion', \OperacionService::class . ':traerUno');

  $this->post('/', \OperacionService::class . ':CargarUno');

  $this->delete('/', \OperacionService::class . ':BorrarUno');

  $this->put('/', \OperacionService::class . ':ModificarUno');
     
})->add(\MWparaAutentificar::class . ':VerificarToken');

$app->group('/Empleado', function () {
  
   $this->get('/', \EmpleadoService::class . ':traerTodos');
  
   $this->get('/empleado/', \EmpleadoService::class . ':traerUno');
 
   $this->post('/', \EmpleadoService::class . ':CargarUno');
 
   $this->delete('/', \EmpleadoService::class . ':BorrarUno');
 
   $this->put('/', \EmpleadoService::class . ':ModificarUno');

   $this->get('/ingresos/', \EmpleadoService::class . ':TraerIngresos');

   $this->get('/operaciones/', \EmpleadoService::class . ':TraerOperaciones');
      
 })->add(\MWparaAutentificar::class . ':VerificarPerfil')->add(\MWparaAutentificar::class . ':VerificarToken');

$app->get('/cocheras/', function (Request $request, Response $response) {
      $params = $request->getParams();
      $cocheras= Cochera::TraerCocheras($params['libres']); 
      $newResponse = $response->withJson($cocheras, 200); 
      return $newResponse;
  });
$app->get('/cocherasStatus/', function (Request $request, Response $response) {
      $status= CocheraService::TraerStatus(); 
      $newResponse = $response->withJson($status, 200); 
      return $newResponse;
  });

  //log---------------------------------------------

   $app->post('/login/', function (Request $request, Response $response) {
      return $response;
  })
   ->add(\MWparaAutentificar::class . ':VerificarUsuario');

$app->get('/logout/', function (Request $request, Response $response) {
        $data = Session::getInstance();
        $data->destroy();
      return $response->getBody()->write('<p>sesion cerrada</p>');
  });
 //jwt---------------------------------------------

  $app->get('/crearToken/', function (Request $request, Response $response) {
      $auth= MWparaAutentificar::VerificarUsuario($request, $response); 
      $newResponse = $response->withJson($auth, 200); 
      return $newResponse;
  });

  // $app->get('/devolverPayLoad/', function (Request $request, Response $response) { 
  //     $datos = array('usuario' => 'rogelio@agua.com','perfil' => 'Administrador', 'alias' => "PinkBoy");
  //     $token= AutentificadorJWT::CrearToken($datos); 
  //     $payload=AutentificadorJWT::ObtenerPayload($token);
  //     $newResponse = $response->withJson($payload, 200); 
  //     return $newResponse;
  // });

  // $app->get('/devolverDatos/', function (Request $request, Response $response) {
  //     $datos = array('usuario' => 'rogelio@agua.com','perfil' => 'Administrador', 'alias' => "PinkBoy");
  //     $token= AutentificadorJWT::CrearToken($datos); 
  //     $payload=AutentificadorJWT::ObtenerData($token);
  //     $newResponse = $response->withJson($payload, 200); 
  //     return $newResponse;
  // });

  // $app->get('/verificarTokenNuevo/', function (Request $request, Response $response) { 
  //     $datos = array('usuario' => 'rogelio@agua.com','perfil' => 'Administrador', 'alias' => "PinkBoy");
  //     $token= AutentificadorJWT::CrearToken($datos); 
  //     $esValido=false;
  //     try 
  //     {
  //       AutentificadorJWT::verificarToken($token);
  //       $esValido=true;      
  //     }
  //     catch (Exception $e) {      
  //       //guardar en un log
  //       echo $e;
  //     }
  //     if( $esValido)
  //     {
  //         /* hago la operacion del  metodo */
  //         echo "ok";
  //     }   
  //     return $response;
  // });

  // $app->get('/verificarTokenViejo/', function (Request $request, Response $response) {    
      
  //     $esValido=false;

  //     try {
  //       AutentificadorJWT::verificarToken("eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpYXQiOjE0OTczMTM0NTEsImV4cCI6MTQ5NzMxMzUxMSwiYXVkIjoiMTU3NDQzNzc4MzUzNGEzMDNjYzExY2YzNGI0OTc1ODAxMTNkMDBiOSIsImRhdGEiOnsibm9tYnJlIjoicm9nZWxpbyIsImFwZWxsaWRvIjoiYWd1YSIsImVkYWQiOjQwfSwiYXBwIjoiQVBJIFJFU1QgQ0QgMjAxNyJ9.DZ1LC0BTl5YKHWr7NjWY6r2EDKvVBeOTZiNEv4CXaN0");
  //       $esValido=true;
        
  //     } catch (Exception $e) {      
  //       //guardar en un log
  //       echo $e;
  //     }
  //     if( $esValido)
  //     {
  //         /* hago la operacion del  metodo
  //         */
  //         echo "ok";
  //     }   
  //     return $response;

  // });
  // $app->get('/verificarTokenError/', function (Request $request, Response $response) {    
      
  //     $esValido=false;
  //     // cambio un caracter de un token valido
  //     try {
  //       AutentificadorJWT::verificarToken("eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpYXQiOjE0OTczMTM0NTEsImV4cCI6MTQ5NzMxMzUxMSwiYXVkIjoiMTU3NDQzNzc4MzUzNGEzMDNjYzExY2YzNGI0OTc1ODAxMTNkMDBiOSIsImRhdGEiOnsibm9tYnJlIjoicm9nZWxpbyIsImFwZWxsaWRvIjoiYWd1YSIsImVkYWQiOjQwfSwiYXBwIjoiQVBJIFJFU1QgQ0QgMjAxNyJ9.DZ1LC0BTl5YKHWr7NjWY6r2EDKvVBeOTZiNEv4CXaN");
  //       $esValido=true;
        
  //     } catch (Exception $e) {      
  //       //guardar en un log
  //       echo $e;
  //     }
  //     if( $esValido)
  //     {
  //         /* hago la operacion del  metodo
  //         */
  //         echo "ok";
  //     }   
  //     return $response;

  // });


$app->run();