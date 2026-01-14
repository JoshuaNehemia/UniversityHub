<?php
require_once(__DIR__ . "/../../CONTROLLERS/ThreadController.php");
use CONTROLLERS\ThreadController;

$method = $_SERVER['REQUEST_METHOD'];
$controller = new ThreadController();

switch ($method) {
    case "POST":
        $data = json_decode(file_get_contents("php://input"), true);
        $res = $controller->createThread($data);
        echo json_encode(["status"=>"success","data"=>$res]);
        break;

    case "GET":
        $res = $controller->getThreads($_GET);
        echo json_encode(["status"=>"success","data"=>$res]);
        break;

    default:
        http_response_code(405);
}
