<?php

namespace MODELS;

require_once __DIR__ . '/../DATABASE/Connection.php';
require_once __DIR__ . '/Akun.php';

use DATABASE\Connection;
use MODELS\Akun;
use Exception;

class Mahasiswa extends Akun
{
    private $nrp;
    private $tanggal_lahir;
    private $gender;
    private $angkatan;
    private $foto_extention;

    /**
     * Constructor untuk Class Mahasiswa
     * @param string $username Menyimpan nama akun
     * @param string $nama Menyimpan password akun
     * @param string $nrp Menyimpan kode NRP mahasiswa
     * @param string $tanggal_lahir Menyimpan tanggal lahir mahasiswa
     * @param string $gender Menyimpan gender mahasiswa (Enum: Pria / Wanita)
     * @param int $angkatan Menyimpan tahun angkatan mahasiswa
     * @param string $foto_extention Menyimpan ???????? (opo iki)
     */
    public function __construct($username, $nama, $nrp, $tanggal_lahir, $gender, $angkatan, $foto_extention)
    {
        parent::__construct($username, $nama, "MAHASISWA");
        $this->setNRP($nrp);
        $this->setTanggalLahir($tanggal_lahir);
        $this->setGender($gender);
        $this->setAngkatan($angkatan);
        $this->setFotoExtention($foto_extention);
    }

    // --- Getters ---

    /**
     * Memberikan nilai kode NRP mahasiswa
     * @return string 
     */
    public function getNRP()
    {
        return $this->nrp;
    }

    /**
     * Memberikan nilai tanggal lahir mahasiswa
     * @return string 
     */
    public function getTanggalLahir()
    {
        return $this->tanggal_lahir;
    }

    /**
     * Memberikan nilai tahun angkatan mahasiswa
     * @return int 
     */
    public function getAngkatan()
    {
        return $this->angkatan;
    }

    /**
     * apakah maksudnya yang ini ga tau aku. (NOTES: ditanyakan!!!!)
     * @return string 
     */
    public function getFotoExtention()
    {
        return $this->foto_extention;
    }
    /**
     * Memberikan gender mahasaiswa
     * @return string 
     */
    public function getGender()
    {
        return $this->gender;
    }


    // --- Setters ---
    /**
     * Menyimpan nilai nrp kedalam class
     * @param string $nrp
     */
    public function setNRP(string $nrp)
    {
        $this->nrp = $nrp;
    }

    /**
     * Menyimpan tanggal lahir kedalam class
     * @param string $tanggal_lahir
     */
    public function setTanggalLahir(string $tanggal_lahir)
    {
        $this->tanggal_lahir = $tanggal_lahir;
    }

    /**
     * Menyimpan tahun angkatan kedalam class
     * @param int $angkatan
     */
    public function setAngkatan(int $angkatan)
    {
        $this->angkatan = $angkatan;
    }

    /**
     * Menyimpan ??? kedalam class
     * @param string $foto_extention
     */
    public function setFotoExtention(string $foto_extention)
    {
        $this->foto_extention = $foto_extention;
    }

    /**
     * Menyimpan gender mahasiswa ('Pria','Wanita') kedalam class
     * @param string $foto_extention
     */
    public function setGender(string $gender)
    {
        $this->gender = $gender;
    }

    // Function =====================================================================
    public static function LogIn(string $username, string $password)
    {
        $sql = "SELECT `username`,`nrp`,`nama`,`gender`,`tanggal_lahir`,`angkatan`,`foto_extention` FROM `akun` INNER JOIN `mahasiswa` ON `akun`.`nrp_mahasiswa` = `mahasiswa`.`nrp` WHERE `username` = ? AND `password` = ?;";

        Connection::startConnection();
        $stmt = Connection::getCOnnection()->prepare($sql);
        $stmt->bind_param('ss', $username, $password);

        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
        } else {
            return null;
        }

        $stmt->close();
        if (Connection::getConnection() !== null) {
            Connection::closeConnection();
        }
        return new Mahasiswa($row['username'], $row['nama'], $row['nrp'], $row['tanggal_lahir'], $row['gender'], $row['angkatan'], $row['foto_extention']);
    }

    public static function LogIn_Mahasiswa(string $username, string $password)
    {
        $sql = "SELECT `username`,`nrp`,`nama`,`gender`,`tanggal_lahir`,`angkatan`,`foto_extention` FROM `akun` INNER JOIN `mahasiswa` ON `akun`.`nrp_mahasiswa` = `mahasiswa`.`nrp` WHERE `username` = ? AND `password` = ?;";
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

            if($row['gender']==="Pria"){
                $nama = "Mahasiswa " .$row['nama'];
            }
            else{
                $nama = "Mahasiswi " .$row['nama'];
            }

            $stmt->close();
            return new Mahasiswa($row['username'], $nama, $row['nrp'], $row['tanggal_lahir'], $row['gender'], $row['angkatan'], $row['foto_extention']);
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), $e->getCode());
        } finally {
            if (Connection::getConnection() !== null) {
                Connection::closeConnection();
            }
        }

        $stmt->execute();
        $result = $stmt->get_result();

        $row = [];
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
        } else {
            return null;
        }

        $stmt->close();
        if (Connection::getConnection() !== null) {
            Connection::closeConnection();
        }
        return new Mahasiswa($row['username'], $row['nama'], $row['nrp'], $row['tanggal_lahir'], $row['gender'], $row['angkatan'], $row['foto_extention']);
    }

    public function signUp($password)
    {
        $sql_mahasiswa = "INSERT INTO `mahasiswa` (`nrp`, `nama`, `gender`, `tanggal_lahir`, `angkatan`, `foto_extention`) VALUES (?, ?, ?, ?, ?, ?);";
        $sql_akun = "INSERT INTO `akun`(`username`,`password`,`nrp_mahasiswa`) VALUES (?,?,?)";

        Connection::startConnection();
        Connection::getConnection()->begin_transaction();
        $stmtMHS = Connection::getConnection()->prepare($sql_mahasiswa);

        $stmtMHS->bind_param(
            'ssssis',
            $this->getNRP(),
            $this->getNama(),
            $this->getGender(),
            $this->getTanggalLahir(),
            $this->getAngkatan(),
            $this->getFotoExtention()
        );

        $stmtMHS->execute();
        $stmtMHS->close();

        $stmtAkun = Connection::getConnection()->prepare($sql_akun);

        $stmtAkun->bind_param(
            'sss',
            $this->getUsername(),
            $password,
            $this->getNRP()
        );

        $stmtAkun->execute();
        $stmtAkun->close();
        Connection::getConnection()->commit();
        if (Connection::getConnection() !== null) {
            Connection::closeConnection();
        }
    }
}
