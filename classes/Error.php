<?php

namespace webshop;

use Exception;
use DateTime;
use DateTimeZone;

class Error
{
    public $errors = array();
    private $errorPage;

    //sets path to errorlog
    public function __construct(string $errorPage)
    {
        $this->errorPage = $errorPage;
    }

    public function logError(string $error, string $file = "error.log")
    {
        $datetime = new DateTime();
        $datetime->setTimezone(new DateTimeZone('UTC'));
        $logEntry = $datetime->format('Y/m/d H:i:s') . " " . $this->errorPage . ' ' . $error . "\n";
        error_log($logEntry, 3, $file);
    }

    //create exception
    public function maakError($error)
    {
        throw new Exception($error);
    }

    //store errors
    public function maakArray($errorCatch)
    {
        $arrayErrors = array();
        array_push($arrayErrors, $errorCatch);
        return $arrayErrors;
    }
}
