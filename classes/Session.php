<?php

namespace main;

use main\Validate;

class Session
{
    private object $error;
    private object $validate;
    private array $validatedArray;

    public function __construct()
    {
        $this->error = new Error("Session");
        $this->validate = new Validate();
        session_start();
    }

    public function createConfirmEmailSession(array $post): void
    {
        $useArray = array("email");
        $this->validate($post, $useArray);
        $_SESSION["email"] = $this->validatedArray["email"];
    }

    public function createMancalaSession(array $post): void
    {
        $useArray = array("idUser");
        $this->validate($post, $useArray);
        $_SESSION["idUser"] = $this->validatedArray["idUser"];
    }

    public function checkExistsMancalaSession(): bool
    {
        if (isset($_SESSION["idUser"])) {
            return true;
        }
        return false;
    }


    public function deleteConfirmEmailSession(): void
    {
        unset($_SESSION['email']);
    }

    public function checkExistsEmailSession(): bool
    {
        if (isset($_SESSION["email"])) {
            return true;
        }
        return false;
    }


    public function clearAllSessions(): void
    {
        session_destroy();
    }

    private function validate(array $post, array $useArray): void
    {
        $validateArray = array(
            'email' => array(
                'required' => true,
            ),
            'idUser' => array(
                'required' => true,
            ),
        );
        $useArray = array_flip($useArray);

        $validationArray = $this->validate->getValidationArray($validateArray, $useArray);
        $this->validate->checkValidation($post, $validationArray);

        if ($this->validate->checkErrorsValidate()) {
            $this->validatedArray = $this->validate->getValidatedArray($post, $validationArray);
        } else {
            $this->error->maakError("validation");
        }
    }
}
