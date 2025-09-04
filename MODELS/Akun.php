<?php

namespace MODELS;

require_once __DIR__ . '/../DATABASE/Connection.php';

use DATABASE\Connection;

class Akun
{
    private $username;
    private $nama;
    private $password;
    private $jenis; //Enum value Admin, Dosen, atau Mahasiswa

    /**
     * Constructor untuk Class Akun
     *
     * @param string $username Menyimpan nama akun
     * @param string $nama Menyimpan nama pengguna
     * @param string $password Menyimpan password akun
     * @param string $jenis Menyimpan jenis akun
     */
    public function __construct($username, $nama, $password, $jenis)
    {
        $this->setUsername($username);
        $this->setNama($nama);
        $this->setPassword($password);
        $this->setJenis($jenis);
    }

    // --- Getters ---
    /**
     * Memberikan nilai username
     * @return string 
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Memberikan jenis akun Mahasiswa, Dosen atau Admin
     * @return string 
     */
    public function getJenis()
    {
        return $this->jenis;
    }
    /**
     * Memberikan nama pengguna
     * @return string 
     */
    public function getNama()
    {
        return $this->nama;
    }

    // --- Setters ---

    /**
     * Menyimpan nilali username kedalam class
     * @param string $username
     */
    public function setUsername(string $username)
    {
        if ($username == "") $username = "" . random_bytes(10) . "_" . date("Y-m-d-H-i-s");
        $this->username = $username;
    }

    /**
     * Menyimpan nilai password kedalam class
     * @param string $password
     */
    public function setPassword(string $password)
    {
        if ($password == "") $this->password = "" . random_bytes(10);
        $this->password = $password;
    }

    /**
     * Menyimpan nilai nama kedalam class
     * @param string $nama
     */
    public function setNama(string $nama)
    {
        if ($nama == "") $this->nama = "John Doe";
        $this->nama = $nama;
    }

    /**
     * Menyimpan nilai jenis kedalam class
     * @param string $jenis
     */
    public function setJenis($jenis)
    {
        if ($jenis == "") $this->jenis = "MAHASISWA";
        $this->jenis = $jenis;
    }


    // FUNCTION =======================================================================================================
    /**
     * Melakukan log in dengan mengecek username dan password ke Database
     * @param string $username username akun
     * @param string $password password akun
     * @return array array dari akun tersebut;
     */
    public static function LogIn(string $username, string $password)
    {
        $sql = "SELECT * FROM `akun` WHERE `username` = ? AND `password` = ?;";

        Connection::startConnection();
        $stmt = Connection::getCOnnection()->prepare($sql);
        $stmt->bind_param('ss', $username,$password);

        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
        }

        $stmt->close();
        Connection::closeConnection();
        return $row;
    }
}
