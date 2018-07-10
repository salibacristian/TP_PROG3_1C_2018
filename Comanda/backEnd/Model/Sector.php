<?php
require_once './Model/Role.php';

class Sector extends BaseEnum {
    const __default = self::BarraTragosVinos;
    
    const BarraTragosVinos = 0;
    const BarraChoperasCervezaArtesanal = 1;
    const Cocina = 2;
    const CandyBar  = 3;
    // echo Sector::BarraTragosVinos; 
    // echo Sector::toString(Sector::BarraTragosVinos);
}

?>