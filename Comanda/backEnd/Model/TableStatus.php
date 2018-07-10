<?php
require_once './Model/BaseEnum.php';

class TableStatus extends BaseEnum {
    const __default = self::Closed;
    
    const Closed  = 0;
    const Waiting = 1;
    const Eating = 2;
    const Paying = 3;
   
    // echo TableStatus::Closed; 
    // echo TableStatus::toString(Sector::Closed);
}

?>