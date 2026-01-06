<?php
$uri = explode('/', trim($_SERVER['REQUEST_URI'], '/'));

$resource = $uri[3] ?? null;
$case = array_slice($uri, 3);
$resource = strtoupper($resource);
$routesPath = __DIR__;
$index = "index.php";
//print_r($uri);
switch ($resource) {
    case "AUTH":
        require_once("$routesPath/API/AUTH/" . $index);
        break;
    case "MEDIA":
        require_once("$routesPath/API/MEDIA/" . $index);
        break;
    case "MAHASISWA":
        require_once("$routesPath/API/MAHASISWA/" . $index);
        break;

    case "DOSEN":
        require_once("$routesPath/API/DOSEN/" . $index);
        break;

    case "GROUP":
        require_once("$routesPath/API/GROUP/" . $index);
        break;

    default:
        $idgroup = $uri[4];
        if (is_numeric($idgroup)) {
            $_GET['idgroup'] = $idgroup;
        } else {
            illegal_call($uri);
        }
        switch ($resource) {
            case "MEMBER":
                require_once("$routesPath/API/MEMBER/" . $index);
                break;
            case "JOIN":
                require_once("$routesPath/API/JOIN/" . $index);
                break;
            case "EVENT":
                require_once("$routesPath/API/EVENT/" . $index);
                break;
            case "THREAD":
                require_once("$routesPath/API/THREAD/" . $index);
                break;
            case "CHAT":
                $idthread = $uri[5];
                if (is_numeric($param)) {
                    $_GET['idthread'] = $idthread;
                    require_once("$routesPath/API/CHAT/" . $index);
                } else {
                    illegal_call($uri);
                }
                break;
            default:
                echo json_encode(
                    array(
                        "status" => "error",
                        "message" => "Route API tidak ditemukan",
                    )
                );
        }
}

function illegal_call($uri)
{
    echo json_encode(
        array(
            "status" => "error",
            "message" => "Pemanggilan API illegal",
            "log" => implode("\n", $uri)
        )
    );

}
    