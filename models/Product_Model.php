<?php

namespace webshop;


class Product_Model extends Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function updateProduct(string $name, string $description, int $idProduct, int $idCategory, float $price, int $stock)
    {
        $values = [$name, $description, $idCategory, $price, $stock, $idProduct];
        return $this->update('UPDATE product SET name=?, descr =?, idCategory = ?, price = ?, stock=? WHERE idProduct = ?', $values, "ssidii");
    }

    public function insertProduct(string $name, string $description, int $idCategory, float $price, int $stock)
    {
        $values = [$name, $description, $idCategory, $price, $stock];
        return $this->insertReturnID('INSERT INTO product (name, descr, idCategory, price, stock) VALUES (?, ?, ?, ?, ?)', $values, "ssidi");
    }

    public function getProductbyID(int $idProduct)
    {
        return $this->select("SELECT * FROM product WHERE idProduct = ?", [$idProduct], "i");
    }

    public function checkProductExist(int $idProduct): bool
    {
        return $this->selectBool("SELECT idProduct FROM product WHERE idProduct = ?", [$idProduct], "i");
    }

    public function SoftDeleteProduct(int $idProduct)
    {
        return $this->update("UPDATE product SET active=? WHERE idProduct = ?", [0, $idProduct], "ii");
    }

    public function listCategories()
    {
        return $this->selectEmpty('SELECT idCategory, name FROM Category');
    }


    public function listProductsDesc()
    {
//        return $this->selectEmpty('SELECT Product.idProduct,Product.name,Product.descr,Product.price,Product.stock,Category.name AS category
//        FROM Product INNER JOIN Category ON catagory = idCategory');

        return $this->selectEmpty('SELECT Product.idProduct,Product.name,Product.descr,Product.price,Product.stock,category.name AS category
        FROM Product LEFT JOIN category ON product.idCategory = category.idCategory WHERE product.active = 1 ORDER BY Product.idProduct DESC;');
    }

    public function listProducts()
    {
//        return $this->selectEmpty('select Product.idProduct,Product.name,Product.descr,Product.price,Product.stock,Category.name as category from Product inner join Category on catagory = idCategory;');

        return $this->selectEmpty('SELECT Product.idProduct,Product.name,Product.descr,Product.price,Product.stock,category.name
        AS category FROM Product LEFT JOIN category ON category.idCategory = product.idCategory WHERE product.active = 1;');
    }
}
