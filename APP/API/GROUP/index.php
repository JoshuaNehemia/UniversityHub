<?php

require_once(__DIR__ . "/../../auth.php");
require_once(__DIR__ . "/../../boot.php");
require_once(__DIR__ . "/../../config.php");
require_once(__DIR__ . "/../../CONTROLLERS/GroupController.php");

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
    $method = $_SERVER['REQUEST_METHOD'];
    $controller = new GroupController();
    switch ($method) {
        case "POST":
            post($controller);
            break;
        case "GET":
            get($controller);
            break;
        case "PUT":
            put($controller);
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

function post(GroupController $controller)
{
    $response = "";
    try {
        requireRole(array(ACCOUNT_ROLE[1], ACCOUNT_ROLE[2]));
        $required = array("nama", "deskripsi", "jenis");
        foreach ($required as $field) {
            if (!isset($_POST[$field])) {
                throw new Exception("Data yang dikirim tidak lengkap: tidak ditemukan {$field}");
            }
        }
        if ($_SESSION[CURRENT_ACCOUNT]['jenis'] === ACCOUNT_ROLE[0]) throw new Exception("Akun anda tidak dapat membuat group.");
        $_POST['pembuat'] = $_SESSION[CURRENT_ACCOUNT]['username'];
        $data = $controller->createGroup($_POST);

        if (!$data) {
            throw new Exception("Gagal update data");
        }
        $response = [
            "status" => "success",
            "data"   => $data
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



function put(GroupController $controller)
{
    $response = null;
    $raw = file_get_contents("php://input");
    //var_dump($raw); // DEBUG
    $params = json_decode($raw, true);
    //print_r($params);}
    $response = "";
    try {
        requireRole(array(ACCOUNT_ROLE[1], ACCOUNT_ROLE[2]));
        $required = array("id", "nama", "deskripsi", "jenis");
        foreach ($required as $field) {
            if (!isset($params[$field])) {
                throw new Exception("Data yang dikirim tidak lengkap: tidak ditemukan {$field}");
            }
        }
        $grController = new GroupController();
        $data = $grController->editGroup($params);
        if (!$data) {
            throw new Exception("Gagal update data");
        }
        $response = [
            "status" => "success",
            "data"   => $data
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


function get(GroupController $controller)
{
    if (isset($_GET['id'])) {
        single($controller);
    } else {
        all($controller);
    }
}


function single(GroupController $controller)
{
    $response = null;
    try {
        requireRole(ACCOUNT_ROLE);
        if (!(isset($_GET['id']))) throw new Exception("Id tidak ada.");
        $id = $_GET['id'];
        $response = array(
            "status" => "success",
            "data" => $controller->getSingleGroup($id)
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

function all(GroupController $controller, $filter = "")
{
    $response = null;
    try {
        requireRole(ACCOUNT_ROLE);
        if (!(isset($_GET['offset']) && ($_GET['offset'] >= 0))) throw new Exception("Offset tidak ada.");
        if (!(isset($_GET['limit']) && !empty($_GET['limit']))) throw new Exception("Limit tidak ada.");
        $limit = $_GET['limit'];
        $offset = $_GET['offset'];
        $keyword = $_GET['keyword'] ?? "";
        $list = array();
        if ($_SESSION[CURRENT_ACCOUNT]['jenis'] === ACCOUNT_ROLE[0]) {
            $list = $controller->getListGroupByNameForMahasiswa($limit, $offset, $keyword);
        } else {
            $list = $controller->getListGroupByName($limit, $offset, $keyword);
        }
        if (sizeof($list) == 0) throw new Exception("Tidak ada grup yang ditemukan");
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

function delete(GroupController $controller)
{
    $response = null;
    $raw = file_get_contents("php://input");
    //var_dump($raw); // DEBUG
    $params = json_decode($raw, true);
    //print_r($params);
    try {
        requireRole(array(ACCOUNT_ROLE[1], ACCOUNT_ROLE[2]));
        if (!isset($params['id'])) throw new Exception("Id grup tidak ada");
        $data = $controller->deleteGroup($params['id']);
        if (!$data) {
            throw new Exception("Gagal update data");
        }
        $response = [
            "status" => "success",
            "data"   => "Berhasil menghapus data."
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
