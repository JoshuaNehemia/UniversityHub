<?php

require_once('../MODELS/Akun.php');
require_once('../MODELS/Dosen.php');
require_once('../MODELS/Mahasiswa.php');

use MODELS\Akun;
use MODELS\Dosen;
use MODELS\Mahasiswa;

session_start();
if (isset($_SESSION['currentAccount'])) {
    unset($_SESSION['currentAccount']);
}

// DEFINE ========================================================================================================================
define("ADMIN_PAGE_ADDRESS", "../../VIEW/ADMIN/");
define("USER_PAGE_ADDRESS", "../../VIEW/USER/");
define("LOGIN_PAGE_ADDRESS", "../../VIEW/login.php");
define("PICTURE_DATABASE", "../../../../DATABASE/");
define("ENUM_JENIS", array("ADMIN", "MAHASISWA", "DOSEN"));

// MAIN LOGIC ====================================================================================================================
main();

// FUNCTION ======================================================================================================================
function main()
{
    try {
        if (IsLoggedIn()) {
            $currentAccount = $_SESSION['currentAccount'];
            if ($currentAccount->getJenis() === ENUM_JENIS[0]) {
                header("Location: " . ADMIN_PAGE_ADDRESS);
            } else {
                header("Location: " . USER_PAGE_ADDRESS);
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
    $jenis = Akun::getAccountRole($_POST['username']);

    switch ($jenis) {
        case ENUM_JENIS[0]:
            Admin_LogIn_Proses();
            break;
        case ENUM_JENIS[1]:
            Mahasiswa_LogIn_Proses();
            break;
        case ENUM_JENIS[2]:
            Dosen_LogIn_Proses();
            break;
        default:
            throw new Exception("Data akun anda mengalami kesalahan");
    }
}


function Admin_LogIn_Proses()
{
    $currentAccount = Akun::logIn($_POST['username'], $_POST['password']);
    $_SESSION['currentAccount'] = $currentAccount;
    header("Location: " . ADMIN_PAGE_ADDRESS . $_POST['from']);
}

function Mahasiswa_LogIn_Proses()
{
    $currentAccount = Mahasiswa::LogIn_Mahasiswa($_POST['username'], $_POST['password']);
    $currentAccount->setFotoAddress(PICTURE_DATABASE . $currentAccount->getJenis() . "/" . $currentAccount->getNRP() . "." . $currentAccount->getFotoExtention());
    $_SESSION['currentAccount'] = $currentAccount;
    header("Location: " . USER_PAGE_ADDRESS . $_POST['from']);
}

function Dosen_LogIn_Proses()
{
    $currentAccount = Dosen::logIn_Dosen($_POST['username'], $_POST['password']);
    $currentAccount->setFotoAddress(PICTURE_DATABASE . $currentAccount->getJenis() . "/" . $currentAccount->getNPK() . "." . $currentAccount->getFotoExtention());
    $_SESSION['currentAccount'] = $currentAccount;
    header("Location: " . USER_PAGE_ADDRESS . $_POST['from']);
}
