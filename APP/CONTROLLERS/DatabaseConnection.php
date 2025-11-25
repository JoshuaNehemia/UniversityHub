<?php

namespace CONTROLLERS;

require_once(__DIR__ . "/../database.php");

use mysqli;
use Exception;

class DatabaseController
{

    private $database_address = DATABASE_ADDRESS;
    private $database_name = DATABASE_NAME;
    private $database_username = DATABASE_USERNAME;
    private $database_password = DATABASE_PASSWORD;
    private $conn;

    public function __construct()
    {
        $this->startConnection();
    }

    public function __destruct()
    {
        if (self::$conn !== null) {
            self::$conn->close();
        }
    }

    private function startConnection()
    {
        if (self::$conn == null) {
            $this->conn = new mysqli($this->database_address, $this->database_username, $this->database_password, $this->database_name);
            if ($this->conn->connect_errno) {
                throw new Exception("Tidak dapat terhubung ke database");
            }
        }
    }

    public function getConnection()
    {
        return $this->conn;
    }

}
