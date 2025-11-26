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

        $username = $_POST['username'];
        $mhs = $accController->getSingleMahasiswaByUsername($username);
        $upController->deleteMahasiswaProfilePicture($mhs['nrp'],$mhs['foto_extention']);
        $accController->deleteMahasiswa($mhs);

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

function checkDataIntegrity()
{
    if($_SERVER['REQUEST_METHOD'] !== "POST") throw new Exception("Metode pengiriman data illegal");
    if(!isset($_POST['username'])) throw new Exception("Data tidak lengkap");  
}

