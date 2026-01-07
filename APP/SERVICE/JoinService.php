<?php

namespace SERVICE;

#region REQUIRE
require_once(__DIR__ ."/../REPOSITORY/RepoGroup.php");
require_once(__DIR__ ."/../REPOSITORY/RepoMember.php");
#endregion

#region USE
use REPOSITORY\RepoGroup;
use REPOSITORY\RepoMember;
use Exception;
#endregion

class JoinService{
    #region FIELDS
    private $repo_member;
    private $repo_group;
    #endregion

    #region CONSTRUCTOR
    public function __construct(){
        $this->repo_member = new RepoMember();
        $this->repo_group = new RepoGroup();
    }
    #endregion

    #region FUNCTION
    public function joinGroup($id_group,$username,$code){
        $group = $this->repo_group->findGroupById($id_group);
        if($group->getKode() !== $code){
            throw new Exception("Failed to join wrong join code");
        }
        return $this->repo_member->addMember($group->getId(),$username);
    }
    #endregion
}