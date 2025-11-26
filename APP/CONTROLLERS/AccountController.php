<?php

namespace CONTROLLERS;

require_once(__DIR__ . "/../MODELS/Dosen.php");
require_once(__DIR__ . "/../MODELS/Mahasiswa.php");
require_once(__DIR__ . "/../config.php");

use MODELS\Mahasiswa;
use MODELS\Dosen;

class AccountController
{
    public function __construct() {}

    public function getMahasiswaList($limit, $offset): array
    {
        $list = Mahasiswa::mahasiswaGetAll($limit, $offset);
        foreach ($list as $key => $mhs) {
            $list[$key] = $mhs->getArray();
        }
        return $list;
    }
    public function getMahasiswaListByName($limit, $offset, $keyword): array
    {
        $list = Mahasiswa::mahasiswaGetAllByName($limit, $offset, $keyword);
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

    public function getDosenList($limit, $offset): array
    {
        $list = Dosen::dosenGetAll($limit, $offset);
        foreach ($list as $key => $mhs) {
            $list[$key] = $mhs->getArray();
        }
        return $list;
    }
    public function getDosenListByName($limit, $offset, $keyword): array
    {
        $list = Dosen::dosenGetAllByName($limit, $offset, $keyword);
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
