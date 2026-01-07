<?php

namespace CONTROLLERS;

#region REQUIRE
require_once(__DIR__ . "/../SERVICE/MemberService.php");
require_once(__DIR__ . "/../config.php");

#endregion

#region USE
use SERVICE\MemberService;
use Exception;

#endregion

class MemberController
{
    #region FIELDS
    private $service;
    #endregion

    #region CONSTRUCTOR
    public function __construct()
    {
        $this->service = new MemberService();
    }
    #endregion

    public function getMember($data)
    {
        $this->checkData($data);
        return $this->service->getGroupMember($data['idgrup']);
    }

    public function addMember($data)
    {
        $this->checkData($data);
        return $this->service->addGroupMember($data['idgrup'], $data['username']);
    }

    public function deleteMember($data)
    {
        $this->checkData($data);
        return $this->service->deleteGroupMember($data['idgrup'], $data['username']);
    }

    private function checkData($data){
        if(!isset($data['idgrup'])) throw new Exception("Data incomplete: No group data sent to add member");
        if(!isset($data['username'])) throw new Exception("Data incomplete: No account data sent to add member");
    }
}