<?php
class Item
{
	public $id;
	public $name;
	public $sectorId;
	public $estimatedTime;
	public $amount;
	public static function Letter() 
	{
		$ctx = AccesoDatos::dameUnObjetoAcceso(); 
		$query =$ctx->RetornarConsulta("select sectorId, id, name, amount from item group by sectorId, id, name, amount");	
		$query->execute();
		return $query->fetchAll(PDO::FETCH_CLASS,'Item');
			
	}
}
?>