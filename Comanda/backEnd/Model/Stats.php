<?php
class Stats
{
	public $id;
	

	public static function OrderEmployeesBySector($fromDate,$toDate) 
	{
			$ctx = AccesoDatos::dameUnObjetoAcceso(); 
			$query =$ctx->RetornarConsulta("
			SELECT u.sectorId, u.email, COUNT(*) as ordersCount
			FROM `order`o 
			INNER JOIN `order_user`ou on o.id = ou.orderId
			INNER JOIN `user`u on ou.userId = u.id
			WHERE o.createdDate BETWEEN DATE(:fromDate) AND DATE(:toDate)
			AND o.status = 4
			GROUP BY u.sectorId, u.email
				");
			$query->bindValue(':fromDate',$fromDate, PDO::PARAM_STR);
			$query->bindValue(':toDate',$toDate, PDO::PARAM_STR);
			$query->execute();
			return $query->fetchAll(PDO::FETCH_CLASS,'SectorOrdersDto');	
			
	}

	public static function OrderEmployeesBySectorForExcel($fromDate,$toDate) 
	{
			$ctx = AccesoDatos::dameUnObjetoAcceso(); 
			$query =$ctx->RetornarConsulta("
			SELECT u.sectorId, u.email, COUNT(*) as ordersCount
			FROM `order`o 
			INNER JOIN `order_user`ou on o.id = ou.orderId
			INNER JOIN `user`u on ou.userId = u.id
			WHERE o.createdDate BETWEEN DATE(:fromDate) AND DATE(:toDate)
			AND o.status = 4
			GROUP BY u.sectorId, u.email
				");
			$query->bindValue(':fromDate',$fromDate, PDO::PARAM_STR);
			$query->bindValue(':toDate',$toDate, PDO::PARAM_STR);
			$query->execute();
			return $query->fetchAll(PDO::FETCH_NUM);	 
			
	}
	
	public static function OrdersBySector($fromDate,$toDate) 
	{
			$ctx = AccesoDatos::dameUnObjetoAcceso(); 
			$query =$ctx->RetornarConsulta("
			SELECT u.sectorId, COUNT(*) as ordersCount
			FROM `order`o 
			INNER JOIN `order_user`ou on o.id = ou.orderId
			INNER JOIN `user`u on ou.userId = u.id
			WHERE o.createdDate BETWEEN DATE(:fromDate) AND DATE(:toDate)
			AND o.status = 4
			GROUP BY u.sectorId
				");
			$query->bindValue(':fromDate',$fromDate, PDO::PARAM_STR);
			$query->bindValue(':toDate',$toDate, PDO::PARAM_STR);
			$query->execute();
			return $query->fetchAll(PDO::FETCH_CLASS,'SectorOrdersDto');	
			
	}
	
	public static function UnitsSales($fromDate,$toDate) 
	{
			$ctx = AccesoDatos::dameUnObjetoAcceso(); 
			$query =$ctx->RetornarConsulta("
			SELECT i.name, SUM(oi.units) as units
			FROM `order`o 
			INNER JOIN `order_item`oi on o.id = oi.orderId
			INNER JOIN `item`i on oi.itemId = i.id
			WHERE o.createdDate BETWEEN DATE(:fromDate) AND DATE(:toDate)
			AND o.status = 4
			GROUP BY i.id, i.name
			ORDER BY oi.units DESC
				");
			$query->bindValue(':fromDate',$fromDate, PDO::PARAM_STR);
			$query->bindValue(':toDate',$toDate, PDO::PARAM_STR);
			$query->execute();
			return $query->fetchAll(PDO::FETCH_ASSOC);	
			
	}

	public static function DelayedOrders($fromDate,$toDate) 
	{
			$ctx = AccesoDatos::dameUnObjetoAcceso(); 
			$query =$ctx->RetornarConsulta("
			SELECT *
			FROM `order`o 
			WHERE o.createdDate BETWEEN DATE(:fromDate) AND DATE(:toDate)
			and o.realTime > o.estimatedTime
			AND o.status = 4
				");
			$query->bindValue(':fromDate',$fromDate, PDO::PARAM_STR);
			$query->bindValue(':toDate',$toDate, PDO::PARAM_STR);
			$query->execute();
			return $query->fetchAll(PDO::FETCH_ASSOC);	
			
	}
	
	public static function CanceledOrders($fromDate,$toDate) 
	{
			$ctx = AccesoDatos::dameUnObjetoAcceso(); 
			$query =$ctx->RetornarConsulta("
			SELECT *
			FROM `order`o 
			WHERE o.createdDate BETWEEN DATE(:fromDate) AND DATE(:toDate)
            AND o.isCanceled = 1
				");
			$query->bindValue(':fromDate',$fromDate, PDO::PARAM_STR);
			$query->bindValue(':toDate',$toDate, PDO::PARAM_STR);
			$query->execute();
			return $query->fetchAll(PDO::FETCH_ASSOC);	
			
	}
	
	public static function TableUsage($fromDate,$toDate) 
	{
		$ctx = AccesoDatos::dameUnObjetoAcceso(); 
		$query =$ctx->RetornarConsulta("
		SELECT t.code, COUNT(*) as count
		FROM `order`o 
		INNER JOIN `restauranttable`t on o.tableId = t.id
		WHERE o.createdDate BETWEEN DATE(:fromDate) AND DATE(:toDate)
		AND o.status = 4
		GROUP BY t.id, t.code
		ORDER BY count DESC
			");
		$query->bindValue(':fromDate',$fromDate, PDO::PARAM_STR);
		$query->bindValue(':toDate',$toDate, PDO::PARAM_STR);
		$query->execute();
		return $query->fetchAll(PDO::FETCH_ASSOC);		
			
	}
	
	public static function TableTotalAmount($fromDate,$toDate) 
	{
		$ctx = AccesoDatos::dameUnObjetoAcceso(); 
		$query =$ctx->RetornarConsulta("
		SELECT t.code, SUM(o.amount) as amount
		FROM `order`o 
		INNER JOIN `restauranttable`t on o.tableId = t.id
		WHERE o.createdDate BETWEEN DATE(:fromDate) AND DATE(:toDate)
		AND o.status = 4
		GROUP BY t.id, t.code
			");
		$query->bindValue(':fromDate',$fromDate, PDO::PARAM_STR);
		$query->bindValue(':toDate',$toDate, PDO::PARAM_STR);
		$query->execute();
		return $query->fetchAll(PDO::FETCH_ASSOC);		
			
	}
	
	public static function Billing($fromDate,$toDate) 
	{
		$ctx = AccesoDatos::dameUnObjetoAcceso(); 
		$query =$ctx->RetornarConsulta("
		SELECT SUM(o.amount) as amount
		FROM `order`o 
		WHERE o.createdDate BETWEEN DATE(:fromDate) AND DATE(:toDate)
		AND o.status = 4
			");
		$query->bindValue(':fromDate',$fromDate, PDO::PARAM_STR);
		$query->bindValue(':toDate',$toDate, PDO::PARAM_STR);
		$query->execute();
		return $query->fetchAll(PDO::FETCH_ASSOC);		
			
    }



}
	

class SectorOrdersDto{
	public $sectorId;
	public $email;
	public $ordersCount;
}
?>