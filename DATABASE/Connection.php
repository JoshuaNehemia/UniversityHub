<?php

namespace DATABASE;

use mysqli;
use Exception;

class Connection
{
    private static $ADDRESS = "localhost";
    private static $USER = "root";
    private static $PWD = "";
    private static $SCHEMA = "fullstack";
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
}
