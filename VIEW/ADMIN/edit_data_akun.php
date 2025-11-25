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
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Akun - <?php echo htmlspecialchars($akun->getUsername()); ?></title>
    <link rel="stylesheet" href="../../ASSETS/STYLES/root.css">
    <link rel="stylesheet" href="../../ASSETS/STYLES/main.css">
    <link rel="stylesheet" href="../../ASSETS/STYLES/form.css">
    <script src="<?php echo JQUERY_ADDRESS; ?>"></script>
</head>
<style>
    body {
        background-color: var(--main-bg-color);
        font-family: var(--font-sans);
        padding: var(--space-8);
    }

    .admin-wrapper {
        max-width: 800px;
        margin: var(--space-8) auto;
    }

    .top-left {
        position: absolute;
        top: var(--space-6);
        left: var(--space-6);
    }

    .admin-card-create {
        background-color: var(--surface-color);
        border: 1px solid var(--border-color);
        border-radius: var(--radius-lg);
        padding: var(--space-8);
        box-shadow: var(--shadow-lg);
    }

    .admin-header.center {
        text-align: center;
        margin-bottom: var(--space-6);
        font-size: var(--fs-2xl);
        color: var(--fifth-color);
        word-break: break-all;
    }

    .admin-divider {
        border-top: 1px solid var(--border-color);
        margin-bottom: var(--space-6);
    }

    #form-edit .form-group {
        margin-bottom: var(--space-5);
    }

    #form-edit label {
        display: block;
        font-weight: var(--fw-semibold);
        color: var(--text-primary);
        margin-bottom: var(--space-2);
    }

    #form-edit .radio-group {
        display: flex;
        gap: var(--space-6);
        align-items: center;
        margin-top: var(--space-2);
        margin-bottom: var(--space-4);
    }

    #form-edit .radio-group label {
        font-weight: var(--fw-normal);
        display: flex;
        align-items: center;
        gap: var(--space-2);
        margin-bottom: 0;
    }

    .current-file-info {
        font-size: var(--fs-sm);
        color: var(--text-secondary);
        background-color: var(--main-bg-color);
        padding: var(--space-2) var(--space-3);
        border-radius: var(--radius-sm);
        display: inline-block;
        margin-bottom: var(--space-2);
    }

    .error.block,
    .success.block {
        margin-top: var(--space-6);
        padding: var(--space-4);
        border-radius: var(--radius-md);
        text-align: center;
    }

    .error.block {
        background-color: var(--status-failed-bg);
        color: var(--status-failed);
    }

    .success.block {
        background-color: var(--status-success-bg);
        color: var(--status-success);
    }
</style>

<body>
    <div class="top-left">
        <a href="daftar_akun.php" class="btn btn-secondary-outline small">Kembali ke Daftar</a>
    </div>

    <div class="admin-wrapper">
        <div class="admin-card-create">
            <h1 class="admin-header center">Edit Data Akun: <?php echo htmlspecialchars($akun->getUsername()); ?></h1>
            <p class="subtitle" style="text-align: center; margin-top: -20px; margin-bottom: 20px;">(<?php echo ucfirst($label); ?>)</p>
            <div class="admin-divider"></div>

            <form id="form-edit" action="<?php echo CONTROLLER_ADDRESS; ?>" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="jenis" value="<?php echo htmlspecialchars($jenis); ?>">
                <input type="hidden" name="username" value="<?php echo htmlspecialchars($_GET['username']); ?>">
                <input type="hidden" name="oldext" value="<?php echo htmlspecialchars($akun->getFotoExtention()); ?>">

                <?php if ($jenis === "MAHASISWA") : ?>
                    <input type="hidden" name="oldnrp" value="<?php echo htmlspecialchars($akun->getNRP()); ?>">

                    <div class="form-group">
                        <label for="nama">Nama Mahasiswa</label>
                        <input type="text" name="nama" id="nama" class="form-control" value="<?php echo htmlspecialchars($akun->getNama()); ?>">
                    </div>
                    <div class="form-group">
                        <label for="nrp">NRP</label>
                        <input type="text" name="nrp" id="nrp" class="form-control" value="<?php echo htmlspecialchars($akun->getNRP()); ?>">
                    </div>
                    <div class="form-group">
                        <label for="gender">Gender</label>
                        <div class="radio-group">
                            <label><input type="radio" name="gender" value="Pria" <?php if ($akun->getGender() === "Pria") echo "checked"; ?>> Pria</label>
                            <label><input type="radio" name="gender" value="Wanita" <?php if ($akun->getGender() === "Wanita") echo "checked"; ?>> Wanita</label>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="tanggal">Tanggal Lahir</label>
                        <input type="date" name="tanggal" id="tanggal" class="form-control" value="<?php echo htmlspecialchars($akun->getTanggalLahir()); ?>">
                    </div>
                    <div class="form-group">
                        <label for="angkatan">Tahun Angkatan</label>
                        <input type="number" name="angkatan" id="angkatan" class="form-control" value="<?php echo htmlspecialchars($akun->getAngkatan()); ?>">
                    </div>

                <?php elseif ($jenis === "DOSEN") : ?>
                    <input type="hidden" name="oldnpk" value="<?php echo htmlspecialchars($akun->getNPK()); ?>">

                    <div class="form-group">
                        <label for="nama">Nama Dosen</label>
                        <input type="text" name="nama" id="nama" class="form-control" value="<?php echo htmlspecialchars($akun->getNama()); ?>">
                    </div>
                    <div class="form-group">
                        <label for="npk">NPK</label>
                        <input type="text" name="npk" id="npk" class="form-control" value="<?php echo htmlspecialchars($akun->getNPK()); ?>">
                    </div>
                <?php endif; ?>

                <div class="form-group">
                    <label for="foto">Ubah Foto Profil (opsional)</label>
                    <?php if ($jenis == "MAHASISWA") {
                        $code = $akun->getNRP();
                    } else {
                        $code = $akun->getNPK();
                    }
                    if (!empty($akun->getFotoExtention())) {
                        echo "<div class='current-file-info'>File saat ini: " . htmlspecialchars($code) . '.' . htmlspecialchars($akun->getFotoExtention()) . "</div>";
                    }
                    ?>
                    <input type="file" name="foto" id="foto" class="form-control" accept="image/*">
                </div>

                <input type="submit" class="btn btn-primary w-full mt-6" value="Simpan Perubahan">
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
    if (!isset($_GET['username']) && !isset($_POST['username'])) {
        throw new Exception("Tidak ada data yang anda kirimkan");
    }
}

function getAccountData($username)
{
    global $jenis;

    $jenis = Akun::getAccountRole($username);
    switch ($jenis) {
        case ENUM_JENIS[1]: // MAHASISWA
            return Mahasiswa::getData($username);
            break;
        case ENUM_JENIS[2]: // DOSEN
            return Dosen::getData($username);
            break;
        default:
            throw new Exception("Jenis akun tidak valid untuk diedit");
    }
}
?>