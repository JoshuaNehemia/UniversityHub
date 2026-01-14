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
    case "GET":
        $response = get($controller);
        break;
    case "DELETE":
        $response = delete($controller);
        break;
    case "PUT":
        $response = put($controller);
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
function get()
{
    return array(
        "status" => "failed",
        "message" => "No api exists"
    );
}
function delete($controller)
{
    $data = json_decode(file_get_contents("php://input"), true);
    $res = $controller->deleteAccount($data);
    return array(
        "status" => "success",
        "data" => $res,
        "message" => "Delete account successful"
    );
}

function put($controller)
{
    $data = json_decode(file_get_contents("php://input"), true);
    $res = $controller->updateAccount($data);
    return array(
        "status" => "success",
        "data" => $res,
        "message" => "Update account successful"
    );
}

echo json_encode($response);
