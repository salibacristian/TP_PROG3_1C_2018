<?php
class Order
{
	public $id;
	public $tableId;
	public $code;
	public $status;
	public $imgUrl;	
	public $estimatedTime;
	public $realTime;
	public $isCanceled;
	public $amount;
	public $comment;
	public $tablePoints;
	public $waiterPoints;
	public $producerPoints;
	public $createdDate;
	public $takenDate;
	public $finishDate;

	public static function OrderByCode($code) 
	{

			$ctx = AccesoDatos::dameUnObjetoAcceso(); 
			$query =$ctx->RetornarConsulta("select * from `order`
			WHERE `code` = :code
			");	
			$query->bindValue(':code',$code, PDO::PARAM_STR);			
			$query->execute();
			return $query->fetchAll(PDO::FETCH_CLASS,'Order');
			
	}
	public static function AllOrders() 
	{
			$ctx = AccesoDatos::dameUnObjetoAcceso(); 
			$query =$ctx->RetornarConsulta("select * from `order` where `status` != 3");	
			$query->execute();
			return $query->fetchAll(PDO::FETCH_CLASS,'Order');
			
	}

	public static function GetOrderById($id) 
	{
			$ctx = AccesoDatos::dameUnObjetoAcceso(); 
			$query =$ctx->RetornarConsulta("select  * from `order` WHERE id =
				:id");
			$query->bindValue(':id',$id, PDO::PARAM_INT);
			$query->execute();
			$o= $query->fetchObject('Order');
      		return $o;				

			
	}

	public static function GetOrderItems($id) 
	{
			$ctx = AccesoDatos::dameUnObjetoAcceso(); 
			$query =$ctx->RetornarConsulta("
			select o.id as orderId, o.status, i.sectorId, i.id as itemId, oi.units, i.estimatedTime, oi.takenDate, i.amount  
			from `order`o
			inner join `order_item`oi on o.id = oi.orderId
			inner join `item`i on oi.itemId = i.id
			WHERE o.id = :id
				");
			$query->bindValue(':id',$id, PDO::PARAM_INT);
			$query->execute();
			return $query->fetchAll(PDO::FETCH_CLASS,'OrderItemDto');	
			
	}
	public function Add()
	{
	    // var_dump($this);die();
	   $ctx = AccesoDatos::dameUnObjetoAcceso();
	   $query = $ctx->RetornarConsulta("INSERT INTO `order` 
	   (tableId,imgUrl,status,code,isCanceled,createdDate)
	   VALUES(:tableId,:imgUrl,:status,:code,:isCanceled,:createdDate)");
	   $query->bindValue(':tableId',$this->tableId, PDO::PARAM_INT);
	   $query->bindValue(':imgUrl',$this->imgUrl, PDO::PARAM_STR);
	   $query->bindValue(':status', 0, PDO::PARAM_INT);
	   $query->bindValue(':code', $this->code, PDO::PARAM_STR);
	   $query->bindValue(':isCanceled', 0, PDO::PARAM_INT);
	   $query->bindValue(':createdDate', $this->createdDate, PDO::PARAM_STR);
	   $query->execute();
	   return $ctx->RetornarUltimoIdInsertado();
	}	
	public static function Take($orderId, $status, $estimatedTime, $takenDate)
	{
	   $ctx = AccesoDatos::dameUnObjetoAcceso();
	   $query = $ctx->RetornarConsulta("UPDATE `order` SET
	   `status`=:status,estimatedTime=:estimatedTime,takenDate=:takenDate
	   WHERE id = :id");
	   $query->bindValue(':status',$status, PDO::PARAM_INT);
	   $query->bindValue(':estimatedTime',$estimatedTime, PDO::PARAM_INT);
	   $query->bindValue(':takenDate',$takenDate, PDO::PARAM_STR);	   
	   $query->bindValue(':id',$orderId, PDO::PARAM_INT);	   
	   return $query->execute();
	}
	public static function Finish($orderId, $status, $realTime, $finishDate)
	{
	   $ctx = AccesoDatos::dameUnObjetoAcceso();
	   $query = $ctx->RetornarConsulta("UPDATE `order` SET
	   `status`=:status,realTime=:realTime,finishDate=:finishDate
	   WHERE id = :id");
	   $query->bindValue(':status',$status, PDO::PARAM_INT);
	   $query->bindValue(':realTime',$realTime, PDO::PARAM_INT);
	   $query->bindValue(':finishDate',$finishDate, PDO::PARAM_STR);	   
	   $query->bindValue(':id',$orderId, PDO::PARAM_INT);	   
	   return $query->execute();
	}
	public static function Cancel($orderId)
	{
	   $ctx = AccesoDatos::dameUnObjetoAcceso();
	   $query = $ctx->RetornarConsulta("UPDATE `order` SET
	   `status`=:status
	   WHERE id = :id");	
	   $query->bindValue(':status',3, PDO::PARAM_INT);   
	   $query->bindValue(':id',$orderId, PDO::PARAM_INT);	   
	   return $query->execute();
	}

	public static function Deliver($orderId)
	{
	   $ctx = AccesoDatos::dameUnObjetoAcceso();
	   $query = $ctx->RetornarConsulta("UPDATE `order` SET
	   `status`=:status
	   WHERE id = :id");	
	   $query->bindValue(':status',4, PDO::PARAM_INT);   
	   $query->bindValue(':id',$orderId, PDO::PARAM_INT);	   
	   return $query->execute();
	}
	public function SetAmount($value){
		$ctx = AccesoDatos::dameUnObjetoAcceso();
		$query = $ctx->RetornarConsulta("UPDATE `order` SET
		 amount	= :amount		
		WHERE id = :id");
		$query->bindValue(':id',$this->id, PDO::PARAM_INT);	   
		$query->bindValue(':amount',$value, PDO::PARAM_INT);	      
		return $query->execute();
	}
	//////////////////////////////////////
	//CLIENTS
	//////////////////////////////////////
	
	public static function OrderForClient($tableCode,$orderCode) 
	{
		// var_dump($orderCode);die();
		$ctx = AccesoDatos::dameUnObjetoAcceso(); 
		$query =$ctx->RetornarConsulta("select o.id, o.tableId, o.status,o.code,o.estimatedTime,o.realTime,o.amount,o.takenDate,o.finishDate
		from `order` o
		inner join restauranttable t on o.tableId = t.id
		WHERE (:orderCode = o.code) and (:tableCode = t.code)");	
		$query->bindValue(':orderCode',$orderCode, PDO::PARAM_STR);			
		$query->bindValue(':tableCode',$tableCode, PDO::PARAM_STR);			
		$query->execute();
		return $query->fetchObject('Order');
			
	}	
	
	public static function CommentAndRate($orderId,$comment,$tablePoints,$waiterPoints,$producerPoints)
	{
	   $ctx = AccesoDatos::dameUnObjetoAcceso();
	   $query = $ctx->RetornarConsulta("UPDATE `order` SET
		comment	= :comment,
		tablePoints = :tablePoints,
		waiterPoints = :waiterPoints,
		producerPoints = :producerPoints
	   WHERE id = :id");
	   $query->bindValue(':id',$orderId, PDO::PARAM_INT);	   
	   $query->bindValue(':comment',$comment, PDO::PARAM_STR);	   
	   $query->bindValue(':tablePoints',$tablePoints, PDO::PARAM_INT);	   
	   $query->bindValue(':waiterPoints',$waiterPoints, PDO::PARAM_INT);	   
	   $query->bindValue(':producerPoints',$producerPoints, PDO::PARAM_INT);	   
	   return $query->execute();
	}

}

class OrderItemDto{
	public $orderId;
	public $status;
	public $sectorId;
	public $itemId;
	public $units;
	public $estimatedTime;
	public $takenDate;
	public $amount;

}
?>