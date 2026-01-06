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
$response = null;
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
    $account = $controller->login($_POST);
    if (!$account)
        throw new Exception("Failed to login");
    return array(
        "status" => "success",
        "data" => $account,
        "message" => "Log in successful"
    );
}
function logout($controller)
{
    $controller->logout();
    return array(
        "status" => "success",
        "message" => "Log out successful"
    );
}

function get($controller)
{
    $account = $controller->getLoggedInAccount();
    return array(
        "status" => "success",
        "data" => $account,
        "message" => "Retrieve account successful"
    );
}