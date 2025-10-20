<?php

namespace MODELS;

require_once(__DIR__ .'/../DATABASE/Connection.php');

use DATABASE\Connection;
use Exception;

class Akun
{
    private $username;
    private $nama;
    private $jenis; //Enum value Admin, Dosen, atau Mahasiswa

    /**
     * Constructor untuk Class Akun
     *
     * @param string $username Menyimpan nama akun
     * @param string $nama Menyimpan nama pengguna
     * @param string $password Menyimpan password akun
     * @param string $jenis Menyimpan jenis akun
     */
    public function __construct(string $username, string $nama, string $jenis)
    {
        $this->setUsername($username);
        $this->setNama($nama);
        $this->setJenis($jenis);
    }

    // --- Getters ---
    /**
     * Memberikan nilai username
     * @return string 
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Memberikan jenis akun Mahasiswa, Dosen atau Admin
     * @return string 
     */
    public function getJenis()
    {
        return $this->jenis;
    }
    /**
     * Memberikan nama pengguna
     * @return string 
     */
    public function getNama()
    {
        return $this->nama;
    }

    // --- Setters ---

    /**
     * Menyimpan nilali username kedalam class
     * @param string $username
     */
    public function setUsername(string $username)
    {
        if ($username == "") throw new Exception("Username tidak boleh kosong", 1);
        $this->username = $username;
    }

    /**
     * Menyimpan nilai nama kedalam class
     * @param string $nama
     */
    public function setNama(string $nama)
    {
        if ($nama == "") throw new Exception("Nama tidak boleh kosong", 1);
        $this->nama = $nama;
    }

    /**
     * Menyimpan nilai jenis kedalam class
     * @param string $jenis
     */
    public function setJenis(string $jenis)
    {
        if ($jenis == "") throw new Exception("Jenis tidak boleh kosong");
        $this->jenis = $jenis;
    }


    // FUNCTION =======================================================================================================
    // Ini lama 
    public static function LogIn_Akun(string $username, string $password)
    {
        $sql = "SELECT `username`,`nrp_mahasiswa`,`npk_dosen`,`isadmin` FROM `akun` WHERE `username` = ? AND `password` = ?;";
        try {
            Connection::startConnection();
            $stmt = Connection::getCOnnection()->prepare($sql);

            if ($stmt === false) {
                throw new Exception("Gagal request ke database");
            }

            $stmt->bind_param('ss', $username, $password);
            $stmt->execute();

            $result = $stmt->get_result();
            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
            } else {
                throw new Exception("Username atau password salah");
            }

            $stmt->close();

            $jenis = '';
            if ($row['isadmin'] == '1') {
                $jenis = 'ADMIN';
            } else if (!empty($row['npk_dosen'])) {
                $jenis = 'DOSEN';
            } else if (!empty($row['nrp_mahasiswa'])) {
                $jenis = 'MAHASISWA';
            } else {
                throw new Exception("Akun tidak memiliki data yang sesuai");
            }

            return new Akun($row['username'], $jenis, $jenis);
        } catch (Exception $e) {
            throw $e;
        } finally {
            if (Connection::getConnection() !== null) {
                Connection::closeConnection();
            }
        }
    }

    //baru
    public static function logIn(string $username, string $password)
    {
        $sql = "SELECT `username`,`nrp_mahasiswa`,`npk_dosen`,`isadmin` FROM `akun` WHERE `username` = ? AND `password` = ?;";
        try {
            Connection::startConnection();
            $stmt = Connection::getCOnnection()->prepare($sql);

            if ($stmt === false) {
                throw new Exception("Gagal request ke database");
            }

            $stmt->bind_param('ss', $username, $password);
            $stmt->execute();

            $result = $stmt->get_result();
            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
            } else {
                throw new Exception("Username atau password salah");
            }

            $stmt->close();

            $jenis = '';
            if ($row['isadmin'] == '1') {
                $jenis = 'ADMIN';
            } else {
                throw new Exception("Akun tidak memiliki data yang sesuai");
            }

            return new Akun($row['username'], $jenis, $jenis);
        } catch (Exception $e) {
            throw $e;
        } finally {
            if (Connection::getConnection() !== null) {
                Connection::closeConnection();
            }
        }
    }

    public static function getAccountRole($username)
    {
        $sql = "SELECT `nrp_mahasiswa`,`npk_dosen`,`isadmin` FROM `akun` WHERE `username` = ?;";
        try {
            Connection::startConnection();
            $stmt = Connection::getCOnnection()->prepare($sql);

            if ($stmt === false) {
                throw new Exception("Gagal request ke database");
            }

            $stmt->bind_param('s', $username);
            $stmt->execute();

            $result = $stmt->get_result();
            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
            } else {
                throw new Exception("Username atau password salah");
            }

            $stmt->close();

            $jenis = '';
            if ($row['isadmin'] == '1') {
                $jenis = 'ADMIN';
            } else if (!empty($row['npk_dosen'])) {
                $jenis = 'DOSEN';
            } else if (!empty($row['nrp_mahasiswa'])) {
                $jenis = 'MAHASISWA';
            } else {
                throw new Exception("Akun tidak memiliki data yang sesuai");
            }

            return $jenis;
        } catch (Exception $e) {
            throw $e;
        } finally {
            if (Connection::getConnection() !== null) {
                Connection::closeConnection();
            }
        }
    }

    public static function getData($username){
        return;
    }

    public function CreateInDatabase(string $nrp = "", string $npk = "", string $password, int $isAdmin = 0)
    {
        if (!empty($nrp)) {
            $sql  = "INSERT INTO `akun`(`username`, `password`,`nrp_mahasiswa`,`isadmin`) VALUES (?,?,?,?)";
        } else if (!empty($npk)) {
            $sql  = "INSERT INTO `akun`(`username`, `password`,`npk_dosen`,`isadmin`) VALUES (?,?,?,?)";
        }
        try {
            $stmt = Connection::getConnection()->prepare($sql);
            $username = $this->getUsername();

            if (!empty($nrp)) {
                $stmt->bind_param(
                    'sssi',
                    $username,
                    $password,
                    $nrp,
                    $isAdmin
                );
            } else if (!empty($npk)) {
                $stmt->bind_param(
                    'sssi',
                    $username,
                    $password,
                    $npk,
                    $isAdmin
                );
            }

            $stmt->execute();

            if (!($stmt->affected_rows == 1)) {
                throw new Exception("Data mahasiswa gagal dimasukan ke database,Tidak ada data yang disimpan.");
            }

            if ($stmt !== null) {
                $stmt->close();
            }
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function UpdateInDatabase(string $usernameBaru, string $password, string $nrp = "", string $npk = "", string $nama = "", string $gender = "", string $tanggal_lahir = "", string $angkatan = "", string $foto_extention = "")
    {
        try {
            Connection::startConnection();
            $conn = Connection::getConnection();

            if (!empty($nrp)) {
                $sqlAkun = "UPDATE akun SET `username` = ?, `password` = ?, `nrp_mahasiswa` = ? WHERE `username` = ?";
                $stmtAkun = $conn->prepare($sqlAkun);
                $username = $this->getUsername();

                if ($stmtAkun === false) {
                    throw new Exception("Gagal menyiapkan statement update akun (mahasiswa)");
                }

                $stmtAkun->bind_param("ssss", $usernameBaru, $password, $nrp, $username);
                $stmtAkun->execute();
                $stmtAkun->close();

                $sqlMhs = "UPDATE mahasiswa SET `nama` = ?, `gender` = ?, `tanggal_lahir` = ?, `angkatan` = ?, `foto_extention` = ? WHERE `nrp` = ?";
                $stmtMhs = $conn->prepare($sqlMhs);

                if ($stmtMhs === false) {
                    throw new Exception("Gagal menyiapkan statement update mahasiswa");
                }

                $stmtMhs->bind_param("ssssss", $nama, $gender, $tanggal_lahir, $angkatan, $foto_extention, $nrp);
                $stmtMhs->execute();
                $stmtMhs->close();
            } else if (!empty($npk)) {
                $sqlAkun = "UPDATE akun SET `username` = ?, `password` = ?, `npk_dosen` = ? WHERE `username` = ?";
                $stmtAkun = $conn->prepare($sqlAkun);
                $username = $this->getUsername();

                if ($stmtAkun === false) {
                    throw new Exception("Gagal menyiapkan statement update akun (dosen)");
                }

                $stmtAkun->bind_param("ssss", $usernameBaru, $password, $npk, $username);
                $stmtAkun->execute();
                $stmtAkun->close();

                $sqlDosen = "UPDATE dosen SET `nama` = ?, `foto_extention` = ? WHERE `npk` = ?";
                $stmtDosen = $conn->prepare($sqlDosen);

                if ($stmtDosen === false) {
                    throw new Exception("Gagal menyiapkan statement update dosen");
                }

                $stmtDosen->bind_param("sss", $nama, $foto_extention, $npk);
                $stmtDosen->execute();
                $stmtDosen->close();
            } else {
                throw new Exception("NRP atau NPK harus diisi untuk update");
            }
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Verifikasi password lama akun apakah sesuai dengan yang ada di database
     * @param string $username username akun
     * @param string $oldPassword password lama yang diketikkan user
     * @return bool true jika password cocok
     */
    public static function VerifyPassword(string $username, string $oldPassword)
    {
        $sql = "SELECT `password` FROM `akun` WHERE `username` = ?;";
        try {
            Connection::startConnection();
            $stmt = Connection::getConnection()->prepare($sql);

            if ($stmt === false) {
                throw new Exception("Gagal request ke database");
            }

            $stmt->bind_param('s', $username);
            $stmt->execute();

            $result = $stmt->get_result();
            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
            } else {
                throw new Exception("Akun tidak ditemukan");
            }

            $stmt->close();

            return $row['password'] === $oldPassword;
        } catch (Exception $e) {
            throw $e;
        } finally {
            if (Connection::getConnection() !== null) {
                Connection::closeConnection();
            }
        }
    }

    /**
     * Update password akun
     * @param string $username username akun
     * @param string $newPassword password baru akun
     * @return bool true jika berhasil update
     */
    public static function UpdatePasswordInDatabase(string $username, string $newPassword)
    {
        $sql = "UPDATE `akun` SET `password` = ? WHERE `username` = ?;";
        try {
            Connection::startConnection();
            $stmt = Connection::getConnection()->prepare($sql);

            if ($stmt === false) {
                throw new Exception("Gagal request ke database");
            }

            $stmt->bind_param('ss', $newPassword, $username);
            $stmt->execute();
            if (!($stmt->affected_rows == 1)) {
                $stmt->close();
                throw new Exception("Password gagal diperbarui. Tidak ada perubahan yang disimpan.");
            }

            $stmt->close();

            return true;  //Sukses
        } catch (Exception $e) {
            throw $e;
        } finally {
            if (Connection::getConnection() !== null) {
                Connection::closeConnection();
            }
        }
    }
}
