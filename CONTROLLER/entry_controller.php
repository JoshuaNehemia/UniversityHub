<?php

require_once __DIR__ . '/../MODELS/Akun.php';
require_once __DIR__ . '/../MODELS/Dosen.php';
require_once __DIR__ . '/../MODELS/Mahasiswa.php';

use MODELS\Akun;
use MODELS\Dosen;
use MODELS\Mahasiswa;

session_start();

// Function ===================================================================================================
function IsLoggedIn()
{
    return isset($_SESSION['currentAccount']);
}

function IsLoggingIn()
{
    return isset($_POST['username']) && isset($_POST['password']);
}

function Transfering($currentAccount)
{
    $_SESSION['currentAccount'] = $currentAccount;
    if ($currentAccount->getJenis() == 'ADMIN') {
        header('Location: ../ADMIN/home.php');
    } else if ($currentAccount->getJenis() == 'MAHASISWA') {
        if (!($currentAccount instanceof Mahasiswa)) {
            $currentAccount = Mahasiswa::LogIn($_POST['username'], $_POST['password']);
        }
        if (is_null($currentAccount)) {
            header("Location: ../PAGES/login.php?error_msg=2");
            exit();
        } else {
            $_SESSION['currentAccount'] = $currentAccount;
            header('Location: ../PAGES/home.php');
            exit();
        }
    } else if ($currentAccount->getJenis() == 'DOSEN') {
        if (!($currentAccount instanceof Dosen)) {
            $currentAccount = Dosen::LogIn($_POST['username'], $_POST['password']);
        }
        if (is_null($currentAccount)) {
            header("Location: ../PAGES/login.php?error_msg=2");
            exit();
        } else {
            $_SESSION['currentAccount'] = $currentAccount;
            header('Location: ../PAGES/home.php');
            exit();
        }
    } else {
        header("Location: ../PAGES/login.php?error_msg=2");
        exit();
    }
}

function login()
{
    // Ambil dari Database
    if (IsLoggingIn()) {
        $currentAccount = Akun::LogIn($_POST['username'], $_POST['password']);
        if (is_null($currentAccount)) {
            header("Location: ../PAGES/login.php?error_msg=1");
            exit();
        }
        // Log In Sebagai Apa?
        Transfering($currentAccount);
    } else {
        header("Location: ../PAGES/login.php");
        exit();
    }
}


// RUNNABLE ====================================================================================================

//Check user udah pernah logged in apa tidak di session
if (IsLoggedIn()) {
    if (IsLoggingIn()) {
        session_destroy();
        login();
    }
    Transfering($_SESSION['currentAccount']);
} else {
    login();
}
