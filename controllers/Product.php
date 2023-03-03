<?php

namespace controllers;

use main\Error;
use models\ProductModel;

class User extends ProductModel
{
    private object $error;

    public function __construct()
    {
        parent::__construct();
        $this->error = new Error("Product Controller");
    }

    public function getProductByID(array $post): string|false
    {
        $useArray = array("idProduct");
        $this->validateDataGet($post, $useArray);
        $this->get(array("firstName"), array("idUser" => $this->validatedArray["idUser"]));
        if ($this->checkFetch()) {
            return $this->fetchRow()["firstName"];
        }
        return false;
    }
}