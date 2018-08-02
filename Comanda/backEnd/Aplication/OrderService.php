<?php
require_once './Model/Order.php';
require_once './Model/Order_User.php';
require_once './Model/Order_Item.php';
require_once './Model/RestaurantTable.php';
require_once './Model/BaseEnum.php';
require_once './Aplication/SessionService.php';

class OrderService extends Order 
{
  ///cada rol tiene una vista distinta del get orders
  public function GetOrders($request, $response, $args) {
    $role= $_SESSION['role'];
    $sector= $_SESSION['sector'];
    // var_dump($role);
		// var_dump($sector);die();
    switch ($role) {
      case Role::Administrator://debe ver todo
        $o=Order::AllOrders();
        break;
        case Role::Waiter://pedidos terminados de todos los sectores
        $o=Order::AllOrders();
        break;
        case Role::Producer://debe ver los pendientes
        $o=Order_Item::PendingOrderItems($sector);
        break;
    }

    $response = $response->withJson($o, 200);  
    return $response;
  }

  public function GetOrderByCode($request, $response, $args) {
    $params = $request->getParams(); 
    $o = Order::OrderByCode($params['orderCode']);
     return $response->withJson($o, 200);  
  }

  public function GetOrderForClient($request, $response, $args) { 
    $params = $request->getParams(); 
    $o=Order::OrderForClient($params['tableCode'],$params['orderCode']);
    $objDelaRespuesta= new stdclass();
    $objDelaRespuesta->remainingTime = null;
    // var_dump($o);die();
    if($o->takenDate != null){
      //seteo hora local 
      date_default_timezone_set('America/Argentina/Buenos_Aires');
      $now = new Datetime();
      $diff = date_diff(new Datetime($o->takenDate), $now);
      $realTime =  $diff->i;
      $objDelaRespuesta->remainingTime = $o->estimatedTime - $realTime;
    }
    $objDelaRespuesta->order = $o;
    return $response->withJson($objDelaRespuesta, 200);  
  }  

  public function AnswerSurvey($request, $response, $args) {
    $objDelaRespuesta= new stdclass();
    try{
    $params = $request->getParsedBody();
    $orderId= $params['orderId'];
    $comment= $params['comment'];
    $tablePoints= $params['tablePoints'];
    $waiterPoints= $params['waiterPoints'];
    $producerPoints= $params['producerPoints'];
  
    $order = Order::GetOrderById($orderId);
    if($order == null){
      $objDelaRespuesta->mensaje = "No se encontro la orden";
      return $response->withJson($objDelaRespuesta, 200);
    }
    if($order->status != OrderStatus::Delivered){
      $objDelaRespuesta->mensaje = "La orden no ha sido entregada";
      return $response->withJson($objDelaRespuesta, 200);
    }  
   
    Order::CommentAndRate($orderId,$comment,$tablePoints,$waiterPoints,$producerPoints);

     $objDelaRespuesta->mensaje = "Gracias por su opinión";
    }catch(Exception $e){
      $objDelaRespuesta->mensaje = $e->getMessage();
    }
  
    return $response->withJson($objDelaRespuesta, 200);
  }

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
    $body = $request->getParsedBody();
    $params = json_decode($body['request']);
    $tableId= $params->tableId;
    $items= $params->items;
    $id_user= $_SESSION['userId'];
    $role_user= $_SESSION['role'];
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
    $o->code=$code;
    $o->createdDate=$fecha_hora_ingreso;    
  
    $archivos = $request->getUploadedFiles();
    $destino="./fotosPedidos/";
    // var_dump($archivos);die();
    //var_dump($archivos['foto']);die();

    $nombreAnterior=$archivos['foto']->getClientFilename();
    $extension= explode(".", $nombreAnterior)  ;
    //var_dump($nombreAnterior);die();
    $extension=array_reverse($extension);
    $o->imgUrl=$code.".".$extension[0];

    $orderId = $o->Add();

    $archivos['foto']->moveTo($destino.$code.".".$extension[0]);
  
    //cargo relacion con usuario moso
     $ou = new Order_User();
     
     $ou->userId=$id_user;
     $ou->orderId=$orderId;
     $ou->userRole= $role_user;//aqui deberia ser waiter 

     $ou->Add();

     //cargo relacion con items
     $oi = new Order_Item();
     foreach ($items as $item) {     
      $oi->orderId=$orderId;
      $oi->itemId=$item->id;
      $oi->units=$item->units;
      $oi->Add();
     }

     //actualizo status mesa
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
  $id_user= $_SESSION['userId'];
  $role_user= $_SESSION['role'];  
  $sector_user= $_SESSION['sector'];  

  $orderItems = Order::GetOrderItems($orderId);
  if($orderItems == null ||  count($orderItems) == 0){
    $objDelaRespuesta->mensaje = "No se encontro la orden";
    return $response->withJson($objDelaRespuesta, 200);
  }
  if($orderItems[0]->status == OrderStatus::Canceled){
    $objDelaRespuesta->mensaje = "La orden esta cancelada";
    return $response->withJson($objDelaRespuesta, 200);
  }

  //seteo hora local 
  date_default_timezone_set('America/Argentina/Buenos_Aires');
  // $today = getdate();
  //var_dump($today);
  //FORMATO PROPIO (dd/mm/yyyy hh:mm)
  // $fecha_hora_ingreso = $today['mday']."/".$today['mon']."/".$today['year']." "
  // .$today['hours'].":".$today['minutes'];

  $now = new Datetime();
  $takenDate = $now->format('Y-m-d H:i:s');  
  
//tomo los items correspondientes
   foreach ($orderItems as $key => $item) {
     if($item->sectorId == $sector_user){
        Order_Item::Take($orderId,$item->itemId,$takenDate);      
     }
   }
  //la order se considera tomada cuando se toma el item de cocina o todos sus items
    //en ese caso seteo el tiempo estimado
    //(un cocinero prepara items DE UN PEDIDO a la vez)
  //si un usr tomo el pedido es porque tiene algun item de su sector
  $pendingItems = Order_Item::GetPendingItems($orderId);
  $estimatedTime = null;
  if($sector_user == Sector::Cocina || count($pendingItems) == 0)
   {
      $estimatedTime = max(array_map(function($oi){return $oi->estimatedTime;},$orderItems)); 
      $a = array_map(function($oi){return $oi->estimatedTime;},$orderItems);
      // var_dump($estimatedTime);die();   
      Order::Take($orderId,OrderStatus::InProgress,$estimatedTime,$takenDate);
   } 

   //cargo relacion con usuario productor
   $ou = new Order_User();
   
   $ou->userId=$id_user;
   $ou->orderId=$orderId;
   $ou->userRole= $role_user;//deberia ser producer

   $ou->Add();


   $objDelaRespuesta= new stdclass();
   $objDelaRespuesta->mensaje = "Pedido en preparacion";
  }catch(Exception $e){
    $objDelaRespuesta->mensaje = $e->getMessage();
  }

  return $response->withJson($objDelaRespuesta, 200);
}

public function FinishOrder($request, $response, $args) {
  $objDelaRespuesta= new stdclass();
  $objDelaRespuesta->mensaje = "";
  try{
  $params = $request->getParsedBody();
  $orderId= $params['orderId'];
  $id_user= $_SESSION['userId'];
  $role_user= $_SESSION['role'];  
  $sector_user= $_SESSION['sector'];

  $order = Order::GetOrderById($orderId);
  // var_dump($order);die();
  $orderItems = Order::GetOrderItems($orderId);
  // var_dump($orderItems);die();
  if($orderItems == null ||  count($orderItems) == 0){
    $objDelaRespuesta->mensaje = "No se encontro la orden";
    return $response->withJson($objDelaRespuesta, 200);
  }
  if($orderItems[0]->status == OrderStatus::Canceled){
    $objDelaRespuesta->mensaje = "La orden esta cancelada";
    return $response->withJson($objDelaRespuesta, 200);
  }
  

  //seteo hora local 
  date_default_timezone_set('America/Argentina/Buenos_Aires');
  
  $now = new Datetime();
  $finishDate = $now->format('Y-m-d H:i:s');   
  // var_dump($now);
  // var_dump(new Datetime($order->takenDate));die();
  
   //finalizo los items correspondientes
   foreach ($orderItems as $key => $item) {
    if($item->sectorId == $sector_user && $item->takenDate != null){
       Order_Item::Finish($orderId,$item->itemId,$finishDate);      
    }
  }

 //la order se considera finalizada cuando se finalizan todos los items  
   //en ese caso seteo el tiempo real
 //si un usr finalizo el pedido es porque tiene algun item de su sector
 $takenItems = Order_Item::GetTakenItems($orderId);
//  var_dump($takenItems);die();
 $pendingItems = Order_Item::GetPendingItems($orderId);
  //var_dump($pendingItems);die(); 
 $realTime = null;

 if((count($takenItems) == 0 && count($pendingItems) == 0))
  {
     //calculo demora
   $diff = date_diff(new Datetime($order->takenDate), $now);
   $realTime =  $diff->i;
     Order::Finish($orderId,OrderStatus::Finished,$realTime,$finishDate);
     $objDelaRespuesta->mensaje = "Todos los pedidos finalizados";     
  } 
  else
    $objDelaRespuesta->mensaje = " Pedido finalizado";

  }catch(Exception $e){
    $objDelaRespuesta->mensaje = $e->getMessage();
  }

  return $response->withJson($objDelaRespuesta, 200);
}

public function DeliverOrder($request, $response, $args) {
  $objDelaRespuesta= new stdclass();
  try{
  $params = $request->getParsedBody();
  $orderId= $params['orderId']; 
  $order = Order::GetOrderById($orderId);
  if($order == null){
    $objDelaRespuesta->mensaje = "No se encontro la orden";
    return $response->withJson($objDelaRespuesta, 200);
  } 

  Order::Deliver($orderId);  

   //actualizo status mesa
    RestaurantTable::ChangeStatus($order->tableId,TableStatus::Eating);

   $objDelaRespuesta= new stdclass();
   $objDelaRespuesta->mensaje = "Pedido entregado";
  }catch(Exception $e){
    $objDelaRespuesta->mensaje = $e->getMessage();
  }

  return $response->withJson($objDelaRespuesta, 200);
}

public function PayOrder($request, $response, $args) {
  $objDelaRespuesta= new stdclass();
  try{
  $params = $request->getParsedBody();
  $orderId= $params['orderId']; 
  $order = Order::GetOrderById($orderId);
  if($order == null){
    $objDelaRespuesta->mensaje = "No se encontro la orden";
    return $response->withJson($objDelaRespuesta, 200);
  } 

   //actualizo status mesa
    RestaurantTable::ChangeStatus($order->tableId,TableStatus::Paying);
    //calculo total
    $total = 0;
    $orderItems = Order::GetOrderItems($order->id);
    foreach ($orderItems as $key => $i) {
      $total += $i->amount * $i->units;
    }
    
    $order->SetAmount($total);

   $objDelaRespuesta= new stdclass();
   $objDelaRespuesta->mensaje = "Total $".$total;
  }catch(Exception $e){
    $objDelaRespuesta->mensaje = $e->getMessage();
  }

  return $response->withJson($objDelaRespuesta, 200);
}


public function CancelOrder($request, $response, $args) {
  $objDelaRespuesta= new stdclass();
  try{
  $params = $request->getParsedBody();
  $orderId= $params['orderId']; 
  $order = Order::GetOrderById($orderId);
  if($order == null){
    $objDelaRespuesta->mensaje = "No se encontro la orden";
    return $response->withJson($objDelaRespuesta, 200);
  } 
  if($order->status > 1){
    $objDelaRespuesta->mensaje = "No se puede cancelar la orden porque ya esta hecha";
    return $response->withJson($objDelaRespuesta, 200);
  } 

  Order::Cancel($orderId);  

   //actualizo status mesa
    RestaurantTable::ChangeStatus($order->tableId,TableStatus::Closed);

   $objDelaRespuesta= new stdclass();
   $objDelaRespuesta->mensaje = "Pedido cancelado";
  }catch(Exception $e){
    $objDelaRespuesta->mensaje = $e->getMessage();
  }

  return $response->withJson($objDelaRespuesta, 200);
}

 
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