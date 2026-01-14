<?php
#region REQUIRE
require_once(__DIR__ . "/../../CONTROLLERS/EventController.php");
#endregion

#region USE
use CONTROLLERS\EventController;

#endregion

#region REQUEST METHOD
$method = $_SERVER['REQUEST_METHOD'];
$controller = new EventController();
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
    // Handle file upload if present
    if (isset($_FILES['poster']) && $_FILES['poster']['error'] === UPLOAD_ERR_OK) {
        $_POST['poster_file'] = $_FILES['poster'];
    }
    
    $res = $controller->createEvent($_POST);
    if (!$res)
        throw new Exception("Failed to create event.");
    return array(
        "status" => "success",
        "data" => $res,
        "message" => "Create event successful"
    );
}
function get($controller)
{
    $res = $controller->getEvent($_GET);
    if (!$res)
        throw new Exception("Failed to retrieve event.");
    return array(
        "status" => "success",
        "data" => $res,
        "message" => "Retrieve event successful"
    );
}
function delete($controller)
{
    $data = json_decode(file_get_contents("php://input"), true);
    $res = $controller->deleteEvent($data);
    return array(
        "status" => "success",
        "data" => $res,
        "message" => "Delete event successful"
    );
}

function put($controller)
{
    $data = json_decode(file_get_contents("php://input"), true);
    $res = $controller->updateEvent($data);
    return array(
        "status" => "success",
        "data" => $res,
        "message" => "Update event successful"
    );
}

echo json_encode($response);
