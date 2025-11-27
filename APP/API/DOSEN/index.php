<?php

require_once(__DIR__ . "/../../auth.php");
require_once(__DIR__ . "/../../boot.php");
require_once(__DIR__ . "/../../config.php");
require_once(__DIR__ . "/../../CONTROLLERS/DosenController.php");
require_once(__DIR__ . "/../../CONTROLLERS/UploadController.php");

use CONTROLLERS\DosenController;
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
    //echo $method;
    $controller = new DosenController();
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
            echo json_encode(["error" => "API not found"]);
    }
}
function post(DosenController $controller, UploadController $upload)
{
    if (isset($_FILES['new_foto'])) {
        changeProfilePicture($controller, $upload);
    } else {
        create($controller, $upload);
    }
}

function create(DosenController $controller, UploadController $upload)
{
    $response = null;
    try {
        requireRole(array(ACCOUNT_ROLE[2]));

        $required = ['username', 'password', 'nama', 'npk'];

        foreach ($required as $field) {
            if (!isset($_POST[$field])) {
                throw new Exception("Data yang dikirim tidak lengkap: tidak ditemukan {$field}");
            }
        }

        if (!isset($_FILES['foto']) || $_FILES['foto']['error'] === UPLOAD_ERR_NO_FILE) {
            throw new Exception("Tidak ada foto profil yang diupload");
        }

        $ext = $upload->saveDosenProfilePicture($_FILES['foto'], $_POST['npk']);
        $_POST['foto_extention'] = $ext;

        $data = $controller->createDosen($_POST);
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

function changeProfilePicture(DosenController $controller, UploadController $upload)
{
    $response = null;
    try {
        if (!isset($_POST['username'])) throw new Exception("Tidak ada username.");
        $mahasiswa = $controller->getSingleDosenByUsername($_POST['username']);
        $ext = "";
        if (!isset($_FILES['new_foto']) || $_FILES['new_foto']['error'] === UPLOAD_ERR_NO_FILE) throw new Exception("File foto tidak terupload");
        $ext = $upload->saveDosenProfilePicture($_FILES['new_foto'], $mahasiswa['npk']);
        if ($ext !== $mahasiswa['foto_extention']) {
            $mahasiswa['foto_extention'] = $ext;
            $mahasiswa = $controller->updateDosen($mahasiswa);
        }
        $response = [
            "status" => "success",
            "data"   => "berhasil mengganti profile picture {$mahasiswa['npk']}.{$mahasiswa['foto_extention']}"
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

function update(DosenController $controller, UploadController $upload)
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
        $required = ['username', 'nama', 'npk',];

        foreach ($required as $field) {
            if (!isset($params[$field])) {
                throw new Exception("Data yang dikirim tidak lengkap: tidak ditemukan {$field}");
            }
        }

        $mahasiswa = $controller->getSingleDosenByUsername($params['username']);
        $params['foto_extention'] = $mahasiswa['foto_extention'];
        $data = $controller->updateDosen($params);
        $upload->renameDosenProfilePicture(
            $mahasiswa['npk'],
            $params['npk'],
            $mahasiswa['foto_extention']
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


function get(DosenController $controller, UploadController $upload)
{
    if (isset($_GET['username'])) {
        single($controller);
    } else {
        all($controller);
    }
}

function single(DosenController $controller)
{
    $response = null;
    try {
        requireRole(array(ACCOUNT_ROLE[2]));
        $username = $_GET['username'];
        $response = array(
            "status" => "success",
            "data" => $controller->getSingleDosenByUsername($username)
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

function all(DosenController $controller, $filter = "")
{
    $response = null;
    try {
        requireRole(array(ACCOUNT_ROLE[1], ACCOUNT_ROLE[2]));

        if (!(isset($_GET['offset']) && ($_GET['offset'] >= 0))) throw new Exception("Offset tidak ada.");
        if (!(isset($_GET['limit']) && !empty($_GET['limit']))) throw new Exception("Limit tidak ada.");

        $limit = $_GET['limit'];
        $offset = $_GET['offset'];
        $nama = $_GET['keyword'] ?? "";
        $list = $controller->getListDosenByName($limit, $offset, $nama);

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

function delete(DosenController $controller,UploadController $upload)
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
        $mhs = $controller->getSingleDosenByUsername($username);
        $upload->deleteDosenProfilePicture($mhs['npk'], $mhs['foto_extention']);
        $controller->deleteDosen($mhs);

        $response = [
            "status" => "success",
            "message"   => "Berhasil menghapus akun mahasiswa {$username}"
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
