<?php

require_once('../MODELS/Akun.php');
require_once('../MODELS/Dosen.php');
require_once('../MODELS/Mahasiswa.php');

use MODELS\Akun;
use MODELS\Dosen;
use MODELS\Mahasiswa;

session_start();

// DEFINE ========================================================================================================================
define("ADMIN_HOME_PAGE_ADDRESS", "../ADMIN/home.php");
define("HOME_PAGE_ADDRESS", "../PAGES/home.php");
define("LOGIN_PAGE_ADDRESS", "../PAGES/login.php");
define("ENUM_JENIS", array("MAHASISWA", "DOSEN", "ADMIN"));
define("PICTURE_DATABASE", "../DATABASE/");
// MAIN LOGIC ====================================================================================================================
main();

// FUNCTION ======================================================================================================================
function main()
{
    try {
        if (IsLoggedIn()) {
            $currentAccount = $_SESSION['currentAccount'];
            if ($currentAccount->getJenis() === "ADMIN") {
                header("Location: " . ADMIN_HOME_PAGE_ADDRESS);
            } else {
                header("Location: " . HOME_PAGE_ADDRESS);
            }
        } else if (IsLoggingIn()) {
            LogIn();
        } else {
            header("Location: " . LOGIN_PAGE_ADDRESS);
        }
    } catch (Exception $e) {
        $_SESSION['error_msg'] = $e->getMessage();
        header("Location: " . LOGIN_PAGE_ADDRESS);
    } finally {
        exit();
    }
}

function IsLoggedIn()
{
    return isset($_SESSION['currentAccount']);
}

function IsLoggingIn()
{
    return isset($_POST['username']) && isset($_POST['password']);
}

function LogIn()
{
    $currentAccount = Akun::LogIn_Akun($_POST['username'], $_POST['password']);
    if (!$currentAccount) {
        throw new Exception("Username atau password salah");
    }

    switch ($currentAccount->getJenis()) {
        case ENUM_JENIS[2]:
            Admin_LogIn_Proses($currentAccount);
            break;
        case ENUM_JENIS[0]:
            Mahasiswa_LogIn_Proses($currentAccount);
            break;
        case ENUM_JENIS[1]:
            Dosen_LogIn_Proses($currentAccount);
            break;
        default:
            throw new Exception("Data akun anda mengalami kesalahan");
    }
}


function Admin_LogIn_Proses($currentAccount)
{
    $_SESSION['currentAccount'] = $currentAccount;
    header("Location: " . ADMIN_HOME_PAGE_ADDRESS);
}

function Mahasiswa_LogIn_Proses($currentAccount)
{
    $currentAccount = Mahasiswa::LogIn_Mahasiswa($_POST['username'], $_POST['password']);
    $currentAccount->setFotoAddress(PICTURE_DATABASE . $currentAccount->getJenis() . "/" . $currentAccount->getUsername() . "." . $currentAccount->getFotoExtention());
    $_SESSION['currentAccount'] = $currentAccount;
    header("Location: " . HOME_PAGE_ADDRESS);
}

function Dosen_LogIn_Proses($currentAccount)
{
    $currentAccount = Dosen::logIn_Dosen($_POST['username'], $_POST['password']);
    $currentAccount->setFotoAddress(PICTURE_DATABASE . $currentAccount->getJenis() . "/" . $currentAccount->getUsername() . "." . $currentAccount->getFotoExtention());
    $_SESSION['currentAccount'] = $currentAccount;
    header("Location: " . HOME_PAGE_ADDRESS);
}
