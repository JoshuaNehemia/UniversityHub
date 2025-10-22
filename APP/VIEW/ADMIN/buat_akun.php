<?php
require_once(__DIR__ . "/../../MODELS/Akun.php");

use MODELS\Akun;

session_start();

define("JQUERY_ADDRESS", "../../../SCRIPTS/jquery-3.7.1.min.js");
define("CONTROLLER_ADDRESS", "../../CONTROLLER/ADMIN/create_account_controller.php");
define("ENUM_JENIS", array("MAHASISWA", "DOSEN"));

$label = "";
$jenis = "";

CheckAccountIntegrity();
CheckNewAccountType();
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>University Hub Admin - Buat Akun</title>
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
        width: 100%;
        max-width: 700px;
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
        font-size: var(--fs-3xl);
        color: var(--fifth-color);
    }

    .admin-divider {
        border-top: 1px solid var(--border-color);
        margin-bottom: var(--space-6);
    }

    #selector-jenis {
        margin-bottom: var(--space-6);
        padding: var(--space-4);
        background-color: var(--main-bg-color);
        border-radius: var(--radius-md);
        text-align: center;
    }

    #selector-jenis label {
        font-weight: var(--fw-semibold);
        color: var(--text-primary);
        margin-bottom: var(--space-3);
        display: block;
    }

    .radio-group {
        display: flex;
        justify-content: center;
        gap: var(--space-4);
        margin-top: var(--space-3);
    }

    .radio-group label {
        display: flex;
        align-items: center;
        gap: var(--space-2);
        cursor: pointer;
        font-weight: var(--fw-normal);
    }

    input[type="file"] {
        padding: var(--space-3);
        border: 1px solid var(--border-color);
        border-radius: var(--radius-md);
        background-color: #fff;
    }

    .btn-submit {
        width: 100%;
        margin-top: var(--space-6);
    }

    .message-block {
        margin-top: var(--space-6);
        text-align: center;
    }
</style>

<body>
    <div class="top-left">
        <a href="home.php" class="btn btn-secondary-outline small">Kembali ke Home</a>
    </div>

    <div class="admin-wrapper">
        <div class="admin-card-create">
            <h1 class="admin-header center">Buat Akun <?php echo ucwords($label) ?> Baru</h1>
            <div class="admin-divider"></div>

            <form id="selector-jenis" class="form-group">
                <label for="jenis">Pilih Jenis Akun:</label>
                <div class="radio-group">
                    <label><input type="radio" name="jenis" value="MAHASISWA" <?php if ($jenis === ENUM_JENIS[0]) echo "checked"; ?>> Mahasiswa</label>
                    <label><input type="radio" name="jenis" value="DOSEN" <?php if ($jenis === ENUM_JENIS[1]) echo "checked"; ?>> Dosen</label>
                </div>
            </form>

            <form action="<?php echo CONTROLLER_ADDRESS; ?>" method="POST" enctype="multipart/form-data">
                <input type="hidden" value="<?php echo $jenis ?>" name="jenis">

                <div class="form-group">
                    <label for="username" class="form-label">Username</label>
                    <input type="text" name="username" id="username" class="form-control" placeholder="Isi username akun" required>
                </div>

                <div class="form-group">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" name="password" id="password" class="form-control" placeholder="Isi password awal akun" required>
                </div>

                <div class="form-group">
                    <label for="nama" class="form-label">Nama Lengkap</label>
                    <input type="text" name="nama" id="nama" class="form-control" placeholder="Isi nama pemilik akun" required>
                </div>

                <?php if ($jenis === ENUM_JENIS[0]) { // MAHASISWA 
                ?>
                    <div class="form-group">
                        <label for="nrp" class="form-label">NRP</label>
                        <input type="text" name="nrp" id="nrp" class="form-control" placeholder="Masukkan NRP mahasiswa" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Gender</label>
                        <div class="radio-group">
                            <label><input type="radio" name="gender" value="Pria" required> Pria</label>
                            <label><input type="radio" name="gender" value="Wanita" required> Wanita</label>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="tanggal" class="form-label">Tanggal Lahir</label>
                        <input type="date" name="tanggal" id="tanggal" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="angkatan" class="form-label">Tahun Angkatan</label>
                        <input type="number" name="angkatan" id="angkatan" class="form-control" placeholder="Contoh: 2022" required>
                    </div>
                    <div class="form-group">
                        <label for="foto" class="form-label">Foto Profil</label>
                        <input type="file" name="foto" id="foto" class="form-control" accept="image/*">
                    </div>
                <?php } else if ($jenis === ENUM_JENIS[1]) { // DOSEN 
                ?>
                    <div class="form-group">
                        <label for="npk" class="form-label">NPK</label>
                        <input type="text" name="npk" id="npk" class="form-control" placeholder="Masukkan NPK dosen" required>
                    </div>
                    <div class="form-group">
                        <label for="foto" class="form-label">Foto Profil</label>
                        <input type="file" name="foto" id="foto" class="form-control" accept="image/*">
                    </div>
                <?php } ?>

                <input type="submit" class="btn btn-primary btn-submit" value="Buat Akun">
            </form>

            <?php if (isset($_SESSION['error_msg'])) { ?>
                <div class="message-block">
                    <div class="alert alert-danger"><?php echo $_SESSION['error_msg']; ?></div>
                </div>
            <?php unset($_SESSION['error_msg']);
            } ?>

            <?php if (isset($_SESSION['success_msg'])) { ?>
                <div class="message-block">
                    <div class="alert alert-success"><?php echo $_SESSION['success_msg']; ?></div>
                </div>
            <?php unset($_SESSION['success_msg']);
            } ?>
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
function CheckAccountIntegrity()
{
    if (!isset($_SESSION['currentAccount'])) {
        header("Location: ../PAGES/login.php");
        exit();
    }
    $currentAccount = $_SESSION['currentAccount'];
    if (!($currentAccount->getJenis() == 'ADMIN')) {
        header("Location: ../ERROR/error.php?code=403&msg=Anda tidak memiliki akses terhadap halaman ini!");
        exit();
    }
}
function CheckNewAccountType()
{
    global $label, $jenis;
    if (isset($_GET['jenis']) && in_array($_GET['jenis'], ENUM_JENIS)) {
        $jenis = $_GET['jenis'];
    } else {
        $jenis = ENUM_JENIS[0]; 
    }
    $label = strtolower($jenis);
}
?>