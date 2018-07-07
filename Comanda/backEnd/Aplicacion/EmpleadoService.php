<?php
require_once './Modelo/Empleado.php';

class EmpleadoService extends Empleado //implements IApiUsable
{
 	public function TraerUno($request, $response, $args) {
     	$ArrayDeParametros=$request->getParams();
        $e=Empleado::TraerEmpleado($ArrayDeParametros['id']);
     	$newResponse = $response->withJson($e, 200);  
    	return $newResponse;
    }

    public static function VerificarUsuario($request, $response) {
        $ArrayDeParametros = $request->getParsedBody();
        $e=Empleado::VerificarEmpleado($ArrayDeParametros['mail'],$ArrayDeParametros['clave']);
        return $e;
    }

     public function TraerTodos($request, $response, $args) {
      	$empleados=Empleado::TraerEmpleados();
     	$response = $response->withJson($empleados, 200);  
    	return $response;
    }
      public function CargarUno($request, $response, $args) {
     	 $ArrayDeParametros = $request->getParsedBody();
       // var_dump($ArrayDeParametros);die();
		$nombre= $ArrayDeParametros['nombre'];
        $apellido= $ArrayDeParametros['apellido'];
        $clave= $ArrayDeParametros['clave'];
		$mail= $ArrayDeParametros['mail'];
        $turno= $ArrayDeParametros['turno'];
        $perfil= $ArrayDeParametros['perfil'];
        // $fecha_creacion= $ArrayDeParametros['fecha_creacion'];
        //seteo hora local 
          date_default_timezone_set('America/Argentina/Buenos_Aires');
          $today = getdate();
          //var_dump($today);          
        
        $e = new Empleado();
		$e->nombre=$nombre;
		$e->apellido=$apellido;
        $e->clave=$clave;
		$e->mail=$mail;
        $e->turno=$turno;
		$e->perfil=$perfil;
		//GUARDO LA FECHA ACTUAL EN FORMATO PROPIO (dd/mm/yyyy hh:mm)
          $e->fecha_creacion = $today['mday']."/".$today['mon']."/".$today['year']." "
          .$today['hours'].":".$today['minutes'];

        $archivos = $request->getUploadedFiles();
        $destino="./fotosEmpleados/";
        //var_dump($archivos);
        //var_dump($archivos['foto']);

        if(isset($archivos['foto']))
        {
        $nombreAnterior=$archivos['foto']->getClientFilename();
        $extension= explode(".", $nombreAnterior);
        //var_dump($nombreAnterior);die();
        $extension=array_reverse($extension);
		$e->foto=$mail.".".$extension[0];
		
		$archivos['foto']->moveTo($destino.$mail.".".$extension[0]);
        }
        $id_empleado = $e->IngresarEmpleado();
		$response->getBody()->write("se guardo el empleado");

        return $response;
	}
	
      public function BorrarUno($request, $response, $args) {
     	$ArrayDeParametros = $request->getParsedBody();
     	$id=$ArrayDeParametros['id'];
     	$e= new Empleado();
     	$e->id=$id;
     	$cantidadDeBorrados=$e->Borrar();

     	$objDelaRespuesta= new stdclass();
	    $objDelaRespuesta->cantidad=$cantidadDeBorrados;
	    if($cantidadDeBorrados>0)
	    	{
	    		 $objDelaRespuesta->resultado="algo borro!!!";
	    	}
	    	else
	    	{
	    		$objDelaRespuesta->resultado="no Borro nada!!!";
	    	}
	    $newResponse = $response->withJson($objDelaRespuesta, 200);  
      	return $newResponse;
    }
     
     public function ModificarUno($request, $response, $args) {
		$ArrayDeParametros = $request->getParsedBody();
		//var_dump($ArrayDeParametros); 
	    $e = new Empleado();
	    $e->id=$ArrayDeParametros['id'];
	    $e->nombre=$ArrayDeParametros['nombre'];
	    $e->apellido=$ArrayDeParametros['apellido'];
	    $e->clave=$ArrayDeParametros['clave'];
	    $e->turno=$ArrayDeParametros['turno'];
		$e->perfil=$ArrayDeParametros['perfil'];
		$resultado =$e->Modificar();		
	   	$objDelaRespuesta= new stdclass();
		//var_dump($resultado);
		$objDelaRespuesta->resultado=$resultado;
		return $response->withJson($objDelaRespuesta, 200);		
    }

     public function TraerIngresos($request, $response, $args) {
        $ArrayDeParametros=$request->getParams();
        $objDelaRespuesta= new stdclass();
        $objDelaRespuesta->resultado = array();
        $e=Empleado::TraerEmpleado($ArrayDeParametros['id']);
        if($e != null)
        {
            $file = fopen("ingresos.txt", "r");
            while (!feof($file)) {
                $linea = fgets($file);
                $mail =  explode('-', $linea)[0];   
                if($e->mail == $mail)
                {
                    array_push($objDelaRespuesta->resultado, $linea); 
                }
            }
            
            //var_dump($objDelaRespuesta);die();
            fclose($file);
        }

        return $response->withJson($objDelaRespuesta, 200);      
    }

    public function TraerOperaciones($request, $response, $args) {
        $objDelaRespuesta= new stdclass();
         $e = new Empleado();
         $rtdo = $e->Operaciones();//deberia ser estatico pero no me anda
         $objDelaRespuesta->resultado = $rtdo;
        return $response->withJson($objDelaRespuesta, 200);      
    }


}