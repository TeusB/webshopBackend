<?php
namespace webshop;

class DB
{
    private static $instance;
    private $mysqli;

    /**
     * Returns the *Singleton* instance of this class.
     *
     * @return Singleton The *Singleton* instance.
     */
    public static function getInstance($host, $username, $password, $dbname) {
        if(!self::$instance) { // If no instance then make one
            self::$instance = new self($host, $username, $password, $dbname);
        }
        return self::$instance;
    }

    private function __construct($host, $username, $password, $dbname) {
        $this->mysqli = new \mysqli($host, $username, $password, $dbname);

        // Controle connectie
        if ($this->mysqli->connect_errno) {
            trigger_error("Connect failed: %s\n" . $this->mysqli->connect_error, E_USER_ERROR);
        }
    }

    // Get mysqli connection
    public function getConnection() {
        return $this->mysqli;
    }

    //Prevent override
    private function __clone() {}
    private function __wakeup() {}

}