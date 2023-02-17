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
    $waardeBon = new \webshop\Waardebon();
    $sesionRequired = new \webshop\SessionRequired(2);
    $sesionRequired->validatePage();
    switch ($value = $_SERVER["REQUEST_METHOD"]) {
        case "POST":
            if ($idWaardeBon->validateInsertWaardebon($_POST)) {
                if ($idWaardeBon = $idWaardeBon->insertWaardeBon($_POST["idWaardeBon"], $_POST["name"], $_POST["percentageOff"], $_POST["minOrderValue"], $_POST["expires"], $_POST["code"])) {
                    echo json_encode([
                        "succes" => "succes",
                        "msg" => "waardeBon is succesvol toegevoegd",
                        "data" => $idWaardeBon,
                    ]);
                } else {
                    echo json_encode([
                        "succes" => "fout",
                        "msg" => "WaardeBon is niet toegevoegd"
                    ]);
                }
            } else {
                echo json_encode([
                    "succes" => "fout",
                    "msg" => $waardeBon->validate->returnErrorValidate()
                ]);
            }

            break;

        case "PUT":
            parse_str(file_get_contents("php://input"), $_PUT);
            if ($waardeBon->validateUpdateWaardeBon($_PUT)) {
                if ($waardeBon->updateWaardeBon($_PUT["idWaardeBon"], $_PUT["name"], $_PUT["percentageOff"], $_PUT["minOrderValue"], $_PUT["expires"], $_PUT["active"])) {
                    echo json_encode([
                        "succes" => "succes",
                        "msg" => "WaardeBon is succesvol gewijzigd"
                    ]);
                } else {
                    echo json_encode([
                        "succes" => "fout",
                        "msg" => "Waardebon is niet gewijzigd"
                    ]);
                }
            } else {
                echo json_encode([
                    "succes" => "fout",
                    "msg" => $waardeBon->validate->returnErrorValidate()
                ]);
            }
            break;

        case "GET":
            if ($waardeBon->validateShowWaardeBon($_GET)) {
                if ($waardeBon->checkWaardeBonExist($_GET["idWaardeBon"])) {
                    echo json_encode([
                        "succes" => "succes",
                        "data" => "index.php?c=Waardebon&m=loadWaardeBonPage&idWaardeBon={$_GET['idWaardeBon']}"
                    ]);
                } else {
                    echo json_encode([
                        "succes" => "fout",
                        "msg" => "kon waardeBon niet inladen",
                    ]);
                }
            } else {
                echo json_encode([
                    "succes" => "fout",
                    "msg" => $waardeBon->validate->returnErrorValidate()
                ]);
            }
            break;

        case "SOFTDELETE":
            parse_str(file_get_contents('php://input'), $_SOFTDELETE);
            if ($product->validateDeleteWaardeBon($_SOFTDELETE)) {
                if ($product->validateDeleteWaardeBon($_SOFTDELETE["idWaardeBon"])) {
                    echo json_encode([
                        "succes" => "succes",
                        "msg" => "WaardeBon is gedeactiveerd"
                    ]);
                } else {
                    echo json_encode([
                        "succes" => "fout",
                        "msg" => "WaardeBon is niet gedeactiveerd"
                    ]);
                }
            } else {
                echo json_encode([
                    "succes" => "fout",
                    "msg" => $waardeBon->validate->returnErrorValidate(),
                ]);
            }
            break;

        default:
            echo json_encode([
                "succes" => "fout",
                "msg" => "kon geen request vinden"
            ]);
            $waardeBon->WaardeBonErrorLog("on geldig request gestuurd in rest page, Request: $value");
    }
} catch (Exception $e) {
    echo json_encode([
        "succes" => "fout",
        "msg" => "er is iets mis gegaan"
    ]);
    $waardeBon->WaardeBonErrorLog($e->getMessage());
}
