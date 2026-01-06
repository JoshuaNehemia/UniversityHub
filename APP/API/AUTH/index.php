<?php
#region REQUIRE
require_once(__DIR__ . "/../../CONTROLLERS/AuthController.php");
#endregion

#region USE
use CONTROLLERS\AuthController;
#endregion

#region REQUEST METHOD
$method = $_SERVER['REQUEST_METHOD'];
$controller = new AuthController;
switch ($method) {
    case "POST":
        $response = login($controller);
        break;
    case "DELETE":
        $response = logout($controller);
        break;
    case "GET":
        $response = get($controller);
        break;
}
#endregion

function login($controller)
{
    $response = $controller->login($_POST);
}
function logout($controller){
    $response = $controller->logout();
}

function get($controller){
    $response = $controller->getLoggedInAccount();
}