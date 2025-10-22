<?php
require_once(__DIR__ . '/../../MODELS/Dosen.php');
require_once(__DIR__ . '/../../MODELS/Mahasiswa.php');

use MODELS\Dosen;
use MODELS\Mahasiswa;

session_start();
define("JQUERY_ADDRESS", "../SCRIPTS/jquery-3.7.1.min.js");

// This function call should be at the top to protect the page
CheckAccountIntegrity();

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

    .home-wrapper {
        max-width: 800px;
        margin: 0 auto;
    }

    .home-card {
        background-color: var(--surface-color);
        border: 1px solid var(--border-color);
        border-radius: var(--radius-lg);
        padding: var(--space-8);
        box-shadow: var(--shadow-lg);
    }

    .home-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: var(--space-8);
        flex-wrap: wrap;
        gap: var(--space-4);
    }

    .home-header h1 {
        margin-bottom: 0;
        color: var(--fifth-color);
        font-size: var(--fs-3xl);
    }

    .profile-picture {
        width: 100px;
        height: 100px;
        border-radius: 50%;
        object-fit: cover;
        border: 4px solid var(--surface-color);
        box-shadow: var(--shadow-md);
    }

    .home-info p {
        font-size: var(--fs-lg);
        color: var(--text-primary);
        margin-bottom: var(--space-4);
        border-bottom: 1px solid var(--border-color);
        padding-bottom: var(--space-4);
        display: flex;
    }

    .home-info p:last-child {
        border-bottom: none;
        margin-bottom: 0;
    }

    .home-info p strong {
        font-weight: var(--fw-semibold);
        color: var(--text-secondary);
        display: inline-block;
        width: 240px;
        flex-shrink: 0;
    }

    .account-security {
        margin-top: var(--space-8);
        padding-top: var(--space-6);
        border-top: 1px solid var(--border-color);
    }

    .account-security h3 {
        font-size: var(--fs-xl);
        color: var(--fifth-color);
        margin-bottom: var(--space-2);
    }

    .account-security p {
        color: var(--text-secondary);
        margin-bottom: var(--space-4);
    }
</style>

<body>
    <main class="home-wrapper">
        <div class="home-card">
            <div class="home-header">
                <h1>Selamat Datang, <?php echo $currentAccount->getNama(); ?></h1>
                <?php DisplayPicture(); ?>
            </div>
            <!--
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
                -->
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

function DisplayPicture()
{
    global $currentAccount, $imageElementOpen, $imageElementClose;
    $file = $currentAccount->getFotoAddress();;
    if (file_exists($file)) {
        echo $imageElementOpen . $file . $imageElementClose;
    } else {
        $defaultAddress = "../ASSETS/IMAGES/default_profile_picture.svg";
        echo $imageElementOpen . $defaultAddress . $imageElementClose;
    }
}
?>

