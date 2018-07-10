<?php
require_once './Model/BaseEnum.php';

class OrderStatus extends BaseEnum {
    const __default = self::Pending;
    
    const Pending  = 0;
    const InProgress = 1;
    const Finished = 2;
    const Canceled = 3;

   
    // echo OrderStatus::Closed; 
    // echo OrderStatus::toString(OrderStatus::Pending);
}

?>