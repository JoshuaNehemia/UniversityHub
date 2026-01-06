<?php

namespace CONTROLLERS;

#region REQUIRE
require_once(__DIR__ ."/../SERVICE/AccountService.php");
#endregion

#region USE
use SERVICE\AccountService;
use Exception;
#endregion

class AuthController
{
    #region FIELDS
    private $authService;
    #endregion

    #region CONSTRUCTOR
    public function __construct(){
        $this->authService = new AccountService();
    }
    #endregion

    #region CREATE
    public function createAccount(array $data){
        
    }
    #endregion
}
