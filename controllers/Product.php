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

    public function insertProduct(array $post): bool
    {
        $useArray = array("name", "descr", "idCategory", "price", "stock");
        $this->validateDataInput($post, $useArray);
        $this->insert(array(
            "name" => $this->validatedArray["name"],
            "descr" => $this->validatedArray["descr"],
            "idCategory" => $this->validatedArray["idCategory"],
            "price" => $this->validatedArray["price"],
            "stock" => $this->validatedArray["stock"],
        ));
        return true;
    }

    public function checkIfProductExists(array $post): bool
    {
        $useArray = array("idProduct");
        $this->validateDataGet($post, $useArray);
        return $this->checkExist(array("idProduct" => $this->validatedArray["idProduct"]));
    }

    public function getProductJoinCategory($idProduct): array|false
    {
        $this->getHandler("SELECT Product.idProduct, Product.name AS productName, Product.descr, Product.price, Product.stock, category.idCategory, category.name AS categoryName FROM Product LEFT JOIN category ON category.idCategory = product.idCategory WHERE product.idProduct = ?;", [$idProduct]);
        if ($this->checkFetch()) {
            return $this->fetchRow();
        }
        return false;
    }

    public function getProductsJoincategory(): array|false
    {
        $this->getHandler("SELECT Product.idProduct, Product.name AS productName, Product.descr, Product.price, Product.stock, category.idCategory, category.name AS categoryName FROM Product LEFT JOIN category ON category.idCategory = product.idCategory");
        if ($this->checkFetch()) {
            return $this->fetch();
        }
        return false;
    }

    public function updateProduct(array $put)
    {
        $useArray = array("idProduct", "name", "descr", "idCategory", "price", "stock");
        $this->validateDataInput($put, $useArray);
        return $this->update(array(
            "name" =>  $this->validatedArray["name"],
            "descr" => $this->validatedArray["descr"],
            "idCategory" => $this->validatedArray["idCategory"],
            "price" => $this->validatedArray["price"],
            "stock" => $this->validatedArray["stock"],
        ), array(
            "idProduct" => $this->validatedArray["idProduct"],
        ));
    }
}
