<?php
require_once './Model/User.php';
require_once './Aplication/SessionService.php';

class UserService extends User
{
    public function GetAllUsers($request, $response, $args) {
        $usrs=User::GetUsers();
       $response = $response->withJson($usrs, 200);  
      return $response;
  }

    public function GetUser($request, $response, $args) {
      $params = $request->getParams();   
      $id=$params['id'];
    	$u=User::GetUserById($id);
     	$newResponse = $response->withJson($u, 200);  
    	return $newResponse;
    }

    public function CheckUser($request, $response, $args) {
        $ArrayDeParametros = $request->getParsedBody();
        $u=User::Check($ArrayDeParametros['email'],$ArrayDeParametros['password']);
        return $u;
    }

  public function SaveUser($request, $response, $args) {      
    $params = $request->getParsedBody();
    // var_dump($params);die();
    $id = 0;
    if(array_key_exists('id',$params))
        $id= $params['id'];
    if($id != 0)  {//update
        $name= $params['name'];
        $email= $params['email'];
        $password= $params['password'];
        if(array_key_exists('sectorId',$params))
            $sectorId= $params['sectorId'];        
        $role= $params['role'];

        $u = new User();
        $u->id=$id;
        $u->name=$name;
        $u->email=$email;
        $u->password=$password;
        $u->sectorId=isset($sectorId)? $sectorId: null;
        $u->role=$role;
        //  var_dump($u);die();
        $id = $u->UpdateUser();
    }  
    else{//new
        $name= $params['name'];
        $email= $params['email'];
        $password= $params['password'];
        if(array_key_exists('sectorId',$params))
            $sectorId= $params['sectorId'];
        
        $role= $params['role'];

        $u = new User();
        $u->name=$name;
        $u->email=$email;
        $u->password=$password;
        $u->isSuspended=false;
        $u->isDeleted=false;
        $u->sectorId=isset($sectorId)? $sectorId: null;
        $u->role=$role;

        //  var_dump($u);die();

        $id = $u->AddUser();
    }

    $objDelaRespuesta= new stdclass();
    $objDelaRespuesta->mensaje = "Exito"; 
    $objDelaRespuesta->id = $id; 
    return $response->withJson($objDelaRespuesta, 200);
}

    public function DeleteUser($request, $response, $args) {
        $params = $request->getParsedBody();
        // var_dump($params);die();
        $id=$params['id'];
        $status=$params['status'];
        User::Delete($id,$status);
        $objDelaRespuesta= new stdclass();
        $objDelaRespuesta->mensaje = "Exito"; 
        return $response->withJson($objDelaRespuesta, 200);
       
    }
    public function SuspendUser($request, $response, $args) {
        $params = $request->getParsedBody();
        $id=$params['id'];
        $status=$params['status'];
        User::Suspend($id,$status);
        $objDelaRespuesta= new stdclass();
        $objDelaRespuesta->mensaje = "Exito"; 
        return $response->withJson($objDelaRespuesta, 200);
     }

     public function GetLogs($request, $response, $args) {
        
        $objDelaRespuesta= new stdclass();
        $objDelaRespuesta->resultado = array();
        $params=$request->getParams();
        $u=User::GetUserById($params['id']);
        if($u != null)
        {
            $file = fopen("ingresos.txt", "r");
            while (!feof($file)) {
                $linea = fgets($file);
                $mail =  explode('-', $linea)[0];   
                if($u->email == $mail)
                {
                    array_push($objDelaRespuesta->resultado, $linea); 
                }
            }
            
            //var_dump($objDelaRespuesta);die();
            fclose($file);
        }

        return $response->withJson($objDelaRespuesta, 200);      
    }

//   public function CargarUno($request, $response, $args) {
//     $ArrayDeParametros = $request->getParsedBody();
//     //var_dump($ArrayDeParametros);
//     $dominio= $ArrayDeParametros['dominio'];
//     $marca= $ArrayDeParametros['marca'];
//     $cocheraId= $ArrayDeParametros['cocheraId'];
//     $id_empleado_ingreso= $_SESSION['userId'];
//      //seteo hora local 
//         date_default_timezone_set('America/Argentina/Buenos_Aires');
//         $today = getdate();
//         //var_dump($today);

//         //GUARDO LA FECHA ACTUAL EN FORMATO PROPIO (dd/mm/yyyy hh:mm)
//         $fecha_hora_ingreso = $today['mday']."/".$today['mon']."/".$today['year']." "
//         .$today['hours'].":".$today['minutes'];
   
//     $color= $ArrayDeParametros['color'];

//     $o = new Operacion();
//     $o->dominio=$dominio;
//     $o->marca=$marca;
//     $o->cocheraId=$cocheraId;
//     $o->id_empleado_ingreso=$id_empleado_ingreso;
//     $o->fecha_hora_ingreso=$fecha_hora_ingreso;
//     $o->color=$color;

//     //ocupo cochera
//     Cochera::Modificar($o->cocheraId,1,$dominio);

//     $archivos = $request->getUploadedFiles();
//     $destino="./fotosVehiculos/";
//     //var_dump($archivos);
//     //var_dump($archivos['foto']);

//     $nombreAnterior=$archivos['foto']->getClientFilename();
//     $extension= explode(".", $nombreAnterior)  ;
//     //var_dump($nombreAnterior);die();
//     $extension=array_reverse($extension);
//     $o->foto=$dominio.".".$extension[0];
//     $o->IngresarOperacion();
//     $archivos['foto']->moveTo($destino.$dominio.".".$extension[0]);
//     $objDelaRespuesta= new stdclass();
//     $objDelaRespuesta->mensaje = "Exito";  

//     $i = new Ingreso_empleado();
//     $i->fecha_hora_ingreso = $fecha_hora_ingreso;
//     $i->id_empleado = $id_empleado_ingreso;
//     $i->Ingresar();

//     return $response->withJson($objDelaRespuesta, 200);;
// }
 	
     
	
    //   public function BorrarUno($request, $response, $args) {
    //  	$ArrayDeParametros = $request->getParsedBody();
    //  	$id=$ArrayDeParametros['id'];
    //  	$v= new Vehiculo();
    //  	$v->id=$id;
    //  	$cantidadDeBorrados=$v->BorrarVehiculo();

    //  	$objDelaRespuesta= new stdclass();
	//     $objDelaRespuesta->cantidad=$cantidadDeBorrados;
	//     if($cantidadDeBorrados>0)
	//     	{
	//     		 $objDelaRespuesta->resultado="algo borro!!!";
	//     	}
	//     	else
	//     	{
	//     		$objDelaRespuesta->resultado="no Borro nada!!!";
	//     	}
	//     $newResponse = $response->withJson($objDelaRespuesta, 200);  
    //   	return $newResponse;
    // }
     
    //  public function ModificarUno($request, $response, $args) {
    //    $ArrayDeParametros = $request->getParsedBody();
    //     // var_dump($ArrayDeParametros);die();
    //     $o=Operacion::TraerOperacionPorDominio($ArrayDeParametros['dominio']);
    //     if($o != null){
    //       $o->id_empleado_salida = $_SESSION['userId'];

    //       //seteo hora local 
    //       date_default_timezone_set('America/Argentina/Buenos_Aires');
    //       $today = getdate();
    //       //var_dump($today);

    //       //GUARDO LA FECHA ACTUAL EN FORMATO PROPIO (dd/mm/yyyy hh:mm)
    //       $o->fecha_hora_salida = $today['mday']."/".$today['mon']."/".$today['year']." "
    //       .$today['hours'].":".$today['minutes'];
    //       //var_dump($o->fecha_hora_salida);
          
    //       //...............................................................................

    //       //necesito la fecha de ingreso a datetime para sacar el diff 
    //       $dateAndTime = explode(" ", $o->fecha_hora_ingreso);//separo hora de la fecha
    //       $date = explode("/", $dateAndTime[0]);//separo dia,mes y año
    //       $time = explode(":", $dateAndTime[1]);//separo hora y minuto
    //       $day = intval($date[0],10);
    //       $month = intval($date[1],10);
    //       $year = intval($date[2],10);
    //       $hour = intval($time[0],10);
    //       $min = intval($time[1],10);
    //       $mktime = mktime($hour,$min,0,$month,$day,$year);
    //       $ingreso = new DateTime(date(DATE_ATOM,$mktime));
    //       // var_dump($ingreso);

    //       //...............................................................................

    //       //ahora que tengo la fecha de ingreso saco dif con now
    //       $now = new DateTime(date(DATE_ATOM));
    //       // var_dump($now);
    //       $diff = date_diff($ingreso, $now);
    //       //var_dump($diff);
    //       $o->tiempo =  $diff->h + ($diff->d * 24);
    //       // var_dump($o->tiempo);
    //       $o->importe = $this->CalculateImport($o->tiempo);
    //       // var_dump($o->importe); die();

    //       //libero cochera
    //       Cochera::Modificar($o->cocheraId,0,null);

    //       $resultado =$o->Modificar();    
    //       $objDelaRespuesta= new stdclass();
    //       //var_dump($resultado);
    //       $objDelaRespuesta->mensaje=$resultado? 'Exito':'Error';
    //       $objDelaRespuesta->importe=$o->importe;

    //       return $response->withJson($objDelaRespuesta, 200); 
    //     }	
    //     $objDelaRespuesta= new stdclass();
    //     $objDelaRespuesta->mensaje = "El auto no esta";
    //    return $response->withJson($objDelaRespuesta, 200);
    // }

    //  function CalculateImport($hours){
    //   $days = 0;
    //   $halfDays = 0;
    //   $resto = $hours;
    //   if($resto >= 24)
    //   {
    //       $days = intval($resto / 24);
    //       $resto %= 24;
    //   }
    //   if($resto >= 12)
    //   {
    //     $halfDays = intval($resto / 12);
    //     $resto %= 12;
    //   }

    //   return ($resto * 10) + ($halfDays * 90) + ($days * 170);
    // }


}
?>