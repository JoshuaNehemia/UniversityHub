<?php

namespace MODELS;

require_once('../DATABASE/Connection.php');
require_once('Akun.php');

use DATABASE\Connection;
use MODELS\Akun;
use Exception;

class Dosen extends Akun
{
    private $npk;
    private $foto_extention;
    private $foto_address;

    /**
     * Constructor untuk Class Dosen
     *
     * @param string $username Menyimpan nama akun
     * @param string $password Menyimpan password akun
     * @param string $npk Menyimpan kode npk Dosen
     * @param string $tanggal_lahir Menyimpan tanggal lahir Dosen
     * @param int $angkatan Menyimpan tahun angkatan Dosen
     * @param string $foto_extention Menyimpan ???????? (opo iki)
     */
    public function __construct($username, $nama, $npk, $foto_extention)
    {
        parent::__construct($username, $nama, "DOSEN");
        $this->setNPK($npk);
        $this->setFotoExtention($foto_extention);
    }

    // --- Getters ---

    /**
     * Memberikan nilai kode npk Dosen
     * @return string 
     */
    public function getNPK()
    {
        return $this->npk;
    }

    /**
     * apakah maksudnya yang ini ga tau aku. (NOTES: ditanyakan!!!!)
     * @return string 
     */
    public function getFotoExtention()
    {
        return $this->foto_extention;
    }

    public function getFotoAddress(){
        return $this->foto_address;
    }
    public function setFotoAddress($address){
        $this->foto_address = $address;
    }

    // --- Setters ---
    /**
     * Menyimpan nilai npk kedalam class
     * @param string $npk
     */
    public function setNPK(string $npk)
    {
        $this->npk = $npk;
    }
    /**
     * Menyimpan ??? kedalam class
     * @param string $foto_extention
     */
    public function setFotoExtention(string $foto_extention)
    {
        $this->foto_extention = $foto_extention;
    }

    // Function
    public static function LogIn_Dosen(string $username, string $password)
    {
        $sql = "SELECT `username`,`npk`,`nama`,`foto_extension` FROM `akun` INNER JOIN `dosen` ON `akun`.`npk_dosen` = `dosen`.`npk` WHERE `username` = ? AND `password` = ?;";

        try {
            Connection::startConnection();
            $stmt = Connection::getCOnnection()->prepare($sql);
            $stmt->bind_param('ss', $username, $password);

            if ($stmt === false) {
                throw new Exception("Gagal request ke database");
            }

            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
            } else {
                throw new Exception("Tidak ditemukan data yang sesuai dengan permintaan");
            }

            $stmt->close();
            return new Dosen($row['username'], $row['nama'], $row['npk'], $row['foto_extension']);
        } catch (Exception $e) {
            throw $e;
        } finally {
            if (Connection::getConnection() !== null) {
                Connection::closeConnection();
            }
        }
    }

    public function CreateDosenInDatabase(string $password)
    {
        $sql = "INSERT INTO `dosen` (`npk`, `nama`, `foto_extension`) VALUES (?, ?, ?)";

        try {
            Connection::startConnection();
            Connection::getConnection()->begin_transaction();
            $stmt = Connection::getConnection()->prepare($sql);

            if ($stmt === false) {
                throw new Exception("Gagal request ke database");
            }

            $npk     = $this->getNPK();
            $nama    = $this->getNama();
            $fotoExt = $this->getFotoExtention();

            $stmt->bind_param(
                'sss',
                $npk,
                $nama,
                $fotoExt
            );

            $stmt->execute();


            if (!($stmt->affected_rows == 1)) {
                throw new Exception("Data mahasiswa gagal dimasukan ke database,Tidak ada data yang disimpan.");
            }

            parent::CreateInDatabase("", $this->getNPK(), $password,0);
            if ($stmt !== null) {
                $stmt->close();
            }
            Connection::getConnection()->commit();
        } catch (Exception $e) {
            if (Connection::getConnection() !== null) {
                Connection::getConnection()->rollback();
            }
            throw $e;
        } finally {

            if (Connection::getConnection() !== null) {
                Connection::closeConnection();
            }
        }
    }
}
