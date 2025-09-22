<?php
require_once("../MODELS/Akun.php");

use MODELS\Akun;

session_start();

define("JQUERY_ADDRESS", "../SCRIPTS/jquery-3.7.1.min.js");

if (!isset($_SESSION['currentAccount'])) {
    header("Location: ../PAGES/login.php");
}

$currentAccount = $_SESSION['currentAccount'];

if (!($currentAccount->getJenis() == 'ADMIN')) {
    header("Location: ../ERROR/error.php?code=403&msg=Anda tidak memiliki akses terhadap halaman ini!");
}


if (isset($_GET['jenis'])) {
    $jenis = $_GET['jenis'];
} else {
    $jenis = "Mahasiswa";
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>University Hub Admin - Buat Akun</title>
    <script src="<?php echo JQUERY_ADDRESS; ?>"></script>
</head>

<body>
    <h1>Buat Akun <?php echo $jenis ?> Baru</h1>

    <form id="selector-jenis">
        <label for="jenis">Buat Akun apa?</label><br>
        <input type="radio" name="jenis" value="Mahasiswa" <?php if ($jenis == "Mahasiswa") echo "checked"; ?>>Mahasiswa</input>
        <input type="radio" name="jenis" value="Dosen" <?php if ($jenis == "Dosen") echo "checked"; ?>>Dosen</input>
    </form>

    <form action="" method="POST">
        <input type="hidden" value="<?php echo $jenis ?>">
        <label for="username">Username</label><br>
        <input type="text" name="username" id="username" placeholder="Isi username akun"><br>
        <label for="password">Password</label><br>
        <input type="text" name="username" id="username" placeholder="Isi password awal akun"><br>
        <label for="nama">Nama <?php echo $jenis; ?></label><br>
        <input type="text" name="nama" id="nama" placeholder="Isi nama pemilik akun"><br>
        <?php
        if ($jenis == "Mahasiswa") {
            echo  <<<HTML
            <label for="nrp">NRP Mahasiswa</label><br>
            <input type="text" name="nrp" id="nrp" placeholder="Masukkan NRP mahasiswa"><br>
            <label for="gender">Gender Mahasiswa</label><br>
            <input type="radio" name="gender" id="gender" value="Pria" placeholder="Masukkan gender mahasiswa">Pria</input>
            <input type="radio" name="gender" id="gender" value="Wanita" placeholder="Masukkan gender mahasiswa">Wanita</input><br>
            <label for="tanggalLahir">Tanggal lahir Mahasiswa</label><br>
            <input type="date" name="tanggalLahir" id="tanggalLahir"><br>
            <label for="angkatan">Tahun Angkatan Mahasiswa</label><br>
            <input type="number" name="angkatan" id="angkatan" placeholder="Masukkan tahun angkatan mahasiswa"><br>
            <label for="foto">Foto Mahasiswa</label><br>
            <input type="file" name="foto" id="foto" placeholder="Masukkan foto mahasiswa"><br>
    HTML;
        } else if ($jenis == "Dosen") {
            echo  <<<HTML
            <label for="npk">NPK Dosen</label><br>
            <input type="text" name="npk" id="npk" placeholder="Masukkan NPK dosen"><br>
            <label for="foto">Foto Dosen</label><br>
            <input type="file" name="foto" id="foto" placeholder="Masukkan foto dosen"><br>
    HTML;
        }
        ?>
        <input type="submit">
    </form>
    <br>
    <a href="home.php">Kembali ke Home</a>
</body>
<script>
    $(document).ready(function() {
        $('input[name="jenis"]').on('change', function() {
            let selected = $(this).val();
            window.location.href = "?jenis=" + selected;
        });
    });
</script>

</html>