<?php

require_once(__DIR__ . "/../../auth.php");
require_once(__DIR__ . "/../../config.php");
require_once(__DIR__ . "/../../SERVICE/AccountService.php");

use SERVICE\AccountService;

// =============================================================================================
// RUN
// =============================================================================================
main();

// =============================================================================================
// FUNCTION
// =============================================================================================
function main()
{
    $method = $_SERVER['REQUEST_METHOD'];
    $service = new AccountService();
    
    switch ($method) {
        case "GET":
            get($service);
            break;
        default:
            http_response_code(404);
            echo json_encode(
                array(
                    "status" => "error",
                    "message" => "API not found"
                )
            );
    }
}

function get(AccountService $service)
{
    $response = null;
    try {
        requireRole(ACCOUNT_ROLE);

        if (!(isset($_GET['offset']) && ($_GET['offset'] >= 0))) {
            throw new Exception("Offset tidak ada.");
        }
        if (!(isset($_GET['limit']) && !empty($_GET['limit']))) {
            throw new Exception("Limit tidak ada.");
        }

        $limit = $_GET['limit'];
        $offset = $_GET['offset'];
        $keyword = $_GET['keyword'] ?? "";

        // Get dosen accounts only
        $list = $service->searchDosenByKeyword($keyword, $limit, $offset);

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
