<?php 

namespace CONTROLLERS;

require_once(__DIR__ . "/../MODELS/Group.php");
require_once(__DIR__ . "/../config.php");

use MODELS\Group;

class GroupController{
    public function __construct()
    {}

    public function createGroup(array $group){
        $group = Group::readArray($group);
        return $group->create()->getArray();
    }

    public function editGroup(array $arr_group){
        $group = new Group();
        $group->setId($arr_group['id']);
        $group->setNama($arr_group['nama']);
        $group->setDeskripsi($arr_group['deskripsi']);
        $group->setJenis($arr_group['jenis']);
        $group->setKode($arr_group['kode']);
        return $group->update()->getArray();
    }

    public function getSingleGroup($id){
        return Group::getGroupById($id)->getArray();
    }

    public function getListGroupByName($limit,$offset,$keyword){
        $list = Group::getAllGroupByName($limit,$offset,$keyword);
        foreach($list as $key => $value){
            $list[$key] = $value->getArray();
        }
        return $list;
    }

    public function deleteGroup($id){
        $g = new Group();
        $g->setId($id);
        return $g->delete();
    }

    public function getAllGroupJoinedByUser($username, $limit, $offset, $keyword = ""){
        $list = Group::getAllGroupJoinedByUser($username, $limit, $offset, $keyword);
        
        foreach ($list as $key => $value){
            $list[$key] = $value->getArray();
        }

        return $list;
    }

    public function addMemberToGroup($idgrup, $username){
        $g = new Group();
        $g->setId($idgrup);
        return $g->addMember($username);
    }

    public function removeMemberFromGroup($idgrup, $username){
        $g = new Group();
        $g->setId($idgrup);
        return $g->deleteMember($username);
    }

    public function getAllMemberOfGroup($idgrup){
        $g = new Group();
        $g->setId($idgrup);
        return $g->getAllMember();
    }

    public function checkGroupMember($idgrup, $username){
        return Group::isMember($idgrup, $username);
    }
}