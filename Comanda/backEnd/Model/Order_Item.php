<?php
class Order_Item
{
	public $id;
	public $orderId;
    public $itemId;
    public $takenDate;
	public $finishDate;

	public function Add()
	{
	   //var_dump($this);die;
	   $ctx = AccesoDatos::dameUnObjetoAcceso();
	   $query = $ctx->RetornarConsulta("INSERT INTO order_item 
	   (itemId,orderId)
	   VALUES(:itemId,:orderId)");
	   $query->bindValue(':itemId',$this->userId, PDO::PARAM_INT);
	   $query->bindValue(':orderId',$this->orderId, PDO::PARAM_INT);
	   $query->execute();
	   return $ctx->RetornarUltimoIdInsertado();
	}
}
?>