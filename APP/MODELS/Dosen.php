<?php

namespace MODELS;

require_once(__DIR__ . '/Akun.php');
require_once(__DIR__ . '/../upload.php');

use MODELS\Akun;
use Exception;

class Dosen extends Akun
{
    private $npk;
    private $foto_extention;
    private $foto_address;

    
    // ================================================================================================
    // CONSTRUCTOR
    // ================================================================================================
    public function __construct($username, $nama, $npk, $foto_extention)
    {
        parent::__construct($username, $nama, "DOSEN");
        $this->setNPK($npk);
        $this->setFotoExtention($foto_extention);
    }


    // ================================================================================================
    // GETTER
    // ================================================================================================
    public function getNPK()
    {
        return $this->npk;
    }

    public function getFotoExtention()
    {
        return $this->foto_extention;
    }

    public function getFotoAddress()
    {
        return $this->foto_address;
    }

    // ================================================================================================
    // SETTER
    // ================================================================================================
    public function setNPK(string $npk)
    {
        if(empty($npk)) throw new Exception("NPK tidak dapat kosong");
        $this->npk = $npk;
    }

    public function setFotoExtention(string $foto_extention)
    {
        $foto_extention = strtolower($foto_extention);
        if(empty($foto_extention)) throw new Exception("Extention tidak dapat kosong");
        if(!in_array($foto_extention,ALLOWED_PICTURE_EXTENSION))  throw new Exception("Extention illegal, Upload file berupa: " . implode(', ', ALLOWED_PICTURE_EXTENSION));
        $this->foto_extention = $foto_extention;
    }

    public function setFotoAddress($address)
    {
        $this->foto_address = $address;
    }
}
