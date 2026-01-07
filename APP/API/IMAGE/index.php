<?php
#region REQUIRE
require_once(__DIR__ . "/../../CONTROLLERS/MediaController.php");
#endregion

#region USE
use CONTROLLERS\MediaController;

#endregion

#region REQUEST METHOD
$method = $_SERVER['REQUEST_METHOD'];
$controller = new MediaController();
$response = null;
switch ($method) {
    case "POST":
        $response = post($controller);
        break;
    case "PUT":
        $response = update($controller);
        break;
    default:
        $response = notfound();
}
#endregion

function post($controller)
{
    $res = $controller->save($_FILES,$_POST);
    if (!$res)
        throw new Exception("Failed to save new image.");
    return array(
        "status" => "success",
        "data" => $res,
        "message" => "Saving new image is successful"
    );
}
function update($controller)
{
    $res = $controller->rename(json_decode(file_get_contents("php://input") , true));
    if (!$res)
        throw new Exception("Failed to rename image.");
    return array(
        "status" => "success",
        "data" => $res,
        "message" => "Renaming an image is successful"
    );
}

function notfound()
{
    return array(
        "status" => "failed",
        "message" => "API does not exists"
    );
}
