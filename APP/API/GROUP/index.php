<?php
#region REQUIRE
require_once(__DIR__ . "/../../CONTROLLERS/GroupController.php");
#endregion

#region USE
use CONTROLLERS\GroupController;

#endregion

#region REQUEST METHOD
$method = $_SERVER['REQUEST_METHOD'];
$controller = new GroupController;
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
    $res = $controller->createGroup($_POST);
    if (!$res)
        throw new Exception("Failed to create group.");
    return array(
        "status" => "success",
        "data" => $res,
        "message" => "Create group successful"
    );
}
function get($controller)
{
    $res = $controller->getGroup($_GET);
    
    return array(
        "status" => "success",
        "data" => $res,
        "message" => "Retrieve group successful"
    );
}
function delete($controller)
{
    $data = json_decode(file_get_contents("php://input"), true);
    $res = $controller->deleteGroup($data);
    return array(
        "status" => "success",
        "data" => $res,
        "message" => "Delete group successful"
    );
}

function put($controller)
{
    $data = json_decode(file_get_contents("php://input"), true);
    $res = $controller->updateGroup($data);
    return array(
        "status" => "success",
        "data" => $res,
        "message" => "Update group successful"
    );
}

echo json_encode($response);
