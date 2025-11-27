<?php

namespace CONTROLLERS;

require_once(__DIR__ . "/../MODELS/Mahasiswa.php");
require_once(__DIR__ . "/../config.php");

use MODELS\Mahasiswa;

class MahasiswaController
{
    public function __construct() {}

    public function getListMahasiswaByNama($limit, $offset, $keyword): array
    {
        $list = Mahasiswa::mahasiswaGetAllByName($limit, $offset, $keyword);
        foreach ($list as $key => $mhs) {
            $list[$key] = $mhs->getArray();
        }
        return $list;
    }
    public function getListMahasiswaByNRP($limit, $offset, $keyword): array
    {
        $list = Mahasiswa::mahasiswaGetAllByNRP($limit, $offset, $keyword);
        foreach ($list as $key => $mhs) {
            $list[$key] = $mhs->getArray();
        }
        return $list;
    }
    
    public function getSingleMahasiswaByUsername($username): array
    {
        return Mahasiswa::mahasiswaGetByUsername($username)->getArray();
    }

    public function updateMahasiswa(array $mahasiswa): array
    {
        $mahasiswa = Mahasiswa::readArray($mahasiswa);
        return $mahasiswa->mahasiswaUpdate()->getArray();
    }

    public function createMahasiswa(array $arr_mahasiswa): array
    {
        $mahasiswa = Mahasiswa::readArray($arr_mahasiswa);
        return $mahasiswa->mahasiswaCreate($arr_mahasiswa['password'])->getArray();
    }

    public function deleteMahasiswa(array $mahasiswa)
    {
        $mahasiswa = Mahasiswa::readArray($mahasiswa);
        $mahasiswa->mahasiswaDelete();
    }
}
