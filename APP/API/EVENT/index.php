<?php

require_once(__DIR__ . "/../../auth.php");
require_once(__DIR__ . "/../../boot.php");
require_once(__DIR__ . "/../../config.php");
require_once(__DIR__ . "/../../CONTROLLERS/EventController.php");
require_once(__DIR__ . "/../../CONTROLLERS/UploadController.php");

use CONTROLLERS\EventController;
use CONTROLLERS\UploadController;

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
    requireRole(array(ACCOUNT_ROLE[1],ACCOUNT_ROLE[2]));
    //echo $method;
    $controller = new EventController();
    $upload = new UploadController();
    switch ($method) {
        case "POST":
            post($controller, $upload);
            break;
        case "GET":
            get($controller, $upload);
            break;
        case "PUT":
            update($controller, $upload);
            break;
        case "DELETE":
            delete($controller, $upload);
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
function post(EventController $controller, UploadController $upload)
{
    if (isset($_FILES['new_foto'])) {
        changeProfilePicture($controller, $upload);
    } else {
        create($controller, $upload);
    }
}

function create(EventController $controller, UploadController $upload)
{
    $response = null;
    try {
        requireRole(array(ACCOUNT_ROLE[2]));

        $required = ['username', 'password', 'nama', 'nrp', 'tanggal_lahir', 'gender', 'angkatan'];

        foreach ($required as $field) {
            if (!isset($_POST[$field])) {
                throw new Exception("Data yang dikirim tidak lengkap: tidak ditemukan {$field}");
            }
        }

        if (!isset($_FILES['foto']) || $_FILES['foto']['error'] === UPLOAD_ERR_NO_FILE) {
            throw new Exception("Tidak ada foto profil yang diupload");
        }

        $ext = $upload->saveEventPoster($_FILES['foto'], $_POST['nrp']);
        $_POST['foto_extention'] = $ext;

        $data = $controller->createEvent($_POST);
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

function changeProfilePicture(EventController $controller, UploadController $upload)
{
    $response = null;
    try {
        if (!isset($_POST['username'])) throw new Exception("Tidak ada username.");
        $Event = $controller->getSingleEventByUsername($_POST['username']);
        $ext = "";
        if (!isset($_FILES['new_foto']) || $_FILES['new_foto']['error'] === UPLOAD_ERR_NO_FILE) throw new Exception("File foto tidak terupload");
        $ext = $upload->saveEventPoster($_FILES['new_foto'], $Event['nrp']);
        if ($ext !== $Event['foto_extention']) {
            $Event['foto_extention'] = $ext;
            $Event = $controller->updateEvent($Event);
        }
        $response = [
            "status" => "success",
            "data"   => "berhasil mengganti profile picture {$Event['nrp']}.{$Event['foto_extention']}"
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

function update(EventController $controller, UploadController $upload)
{
    $response = null;
    $raw = file_get_contents("php://input");
    //var_dump($raw); // DEBUG
    $params = json_decode($raw, true);
    //print_r($params);

    try {
        requireRole([ACCOUNT_ROLE[2]]);
        if (!is_array($params)) {
            throw new Exception("Invalid  input");
        }
        $required = ['username', 'nama', 'nrp', 'tanggal_lahir', 'gender', 'angkatan'];

        foreach ($required as $field) {
            if (!isset($params[$field])) {
                throw new Exception("Data yang dikirim tidak lengkap: tidak ditemukan {$field}");
            }
        }

        $Event = $controller->getSingleEventByUsername($params['username']);
        $params['foto_extention'] = $Event['foto_extention'];
        $data = $controller->updateEvent($params);
        $upload->renameEventProfilePicture(
            $Event['nrp'],
            $params['nrp'],
            $Event['foto_extention']
        );

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


function get(EventController $controller, UploadController $upload)
{
    if (isset($_GET['username'])) {
        single($controller);
    } else {
        all($controller);
    }
}

function single(EventController $controller)
{
    $response = null;
    try {
        requireRole(array(ACCOUNT_ROLE[2]));
        $username = $_GET['username'];
        $response = array(
            "status" => "success",
            "data" => $controller->getSingleEventByUsername($username)
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

function all(EventController $controller, $filter = "")
{
    $response = null;
    try {
        requireRole(array(ACCOUNT_ROLE[1], ACCOUNT_ROLE[2]));

        if (!(isset($_GET['offset']) && ($_GET['offset'] >= 0))) throw new Exception("Offset tidak ada.");
        if (!(isset($_GET['limit']) && !empty($_GET['limit']))) throw new Exception("Limit tidak ada.");

        $limit = $_GET['limit'];
        $offset = $_GET['offset'];
        $nama = $_GET['keyword'] ?? "";
        $list = $controller->getListEventByNama($limit, $offset, $nama);

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

function delete(EventController $controller,UploadController $upload)
{

    $response = null;

    $raw = file_get_contents("php://input");
    //var_dump($raw); // DEBUG
    $params = json_decode($raw, true);
    //print_r($params);
    try {
        requireRole([ACCOUNT_ROLE[2]]);
        if (!isset($params['username'])) throw new Exception("Data tidak lengkap, tidak ada username");
        $username = $params['username'];
        $mhs = $controller->getSingleEventByUsername($username);
        $upload->deleteEventProfilePicture($mhs['nrp'], $mhs['foto_extention']);
        $controller->deleteEvent($mhs);

        $response = [
            "status" => "success",
            "message"   => "Berhasil menghapus akun Event {$username}"
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
