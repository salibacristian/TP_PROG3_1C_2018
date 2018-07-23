<?php
class Order_Item
{
	public $id;
	public $orderId;
    public $itemId;
    public $units;
    public $takenDate;
	public $finishDate;

	public function Add()
	{
	//    var_dump($this);die;
	   $ctx = AccesoDatos::dameUnObjetoAcceso();
	   $query = $ctx->RetornarConsulta("INSERT INTO order_item 
	   (itemId,units,orderId)
	   VALUES(:itemId,:units,:orderId)");
	   $query->bindValue(':itemId',$this->itemId, PDO::PARAM_INT);
	   $query->bindValue(':units',$this->units, PDO::PARAM_INT);
	   $query->bindValue(':orderId',$this->orderId, PDO::PARAM_INT);
	   $query->execute();
	   return $ctx->RetornarUltimoIdInsertado();
	}
}
?>