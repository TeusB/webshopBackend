<?php

namespace controllers;

use main\Error;
use models\UserModel;
use main\Mailer;

class User extends UserModel
{
    private object $error;

    public function __construct()
    {
        parent::__construct();
        $this->error = new Error("User Controller");
    }

    public function getNameById(array $post): string|false
    {
        $useArray = array("idUser");
        $this->validateDataGet($post, $useArray);
        $this->get(array("firstName"), array("idUser" => $this->validatedArray["idUser"]));
        if ($this->checkFetch()) {
            return $this->fetchRow()["firstName"];
        }
        return false;
    }

    public function registerUser(array $post): void
    {
        $useArray = array("email", "password", "confirmPassword");
        $this->validateDataInput($post, $useArray);
        $this->validatedArray["password"] = $this->hashPassword($this->validatedArray["password"]);
        $this->insert(array(
            "email" => $this->validatedArray["email"],
            "password" => $this->validatedArray["password"],
        ));
    }

    public function checkEmailExist(array $post): bool
    {
        $useArray = array("email");
        $this->validateDataGet($post, $useArray);
        return $this->checkExist(array("email" => $this->validatedArray["email"]));
    }

    public function confirmAccount(array $post): void
    {
        $useArray = array("idUser");
        $this->validateDataInput($post, $useArray);
        $this->update(array(
            "accountStatus" => 1,
        ), array(
            "idUser" => $this->validatedArray["idUser"],
        ));
    }

    public function updatePic(array $put)
    {
        $img_name = $_FILES['change-photo']['name'];
        $img_size = $_FILES['change-photo']['size'];
        $tmp_name = $_FILES['change-photo']['tmp_name'];
        $error = $_FILES['change-photo']['error'];

        if ($error === 0) {
            if ($img_size > 1250000) {
                $em = "Sorry, your file is too large.";
                header("Location: index.php?error=$em");
            } else {
                $img_ex = pathinfo($img_name, PATHINFO_EXTENSION);
                $img_ex_lc = strtolower($img_ex);

                $allowed_exs = array("jpg", "jpeg", "png");

                if (in_array($img_ex_lc, $allowed_exs)) {
                    $new_img_name = uniqid("IMG-", true) . '.' . $img_ex_lc;
                    $img_upload_path = '../../uploads/' . $new_img_name;
                    move_uploaded_file($tmp_name, $img_upload_path);

                    // Insert into Database
                } else {
                    echo json_encode([
                        "succes" => "error",
                        "msg" => "only upload pics: jpg, jpeg, png"
                    ]);
                    return;
                }
            }
        }
    }

    public function getIdByEmail(array $post): int
    {
        $useArray = array("email");
        $this->validateDataGet($post, $useArray);
        $this->get(array("idUser"), array("email" => $this->validatedArray["email"]));
        if ($this->checkFetch()) {
            return $this->fetchRow()["idUser"];
        } else {
            $this->error->maakError("email does not exist");
        }
    }

    public function checkStatus(array $post): bool
    {
        $useArray = array("email");
        $this->validateDataGet($post, $useArray);
        $this->get(array("accountStatus"), array("email" => $this->validatedArray["email"]));
        if ($this->checkFetch()) {
            if ($this->fetchRow()["accountStatus"] === 0) {
                return true;
            }
            return false;
        } else {
            $this->error->maakError("email does not exist");
        }
    }

    public function updatePassword(array $post)
    {
        $useArray = array("idUser", "password", "confirmPassword");
        $this->validateDataInput($post, $useArray);
        $this->validatedArray["password"] = $this->hashPassword($this->validatedArray["password"]);
        $this->update(array(
            "password" =>  $this->validatedArray["password"],
        ), array(
            "idUser" => $this->validatedArray["idUser"],
        ));
    }


    public function getEmailById(array $post)
    {
        $useArray = array("idUser");
        $this->validateDataGet($post, $useArray);
        $this->get(array("email"), array("idUser" => $this->validatedArray["idUser"]));
        if ($this->checkFetch()) {
            return $this->fetchRow()["email"];
        }
        return false;
    }

    public function checkVerified(array $post): bool
    {
        $postArray = array("idUser");
        $this->validateDataInput($post, $postArray);
        return $this->checkExist(array("idUser" => $this->validatedArray["idUser"], "accountStatus" => 0));
    }

    public function getPassword(array $post): string
    {
        $useArray = array("email");
        $this->validateDataGet($post, $useArray);
        $this->get(array("password"), array("email" => $this->validatedArray["email"]));
        if ($this->checkFetch()) {
            return $this->fetchRow()["password"];
        } else {
            $this->error->maakError("Email is not registered");
        }
    }

    public function getLevelById(array $post): int
    {
        $useArray = array("idUser");
        $this->validateDataGet($post, $useArray);
        $this->get(array("level"), array("idUser" => $this->validatedArray["idUser"]));
        if ($this->checkFetch()) {
            return $this->fetchRow()["level"];
        } else {
            $this->error->maakError("account heeft geen levels");
        }
    }


    public function sendConfirmEmail(array $post, string $token)
    {
        $useArray = array("email");
        $this->validateDataInput($post, $useArray);
        $this->get(array("firstName", "lastName"), array("email" => $this->validatedArray["email"]));
        if ($this->checkFetch()) {
            $fetchedArray = $this->fetchRow();
            ob_start();
            include("../Emails/confirmEmail.php");
            $email = ob_get_contents();
            ob_end_clean();
            new Mailer($this->validatedArray["email"], $fetchedArray["firstName"] . " " . $fetchedArray["lastName"], "confirm email", $email);
        }
    }

    public function sendResetPasswordEmail(array $post, string $token)
    {
        $useArray = array("email");
        $this->validateDataInput($post, $useArray);
        $this->get(array("firstName", "lastName"), array("email" => $this->validatedArray["email"]));
        if ($this->checkFetch()) {
            $fetchedArray = $this->fetchRow();
            ob_start();
            include("../Emails/forgotPasswordEmail.php");
            $email = ob_get_contents();
            ob_end_clean();
            new Mailer($this->validatedArray["email"], $fetchedArray["firstName"] . " " . $fetchedArray["lastName"], "reset Password", $email);
        }
    }


    private function hashPassword(string $password): string
    {
        return password_hash($password, PASSWORD_DEFAULT);
    }

    public function validatePassword(array $post, string $storedPassword): bool
    {
        $useArray = array("password");
        $this->validateDataGet($post, $useArray);
        return password_verify($this->validatedArray["password"], $storedPassword);
    }
}
