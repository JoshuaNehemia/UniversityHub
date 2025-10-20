<?php

namespace MODELS;

require_once(__DIR__ .'/../../DATABASE/Connection.php');
require_once(__DIR__ .'/Akun.php');

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
    private $foto_address;

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


    public function getFotoAddress(){
        return $this->foto_address;
    }
    public function setFotoAddress($address){
        $this->foto_address = $address;
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

            if ($row['gender'] === "Pria") {
                $nama = "Mahasiswa " . $row['nama'];
            } else {
                $nama = "Mahasiswi " . $row['nama'];
            }

            $stmt->close();
            return new Mahasiswa($row['username'], $nama, $row['nrp'], $row['tanggal_lahir'], $row['gender'], $row['angkatan'], $row['foto_extention']);
        } catch (Exception $e) {
            throw $e;
        } finally {
            if (Connection::getConnection() !== null) {
                Connection::closeConnection();
            }
        }
    }

    public function CreateMahasiswaInDatabase($password)
    {
        $sql = "INSERT INTO `mahasiswa` (`nrp`, `nama`, `gender`, `tanggal_lahir`, `angkatan`, `foto_extention`) VALUES (?, ?, ?, ?, ?, ?);";
        try {
            Connection::startConnection();
            Connection::getConnection()->begin_transaction();
            $stmt = Connection::getConnection()->prepare($sql);

            if ($stmt === false) {
                throw new Exception("Gagal request ke database");
            }

            $nrp        = $this->getNRP();
            $nama       = $this->getNama();
            $gender     = $this->getGender();
            $tanggal    = $this->getTanggalLahir();
            $angkatan   = $this->getAngkatan();
            $fotoExt    = $this->getFotoExtention();

            $stmt->bind_param(
                'ssssis',
                $nrp,
                $nama,
                $gender,
                $tanggal,
                $angkatan,
                $fotoExt
            );

            $stmt->execute();

            if (!($stmt->affected_rows == 1)) {
                throw new Exception("Data mahasiswa gagal dimasukan ke database,Tidak ada data yang disimpan.");
            }

            parent::CreateInDatabase($this->getNRP(), "",$password, 0);
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

    /**
     * Hapus data dari tabel Akun dan tabel Mahasiswa
     * @param string $username username akun = nrp mahasiswa
     */
    public static function DeleteMahasiswaInDatabase(string $username, string $nrp)
    {
        $sqlAkun = "DELETE FROM akun WHERE username = ?";
        $sqlMahasiswa = "DELETE FROM mahasiswa WHERE nrp = ?";

        try {
            Connection::startConnection();
            Connection::getConnection()->begin_transaction();

            // Hapus data di tabel akun dulu
            $stmt = Connection::getConnection()->prepare($sqlAkun);
            $stmt->bind_param("s", $username);
            $stmt->execute();
            if ($stmt->affected_rows < 1) {
                throw new Exception("Akun tidak ditemukan.");
            }
            $stmt->close();

            // Lalu hapus data di tabel mahasiswa
            $stmt2 = Connection::getConnection()->prepare($sqlMahasiswa);
            $stmt2->bind_param("s", $nrp);
            $stmt2->execute();
            if ($stmt2->affected_rows < 1) {
                throw new Exception("Data mahasiswa tidak ditemukan.");
            }
            $stmt2->close();

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
