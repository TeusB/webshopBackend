<?php

namespace webshop;

class User extends AbstractView
{
    private $u_model;
    public $validate;
    private $error;

    public function __construct()
    {
        session_start();
        $this->u_model = new User_Model();
        $this->error = new Error("User");
        $this->validate = new Validate();
    }

    public function UserErrorLog(string $messages): void
    {
        $this->error->logError($messages);
    }


    public function validateInputLogin(array $POST): bool
    {
        $this->validate->check($POST, array(
            'email' => array(
                'required' => true,
            ),
            'password' => array(
                'required' => true,
            ),
        ));
        return $this->validate->checkErrorsValidate();
    }


    public function validateInputRegister(array $POST): bool
    {
        $this->validate->check($POST, array(
            'email' => array(
                'required' => true,
                'email' => true,
            ),
            'password' => array(
                'required' => true,
                'max' => 254,
                'min' => 6,
                'uppercase' => true,
                'lowercase' => true,
                'symbol' => true,
                'digit' => true,
            ),
            'confirmPassword' => array(
                'required' => true,
                'matches' => $_POST["password"],
            ),
        ));
        return $this->validate->checkErrorsValidate();
    }




    public function login(string $email, string $password): bool
    {
        if (password_verify($password, $this->u_model->getPassword($email))) {
            return true;
        }
        return false;
    }


    public function checkEmailExist(string $email): bool
    {
        if ($this->u_model->getId($email)) {
            return true;
        }
        return false;
    }


    public function returnID(string $email)
    {
        if ($idUser = $this->u_model->getId($email)) {
            return $idUser;
        }
        return false;
    }

    public function returnEmail(int $idUser)
    {
        if ($email = $this->u_model->getEmail($idUser)) {
            return $email;
        }
        return false;
    }


    public function register(string $email, string $password): int
    {
        return $this->u_model->insertUser($email, password_hash($password, PASSWORD_DEFAULT));
    }

    public function getLevel(int $idUser): int
    {
        return $this->u_model->getLevel($idUser);
    }

    public function setSession(int $idUser, int $level): void
    {
        $_SESSION["idUser"] = $idUser;
        $_SESSION["level"] = $level;
    }

    public function unsetSession(): void
    {
        session_destroy();
    }

    public function getLevelLink(int $level): string
    {
        switch ($level) {
            case 1:
                $link = "index.php?c=user&m=loadShop";
                break;
            case 2:
                $link = "index.php?c=product&m=loadProductsPage";
                break;
            default:
                $this->unsetSession();
                $this->error->maakError("kon geen valid level vinden");
                break;
        }
        return $link;
    }


    public function updateUser()
    {
        if ($this->u_model->updateUser($_POST["firstName"], $_POST["lastName"], $_POST["email"], $_GET["idUser"])) {
            echo "user is succesvol gewijzigd";
        } else {
            echo "kon user niet wijzigen";
        }
    }

    public function deleteUser()
    {
        if ($this->u_model->deleteUser($_GET["idUser"])) {
            echo "user verwijderd";
        } else {
            echo "kon user niet verwijderen";
        }
    }

    public function loadLoginPage()
    {
        $emptyArray = array();
        $this->showView('login', $emptyArray);
    }

    public function loadRegisterPage()
    {
        $emptyArray = array();
        $this->showView('register', $emptyArray);
    }

    public function loadAdminPage()
    {
        $emptyArray = array();
        $this->showView('adminPannel', $emptyArray);
    }

    public function loadUserPage()
    {
        $this->showView('users', ['users' => $this->u_model->listUsers()]);
    }

    public function loadShop()
    {
        new Shop();
    }

    public function logOut()
    {
        session_unset();
        new Shop();
    }
}
