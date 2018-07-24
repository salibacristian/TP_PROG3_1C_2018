 <?php
require_once './Model/Item.php';

class ItemService extends Item 
{
    public function GetLetter($request, $response, $args) { 
        $letter = Item::Letter();
        return $response->withJson($letter, 200);  
      }

}

?>