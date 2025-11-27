<?php

require_once(__DIR__ . "/../boot.php");
require_once(__DIR__ . "/../config.php");
require_once(__DIR__ . "/../Auth.php");
require_once(__DIR__ . "/../CONTROLLERS/GroupController.php");

use CONTROLLERS\GroupController;

main();

function main()
{
    $response="";
    try {
        requireRole(array(ACCOUNT_ROLE[1], ACCOUNT_ROLE[2]));
        checkDataIntegrity();
        $grController = new GroupController();
        $data = $grController->deleteGroup($_POST['id']);
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
    if(!isset($_POST['id'])) throw new Exception("Id grup tidak ada");
}
