<?php
require_once(__DIR__ . '/../../MODELS/Akun.php');
require_once(__DIR__ . '/../../MODELS/Dosen.php');
require_once(__DIR__ . '/../../MODELS/Mahasiswa.php');

use MODELS\Akun;
use MODELS\Dosen;
use MODELS\Mahasiswa;

session_start();

$feedbackMessage = '';
$messageType = '';
$feedbackIcon = '';

main();

function main()
{
    checkAccountIntegrity();
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        handlePasswordChange();
    }
}

function handlePasswordChange()
{
    global $feedbackMessage, $messageType, $feedbackIcon;

    if (empty($_POST['pwd']) || empty($_POST['confirm'])) {
        $feedbackMessage = "Mohon isi semua kolom password.";
        $messageType = 'danger';
        $feedbackIcon = '../../ASSETS/IMAGES/fail.svg';
        return;
    }

    if ($_POST['pwd'] !== $_POST['confirm']) {
        $feedbackMessage = "Password dan konfirmasi tidak sama.";
        $messageType = 'danger';
        $feedbackIcon = '../../ASSETS/IMAGES/fail.svg';
        return;
    }

    try {
        $currentAccount = $_SESSION['currentAccount'];
        $akun = new Akun($currentAccount->getUsername(), 'dummy', 'dummy');
        $akun->updatePassword($_POST['pwd']);
        $feedbackMessage = "Password berhasil diperbarui!";
        $messageType = 'success';
        $feedbackIcon = '../../ASSETS/IMAGES/success.svg';
    } catch (Exception $e) {
        $feedbackMessage = "Gagal memperbarui password: " . htmlspecialchars($e->getMessage());
        $messageType = 'danger';
        $feedbackIcon = '../../ASSETS/IMAGES/fail.svg';
    }
}

function checkAccountIntegrity()
{
    if (!isset($_SESSION['currentAccount'])) {
        header("Location: ../PAGES/login.php");
        exit();
    }

    $currentAccount = $_SESSION['currentAccount'];
    if (!($currentAccount instanceof Mahasiswa) && !($currentAccount instanceof Dosen)) {
        header("Location: ../ERROR/error.php?code=403&msg=Akses ditolak.");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UniversityHub - Ganti Password</title>
    <link rel="stylesheet" href="../../ASSETS/STYLES/root.css">
    <link rel="stylesheet" href="../../ASSETS/STYLES/main.css">
    <link rel="stylesheet" href="../../ASSETS/STYLES/form.css">
</head>

<style>
    body {
        background-color: var(--main-bg-color);
        font-family: var(--font-sans);
        display: flex;
        justify-content: center;
        align-items: center;
        min-height: 100vh;
        padding: var(--space-8);
    }

    .password-wrapper {
        width: 100%;
        max-width: 500px;
    }

    .password-card {
        background-color: var(--surface-color);
        border: 1px solid var(--border-color);
        border-radius: var(--radius-lg);
        padding: var(--space-8);
        box-shadow: var(--shadow-lg);
    }

    .card-header {
        text-align: center;
        margin-bottom: var(--space-8);
    }

    .card-header h2 {
        font-size: var(--fs-3xl);
        color: var(--fifth-color);
        margin-bottom: var(--space-2);
    }

    .card-header p {
        color: var(--text-secondary);
        font-size: var(--fs-base);
    }

    .form-actions {
        display: flex;
        flex-direction: column;
        gap: var(--space-4);
        margin-top: var(--space-6);
    }

    .btn {
        width: 100%;
    }

    .alert {
        margin-top: var(--space-6);
        display: flex;
        align-items: center;
        justify-content: center;
        gap: var(--space-3);
    }

    .alert-icon {
        width: 24px;
        height: 24px;
    }
</style>

<body>
    <main class="password-wrapper">
        <div class="password-card">
            <div class="card-header">
                <h2>Ganti Password</h2>
                <p>Masukkan password baru Anda di bawah ini.</p>
            </div>

            <form method="POST" action="">
                <div class="form-group">
                    <label for="pwd" class="form-label">Password Baru</label>
                    <input type="password" id="pwd" name="pwd" class="form-control" placeholder="Masukan password baru" required>
                </div>
                <div class="form-group">
                    <label for="confirm" class="form-label">Konfirmasi Password</label>
                    <input type="password" id="confirm" name="confirm" class="form-control" placeholder="Konfirmasi password" required>
                </div>

                <?php if (!empty($feedbackMessage)) : ?>
                    <div class="alert alert-<?php echo $messageType; ?>">
                        <img src="<?php echo $feedbackIcon; ?>" alt="" class="alert-icon">
                        <span><?php echo $feedbackMessage; ?></span>
                    </div>
                <?php endif; ?>

                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">Ganti Password</button>
                    <a href="index.php" class="btn btn-secondary-outline">Kembali ke Home</a>
                </div>
            </form>
        </div>
    </main>
</body>

</html>

