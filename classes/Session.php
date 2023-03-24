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

    public function createUserSession(array $post): void
    {
        $useArray = array("idUser");
        $this->validate($post, $useArray);
        $_SESSION["idUser"] = $this->validatedArray["idUser"];
    }

    public function createLevelSession(array $post): void
    {
        $useArray = array("level");
        $this->validate($post, $useArray);
        $_SESSION["level"] = $this->validatedArray["level"];
    }

    public function checkSessionExist(): bool
    {
        if (isset($_SESSION["idUser"]) && isset($_SESSION["level"])) {
            return true;
        }
        return false;
    }

    public function getUserSessionValues(){
        if (isset($_SESSION['idUser']) && isset($_SESSION['level'])){
            return ['idUser'=>$_SESSION['idUser'],'level'=>$_SESSION['level']];
        }
        return false;
    }

    public function checkSessionLevel(int $requiredLevel): bool
    {
        if ($_SESSION["level"] >= $requiredLevel) {
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
            'level' => array(
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
            $this->error->log->error($this->validate->returnErrorValidate());
            $this->error->maakError("something went wrong with the validation");
        }
    }
}
