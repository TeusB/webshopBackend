<?php

namespace models;

use Dotenv\Parser\Value;
use main\Error;
use main\Model;
use main\Validate;

class shoppingCartModel extends Model
{
    public string $tableName = "shoppingcart";
    public int|string $identifierColumn = "idShoppingCart";
    private object $error;
    public object $validate;
    protected array $validatedArray = array();


    public function createShoppingCart($ID): bool
    {
        return $this->insert(array(
            "idUser" => $ID,
        ));
    }
}