<?php

namespace MODELS;

require_once(__DIR__ . '/Akun.php');
require_once(__DIR__ . '/../upload.php');
require_once(__DIR__ .'/../CORE/DatabaseConnection.php');

use CORE\DatabaseConnection;
use MODELS\Akun;
use Exception;

class Dosen extends Akun
{
    private $npk;
    private $foto_extention;
    private $foto_address;

    
    // ================================================================================================
    // CONSTRUCTOR
    // ================================================================================================
    public function __construct($username, $nama, $npk, $foto_extention)
    {
        parent::__construct($username, $nama, "DOSEN");
        $this->setNPK($npk);
        $this->setFotoExtention($foto_extention);
    }


    // ================================================================================================
    // GETTER
    // ================================================================================================
    public function getNPK()
    {
        return $this->npk;
    }

    public function getFotoExtention()
    {
        return $this->foto_extention;
    }

    public function getFotoAddress()
    {
        return $this->foto_address;
    }

    // ================================================================================================
    // SETTER
    // ================================================================================================
    public function setNPK(string $npk)
    {
        if(empty($npk)) throw new Exception("NPK tidak dapat kosong");
        $this->npk = $npk;
    }

    public function setFotoExtention(string $foto_extention)
    {
        $foto_extention = strtolower($foto_extention);
        if(empty($foto_extention)) throw new Exception("Extention tidak dapat kosong");
        if(!in_array($foto_extention,ALLOWED_PICTURE_EXTENSION))  throw new Exception("Extention illegal, Upload file berupa: " . implode(', ', ALLOWED_PICTURE_EXTENSION));
        $this->foto_extention = $foto_extention;
    }

    public function setFotoAddress($address)
    {
        $this->foto_address = $address;
    }

    

    // ================================================================================================
    // CRUD (RETRIEVE)
    // ================================================================================================
    public static function login(string $username, string $password): Dosen
    {
        $db = new DatabaseConnection();
        $conn = $db->conn;

        $sql = "
            SELECT a.username, a.password, d.npk, d.nama, d.foto_extension
            FROM akun AS a
            INNER JOIN dosen AS d ON a.npk_dosen = d.npk
            WHERE a.username = ?
        ";

        try {
            $stmt = $conn->prepare($sql);
            if (!$stmt) throw new Exception("Failed to prepare login query.");

            $stmt->bind_param("s", $username);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows === 0)
                throw new Exception("Username tidak ditemukan.");

            $row = $result->fetch_assoc();

            if (!password_verify($password, $row['password']))
                throw new Exception("Password salah.");

            $stmt->close();

            return new Dosen(
                $row['username'],
                $row['nama'],
                $row['npk'],
                $row['foto_extension']
            );

        } finally {
            if ($stmt) $stmt->close();
            $db->__destruct();
        }
    }

    // ============================
    // GET DATA BY USERNAME
    // ============================

    public static function get_data(string $username): Dosen
    {
        $db = new DatabaseConnection();
        $conn = $db->conn;

        $sql = "
            SELECT d.npk, d.nama, d.foto_extension
            FROM dosen AS d
            INNER JOIN akun AS a ON d.npk = a.npk_dosen
            WHERE a.username = ?
        ";

        try {
            $stmt = $conn->prepare($sql);
            if (!$stmt) throw new Exception("Failed to prepare select query.");

            $stmt->bind_param("s", $username);
            $stmt->execute();

            $result = $stmt->get_result();
            if ($result->num_rows === 0)
                throw new Exception("Data dosen tidak ditemukan.");

            $row = $result->fetch_assoc();
            $stmt->close();

            return new Dosen(
                $username,
                $row['nama'],
                $row['npk'],
                $row['foto_extension']
            );

        } finally {
            $db->close();
        }
    }

    // ============================
    // CREATE
    // ============================

    public function create_dosen_in_database(string $password): void
    {
        $db = new DatabaseConnection();
        $conn = $db->conn;

        $sql = "INSERT INTO dosen (npk, nama, foto_extension) VALUES (?, ?, ?)";

        try {
            $conn->begin_transaction();

            $stmt = $conn->prepare($sql);
            if (!$stmt) throw new Exception("Failed to prepare insert query.");

            $stmt->bind_param(
                "sss",
                $this->npk,
                $this->nama,
                $this->foto_extension
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
            $db->close();
        }
    }

    // ============================
    // UPDATE
    // ============================

    public function update_database(): void
    {
        $db = new DatabaseConnection();
        $conn = $db->conn;

        $sql = "
            UPDATE dosen
            SET npk = ?, nama = ?, foto_extension = ?
            WHERE npk = (SELECT npk_dosen FROM akun WHERE username = ?)
        ";

        try {
            $stmt = $conn->prepare($sql);
            if (!$stmt) throw new Exception("Failed to prepare update query.");

            $stmt->bind_param(
                "ssss",
                $this->npk,
                $this->nama,
                $this->foto_extension,
                $this->username
            );

            $stmt->execute();

            if ($stmt->affected_rows === 0)
                throw new Exception("Tidak ada data dosen yang diperbarui.");

            $stmt->close();

        } finally {
            $db->close();
        }
    }

    // ============================
    // DELETE
    // ============================

    public static function delete_dosen_from_database(string $username, string $npk): void
    {
        $db = new DatabaseConnection();
        $conn = $db->conn;

        $sql = "DELETE FROM dosen WHERE npk = ?";

        try {
            $conn->begin_transaction();

            parent::delete_from_database($username);

            $stmt = $conn->prepare($sql);
            $stmt->bind_param("s", $npk);
            $stmt->execute();

            if ($stmt->affected_rows < 1)
                throw new Exception("Data dosen tidak ditemukan.");

            $stmt->close();
            $conn->commit();

        } catch (Exception $e) {
            $conn->rollback();
            throw $e;

        } finally {
            $db->close();
        }
    }
}
