<?php

class CocheraDto{
  public $piso;
  public $numero;
  public $numeroDeOpereaciones;

  public function __construct() {
       
   }

}
class Cochera
{
	public $id;
	public $esParaDiscapacitados;
	public $enUso;
	public $piso;
	public $numero;
	public $dominio;

	public static function TraerCocheras($libres) 
	{
		$enUso = $libres? 0 : 1;
			$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
			$consulta =$objetoAccesoDato->RetornarConsulta("select  * from Cocheras WHERE enUso =
				:enUso");
			$consulta->bindValue(':enUso',$enUso, PDO::PARAM_INT);
			$consulta->execute();
			return $consulta->fetchAll(PDO::FETCH_CLASS, "Cochera");	
			
	}
  	
	//llamado al ingresar/sacar vehiculo
	public static function Modificar($id,$enUso,$dom)
	 {
		$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
		$consulta =$objetoAccesoDato->RetornarConsulta("
			update Cocheras 
			set 
			enUso = :enUso,
			dominio = :dom
			WHERE id =:id");
		$consulta->bindValue(':id',$id, PDO::PARAM_INT); 
		$consulta->bindValue(':enUso',$enUso, PDO::PARAM_INT); 
		$consulta->bindValue(':dom',$dom, PDO::PARAM_STR); 
		
		return $consulta->execute();
	  }

	  public static function TraerUsos()
	 {
		$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
		$consulta =$objetoAccesoDato->RetornarConsulta("
			select c.piso, c.numero, count(*) as numeroDeOpereaciones		
			from Cocheras c
			inner join Operaciones o on c.id = o.cocheraId
			group by c.id,c.piso,c.numero"); 
		
		 $consulta->execute();
		  // $c = $consulta->fetchObject('CocheraDto');
		 $c = $consulta->fetchAll(PDO::FETCH_CLASS, "CocheraDto");
		   // var_dump($c);
		  return $c;
	  } 
	
	 public static function TraerSinUsos()
	 {
		$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
		$consulta =$objetoAccesoDato->RetornarConsulta("
			select c.piso, c.numero, 0 as numeroDeOpereaciones
			from Cocheras c
			left join Operaciones o on c.id = o.cocheraId
			where o.id is null
			"); 
		
		 $consulta->execute();
		  // $c = $consulta->fetchObject('CocheraDto');
		 $c = $consulta->fetchAll(PDO::FETCH_CLASS, "CocheraDto");
		   // var_dump($c);die();
		  return $c;
	  } 



}