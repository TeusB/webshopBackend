<?php

namespace webshop;

class Product extends AbstractView
{
    private $p_model;
    public $validate;
    private $error;

    public function __construct()
    {
        $this->p_model = new Product_Model();
        $this->validate = new Validate();
        $this->error = new Error("Product");
    }

    public function ProductErrorLog(string $messages): void
    {
        $this->error->logError($messages);
    }

    public function validateInsertProduct(array $POST): bool
    {
        $this->validate->check($POST, array(
            'name' => array(
                'required' => true,
                'max' => 50,
                'min' => 1,
            ),
            'descr' => array(
                'required' => true,
                'max' => 200,
                'min' => 1,
            ),
            'idCategory' => array(
                'required' => true,
                'max' => 4,
                'min' => 1,
                'onlyDigit' => true,
            ),
            'price' => array(
                'required' => true,
                'price' => true,
            ),
            'stock' => array(
                'required' => true,
                'max' => 7,
                'min' => 1,
                'onlyDigit' => true,
            ),
        ));
        return $this->validate->checkErrorsValidate();
    }



    public function validateUpdateProduct(array $PUT): bool
    {
        $this->validate->check($PUT, array(
            'name' => array(
                'required' => true,
                'max' => 50,
                'min' => 1,
            ),
            'descr' => array(
                'required' => true,
                'max' => 200,
                'min' => 1,
            ),
            'idCategory' => array(
                'required' => true,
                'max' => 4,
                'min' => 1,
                'onlyDigit' => true,
            ),
            'price' => array(
                'required' => true,
                'price' => true,
            ),
            'stock' => array(
                'required' => true,
                'max' => 7,
                'min' => 1,
                'onlyDigit' => true,
            ),
        ));
        return $this->validate->checkErrorsValidate();
    }


    public function validateDeleteProduct(array $DELETE): bool
    {
        $this->validate->check($DELETE, array(
            'idProduct' => array(
                'required' => true,
                'max' => 5,
                'min' => 1,
                'onlyDigit' => true,
            )
        ));
        return $this->validate->checkErrorsValidate();
    }


    public function validateShowProduct(array $GET): bool
    {
        $this->validate->check($GET, array(
            'idProduct' => array(
                'required' => true,
                'max' => 5,
                'min' => 1,
                'onlyDigit' => true,
            )
        ));
        return $this->validate->checkErrorsValidate();
    }


    public function insertProduct(string $name, string $description, int $idCategory, float $price, int $stock)
    {
        return $this->p_model->insertProduct($name, $description, $idCategory, $price, $stock);
    }

    public function updateProduct(int $idProduct, string $name, string $description, int $idCategory, float $price, int $stock)
    {
        if ($this->p_model->updateProduct($name, $description, $idProduct, $idCategory, $price, $stock)) {
            return true;
        }
        return false;
    }


    public function checkProductExist(int $idProduct): bool
    {
        return $this->p_model->checkProductExist($idProduct);
    }


    public function SoftDeleteProduct(int $idProduct)
    {
        if ($this->p_model->SoftDeleteProduct($idProduct)) {
            return true;
        }
        return false;
    }

    public function loadProductPage(array $idProduct)
    {
        $idProduct = intval($idProduct["idProduct"]);
        $this->showView2Array('product', ['products' => $this->p_model->getProductbyID($idProduct)], ['categories' => $this->p_model->listCategories()]);
    }

    public function loadProductsPage()
    {
        $this->showView2Array('products', ['categories' => $this->p_model->listCategories()], ['products' => $this->p_model->listProductsDesc()]);
    }
}
