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


}
?>