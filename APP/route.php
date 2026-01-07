<?php
$uri = explode('/', trim($_SERVER['REQUEST_URI'], '/'));

$resource = $uri[3] ?? null;
$case = array_slice($uri, 3);
$resource = strtoupper($resource);
$routesPath = __DIR__;
$index = "index.php";

switch ($resource) {
    case "AUTH":
        require_once("$routesPath/API/AUTH/" . $index);
        break;
    case "ACCOUNT":
        require_once("$routesPath/API/ACCOUNT/" . $index);
        break;
    case "GROUP":
        require_once("$routesPath/API/GROUP/" . $index);
        break;
    case "EVENT":
        require_once("$routesPath/API/EVENT/" . $index);
        break;
    default:
        throw new Exception("No API Found");
}


function illegal_call($uri)
{

}
