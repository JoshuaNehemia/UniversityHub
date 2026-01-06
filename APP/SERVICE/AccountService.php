<?php

namespace SERVICE;

#region REQUIRE
require_once(__DIR__ . "/../config.php");
require_once(__DIR__ . "/../MODELS/Akun.php");
require_once(__DIR__ . "/../MODELS/Mahasiswa.php");
require_once(__DIR__ . "/../MODELS/Dosen.php");
require_once(__DIR__ . "/../REPOSITORY/RepoAccount.php");
#endregion

#region USE
use MODELS\Akun;
use MODELS\Dosen;
use MODELS\Mahasiswa;
use REPOSITORY\RepoAccount;
#endregion

class AccountService
{
    #region FIELDS
    private $repo_account;
    #endregion

    #region CONSTRUCTOR
    public function __construct()
    {
        $this->repo_account = new RepoAccount();
    }
    #endregion

    #region CREATE
    public function createAccount($akun, string $raw_password)
    {
        $hashed = password_hash($raw_password, PASSWORD_DEFAULT);

        if ($akun instanceof Dosen) {
            // create dosen account
            $result = $this->repo_account->createDosen($akun, $hashed);
        } else if ($akun instanceof Mahasiswa) {
            // create mahasiswa account
            $result = $this->repo_account->createMahasiswa($akun, $hashed);
        }
        return $result;
    }
    #endregion

    #region UPDATE   
    public function updateAccount($akun, string $raw_password)
    {
        $hashed = password_hash($raw_password, PASSWORD_DEFAULT);

        if ($akun instanceof Dosen) {
            // create dosen account
            $result = $this->repo_account->updateDosen($akun);
        } else if ($akun instanceof Mahasiswa) {
            // create mahasiswa account
            $result = $this->repo_account->updateMahasiswa($akun);
        }
        return $result;
    }

    public function updatePassword(string $username, string $raw_password)
    {
        $hashed = password_hash($raw_password, PASSWORD_DEFAULT);
        return $this->repo_account->updateAccount($username, $hashed);
    }
    #endregion

    #region DELETE
    public function deleteAccount($akun, string $raw_password)
    {
        $hashed = password_hash($raw_password, PASSWORD_DEFAULT);

        $result = $this->repo_account->deleteAkun($akun->getUsername());
        if ($akun instanceof Dosen) {
            // delete dosen account
            $result = $this->repo_account->deleteDosen($akun->getNPK());
        } else if ($akun instanceof Mahasiswa) {
            // delete mahasiswa account
            $result = $this->repo_account->deleteMahasiswa($akun->getNRP());
        }
        return $result;
    }
    #endregion
}