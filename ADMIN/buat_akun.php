<?php

require_once __DIR__ . '/../MODELS/Akun.php';
require_once __DIR__ . '/../MODELS/Dosen.php';
require_once __DIR__ . '/../MODELS/Mahasiswa.php';

use MODELS\Akun;
use MODELS\Dosen;
use MODELS\Mahasiswa;

session_start();

if(!isset($_SESSION['currentAccount'])){
    header("Location: ../PAGES/login.php");
}

$currentAccount = $_SESSION['currentAccount'];

if(!($currentAccount->getJenis()=='ADMIN')){
    header("Location: ../ERROR/error.php?code=403&msg=Anda tidak memiliki akses terhadap halaman ini!");
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>University Hub Admin - Buat Akun</title>
</head>
<body>
    <h1>Buat Akun Baru</h1>


    
    <a href="home.php">Kembali ke Home</a>
</body>
</html>