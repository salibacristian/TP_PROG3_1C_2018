<?php
class Order_Item
{
	public $id;
	public $orderId;
    public $itemId;
    public $units;
    public $takenDate;
	public $finishDate;

	public static function OrderItems($status,$sector) 
	{
		// var_dump($status);
		// var_dump($sector);die();
			$ctx = AccesoDatos::dameUnObjetoAcceso(); 
			$query =$ctx->RetornarConsulta("select 					
			orderId, itemId, units
			FROM `order_item`oi
			inner join `order` o on oi.orderId = o.id  
			inner join `item` i on oi.itemId = i.id
			WHERE (:sector = i.`sectorId`)
			AND (:status = o.`status`)
			GROUP BY orderId, itemId, units
			");	
			$query->bindValue(':sector',$sector, PDO::PARAM_INT);			
			$query->bindValue(':status',$status, PDO::PARAM_INT);		
			$query->execute();
			return $query->fetchAll(PDO::FETCH_CLASS,'Order_Item');
			
	}

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

	public static function Take($orderId, $itemId, $takenDate)
	{
	   $ctx = AccesoDatos::dameUnObjetoAcceso();
	   $query = $ctx->RetornarConsulta("UPDATE `order_item` SET
	   takenDate=:takenDate
	   WHERE orderId = :orderId AND itemId = :itemId");
	   $query->bindValue(':takenDate',$takenDate, PDO::PARAM_STR);	   
	   $query->bindValue(':orderId',$orderId, PDO::PARAM_INT);	   
	   $query->bindValue(':itemId',$itemId, PDO::PARAM_INT);	   
	   return $query->execute();
	}

	public static function GetPendingItems($orderId)
	{
	   $ctx = AccesoDatos::dameUnObjetoAcceso();
	   $query = $ctx->RetornarConsulta("SELECT * FROM `order_item` 
	  WHERE orderId = :orderId AND takenDate = null");
	   $query->bindValue(':orderId',$orderId, PDO::PARAM_INT);	   
	  return $query->fetchObject('Order_Item');
	}
}
?>