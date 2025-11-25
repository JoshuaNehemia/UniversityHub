<?php
require_once(__DIR__ . '/../../MODELS/Dosen.php');
require_once(__DIR__ . '/../../MODELS/Mahasiswa.php');
require_once(__DIR__ . '/../../CONTROLLER/TOOLS/dependencies.php');

use MODELS\Dosen;
use MODELS\Mahasiswa;

session_start();

if (!checkAccess(['MAHASISWA', 'DOSEN'])) {
    die("Tidak dapat mengakses halaman ini");
}

$currentAccount = $_SESSION['currentAccount'];
$imageElementOpen = "<img src='";
$imageElementClose = "' alt='Foto Profil' class='profile-picture'>";
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>University Hub | Profil Pengguna</title>
</head>
<body>
    <main class="home-wrapper">
        <div class="home-card">
            <div class="home-header">
                <h1>Selamat Datang, <?php echo $currentAccount->getNama(); ?></h1>
                <?php echo "<img src = '{$currentAccount->getFotoAddress()}'>"; ?>
            </div>
            <div class="home-info">
                <p><strong>Nama:</strong> <?php echo $currentAccount->getNama(); ?></p>
                <p><strong>Jenis Akun:</strong> <?php echo ($currentAccount instanceof Mahasiswa) ? 'Mahasiswa' : 'Dosen'; ?></p>
                <?php if ($currentAccount instanceof Mahasiswa) : ?>
                    <p><strong>NRP:</strong> <?php echo $currentAccount->getNRP(); ?></p>
                    <p><strong>Gender:</strong> <?php echo $currentAccount->getGender(); ?></p>
                    <p><strong>Tanggal Lahir:</strong> <?php echo $currentAccount->getTanggalLahir(); ?></p>
                    <p><strong>Tahun Angkatan:</strong> <?php echo $currentAccount->getAngkatan(); ?></p>
                <?php elseif ($currentAccount instanceof Dosen) : ?>
                    <p><strong>NPK:</strong> <?php echo $currentAccount->getNPK(); ?></p>
                <?php endif; ?>
            </div>
            <div class="account-security">
                <h3>Keamanan Akun</h3>
                <p>Untuk menjaga keamanan akun Anda, silakan ubah kata sandi secara berkala.</p>
                <a href="ubah_password.php" class="btn btn-secondary">Ubah Password</a>
            </div>
        </div>
    </main>
</body>

</html>
<?php
// FUNCTION ==================================================================================================================
function CheckAccountIntegrity()
{
    if (!isset($_SESSION['currentAccount'])) {
        header("Location: ../login.php");
        exit();
    }
}

?>