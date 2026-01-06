<?php

namespace CONTROLLERS;

#region REQUIRE
require_once(__DIR__ ."/../SERVICE/AccountService.php");
require_once(__DIR__ ."/../MODELS/Akun.php");
require_once(__DIR__ ."/../MODELS/Dosen.php");
require_once(__DIR__ ."/../MODELS/Mahasiswa.php");
#endregion

#region USE
use SERVICE\AccountService;
use MODELS\Akun;
use MODELS\Dosen;
use MODELS\Mahasiswa;
use Exception;
#endregion

class AccountController
{
    #region FIELDS
    private $service;
    #endregion

    #region CONSTRUCTOR
    public function __construct(){
        $this->service = new AccountService();
    }
    #endregion

    #region CREATE
    public function createAccount(array $data){
        $acc = $this->mapAccountObject($data);
        return $this->service->createAccount($acc,$data['raw_password']);
    }
    #endregion

    #region UPDATE

    #endregion

    #region DELETE

    #endregion

    #region MAPPER
    private function mapAccountObject(array $row): Akun|Dosen|Mahasiswa
    {
        if (isset($row['nrp'])) {
            $m = new Mahasiswa();
            $m->setUsername($row['username']);
            $m->setNama($row['nama']);
            $m->setJenis(ACCOUNT_ROLE[0]);
            $m->setNRP($row['nrp']);
            $m->setTanggalLahir($row['tanggal_lahir']);
            $m->setGender($row['gender']);
            $m->setAngkatan($row['angkatan']);
            $m->setFotoExtention($row['foto_extention']);
            return $m;
        }

        if (isset($row['npk'])) {
            $d = new Dosen();
            $d->setUsername($row['username']);
            $d->setNama($row['nama']);
            $d->setJenis(ACCOUNT_ROLE[1]);
            $d->setNPK($row['npk']);
            $d->setFotoExtention($row['foto_extention']);
            return $d;
        }

        if (isset($row['isadmin']) && $row['isadmin'] === 1) {
            $a = new Akun();
            $a->setUsername($row['username']);
            $a->setJenis(ACCOUNT_ROLE[2]);
            $a->setNama("ADMIN");
            return $a;
        }

        throw new Exception("Invalid account type");
    }
    #endregion
}
