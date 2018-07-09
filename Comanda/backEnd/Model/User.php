<?php
class User
{
	public $id;
	public $name;
	public $email;
 	public $password;
	public $isSuspended;
	public $isDeleted;
	public $sectorId;
	public $role;

	public static function GetUsers() 
	{
			$ctx = AccesoDatos::dameUnObjetoAcceso(); 
			$query =$ctx->RetornarConsulta("select * from user");
			$query->execute();
			$u= $query->fetchAll(PDO::FETCH_CLASS,'User');
      		return $u;		
			
	}

	public static function GetUserById($id) 
	{
			$ctx = AccesoDatos::dameUnObjetoAcceso(); 
			$query =$ctx->RetornarConsulta("select  * from user WHERE id =
				:id");
			$query->bindValue(':id',$id, PDO::PARAM_INT);
			$query->execute();
			$u= $query->fetchObject('User');
      		return $u;				

			
	}
	 public function AddUser()
	 {
		// var_dump($this);die();
		$ctx = AccesoDatos::dameUnObjetoAcceso();
		$query = $ctx->RetornarConsulta("INSERT INTO user 
		(name,email,password,isSuspended,isDeleted,sectorId,role)
		VALUES(:name,:email,:password,:isSuspended,:isDeleted,:sectorId,:role)");
		$query->bindValue(':name',$this->name, PDO::PARAM_STR);
		$query->bindValue(':email',$this->email, PDO::PARAM_STR);
		$query->bindValue(':password',$this->password, PDO::PARAM_STR);
		$query->bindValue(':isSuspended', $this->isSuspended, PDO::PARAM_INT);
		$query->bindValue(':isDeleted', $this->isDeleted, PDO::PARAM_INT);
		$query->bindValue(':sectorId', $this->sectorId, PDO::PARAM_INT);
		$query->bindValue(':role', $this->role, PDO::PARAM_INT);
		$query->execute();
		return $ctx->RetornarUltimoIdInsertado();
	 }	
	 public function UpdateUser()
	 {
		//   var_dump($this);die;
		$ctx = AccesoDatos::dameUnObjetoAcceso(); 
		$query =$ctx->RetornarConsulta("
			update user 
			set 
			name = :name,
			email = :email,
			password = :password,
			sectorId = :sectorId,		
			role = :role		
			WHERE id =:id");
		$query->bindValue(':id',$this->id, PDO::PARAM_INT);
		$query->bindValue(':name',$this->name, PDO::PARAM_STR);
		$query->bindValue(':email',$this->email, PDO::PARAM_STR);
		$query->bindValue(':password',$this->password, PDO::PARAM_STR);
		$query->bindValue(':sectorId', $this->sectorId, PDO::PARAM_INT);
		$query->bindValue(':role', $this->role, PDO::PARAM_INT);
	
		return $query->execute();
	 }
	 
	 public static function Delete($id,$status) 
	 {

			 $ctx = AccesoDatos::dameUnObjetoAcceso(); 
			 $query =$ctx->RetornarConsulta("
			 update user 
			 set 
			 isDeleted = :status			
			 WHERE id =:id");
		 $query->bindValue(':id',$id, PDO::PARAM_INT);
		 $query->bindValue(':status',$status, PDO::PARAM_INT);
			return $query->execute();	
	 }
	 public static function Suspend($id,$status) 
	 {
		$ctx = AccesoDatos::dameUnObjetoAcceso(); 
		$query =$ctx->RetornarConsulta("
		update user 
		set 
		isSuspended = :status			
		WHERE id =:id");
		$query->bindValue(':id',$id, PDO::PARAM_INT);
		$query->bindValue(':status',$status, PDO::PARAM_INT);
	   return $query->execute();		
	 }

	 public static function Check($email,$pass) 
	 {
		$ctx = AccesoDatos::dameUnObjetoAcceso(); 
		$query =$ctx->RetornarConsulta("
		select * from user 			
		WHERE email =:email AND password = :pass");
		$query->bindValue(':email',$email, PDO::PARAM_STR);
		$query->bindValue(':pass',$pass, PDO::PARAM_STR);
	    $query->execute();	
	   $u= $query->fetchObject('User');
	   return $u;			
	 }

  	// public function BorrarVehiculo()
	//  {
	//  		$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
	// 		$consulta =$objetoAccesoDato->RetornarConsulta("
	// 			delete 
	// 			from Vehiculos 				
	// 			WHERE id=:id");	
	// 			$consulta->bindValue(':id',$this->id, PDO::PARAM_INT);		
	// 			$consulta->execute();
	// 			return $consulta->rowCount();
	//  }

	// public static function BorrarCdPorAnio($año)
	 // {

			// $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
			// $consulta =$objetoAccesoDato->RetornarConsulta("
				// delete 
				// from cds 				
				// WHERE jahr=:anio");	
				// $consulta->bindValue(':anio',$año, PDO::PARAM_INT);		
				// $consulta->execute();
				// return $consulta->rowCount();

	 // }
	// public function Modificar()
	//  {
	// 	// var_dump($this);die;
	// 	$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
	// 	$consulta =$objetoAccesoDato->RetornarConsulta("
	// 		update Operaciones 
	// 		set 
	// 		id_empleado_salida = :id_empleado_salida,
	// 		fecha_hora_salida = :fecha_hora_salida,
	// 		tiempo = :tiempo,
	// 		importe = :importe,
	// 		cocheraId = :cocheraId		
	// 		WHERE id =:id");
	// 	$consulta->bindValue(':id',$this->id, PDO::PARAM_INT);
	// 	$consulta->bindValue(':id_empleado_salida',$this->id_empleado_salida, PDO::PARAM_INT);
	// 	$consulta->bindValue(':fecha_hora_salida', $this->fecha_hora_salida, PDO::PARAM_STR);
	// 	$consulta->bindValue(':tiempo', $this->tiempo, PDO::PARAM_STR);
	// 	$consulta->bindValue(':importe', $this->importe, PDO::PARAM_STR);
	// 	$consulta->bindValue(':cocheraId', $this->cocheraId, PDO::PARAM_INT);
	// 	return $consulta->execute();
	//   }
	

	  // public function ModificarCdParametros()
	 // {
			// $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
			// $consulta =$objetoAccesoDato->RetornarConsulta("
				// update cds 
				// set titel=:titulo,
				// interpret=:cantante,
				// jahr=:anio
				// WHERE id=:id");
			// $consulta->bindValue(':id',$this->id, PDO::PARAM_INT);
			// $consulta->bindValue(':titulo',$this->titulo, PDO::PARAM_INT);
			// $consulta->bindValue(':anio', $this->año, PDO::PARAM_STR);
			// $consulta->bindValue(':cantante', $this->cantante, PDO::PARAM_STR);
			// return $consulta->execute();
	 // }

	

  	// public static function TraerOperaciones()
	// {
	// 		$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
	// 		$consulta =$objetoAccesoDato->RetornarConsulta("select * from Operaciones");
	// 		$consulta->execute();			
	// 		return $consulta->fetchAll(PDO::FETCH_CLASS, "Operacion");		
	// }

	// public static function TraerOperacion($id) 
	// {
	// 		$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
	// 		$consulta =$objetoAccesoDato->RetornarConsulta("select *
	// 		from Operaciones where id = $id");
	// 		$consulta->execute();
	// 		$v= $consulta->fetchObject('Operacion');
	// 		return $v;				

			
	// }

	// public static function TraerOperacionPorDominio($dom) 
	// {
	// 		$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
	// 		$consulta =$objetoAccesoDato->RetornarConsulta("select  * from Operaciones WHERE dominio =
	// 			:dominio AND fecha_hora_salida is null");
	// 		$consulta->bindValue(':dominio',$dom, PDO::PARAM_STR);
	// 		$consulta->execute();
	// 		$o= $consulta->fetchObject('Operacion');
    //   		return $o;				

			
	// }

	// public static function TraerUnCdAnioParamNombre($id,$anio) 
	// {
			// $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
			// $consulta =$objetoAccesoDato->RetornarConsulta("select  titel as titulo, interpret as cantante,jahr as año from cds  WHERE id=:id AND jahr=:anio");
			// $consulta->bindValue(':id', $id, PDO::PARAM_INT);
			// $consulta->bindValue(':anio', $anio, PDO::PARAM_STR);
			// $consulta->execute();
			// $cdBuscado= $consulta->fetchObject('cd');
      		// return $cdBuscado;				

			
	// }
	
	// public static function TraerUnCdAnioParamNombreArray($id,$anio) 
	// {
			// $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
			// $consulta =$objetoAccesoDato->RetornarConsulta("select  titel as titulo, interpret as cantante,jahr as año from cds  WHERE id=:id AND jahr=:anio");
			// $consulta->execute(array(':id'=> $id,':anio'=> $anio));
			// $consulta->execute();
			// $cdBuscado= $consulta->fetchObject('cd');
      		// return $cdBuscado;				

			
	// }


}

?>