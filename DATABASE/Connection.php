<?php

namespace DATABASE;

require_once("../.ENV/database.php");

use mysqli;
use Exception;

class Connection
{
    private static $ADDRESS = DATABASE_ADDRESS;
    private static $USER = DATABASE_USER_ID;
    private static $PWD = DATABASE_USER_PASSWORD;
    private static $SCHEMA = DATABASE_SCHEMA;
    private static $conn = null;

    public static function startConnection()
    {
        try {
            self::$conn = new mysqli(self::$ADDRESS, self::$USER, self::$PWD, self::$SCHEMA);
            if (self::$conn->connect_errno) {
            throw new Exception("Tidak dapat terhubung ke database");
            }
        } catch (Exception $e) {
            throw new Exception("Tidak dapat terhubung ke database");
        }
    }

    public static function closeConnection()
    {
        self::$conn->close();
    }

    public static function getConnection()
    {
        return self::$conn;
    }

    public function __construct(){
        try {
            self::$conn = new mysqli(self::$ADDRESS, self::$USER, self::$PWD, self::$SCHEMA);
            if (self::$conn->connect_errno) {
            throw new Exception("Tidak dapat terhubung ke database");
            }
        } catch (Exception $e) {
            throw new Exception("Tidak dapat terhubung ke database");
        }
    }
    public function __destruct(){
        self::$conn->close();
    }
}
