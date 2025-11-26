<?php

namespace CONTROLLERS;

require_once(__DIR__ . "/../MODELS/Dosen.php");
require_once(__DIR__ . "/../MODELS/Mahasiswa.php");
require_once(__DIR__ . "/../config.php");

use MODELS\Mahasiswa;
use MODELS\Dosen;
use Exception;

class AccountController
{
    public function __construct() {}

    public function getMahasiswaList($limit, $offset)
    {
        $list = Mahasiswa::mahasiswaGetAll($limit, $offset);
        foreach ($list as $key => $mhs) {
            $list[$key] = $mhs->getArray();
        }
        return $list;
    }
    public function getMahasiswaListByName($limit, $offset, $keyword)
    {
        $list = Mahasiswa::mahasiswaGetAllByName($limit, $offset, $keyword);
        foreach ($list as $key => $mhs) {
            $list[$key] = $mhs->getArray();
        }
        return $list;
    }
    public function getSingleMahasiswaByUsername($username)
    {
        return Mahasiswa::mahasiswaGetByUsername($username)->getArray();
    }

        public function getDosenList($limit, $offset)
    {
        $list = Dosen::dosenGetAll($limit, $offset);
        foreach ($list as $key => $mhs) {
            $list[$key] = $mhs->getArray();
        }
        return $list;
    }
    public function getDosenListByName($limit, $offset, $keyword)
    {
        $list = Dosen::dosenGetAllByName($limit, $offset, $keyword);
        foreach ($list as $key => $mhs) {
            $list[$key] = $mhs->getArray();
        }
        return $list;
    }
}
