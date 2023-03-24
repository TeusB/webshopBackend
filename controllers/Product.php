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

    public function getProductByID(array $get): array|false
    {
        $useArray = array("idProduct");
        $this->validateDataGet($get, $useArray);
        $this->get(array("*"), array("idProduct" => $this->validatedArray["idProduct"]));
        if ($this->checkFetch()) {
            return $this->fetchRow();
        }
        return false;
    }

    public function getProductByIDJSON(array $get)
    {
        $useArray = array("idProduct");
        $this->validateDataGet($get, $useArray);
        $this->get(array("*"), array("idProduct" => $this->validatedArray["idProduct"]));
        if ($this->checkFetch()) {
            echo json_encode([
                "succes" => "succes",
                "data" => $this->fetchRow()
            ]);
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
        if (!empty($post["imageURL"]) && $post["imageURL"]["name"] !== '') {
            $target_dir = "../uploads/";
            $file = $post['imageURL']['name'];
            $path = pathinfo($file);
            $filename = $path['filename'];
            $ext = $path['extension'];
            $temp_name = $post['imageURL']['tmp_name'];
            $path_filename_ext = $target_dir . $filename . "." . $ext;
            move_uploaded_file($temp_name, $path_filename_ext);
            return $this->update(array(
                "imageURL" => $temp_name,
            ), array(
                "idProduct" => $this->returnLastID(),
            ));
        }
        return true;
    }

    public function softDeleteProduct(array $softDelete)
    {
        $useArray = array("idProduct");
        $this->validateDataInput($softDelete, $useArray);
        return $this->update(array(
            "active" =>  0,
        ), array(
            "idProduct" => $this->validatedArray["idProduct"],
        ));
    }

    public function checkIfProductExists(array $post): bool
    {
        $useArray = array("idProduct");
        $this->validateDataGet($post, $useArray);
        return $this->checkExist(array("idProduct" => $this->validatedArray["idProduct"]));
    }

    public function getProductJoinCategory($idProduct): array|false
    {
        $this->getHandler("SELECT product.imageURL, Product.idProduct, Product.name AS productName, Product.descr, Product.price, Product.stock, category.idCategory, category.name AS categoryName FROM Product LEFT JOIN category ON category.idCategory = product.idCategory WHERE product.idProduct = ?;", [$idProduct]);
        if ($this->checkFetch()) {
            return $this->fetchRow();
        }
        return false;
    }

    public function getActiveProductsJoincategory(): array|false
    {
        $this->getHandler("SELECT Product.idProduct, Product.name AS productName, Product.descr, Product.price, Product.stock, category.idCategory, category.name AS categoryName FROM Product LEFT JOIN category ON category.idCategory = product.idCategory WHERE product.active = 1");
        if ($this->checkFetch()) {
            return $this->fetch();
        }
        return false;
    }


    public function getActiveProductsJoincategoryJSON()
    {
        $this->getHandler("SELECT product.imageURL, Product.idProduct, Product.name AS productName, Product.descr, Product.price, Product.stock, category.idCategory, category.name AS categoryName FROM Product LEFT JOIN category ON category.idCategory = product.idCategory WHERE product.active = 1");
        if ($this->checkFetch()) {
            echo json_encode([
                "succes" => "succes",
                "data" => json_encode($this->fetch())
            ]);
        }
        return true;
    }

    public function updateProduct(array $put)
    {
        $useArray = array("idProduct", "name", "descr", "idCategory", "price", "stock");
        $this->validateDataInput($put, $useArray);
        $this->update(array(
            "name" =>  $this->validatedArray["name"],
            "descr" => $this->validatedArray["descr"],
            "idCategory" => $this->validatedArray["idCategory"],
            "price" => $this->validatedArray["price"],
            "stock" => $this->validatedArray["stock"],
        ), array(
            "idProduct" => $this->validatedArray["idProduct"],
        ));
        if (!empty($put["imageURL"]) && $put["imageURL"]["name"] !== '') {
            $target_dir = "../uploads/";
            $file = $put['imageURL']['name'];
            $path = pathinfo($file);
            $filename = $path['filename'];
            $ext = $path['extension'];
            $temp_name = $put['imageURL']['tmp_name'];
            $path_filename_ext = $target_dir . $filename . "." . $ext;
            move_uploaded_file($temp_name, $path_filename_ext);
            return $this->update(array(
                "imageURL" => $temp_name,
            ), array(
                "idProduct" => $this->validatedArray["idProduct"],
            ));
        }
        return true;
    }
}
