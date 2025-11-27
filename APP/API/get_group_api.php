<?php

require_once(__DIR__ . "/../boot.php");
require_once(__DIR__ . "/../config.php");
require_once(__DIR__ . "/../Auth.php");
require_once(__DIR__ . "/../CONTROLLERS/GroupController.php");

use CONTROLLERS\GroupController;
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
        requireRole(ACCOUNT_ROLE);
        $grController = new GroupController();
        checkDataIntegrity();
        $id = $_GET['id'];
        $response = array(
            "status" => "success",
            "data" => $grController->getSingleGroup($id)
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
    if (!(isset($_GET['id']))) throw new Exception("Id tidak ada.");
}
