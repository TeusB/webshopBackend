<?php

require_once("../vendor/autoload.php");

use controllers\User;
use main\Error;
use main\Session;

$error = new Error("session checkt");

$session = new Session();
if ($session->checkSessionExist()) {
    echo "ja, er is een session";
    echo "<br>";
    if ($session->checkSessionLevel(1)) {
        echo "ja, ik mag hier zijn";
    } else {
        echo "nee, ik mag hier niet zijn";
    }
} else {
    echo "nee, er is geen sessie";
}
