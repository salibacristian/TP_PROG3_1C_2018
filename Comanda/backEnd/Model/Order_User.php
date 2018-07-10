<?php
class Order_User
{
	public $id;
	public $userId;
	public $orderId;
	public $userRole;
	

	 public function Add()
	 {
		//var_dump($this);die;
		$ctx = AccesoDatos::dameUnObjetoAcceso();
		$query = $ctx->RetornarConsulta("INSERT INTO order_user 
		(userId,orderId,userRole)
		VALUES(:userId,:orderId,:userRole)");
		$query->bindValue(':userId',$this->userId, PDO::PARAM_INT);
		$query->bindValue(':orderId',$this->orderId, PDO::PARAM_INT);
		$query->bindValue(':userRole',$this->userRole, PDO::PARAM_INT);
		$query->execute();
		return $ctx->RetornarUltimoIdInsertado();
	 }


  	// public static function TraerIngresos()
	// {
	// 		$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
	// 		$consulta =$objetoAccesoDato->RetornarConsulta("select * from Ingresos_empleados");
	// 		$consulta->execute();			
	// 		return $consulta->fetchAll(PDO::FETCH_CLASS, "Ingreso_empleado");		
	// }

	// public static function TraerIngreso($id_empleado) 
	// {
	// 		$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
	// 		$consulta =$objetoAccesoDato->RetornarConsulta("select *
	// 		from Ingresos_empleados where id_empleado = $id_empleado");
	// 		$consulta->execute();
	// 		$v= $consulta->fetchObject('Ingreso_empleado');
	// 		return $v;				

			
	// }


}

?>