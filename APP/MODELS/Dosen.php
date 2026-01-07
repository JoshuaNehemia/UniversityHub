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

class Dosen extends Akun
{

    #region FIELDS
    private $npk;
    private $foto_extention;
    private $foto_address;
    #endregion
    #region CONSTRUCTOR
    public function __construct()
    {
        parent::__construct();
    }
    #endregion

    #region GETTERS
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
    #endregion

    #region SETTER
    public function setNPK(string $npk)
    {
        if (empty($npk))
            throw new Exception("NPK tidak dapat kosong");
        $this->npk = $npk;
    }

    public function setFotoExtention(string $foto_extention)
    {
        $foto_extention = strtolower($foto_extention);

        if (empty($foto_extention))
            throw new Exception("Extention tidak dapat kosong");

        if (!in_array($foto_extention, ALLOWED_PICTURE_EXTENSION))
            throw new Exception("Extention illegal: " . implode(', ', ALLOWED_PICTURE_EXTENSION));

        $this->foto_extention = $foto_extention;
    }

    public function setFotoAddress($address)
    {
        $this->foto_address = $address;
    }
    #endregion

    #region UTILITIES
    /**
     * Merubah data object class Akun dari serializable object menjadi PHP Array
     * @return array data kelas dalam array.
     */
    public function toArray(): array
    {
        return array_merge(
            parent::toArray(),
            array(
                "npk" => $this->getNPK(),
                "foto_extention" => $this->getFotoExtention()
            )
        );
    }

}
