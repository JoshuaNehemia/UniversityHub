<?php

namespace MODELS;

require_once(__DIR__ . '/../../DATABASE/Connection.php');
require_once(__DIR__ . '/Akun.php');

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

    public function getFotoAddress()
    {
        return $this->foto_address;
    }
    public function setFotoAddress($address)
    {
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
        $sql = "SELECT `username`,`password`,`npk`,`nama`,`foto_extension` FROM `akun` INNER JOIN `dosen` ON `akun`.`npk_dosen` = `dosen`.`npk` WHERE `username` = ?";

        try {
            Connection::startConnection();
            $stmt = Connection::getCOnnection()->prepare($sql);
            $stmt->bind_param('s', $username);

            if ($stmt === false) {
                throw new Exception("Gagal request ke database");
            }

            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
            } else {
                throw new Exception("Tidak ditemukan username yang sesuai dengan permintaan");
            }

            if (!password_verify($password,$row['password'])) {
                throw new Exception("Password salah");
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
    public static function getData($username)
    {
        $sql = "SELECT 
                d.npk,
                d.nama,
                d.foto_extension
            FROM dosen AS d
            INNER JOIN akun AS a ON d.npk = a.npk_dosen
            WHERE a.username = ?;";

        try {
            Connection::startConnection();
            $stmt = Connection::getConnection()->prepare($sql);

            if ($stmt === false) {
                throw new Exception("Gagal mempersiapkan query ke database");
            }

            $stmt->bind_param("s", $username);
            $stmt->execute();

            $result = $stmt->get_result();

            if ($result->num_rows === 0) {
                throw new Exception("Tidak ditemukan data dosen dengan username tersebut.");
            }

            $row = $result->fetch_assoc();

            $dsn = new Dosen(
                $username,
                $row['nama'],
                $row['npk'],
                $row['foto_extension']
            );

            $stmt->close();

            return $dsn;
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

            parent::CreateInDatabase("", $this->getNPK(), $password, 0);
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

    public function UpdateDosenInDatabase()
    {
        $sql = "UPDATE `dosen`
            SET `npk` = ?, 
                `nama` = ?, 
                `foto_extension` = ?
            WHERE `npk` = (SELECT `npk_dosen` FROM `akun` WHERE `username`= ?);";

        try {
            Connection::startConnection();
            $stmt = Connection::getConnection()->prepare($sql);

            if ($stmt === false) {
                throw new Exception("Gagal mempersiapkan query update ke database");
            }

            $newNPK   = $this->getNPK();
            $nama     = $this->getNama();
            $fotoExt  = $this->getFotoExtention();
            $username = $this->getUsername();

            $stmt->bind_param('ssss', $newNPK, $nama, $fotoExt, $username);
            $stmt->execute();

            if ($stmt->affected_rows === 0) {
                throw new Exception("Tidak ada data mahasiswa yang diperbarui. Pastikan data yang dimasukan benar.");
            }

            if ($stmt !== null) {
                $stmt->close();
            }
        } catch (Exception $e) {
            throw $e;
        } finally {
            if (Connection::getConnection() !== null) {
                Connection::closeConnection();
            }
        }
    }

    
    public static function deleteDosenInDatabase(string $username, string $npk)
    {
        $sql = "DELETE FROM `dosen` WHERE `npk` = ?;";

        try {
            Connection::startConnection();
            Connection::getConnection()->begin_transaction();

            //hps di akun 
            parent::deleteAccountInDatabase($username);

            //hps di mhs
            $stmt = Connection::getConnection()->prepare($sql);
            $stmt->bind_param("s", $npk);
            $stmt->execute();
            if ($stmt->affected_rows < 1) {
                throw new Exception("Data dosen tidak ditemukan.");
            }
            $stmt->close();

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
