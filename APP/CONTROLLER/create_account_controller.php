<?php

require_once(__DIR__ .'/../MODELS/Akun.php');
require_once(__DIR__ .'/../MODELS/Dosen.php');
require_once(__DIR__ .'/../MODELS/Mahasiswa.php');

use MODELS\Akun;
use MODELS\Dosen;
use MODELS\Mahasiswa;

session_start();

// DEFINE ========================================================================================================================
define("MAX_IMAGE_SIZE", 10); // dalam MB
define("CREATE_ACCOUNT_PAGE_ADDRESS", "../ADMIN/buat_akun.php");
define("ENUM_JENIS", array("MAHASISWA", "DOSEN", "ADMIN"));


// MAIN LOGIC ====================================================================================================================
main();

// FUNCTION ======================================================================================================================
function main()
{
    //print_r($_POST);
    try {
        CheckAccountIntegrity();
        if ($_POST['jenis'] == ENUM_JENIS[0]) {
            $akun = CreateMahasiswa();
        } else if ($_POST['jenis'] == ENUM_JENIS[1]) {
            $akun = CreateDosen();
        } else {
            throw new Exception("Fatal Error");
        }
        $_SESSION['success_msg'] = "Berhasil membuat akun";
        //print_r($akun);
        header("Location: " . CREATE_ACCOUNT_PAGE_ADDRESS);
    } catch (Exception $e) {
        $_SESSION['error_msg'] = $e->getMessage();
        //print_r($e);
        header("Location: " . CREATE_ACCOUNT_PAGE_ADDRESS);
    }
}

function CheckAccountIntegrity()
{
    if (!isset($_SESSION['currentAccount'])) {
        header("Location: ../PAGES/login.php");
    }

    $currentAccount = $_SESSION['currentAccount'];

    if (!($currentAccount->getJenis() == 'ADMIN')) {
        header("Location: ../ERROR/error.php?code=403&msg=Anda tidak memiliki akses terhadap halaman ini!");
    }
}

function CreateMahasiswa()
{
    if (
        isset($_POST['username']) &&
        isset($_POST['password']) &&
        isset($_POST['nrp']) &&
        isset($_POST['nama']) &&
        isset($_POST['gender']) &&
        isset($_POST['tanggal']) &&
        isset($_POST['angkatan']) &&
        isset($_FILES['foto'])
    ) {
        CheckUploaddedImage();
        $username = $_POST['username'];
        $password = $_POST['password'];
        $nrp = $_POST['nrp'];
        $nama = $_POST['nama'];
        $gender = $_POST['gender'];
        $tanggal_lahir = $_POST['tanggal'];
        $angkatan = $_POST['angkatan'];
        $extention = strtolower(pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION));

        $mahasiswa = new Mahasiswa($username, $nama, $nrp, $tanggal_lahir, $gender, $angkatan, $extention);
        $mahasiswa->CreateMahasiswaInDatabase($password);
        SaveUploadedImage(ENUM_JENIS[0], $username);
        return $mahasiswa;
    } else {
        echo "DISINI WOY";
        throw new Exception("Data tidak lengkap, mohon lengkapi pengisian data");
    }
}


function CreateDosen()
{
    if (
        isset($_POST['username']) &&
        isset($_POST['password']) &&
        isset($_POST['npk']) &&
        isset($_POST['nama']) &&
        isset($_FILES['foto'])
    ) {
        CheckUploaddedImage();
        $username = $_POST['username'];
        $password = $_POST['password'];
        $npk = $_POST['npk'];
        $nama = $_POST['nama'];
        $extention = strtolower(pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION));

        $dosen = new Dosen($username, $nama, $npk, $extention);
        $dosen->CreateDosenInDatabase($password);
        SaveUploadedImage(ENUM_JENIS[1], $username);
        return $dosen;
    } else {
        throw new Exception("Data tidak lengkap, mohon lengkapi pengisian data");
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

function SaveUploadedImage($jenis, $username, $nrp = "", $npk = "")
{
    try {
        $address = "../DATABASE/{$jenis}/";

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
    } catch (Exception $e) {
        throw new Exception("Gagal menyimpan file: " + $e->getMessage());
    }
}
