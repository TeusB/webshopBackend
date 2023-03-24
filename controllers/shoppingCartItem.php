<?php

namespace controllers;

use main\Error;
use models\shoppingCartItemModel;

class shoppingCartItem extends shoppingCartItemModel
{

    public function __construct()
    {
        parent::__construct();
        $this->error = new Error("shoppingCartItem Controller");
    }

    public function getShoppingCartitem($post): mixed
    {
        $useArray = array("idProduct", 'idShoppingCart');
        $this->validateDataGet($post, $useArray);
        $this->get(
            ['idProduct', 'idShoppingCart', 'amount'],
            ['idShoppingCart' => $this->validatedArray['idShoppingCart'],'idProduct' => $this->validatedArray['idProduct']]
        );
        if ($this->checkFetch()) {
            return $this->fetch();
        }
        return false;
    }

    public function getFullShoppingCart($array): mixed
    {
        $useArray = array('idShoppingCart');
        $this->validateDataGet($array, $useArray);
        $this->getHandler("select shoppingcartitem.idProduct,shoppingcartitem.amount, product.name, product.descr, product.price, product.imageURL from shoppingcartitem RIGHT join product on shoppingcartitem.idProduct = product.idProduct WHERE shoppingcartitem.idShoppingCart = ? and product.active = 1",[$this->validatedArray['idShoppingCart']]);
        if ($this->checkFetch()) {
            return $this->fetch();
        }
        return false;
    }

    public function insertInCart($post): bool
    {
        $useArray = array("idProduct", 'idShoppingCart', 'amount');
        $this->validateDataInput($post, $useArray);
        $this->insert(array(
            "idProduct" => $this->validatedArray["idProduct"],
            "idShoppingCart" => $this->validatedArray["idShoppingCart"],
            "amount" => $this->validatedArray['amount'],
        ));

        return true;
    }

    public function increaseAmountInCart($post): bool
    {
        $useArray = array("idProduct", 'idShoppingCart', 'amount');
        $this->validateDataInput($post, $useArray);
        $this->update(array(
            "amount" => $this->validatedArray['amount'],
        ), array(
            "idShoppingCart" => $this->validatedArray["idShoppingCart"],
            'idProduct'      => $this->validatedArray['idProduct'],
        ));

        return true;
    }

    function deleteCartItem($post)
    {
        $useArray = array("idShoppingCart", 'idProduct');
        $this->validateDataInput($post, $useArray);
        $this->delete(array(
            "idShoppingCart" => $this->validatedArray["idShoppingCart"],
            'idProduct'      => $this->validatedArray['idProduct'],
        ));
    }
}