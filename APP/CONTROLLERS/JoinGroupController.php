<?php

namespace CONTROLLERS;

require_once(__DIR__ . "/../MODELS/Group.php");
require_once(__DIR__ . "/../config.php");

use MODELS\Group;
use Exception;

class JoinGroupController
{
    public function __construct() {}

    public function joinGroup($idgroup,$username,$kode){
        $g = Group::getGroupById($idgroup);
        if($g->getKode()!==$kode ){
            throw new Exception("Gagal join group, kode yang dimasukan salah.");
        }
        if($g->getJenis() !== GROUP_TYPES[1]){
            throw new Exception("Gagal join group, group bersifat privat.");
        }
        $g->addMember($username);
        return true;
    }

    public function checkUserJoined($idgroup,$username){
        $g = new Group();
        $g->setId($idgroup);
        return $g->isMember($idgroup,$username);
    }
}
