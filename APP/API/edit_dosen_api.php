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

        // if new file upload
        if (checkUpload()) {
            $ext = $upController->saveDosenProfilePicture($_FILES['foto'], $_POST['npk']);
            $_POST['foto_extention'] = $ext;

        // if no upload → rename old file like mahasiswa API
        } else {
            $dosen = $accController->getSingleDosenByUsername($_POST['username']);

            $upController->renameDosenProfilePicture(
                $dosen['npk'], 
                $_POST['npk'], 
                $dosen['foto_extention']
            );

            $_POST['foto_extention'] = $dosen['foto_extention'];
        }

        // Perform update
        $data = $accController->updateDosen($_POST);
        if (!$data) {
            throw new Exception("Gagal update data");
        }

        // Response structure matches mahasiswa API
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
        return false;
    } 
    return true;
}
