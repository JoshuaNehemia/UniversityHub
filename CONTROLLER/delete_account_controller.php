<?php
require_once(__DIR__ . '/../MODELS/Akun.php');
require_once(__DIR__ . '/../MODELS/Dosen.php');
require_once(__DIR__ . '/../MODELS/Mahasiswa.php');

use MODELS\Akun;
use MODELS\Dosen;
use MODELS\Mahasiswa;

session_start();

// DEFINE ========================================================================================================================
define("LOGIN_PAGE_ADDRESS", "../PAGES/login.php");
define("DAFTAR_AKUN_PAGE_ADDRESS", "../ADMIN/daftar_akun.php");

// MAIN LOGIC ====================================================================================================================
main();

// FUNCTION ======================================================================================================================
function main()
{
    try {
        CheckAccountIntegrity();

        $username = $_GET['username'];
        $jenis = strtoupper($_GET['jenis']);  // "DOSEN" / "MAHASISWA"

        if ($jenis === "DOSEN") {
            Dosen::DeleteDosenInDatabase($username);
        } elseif ($jenis === "MAHASISWA") {
            Mahasiswa::DeleteMahasiswaInDatabase($username);
        } else {
            throw new Exception("Jenis akun tidak valid.");
        }

        $_SESSION['success_msg'] = "Akun $username berhasil dihapus.";
        header("Location: " . DAFTAR_AKUN_PAGE_ADDRESS);
        exit();
    } catch (Exception $e) {
        $_SESSION['error_msg'] = $e->getMessage();
        header("Location: " . DAFTAR_AKUN_PAGE_ADDRESS);
        exit();
    }
}

function CheckAccountIntegrity()
{
    if (!isset($_SESSION['currentAccount'])) {
        header("Location: " . LOGIN_PAGE_ADDRESS);
    }

    $currentAccount = $_SESSION['currentAccount'];
    if ($currentAccount->getJenis() !== 'ADMIN') {
        header("Location: ../ERROR/error.php?code=403&msg=Anda tidak memiliki akses terhadap halaman ini!");
    }
}
