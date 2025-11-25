<?php

namespace MODELS;

require_once(__DIR__ . '/Akun.php');
require_once(__DIR__ . '/../CORE/DatabaseConnection.php');

use CORE\DatabaseConnection;
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

    // ================================================================================
    // CONSTRUCTOR
    // ================================================================================
    public function __construct(
        $username,
        $nama,
        $nrp,
        $tanggal_lahir,
        $gender,
        $angkatan,
        $foto_extention
    ) {
        parent::__construct($username, $nama, "MAHASISWA");

        $this->setNRP($nrp);
        $this->setTanggalLahir($tanggal_lahir);
        $this->setGender($gender);
        $this->setAngkatan($angkatan);
        $this->setFotoExtention($foto_extention);
    }

    // ================================================================================
    // GETTERS
    // ================================================================================
    public function getNRP()
    {
        return $this->nrp;
    }
    public function getTanggalLahir()
    {
        return $this->tanggal_lahir;
    }
    public function getGender()
    {
        return $this->gender;
    }
    public function getAngkatan()
    {
        return $this->angkatan;
    }
    public function getFotoExtention()
    {
        return $this->foto_extention;
    }
    public function getFotoAddress()
    {
        return $this->foto_address;
    }

    // ================================================================================
    // SETTERS
    // ================================================================================
    public function setNRP(string $nrp)
    {
        if (empty($nrp)) throw new Exception("NRP tidak boleh kosong.");
        $this->nrp = $nrp;
    }

    public function setTanggalLahir(string $tanggal_lahir)
    {
        if (empty($tanggal_lahir)) throw new Exception("Tanggal lahir tidak boleh kosong.");
        $this->tanggal_lahir = $tanggal_lahir;
    }

    public function setGender(string $gender)
    {
        $gender = ucfirst(strtolower($gender));
        if (!in_array($gender, GENDER)) throw new Exception("Gender tidak valid.");
        $this->gender = $gender;
    }

    public function setAngkatan(int $angkatan)
    {
        if (empty($angkatan)) throw new Exception("Tahun angkatan tidak boleh kosong.");
        $this->angkatan = $angkatan;
    }

    public function setFotoExtention(string $foto_extention)
    {
        $foto_extention = strtolower($foto_extention);
        if (!in_array($foto_extention, ALLOWED_PICTURE_EXTENSION)) {
            throw new Exception("Ekstensi foto tidak valid.");
        }
        $this->foto_extention = $foto_extention;
    }

    public function setFotoAddress($address)
    {
        $this->foto_address = $address;
    }

    // ================================================================================
    // CRUD: CREATE
    // ================================================================================
    public function createMahasiswa($password)
    {
        $stmt = null;
        $this->conn->begin_transaction();

        try {
            $sql = "INSERT INTO mahasiswa (nrp, nama, gender, tanggal_lahir, angkatan, foto_extention)
                    VALUES (?, ?, ?, ?, ?, ?)";

            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param(
                "ssssss",
                $this->nrp,
                $this->getNama(),
                $this->gender,
                $this->tanggal_lahir,
                $this->angkatan,
                $this->foto_extention
            );

            $stmt->execute();
            if ($stmt->affected_rows !== 1) {
                throw new Exception("Gagal insert mahasiswa.");
            }

            parent::createAccount($password, $this->nrp, null);

            $this->conn->commit();
            return true;
        } catch (Exception $e) {
            $this->conn->rollback();
            throw $e;
        } finally {
            if ($stmt) $stmt->close();
            $this->conn->close();
        }
    }

    // ================================================================================
    // CRUD: LOGIN
    // ================================================================================
    public static function mahasiswaLogin($username, $password)
    {
        $sql = "
            SELECT a.username, a.password, a.nrp_mahasiswa,
                   m.nama, m.gender, m.tanggal_lahir, m.angkatan, m.foto_extention
            FROM akun a
            INNER JOIN mahasiswa m ON a.nrp_mahasiswa = m.nrp
            WHERE a.username = ?;
        ";

        $db = new DatabaseConnection();
        $conn = $db->conn;
        $stmt = null;

        try {
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("s", $username);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows === 0) {
                throw new Exception("Username atau Password salah.");
            }

            $row = $result->fetch_assoc();

            if (!password_verify($password, $row["password"])) {
                throw new Exception("Username atau Password salah.");
            }

            return new Mahasiswa(
                $row["username"],
                $row["nama"],
                $row["nrp_mahasiswa"],
                $row["tanggal_lahir"],
                $row["gender"],
                $row["angkatan"],
                $row["foto_extention"]
            );
        } finally {
            if ($stmt) $stmt->close();
            $conn->close();
            $db->__destruct();
        }
    }

    // ================================================================================
    // CRUD: READ ALL
    // ================================================================================
    public static function getAllMahasiswa()
    {
        $db = new DatabaseConnection();
        $conn = $db->conn;
        $stmt = null;

        try {
            $sql = "SELECT nrp, nama, gender, tanggal_lahir, angkatan, foto_extention FROM mahasiswa";
            $stmt = $conn->prepare($sql);
            $stmt->execute();
            $result = $stmt->get_result();

            $list = [];
            while ($row = $result->fetch_assoc()) {
                $list[] = new Mahasiswa(
                    $row["nrp"],         // username = nrp (unless you use different logic)
                    $row["nama"],
                    $row["nrp"],
                    $row["tanggal_lahir"],
                    $row["gender"],
                    $row["angkatan"],
                    $row["foto_extention"]
                );
            }

            return $list;
        } finally {
            if ($stmt) $stmt->close();
            $conn->close();
            $db->__destruct();
        }
    }
    // ================================================================================
    // CRUD: READ — GET ONE MAHASISWA BY USERNAME (JOIN akun + mahasiswa)
    // ================================================================================
    public static function getMahasiswaByUsername(string $username)
    {
        $db = new DatabaseConnection();
        $conn = $db->conn;
        $stmt = null;

        try {
            $sql = "
            SELECT 
                a.username,
                a.nrp_mahasiswa,
                m.nama,
                m.gender,
                m.tanggal_lahir,
                m.angkatan,
                m.foto_extention
            FROM akun a
            INNER JOIN mahasiswa m 
                ON a.nrp_mahasiswa = m.nrp
            WHERE a.username = ?
        ";

            $stmt = $conn->prepare($sql);
            if (!$stmt) {
                throw new Exception("Prepare gagal: " . $conn->error);
            }

            $stmt->bind_param("s", $username);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows === 0) {
                return null;
                // or: throw new Exception("Mahasiswa dengan username tersebut tidak ditemukan.");
            }

            $row = $result->fetch_assoc();

            return new Mahasiswa(
                $row["username"],           // username
                $row["nama"],               // nama (from mahasiswa)
                $row["nrp_mahasiswa"],      // nrp
                $row["tanggal_lahir"],
                $row["gender"],
                $row["angkatan"],
                $row["foto_extention"]
            );
        } catch (Exception $e) {
            throw new Exception("Gagal mengambil mahasiswa by username: " . $e->getMessage());
        } finally {
            if ($stmt) $stmt->close();
            $conn->close();
            $db->__destruct();
        }
    }

    // ================================================================================
    // CRUD: UPDATE
    // ================================================================================
    public function updateMahasiswa()
    {
        $stmt = null;
        $this->conn->begin_transaction();

        try {
            $sql = "UPDATE mahasiswa
                    SET nama = ?, gender = ?, tanggal_lahir = ?, angkatan = ?, foto_extention = ?
                    WHERE nrp = ?";

            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param(
                "ssssss",
                $this->getNama(),
                $this->gender,
                $this->tanggal_lahir,
                $this->angkatan,
                $this->foto_extention,
                $this->nrp
            );

            $stmt->execute();

            parent::updateProfile();

            $this->conn->commit();
            return true;
        } catch (Exception $e) {
            $this->conn->rollback();
            throw new Exception("Gagal update mahasiswa: " . $e->getMessage());
        } finally {
            if ($stmt) $stmt->close();
            $this->conn->close();
        }
    }

    // ================================================================================
    // CRUD: DELETE
    // ================================================================================
    public function deleteMahasiswa()
    {
        $stmt = null;

        try {
            $sql = "DELETE FROM mahasiswa WHERE nrp = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("s", $this->nrp);
            $stmt->execute();

            if ($stmt->affected_rows === 0) {
                throw new Exception("Mahasiswa tidak ditemukan.");
            }

            return true;
        } finally {
            if ($stmt) $stmt->close();
            $this->conn->close();
        }
    }
}
