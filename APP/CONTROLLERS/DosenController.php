<?php

namespace CONTROLLERS;

require_once(__DIR__ . "/../MODELS/Dosen.php");
require_once(__DIR__ . "/../config.php");

use MODELS\Dosen;

class DosenController
{
    public function __construct() {}

    public function getListDosenByName($limit, $offset,$keyword): array
    {
        $list = Dosen::dosenGetAllByName($limit, $offset,$keyword);
        foreach ($list as $key => $mhs) {
            $list[$key] = $mhs->getArray();
        }
        return $list;
    }

    public function getSingleDosenByUsername($username): array
    {
        return Dosen::dosenGetByUsername($username)->getArray();
    }

    public function updateDosen(array $dosen): array
    {
        $dosen = Dosen::readArray($dosen);
        return $dosen->dosenUpdate()->getArray();
    }

    public function createDosen(array $arr_dosen): array
    {
        $dosen = Dosen::readArray($arr_dosen);
        return $dosen->dosenCreate($arr_dosen['password'])->getArray();
    }

    public function deleteDosen(array $dosen)
    {
        $dosen = Dosen::readArray($dosen);
        $dosen->dosenDelete();
    }
}
