<?php

namespace DATABASE;

use mysqli;

class Connection
{
    private static $ADDRESS = "localhost";
    private static $USER = "root";
    private static $PWD = "";
    private static $SCHEMA = "fullstack";
    private static $conn = null;

    public static function startConnection()
    {
        self::$conn = new mysqli(self::$ADDRESS, self::$USER, self::$PWD, self::$SCHEMA);
        if (self::$conn->connect_errno) {
            //Buat Exception Page
            header("Location: ../errpr.php?kode='502'&msg='Tidak dapat terkoneksi dengan server.'");
        }
    }

    public static function closeConnection()
    {
        self::$conn->close();
    }

    public static function getConnection(){
        return self::$conn;
    }

    // DLL

    /**
     * Melakukan select ke database 
     * @param string $sql merupakan query dengan binding parameter
     * @param array $parameters merupakan arrays of array dari parameter yang digunakan dan tipe data (s = string, i = integer, d = double, b = blob) i.e. {0=>{'type' =>'s','value'=>'John Doe'},1=>{'type' =>'s','value'=>'Jane Doe'}}
     * @return array Mengembalikan array atau bisa saja arrays of array
     */
    public static function SelectQuery(string $sql, array $parameters)
    {
        self::startConnection();
        $stmt = self::$conn->prepare($sql);

        if (!empty($parameters)) {
            $types = '';
            $values = []; 

            foreach ($parameters as $param) {
                $types = 's';
                $values[] = $param['value'];
            }

            $stmt->bind_param($types, ...$values);
        }

        $stmt->execute();
        $result = $stmt->get_result();
        $arr = [];

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $arr[] = $row;
            }
        }

        $stmt->close();
        self::closeConnection();

        return $arr;
    }
}
