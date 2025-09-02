<?php
namespace MODEL;

class Mahasiswa{
    private $username; 
    private $password;
    private $nrp;
    private $tanggal_lahir;
    private $gender;
    private $angkatan;
    private $foto_extention;

    /**
     * Constructor untuk Class Mahasiswa
     *
     * @param string $username Menyimpan nama akun
     * @param string $password Menyimpan password akun
     * @param string $nrp Menyimpan kode NRP mahasiswa
     * @param string $tanggal_lahir Menyimpan tanggal lahir mahasiswa
     * @param int $angkatan Menyimpan tahun angkatan mahasiswa
     * @param string $foto_extention Menyimpan ???????? (opo iki)
     */
    public function __construct($username, $password, $nrp, $tanggal_lahir,$gender, $angkatan, $foto_extention) {
        $this->setUsername($username);
        $this->setPassword($password);
        $this->setNRP($nrp);
        $this->setTanggalLahir($tanggal_lahir);
        $this->setGender($gender);
        $this->setAngkatan($angkatan);
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
     * Memberikan nilai kode NRP mahasiswa
     * @return string 
     */
    public function getNRP() {
        return $this->nrp;
    }

    /**
     * Memberikan nilai tanggal lahir mahasiswa
     * @return string 
     */
    public function getTanggalLahir() {
        return $this->tanggal_lahir;
    }

    /**
     * Memberikan nilai tahun angkatan mahasiswa
     * @return int 
     */
    public function getAngkatan() {
        return $this->angkatan;
    }

    /**
     * apakah maksudnya yang ini ga tau aku. (NOTES: ditanyakan!!!!)
     * @return string 
     */
    public function getFotoExtention() {
        return $this->foto_extention;
    }
    /**
     * Memberikan gender mahasaiswa
     * @return string 
     */
    public function getGender() {
        return $this->gender;
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
     * Menyimpan nilai nrp kedalam class
     * @param string $nrp
     */
    public function setNRP($nrp) {
        $this->nrp = $nrp;
    }

    /**
     * Menyimpan tanggal lahir kedalam class
     * @param string $tanggal_lahir
     */
    public function setTanggalLahir($tanggal_lahir) {
        $this->tanggal_lahir = $tanggal_lahir;
    }

    /**
     * Menyimpan tahun angkatan kedalam class
     * @param int $angkatan
     */
    public function setAngkatan($angkatan) {
        $this->angkatan = $angkatan;
    }

    /**
     * Menyimpan ??? kedalam class
     * @param string $foto_extention
     */
    public function setFotoExtention($foto_extention) {
        $this->foto_extention = $foto_extention;
    }

    /**
     * Menyimpan gender mahasiswa ('Pria','Wanita') kedalam class
     * @param string $foto_extention
     */
    public function setGender($gender) {
        $this->gender = $gender;
    }
}
?>