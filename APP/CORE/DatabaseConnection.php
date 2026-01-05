<?php

namespace CORE;
#region REQUIRE
require_once(__DIR__ . "/../database.php");
#endregion

#region USE
use mysqli;
use Exception;
#endregion

class DatabaseConnection
{
    #region FIELDS
    private $conn;

    public function __construct()
    {
    }

    public function __destruct()
    {
    }

    public function connect()
    {
        if ($this->conn == null) {
            $this->conn = new mysqli(
                DATABASE_ADDRESS,
                DATABASE_USERNAME,
                DATABASE_PASSWORD,
                DATABASE_NAME
            );

            if ($this->conn->connect_errno) {
                throw new Exception("Couldn't connect to database: " . $this->conn->connect_error);
            }
        }
        return $this->conn;
    }

    public function close()
    {
        if ($this->conn !== null) {
            $this->conn->close();
            $this->conn = null;
        }
    }
}