<?php
#region REQUIRE
require_once(__DIR__ . "/../../CONTROLLERS/AccountController.php");
#endregion

#region USE
use CONTROLLERS\AccountController;
#endregion

#region REQUEST METHOD
$method = $_SERVER['REQUEST_METHOD'];
$controller = new AccountController;
$response = null;
switch ($method) {
    case "POST":
        $response = post($controller);
        break;
    case "DELETE":
        $response = delete($controller);
        break;
    case "GET":
        $response = get($controller);
        break;
}
#endregion

function post($controller)
{
    $res = $controller->createAccount($_POST);
    if (!$res)
        throw new Exception("Failed to create");
    return array(
        "status" => "success",
        "data" => $res,
        "message" => "Create account successful"
    );
}
function delete($controller)
{
    $controller->logout();
    return array(
        "status" => "success",
        "message" => "Log out successful"
    );
}

function put(){
    
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