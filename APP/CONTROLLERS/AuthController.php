<?php

namespace CONTROLLERS;

require_once(__DIR__ . "/../MODELS/Akun.php");
require_once(__DIR__ . "/../MODELS/Dosen.php");
require_once(__DIR__ . "/../MODELS/Mahasiswa.php");
require_once(__DIR__ . "/../config.php");

use MODELS\Akun;
use MODELS\Mahasiswa;
use MODELS\Dosen;
use Exception;

class AuthController
{
    public function __construct() {}

    public function login($username, $password): array
    {

        $jenis = Akun::akunRetrieveRole($username, $password);
        if(!isset($jenis)){
            throw new Exception("Akun anda tidak tercatat di database, mohon hubungi admin untuk pembuatan akun anda");
        }
        $akun = $this->assignLogin($username,$password,$jenis);
        return $akun->getArray();
    }

    private function assignLogin($username, $password, $jenis)
    {
        switch ($jenis) {
            case ACCOUNT_ROLE[0]:
                return Mahasiswa::mahasiswaLogin($username, $password);
                break;
            case ACCOUNT_ROLE[1]:
                return Dosen::dosenLogin($username, $password);
                break;
            case ACCOUNT_ROLE[2]:
                return Akun::akunLogin($username, $password);
                break;
            default:
                throw new Exception("Terjadi kesalahan dengan akun anda, mohon hubungi admin");
                break;
        }
    }
}
