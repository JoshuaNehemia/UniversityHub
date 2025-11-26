<?php

require_once(__DIR__ . "/../boot.php");
require_once(__DIR__ . "/../config.php");

// =============================================================================================
// RUN
// =============================================================================================
main();


// =============================================================================================
// FUNCTION
// =============================================================================================
function main()
{
    try {
        logging_out();
        $response = array(
            "status" => "success",
            "route" => "login.php"
        );
    } catch (Exception $e) {
        $response = array(
            "status" => "error",
            "message" => $e->getMessage(),
            "route" => "login.php"
        );
    } finally {
    }
}

function logging_out()
{
    if (!isset($_SESSION[CURRENT_ACCOUNT])) {
        throw new Exception("Lupa kali kau login, udah mau logout aja");
    }
    session_destroy();
}
