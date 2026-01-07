<?php

namespace SERVICE;

#region REQUIRE
require_once(__DIR__ ."/../REPOSITORY/RepoMember.php");
#endregion

#region USE
use REPOSITORY\RepoMember;
#endregion

class MemberService{
    #region FIELDS
    private $repo;
    #endregion

    #region CONSTRUCTOR
    public function __construct(){
        $this->repo = new RepoMember();
    }
    #endregion

    #region FUNCTION
    public function getGroupMember($group_id){
        return $this->repo->findGroupMember($group_id);
    }

    public function addGroupMember($group_id,$username){
        return $this->repo->addMember($group_id,$username);
    }

    public function deleteGroupMember($group_id,$username){
        return $this->repo->deleteMember($group_id,$username);
    }
    #endregion
}