<?php

namespace MIDDLEWARE;
#region REQUIRE
require_once(__DIR__ ."/../config.php");
#endregion
class AuthMiddleware
{
    public static function getLoggedInAccount()
    {
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
            http_response_code(401);
            exit;
        }

        if (!in_array($_SESSION[CURRENT_ACCOUNT]['jenis'], $roles)) {
            http_response_code(403);
            exit;
        }
    }

}
