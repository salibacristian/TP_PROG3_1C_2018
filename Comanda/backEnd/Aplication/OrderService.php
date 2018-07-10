<?php
require_once './Model/Order.php';
require_once './Model/Order_User.php';
require_once './Model/RestaurantTable.php';
require_once './Model/Role.php';
require_once './Model/TableStatus.php';
require_once './Model/OrderStatus.php';
require_once './Aplication/SessionService.php';

class OrderService extends Order 
{

  static function randomKey($length) {
    $pool = array_merge(range(0,9), range('a', 'z'),range('A', 'Z'));
    $key = '';
    for($i=0; $i < $length; $i++) {
        $key .= $pool[mt_rand(0, count($pool) - 1)];
    }
    return $key;
}

  public function NewOrder($request, $response, $args) {
    $objDelaRespuesta= new stdclass();
    try{
    $params = $request->getParsedBody();
    $tableId= $params['tableId'];
    $sectorId= $params['sectorId'];
    $id_waiter= $_SESSION['userId'];
    $code = self::randomKey(5);

     //seteo hora local 
    date_default_timezone_set('America/Argentina/Buenos_Aires');
    // $today = getdate();
    //var_dump($today);
    //FORMATO PROPIO (dd/mm/yyyy hh:mm)
    // $fecha_hora_ingreso = $today['mday']."/".$today['mon']."/".$today['year']." "
    // .$today['hours'].":".$today['minutes'];

    $now = new Datetime();
    $fecha_hora_ingreso = $now->format('Y-m-d H:i:s');   

    $o = new Order();
    
    $o->tableId=$tableId;
    $o->sectorId=$sectorId;
    $o->code=$code;
    $o->createdDate=$fecha_hora_ingreso;    
  
    $archivos = $request->getUploadedFiles();
    $destino="./fotosPedidos/";
    //var_dump($archivos);
    //var_dump($archivos['foto']);

    $nombreAnterior=$archivos['foto']->getClientFilename();
    $extension= explode(".", $nombreAnterior)  ;
    //var_dump($nombreAnterior);die();
    $extension=array_reverse($extension);
    $o->imgUrl=$code.".".$extension[0];

    $orderId = $o->Add();

    $archivos['foto']->moveTo($destino.$code.".".$extension[0]);
  
     //cargo relacion con usuario moso
     $ou = new Order_User();
     
     $ou->userId=$id_waiter;
     $ou->orderId=$orderId;
     $ou->userRole= Role::Waiter;

     $ou->Add();

     //actualizo status mesa
    // var_dump($tableId);die();
    RestaurantTable::ChangeStatus($tableId,TableStatus::Waiting);

     $objDelaRespuesta= new stdclass();
     $objDelaRespuesta->mensaje = $code;
    }catch(Exception $e){
      $objDelaRespuesta->mensaje = $e->getMessage();
    }

    return $response->withJson($objDelaRespuesta, 200);;
}

public function TakeOrder($request, $response, $args) {
  $objDelaRespuesta= new stdclass();
  try{
  $params = $request->getParsedBody();
  $orderId= $params['orderId'];
  $estimatedTime= $params['estimatedTime'];
  $id_producer= $_SESSION['userId'];

  //seteo hora local 
  date_default_timezone_set('America/Argentina/Buenos_Aires');
  // $today = getdate();
  //var_dump($today);
  //FORMATO PROPIO (dd/mm/yyyy hh:mm)
  // $fecha_hora_ingreso = $today['mday']."/".$today['mon']."/".$today['year']." "
  // .$today['hours'].":".$today['minutes'];

  $now = new Datetime();
  $takenDate = $now->format('Y-m-d H:i:s');   

  Order::Take($orderId,OrderStatus::InProgress,$estimatedTime,$takenDate);

   //cargo relacion con usuario productor
   $ou = new Order_User();
   
   $ou->userId=$id_producer;
   $ou->orderId=$orderId;
   $ou->userRole= Role::Producer;

   $ou->Add();


   $objDelaRespuesta= new stdclass();
   $objDelaRespuesta->mensaje = "Pedido en preparacion";
  }catch(Exception $e){
    $objDelaRespuesta->mensaje = $e->getMessage();
  }

  return $response->withJson($objDelaRespuesta, 200);;
}

 	// public function TraerUno($request, $response, $args) {
  //     $params = $request->getParams();   
  //     $dom=$params['dominio'];
  //   	$o=Operacion::TraerOperacionPorDominio($dom);
  //    	$newResponse = $response->withJson($o, 200);  
  //   	return $newResponse;
  //   }
  //    public function TraerTodos($request, $response, $args) {
  //     	$operaciones=Operacion::TraerOperaciones();
  //    	$response = $response->withJson($operaciones, 200);  
  //   	return $response;
  //   }
     
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
     
     public function ModificarUno($request, $response, $args) {
       $ArrayDeParametros = $request->getParsedBody();
        // var_dump($ArrayDeParametros);die();
        $o=Operacion::TraerOperacionPorDominio($ArrayDeParametros['dominio']);
        if($o != null){
          $o->id_empleado_salida = $_SESSION['userId'];

          //seteo hora local 
          date_default_timezone_set('America/Argentina/Buenos_Aires');
          $today = getdate();
          //var_dump($today);

          //GUARDO LA FECHA ACTUAL EN FORMATO PROPIO (dd/mm/yyyy hh:mm)
          $o->fecha_hora_salida = $today['mday']."/".$today['mon']."/".$today['year']." "
          .$today['hours'].":".$today['minutes'];
          //var_dump($o->fecha_hora_salida);
          
          //...............................................................................

          //necesito la fecha de ingreso a datetime para sacar el diff 
          $dateAndTime = explode(" ", $o->fecha_hora_ingreso);//separo hora de la fecha
          $date = explode("/", $dateAndTime[0]);//separo dia,mes y año
          $time = explode(":", $dateAndTime[1]);//separo hora y minuto
          $day = intval($date[0],10);
          $month = intval($date[1],10);
          $year = intval($date[2],10);
          $hour = intval($time[0],10);
          $min = intval($time[1],10);
          $mktime = mktime($hour,$min,0,$month,$day,$year);
          $ingreso = new DateTime(date(DATE_ATOM,$mktime));
          // var_dump($ingreso);

          //...............................................................................

          //ahora que tengo la fecha de ingreso saco dif con now
          $now = new DateTime(date(DATE_ATOM));
          // var_dump($now);
          $diff = date_diff($ingreso, $now);
          //var_dump($diff);
          $o->tiempo =  $diff->h + ($diff->d * 24);
          // var_dump($o->tiempo);
          $o->importe = $this->CalculateImport($o->tiempo);
          // var_dump($o->importe); die();

          //libero cochera
          Cochera::Modificar($o->cocheraId,0,null);

          $resultado =$o->Modificar();    
          $objDelaRespuesta= new stdclass();
          //var_dump($resultado);
          $objDelaRespuesta->mensaje=$resultado? 'Exito':'Error';
          $objDelaRespuesta->importe=$o->importe;

          return $response->withJson($objDelaRespuesta, 200); 
        }	
        $objDelaRespuesta= new stdclass();
        $objDelaRespuesta->mensaje = "El auto no esta";
       return $response->withJson($objDelaRespuesta, 200);
    }

     function CalculateImport($hours){
      $days = 0;
      $halfDays = 0;
      $resto = $hours;
      if($resto >= 24)
      {
          $days = intval($resto / 24);
          $resto %= 24;
      }
      if($resto >= 12)
      {
        $halfDays = intval($resto / 12);
        $resto %= 12;
      }

      return ($resto * 10) + ($halfDays * 90) + ($days * 170);
    }


}

?>