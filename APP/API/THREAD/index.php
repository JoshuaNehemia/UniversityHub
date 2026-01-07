<?php
#region REQUIRE
require_once(__DIR__ . "/../../CONTROLLERS/ThreadController.php");
#endregion

#region USE
use CONTROLLERS\ThreadController;

#endregion

#region REQUEST METHOD
$method = $_SERVER['REQUEST_METHOD'];
$controller = new ThreadController();
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
    $res = $controller->create($_POST);
    if (!$res)
        throw new Exception("Failed to create thread.");
    return array(
        "status" => "success",
        "data" => $res,
        "message" => "Success creating thread"
    );
}
function get($controller)
{
    $res = $controller->get($_GET);
    if (!$res)
        throw new Exception("Failed to retrieve thread.");
    return array(
        "status" => "success",
        "data" => $res,
        "message" => "Success retrieving thread"
    );
}
function put($controller)
{
    $data = json_decode(file_get_contents("php://input"), true);
    $res = $controller->update($data);
    if (!$res)
        throw new Exception("Failed to update thread.");
    return array(
        "status" => "success",
        "data" => $res,
        "message" => "Success updating thread"
    );
}
function delete($controller)
{
    $data = json_decode(file_get_contents("php://input"), true);
    $res = $controller->delete($data);
    if (!$res)
        throw new Exception("Failed to delete thread group.");
    return array(
        "status" => "success",
        "data" => $res,
        "message" => "Success deleting thread"
    );
}

