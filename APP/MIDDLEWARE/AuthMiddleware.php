<?php

namespace MIDDLEWARE;
#region REQUIRE
require_once(__DIR__ . "/../config.php");
#endregion
use Exception;
class AuthMiddleware
{
    public static function getLoggedInAccount()
    {
        if (!isset($_SESSION[CURRENT_ACCOUNT])) {
            throw new Exception("Account is not logged in");
        }
        return $_SESSION[CURRENT_ACCOUNT];
    }

    public static function setLoggedInAccount($account)
    {
        $_SESSION[CURRENT_ACCOUNT] = $account;
    }

    public static function isLoggedIn()
    {
        return isset($_SESSION[CURRENT_ACCOUNT]);
    }

    public static function checkAllowed(array $roles)
    {
        if (!isset($_SESSION[CURRENT_ACCOUNT])) {
            throw new Exception("Account is not logged in");
        }

        if (!in_array($_SESSION[CURRENT_ACCOUNT]['jenis'], $roles)) {
            throw new Exception("Forbidden access");
            exit;
        }
    }

}
