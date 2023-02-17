<?php

namespace webshop;

use mysql_xdevapi\Exception;

//require_once '../classes/AbstractView.php';
//require_once '../models/Cart_Model.php';
session_start();

class Cart extends AbstractView
{
    private $c_model;

    public function __construct()
    {
        $this->c_model = new Cart_Model();
    }

    public function PlusMinShoppingCart()
    {
        $idProduct = intval($_POST['productID']);
        $add       = filter_var($_POST['add'], FILTER_VALIDATE_BOOLEAN);

        $idShoppingCart = $this->c_model->getShoppingCartID(intval($_POST['idUser']));
        if ($idShoppingCart) {
            if ($this->c_model->checkItemExistInCart($idShoppingCart[0]['idShoppingCart'], $idProduct)) {
                $amount = $this->c_model->returnAmount($idShoppingCart[0]['idShoppingCart'], $idProduct);
                if ($add) {
                    $amount = $amount[0]['amount'] + 1;
                } else {
                    if ($amount[0]['amount'] === 1) { // als het min is een amount = 1 dan wordt product verwijderd.
                        if ($this->c_model->delItem($idProduct, $idShoppingCart[0]['idShoppingCart'])) {
                            echo json_encode(true);
                            return;
                        }
                    } else {
                        $amount = $amount[0]['amount'] - 1;
                    }
                }
                if ($this->c_model->addAmountToCart($idShoppingCart[0]['idShoppingCart'], $idProduct, $amount)) {
                    echo json_encode(true);
                } else {
                    echo json_encode(false);
                }
            } else {
                echo json_encode(true);
            }
        }
    }

    public function retrieveShoppingCart($data)
    {
        $idUser         = $data['idUser'];
        $idShoppingCart = $this->c_model->getShoppingCartID($idUser);
        if ($idShoppingCart != false) {
            $idShoppingCart   = $idShoppingCart[0]['idShoppingCart'];
            $shoppingCartData = $this->c_model->getShoppingCartData($idShoppingCart);
            if ($shoppingCartData && ! empty($shoppingCartData)) {
                $result['status'] = 'success';
                $result['data']   = $shoppingCartData;
                echo json_encode($result);
            } else {
                $result['status']      = 'false';
                $result['foutmelding'] = 'getShoppingCartData gefaald of leeg';
                echo json_encode($result);
            }
        }
    }

    public function cartEntry()
    {
        $idUser         = intval($_POST['idUser']);
        $productID      = intval($_POST['productID']);
        $idShoppingCart = $this->c_model->getShoppingCartID($idUser);
        if ($idShoppingCart) {
            if ($this->c_model->checkItemExistInCart($idShoppingCart[0]['idShoppingCart'], $productID)) {
                $amount = $this->c_model->returnAmount($idShoppingCart[0]['idShoppingCart'], $productID);
                $amount = $amount[0]['amount'] + 1;
                if ($this->c_model->addAmountToCart($idShoppingCart[0]['idShoppingCart'], $productID, $amount)) {
                    echo json_encode(true);
                } else {
                    $result['foutmelding'] = 'AddAmountToCart failed';
                    echo json_encode($result);
                }
            } else {
                if ($this->c_model->addItemToCart($productID, $idShoppingCart[0]['idShoppingCart'], 1)) {
                    echo json_encode(true);
                } else {
                    $result['foutmelding'] = 'addItemToCart failed';
                    echo json_encode($result);
                }
            }
        } else {
            $result['foutmelding'] = 'getShoppingCartID failed';
            echo json_encode($result);
        }
    }

    public function removeFromCart()
    {
        $idShoppingCart = $this->c_model->getShoppingCartID(intval($_POST['idUser']));
        if ($idShoppingCart) {
            if ($this->c_model->checkItemExistInCart(
                $idShoppingCart[0]['idShoppingCart'],
                intval($_POST['productID'])
            )) {
                $delete = $this->c_model->delItem(intval($_POST['productID']), $idShoppingCart[0]['idShoppingCart']);
                if ($delete) {
                    echo json_encode(true);
                }
            } else {
                echo json_encode(false);
            }
        } else {
            echo json_encode(false);
        }
    }
}
