<?php

namespace MODELS;

#region REQUIRE
require_once(__DIR__ . '/Akun.php');
require_once(__DIR__ . '/../config.php');
#endregion

#region USE
use MODELS\Akun;
use Exception;
#endregion

class Mahasiswa extends Akun
{
    #region FIELDS
    private $nrp;
    private $tanggal_lahir;
    private $gender;
    private $angkatan;
    private $foto_extention;
    private $foto_address;
    #endregion

    #region CONSTRUCTOR
    public function __construct(
        $username = null,
        $nama = null,
        $nrp = null,
        $tanggal_lahir = null,
        $gender = null,
        $angkatan = null,
        $foto_extention = null
    ) {
        parent::__construct($username, $nama, ACCOUNT_ROLE[0]);

        if ($nrp !== null)
            $this->setNRP($nrp);
        if ($tanggal_lahir !== null)
            $this->setTanggalLahir($tanggal_lahir);
        if ($gender !== null)
            $this->setGender($gender);
        if ($angkatan !== null)
            $this->setAngkatan($angkatan);
        if ($foto_extention !== null)
            $this->setFotoExtention($foto_extention);
    }
    #endregion
    #region GETTER
    public function getNRP()
    {
        return $this->nrp;
    }
    public function getTanggalLahir()
    {
        return $this->tanggal_lahir;
    }
    public function getGender()
    {
        return $this->gender;
    }
    public function getAngkatan()
    {
        return $this->angkatan;
    }
    public function getFotoExtention()
    {
        return $this->foto_extention;
    }
    public function getFotoAddress()
    {
        return $this->foto_address;
    }
    #endregion

    #region SETTER
    public function setNRP(string $nrp)
    {
        if (empty($nrp))
            throw new Exception("NRP tidak boleh kosong.");
        $this->nrp = $nrp;
    }

    public function setTanggalLahir(string $tanggal_lahir)
    {
        if (!preg_match(DATE_REGEX, $tanggal_lahir)) {
            throw new Exception("Format tanggal lahir invalid. Format yang benar: YYYY-MM-DD (Y-m-d)");
        }
        if (empty($tanggal_lahir))
            throw new Exception("Tanggal lahir tidak boleh kosong.");
        $this->tanggal_lahir = $tanggal_lahir;
    }

    public function setGender(string $gender)
    {
        $gender = ucfirst(strtolower($gender));
        if (!in_array($gender, GENDER))
            throw new Exception("Gender tidak valid.");
        $this->gender = $gender;
    }

    public function setAngkatan(int $angkatan)
    {
        if (empty($angkatan))
            throw new Exception("Tahun angkatan tidak boleh kosong.");
        $this->angkatan = $angkatan;
    }

    public function setFotoExtention(string $foto_extention)
    {
        $foto_extention = strtolower($foto_extention);
        if (!in_array($foto_extention, ALLOWED_PICTURE_EXTENSION)) {
            throw new Exception("Ekstensi foto tidak valid.");
        }
        $this->foto_extention = $foto_extention;
    }

    public function setFotoAddress($address)
    {
        $this->foto_address = $address;
    }
    #endregion

    #region UTILITIES
    public function toArray(): array
    {
        return array_merge(
            parent::toArray(),
            array(
                "nrp" => $this->getNRP(),
                "tanggal_lahir" => $this->getTanggalLahir(),
                "gender" => $this->getGender(),
                "angkatan" => $this->getAngkatan(),
                "foto_extention" => $this->getFotoExtention()
            )
        );
    }
}
