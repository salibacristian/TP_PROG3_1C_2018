<?php
abstract class BaseEnum{
    private final function __construct(){}
    public static function toString($val){
        $tmp = new ReflectionClass(get_called_class());
        $a = $tmp->getConstants();
        $b = array_flip($a);
        return ucfirst(strtolower($b[$val]));
    }
}
class OrderStatus extends BaseEnum {
    const __default = self::Pending;    
    const Pending  = 0;
    const InProgress = 1;
    const Finished = 2;
    const Canceled = 3;
}
class Role extends BaseEnum {
    const __default = self::Administrator;    
    // const Client = 0;
    const Administrator = 1;
    const Waiter = 2;
    const Producer = 3;
}
class Sector extends BaseEnum {
    const __default = self::BarraTragosVinos;    
    const BarraTragosVinos = 0;
    const BarraChoperasCervezaArtesanal = 1;
    const Cocina = 2;
    const CandyBar  = 3;
}
class TableStatus extends BaseEnum {
    const __default = self::Closed;    
    const Closed  = 0;
    const Waiting = 1;
    const Eating = 2;
    const Paying = 3;
}
?>