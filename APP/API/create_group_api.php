<?php

require_once(__DIR__ . "/../boot.php");
require_once(__DIR__ . "/../config.php");
require_once(__DIR__ . "/../Auth.php");
require_once(__DIR__ . "/../CONTROLLERS/GroupController.php");

use CONTROLLERS\GroupController;
use MODELS\Group;

main();

function main()
{
    $response="";
    try {
        requireRole(array(ACCOUNT_ROLE[1], ACCOUNT_ROLE[2]));
        checkDataIntegrity();
        $grController = new GroupController();
        $data = $grController->createGroup($_POST);
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
    $required = array("pembuat", "nama", "deskripsi", "jenis", "kode");
    foreach ($required as $field) {
        if (!isset($_POST[$field])) {
            throw new Exception("Data yang dikirim tidak lengkap: tidak ditemukan {$field}");
        }
    }
    $_POST['tanggal_dibuat'] = "" .date("Y-m-d H:i:s");
}
