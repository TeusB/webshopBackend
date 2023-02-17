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
    $product = new \webshop\Product();
    $sesionRequired = new \webshop\SessionRequired(2);
    $sesionRequired->validatePage();
    switch ($value = $_SERVER["REQUEST_METHOD"]) {
        case "POST":
            if ($product->validateInsertProduct($_POST)) {
                if ($idProduct = $product->insertProduct($_POST["name"], $_POST["descr"], $_POST["idCategory"], $_POST["price"], $_POST["stock"])) {
                    echo json_encode([
                        "succes" => "succes",
                        "msg" => "product is succesvol toegevoegd",
                        "data" => $idProduct,
                    ]);
                } else {
                    echo json_encode([
                        "succes" => "fout",
                        "msg" => "product is niet toegevoegd"
                    ]);
                }
            } else {
                echo json_encode([
                    "succes" => "fout",
                    "msg" => $product->validate->returnErrorValidate()
                ]);
            }

            break;

        case "PUT":
            parse_str(file_get_contents("php://input"), $_PUT);
            if ($product->validateUpdateProduct($_PUT)) {
                if ($product->updateProduct($_PUT["idProduct"], $_PUT["name"], $_PUT["descr"], $_PUT["idCategory"], $_PUT["price"], $_PUT["stock"])) {
                    echo json_encode([
                        "succes" => "succes",
                        "msg" => "product is succesvol gewijzigd"
                    ]);
                } else {
                    echo json_encode([
                        "succes" => "fout",
                        "msg" => "product is niet gewijzigd"
                    ]);
                }
            } else {
                echo json_encode([
                    "succes" => "fout",
                    "msg" => $product->validate->returnErrorValidate()
                ]);
            }
            break;

        case "GET":
            if ($product->validateShowProduct($_GET)) {
                if ($product->checkProductExist($_GET["idProduct"])) {
                    echo json_encode([
                        "succes" => "succes",
                        "data" => "index.php?c=product&m=loadProductPage&idProduct={$_GET['idProduct']}"
                    ]);
                } else {
                    echo json_encode([
                        "succes" => "fout",
                        "msg" => "kon product niet inladen",
                    ]);
                }
            } else {
                echo json_encode([
                    "succes" => "fout",
                    "msg" => $product->validate->returnErrorValidate()
                ]);
            }

            break;

        case "SOFTDELETE":
            parse_str(file_get_contents('php://input'), $_SOFTDELETE);
            if ($product->validateDeleteProduct($_SOFTDELETE)) {
                if ($product->SoftDeleteProduct($_SOFTDELETE["idProduct"])) {
                    echo json_encode([
                        "succes" => "succes",
                        "msg" => "product is gedeactiveerd"
                    ]);
                } else {
                    echo json_encode([
                        "succes" => "fout",
                        "msg" => "product is niet gedeactiveerd"
                    ]);
                }
            } else {
                echo json_encode([
                    "succes" => "fout",
                    "msg" => $product->validate->returnErrorValidate(),
                ]);
            }
            break;

        default:
            echo json_encode([
                "succes" => "fout",
                "msg" => "kon geen request vinden"
            ]);
            $product->ProductErrorLog("on geldig request gestuurd in rest page, Request: $value");
    }
} catch (Exception $e) {
    echo json_encode([
        "succes" => "fout",
        "msg" => "er is iets mis gegaan"
    ]);
    $product->ProductErrorLog($e->getMessage());
}
