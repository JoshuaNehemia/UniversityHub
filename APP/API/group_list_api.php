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
        $accController = new GroupController();
        checkDataIntegrity();
        $limit = $_GET['limit'];
        $offset = $_GET['offset'];
        $keyword = $_GET['keyword'];
        $list = $accController->getListGroupByName($limit, $offset, $keyword);
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

function checkDataIntegrity()
{
    if (!(isset($_GET['offset']) && ($_GET['offset'] >= 0))) throw new Exception("Offset tidak ada.");
    if (!(isset($_GET['limit']) && !empty($_GET['limit']))) throw new Exception("Limit tidak ada.");
}
