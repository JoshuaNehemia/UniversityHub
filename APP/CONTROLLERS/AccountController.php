<?php

namespace CONTROLLERS;

#region REQUIRE
require_once(__DIR__ . "/../SERVICE/AccountService.php");
require_once(__DIR__ . "/../MODELS/Akun.php");
require_once(__DIR__ . "/../MODELS/Dosen.php");
require_once(__DIR__ . "/../MODELS/Mahasiswa.php");
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
    public function __construct()
    {
        $this->service = new AccountService();
    }
    #endregion

    #region CREATE
    public function createAccount(array $data)
    {
        $acc = $this->mapAccountObject($data);
        return $this->service->createAccount($acc, $data['raw_password']);
    }
    #endregion

    #region UPDATE
    public function updateAccount(array $data)
    {
        if (isset($data['new_password'])) {
            return $this->service->updatePassword($data['username'], $data['new_password']);
        } else {
            $acc = $this->mapAccountObject($data);
            return $this->service->updateAccount($acc);
        }
    }
    #endregion

    #region DELETE
    public function deleteAccount(array $data)
    {
        if (isset($data['nrp'])) {
            return $this->service->deleteMahasiswa($data['nrp']);
        } else if (isset($data['npk'])) {
            return $this->service->deleteDosen($data['npk']);
        } else {
            throw new Exception("Failed to delete account: Data is incomplete.");
        }
    }
    #endregion

    #region MAPPER
    private function assertArrayComplete(
        array $row,
        array $requiredKeys,
        string $context = 'Data'
    ): void {
        $missing = [];
        $nulls = [];

        foreach ($requiredKeys as $key) {
            if (!array_key_exists($key, $row)) {
                $missing[] = $key;
            } elseif ($row[$key] === null) {
                $nulls[] = $key;
            }
        }

        if ($missing || $nulls) {
            $message = $context . ' data is invalid.';

            if ($missing) {
                $message .= ' Missing data: ' . implode(', ', $missing) . '.';
            }

            if ($nulls) {
                $message .= ' Null values: ' . implode(', ', $nulls) . '.';
            }

            throw new Exception($message);
        }
    }

    private function mapAccountObject(array $row): Akun|Dosen|Mahasiswa
    {
        if (isset($row['nrp'])) {
            $this->assertArrayComplete(
                $row,
                [
                    'username',
                    'nama',
                    'nrp',
                    'tanggal_lahir',
                    'gender',
                    'angkatan',
                    'foto_extention'
                ],
                'Mahasiswa data'
            );

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
            $this->assertArrayComplete(
                $row,
                [
                    'username',
                    'nama',
                    'npk',
                    'foto_extention'
                ],
                'Dosen data'
            );

            $d = new Dosen();
            $d->setUsername($row['username']);
            $d->setNama($row['nama']);
            $d->setJenis(ACCOUNT_ROLE[1]);
            $d->setNPK($row['npk']);
            $d->setFotoExtention($row['foto_extention']);

            return $d;
        }

        if (isset($row['isadmin']) && $row['isadmin'] === 1) {
            $this->assertArrayComplete(
                $row,
                ['username'],
                'Admin data'
            );

            $a = new Akun();
            $a->setUsername($row['username']);
            $a->setJenis(ACCOUNT_ROLE[2]);
            $a->setNama('ADMIN');

            return $a;
        }

        throw new Exception('Invalid account type');
    }

    #endregion
}
