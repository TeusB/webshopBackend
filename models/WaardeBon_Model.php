<?php

namespace webshop;


class WaardeBon_Model extends Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function updateWaardeBon(int $idWaardeBon, string $name, int $percentageOff, int $minOrderValue, string $code, string $expires)
    {
        $values = [$name, $percentageOff, $minOrderValue, $code, $expires, $idWaardeBon];
        return $this->update('UPDATE waardeBon SET name=?, percentageOff =?, minOrderValue = ?, code = ?, expires=? WHERE idProduct = ?', $values, "ssidii");
    }

    public function insertWaardeBon(string $name, string $description, int $idCategory, float $price, int $stock)
    {
        $values = [$name, $description, $idCategory, $price, $stock];
        return $this->insertReturnID('INSERT INTO waardeBon (name, descr, idCategory, price, stock) VALUES (?, ?, ?, ?, ?)', $values, "ssidi");
    }

    public function getWaardeBonByID(int $idWaardeBon)
    {
        return $this->select("SELECT * FROM waardeBon WHERE idWaardeBon = ?", [$idWaardeBon], "i");
    }

    public function checkWaardeBonExist(int $idWaardeBon): bool
    {
        return $this->selectBool("SELECT idWaardeBon FROM waardeBon WHERE idWaardeBon = ?", [$idWaardeBon], "i");
    }

    public function SoftDeleteWaardeBon(int $idWaardeBon)
    {
        return $this->update("UPDATE idWaardeBon SET active=? WHERE idWaardeBon = ?", [0, $idWaardeBon], "ii");
    }

    public function listWaardeBonnen()
    {
        return $this->selectEmpty('SELECT idWaardeBon, name FROM WaardeBon');
    }

    public function listWaardeBonnenDesc()
    {
        return $this->selectEmpty('SELECT Product.idProduct,Product.name,Product.descr,Product.price,Product.stock,category.name AS category
        FROM Product LEFT JOIN category ON product.idCategory = category.idCategory WHERE product.active = 1
        ORDER BY Product.idProduct DESC;');
    }
}
