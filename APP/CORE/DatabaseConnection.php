<?php

namespace CORE;

require_once(__DIR__ . "/../database.php");

use mysqli;
use Exception;

/**
 * Class DatabaseConnection
 *
 * Kelas pembungkus (wrapper) untuk mengelola koneksi database MySQLi.
 * Kelas ini menangani inisialisasi dan pemutusan hubungan database
 * menggunakan kredensial yang dirahasiakan
 *
 * @package CORE
 */
class DatabaseConnection
{
    /**
     * @var string Alamat host atau IP server database.
     */
    private $database_address = DATABASE_ADDRESS;

    /**
     * @var string Nama database yang akan dituju.
     */
    private $database_name = DATABASE_NAME;

    /**
     * @var string Username untuk autentikasi database.
     */
    private $database_username = DATABASE_USERNAME;

    /**
     * @var string Password untuk autentikasi database.
     */
    private $database_password = DATABASE_PASSWORD;

    /**
     * @var mysqli|null Objek koneksi MySQLi.
     */
    protected $conn;

    /**
     * Konstruktor DatabaseConnection.
     *
     * Secara otomatis mencoba membangun koneksi saat kelas diinstansiasi (dibuat).
     *
     * @throws Exception Jika koneksi gagal.
     */
    public function __construct()
    {
        $this->startConnection();
    }

    /**
     * Destruktor DatabaseConnection.
     *
     * Secara otomatis menutup koneksi database saat objek dihancurkan (misalnya saat skrip selesai).
     */
    public function __destruct()
    {
        $this->closeConnection();
    }

    /**
     * Memulai koneksi MySQLi baru.
     *
     * Memeriksa apakah koneksi sudah ada; jika belum, akan membuat koneksi baru.
     *
     * @return void
     * @throws Exception Jika MySQLi mengembalikan error koneksi.
     */
    protected function startConnection()
    {
        if ($this->conn == null) {
            $this->conn = new mysqli(
                $this->database_address,
                $this->database_username,
                $this->database_password,
                $this->database_name
            );

            if ($this->conn->connect_errno) {
                throw new Exception("Tidak dapat terhubung ke database: " . $this->conn->connect_error);
            }
        }
    }

    /**
     * Menutup koneksi MySQLi yang aktif.
     *
     * @return void
     */
    protected function closeConnection()
    {
        if ($this->conn !== null) {
            $this->conn->close();
            $this->conn = null;
        }
    }
}