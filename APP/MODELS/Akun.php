<?php

namespace MODELS;

require_once(__DIR__ .'/../../DATABASE/Connection.php');

use DATABASE\Connection;
use Exception;

class Akun
{
    private $username;
    private $nama;
    private $jenis; //Enum value Admin, Dosen, atau Mahasiswa

    /**
     * Constructor untuk Class Akun
     *
     * @param string $username Menyimpan nama akun
     * @param string $nama Menyimpan nama pengguna
     * @param string $password Menyimpan password akun
     * @param string $jenis Menyimpan jenis akun
     */
    public function __construct(string $username, string $nama, string $jenis)
    {
        $this->setUsername($username);
        $this->setNama($nama);
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
        if ($username == "") throw new Exception("Username tidak boleh kosong", 1);
        $this->username = $username;
    }

    /**
     * Menyimpan nilai nama kedalam class
     * @param string $nama
     */
    public function setNama(string $nama)
    {
        if ($nama == "") throw new Exception("Nama tidak boleh kosong", 1);
        $this->nama = $nama;
    }

    /**
     * Menyimpan nilai jenis kedalam class
     * @param string $jenis
     */
    public function setJenis(string $jenis)
    {
        if ($jenis == "") throw new Exception("Jenis tidak boleh kosong");
        $this->jenis = $jenis;
    }


    // FUNCTION =======================================================================================================
    // Ini lama 
    public static function LogIn_Akun(string $username, string $password)
    {
        $sql = "SELECT `username`,`nrp_mahasiswa`,`npk_dosen`,`isadmin` FROM `akun` WHERE `username` = ? AND `password` = ?;";
        try {
            Connection::startConnection();
            $stmt = Connection::getCOnnection()->prepare($sql);

            if ($stmt === false) {
                throw new Exception("Gagal request ke database");
            }

            $stmt->bind_param('ss', $username, $password);
            $stmt->execute();

            $result = $stmt->get_result();
            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
            } else {
                throw new Exception("Username atau password salah");
            }

            $stmt->close();

            $jenis = '';
            if ($row['isadmin'] == '1') {
                $jenis = 'ADMIN';
            } else if (!empty($row['npk_dosen'])) {
                $jenis = 'DOSEN';
            } else if (!empty($row['nrp_mahasiswa'])) {
                $jenis = 'MAHASISWA';
            } else {
                throw new Exception("Akun tidak memiliki data yang sesuai");
            }

            return new Akun($row['username'], $jenis, $jenis);
        } catch (Exception $e) {
            throw $e;
        } finally {
            if (Connection::getConnection() !== null) {
                Connection::closeConnection();
            }
        }
    }

    //baru
    public static function logIn(string $username, string $password)
    {
        $sql = "SELECT `username`,`nrp_mahasiswa`,`npk_dosen`,`isadmin` FROM `akun` WHERE `username` = ? AND `password` = ?;";
        try {
            Connection::startConnection();
            $stmt = Connection::getCOnnection()->prepare($sql);

            if ($stmt === false) {
                throw new Exception("Gagal request ke database");
            }

            $stmt->bind_param('ss', $username, $password);
            $stmt->execute();

            $result = $stmt->get_result();
            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
            } else {
                throw new Exception("Username atau password salah");
            }

            $stmt->close();

            $jenis = '';
            if ($row['isadmin'] == '1') {
                $jenis = 'ADMIN';
            } else {
                throw new Exception("Akun tidak memiliki data yang sesuai");
            }

            return new Akun($row['username'], $jenis, $jenis);
        } catch (Exception $e) {
            throw $e;
        } finally {
            if (Connection::getConnection() !== null) {
                Connection::closeConnection();
            }
        }
    }

    public static function getAccountRole($username)
    {
        $sql = "SELECT `nrp_mahasiswa`,`npk_dosen`,`isadmin` FROM `akun` WHERE `username` = ?;";
        try {
            Connection::startConnection();
            $stmt = Connection::getCOnnection()->prepare($sql);

            if ($stmt === false) {
                throw new Exception("Gagal request ke database");
            }

            $stmt->bind_param('s', $username);
            $stmt->execute();

            $result = $stmt->get_result();
            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
            } else {
                throw new Exception("Username atau password salah");
            }

            $stmt->close();

            $jenis = '';
            if ($row['isadmin'] == '1') {
                $jenis = 'ADMIN';
            } else if (!empty($row['npk_dosen'])) {
                $jenis = 'DOSEN';
            } else if (!empty($row['nrp_mahasiswa'])) {
                $jenis = 'MAHASISWA';
            } else {
                throw new Exception("Akun tidak memiliki data yang sesuai");
            }

            return $jenis;
        } catch (Exception $e) {
            throw $e;
        } finally {
            if (Connection::getConnection() !== null) {
                Connection::closeConnection();
            }
        }
    }


    public function CreateInDatabase(string $nrp = "", string $npk = "", string $password, int $isAdmin = 0)
    {
        if (!empty($nrp)) {
            $sql  = "INSERT INTO `akun`(`username`, `password`,`nrp_mahasiswa`,`isadmin`) VALUES (?,?,?,?)";
        } else if (!empty($npk)) {
            $sql  = "INSERT INTO `akun`(`username`, `password`,`npk_dosen`,`isadmin`) VALUES (?,?,?,?)";
        }
        try {
            $stmt = Connection::getConnection()->prepare($sql);
            $username = $this->getUsername();

            if (!empty($nrp)) {
                $stmt->bind_param(
                    'sssi',
                    $username,
                    $password,
                    $nrp,
                    $isAdmin
                );
            } else if (!empty($npk)) {
                $stmt->bind_param(
                    'sssi',
                    $username,
                    $password,
                    $npk,
                    $isAdmin
                );
            }

            $stmt->execute();

            if (!($stmt->affected_rows == 1)) {
                throw new Exception("Data mahasiswa gagal dimasukan ke database,Tidak ada data yang disimpan.");
            }

            if ($stmt !== null) {
                $stmt->close();
            }
        } catch (Exception $e) {
            throw $e;
        }
    }

    //override buat admin
    public function updatePassword($oldPassword, $newPassword,$override=false){

    }
}
