<?php
#region REQUIRE
require_once(__DIR__ . "/../../CONTROLLERS/MemberController.php");
#endregion

#region USE
use CONTROLLERS\MemberController;

#endregion

#region REQUEST METHOD
$method = $_SERVER['REQUEST_METHOD'];
$controller = new MemberController();
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
    $res = $controller->addMember($_POST);
    if (!$res)
        throw new Exception("Failed to create group member.");
    return array(
        "status" => "success",
        "data" => $res,
        "message" => "Create group member successful"
    );
}
function get($controller)
{
    $res = $controller->getMember($_GET);
    if (!$res)
        throw new Exception("Failed to retrieve group member.");
    return array(
        "status" => "success",
        "data" => $res,
        "message" => "Retrieve group member successful"
    );
}
function delete($controller)
{
    $data = json_decode(file_get_contents("php://input"), true);
    if (!is_array($data)) $data = [];
    // Merge query parameters for compatibility (some requests send idgroup in URL)
    $data = array_merge($_GET, $data);
    $res = $controller->deleteMember($data);
    return array(
        "status" => "success",
        "data" => $res,
        "message" => "Delete group member successful"
    );
}

function put($controller)
{
    return array(
        "status" => "failed",
        "message" => "API does not exists"
    );
}

// Echo response
if ($response !== null) {
    echo json_encode($response);
}
