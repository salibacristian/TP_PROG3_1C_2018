<?php
require_once './Model/BaseEnum.php';

class Role extends BaseEnum {
    const __default = self::Administrator;
    
    // const Client = 0;
    const Administrator = 1;
    const Waiter = 2;
    const Producer = 3;

}

	// echo Role::Administrator; 
    // echo Role::toString(Role::Administrator);
?>