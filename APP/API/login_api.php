<?php

require_once(__DIR__ . "/../boot.php");
require_once(__DIR__ . "/../config.php");
require_once(__DIR__ . "/../CONTROLLERS/AuthController.php");

use CONTROLLERS\AuthController;

// =============================================================================================
// RUN
// =============================================================================================
main();


// =============================================================================================
// FUNCTION
// =============================================================================================
function main()
{
    $response = null;
    try {
        $auth = new AuthController;

        $username = $_POST['username'];
        $password = $_POST['password'];

        $akun = $auth->login($username, $password);
        $response = array(
            "status" => "success",
            "data" => $akun,
            "route" => getRoute($akun['jenis'])
        );
    } catch (Exception $e) {
        $response = array(
            "status" => "error",
            "message" => $e->getMessage(),
            "route" => "login.php"
        );
    } finally {
        echo json_encode($response);
    }
}

function checkDataIntegrity()
{
    if (!($_SERVER['REQUEST_METHOD'] === "POST")) throw new Exception("Request server illegal");
    if (!(isset($_POST['username']) && isset($_POST['password']))) throw new Exception("Data tidak lengkap");
}

function getRoute($role)
{
    if ($role === ACCOUNT_ROLE[2]) {
        return "ADMIN/";
    } else {
        return "index.php";
    }
}
