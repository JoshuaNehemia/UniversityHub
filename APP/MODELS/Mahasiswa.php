<?php

namespace MODELS;

require_once(__DIR__ . '/../../DATABASE/Connection.php');
require_once(__DIR__ . '/Akun.php');

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
        $sql = "SELECT `username`,`password`,`nrp`,`nama`,`gender`,`tanggal_lahir`,`angkatan`,`foto_extention` FROM `akun` INNER JOIN `mahasiswa` ON `akun`.`nrp_mahasiswa` = `mahasiswa`.`nrp` WHERE `username` = ?;";
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
                throw new Exception("Username tidak ditemukan");
            }
            if (!password_verify($password,$row['password'])) {
                throw new Exception("Password salah");
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

    public static function getData($username)
    {
        $sql = "SELECT 
                m.nrp,
                m.nama,
                m.gender,
                m.tanggal_lahir,
                m.angkatan,
                m.foto_extention
            FROM mahasiswa AS m
            INNER JOIN akun AS a ON m.nrp = a.nrp_mahasiswa
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
                throw new Exception("Tidak ditemukan data mahasiswa");
            }

            $row = $result->fetch_assoc();

            // Buat object Mahasiswa baru dan isi data dari DB
            $mhs = new Mahasiswa($username, $row['nama'], $row['nrp'], $row['tanggal_lahir'], $row['gender'], $row['angkatan'], $row['foto_extention']);
            $stmt->close();

            return $mhs;
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

            parent::CreateInDatabase($this->getNRP(), "", $password, 0);
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

    public function UpdateMahasiswaInDatabase() //string $oldNRP)
    {
        $sql = "UPDATE `mahasiswa`
            SET `nrp` = ?, 
                `nama` = ?, 
                `gender` = ?, 
                `tanggal_lahir` = ?, 
                `angkatan` = ?, 
                `foto_extention` = ?
            WHERE `nrp` = (SELECT `nrp_mahasiswa` FROM `akun` WHERE `username`= ?);";
        try {
            Connection::startConnection();
            $stmt = Connection::getConnection()->prepare($sql);

            if ($stmt === false) {
                throw new Exception("Gagal mempersiapkan query update ke database");
            }
            //print_r($this);
            $username   = $this->getUsername();
            $newNRP     = $this->getNRP();
            $nama       = $this->getNama();
            $gender     = $this->getGender();
            $tanggal    = htmlspecialchars($this->getTanggalLahir());
            //print_r($tanggal);
            $angkatan   = $this->getAngkatan();
            $fotoExt    = $this->getFotoExtention();

            $stmt->bind_param(
                'ssssiss',
                $newNRP,
                $nama,
                $gender,
                $tanggal,
                $angkatan,
                $fotoExt,
                $username
            );
            print_r($stmt);

            $stmt->execute();

            if ($stmt->affected_rows === 0) {
                throw new Exception("Tidak ada data mahasiswa yang diperbarui. Pastikan data yang dimasukan benar.");
            }

            if ($stmt !== null) {
                $stmt->close();
            }
        } catch (Exception $e) {
            //update on cascade ga perlu transaction 
            throw $e;
        } finally {
            if (Connection::getConnection() !== null) {
                Connection::closeConnection();
            }
        }
    }

    public static function DeleteMahasiswaInDatabase(string $username, string $nrp)
    {
        $sql = "DELETE FROM `mahasiswa` WHERE `nrp` = ?;";

        try {
            Connection::startConnection();
            Connection::getConnection()->begin_transaction();

            //hps di akun 
            parent::deleteAccountInDatabase($username);

            //hps di mhs
            $stmt = Connection::getConnection()->prepare($sql);
            $stmt->bind_param("s", $nrp);
            $stmt->execute();
            if ($stmt->affected_rows < 1) {
                throw new Exception("Data mahasiswa tidak ditemukan.");
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
