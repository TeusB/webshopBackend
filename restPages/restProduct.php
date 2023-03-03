<?php

require_once("../vendor/autoload.php");

use controllers\Product;
use controllers\User;
use main\Error;
use main\Session;

$error = new Error("Product Rest");

try {
    $product = new Product();

    switch ($value = $_SERVER["REQUEST_METHOD"]) {
        case "POST":
            if ($product->insertProduct($_POST)) {
                echo json_encode([
                    "succes" => "succes",
                    "msg" => "item is toegevoegd",
                ]);
            } else {
                echo json_encode([
                    "succes" => "error",
                    "msg" => "item is niet toegevoegd",
                ]);
            }
            break;
        case "PUT":
            parse_str(file_get_contents("php://input"), $_PUT);
            if ($product->updateProduct($_PUT)) {
                echo json_encode([
                    "succes" => "succes",
                    "msg" => "item is geupdate"
                ]);
            }
            break;

        case "GET":
            if ($product->checkIfProductExists($_GET)) {
                echo json_encode([
                    "succes" => "succes",
                    "link" => "product.php?idProduct=" . $_GET["idProduct"]
                ]);
                return;
            } else {
                echo json_encode([
                    "succes" => "error",
                    "msg" => "product bestaat niet"
                ]);
                return;
            }
            break;
        case "SOFTDELETE":

            return;

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
