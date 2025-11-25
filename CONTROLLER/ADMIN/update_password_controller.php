<?php
// IMPORT
require_once(__DIR__ . '/../../MODELS/Akun.php');
use MODELS\Akun;

session_start();

// DEFINE
define("BACK_PAGE_ADDRESS", "../../VIEW/ADMIN/daftar_akun.php");
define("LOGIN_PAGE_ADDRESS", "../../VIEW/login.php");

// MAIN LOGIC
main();

// =========================================================
// MAIN FUNCTION
// =========================================================
function main()
{
    try {
        checkAccountIntegrity();
        checkDataIntegrity();

        updatePassword($_POST['username'], $_POST['pwd']);

        $_SESSION['success_msg'] = "Password berhasil diperbarui.";
        header("Location: " . BACK_PAGE_ADDRESS . "?jenis=" . $_POST['jenis']);
        exit();
    } catch (Exception $e) {
        throwErrorBackToPage($e, BACK_PAGE_ADDRESS . "?jenis=" . $_POST['jenis']);
    }
}

// =========================================================
// HELPER FUNCTIONS
// =========================================================

function throwErrorBackToPage(Exception $e, $address)
{
    $_SESSION['error_msg'] = $e->getMessage();
    header("Location: " . $address);
    exit();
}

function checkAccountIntegrity()
{
    if (!isset($_SESSION['currentAccount'])) {
        header("Location: " . LOGIN_PAGE_ADDRESS);
        exit();
    }

    $currentAccount = $_SESSION['currentAccount'];

    if ($currentAccount->getJenis() !== 'ADMIN') {
        header("Location: ../ERROR/error.php?code=403&msg=Anda tidak memiliki akses terhadap halaman ini!");
        exit();
    }
}

function checkDataIntegrity()
{
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception("Metode request tidak valid.");
    }

    if (!(isset($_POST['username']) && isset($_POST['pwd']) && isset($_POST['confirm']))) {
        throw new Exception("Data tidak lengkap. Tidak ada password yang diganti.");
    }

    if ($_POST['pwd'] !== $_POST['confirm']) {
        throw new Exception("Pastikan password dan konfirmasi password memiliki isi yang sama.");
    }
}

function updatePassword($username, $newpassword)
{
    $akun = new Akun($username, "ADMIN", "ADMIN");
    $akun->updatePassword($newpassword);
}
