<?php

require_once(__DIR__ . "/../../auth.php");
require_once(__DIR__ . "/../../boot.php");
require_once(__DIR__ . "/../../config.php");
require_once(__DIR__ . "/../../CONTROLLERS/JoinGroupController.php");

use CONTROLLERS\JoinGroupController;

// =============================================================================================
// RUN
// =============================================================================================
main();


// =============================================================================================
// FUNCTION
// =============================================================================================
function main()
{
    requireRole(ACCOUNT_ROLE);
    $method = $_SERVER['REQUEST_METHOD'];
    $controller = new JoinGroupController();
    switch ($method) {
        case "POST":
            post($controller);
            break;
        case "GET":
            get($controller);
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

function post(JoinGroupController $controller)
{
    $response = null;
    try {
        if (!((isset($_GET['idgroup'])) && ($_GET['idgroup'] > 0))) throw new Exception("Id group tidak ada atau tidak valid.");
        if (!isset($_POST['kode'])) throw new Exception("Kode tidak ada.");
        $idgroup = $_GET['idgroup'];
        $username = $_SESSION[CURRENT_ACCOUNT]['username'];
        $kode = $_POST['kode'];
        $controller->joinGroup($idgroup, $username, $kode);
        $response = [
            "status"  => "success",
            "message" => "Berhasil masuk sebagai anggota kedalam group"
        ];
    } catch (Exception $e) {
        $response = [
            "status"  => "error",
            "message" => $e->getMessage()
        ];
    } finally {
        echo json_encode($response);
    }
}

function get(JoinGroupController $controller)
{
    $response = null;
    try {
        if (!((isset($_GET['idgroup'])) && ($_GET['idgroup'] > 0))) throw new Exception("Id group tidak ada atau tidak valid.");
        $idgroup = $_GET['idgroup'];
        $username = $_GET['username'];
        $result = $controller->checkUserJoined($idgroup,$username);
        $response = [
            "status"  => "success",
            "data" => $result
        ];
    } catch (Exception $e) {
        $response = [
            "status"  => "error",
            "message" => $e->getMessage()
        ];
    } finally {
        echo json_encode($response);
    }
}
