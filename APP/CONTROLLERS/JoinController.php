<?php

namespace CONTROLLERS;

#region REQUIRE
require_once(__DIR__ . "/../SERVICE/JoinService.php");
require_once(__DIR__ . "/../config.php");

#endregion

#region USE
use SERVICE\JoinService;
use Exception;

#endregion

class JoinController
{
    #region FIELDS
    private $service;
    #endregion

    #region CONSTRUCTOR
    public function __construct()
    {
        $this->service = new JoinService();
    }
    #endregion

    public function join($data)
    {

        return $this->service->joinGroup($data['idgrup'],$data['username'],$data['kode']);
    }


    private function checkData(){
        if(!isset($data['idgrup'])) throw new Exception("Data incomplete: No group data sent to add member");
        if(!isset($data['username'])) throw new Exception("Data incomplete: No account data sent to add member");
        if(!isset($data['kode'])) throw new Exception("Data incomplete: No account data sent to add member");
    }
}