<?php
// IMPORT
require_once(__DIR__ . '/../../MODELS/Akun.php');
require_once(__DIR__ . '/../../MODELS/Dosen.php');
require_once(__DIR__ . '/../../MODELS/Mahasiswa.php');

use MODELS\Akun;
use MODELS\Dosen;
use MODELS\Mahasiswa;

session_start();

// DEFINE
define("BACK_PAGE_ADDRESS", "../../VIEW/ADMIN/daftar_akun.php");
define("LOGIN_PAGE_ADDRESS", "../../VIEW/login.php");
define("PICTURE_DATABASE", "../../../DATABASE/");
define("MAX_IMAGE_SIZE", 2); // in MB
define("ENUM_JENIS", array("ADMIN", "MAHASISWA", "DOSEN"));

// MAIN LOGIC
main();

// =========================================================
// MAIN FUNCTION
// =========================================================
function main()
{
    try {
        checkAccountIntegrity();
        $jenis = checkDataIntegrity();
        updateData($jenis);

        $_SESSION['success_msg'] = "Data berhasil diperbarui.";
        header("Location: " . BACK_PAGE_ADDRESS);
        exit();
    } catch (Exception $e) {
        throwErrorBackToPage($e, BACK_PAGE_ADDRESS);
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

    if (!isset($_POST['jenis'])) {
        throw new Exception("Jenis akun tidak ditemukan.");
    }

    $jenis = strtoupper(trim($_POST['jenis']));

    switch ($jenis) {
        case ENUM_JENIS[1]: // MAHASISWA
            checkMahasiswaDataIntegrity();
            break;
        case ENUM_JENIS[2]: // DOSEN
            checkDosenDataIntegrity();
            break;
        default:
            throw new Exception("Jenis akun tidak valid.");
    }

    return $jenis;
}

function checkMahasiswaDataIntegrity()
{
    $requiredFields = ['nrp', 'oldnrp', 'nama', 'gender', 'tanggal', 'angkatan'];

    foreach ($requiredFields as $field) {
        if (!isset($_POST[$field]) || trim($_POST[$field]) === '') {
            throw new Exception("Data mahasiswa tidak lengkap: $field kosong.");
        }
    }
}

function checkDosenDataIntegrity()
{
    $requiredFields = ['npk', 'oldnpk', 'nama'];

    foreach ($requiredFields as $field) {
        if (!isset($_POST[$field]) || trim($_POST[$field]) === '') {
            throw new Exception("Data dosen tidak lengkap: $field kosong.");
        }
    }
}

// =========================================================
// UPDATE DATA LOGIC
// =========================================================
function updateData($jenis)
{
    switch ($jenis) {
        case ENUM_JENIS[1]: // MAHASISWA
            $foto_extension = manageImage($jenis, $_POST['nrp'], "");
            $mhs = new Mahasiswa(
                $_POST['username'],
                $_POST['nama'],
                $_POST['nrp'],
                $_POST['tanggal'],
                $_POST['gender'],
                $_POST['angkatan'],
                $foto_extension
            );
            $mhs->UpdateMahasiswaInDatabase($_POST['oldnrp']);
            break;

        case ENUM_JENIS[2]: // DOSEN
            $foto_extension = manageImage($jenis, "", $_POST['npk']);
            $dsn = new Dosen(
                $_POST['username'],
                $_POST['nama'],
                $_POST['npk'],
                $foto_extension
            );
            $dsn->UpdateDosenInDatabase($_POST['oldnpk']);
            break;
    }
}

// =========================================================
// IMAGE MANAGEMENT
// =========================================================

function manageImage($jenis, $nrp = "", $npk = "")
{
    // If no file uploaded at all
    if (!isset($_FILES['foto']) || $_FILES['foto']['error'] === UPLOAD_ERR_NO_FILE) {
        return $_POST['oldext'] ?? null;
    }

    // Validate file
    CheckUploaddedImage();

    // Save & return extension
    return SaveUploadedImage($jenis, $nrp, $npk);
}

function CheckUploaddedImage()
{
    $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];

    if (empty($_FILES['foto']) || $_FILES['foto']['error'] !== UPLOAD_ERR_OK) {
        throw new Exception("File foto tidak dapat diupload, mohon gunakan foto lain.");
    }

    $extension = strtolower(pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION));

    if (!in_array($extension, $allowedExtensions, true)) {
        throw new Exception("Format foto tidak diizinkan. Gunakan: " . implode(", ", $allowedExtensions));
    }

    if ($_FILES['foto']['size'] > (MAX_IMAGE_SIZE * 1024 * 1024)) {
        throw new Exception("Ukuran file foto melebihi batas " . MAX_IMAGE_SIZE . " MB");
    }
}

function SaveUploadedImage($jenis, $nrp = "", $npk = "")
{
    try {
        $basePath = PICTURE_DATABASE . "{$jenis}/";

        if (!is_dir($basePath)) {
            if (!mkdir($basePath, 0777, true)) {
                throw new Exception("Gagal membuat folder upload.");
            }
        }

        $fileTmp  = $_FILES['foto']['tmp_name'];
        $fileName = $_FILES['foto']['name'];
        $extension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

        // Determine file path
        if ($jenis === ENUM_JENIS[1]) { // MAHASISWA
            $targetPath = $basePath . $nrp . "." . $extension;
        } elseif ($jenis === ENUM_JENIS[2]) { // DOSEN
            $targetPath = $basePath . $npk . "." . $extension;
        } else {
            throw new Exception("Jenis akun tidak valid saat menyimpan gambar.");
        }

        if (!move_uploaded_file($fileTmp, $targetPath)) {
            throw new Exception("Gagal memindahkan file upload.");
        }

        return $extension;
    } catch (Exception $e) {
        throw new Exception("Gagal menyimpan file: " . $e->getMessage());
    }
}
