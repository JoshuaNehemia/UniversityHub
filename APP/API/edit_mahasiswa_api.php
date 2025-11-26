<?php

require_once(__DIR__ . "/../boot.php");
require_once(__DIR__ . "/../config.php");
require_once(__DIR__ . "/../Auth.php");
require_once(__DIR__ . "/../CONTROLLERS/AccountController.php");
require_once(__DIR__ . "/../CONTROLLERS/UploadController.php");

use CONTROLLERS\AccountController;
use CONTROLLERS\UploadController;

main();

function main()
{
    $response = [];

    try {
        requireRole([ACCOUNT_ROLE[2]]);

        checkDataIntegrity();

        $accController = new AccountController();
        $upController  = new UploadController();

        if (checkUpload()) {
            $ext = $upController->saveMahasiswaProfilePicture($_FILES['foto'], $_POST['nrp']);
            $_POST['foto_extention'] = $ext;
        } else {
            $mahasiswa = $accController->getSingleMahasiswaByUsername($_POST['username']);
            $upController->renameMahasiswaProfilePicture($mahasiswa['nrp'], $_POST['nrp'], $mahasiswa['foto_extention']);
            $_POST['foto_extention'] = $mahasiswa['foto_extention'];
        }
        $data = $accController->updateMahasiswa($_POST);
        if (!$data) {
            throw new Exception("Gagal update data");
        }
        $response = [
            "status" => "success",
            "data"   => $data->getArray()
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

function checkDataIntegrity()
{
    $required = ['username', 'nama', 'nrp', 'tanggal_lahir', 'gender', 'angkatan'];

    foreach ($required as $field) {
        if (!isset($_POST[$field])) {
            throw new Exception("Data yang dikirim tidak lengkap: tidak ditemukan {$field}");
        }
    }
}

function checkUpload()
{
    if (!isset($_FILES['foto']) || $_FILES['foto']['error'] === UPLOAD_ERR_NO_FILE) {
        return false;
    } else return true;
}
