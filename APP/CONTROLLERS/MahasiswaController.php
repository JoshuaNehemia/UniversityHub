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
            $list[$key] = $mhs->toArray();
        }
        return $list;
    }
    public function getListMahasiswaByNRP($limit, $offset, $keyword): array
    {
        $list = Mahasiswa::mahasiswaGetAllByNRP($limit, $offset, $keyword);
        foreach ($list as $key => $mhs) {
            $list[$key] = $mhs->toArray();
        }
        return $list;
    }
    
    public function getSingleMahasiswaByUsername($username): array
    {
        return Mahasiswa::mahasiswaGetByUsername($username)->toArray();
    }

    public function updateMahasiswa(array $mahasiswa): array
    {
        $mahasiswa = Mahasiswa::readArray($mahasiswa);
        return $mahasiswa->mahasiswaUpdate()->toArray();
    }

    public function createMahasiswa(array $arr_mahasiswa): array
    {
        $mahasiswa = Mahasiswa::readArray($arr_mahasiswa);
        return $mahasiswa->mahasiswaCreate($arr_mahasiswa['password'])->toArray();
    }

    public function deleteMahasiswa(array $mahasiswa)
    {
        $mahasiswa = Mahasiswa::readArray($mahasiswa);
        $mahasiswa->mahasiswaDelete();
    }
}
