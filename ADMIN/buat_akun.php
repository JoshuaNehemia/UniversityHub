<?php
require_once("../MODELS/Akun.php");

use MODELS\Akun;

session_start();

// DEFINE ========================================================================================================================
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
    <script src="<?php echo JQUERY_ADDRESS; ?>"></script>
</head>

<body>
    <h1>Buat Akun <?php echo $label ?> Baru</h1>

    <form id="selector-jenis">
        <label for="jenis">Buat Akun apa?</label><br>
        <input type="radio" name="jenis" value="MAHASISWA" <?php if ($jenis === ENUM_JENIS[0]) echo "checked"; ?>>Mahasiswa</input>
        <input type="radio" name="jenis" value="DOSEN" <?php if ($jenis === ENUM_JENIS[1]) echo "checked"; ?>>Dosen</input>
    </form>

    <form action="<?php echo CONTROLLER_ADDRESS; ?>" method="POST" enctype="multipart/form-data">
        <input type="hidden" value="<?php echo $jenis ?>" name="jenis">
        <label for="username">Username</label><br>
        <input type="text" name="username" id="username" placeholder="Isi username akun"><br>
        <label for="password">Password</label><br>
        <input type="text" name="password" id="password" placeholder="Isi password awal akun"><br>
        <label for="nama">Nama <?php echo strtolower($jenis); ?></label><br>
        <input type="text" name="nama" id="nama" placeholder="Isi nama pemilik akun"><br>
        <?php
        if ($jenis === ENUM_JENIS[0]) {
            echo  <<<HTML
            <label for="nrp">NRP {$label}</label><br>
            <input type="text" name="nrp" id="nrp" placeholder="Masukkan NRP mahasiswa"><br>
            <label for="gender">Gender {$label}</label><br>
            <input type="radio" name="gender" id="gender" value="Pria">Pria</input>
            <input type="radio" name="gender" id="gender" value="Wanita">Wanita</input><br>
            <label for="tanggal">Tanggal lahir {$label}</label><br>
            <input type="date" name="tanggal" id="tanggal"><br>
            <label for="angkatan">Tahun Angkatan {$label}</label><br>
            <input type="number" name="angkatan" id="angkatan" placeholder="Masukkan tahun angkatan mahasiswa"><br>
            <label for="foto">Foto {$label}</label><br>
            <input type="file" name="foto" id="foto" accept="image/*"><br>
    HTML;
        } else if ($jenis === ENUM_JENIS[1]) {
            echo  <<<HTML
            <label for="npk">NPK {$label}</label><br>
            <input type="text" name="npk" id="npk" placeholder="Masukkan NPK dosen"><br>
            <label for="foto">Foto {$label}</label><br>
            <input type="file" name="foto" id="foto" accept="image/*"><br>
    HTML;
        }
        ?>
        <input type="submit">
    </form>
    <?php
    // IF ERROR
    if (isset($_SESSION['error_msg'])) {
        echo '<div class="error block">';
        echo '<p class="error message">' . $_SESSION['error_msg'] . '</p>';
        echo '</div>';
        unset($_SESSION['error_msg']);
    } 
    // IF ERROR
    if (isset($_SESSION['success_msg'])) {
        echo '<div class="success block">';
        echo '<p class="success message">' . $_SESSION['success_msg'] . '</p>';
        echo '</div>';
        unset($_SESSION['success_msg']);
    } 
    ?>
    <br>
    <a href="home.php">Kembali ke Home</a>
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
// FUNCTION ======================================================================================================================
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

function CheckNewAccountType()
{
    global $label, $jenis;
    if (isset($_GET['jenis'])) {
        $jenis = $_GET['jenis'];
    } else {
        $jenis = ENUM_JENIS[0];
    }
    $label = strtolower($jenis);
}
?>