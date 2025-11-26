<?php

namespace MODELS;

require_once(__DIR__ . '/../CORE/DatabaseConnection.php');
require_once(__DIR__ . '/../config.php');

use CORE\DatabaseConnection;
use Exception;
use mysqli;

/**
 * Class Akun
 * * Model yang merepresentasikan entitas Akun pengguna.
 * Menangani operasi CRUD (Create, Read, Update, Delete) ke tabel `akun`.
 * Mewarisi koneksi database dari class DatabaseConnection.
 * * @package MODELS
 */
class Akun extends DatabaseConnection
{
    /** @var string Username pengguna (Primary Key/Unique). */
    private string $username;

    /** @var string Nama lengkap pengguna. */
    private string $nama;

    /** @var string Jenis role akun */
    private string $jenis;

    // ================================================================================================
    // CONSTRUCTOR
    // ================================================================================================
    /**
     * Akun Constructor.
     * Menginisialisasi objek Akun dan membuka koneksi database (via parent).
     * Jika parameter diisi, properti akan langsung diset.
     * @param string $username Username akun.
     * @param string $nama     Nama akun.
     * @param string $jenis    Jenis/Role akun.
     * @throws Exception Jika validasi setter gagal.
     */
    public function __construct(string $username = "", string $nama = "", string $jenis = "")
    {
        parent::__construct();

        if (!empty($username)) $this->setUsername($username);
        if (!empty($nama)) $this->setNama($nama);
        if (!empty($jenis)) $this->setJenis($jenis);
    }

    // ================================================================================================
    // SETTERS & GETTERS
    // ================================================================================================

    /**
     * Mengambil username.
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Mengambil nama.
     * @return string
     */
    public function getNama()
    {
        return $this->nama;
    }

    /**
     * Mengambil jenis akun.
     * @return string
     */
    public function getJenis()
    {
        return $this->jenis;
    }

    /**
     * Mengatur username dengan validasi.
     * @param string $username
     * @throws Exception Jika username kosong.
     */
    public function setUsername(string $username)
    {
        if (trim($username) == "") throw new Exception("Username tidak boleh kosong");
        $this->username = $username;
    }

    /**
     * Mengatur nama dengan validasi.
     * @param string $nama
     * @throws Exception Jika nama kosong.
     */
    public function setNama(string $nama)
    {
        if (trim($nama) == "") throw new Exception("Nama tidak boleh kosong");
        $this->nama = $nama;
    }

    /**
     * Mengatur jenis akun dengan validasi terhadap konstanta ACCOUNT_ROLE.
     * @param string $jenis
     * @throws Exception Jika jenis kosong atau tidak ada dalam daftar yang diizinkan.
     */
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
    // FUNCTION
    // ================================================================================================
    /**
     * Merubah data object class Akun dari serializable object menjadi PHP Array
     * @return array data kelas dalam array.
     */
    public function getArray(): array
    {
        return array(
            "username" => $this->getUsername(),
            "nama" => $this->getNama(),
            "jenis" => $this->getJenis()
        );
    }

    /**
     * Merubah data array menjadi Akun serializable object
     * @return Akun data kelas yang sudah dikonversi
     */
    public static function readArray(array $data)
    {
        return new Akun($data['username'], $data['nama'], $data['jenis']);
    }

    /**
     * Merubah data object class Akun dari serializable object menjadi JSON (JavaScript Object Notation)
     * @return string data kelas dalam JSON String.
     */
    public function getJSON(): string
    {
        return json_encode($this->getArray());
    }

    /**
     * Merubah data JSON menjadi Akun serializable object
     * @return Akun data kelas yang sudah dikonversi
     */
    public static function readJSON(string $json)
    {
        $data = json_decode($json);
        return self::readArray($data);
    }


    // ================================================================================================
    // CRUD: CREATE
    // ================================================================================================
    /**
     * Menyimpan data akun baru ke database.
     * * @param string $password Password mentah (akan di-hash).
     * @param string|null $nrp (Opsional) NRP jika mahasiswa.
     * @param string|null $npk (Opsional) NPK jika dosen.
     * @return bool True jika berhasil.
     * @throws Exception Jika validasi gagal atau query error.
     */
    public function akunCreate(string $password, string $nrp = null, string $npk = null)
    {
        // Validasi spesifik role
        if ($this->jenis === ACCOUNT_ROLE[0] && empty($nrp)) throw new Exception("Mahasiswa wajib punya NRP");
        if ($this->jenis === ACCOUNT_ROLE[1] && empty($npk)) throw new Exception("Dosen wajib punya NPK");

        $sql = "INSERT INTO `akun` (`username`, `password`, `nama`, `jenis`, `nrp_mahasiswa`, `npk_dosen`, `isadmin`) VALUES (?, ?, ?, ?, ?, ?, ?)";

        $hashed_password = password_hash($password, PASSWORD_BCRYPT);

        // Logika isadmin (Asumsi index 2 array ACCOUNT_ROLE adalah Admin)
        $isAdmin = ($this->jenis === ACCOUNT_ROLE[2]) ? 1 : 0;

        $nrp_val = empty($nrp) ? null : $nrp;
        $npk_val = empty($npk) ? null : $npk;

        $stmt = null;
        try {
            $stmt = $this->conn->prepare($sql);

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

            return true;
        } catch (Exception $e) {
            throw $e;
        } finally {
            if ($stmt) $stmt->close();
            // Catatan: Jangan menutup $this->conn di sini jika objek ini masih ingin dipakai setelah create.
        }
    }
// ================================================================================================
    // CRUD: READ
    // ================================================================================================
    /**
     * Melakukan proses login dan mengembalikan objek Akun.
     * * @param string $username
     * @param string $password
     * @return Akun Objek Akun yang berhasil login.
     * @throws Exception Jika username tidak ditemukan atau password salah.
     */
    public static function akunRetrieveRole(string $username)
    {
        $instance = new self();
        $conn = $instance->conn;

        $sql = "SELECT `nrp_mahasiswa`,`npk_dosen`,`isadmin` FROM akun WHERE username = ?";
        $stmt = null;

        try {
            $stmt = $conn->prepare($sql);
            if (!$stmt) throw new Exception("Gagal mempersiapkan statement.");

            $stmt->bind_param('s', $username);
            $stmt->execute();
            $result = $stmt->get_result();

            if (!$result || $result->num_rows === 0) {
                throw new Exception("Username tidak ditemukan.");
            }

            $row = $result->fetch_assoc();
            $jenis = "";
            if (!empty($row['nrp_mahasiswa'])) {
                $jenis = ACCOUNT_ROLE[0];
            } else if (!empty($row['npk_dosen'])) {
                $jenis = ACCOUNT_ROLE[1];
            } else if (!empty($row['isadmin']) && $row['isadmin'] == 1) {
                $jenis = ACCOUNT_ROLE[2];
            }
            // Return objek baru yang terisi
            return $jenis;
        } catch (Exception $e) {
            throw $e;
        } finally {
            if ($stmt) $stmt->close();
            $instance->__destruct();
        }
    }

    // ================================================================================================
    // CRUD: READ
    // ================================================================================================
    /**
     * Melakukan proses login dan mengembalikan objek Akun.
     * * @param string $username
     * @param string $password
     * @return Akun Objek Akun yang berhasil login.
     * @throws Exception Jika username tidak ditemukan atau password salah.
     */
    public static function akunLogin(string $username, string $password)
    {
        $instance = new self();
        $conn = $instance->conn;

        $sql = "SELECT `username`, `password` FROM akun WHERE username = ?";
        $stmt = null;

        try {
            $stmt = $conn->prepare($sql);
            if (!$stmt) throw new Exception("Gagal mempersiapkan statement.");

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

            // Return objek baru yang terisi
            return new Akun(
                $row['username'],
                ACCOUNT_ROLE[2],
                ACCOUNT_ROLE[2]
            );
        } catch (Exception $e) {
            throw $e;
        } finally {
            if ($stmt) $stmt->close();
            $instance->__destruct();
        }
    }

    /**
     * Mencari akun berdasarkan username.
     * * @param string $username
     * @return Akun|null Mengembalikan objek Akun atau null jika tidak ditemukan.
     * @throws Exception
     */
    public static function akunGetByUsername($username)
    {
        $instance = new static(); // Perbaikan dari new DatabaseConnection()
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
            $instance = null;
        }
    }

    // ================================================================================================
    // CRUD: UPDATE
    // ================================================================================================
    /**
     * Mengganti password akun.
     * * @param string $newPassword Password baru (belum di-hash).
     * @return bool
     * @throws Exception
     */
    public function akunUpdatePassword($newPassword)
    {
        $sql = "UPDATE `akun` SET `password` = ? WHERE `username` = ?";
        $hashed = password_hash($newPassword, PASSWORD_BCRYPT);
        $stmt = null;

        try {
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param('ss', $hashed, $this->username);
            $stmt->execute();
            return true;
        } catch (Exception $e) {
            throw new Exception("Gagal update password: " . $e->getMessage());
        } finally {
            if ($stmt) $stmt->close();
        }
    }

    // ================================================================================================
    // CRUD: DELETE
    // ================================================================================================

    /**
     * Menghapus akun dari database.
     * * @return bool
     * @throws Exception
     */
    public function akunDelete()
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
        }
    }
}
