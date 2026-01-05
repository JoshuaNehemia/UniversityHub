<?php

namespace CONTROLLERS;

require_once(__DIR__ . "/../MODELS/Group.php");
require_once(__DIR__ . "/../config.php");

use MODELS\Group;

class MemberController
{
    public function __construct() {}

    public function getAllGroupJoinedByUser($username, $limit, $offset, $keyword = "")
    {
        $list = Group::getAllGroupJoinedByUser($username, $limit, $offset, $keyword);

        foreach ($list as $key => $value) {
            $list[$key] = $value->toArray();
        }

        return $list;
    }

    public function addMemberToGroup($idgrup, $username)
    {
        $g = new Group();
        $g->setId($idgrup);
        return $g->addMember($username);
    }

    public function removeMemberFromGroup($idgrup, $username)
    {
        $g = new Group();
        $g->setId($idgrup);
        return $g->deleteMember($username);
    }

    public function getAllMemberOfGroup($idgrup)
    {
        $g = new Group();
        $g->setId($idgrup);
        return $g->getAllMember();
    }

    public function checkGroupMember($idgrup, $username)
    {
        return Group::isMember($idgrup, $username);
    }
    
}
