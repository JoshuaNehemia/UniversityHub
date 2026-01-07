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

class UserService
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

    #region FUNCTION
    public function addGroup($group)
    {

    }

    public function getGroup($group_id)
    {

    }

    public function updateGroup($group)
    {

    }
    public function deleteGroup($group)
    {

    }
    #endregion
}