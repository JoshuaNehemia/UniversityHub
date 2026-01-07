<?php
#region REQUIRE
require_once(__DIR__ . "/../../CONTROLLERS/ChatController.php");
#endregion

#region USE
use CONTROLLERS\ChatController;

#endregion

#region REQUEST METHOD
$method = $_SERVER['REQUEST_METHOD'];
$controller = new ChatController();
$response = null;
switch ($method) {
    case "POST":
        $response = post($controller);
        break;
    case "PUT":
        $response = put($controller);
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
    $res = $controller->createChat($_POST);
    if (!$res)
        throw new Exception("Failed to create chat.");
    return array(
        "status" => "success",
        "data" => $res,
        "message" => "Success creating thread"
    );
}
function get($controller)
{
    $res = $controller->getChat($_GET);
    if (!$res)
        throw new Exception("Failed to retrieve chat.");
    return array(
        "status" => "success",
        "data" => $res,
        "message" => "Success retrieving thread"
    );
}
function put($controller)
{
    $data = json_decode(file_get_contents("php://input"), true);
    $res = $controller->updateChat($data);
    if (!$res)
        throw new Exception("Failed to update chat.");
    return array(
        "status" => "success",
        "data" => $res,
        "message" => "Success updating thread"
    );
}
function delete($controller)
{
    $data = json_decode(file_get_contents("php://input"), true);
    $res = $controller->deleteChat($data);
    if (!$res)
        throw new Exception("Failed to delete chat .");
    return array(
        "status" => "success",
        "data" => $res,
        "message" => "Success deleting thread"
    );
}

