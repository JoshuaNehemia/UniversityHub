<?php

namespace MODELS;

require_once(__DIR__ . '/../CORE/DatabaseConnection.php');
require_once(__DIR__ . '/../config.php');

use CORE\DatabaseConnection;
use Exception;

class Akun extends DatabaseConnection
{
    private $username;
    private $nama;
    private $jenis;

    // ================================================================================================
    // CONSTRUCTOR
    // ================================================================================================
    public function __construct(string $username = "", string $nama = "", string $jenis = "")
    {
        parent::__construct(); // Initialize DB Connection

        if (!empty($username)) $this->setUsername($username);
        if (!empty($nama)) $this->setNama($nama);
        if (!empty($jenis)) $this->setJenis($jenis);
    }

    // ================================================================================================
    // SETTERS & GETTERS
    // ================================================================================================

    public function getUsername()
    {
        return $this->username;
    }
    public function getNama()
    {
        return $this->nama;
    }
    public function getJenis()
    {
        return $this->jenis;
    }

    public function setUsername(string $username)
    {
        if (trim($username) == "") throw new Exception("Username tidak boleh kosong");
        $this->username = $username;
    }

    public function setNama(string $nama)
    {
        if (trim($nama) == "") throw new Exception("Nama tidak boleh kosong");
        $this->nama = $nama;
    }

    public function setJenis(string $jenis)
    {
        $jenis = strtoupper($jenis);
        if ($jenis == "") throw new Exception("Jenis tidak boleh kosong");
        if (!in_array($jenis, ACCOUNT_ROLE)) {
            throw new Exception("Jenis akun illegal. Harus: " . implode(',', ACCOUNT_ROLE));
        }
        $this->jenis = $jenis;
    }

    // ================================================================================================
    // CRUD: CREATE
    // ================================================================================================
    public function createAccount(string $password, string $nrp = null, string $npk = null)
    {
        if ($this->jenis ===  ACCOUNT_ROLE[0] && empty($nrp)) throw new Exception("Mahasiswa wajib punya NRP");
        if ($this->jenis === ACCOUNT_ROLE[1] && empty($npk)) throw new Exception("Dosen wajib punya NPK");

        $sql = "INSERT INTO `akun` (`username`, `password`, `nama`, `jenis`, `nrp_mahasiswa`, `npk_dosen`, `isadmin`) VALUES (?, ?, ?, ?, ?, ?, ?)";

        $hashed_password = password_hash($password, PASSWORD_BCRYPT);
        $isAdmin = 0;
        if ($this->jenis === ACCOUNT_ROLE[2]) $isAdmin = 1;

        $nrp_val = empty($nrp) ? null : $nrp;
        $npk_val = empty($npk) ? null : $npk;

        try {
            $stmt = $this->conn->prepare($sql);

            // s(user), s(pass), s(nama), s(jenis), s(nrp), s(npk), i(admin)
            $stmt->bind_param(
                'ssssssi',
                $this->username,
                $hashed_password,
                $this->nama,
                $this->jenis,
                $nrp_val,
                $npk_val,
                $isAdmin
            );

            $stmt->execute();

            if ($stmt->affected_rows !== 1) {
                throw new Exception("Gagal membuat akun.");
            }

            $stmt->close();
            return true;
        } catch (Exception $e) {
            throw $e;
        } finally {
            if (isset($stmt)) $stmt->close();
            $this->conn->close();
        }
    }

    // ================================================================================================
    // CRUD: READ
    // ================================================================================================
    public static function login(string $username, string $password)
    {
        $instance = new DatabaseConnection();
        $conn = $instance->conn;

        $sql = "SELECT username, password, nama, jenis 
            FROM akun WHERE username = ?";

        try {
            $stmt = $conn->prepare($sql);
            if (!$stmt) {
                throw new Exception("Gagal mempersiapkan statement.");
            }

            $stmt->bind_param('s', $username);
            $stmt->execute();
            $result = $stmt->get_result();

            if (!$result || $result->num_rows === 0) {
                throw new Exception("Username tidak ditemukan.");
            }

            $row = $result->fetch_assoc();

            if (!password_verify($password, $row['password'])) {
                throw new Exception("Password salah.");
            }
            return new Akun(
                $row['username'],
                $row['nama'],
                $row['jenis']
            );
        } catch (Exception $e) {
            throw $e;
        } finally {
            if (isset($stmt)) $stmt->close();
            $instance->__destruct();
        }
    }


    public static function getByUsername($username)
    {
        $instance = new DatabaseConnection();
        $conn = $instance->conn;
        $stmt = null;

        $sql = "SELECT username, nama, jenis FROM `akun` WHERE username = ?";

        try {
            $stmt = $conn->prepare($sql);
            if (!$stmt) throw new Exception("Gagal mempersiapkan statement.");

            $stmt->bind_param('s', $username);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($row = $result->fetch_assoc()) {
                return new Akun($row['username'], $row['nama'], $row['jenis']);
            }

            return null;
        } catch (Exception $e) {
            throw new Exception("Gagal mengambil akun: " . $e->getMessage());
        } finally {
            if ($stmt) $stmt->close();
            $conn->close();
            $instance->__destruct();
        }
    }

    // ================================================================================================
    // CRUD: UPDATE
    // ================================================================================================
    public function updateProfile()
    {
        $sql = "UPDATE `akun` SET `nama` = ?WHERE `username` = ?";
        $stmt = null;

        try {
            $stmt = $this->conn->prepare($sql);
            if (!$stmt) throw new Exception("Gagal mempersiapkan statement.");

            $stmt->bind_param('ss', $this->nama, $this->username);
            $stmt->execute();

            if ($stmt->affected_rows === 0) {
                throw new Exception("Tidak ada perubahan atau username tidak ditemukan.");
            }

            return true;
        } catch (Exception $e) {
            throw new Exception("Gagal update profile: " . $e->getMessage());
        } finally {
            if ($stmt) $stmt->close();
            $this->conn->close();
        }
    }


    public function updatePassword($newPassword)
    {
        $sql = "UPDATE `akun` SET `password` = ? WHERE `username` = ?";
        $hashed = password_hash($newPassword, PASSWORD_BCRYPT);

        try {
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param('ss', $hashed, $this->username);
            $stmt->execute();
            $stmt->close();
            return true;
        } catch (Exception $e) {
            throw new Exception("Gagal update password: " . $e->getMessage());
        }
        finally{
            if ($stmt) $stmt->close();
            $this->conn->close();
        }
    }

    // ================================================================================================
    // CRUD: DELETE
    // ================================================================================================
    public function delete()
    {
        $sql = "DELETE FROM `akun` WHERE `username` = ?";
        $stmt = null;

        try {
            $stmt = $this->conn->prepare($sql);
            if (!$stmt) throw new Exception("Gagal mempersiapkan statement.");

            $stmt->bind_param('s', $this->username);
            $stmt->execute();

            if ($stmt->affected_rows == 0) {
                throw new Exception("User tidak ditemukan atau sudah terhapus.");
            }

            return true;
        } catch (Exception $e) {
            throw new Exception("Gagal menghapus akun: " . $e->getMessage());
        } finally {
            if ($stmt) $stmt->close();
            $this->conn->close();
        }
    }
}
