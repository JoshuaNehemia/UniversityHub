<?php

require_once(__DIR__ . "/../boot.php");
require_once(__DIR__ . "/../config.php");
require_once(__DIR__ . "/../CONTROLLERS/AuthController.php");

use CONTROLLERS\AuthController;

// =============================================================================================
// LOGIC
// =============================================================================================
main();


function main()
{
    try {
        $auth = new AuthController;

        $username = $_POST['username'];
        $password = $_POST['password'];

        $akun = $auth->login($username, $password);
        $response = array(
            "status"=>"success",
            "message"=>$akun,
            "route"=>getRoute($akun['jenis'])
        );
    } catch (Exception $e) {
        $response = array(
            "status"=>"error",
            "message"=>$e->getMessage(),
            "route"=>"login.php"
        );
    } finally {
    }
}

function checkDataIntegrity()
{
    if (!($_SERVER['REQUEST_METHOD'] === "POST")) throw new Exception("Request server illegal");
}

function getRoute($role){
    if($role === ACCOUNT_ROLE[2]){
        return "ADMIN/";
    }
    else{
        return "index.php";
    }
}
