<?php

require_once(__DIR__ . "/../boot.php");
require_once(__DIR__ . "/../config.php");
require_once(__DIR__ . "/../Auth.php");
require_once(__DIR__ . "/../CONTROLLERS/UploadController.php");

// =============================================================================================
// RUN
// =============================================================================================
main();

// =============================================================================================
// FUNCTION
// =============================================================================================
function main()
{
    try {
        requireRole(ACCOUNT_ROLE);
        if (!(isset($_GET['type']) && !empty($_GET['type']))) {
            throw new Exception("Jenis media tidak tertulis, tidak dapat memproses ");
        }
        $type = $_GET['type'];
        $media_address = retrieveMedia($type);
        $response = array(
            "status" => "success",
            "data" => $media_address
        );
    } catch (Exception $e) {
        $response = array(
            "status" => "success",
            "message" => $e->getMessage()
        );
    } finally {
        echo json_encode($response);
        //echo "<img src='{$media_address}'>";
    }
}

function retrieveMedia($type): string
{
    switch ($type) {
        case MEDIA_TYPE[0]:
            return retrieveProfilePicture();
            break;
        default:
            throw new Exception("Jenis media tidak tertulis, tidak dapat memproses ");
    }
}


function retrieveProfilePicture(): string
{
    if (!(isset($_GET['code']) && !empty($_GET['code']))) {
        throw new Exception("code tidak ada, tidak dapat memproses ");
    }
    if (!(isset($_GET['jenis']) && !empty($_GET['jenis']))) {
        throw new Exception("Jenis akun tidak tertulis, tidak dapat memproses ");
    }

    if (!(isset($_GET['foto_extention']) && !empty($_GET['foto_extention']))) {
        throw new Exception("foto_extention tidak tertulis, tidak dapat memproses ");
    }
    $code = $_GET['code'];
    $jenis = $_GET['jenis'];
    $ext = $_GET['foto_extention'];
    $address = "DATABASE/PROFILE/{$jenis}/{$code}.{$ext}";
    if(!file_exists("../" .$address)){
        throw new Exception("Tidak menemukan file");
    }
    return API_ADDRESS . $address;
}