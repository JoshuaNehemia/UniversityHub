<?php

namespace MODELS;

require_once(__DIR__ . '/Akun.php');
require_once(__DIR__ . '/../upload.php');
require_once(__DIR__ . '/../CORE/DatabaseConnection.php');

use CORE\DatabaseConnection;
use MODELS\Akun;
use Exception;

class Dosen extends Akun
{
    private $npk;
    private $foto_extention;
    private $foto_address;

    // ================================================================================
    // CONSTRUCTOR
    // ================================================================================
    public function __construct($username, $nama, $npk, $foto_extention)
    {
        parent::__construct($username, $nama, "DOSEN");
        $this->setNPK($npk);
        $this->setFotoExtention($foto_extention);
    }

    // ================================================================================
    // GETTERS
    // ================================================================================
    public function getNPK() { return $this->npk; }
    public function getFotoExtention() { return $this->foto_extention; }
    public function getFotoAddress() { return $this->foto_address; }

    // ================================================================================
    // SETTERS
    // ================================================================================
    public function setNPK(string $npk)
    {
        if (empty($npk)) throw new Exception("NPK tidak dapat kosong");
        $this->npk = $npk;
    }

    public function setFotoExtention(string $foto_extention)
    {
        $foto_extention = strtolower($foto_extention);

        if (empty($foto_extention))
            throw new Exception("Extention tidak dapat kosong");

        if (!in_array($foto_extention, ALLOWED_PICTURE_EXTENSION))
            throw new Exception("Extention illegal: " . implode(', ', ALLOWED_PICTURE_EXTENSION));

        $this->foto_extention = $foto_extention;
    }

    public function setFotoAddress($address)
    {
        $this->foto_address = $address;
    }

    // ================================================================================
    // LOGIN
    // ================================================================================
    public static function login(string $username, string $password): Dosen
    {
        $db = new DatabaseConnection();
        $conn = $db->conn;
        $stmt = null;

        $sql = "
            SELECT a.username, a.password, d.npk, d.nama, d.foto_extension
            FROM akun AS a
            INNER JOIN dosen AS d ON a.npk_dosen = d.npk
            WHERE a.username = ?
        ";

        try {
            $stmt = $conn->prepare($sql);
            if (!$stmt) throw new Exception("Prepare login gagal.");

            $stmt->bind_param("s", $username);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows === 0)
                throw new Exception("Username tidak ditemukan.");

            $row = $result->fetch_assoc();

            if (!password_verify($password, $row['password']))
                throw new Exception("Password salah.");

            return new Dosen(
                $row['username'],
                $row['nama'],
                $row['npk'],
                $row['foto_extension']
            );

        } finally {
            if ($stmt) $stmt->close();
            $conn->close();
            $db->__destruct();
        }
    }

    // ================================================================================
    // GET SINGLE DOSEN BY USERNAME (JOIN)
    // ================================================================================
    public static function getDosenByUsername(string $username): ?Dosen
    {
        $db = new DatabaseConnection();
        $conn = $db->conn;
        $stmt = null;

        try {
            $sql = "
                SELECT a.username, d.npk, d.nama, d.foto_extension
                FROM akun AS a
                INNER JOIN dosen AS d ON a.npk_dosen = d.npk
                WHERE a.username = ?
            ";

            $stmt = $conn->prepare($sql);
            if (!$stmt) throw new Exception("Prepare gagal: " . $conn->error);

            $stmt->bind_param("s", $username);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows === 0)
                return null;

            $row = $result->fetch_assoc();

            return new Dosen(
                $row["username"],
                $row["nama"],
                $row["npk"],
                $row["foto_extension"]
            );

        } catch (Exception $e) {
            throw new Exception("Gagal mengambil dosen: " . $e->getMessage());
        } finally {
            if ($stmt) $stmt->close();
            $conn->close();
            $db->__destruct();
        }
    }

    // ================================================================================
    // GET ALL DOSEN (ADMIN)
    // ================================================================================
    public static function getAllDosen(): array
    {
        $db = new DatabaseConnection();
        $conn = $db->conn;
        $stmt = null;

        try {
            $sql = "
                SELECT d.npk, d.nama, d.foto_extension, a.username
                FROM dosen AS d
                LEFT JOIN akun AS a ON a.npk_dosen = d.npk
            ";

            $stmt = $conn->prepare($sql);
            if (!$stmt) throw new Exception("Prepare gagal: " . $conn->error);

            $stmt->execute();
            $result = $stmt->get_result();

            $list = [];

            while ($row = $result->fetch_assoc()) {
                $list[] = new Dosen(
                    $row["username"] ?? "-",   // dosen without akun yet
                    $row["nama"],
                    $row["npk"],
                    $row["foto_extension"]
                );
            }

            return $list;

        } catch (Exception $e) {
            throw new Exception("Gagal mengambil semua dosen: " . $e->getMessage());
        } finally {
            if ($stmt) $stmt->close();
            $conn->close();
            $db->__destruct();
        }
    }

    // ================================================================================
    // CREATE DOSEN
    // ================================================================================
    public function create_dosen_in_database(string $password): void
    {
        $db = new DatabaseConnection();
        $conn = $db->conn;
        $stmt = null;

        $sql = "INSERT INTO dosen (npk, nama, foto_extension) VALUES (?, ?, ?)";

        try {
            $conn->begin_transaction();

            $stmt = $conn->prepare($sql);
            if (!$stmt) throw new Exception("Prepare insert gagal.");

            $stmt->bind_param(
                "sss",
                $this->npk,
                $this->nama,
                $this->foto_extention
            );

            $stmt->execute();
            if ($stmt->affected_rows !== 1)
                throw new Exception("Gagal menyimpan data dosen.");

            $stmt->close();

            parent::create_in_database("", $this->npk, $password, 0);

            $conn->commit();

        } catch (Exception $e) {
            $conn->rollback();
            throw $e;

        } finally {
            $conn->close();
            $db->__destruct();
        }
    }

    // ================================================================================
    // UPDATE
    // ================================================================================
    public function update_database(): void
    {
        $db = new DatabaseConnection();
        $conn = $db->conn;
        $stmt = null;

        $sql = "
            UPDATE dosen
            SET npk = ?, nama = ?, foto_extension = ?
            WHERE npk = (SELECT npk_dosen FROM akun WHERE username = ?)
        ";

        try {
            $stmt = $conn->prepare($sql);
            if (!$stmt) throw new Exception("Prepare gagal.");

            $stmt->bind_param(
                "ssss",
                $this->npk,
                $this->nama,
                $this->foto_extention,
                $this->username
            );

            $stmt->execute();

            if ($stmt->affected_rows === 0)
                throw new Exception("Tidak ada data dosen yang diperbarui.");

        } finally {
            if ($stmt) $stmt->close();
            $conn->close();
            $db->__destruct();
        }
    }

    // ================================================================================
    // DELETE
    // ================================================================================
    public static function delete_dosen_from_database(string $username, string $npk): void
    {
        $db = new DatabaseConnection();
        $conn = $db->conn;
        $stmt = null;

        $sql = "DELETE FROM dosen WHERE npk = ?";

        try {
            $conn->begin_transaction();

            parent::delete_from_database($username);

            $stmt = $conn->prepare($sql);
            $stmt->bind_param("s", $npk);
            $stmt->execute();

            if ($stmt->affected_rows < 1)
                throw new Exception("Data dosen tidak ditemukan.");

            $conn->commit();

        } catch (Exception $e) {
            $conn->rollback();
            throw $e;

        } finally {
            if ($stmt) $stmt->close();
            $conn->close();
            $db->__destruct();
        }
    }
}
