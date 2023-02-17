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
            if ($user->validateInputLogin($_POST)) {
                if ($user->checkEmailExist($_POST["email"])) {
                    if ($user->login($_POST["email"], $_POST["password"])) {
                        $idUser = $user->returnID($_POST["email"]);
                        $level = $user->getLevel($idUser);
                        $user->setSession($idUser, $level);;
                        echo json_encode([
                            "succes" => "succes",
                            "data" => $user->getLevelLink($level)
                        ]);
                    } else {
                        $user->UserErrorLog($_POST["email"] . " failed loggin in");
                        echo json_encode([
                            "succes" => "fout",
                            "msg" => "password matched niet met email"
                        ]);
                    }
                } else {
                    echo json_encode([
                        "succes" => "fout",
                        "msg" => "email niet gevonden"
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
