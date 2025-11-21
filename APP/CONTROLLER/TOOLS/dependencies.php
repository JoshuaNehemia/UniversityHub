<?php

require_once(__DIR__ . '/../../MODELS/Akun.php');
require_once(__DIR__ . '/../../MODELS/Dosen.php');
require_once(__DIR__ . '/../../MODELS/Mahasiswa.php');

use MODELS\Akun;
use MODELS\Dosen;
use MODELS\Mahasiswa;

function checkAccess(array $allowed)
{
    //echo "ACCESS GRANTED";
    global $login_address;
    if (!isset($_SESSION['currentAccount'])) {
        throw new Exception("Tidak ada akun yang ter-logged-in");
    }
    if (!is_array($allowed)) {
        throw new Exception("Daftar akun yang diijinkan bukanlah array");
    }

    $akun = $_SESSION['currentAccount'];
    $jenis = $akun->getJenis();

    if (in_array($jenis, $allowed)) {
        return true;
    } else {
        return false;
    }
}
