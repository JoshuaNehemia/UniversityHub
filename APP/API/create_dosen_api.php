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
        checkUpload();

        $accController = new AccountController();
        $upController  = new UploadController();
        
        $ext = $upController->saveDosenProfilePicture($_FILES['foto'], $_POST['npk']);
        $_POST['foto_extention'] = $ext;

        $data = $accController->createDosen($_POST);
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

function checkDataIntegrity()
{
    $required = ['username', 'nama', 'npk'];

    foreach ($required as $field) {
        if (!isset($_POST[$field])) {
            throw new Exception("Data yang dikirim tidak lengkap: tidak ditemukan {$field}");
        }
    }
}

function checkUpload()
{
    if (!isset($_FILES['foto']) || $_FILES['foto']['error'] === UPLOAD_ERR_NO_FILE) {
        throw new Exception("Tidak ada foto profil yang diupload");
    }
}
