<?php

namespace CORE;

require_once(__DIR__ . "/../database.php");

use mysqli;
use Exception;

class DatabaseConnection
{

    private $database_address = DATABASE_ADDRESS;
    private $database_name = DATABASE_NAME;
    private $database_username = DATABASE_USERNAME;
    private $database_password = DATABASE_PASSWORD;
    protected $conn;

    public function __construct()
    {
        $this->startConnection();
    }

    public function __destruct()
    {
        $this->closeConnection();
    }

    protected function startConnection()
    {
        if (self::$conn == null) {
            $this->conn = new mysqli($this->database_address, $this->database_username, $this->database_password, $this->database_name);
            if ($this->conn->connect_errno) {
                throw new Exception("Tidak dapat terhubung ke database");
            }
        }
    }

    protected function closeConnection()
    {
        if (self::$conn !== null) {
            self::$conn->close();
        }
    }
}
