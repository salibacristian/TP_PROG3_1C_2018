<?php
class Table
{
	public $id;
	public $nombre;
 	public $apellido;
	public $clave;
	public $mail;
	public $turno;
	public $perfil;
	public $fecha_creacion;
	public $foto;

  	public function Borrar()
	 {
	 		$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
			$consulta =$objetoAccesoDato->RetornarConsulta("
				delete 
				from Empleados 				
				WHERE id=:id");	
				$consulta->bindValue(':id',$this->id, PDO::PARAM_INT);		
				$consulta->execute();
				return $consulta->rowCount();
	 }

	  public function Modificar()
	 {
		//var_dump($this);die;
		$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
		$consulta =$objetoAccesoDato->RetornarConsulta("
			update Empleados 
			set 
			nombre = :nombre,
			apellido = :apellido,
			clave = :clave,
			turno = :turno,
			perfil = :perfil
			WHERE id =:id");
		$consulta->bindValue(':id',$this->id, PDO::PARAM_INT);
		$consulta->bindValue(':nombre',$this->nombre, PDO::PARAM_STR);
		$consulta->bindValue(':apellido', $this->apellido, PDO::PARAM_STR);
		$consulta->bindValue(':clave', $this->clave, PDO::PARAM_STR);
		$consulta->bindValue(':turno', $this->turno, PDO::PARAM_STR);
		$consulta->bindValue(':perfil', $this->perfil, PDO::PARAM_STR);
		return $consulta->execute();
	 }

	 public function IngresarEmpleado()
	 {
		//var_dump($this);die;
		$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
		$consulta = $objetoAccesoDato->RetornarConsulta("INSERT INTO Empleados 
		(nombre,apellido,clave,mail,turno,perfil,fecha_creacion,foto)
		VALUES(:nombre,:apellido,:clave,:mail,:turno,:perfil,:fecha_creacion,:foto)");
		$consulta->bindValue(':nombre',$this->nombre, PDO::PARAM_STR);
		$consulta->bindValue(':apellido',$this->apellido, PDO::PARAM_STR);
		$consulta->bindValue(':clave', $this->clave, PDO::PARAM_INT);
		$consulta->bindValue(':mail', $this->mail, PDO::PARAM_STR);
		$consulta->bindValue(':turno', $this->turno, PDO::PARAM_STR);
		$consulta->bindValue(':perfil', $this->perfil, PDO::PARAM_STR);
		$consulta->bindValue(':fecha_creacion', $this->fecha_creacion, PDO::PARAM_STR);
		$consulta->bindValue(':foto', $this->foto, PDO::PARAM_STR);
		$consulta->execute();
		return $objetoAccesoDato->RetornarUltimoIdInsertado();
	 }

  	public static function TraerEmpleados()
	{
			$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
			$consulta =$objetoAccesoDato->RetornarConsulta("select * from Empleados");
			$consulta->execute();			
			return $consulta->fetchAll(PDO::FETCH_CLASS, "Empleado");		
	}

	public static function TraerEmpleado($id) 
	{
			$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
			$consulta =$objetoAccesoDato->RetornarConsulta("select *
			from Empleados where id = :id");
			$consulta->bindValue(':id',$id, PDO::PARAM_INT);
			$consulta->execute();
			$v= $consulta->fetchObject('Empleado');
			return $v;				

			
	}

	
	public static function VerificarEmpleado($mail,$clave) 
	{
			$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
			$consulta =$objetoAccesoDato->RetornarConsulta("select *
			from Empleados where mail = :mail AND clave = :clave");
			$consulta->bindValue(':mail',$mail, PDO::PARAM_STR);
			$consulta->bindValue(':clave',$clave, PDO::PARAM_STR);
			$consulta->execute();
			$v= $consulta->fetchObject('Empleado');
			return $v;				

			
	}

	public function Operaciones() 
	{
			$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
			$consulta = $objetoAccesoDato->RetornarConsulta("select 
				e.mail as user, count(*) as operaciones
			from Ingresos_empleados i
			inner join Empleados e on i.id_empleado = e.id
			group by e.mail");
			$consulta->execute();
			$v= $consulta->fetchAll(PDO::FETCH_ASSOC);
			return $v;				

			
	}

	


}

?>