<?php

require_once(__DIR__ . "/../../auth.php");
require_once(__DIR__ . "/../../boot.php");
require_once(__DIR__ . "/../../config.php");
require_once(__DIR__ . "/../../CONTROLLERS/MemberController.php");

use CONTROLLERS\MemberController;

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
    $controller = new MemberController();
    switch ($method) {
        case "POST":
            post($controller);
            break;
        case "GET":
            get($controller);
            break;
        case "DELETE":
            delete($controller);
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

function post(MemberController $controller)
{
    requireRole(array(ACCOUNT_ROLE[1],ACCOUNT_ROLE[2]));
    $response = null;
    try {
        if (!((isset($_GET['idgroup'])) && ($_GET['idgroup'] > 0))) throw new Exception("Id group tidak ada atau tidak valid.");
        if (!isset($_POST['username'])) throw new Exception("Username tidak ada atau tidak valid untuk memasukan member.");
        $idgroup = $_GET['idgroup'];
        $username = $_POST['username'];
        $controller->addMemberToGroup($idgroup, $username);
        $response = [
            "status"  => "success",
            "message" => "Berhasil menambahkan akun kedalam group"
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

function delete(MemberController $controller)
{
    requireRole(array(ACCOUNT_ROLE[1],ACCOUNT_ROLE[2]));
    $response = null;
    $raw = file_get_contents("php://input");
    //var_dump($raw); // DEBUG
    $params = json_decode($raw, true);
    //print_r($params);
    try {
        if (!((isset($_GET['idgroup'])) && ($_GET['idgroup'] > 0))) throw new Exception("Id group tidak ada atau tidak valid.");
        if (!isset($params['username'])) throw new Exception("Username tidak ada atau tidak valid untuk remove member");
        $idgroup = $_GET['idgroup'];
        $username = $params['username'];
        $controller->removeMemberFromGroup($idgroup, $username);
        $response = [
            "status"  => "success",
            "message" => "Berhasil mengeluarkan akun dari group"
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

function get(MemberController $controller)
{
    $response = null;
    try {
        if (!((isset($_GET['idgroup'])) && ($_GET['idgroup'] > 0))) throw new Exception("Id group tidak ada atau tidak valid.");
        $idgroup = $_GET['idgroup'];
        $list = $controller->getAllMemberOfGroup($idgroup);
        if(count($list) <=0){
            throw new Exception("Gagal mendapatkan data");
        }
        $response = [
            "status"  => "success",
            "data" => $list
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
