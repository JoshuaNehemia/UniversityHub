<?php
namespace DATABASE;

use mysqli;

class Connection {
    private static $ADDRESS = "localhost";
    private static $USER = "root";
    private static $PWD = "";
    private static $SCHEMA = "fullstack";
    private static $conn = null;

    public static function getConnection(){
        return self::$conn;
    }

    public static function startConnection(){
        self::$conn = new mysqli(self::$ADDRESS,self::$USER,self::$PWD,self::$SCHEMA);
        if(self::$conn->connect_errno){
            //Buat Exception Page
            header("Location: ../errpr.php?kode='502'&msg='Tidak dapat terkoneksi dengan server.'");
        }
    }

    public static function closeConnection(){
        self::$conn->close();
    }
}

?>