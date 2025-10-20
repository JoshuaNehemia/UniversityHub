<?php
require_once("../MODELS/Akun.php");
require_once("../CONTROLLER/account_list_controller.php");

use MODELS\Akun;

session_start();

define("JQUERY_ADDRESS", "../SCRIPTS/jquery-3.7.1.min.js");
define("CONTROLLER_ADDRESS", "../CONTROLLER/update_account_controller.php");

// MAIN LOGIC

CheckAccountIntegrity();
$accountToEdit = CheckDataIntegrity();
if (!isset($_GET['username'])) {
    header("Location: daftar_akun.php");
    exit();
}

$username = $_GET['username'];
$akun = GetAccountByUsername($username);

if (!$akun) {
    throw new Exception("Akun dengan username '$username' tidak ditemukan");
    exit();
}

$jenis = $akun['jenis'];
$label = strtolower($jenis);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Akun - <?php echo $akun['username']; ?></title>
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
            <h1 class="admin-header center">Edit Data Akun <?php echo ucfirst($label); ?></h1>
            <div class="admin-divider"></div>
            <form id="form-edit" action="<?php echo CONTROLLER_ADDRESS; ?>" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="old_username" value="<?php echo $akun['username']; ?>">

                <label for="username">Username</label><br>
                <input type="text" name="username" id="username" value="<?php echo $akun['username']; ?>"><br>

                <label for="password">Password (kosong = tidak diubah)</label><br>
                <input type="password" name="password" id="password" placeholder="Isi jika ingin mengganti password"><br>

                <input type="hidden" name="jenis" value="<?php echo $jenis; ?>">

                <?php if ($jenis === "MAHASISWA") { ?>
                    <label for="nama">Nama <?php echo $label; ?></label><br>
                    <input type="text" name="nama" id="nama" value="<?php echo $akun['nama_mhs']; ?>"><br>

                    <label for="nrp">NRP</label><br>
                    <input type="text" name="nrp" id="nrp" value="<?php echo $akun['nrp']; ?>"><br>

                    <label for="gender">Gender</label><br>
                    <div class="radio-group">
                        <input type="radio" name="gender" value="Pria" <?php if ($akun['gender'] == "Pria") echo "checked"; ?>> Pria
                        <input type="radio" name="gender" value="Wanita" <?php if ($akun['gender'] == "Wanita") echo "checked"; ?>> Wanita<br>
                    </div>

                    <label for="tanggal">Tanggal Lahir</label><br>
                    <input type="date" name="tanggal" id="tanggal" value="<?php echo $akun['tanggal_lahir']; ?>"><br>

                    <label for="angkatan">Tahun Angkatan</label><br>
                    <input type="number" name="angkatan" id="angkatan" value="<?php echo $akun['angkatan']; ?>"><br>

                    <label for="foto">Foto (kosong = tidak diubah)</label><br>
                    <?php if (!empty($akun['foto_mhs'])): ?>
                        <div>File sekarang: <?php echo htmlspecialchars($akun['foto_mhs']); ?></div>
                    <?php endif; ?>
                    <input type="file" name="foto" id="foto" accept="image/*"><br>
                <?php } elseif ($jenis === "DOSEN") { ?>
                    <label for="nama">Nama <?php echo $label; ?></label><br>
                    <input type="text" name="nama" id="nama" value="<?php echo $akun['nama_dosen']; ?>"><br>

                    <label for="npk">NPK</label><br>
                    <input type="text" name="npk" id="npk" value="<?php echo $akun['npk']; ?>"><br>

                    <label for="foto">Foto</label><br>
                    <?php if (!empty($akun['foto_dosen'])): ?>
                        <div>File sekarang: <?php echo htmlspecialchars($akun['foto_dosen']); ?></div>
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
// FUNCTION ======================================================================================================================
function CheckAccountIntegrity()
{
    if (!isset($_SESSION['currentAccount'])) {
        header("Location: ../PAGES/login.php");
    }

    $currentAccount = $_SESSION['currentAccount'];
    if ($currentAccount->getJenis() !== 'ADMIN') {
        header("Location: ../ERROR/error.php?code=403&msg=Anda tidak memiliki akses terhadap halaman ini!");
    }
}

function CheckDataIntegrity()
{
    if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
        throw new Exception("Metode request tidak sesuai");
        exit();
    }
    if (!isset($_GET['username'])) {
        throw new Exception("Tidak ada data yang anda kirimkan");
        exit();
    }
    $username = $_GET['username'];
    $akun = GetAccountByUsername($username);
    if (!$akun) {
        throw new Exception("Akun dengan username '$username' tidak ditemukan");
        exit();
    }
    return $akun;
}
?>