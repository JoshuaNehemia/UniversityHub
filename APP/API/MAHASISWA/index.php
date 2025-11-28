<?php

require_once(__DIR__ . "/../../auth.php");
require_once(__DIR__ . "/../../boot.php");
require_once(__DIR__ . "/../../config.php");
require_once(__DIR__ . "/../../CONTROLLERS/MahasiswaController.php");
require_once(__DIR__ . "/../../CONTROLLERS/UploadController.php");

use CONTROLLERS\MahasiswaController;
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
    $controller = new MahasiswaController();
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
function post(MahasiswaController $controller, UploadController $upload)
{
    if (isset($_FILES['new_foto'])) {
        changeProfilePicture($controller, $upload);
    } else {
        create($controller, $upload);
    }
}

function create(MahasiswaController $controller, UploadController $upload)
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

        $ext = $upload->saveMahasiswaProfilePicture($_FILES['foto'], $_POST['nrp']);
        $_POST['foto_extention'] = $ext;

        $data = $controller->createMahasiswa($_POST);
        if (!$data) {
            throw new Exception("Gagal membuat akun, tidak ada yang tersimpan di database.");
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

function changeProfilePicture(MahasiswaController $controller, UploadController $upload)
{
    $response = null;
    try {
        if (!isset($_POST['username'])) throw new Exception("Tidak ada username.");
        $mahasiswa = $controller->getSingleMahasiswaByUsername($_POST['username']);
        $ext = "";
        if (!isset($_FILES['new_foto']) || $_FILES['new_foto']['error'] === UPLOAD_ERR_NO_FILE) throw new Exception("File foto tidak terupload");
        $ext = $upload->saveMahasiswaProfilePicture($_FILES['new_foto'], $mahasiswa['nrp']);
        if ($ext !== $mahasiswa['foto_extention']) {
            $mahasiswa['foto_extention'] = $ext;
            $mahasiswa = $controller->updateMahasiswa($mahasiswa);
        }
        $response = [
            "status" => "success",
            "data"   => "berhasil mengganti profile picture {$mahasiswa['nrp']}.{$mahasiswa['foto_extention']}"
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

function update(MahasiswaController $controller, UploadController $upload)
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

        $mahasiswa = $controller->getSingleMahasiswaByUsername($params['username']);
        $params['foto_extention'] = $mahasiswa['foto_extention'];
        $data = $controller->updateMahasiswa($params);
        $upload->renameMahasiswaProfilePicture(
            $mahasiswa['nrp'],
            $params['nrp'],
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


function get(MahasiswaController $controller, UploadController $upload)
{
    if (isset($_GET['username'])) {
        single($controller);
    } else {
        all($controller);
    }
}

function single(MahasiswaController $controller)
{
    $response = null;
    try {
        requireRole(array(ACCOUNT_ROLE[2]));
        $username = $_GET['username'];
        $response = array(
            "status" => "success",
            "data" => $controller->getSingleMahasiswaByUsername($username)
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

function all(MahasiswaController $controller, $filter = "")
{
    $response = null;
    try {
        requireRole(array(ACCOUNT_ROLE[1], ACCOUNT_ROLE[2]));

        if (!(isset($_GET['offset']) && ($_GET['offset'] >= 0))) throw new Exception("Offset tidak ada.");
        if (!(isset($_GET['limit']) && !empty($_GET['limit']))) throw new Exception("Limit tidak ada.");

        $limit = $_GET['limit'];
        $offset = $_GET['offset'];
        $nama = $_GET['keyword'] ?? "";
        $list = $controller->getListMahasiswaByNama($limit, $offset, $nama);

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

function delete(MahasiswaController $controller,UploadController $upload)
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
        $mhs = $controller->getSingleMahasiswaByUsername($username);
        $upload->deleteMahasiswaProfilePicture($mhs['nrp'], $mhs['foto_extention']);
        $controller->deleteMahasiswa($mhs);

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
