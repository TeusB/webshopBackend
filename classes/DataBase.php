<?php

namespace main;

use mysqli;
use Exception;
use main\Error;
use Dotenv\Dotenv;

class DataBase
{
    private object $error;

    //create connection
    public function __construct()
    {
        $this->error = new Error("database");
    }

    protected function createConnection(): object
    {
        try {
            $absolute = __DIR__ . '/../';
            $dotenv = Dotenv::createImmutable($absolute);
            $dotenv->load();
            $mysqli = new mysqli(
                $_ENV["localhost"],
                $_ENV["username"],
                $_ENV["password"],
                $_ENV["DBname"],
            );
        } catch (Exception $e) {
            $this->error->log->error($e->getMessage());
            $this->error->maakError("kon geen verbinden maken met de database");
        }
        return $mysqli;
    }
}
