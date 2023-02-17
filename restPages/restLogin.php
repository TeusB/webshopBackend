<?php

require_once("../vendor/autoload.php");

use controllers\User;
use main\Error;
use main\Session;

$error = new Error("login Rest");

try {
    $user = new User();
    $session = new Session();
    switch ($value = $_SERVER["REQUEST_METHOD"]) {
        case "POST":
            $dbPassword = $user->getPassword($_POST);
            if ($user->validatePassword($_POST, $dbPassword)) {
                $idUser = $user->getIdByEmail($_POST);
                echo json_encode([
                    "succes" => "succes",
                    "msg" => "You can login",
                ]);
            } else {
                echo json_encode([
                    "succes" => "error",
                    "msg" => "email does not checkout with the password"
                ]);
            }
            break;
        default:
            echo json_encode([
                "succes" => "error",
                "msg" => "kon geen request vinden"
            ]);
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
