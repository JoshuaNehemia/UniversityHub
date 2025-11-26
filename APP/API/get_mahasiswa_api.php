<?php

require_once(__DIR__ . "/../boot.php");
require_once(__DIR__ . "/../config.php");
require_once(__DIR__ . "/../Auth.php");
require_once(__DIR__ . "/../CONTROLLERS/AccountController.php");

use CONTROLLERS\AccountController;
// =============================================================================================
// RUN
// =============================================================================================
main();

// =============================================================================================
// FUNCTION
// =============================================================================================
function main()
{
    $response = null;  
    try {
        requireRole(array(ACCOUNT_ROLE[2]));
        $accController = new AccountController();
        checkDataIntegrity();
        $username = $_GET['username'];
        $response = array(
            "status" => "success",
            "data" => $accController->getSingleMahasiswaByUsername($username)
        );
    } catch (Exception $e) {
        $response = array(
            "status" => "error",
            "message" => $e->getMessage()
        );
    } finally {
        echo json_encode($response);
    }
}

function checkDataIntegrity()
{
    if (!(isset($_GET['username']))) throw new Exception("Username tidak ada.");
}
