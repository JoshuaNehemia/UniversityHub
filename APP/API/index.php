<?php

header("Content-Type: application/json");

$uri = explode('/', trim($_SERVER['REQUEST_URI'], '/'));
$method = $_SERVER['REQUEST_METHOD'];

//print_r($uri);
$resource = $uri[4] ?? null;
$case = array_slice($uri, 4);
$resource = strtoupper($resource);
$routesPath = __DIR__;
$index = "index.php";

switch ($resource) {
    case "AUTH":
        require_once("$routesPath/AUTH/" . $index);
        break;

    case "MAHASISWA":
        require_once("$routesPath/MAHASISWA/" . $index);
        break;

    case "DOSEN":
        require_once("$routesPath/DOSEN/" . $index);
        break;

    case "GROUP":
        require_once("$routesPath/GROUP/" . $index);
        break;

    case "MEMBER":
        $param = $uri[5];
        if (is_numeric($param)) {
            $_GET['idgroup'] = $param;
            require_once("$routesPath/MEMBER/" . $index);
        } else {
            echo json_encode(
                array(
                    "status" => "error",
                    "message" => "Pemanggilan API illegal",
                    "log" => implode("\n", $uri)
                )
            );
        }
        break;

    case "JOIN":
        $param = $uri[5];
        if (is_numeric($param)) {
            $_GET['idgroup'] = $param;
            require_once("$routesPath/JOIN/" . $index);
        } else {
            echo json_encode(
                array(
                    "status" => "error",
                    "message" => "Pemanggilan API illegal",
                    "log" => implode("\n", $uri)
                )
            );
        }
        break;

    case "EVENT":
        require_once("$routesPath/EVENT/" . $index);
        break;

    case "THREAD":
        require_once("$routesPath/THREAD/" . $index);
        break;

    case "CHAT":
        require_once("$routesPath/CHAT/" . $index);
        break;

    case "MEDIA":
        require_once("$routesPath/MEDIA/" . $index);
        break;

    default:
        http_response_code(404);
        echo json_encode(
            array(
                "status" => "error",
                "message" => "Route not found",
                "log" => implode("\n", $uri)
            )
        );
}
