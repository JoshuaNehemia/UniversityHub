<?php

require_once(__DIR__ . '/../../MODELS/Akun.php');
require_once(__DIR__ . '/../../MODELS/Dosen.php');
require_once(__DIR__ . '/../../MODELS/Mahasiswa.php');

use MODELS\Akun;
use MODELS\Dosen;
use MODELS\Mahasiswa;
// DEFINE ===============================================================================================================
define("CURRENT_ACCOUNT", "currentAccount");
define("ACCOUNT_ROLES", array("MAHASISWA", "DOSEN", "ADMIN"));
define("MAX_IMAGE_SIZE", 2); // dalam MB
define("JQUERY_ADDRESS", "../../../SCRIPTS/jquery-3.7.1.min.js");
define("PICTURE_DATABASE", "../../../DATABASE/");

// FUNCTION =============================================================================================================
function startSession()
{
    if (session_status() == PHP_SESSION_NONE) {
        // Session belum dibuat
        session_start();
    } elseif (session_status() == PHP_SESSION_ACTIVE) {
        //Session udah ada
    } else {
        //Session disabled
    }
}

function checkAccess(array $allowed)
{
    //echo "ACCESS GRANTED";
    if (!isset($_SESSION[CURRENT_ACCOUNT])) {
        throw new Exception("Tidak ada akun yang ter-logged-in");
    }
    if (!is_array($allowed)) {
        throw new Exception("Daftar akun yang diijinkan bukanlah array");
    }

    $akun = $_SESSION[CURRENT_ACCOUNT];
    $jenis = $akun->getJenis();

    if (in_array($jenis, $allowed)) {
        return true;
    } else {
        return false;
    }
}

function showAccountProfilePicture($pfp_file_name)
{
    $jenis = $_SESSION[CURRENT_ACCOUNT]->getJenis();
    $picture_database_address = "../../../DATABASE/" . $jenis . "/";
    $pfp_address = $picture_database_address . "." . $pfp_file_name;
    $alt = $_SESSION[CURRENT_ACCOUNT]->getNama();
    echo "<img src='{$pfp_address}' alt='{$alt} profile picture'>";
}

function addPaging($current_page, $total_data, $num_data_displayed, $num_page_displayed) {}

function showPageTitle($page_title)
{
    echo "<title>UniversityHub | {$page_title}<title>";
}
