<?php

require_once(__DIR__ . "/../../boot.php");
require_once(__DIR__ . "/../../config.php");
require_once(__DIR__ . "/../../CONTROLLERS/AuthController.php");

use CONTROLLERS\AuthController;

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
    //echo $method;
    $controller = new AuthController;
    switch ($method) {
        case "POST":
            login($controller);
            break;
        case "DELETE":
            logout();
            break;
        default:
            http_response_code(404);
            echo json_encode(["error" => "API not found"]);
    }
}

function login($controller)
{
    $response = null;
    try {
        if(!isset($_POST['username'])) throw new Exception("Tidak ada username");
        if(!isset($_POST['password'])) throw new Exception("Tidak ada password");
        $username = $_POST['username'];
        $password = $_POST['password'];
        $akun = $controller->login($username, $password);
        $_SESSION[CURRENT_ACCOUNT] = $akun;
        $response = array(
            "status" => "success",
            "data" => $akun,
            "route" => getRoute($akun['jenis'])
        );
    } catch (Exception $e) {
        $response = array(
            "status" => "error",
            "message" => $e->getMessage(),
            "route" => "login.php"
        );
    } finally {
        echo json_encode($response);
    }
}

function logout()
{
    $raw = file_get_contents("php://input");
    $params = json_decode($raw, true);
    print_r($params);
    $response = null;
    try {
        if (!isset($_SESSION[CURRENT_ACCOUNT])) throw new Exception("Tidak ada akun yang dilogout");
        session_destroy();
        $response = array(
            "status" => "success",
            "message" => "logged out"
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

function getRoute($role)
{
    if ($role === ACCOUNT_ROLE[2]) {
        return "ADMIN/";
    } else {
        return "index.php";
    }
}
