<?php
namespace MODEL;

class Akun{
    private $username; 
    private $nama; 
    private $password;
    private $jenis; //Enum value Admin, Dosen, atau Mahasiswa

    /**
     * Constructor untuk Class Akun
     *
     * @param string $username Menyimpan nama akun
     * @param string $nama Menyimpan nama pengguna
     * @param string $password Menyimpan password akun
     * @param string $jenis Menyimpan jenis akun
     */
    public function __construct($username, $nama, $password, $jenis) {
        $this->setUsername($username);
        $this->setNama($nama);
        $this->setPassword($password);
        $this->setJenis($jenis);
    }

    // --- Getters ---
    /**
     * Memberikan nilai username
     * @return string 
     */
    public function getUsername() {
        return $this->username;
    }

    /**
     * Memberikan jenis akun Mahasiswa, Dosen atau Admin
     * @return string 
     */
    public function getJenis() {
        return $this->jenis;
    }
    /**
     * Memberikan nama pengguna
     * @return string 
     */
    public function getNama() {
        return $this->nama;
    }

    // --- Setters ---

    /**
     * Menyimpan nilali username kedalam class
     * @param string $username
     */
    public function setUsername($username) {
        $this->username = $username;
    }

    /**
     * Menyimpan nilai password kedalam class
     * @param string $password
     */
    public function setPassword($password) {
        $this->password = $password;
    }

    /**
     * Menyimpan nilai nama kedalam class
     * @param string $nama
     */
    public function setNama($nama) {
        $this->nama = $nama;
    }

    /**
     * Menyimpan nilai jenis kedalam class
     * @param string $jenis
     */
    public function setJenis($jenis) {
        $this->jenis = $jenis;
    }

}
?>