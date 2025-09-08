<?php

namespace MODELS;

require_once __DIR__ . '/../DATABASE/Connection.php';
require_once __DIR__ . '/Akun.php';

use DATABASE\Connection;
use MODELS\Akun;
use Exception;

class Dosen extends Akun
{
    private $npk;
    private $foto_extention;

    /**
     * Constructor untuk Class Dosen
     *
     * @param string $username Menyimpan nama akun
     * @param string $password Menyimpan password akun
     * @param string $npk Menyimpan kode npk Dosen
     * @param string $tanggal_lahir Menyimpan tanggal lahir Dosen
     * @param int $angkatan Menyimpan tahun angkatan Dosen
     * @param string $foto_extention Menyimpan ???????? (opo iki)
     */
    public function __construct($username, $nama, $npk, $foto_extention)
    {
        parent::__construct($username, $nama, "DOSEN");
        $this->setNPK($npk);
        $this->setFotoExtention($foto_extention);
    }

    // --- Getters ---

    /**
     * Memberikan nilai kode npk Dosen
     * @return string 
     */
    public function getNPK()
    {
        return $this->npk;
    }

    /**
     * apakah maksudnya yang ini ga tau aku. (NOTES: ditanyakan!!!!)
     * @return string 
     */
    public function getFotoExtention()
    {
        return $this->foto_extention;
    }

    // --- Setters ---
    /**
     * Menyimpan nilai npk kedalam class
     * @param string $npk
     */
    public function setNPK(string $npk)
    {
        $this->npk = $npk;
    }
    /**
     * Menyimpan ??? kedalam class
     * @param string $foto_extention
     */
    public function setFotoExtention(string $foto_extention)
    {
        $this->foto_extention = $foto_extention;
    }

    // Function
    public static function LogIn(string $username, string $password)
    {
        $sql = "SELECT `username`,`npk`,`nama`,`foto_extension` FROM `akun` INNER JOIN `dosen` ON `akun`.`npk_dosen` = `dosen`.`npk` WHERE `username` = ? AND `password` = ?;";

        Connection::startConnection();
        $stmt = Connection::getCOnnection()->prepare($sql);
        $stmt->bind_param('ss', $username, $password);
        if ($stmt === false) {
            throw new Exception("Gagal mempersiapkan data untuk disimpan ke database");
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
        return new Dosen($row['username'], $row['nama'], $row['npk'], $row['foto_extension']);
    }

    public function signUp($password)
    {
        $sql_dosen = "INSERT INTO `dosen` (`npk`, `nama`, `foto_extension`) VALUES (?, ?, ?);";
        $sql_akun = "INSERT INTO `akun`(`username`,`password`,`npk_dosen`) VALUES (?,?,?)";

        Connection::startConnection();
        Connection::getConnection()->begin_transaction();
        $stmtDSN = Connection::getConnection()->prepare($sql_dosen);

        $stmtDSN->bind_param(
            'sss',
            $this->getNPK(),
            $this->getNama(),
            $this->getFotoExtention()
        );

        $stmtDSN->execute();
        $stmtDSN->close();
        $stmtAkun = Connection::getConnection()->prepare($sql_akun);

        $stmtAkun->bind_param(
            'sss',
            $this->getUsername(),
            $password,
            $this->getNPK()
        );

        $stmtAkun->execute();
        $stmtAkun->close();

        if (Connection::getConnection() !== null) {
            Connection::getConnection()->rollback();
        }
        Connection::getConnection()->commit();
    }
}
