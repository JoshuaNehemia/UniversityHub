<?php
require_once("../MODELS/Akun.php");
use MODELS\Akun;
session_start();

define("JQUERY_ADDRESS", "../SCRIPTS/jquery-3.7.1.min.js");
define("CONTROLLER_ADDRESS", "../CONTROLLER/create_account_controller.php");
define("ENUM_JENIS", array("MAHASISWA", "DOSEN", "ADMIN"));

$label = "";
$jenis = "";

CheckAccountIntegrity();
CheckNewAccountType();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>University Hub Admin - Buat Akun</title>
    <link rel="stylesheet" href="../STYLES/root.css">
    <link rel="stylesheet" href="../STYLES/form.css">
    <script src="<?php echo JQUERY_ADDRESS; ?>"></script>
</head>
<body>
    <div class="top-left">
        <a href="home.php" class="admin-btn small">← Kembali ke Home</a>
    </div>
    <div class="admin-wrapper">
        <div class="admin-card-create">
            <h1 class="admin-header center">Buat Akun <?php echo $label ?> Baru</h1>
            <div class="admin-divider"></div>
            <form id="selector-jenis">
                <label for="jenis">Buat Akun apa?</label>
                <div class="radio-group">
                    <label><input type="radio" name="jenis" value="MAHASISWA" <?php if ($jenis === ENUM_JENIS[0]) echo "checked"; ?>> Mahasiswa</label>
                    <label><input type="radio" name="jenis" value="DOSEN" <?php if ($jenis === ENUM_JENIS[1]) echo "checked"; ?>> Dosen</label>
                </div>
            </form>
            <form action="<?php echo CONTROLLER_ADDRESS; ?>" method="POST" enctype="multipart/form-data">
                <input type="hidden" value="<?php echo $jenis ?>" name="jenis">

                <label for="username">Username</label>
                <input type="text" name="username" id="username" placeholder="Isi username akun">

                <label for="password">Password</label>
                <input type="text" name="password" id="password" placeholder="Isi password awal akun">

                <label for="nama">Nama <?php echo strtolower($jenis); ?></label>
                <input type="text" name="nama" id="nama" placeholder="Isi nama pemilik akun">

                <?php if ($jenis === ENUM_JENIS[0]) { ?>
                <label for="nrp">NRP <?php echo $label ?></label>
                <input type="text" name="nrp" id="nrp" placeholder="Masukkan NRP mahasiswa">

                <label>Gender <?php echo $label ?></label>
                <div class="radio-group">
                    <label><input type="radio" name="gender" value="Pria"> Pria</label>
                    <label><input type="radio" name="gender" value="Wanita"> Wanita</label>
                </div>

                <label for="tanggal">Tanggal Lahir <?php echo $label ?></label>
                <input type="date" name="tanggal" id="tanggal">

                <label for="angkatan">Tahun Angkatan <?php echo $label ?></label>
                <input type="number" name="angkatan" id="angkatan" placeholder="Masukkan tahun angkatan mahasiswa">

                <label for="foto">Foto <?php echo $label ?></label>
                <input type="file" name="foto" id="foto" accept="image/*">
                <?php } else if ($jenis === ENUM_JENIS[1]) { ?>
                <label for="npk">NPK <?php echo $label ?></label>
                <input type="text" name="npk" id="npk" placeholder="Masukkan NPK dosen">

                <label for="foto">Foto <?php echo $label ?></label>
                <input type="file" name="foto" id="foto" accept="image/*">
                <?php } ?>

                <input type="submit" class="btn-submit" value="Buat Akun">
            </form>

            <?php if (isset($_SESSION['error_msg'])) { ?>
            <div class="error block">
                <p class="error message"><?php echo $_SESSION['error_msg']; ?></p>
            </div>
            <?php unset($_SESSION['error_msg']); } ?>

            <?php if (isset($_SESSION['success_msg'])) { ?>
            <div class="success block">
                <p class="success message"><?php echo $_SESSION['success_msg']; ?></p>
            </div>
            <?php unset($_SESSION['success_msg']); } ?>
        </div>
    </div>
</body>
</html>

<script>
$(document).ready(function() {
    $('input[name="jenis"]').on('change', function() {
        let selected = $(this).val();
        window.location.href = "?jenis=" + selected;
    });
});
</script>

<?php
function CheckAccountIntegrity(){
    if (!isset($_SESSION['currentAccount'])) {
        header("Location: ../PAGES/login.php");
    }
    $currentAccount = $_SESSION['currentAccount'];
    if (!($currentAccount->getJenis() == 'ADMIN')) {
        header("Location: ../ERROR/error.php?code=403&msg=Anda tidak memiliki akses terhadap halaman ini!");
    }
}
function CheckNewAccountType(){
    global $label, $jenis;
    if (isset($_GET['jenis'])) {
        $jenis = $_GET['jenis'];
    } else {
        $jenis = ENUM_JENIS[0];
    }
    $label = strtolower($jenis);
    }
?>