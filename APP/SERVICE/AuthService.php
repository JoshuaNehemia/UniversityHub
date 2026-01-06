<?php

namespace SERVICE;

#region REQUIRE
require_once(__DIR__ ."/../config.php");
require_once(__DIR__ ."/../MODELS/Akun.php");
require_once(__DIR__ ."/../MODELS/Mahasiswa.php");
require_once(__DIR__ ."/../MODELS/Dosen.php");
require_once(__DIR__ ."/../REPOSITORY/RepoAccount.php");
#endregion

#region USE
use MODELS\Akun;
use MODELS\Dosen;
use MODELS\Mahasiswa;
use REPOSITORY\RepoAccount;
#endregion

class AuthService{
    #region FIELDS
    private $repo_account;
    #endregion

    #region CONSTRUCTOR
    public function __construct(){
        $this->repo_account = new RepoAccount();
    }
    #endregion

    #region FUNCTION
    public function login($username, $password): Akun|Dosen|Mahasiswa{
        return $this->repo_account->login($username, $password);
    }
    #endregion
}