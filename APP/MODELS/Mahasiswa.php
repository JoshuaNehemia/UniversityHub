<?php

namespace MODELS;

require_once(__DIR__ . '/Akun.php');
require_once(__DIR__ . '/../CORE/upload.php');

use MODELS\Akun;
use Exception;

class Mahasiswa extends Akun
{
    private $nrp;
    private $tanggal_lahir;
    private $gender;
    private $angkatan;
    private $foto_extention;
    private $foto_address;

    // ================================================================================================
    // CONSTRUCTOR
    // ================================================================================================
    public function __construct($username, $nama, $nrp, $tanggal_lahir, $gender, $angkatan, $foto_extention)
    {
        parent::__construct($username, $nama, "MAHASISWA");
        $this->setNRP($nrp);
        $this->setTanggalLahir($tanggal_lahir);
        $this->setGender($gender);
        $this->setAngkatan($angkatan);
        $this->setFotoExtention($foto_extention);
    }


    // ================================================================================================
    // GETTER
    // ================================================================================================
    public function getNRP()
    {
        return $this->nrp;
    }

    public function getTanggalLahir()
    {
        return $this->tanggal_lahir;
    }

    public function getAngkatan()
    {
        return $this->angkatan;
    }

    public function getFotoExtention()
    {
        return $this->foto_extention;
    }

    public function getGender()
    {
        return $this->gender;
    }

    public function getFotoAddress()
    {
        return $this->foto_address;
    }

    // ================================================================================================
    // SETTER
    // ================================================================================================
    public function setNRP(string $nrp)
    {
        if(empty($nrp)) throw new Exception("NRP tidak dapat kosong");
        $this->nrp = $nrp;
    }

    public function setTanggalLahir(string $tanggal_lahir)
    {
        if(empty($nrp)) throw new Exception("NRP tidak dapat kosong");
        $this->tanggal_lahir = $tanggal_lahir;
    }

    public function setAngkatan(int $angkatan)
    {
        if(empty($angkatan)) throw new Exception("Tahun angkatan tidak dapat kosong");
        if($angkatan <=1900) throw new Exception("Tahun angkatan tidak dapat lebih kecil dari 1900");
        $this->angkatan = $angkatan;
    }

    public function setFotoExtention(string $foto_extention)
    {
        $foto_extention = strtolower($foto_extention);
        if(empty($foto_extention)) throw new Exception("Extention tidak dapat kosong");
        if(!in_array($foto_extention,ALLOWED_PICTURE_EXTENSION))  throw new Exception("Extention illegal, Upload file berupa: " . implode(', ', ALLOWED_PICTURE_EXTENSION));
        $this->foto_extention = $foto_extention;
    }

    public function setGender(string $gender)
    {
        $gender = strtoupper($gender);
        if(empty($gender)) throw new Exception("Gender tidak dapat kosong");
        if(!in_array($gender,GENDER))
        $this->gender = $gender;
    }

    public function setFotoAddress($address)
    {
        $this->foto_address = $address;
    }

}
