<?php
namespace MODEL;

class Dosen{
    private $username; 
    private $password;
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
    public function __construct($username, $password, $npk,$foto_extention) {
        $this->setUsername($username);
        $this->setPassword($password);
        $this->setNPK($npk);
        $this->setFotoExtention($foto_extention);
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
     * Memberikan nilai kode npk Dosen
     * @return string 
     */
    public function getNPK() {
        return $this->npk;
    }

    /**
     * apakah maksudnya yang ini ga tau aku. (NOTES: ditanyakan!!!!)
     * @return string 
     */
    public function getFotoExtention() {
        return $this->foto_extention;
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
     * Menyimpan nilai npk kedalam class
     * @param string $npk
     */
    public function setNPK($npk) {
        $this->npk = $npk;
    }
    /**
     * Menyimpan ??? kedalam class
     * @param string $foto_extention
     */
    public function setFotoExtention($foto_extention) {
        $this->foto_extention = $foto_extention;
    }
}
?>