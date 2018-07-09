<?php
require_once './Model/Operacion.php';
require_once './Model/Cochera.php';
require_once './Aplication/SessionService.php';

class CocheraService extends Cochera
{
 	public static function maxima($variable){
      $maxima = new stdclass();
      $maxima->numeroDeOpereaciones = 0;
      foreach ($variable as $value) {
        if($maxima->numeroDeOpereaciones < $value->numeroDeOpereaciones)
          $maxima = $value;
      }
      return $maxima;
    }

   public static function minima($variable){
      $min = new stdclass();
      $min->numeroDeOpereaciones = 99999;
      foreach ($variable as $value) {
        if($min->numeroDeOpereaciones > $value->numeroDeOpereaciones)
          $min = $value;
      }
      return $min;
    }

  public static function TraerStatus() {
      $objDelaRespuesta = new stdclass();
      $todas = Cochera::TraerUsos();
      // var_dump($todas);die();
       $objDelaRespuesta->masUsada  = CocheraService::maxima($todas);
       $objDelaRespuesta->menosUsada = CocheraService::minima($todas);
       $objDelaRespuesta->sinUso = Cochera::TraerSinUsos();
      return $objDelaRespuesta;
    }

    




}

