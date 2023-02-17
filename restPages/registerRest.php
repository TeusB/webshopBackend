<?php

require_once("../vendor/autoload.php");

use controllers\User;
use main\Error;
use main\Session;
use models\shoppingCartModel;

$error = new Error("register Rest");

try {
    $user = new User();
    $shoppingCart = new shoppingCartModel();
    $session = new Session();
    switch ($value = $_SERVER["REQUEST_METHOD"]) {
        case "POST":
            if (!$user->checkEmailExist($_POST)) {
                $user->registerUser($_POST);
                $id = $user->returnLastID();
                $shoppingCart->createShoppingCart($id);
                echo json_encode([
                    "succes" => "link",
                    "link" => "index.php"
                ]);
                return;
            } else {
                echo json_encode([
                    "succes" => "error",
                    "msg" => "email already exists"
                ]);
            }
            return;
        default:
            echo json_encode([
                "succes" => "error",
                "msg" => "kon geen request vinden"
            ]);
            return;
    }
} catch (Exception $e) {
    $error->log->error($e->getMessage());
    if (!empty($user)) {
        if (!$user->validate->checkErrorsValidate()) {
            echo json_encode([
                "succes" => "error",
                "msg" => $user->validate->returnErrorValidate()
            ]);
            return;
        }
    }
    echo json_encode([
        "succes" => "error",
        "msg" => $e->getMessage()
    ]);
    return;
}
