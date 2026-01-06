<?php

namespace CONTROLLERS;

#region REQUIRE
require_once(__DIR__ . "/../SERVICE/AuthService.php");
#endregion

#region USE
use MIDDLEWARE\AuthMiddleware;
use SERVICE\AuthService;
use Exception;

#endregion

class AuthController
{
    #region FIELDS
    private $authService;
    #endregion

    #region CONSTRUCTOR
    public function __construct()
    {
        $this->authService = new AuthService();
    }
    #endregion

    #region LOGIN & LOGOUT
    public function login(array $data): array
    {
        if (!(isset($data['username']) && isset($data['password']))) {
            throw new Exception("Data is incomplete");
        }
        $username = $data['username'];
        $password = $data['password'];
        $account = $this->authService->login($username, $password);
        if (!$account) {
            throw new Exception("There are no account found");
        }
        AuthMiddleware::setLoggedInAccount($account);
        return $account->toArray();
    }

    public function logout()
    {
        AuthMiddleware::setLoggedInAccount(null);
    }
    #endregion

    #region RETRIEVE
    public function getLoggedInAccount(){
        return AuthMiddleware::getLoggedInAccount();
    }
    #endregion
}
