<?php

	require_once './AutentificadorJWT.php';
 	require_once './Aplication/UserService.php';
 	require_once './Aplication/SessionService.php';
 	require_once './Model/BaseEnum.php';
class MWparaAutentificar
{
	public static function VerificarUsuario($request, $response, $next) {
         

		  if($request->isGet())
		  {
		     $response = $next($request, $response);
		  }
		  else
		  {
		    $usr = UserService::CheckUser($request,$response);
	    	if($usr != null){
			    $objDelaRespuesta= new stdclass();
			    $objDelaRespuesta->user =  $usr;
			    $objDelaRespuesta->token=AutentificadorJWT::CrearToken(array('user' => $usr->email,'role' => $usr->role));

				$data = Session::getInstance();
				$data->set('userId', $usr->id);
				$data->set('email', $usr->email);
				$data->set('role', $usr->role);
				$data->set('sector', $usr->sectorId);
				$data->set('token', $objDelaRespuesta->token);
				$objDelaRespuesta->session = $data->get('email');
				
		    	MWparaAutentificar::RegistrarInicio($data);
			  
			    $response = $response->withJson($objDelaRespuesta, 200);  

		    	$response = $next($request, $response);
			}
		    else {
					$objDeLaRespuesta = new stdClass();
					$objDeLaRespuesta->mensaje="Usuario o contraseña incorrecta";
					$response = $response->withJson($objDeLaRespuesta, 200); 
				}
		  }
		  return $response;   
	}

	 static function RegistrarInicio($data){
		$file = fopen("ingresos.txt", "a");
		$date = date(DATE_ATOM);
		fwrite($file, $data->get('email') . '-' . $date . PHP_EOL);

		fclose($file);
	}

	public static function VerificarToken($request, $response, $next) {
      try 
      {
      	$ArrayDeParametros = $request->getParsedBody();
        // AutentificadorJWT::verificarToken($ArrayDeParametros['token']);
        $data = Session::getInstance();
        AutentificadorJWT::CheckToken($data->get('token'));
        $response = $next($request, $response);      
      }
      catch (Exception $e) {      
        //guardar en un log
        echo $e;
      }  
      return $response;
	}

	public static function VerificarPerfil($request, $response, $next) {
		try 
		{		
		  $data = Session::getInstance();
		  if($data->get('role') == Role::Administrator)
			  $response = $next($request, $response);  
		  else $response->getBody()->write('<p>ingreso no habilitado</p>');
		}
		catch (Exception $e) {      
		  //guardar en un log
		  echo $e;
		}  
		return $response;
	  }
}