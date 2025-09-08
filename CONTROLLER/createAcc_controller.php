<?php

require_once __DIR__ . '/../MODELS/Akun.php';
require_once __DIR__ . '/../MODELS/Dosen.php';
require_once __DIR__ . '/../MODELS/Mahasiswa.php';

use MODELS\Akun;
use MODELS\Dosen;
use MODELS\Mahasiswa;
use Exception;

session_start();

// Cek Akun dan Jenis Akun
if (!isset($_SESSION['currentAccount'])) {
    header("Location: ../PAGES/login.php");
}

$currentAccount = $_SESSION['currentAccount'];

if (!($currentAccount->getJenis() == 'ADMIN')) {
    header("Location: ../ERROR/error.php?code=403&msg=Anda tidak memiliki akses terhadap halaman ini!");
}

// Function
function CreateMahasiswa()
{
    if (
        isset($_POST['username']) &&
        isset($_POST['password']) &&
        isset($_POST['nrp']) &&
        isset($_POST['nama']) &&
        isset($_POST['gender']) &&
        isset($_POST['tanggal']) &&
        isset($_POST['angkatan'])
    ) {
        $username = $_POST['username'];
        $password = $_POST['password'];
        $nrp = $_POST['nrp'];
        $nama = $_POST['nama'];
        $gender = $_POST['gender'];
        $tanggal_lahir = $_POST['tanggal'];
        $angkatan = $_POST['angkatan'];

        $foto_extention = "jpg";

        $mahasiswa = new Mahasiswa($username,$nama,$nrp,$tanggal_lahir,$gender,$angkatan,$foto_extention);
        $mahasiswa->SignUp($password);
    } else {
        header("Location: ../ADMIN/daftar_akun.php");
    }
}


function CreateDosen() {
    if (
        isset($_POST['username']) &&
        isset($_POST['password']) &&
        isset($_POST['nrp']) &&
        isset($_POST['nama']) &&
        isset($_POST['gender']) &&
        isset($_POST['tanggal']) &&
        isset($_POST['angkatan'])
    ) {
        $username = $_POST['username'];
        $password = $_POST['password'];
        $nrp = $_POST['nrp'];
        $nama = $_POST['nama'];
        $gender = $_POST['gender'];
        $tanggal_lahir = $_POST['tanggal'];
        $angkatan = $_POST['angkatan'];

        $foto_extention = "jpg";

        $mahasiswa = new Mahasiswa($username,$nama,$nrp,$tanggal_lahir,$gender,$angkatan,$foto_extention);
        $mahasiswa->SignUp($password);
    } else {
        header("Location: ../ADMIN/daftar_akun.php");
    }}

// Logic
if ($_POST['jenis'] == "MAHASISWA") {
    CreateMahasiswa();
} elseif ($_POST['jenis'] == "DOSEN") {
    CreateMahasiswa();
}
