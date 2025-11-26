<?php

namespace MODELS;

require_once(__DIR__ . '/Akun.php');
require_once(__DIR__ . '/../config.php');
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

    // ================================================================================================
    // FUNCTION
    // ================================================================================================
    /**
     * Merubah data object class Akun dari serializable object menjadi PHP Array
     * @return array data kelas dalam array.
     */
    public function getArray(): array
    {
        return array_merge(
            parent::getArray(),
            array(
                "npk" => $this->getNPK(),
                "foto_extention" => $this->getFotoExtention()
            )
        );
    }

    /**
     * Merubah data array menjadi Dosen serializable object
     * @return Dosen object kelas yang sudah dikonversi
     */
    public static function readArray(array $data): Dosen
    {
        return new Dosen($data['username'], $data['nama'], $data['npk'], $data['foto_extention']);
    }

    /**
     * Merubah data object class Dosen dari serializable object menjadi JSON (JavaScript Object Notation)
     * @return string data kelas dalam JSON String.
     */
    public function getJSON(): string
    {
        return json_encode($this->getArray());
    }

    /**
     * Merubah data JSON menjadi Dosen serializable object
     * @return Dosen object kelas yang sudah dikonversi
     */
    public static function readJSON(string $json): Dosen
    {
        $data = json_decode($json);
        return self::readArray($data);
    }

    // ================================================================================
    // LOGIN
    // ================================================================================
    public static function dosenLogin(string $username, string $password): Dosen
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
            $db->__destruct();
        }
    }

    // ================================================================================
    // CRUD: READ (Single Dosen)
    // ================================================================================
    public static function dosenGetByUsername(string $username): Dosen
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

            if ($result->num_rows === 0){
                throw new Exception("Tidak ditemukan data dosen yang sesuai");
            }

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
            $db->__destruct();
        }
    }

    // ================================================================================
    // CRUD: READ ALL 
    // Pakai cursor boleh ndak ya?
    // ================================================================================
    public static function dosenGetAll($limit, $offset):array
    {
        $db = new DatabaseConnection();
        $conn = $db->conn;
        $stmt = null;
        $offset = $offset * $limit;
        try {
            $sql = "SELECT 
                    a.username,
                    d.nama,
                    d.npk,
                    d.foto_extension
                FROM dosen d
                INNER JOIN akun a ON d.npk = a.npk_dosen
                LIMIT ? OFFSET ?";

            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ii", $limit, $offset);
            $stmt->execute();

            $result = $stmt->get_result();
            $list = [];

            while ($row = $result->fetch_assoc()) {
                $list[] = new Dosen(
                    $row["username"],
                    $row["nama"],
                    $row["npk"],
                    $row["foto_extension"]
                );
            }

            return $list;
        } finally {
            if ($stmt) $stmt->close();
            $db->__destruct();
        }
    }

    public static function dosenGetAllByName($limit, $offset, $keyword):array
    {
        $db = new DatabaseConnection();
        $conn = $db->conn;
        $stmt = null;
        $keyword = "%{$keyword}%";
        $offset = $offset * $limit;
        try {
            $sql = "SELECT 
                    a.username,
                    d.nama,
                    d.npk,
                    d.foto_extension
                FROM dosen d
                INNER JOIN akun a ON d.npk = a.npk_dosen
                WHERE d.nama LIKE ?
                LIMIT ? OFFSET ?";


            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sii", $keyword, $limit, $offset);
            $stmt->execute();

            $result = $stmt->get_result();
            $list = [];

            while ($row = $result->fetch_assoc()) {
                $list[] = new Dosen(
                    $row["username"],
                    $row["nama"],
                    $row["npk"],
                    $row["foto_extension"]
                );
            }

            return $list;
        } finally {
            if ($stmt) $stmt->close();
            $db->__destruct();
        }
    }
    // ================================================================================
    // CREATE DOSEN
    // ================================================================================
    public function dosenCreate(string $password): Dosen
    {
        $this->startConnection();
        $stmt = null;

        $sql = "INSERT INTO dosen (npk, nama, foto_extension) VALUES (?, ?, ?)";
        try {
            $this->conn->begin_transaction();

            $stmt = $this->conn->prepare($sql);
            if (!$stmt) throw new Exception("Prepare insert gagal.");

            $stmt->bind_param(
                "sss",
                $this->getNPK(),
                $this->getNama(),
                $this->getFotoExtention()
            );

            $stmt->execute();
            if ($stmt->affected_rows !== 1){
                throw new Exception("Gagal menyimpan data dosen.");
            }

            $stmt->close();

            parent::akunCreate("", $this->getNPK(), $password, 0);

            $this->conn->commit();
            return $this;
        } catch (Exception $e) {
            $this->conn->rollback();
            throw $e;
        } finally {
            if ($stmt) $stmt->close();
        }
    }

    // ================================================================================
    // UPDATE
    // ================================================================================
    public function dosenUpdate(): Dosen
    {
        $this->startConnection();
        $this->conn->begin_transaction();
        $stmt = null;
        $sql = "
            UPDATE dosen
            SET npk = ?, nama = ?, foto_extension = ?
            WHERE npk = (SELECT npk_dosen FROM akun WHERE username = ?)
        ";

        try {
            $stmt = $this->conn->prepare($sql);
            if (!$stmt) throw new Exception("Prepare gagal.");

            $npk = $this->getNPK();
            $nama = $this->getNama();
            $ext = $this->getFotoExtention();
            $usn = $this->getUsername();

            $stmt->bind_param(
                "ssss",
                $npk,
                $nama,
                $ext,
                $usn
            );

            $stmt->execute();

            if ($stmt->affected_rows != 1) {
                throw new Exception("Tidak ada data dosen yang diperbarui.");
            }
            $this->conn->commit();
            return $this;
        } catch (Exception $e) {
            $this->conn->rollback();
            throw new Exception("Gagal update dosen: " . $e->getMessage());
        } finally {
            if ($stmt) $stmt->close();
        }
    }

    // ================================================================================
    // DELETE
    // ================================================================================
    public static function dosenDelete(string $username, string $npk): void
    {
        $db = new DatabaseConnection();
        $conn = $db->conn;
        $stmt = null;

        $sql = "DELETE FROM dosen WHERE npk = ?";

        try {
            $conn->begin_transaction();

            parent::akunDelete($username);

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
