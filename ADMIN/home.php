<?php

require_once('../MODELS/Akun.php');

use MODELS\Akun;

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
    <title>University Hub Admin - Home</title>
</head>
<body>
    <h1>Hi, Admin!</h1>
    <a href="buat_akun.php">Buat Akun Baru</a>
</body>
</html>