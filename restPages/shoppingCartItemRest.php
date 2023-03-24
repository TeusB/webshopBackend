<?php

require_once("../vendor/autoload.php");

use controllers\User;
use main\Error;
use main\Session;
use controllers\shoppingCartItem;

$error = new Error("shoppingCartItem Rest");

try {
    $user             = new User();
    $shoppingCartItem = new ShoppingCartItem();
    $session          = new Session();
    switch ($value = $_SERVER["REQUEST_METHOD"]) {
        case "POST":
            if ($sessionValues = $session->getUserSessionValues()) {
                $plusMinus = $_POST['plusMinus']; // bepaalt min of plus
                unset($_POST['plusMinus']);
                $_POST['idShoppingCart'] = $sessionValues['idUser'];
                if ($items = $shoppingCartItem->getShoppingCartitem($_POST)) {
                    if ($plusMinus === 'true') {
                        $_POST['amount'] = $items[0]['amount'] + 1;
                    } else {
                        if ($items[0]['amount'] === 1) {
                            unset($_POST['amount']);
                            $shoppingCartItem->deleteCartItem($_POST);
                            echo json_encode([
                                "succes" => "succes",
                                "msg"    => 'successvol verwijderd',
                            ]);
                            return;
                        } else {
                            $_POST['amount'] = $items[0]['amount'] - 1;
                        }
                    }
                    $shoppingCartItem->increaseAmountInCart($_POST);
                    echo json_encode([
                        "succes" => "succes",
                        "msg"    => 'successvol aangepast',
                    ]);
                    return;
                } elseif ($plusMinus === 'true') {
                    $_POST['amount'] = 1;
                    $shoppingCartItem->insertInCart($_POST);
                    echo json_encode([
                        "succes" => "succes",
                        "msg"    => 'successvol toegevoegd',
                    ]);
                } else {
                    echo json_encode([
                        "succes" => "error",
                        "msg"    => 'Geen product gevonden',
                    ]);
                }
            } else {
                echo json_encode([
                    "succes" => "error",
                    "msg"    => "Je bent niet ingelogd",
                ]);
            }

            return;

        case "GET":
            if ($sessionValues = $session->getUserSessionValues()) {
                $array['idShoppingCart'] = $sessionValues['idUser'];
                if ($items = $shoppingCartItem->getFullShoppingCart($array)) {
                    $totalprice = 0;
                    foreach ($items as $item){
                        $price = $item['price'] * $item['amount'];
                        $totalprice += $price;
                    }
                    echo json_encode([
                        "succes" => "succes",
                        "msg"    => ['items' => $items,'totalPrice' => $totalprice]]);
                    return;
                } else {
                    echo json_encode([
                        "succes" => "error",
                        "msg"    => 'shoppingcart ophalen mislukt',
                    ]);
                    return;
                }
            } else {
                echo json_encode([
                    "succes" => "error",
                    "msg"    => "Je bent niet ingelogd",
                ]);
            }

        default:
            echo json_encode([
                "succes" => "error",
                "msg"    => "kon geen request vinden",
            ]);

            return;
    }
} catch
(Exception $e) {
    $error->log->error($e->getMessage());
    if ( ! empty($user)) {
        if ( ! $user->validate->checkErrorsValidate()) {
            echo json_encode([
                "succes" => "error",
                "msg"    => $user->validate->returnErrorValidate(),
            ]);

            return;
        }
    }
    echo json_encode([
        "succes" => "error",
        "msg"    => $e->getMessage(),
    ]);

    return;
}
