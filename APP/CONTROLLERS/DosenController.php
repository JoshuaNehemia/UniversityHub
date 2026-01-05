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
            $list[$key] = $mhs->toArray();
        }
        return $list;
    }

    public function getSingleDosenByUsername($username): array
    {
        return Dosen::dosenGetByUsername($username)->toArray();
    }

    public function updateDosen(array $dosen): array
    {
        $dosen = Dosen::readArray($dosen);
        return $dosen->dosenUpdate()->toArray();
    }

    public function createDosen(array $arr_dosen): array
    {
        $dosen = Dosen::readArray($arr_dosen);
        return $dosen->dosenCreate($arr_dosen['password'])->toArray();
    }

    public function deleteDosen(array $dosen)
    {
        $dosen = Dosen::readArray($dosen);
        $dosen->dosenDelete();
    }
}
