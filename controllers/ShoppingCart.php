<?php

namespace controllers;

use models\shoppingCartModel;
use main\Error;
use models\UserModel;
use main\Mailer;

class ShoppingCart extends shoppingCartModel
{
    public function createShoppingCart($ID): bool
    {
        return $this->insert(array(
            "idUser" => $ID,
        ));
    }
}