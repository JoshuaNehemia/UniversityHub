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
define("ENUM_JENIS", array("ADMIN", "MAHASISWA", "DOSEN"));

// MAIN LOGIC
main();

// FUNCTIONS
function main()
{
    try {
        // VALIDATION
        checkAccountIntegrity();
        $jenis = checkDataIntegrity();

        // UPDATE DATA
        updateData($jenis);

        // SUCCESS
        $_SESSION['success_msg'] = "Data berhasil diperbarui.";
        header("Location: " . BACK_PAGE_ADDRESS);
        exit();
    } catch (Exception $e) {
        throwErrorBackToPage($e, BACK_PAGE_ADDRESS);
    }
}

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
    $requiredFields = ['nrp', 'oldnrp', 'nama', 'gender', 'tanggal_lahir', 'angkatan'];

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

function updateData($jenis)
{
    switch ($jenis) {
        case ENUM_JENIS[1]: // MAHASISWA
            $foto_extension = manageImage($jenis,$_POST['nrp'],"");
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
            $foto_extension = manageImage($jenis,"",$_POST['npk']);
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

function manageImage($jenis, $nrp, $npk)
{
    if (isset($_FILES['foto'])) {
        CheckUploaddedImage();
        $extension = SaveUploadedImage($jenis, $nrp, $npk);
        return $extension;
    } else {
        return $_POST['oldext'];
    }
}

function CheckUploaddedImage()
{
    $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];
    if (isset($_FILES['foto'])) {
        if (empty($_FILES['foto']) || $_FILES['foto']['error'] !== UPLOAD_ERR_OK) {
            throw new Exception("File foto tidak dapat diupload, mohon gunakan foto lain.");
        }

        $extension = strtolower(pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION));

        if (!in_array($extension, $allowedExtensions, true)) {
            throw new Exception("File foto tidak dalam format yang diizinkan " . implode(", ", $allowedExtensions));
        }

        if ($_FILES['foto']['size'] > (MAX_IMAGE_SIZE * 1024 * 1024)) {
            throw new Exception("File foto terlalu besar melebihi " . MAX_IMAGE_SIZE . " MB");
        }
    } else {
        throw new Exception("Mohon upload foto terlebih dahulu.");
    }
}

function SaveUploadedImage($jenis, $nrp = "", $npk = "")
{
    try {
        $address = PICTURE_DATABASE . "{$jenis}/";

        if ($jenis == ENUM_JENIS[0]) {
            if (!is_dir($address)) {
                if (!mkdir($address, 0777, true)) {
                    throw new Exception("Gagal membuat folder upload.");
                }
            }
            $fileTmp  = $_FILES['foto']['tmp_name'];
            $fileName = $_FILES['foto']['name'];
            $extention = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
            $address = $address . $nrp . "." . $extention;
        } else if ($jenis == ENUM_JENIS[1]) {
            $address = $address . $npk . "/";
            if (!is_dir($address)) {
                if (!mkdir($address, 0777, true)) {
                    throw new Exception("Gagal membuat folder upload.");
                }
            }
            $fileTmp  = $_FILES['foto']['tmp_name'];
            $fileName = $_FILES['foto']['name'];
            $extention = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
            $address = $address . $npk . "." . $extention;
        }

        move_uploaded_file($fileTmp, $address);
        return $extention;
    } catch (Exception $e) {
        throw new Exception("Gagal menyimpan file: " + $e->getMessage());
    }
}
