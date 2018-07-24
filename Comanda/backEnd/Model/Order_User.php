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




}

?>