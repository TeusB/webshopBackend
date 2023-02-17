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
    // $class = new \webshop\Class();
    switch ($value = $_SERVER["REQUEST_METHOD"]) {
        case "POST":
            if ($class->validateInsertProduct($_POST)) {
            } else {

                echo json_encode([
                    "succes" => "fout",
                    "msg" => $class->validate->returnErrorValidate()
                ]);
            }

            break;

        case "PUT":
            parse_str(file_get_contents("php://input"), $_PUT);
            if ($class->validateUpdateProduct($_PUT)) {

            } else {
                echo json_encode([
                    "succes" => "fout",
                    "msg" => $class->validate->returnErrorValidate()
                ]);
            }
            break;

        case "GET":
            if ($class->validateShowProduct($_GET)) {

            } else {
                echo json_encode([
                    "succes" => "fout",
                    "msg" => $class->validate->returnErrorValidate()
                ]);
            }

            break;

        case "DELETE":
            parse_str(file_get_contents('php://input'), $_DELETE);
            if ($class->validateDeleteProduct($_DELETE)) {

            } else {
                echo json_encode([
                    "succes" => "fout",
                    "msg" => $class->validate->returnErrorValidate(),
                ]);
            }
            break;

        default:
            echo json_encode([
                "succes" => "fout",
                "msg" => "kon geen request vinden"
            ]);
            $class->ClassErrorLog("on geldig request gestuurd in rest page, Request: $value");
    }
} catch (Exception $e) {
    echo json_encode([
        "succes" => "fout",
        "msg" => "er is iets mis gegaan"
    ]);
    $class->ClassErrorLog($e->getMessage());
}
