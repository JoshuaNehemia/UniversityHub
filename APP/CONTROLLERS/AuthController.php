<?php

namespace CONTROLLERS;

require_once(__DIR__ . "/../MODELS/Akun.php");
require_once(__DIR__ . "/../MODELS/Dosen.php");
require_once(__DIR__ . "/../MODELS/Mahasiswa.php");
require_once(__DIR__ . "/../MODELS/Group.php");
require_once(__DIR__ . "/../MODELS/Event.php");
require_once(__DIR__ . "/../config.php");

use MODELS\Akun;
use MODELS\Mahasiswa;
use MODELS\Dosen;
use MODELS\Group;
use MODELS\Event;
use Exception;

class AuthController
{
    public function __construct() {}

    public function login($username, $password): array
    {

        $jenis = Akun::akunRetrieveRole($username);
        if (!isset($jenis)) {
            throw new Exception("Akun anda tidak tercatat di database, mohon hubungi admin untuk pembuatan akun anda");
        }
        $akun = $this->assignLogin($username, $password, $jenis);
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

    public function accountChangePassword($username, $old_password, $new_password, $confim_password)
    {
        try {
            $akun = Akun::akunLogin($username, $old_password);
        } catch (Exception $e) {
            throw new Exception("Password lama salah");
        };
        if ($confim_password != $new_password) throw new Exception("Password konfim dan password baru harus sama");
        return $akun->akunUpdatePassword($new_password);
    }


    public function getAllGroupJoinedByUser($username, $limit, $offset, $keyword = "")
    {
        $list = Group::getAllGroupJoinedByUser($username, $limit, $offset, $keyword);
        foreach ($list as $key => $value) {
            $list[$key] = $value->getArray();
        }
        return $list;
    }

    public function getAllUserEvent($username, $keyword = "", $limit, $offset)
    {
        $list = EVent::getAllUserEvent($username, $keyword, $limit, $offset);
        foreach ($list as $key => $value) {
            $list[$key] = $value->getArray();
        }
        return $list;
    }
}
