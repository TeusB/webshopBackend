<?php

namespace webshop;

class SessionRequired
{
    private $error;
    private $levelUser;
    private $levelRequired;

    private $idUser;
    private $url;

    //required for pages that need session level protection
    public function __construct(int $levelRequired)
    {
        session_start();
        $this->error = new Error("Session Required");
        $this->url = $_SERVER['REQUEST_URI'];
        $this->levelRequired = $levelRequired;
    }

    public function validatePage()
    {
        $this->validateSessionLevel();
        $this->validateSessionUser();
        $this->checkAllowed();
    }

    // checks with a session if user is allowed on the page
    public function checkAllowed()
    {
        switch ($this->levelRequired) {
            case 1:
                if ($this->levelUser < 1) {
                    $this->error->maakError("Log in voordat je gebruik maakt van het programma");
                }
                break;
            case 2:
                if ($this->levelUser < 2) {
                    $this->error->logError("account with id $this->idUser tried getting into $this->url (level 2 page)");
                    $this->error->maakError("Alleen level 2 en hoger toegestaan");
                }
                break;
            default:
                $this->error->maakError("uknown level ($this->levelRequired) required for this page");
        }
    }

    //unsets the sessions
    private function unsetSessions()
    {
        session_destroy();
    }


    public function checkSessionExist(string $value): bool
    {
        if (isset($_SESSION[$value])) {
            return true;
        }
        return false;
    }

    //validates level
    public function validateSessionLevel()
    {
        if (isset($_SESSION["level"])) {
            if (is_int($_SESSION["level"])) {
                $this->levelUser = $_SESSION["level"];
            } else {
                $this->unsetSessions();
                $this->error->maakError("Je session level heeft geen cijferig getal");
            }
        } else {
            $this->unsetSessions();
            $this->error->maakError("Er is geen sessie gevonden");
        }
    }

    //validates user
    public function validateSessionUser()
    {
        if (isset($_SESSION["idUser"])) {
            if (is_int($_SESSION["idUser"])) {
                $this->idUser = $_SESSION["idUser"];
            } else {
                $this->unsetSessions();
                $this->error->maakError("je sessie id heeft geen cijferig getal");
            }
        } else {
            $this->unsetSessions();
            $this->error->maakError("er is geen sessie gevonden");
        }
    }
}
