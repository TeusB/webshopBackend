<?php

namespace webshop;

use Exception;


require_once 'Psr4AutoloaderClass.php';
$loader = new \PSR\Psr4AutoloaderClass();
$loader->register();
$loader->addNamespace('webshop', __DIR__ . '/classes');
$loader->addNamespace('webshop', __DIR__ . '/models');
$loader->addNamespace('webshop', __DIR__ . '/controllers');

try {
    $user = new \webshop\User();
    switch ($value = $_SERVER["REQUEST_METHOD"]) {
        case "POST":
            if ($user->validateInputRegister($_POST)) {
                if (!$user->checkEmailExist($_POST["email"])) {
                    $idUser = $user->register($_POST["email"], $_POST["password"]);
                    $level = $user->getLevel($idUser);
                    $user->setSession($idUser, $level);
                    $cart_model = new \webshop\Cart_Model();
                    $cart_model->createShoppingCart($idUser);
                    echo json_encode([
                        "succes" => "succes",
                        "data" => $user->getLevelLink($level)
                    ]);
                } else {
                    echo json_encode([
                        "succes" => "fout",
                        "msg" => "email is al in gebruik"
                    ]);
                }
            } else {
                echo json_encode([
                    "succes" => "fout",
                    "msg" => $user->validate->returnErrorValidate()
                ]);
            }

            break;

        default:
            echo json_encode([
                "succes" => "fout",
                "msg" => "kon geen request vinden"
            ]);
            $user->UserErrorLog("on geldig request gestuurd in rest page, Request: $value");
    }
} catch (Exception $e) {
    echo json_encode([
        "succes" => "fout",
        "msg" => "er is iets mis gegaan"
    ]);
    $user->UserErrorLog($e->getMessage());
}
