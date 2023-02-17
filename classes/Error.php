<?php

namespace main;

use Exception;
use Monolog\Level;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;

class Error
{
    public object $log;
    public array $errors = array();
    public string $rootDir;

    //sets path to errorlog
    public function __construct(string $class)
    {
        $this->rootDir = realpath($_SERVER["DOCUMENT_ROOT"]);
        $path = $this->rootDir . "/error.log";
        $this->log = new Logger($class);
        $this->log->pushHandler(new StreamHandler($path, Level::Warning));
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
