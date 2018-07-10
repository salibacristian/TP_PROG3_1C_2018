<?php
// require_once './Model/Operacion.php';
require_once './Model/RestaurantTable.php';
require_once './Aplication/SessionService.php';

class RestaurantTableService extends RestaurantTable
{
  public function GetTables($request, $response, $args) {
    $params = $request->getParams();   
    $status=$params['status'];
    $tables=RestaurantTable::GetTablesByStatus($status);
     $newResponse = $response->withJson($tables, 200);  
    return $newResponse;
  }

  public function SaveNewTable($request, $response, $args) {
    $objDelaRespuesta= new stdclass();    
    try{  
      $params = $request->getParsedBody();
      //var_dump($params);die();   
        $code= $params['code'];      

        $t = new RestaurantTable();
        $t->code=$code;
        $t->status=0;       
        $id = $t->AddTable();   

        $objDelaRespuesta->mensaje = "Exito";
        $objDelaRespuesta->id = $id; 
  } catch (Exception $e) {
    if($e->getCode() == '23000')
      $objDelaRespuesta->mensaje = "el codigo $code ya existe";
    else throw $e;
  }
    return $response->withJson($objDelaRespuesta, 200);
}

  public function DeleteTable($request, $response, $args) {
     	$params = $request->getParsedBody();
     	$count=RestaurantTable::Delete($params['id']);
     	$objDelaRespuesta= new stdclass();
	    $objDelaRespuesta->mensaje="$count registro/s borrado/s";
	    $newResponse = $response->withJson($objDelaRespuesta, 200);  
      	return $newResponse;
    }
     
 	// public static function maxima($variable){
  //     $maxima = new stdclass();
  //     $maxima->numeroDeOpereaciones = 0;
  //     foreach ($variable as $value) {
  //       if($maxima->numeroDeOpereaciones < $value->numeroDeOpereaciones)
  //         $maxima = $value;
  //     }
  //     return $maxima;
  //   }

  //  public static function minima($variable){
  //     $min = new stdclass();
  //     $min->numeroDeOpereaciones = 99999;
  //     foreach ($variable as $value) {
  //       if($min->numeroDeOpereaciones > $value->numeroDeOpereaciones)
  //         $min = $value;
  //     }
  //     return $min;
  //   }



}

?>