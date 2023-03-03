<?php

require_once("../vendor/autoload.php");

use controllers\Product;
use controllers\User;
use main\Error;
use main\Session;

$error = new Error("login Rest");

try {
    $product = new Product();

    switch ($value = $_SERVER["REQUEST_METHOD"]) {
        case "POST":
            break;
        case "PUT":
            parse_str(file_get_contents("php://input"), $_PUT);
            echo $product->updateProduct($_PUT);
            break;

        case "GET":


            break;

        case "SOFTDELETE":
            break;

        default:
            echo json_encode([
                "succes" => "error",
                "msg" => "kon geen request vinden"
            ]);
    }
} catch (Exception $e) {
    $error->log->error($e->getMessage());
    if (!empty($product)) {
        if (!$product->validate->checkErrorsValidate()) {
            echo json_encode([
                "succes" => "error",
                "msg" => $product->validate->returnErrorValidate()
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
