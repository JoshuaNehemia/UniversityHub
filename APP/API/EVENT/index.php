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
    requireRole(ACCOUNT_ROLE);
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
    requireRole(array(ACCOUNT_ROLE[1], ACCOUNT_ROLE[2]));
    if (isset($_FILES['new_foto'])) {
        changeEventPoster($controller, $upload);
    } else {
        create($controller, $upload);
    }
}

function create(EventController $controller, UploadController $upload)
{
    $response = null;
    try {

        if (!isset($_GET['idgroup'])) throw new Exception("idgroup tidak ada");

        $required = ['judul', 'tanggal', 'keterangan', 'jenis'];
        foreach ($required as $field) {
            if (!isset($_POST[$field])) {
                throw new Exception("Data yang dikirim tidak lengkap: tidak ditemukan {$field}");
            }
        }

        if (!isset($_FILES['foto']) || $_FILES['foto']['error'] === UPLOAD_ERR_NO_FILE) {
            throw new Exception("Tidak ada foto profil yang diupload");
        }

        $ext = "";

        if (isset($_FILES['uploaded_file'])) {
            $filename = $_FILES['uploaded_file']['name'];
            $ext = pathinfo($filename, PATHINFO_EXTENSION);
        }

        $_POST['foto_extention'] = $ext;
        $data = $controller->createEvent($_POST, $_GET['idgroup']);
        $ext = $upload->saveEventPoster($_FILES['foto'], $data['id']);

        if (!$data) {
            throw new Exception("Gagal membuat event");
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

function changeEventPoster(EventController $controller, UploadController $upload)
{
    $response = null;
    try {
        if (!isset($_POST['idevent'])) throw new Exception("Tidak ada idevent.");

        if (!isset($_FILES['new_foto']) || $_FILES['new_foto']['error'] === UPLOAD_ERR_NO_FILE) throw new Exception("File foto tidak terupload");


        $event = $controller->getEventById($_POST['idevent']);
        $ext = $upload->saveEventPoster($_FILES['new_foto'], $event['id']);

        if ($ext !== $event['foto_extention']) {
            $event['foto_extention'] = $ext;
            $event = $controller->updateEvent($event);
        }

        $response = [
            "status" => "success",
            "data"   => "berhasil mengganti poster event {$event['id']}.{$event['foto_extention']}"
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
        requireRole(array(ACCOUNT_ROLE[1], ACCOUNT_ROLE[2]));

        if (!is_array($params)) {
            throw new Exception("Invalid  input");
        }

        $required = ['idevent', 'judul', 'tanggal', 'keterangan', 'jenis'];
        foreach ($required as $field) {
            if (!isset($params[$field])) {
                throw new Exception("Data yang dikirim tidak lengkap: tidak ditemukan {$field}");
            }
        }

        $event = $controller->getEventById($params['idevent']);
        $params['foto_extention'] = $event['foto_extention'];
        $data = $controller->updateEvent($params);

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
    if (isset($_GET['idevent'])) {
        single($controller);
    } else {
        all($controller);
    }
}

function single(EventController $controller)
{
    $response = null;
    try {
        $idevent = $_GET['idevent'];
        $response = array(
            "status" => "success",
            "data" => $controller->getEventById($idevent)
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

        if (!(isset($_GET['offset']) && ($_GET['offset'] >= 0))) throw new Exception("Offset tidak ada.");
        if (!(isset($_GET['limit']) && !empty($_GET['limit']))) throw new Exception("Limit tidak ada.");
        if (!isset($_GET['idgroup'])) throw new Exception("idgroup tidak ada.");
        $idgroup = $_GET['idgroup'];
        $limit = $_GET['limit'];
        $offset = $_GET['offset'];
        $keyword = $_GET['keyword'] ?? "";
        $list = $controller->getGroupEvent($idgroup, $keyword, $limit, $offset);

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

function delete(EventController $controller, UploadController $upload)
{

    $response = null;

    $raw = file_get_contents("php://input");
    //var_dump($raw); // DEBUG
    $params = json_decode($raw, true);
    //print_r($params);
    try {
        requireRole(array(ACCOUNT_ROLE[1], ACCOUNT_ROLE[2]));

        if (!isset($params['idevent'])) throw new Exception("Data tidak lengkap, tidak ada idevent");
        
        $idevent = $params['idevent'];
        $event = $controller->getEventById($idevent);
        $upload->deleteEventPoster($event['id'], $event['foto_extention']);
        $controller->deleteEvent($event);

        $response = [
            "status" => "success",
            "message"   => "Berhasil menghapus event {$event['judul']}"
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
