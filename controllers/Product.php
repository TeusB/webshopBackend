<?php

namespace controllers;

use main\Error;
use models\ProductModel;

class Product extends ProductModel
{
    private object $error;

    public function __construct()
    {
        parent::__construct();
        $this->error = new Error("Product Controller");
    }

    public function getProductByID(array $post): array|false
    {
        $useArray = array("idProduct");
        $this->validateDataGet($post, $useArray);
        $this->get(array("*"), array("idProduct" => $this->validatedArray["idProduct"]));
        if ($this->checkFetch()) {
            return $this->fetchRow();
        }
        return false;
    }

    public function getProductByQuery($idProduct): array|false
    {
        $this->getHandler("SELECT Product.idProduct, Product.name, Product.descr, Product.price, Product.stock, category.idCategory, category.name FROM Product LEFT JOIN category ON category.idCategory = product.idCategory WHERE product.idProduct = ?;", [$idProduct]);
        if ($this->checkFetch()) {
            return $this->fetchRow();
        }
        return false;
    }
}
