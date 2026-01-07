<?php
#region REQUIRE
require_once(__DIR__ . "/../../CONTROLLERS/JoinController.php");
#endregion

#region USE
use CONTROLLERS\JoinController;

#endregion

#region REQUEST METHOD
$method = $_SERVER['REQUEST_METHOD'];
$controller = new JoinController();
$response = null;
switch ($method) {
    case "POST":
        $response = post($controller);
        break;
    case "DEFAULT":
        $response = notfound();
}
#endregion

function post($controller)
{
    $res = $controller->join($_POST);
    if (!$res)
        throw new Exception("Failed to join group.");
    return array(
        "status" => "success",
        "data" => $res,
        "message" => "Joining group as a member is successful"
    );
}

function notfound()
{
    return array(
        "status" => "failed",
        "message" => "API does not exists"
    );
}
