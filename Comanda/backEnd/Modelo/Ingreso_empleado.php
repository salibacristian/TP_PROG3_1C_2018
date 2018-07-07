<?php
class Ingreso_empleado
{
	public $id;
	public $fecha_hora_ingreso;
	public $id_empleado;
	

	 public function Ingresar()
	 {
		//var_dump($this);die;
		$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
		$consulta = $objetoAccesoDato->RetornarConsulta("INSERT INTO Ingresos_empleados 
		(fecha_hora_ingreso,id_empleado)
		VALUES(:fecha_hora_ingreso,:id_empleado)");
		$consulta->bindValue(':fecha_hora_ingreso',$this->fecha_hora_ingreso, PDO::PARAM_STR);
		$consulta->bindValue(':id_empleado',$this->id_empleado, PDO::PARAM_INT);
		$consulta->execute();
		return $objetoAccesoDato->RetornarUltimoIdInsertado();
	 }


  	public static function TraerIngresos()
	{
			$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
			$consulta =$objetoAccesoDato->RetornarConsulta("select * from Ingresos_empleados");
			$consulta->execute();			
			return $consulta->fetchAll(PDO::FETCH_CLASS, "Ingreso_empleado");		
	}

	public static function TraerIngreso($id_empleado) 
	{
			$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
			$consulta =$objetoAccesoDato->RetornarConsulta("select *
			from Ingresos_empleados where id_empleado = $id_empleado");
			$consulta->execute();
			$v= $consulta->fetchObject('Ingreso_empleado');
			return $v;				

			
	}


}