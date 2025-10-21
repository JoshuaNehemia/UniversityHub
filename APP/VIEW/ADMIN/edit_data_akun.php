<?php
require_once(__DIR__ . "/../../MODELS/Akun.php");
require_once(__DIR__ . "/../../MODELS/Mahasiswa.php");
require_once(__DIR__ . "/../../MODELS/Dosen.php");

use MODELS\Akun;
use MODELS\Mahasiswa;
use MODELS\Dosen;

session_start();

// DEFINE
define("JQUERY_ADDRESS", "../../../SCRIPTS/jquery-3.7.1.min.js");
define("CONTROLLER_ADDRESS", "../../CONTROLLER/ADMIN/update_account_controller.php");
define("ENUM_JENIS", array("ADMIN", "MAHASISWA", "DOSEN"));

// MAIN LOGIC
$akun;
$jenis;
$label;
main();

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Akun - <?php echo htmlspecialchars($akun->getUsername()); ?></title>
    <link rel="stylesheet" href="../STYLES/root.css">
    <link rel="stylesheet" href="../STYLES/form.css">
    <script src="<?php echo JQUERY_ADDRESS; ?>"></script>
</head>

<body>
    <div class="top-left">
        <a href="daftar_akun.php" class="admin-btn small">← Kembali ke Daftar Akun</a>
    </div>

    <div class="admin-wrapper">
        <div class="admin-card-create">
            <h1 class="admin-header center">Edit Data Akun <?php echo ucfirst($label) . " " . htmlspecialchars($akun->getUsername()); ?></h1>
            <div class="admin-divider"></div>

            <form id="form-edit" action="<?php echo CONTROLLER_ADDRESS; ?>" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="jenis" value="<?php echo htmlspecialchars($jenis); ?>">
                <input type="hidden" name="username" value="<?php echo htmlspecialchars($_GET['username']); ?>">
                <input type="hidden" name="oldext" value="<?php echo htmlspecialchars($akun->getFotoExtention()); ?>"><br>

                <?php if ($jenis === "MAHASISWA") { ?>
                    <input type="hidden" name="oldnrp" value="<?php echo htmlspecialchars($akun->getNRP()); ?>"><br>
                    <label for="nama">Nama <?php echo $label; ?></label><br>
                    <input type="text" name="nama" id="nama" value="<?php echo htmlspecialchars($akun->getNama()); ?>"><br>

                    <label for="nrp">NRP</label><br>
                    <input type="text" name="nrp" id="nrp" value="<?php echo htmlspecialchars($akun->getNRP()); ?>"><br>

                    <label for="gender">Gender</label><br>
                    <div class="radio-group">
                        <input type="radio" name="gender" value="Pria" <?php if ($akun->getGender() === "Pria") echo "checked"; ?>> Pria
                        <input type="radio" name="gender" value="Wanita" <?php if ($akun->getGender() === "Wanita") echo "checked"; ?>> Wanita<br>
                    </div>

                    <label for="tanggal">Tanggal Lahir</label><br>
                    <input type="date" name="tanggal" id="tanggal" value="<?php echo htmlspecialchars($akun->getTanggalLahir()); ?>"><br>

                    <label for="angkatan">Tahun Angkatan</label><br>
                    <input type="number" name="angkatan" id="angkatan" value="<?php echo htmlspecialchars($akun->getAngkatan()); ?>"><br>

                    <label for="foto">Foto (kosong = tidak diubah)</label><br>
                    <?php if (!empty($akun->getFotoExtention())): ?>
                        <div>File sekarang: <?php echo htmlspecialchars($akun->getFotoExtention()); ?></div>
                    <?php endif; ?>
                    <input type="file" name="foto" id="foto" accept="image/*"><br>

                <?php } elseif ($jenis === "DOSEN") { ?>
                    <input type="hidden" name="oldnpk" value="<?php echo htmlspecialchars($akun->getNPK()); ?>"><br>
                    <label for="nama">Nama <?php echo $label; ?></label><br>
                    <input type="text" name="nama" id="nama" value="<?php echo htmlspecialchars($akun->getNama()); ?>"><br>

                    <label for="npk">NPK</label><br>
                    <input type="text" name="npk" id="npk" value="<?php echo htmlspecialchars($akun->getNPK()); ?>"><br>

                    <label for="foto">Foto</label><br>
                    <?php if (!empty($akun->getFotoExtention())): ?>
                        <div>File sekarang: <?php echo htmlspecialchars($akun->getFotoExtention()); ?></div>
                    <?php endif; ?>
                    <input type="file" name="foto" id="foto" accept="image/*"><br>
                <?php } ?>

                <br>
                <input type="submit" class="btn-submit" value="Simpan Perubahan">
            </form>

            <?php
            if (isset($_SESSION['error_msg'])) {
                echo '<div class="error block">';
                echo '<p class="error message">' . $_SESSION['error_msg'] . '</p>';
                echo '</div>';
                unset($_SESSION['error_msg']);
            }
            if (isset($_SESSION['success_msg'])) {
                echo '<div class="success block">';
                echo '<p class="success message">' . $_SESSION['success_msg'] . '</p>';
                echo '</div>';
                unset($_SESSION['success_msg']);
            }
            ?>
        </div>
    </div>
</body>

</html>

<script>
    $(document).ready(function() {
        $("#form-edit").on("submit", function(e) {
            let confirmEdit = confirm("Apakah Anda yakin ingin menyimpan perubahan data akun ini?");
            if (!confirmEdit) {
                e.preventDefault();
            }
        });
    });
</script>

<?php

// FUNCTIONS

function main()
{
    global $akun, $jenis, $label;

    try {
        checkAccountIntegrity();
        checkDataIntegrity();

        $username = $_GET['username'];
        $akun = getAccountData($username);

        if (!$akun) {
            throw new Exception("Akun dengan username '$username' tidak ditemukan");
        }

        $jenis = Akun::getAccountRole($username);
        $label = strtolower($jenis);
    } catch (Exception $e) {
        $_SESSION['error_msg'] = $e->getMessage();
        header("Location: daftar_akun.php");
        exit();
    }
}

function checkAccountIntegrity()
{
    if (!isset($_SESSION['currentAccount'])) {
        header("Location: ../login.php");
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
    if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
        throw new Exception("Metode request tidak sesuai");
    }

    if (!isset($_GET['username'])) {
        throw new Exception("Tidak ada data yang anda kirimkan");
    }
}

function getAccountData($username)
{
    global $jenis;

    $jenis = Akun::getAccountRole($username);
    switch ($jenis) {
        case ENUM_JENIS[1]:
            return Mahasiswa::getData($username);
            break;
        case ENUM_JENIS[2]:
            return Dosen::getData($username);
            break;
        default:
            throw new Exception("Jenis akun tidak valid");
    }
}
?>