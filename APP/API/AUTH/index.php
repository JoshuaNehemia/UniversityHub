<?php

require_once(__DIR__ . "/../../boot.php");
require_once(__DIR__ . "/../../auth.php");
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
        case "PUT":
            changePassword($controller);
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

function login($controller)
{
    $response = null;
    try {
        if (!isset($_POST['username'])) throw new Exception("Tidak ada username");
        if (!isset($_POST['password'])) throw new Exception("Tidak ada password");
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

function put($controller)
{
    if (!isset($_SESSION[CURRENT_ACCOUNT])) throw new Exception("Tidak ada akun yang terloggedin");
    if ($_SESSION[CURRENT_ACCOUNT]['jenis'] === ACCOUNT_ROLE[2]) {
    } else {
        changePassword($controller);
    }
}

function changePasswordAdmin(AuthController $controller)
{
    $raw = file_get_contents("php://input");
    //var_dump($raw); // DEBUG
    $params = json_decode($raw, true);
    //print_r($params);
    try {
        if (!isset($_SESSION[CURRENT_ACCOUNT])) throw new Exception("Tidak ada akun yang terloggedin");
        if (!(isset($params['username']) && isset($params['new_password']) && isset($params['confirm_password']))) throw new Exception("Data tidak lengkap.");

        $username = $params['username'];
        $new_password = $params['new_password'];
        $confirm_password = $params['confirm_password'];

        $controller->adminChangePassword($username, $new_password, $confirm_password);
        session_destroy();
        $response = array(
            "status" => "success",
            "message" => "Password telah diganti mohon login ulang"
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

function changePassword($controller)
{
    $raw = file_get_contents("php://input");
    //var_dump($raw); // DEBUG
    $params = json_decode($raw, true);
    //print_r($params);
    try {
        if (!isset($_SESSION[CURRENT_ACCOUNT])) throw new Exception("Tidak ada akun yang terloggedin");
        if (!(isset($params['old_password']) && isset($params['new_password']) && isset($params['confirm_password']))) throw new Exception("Data tidak lengkap.");
        $old_password = $params['old_password'];
        $new_password = $params['new_password'];
        $confirm_password = $params['confirm_password'];
        $controller->accountChangePassword($_SESSION[CURRENT_ACCOUNT], $old_password, $new_password, $confirm_password);
        session_destroy();
        $response = array(
            "status" => "success",
            "message" => "Password telah diganti mohon login ulang"
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
            "message" => "logged out",
            "route" => "login.php"
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

function getRoute($role)
{
    if ($role === ACCOUNT_ROLE[2]) {
        return "ADMIN/";
    } else {
        return "index.php";
    }
}

function get(AuthController $controller)
{
    $type = $_GET['jenis'] ?? "";
    switch ($type) {
        case "account":
            getCurrentAccount();
            break;
        case "group":
            getGroup($controller);
            break;
        case "event":
            getEvent($controller);
            break;
        default:
            echo json_encode(
                array(
                    "status" => "error",
                    "message" => "Jenis tidak ditemukan"
                )
            );
    }
}

function getCurrentAccount()
{
    $response = null;
    try {
        if (!isset($_SESSION[CURRENT_ACCOUNT])) throw new Exception("Tidak ada akun yang terloggedin");
        $data = $_SESSION[CURRENT_ACCOUNT];
        $response = array(
            "status" => "success",
            "message" => $data,
            "route" => "login.php"
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
function getGroup(AuthController $controller)
{
    $response = null;
    try {
        requireRole(ACCOUNT_ROLE);
        if (!isset($_SESSION[CURRENT_ACCOUNT])) throw new Exception("Tidak ada akun yang ter-logged-in");
        if (!(isset($_GET['offset']) && ($_GET['offset'] >= 0))) throw new Exception("Offset tidak ada.");
        if (!(isset($_GET['limit']) && !empty($_GET['limit']))) throw new Exception("Limit tidak ada.");

        $username = $_SESSION[CURRENT_ACCOUNT]['username'];
        $limit = $_GET['limit'];
        $offset = $_GET['offset'];
        $keyword = $_GET['keyword'] ?? "";

        $list = $controller->getAllGroupJoinedByUser($username, $limit, $offset, $keyword);
        if (sizeof($list) == 0) throw new Exception("Tidak ada grup yang ditemukan");

        $response = array(
            "status" => "success",
            "data" => $list,
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
function getEvent(AuthController $controller)
{
    $response = null;
    try {
        requireRole(ACCOUNT_ROLE);
        if (!isset($_SESSION[CURRENT_ACCOUNT])) throw new Exception("Tidak ada akun yang ter-logged-in");
        if (!(isset($_GET['offset']) && ($_GET['offset'] >= 0))) throw new Exception("Offset tidak ada.");
        if (!(isset($_GET['limit']) && !empty($_GET['limit']))) throw new Exception("Limit tidak ada.");

        $username = $_SESSION[CURRENT_ACCOUNT];
        $limit = $_GET['limit'];
        $offset = $_GET['offset'];
        $keyword = $_GET['keyword'] ?? "";
        $list = $controller->getAllUserEvent($username, $keyword, $limit, $offset);
        $response = array(
            "status" => "success",
            "data" => $list
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
