<?php
abstract class BaseEnum{
    private final function __construct(){ }

    public static function toString($val){
        $tmp = new ReflectionClass(get_called_class());
        $a = $tmp->getConstants();
        $b = array_flip($a);

        return ucfirst(strtolower($b[$val]));
    }
}
?>