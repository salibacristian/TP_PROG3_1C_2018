<?php
class RestaurantTable
{
	public $id;
	public $code;
 	public $status;
	
	public static function GetTablesByStatus($status) 
	{
			$ctx = AccesoDatos::dameUnObjetoAcceso(); 
			$query = $ctx->RetornarConsulta("select *
			from restauranttable 
			where status = :status");
			$query->bindValue(':status',$status, PDO::PARAM_INT);
			$query->execute();
			$t= $query->fetchAll(PDO::FETCH_CLASS,'RestaurantTable');
			return $t;				
		
	}

	public static function GetAll() 
	{
		$ctx = AccesoDatos::dameUnObjetoAcceso(); 
		$query = $ctx->RetornarConsulta("select *
		from restauranttable");
		$query->execute();
		return $query->fetchAll(PDO::FETCH_CLASS,'RestaurantTable');	
	}

	 public function AddTable()
	 {
		 try{
		// var_dump($this);die;
		$ctx = AccesoDatos::dameUnObjetoAcceso();
		$query = $ctx->RetornarConsulta("INSERT INTO restauranttable
		(code,status)
		VALUES(:code,:status)");
		$query->bindValue(':code',$this->code, PDO::PARAM_STR);
		$query->bindValue(':status',$this->status, PDO::PARAM_INT);
		$query->execute();		
		} catch (Exception $e) {
			throw $e; 
	  }
		return $ctx->RetornarUltimoIdInsertado();
	 }

    	public static function Delete($id)
	 {
	 		$ctx = AccesoDatos::dameUnObjetoAcceso(); 
			$query =$ctx->RetornarConsulta("
				delete 
				from restauranttable 				
				WHERE id=:id");	
				$query->bindValue(':id',$id, PDO::PARAM_INT);		
				$query->execute();
				return $query->rowCount();
	 }

	 public static function ChangeStatus($id,$status)
	 {
		$ctx = AccesoDatos::dameUnObjetoAcceso();
		$query = $ctx->RetornarConsulta("UPDATE restauranttable SET
		`status` = :status
		WHERE id=:id");
		$query->bindValue(':id',$id, PDO::PARAM_INT);
		$query->bindValue(':status',$status, PDO::PARAM_INT);
		return $query->execute();		
	 }

	// public function Operaciones() 
	// {
	// 		$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
	// 		$consulta = $objetoAccesoDato->RetornarConsulta("select 
	// 			e.mail as user, count(*) as operaciones
	// 		from Ingresos_empleados i
	// 		inner join Empleados e on i.id_empleado = e.id
	// 		group by e.mail");
	// 		$consulta->execute();
	// 		$v= $consulta->fetchAll(PDO::FETCH_ASSOC);
	// 		return $v;				

			
	// }

	


}

?>