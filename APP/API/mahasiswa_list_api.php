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
        $limit = $_GET['limit'];
        $offset = $_GET['offset'];
        if (checkKeyword()) {
            $username = $_GET['keyword'];
            $list = $accController->getMahasiswaListByUsername($limit, $offset, $username);
        } else {
            $username = $_GET['keyword'];
            $list = $accController->getMahasiswaListByUsername($limit, $offset, $username);
        }

        $response = array(
            "status" => "success",
            "data" => $list
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

function checkKeyword()
{
    if (isset($_GET['keyword']) && !empty($_GET['keyword'])) return true;
    else false;
}


function checkDataIntegrity()
{
    if (!(isset($_GET['offset']) && !empty($_GET['offset']))) throw new Exception("Offset tidak ada.");
    if (!(isset($_GET['limit']) && !empty($_GET['limit']))) throw new Exception("Limit tidak ada.");
}
