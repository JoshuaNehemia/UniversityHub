<?php
require_once(__DIR__ . '/../../MODELS/Akun.php');
use MODELS\Akun;

session_start();

main();

function main()
{
    checkAccountIntegrity();
}

function checkAccountIntegrity()
{
    if (!isset($_SESSION['currentAccount'])) {
        header("Location: ../PAGES/login.php");
        exit();
    }

    $currentAccount = $_SESSION['currentAccount'];

    if ($currentAccount->getJenis() !== 'ADMIN') {
        header("Location: ../ERROR/error.php?code=403&msg=Anda tidak memiliki akses terhadap halaman ini!");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UniversityHub | Ganti Password</title>
    <link rel="stylesheet" href="../../ASSETS/STYLES/root.css">
    <link rel="stylesheet" href="../../ASSETS/STYLES/main.css">
    <link rel="stylesheet" href="../../ASSETS/STYLES/form.css">
</head>
<style>
    body {
        background-color: var(--main-bg-color);
        font-family: var(--font-sans);
        padding: var(--space-8);
        display: flex;
        align-items: center;
        justify-content: center;
        min-height: 100vh;
    }

    .admin-wrapper {
        max-width: 500px;
        width: 100%;
    }

    .top-left {
        position: absolute;
        top: var(--space-6);
        left: var(--space-6);
    }

    .admin-card {
        background-color: var(--surface-color);
        border: 1px solid var(--border-color);
        border-radius: var(--radius-lg);
        padding: var(--space-8);
        box-shadow: var(--shadow-lg);
    }

    .admin-header.center {
        text-align: center;
        margin-bottom: var(--space-4);
        font-size: var(--fs-2xl);
        color: var(--fifth-color);
        word-break: break-all;
    }
    .subtitle {
        text-align: center;
        margin-top: calc(-1 * var(--space-2));
        margin-bottom: var(--space-6);
        color: var(--text-secondary);
    }

    .admin-divider {
        border-top: 1px solid var(--border-color);
        margin-bottom: var(--space-6);
    }

    .form-group {
        margin-bottom: var(--space-5);
    }
    
    label {
        display: block;
        font-weight: var(--fw-semibold);
        color: var(--text-primary);
        margin-bottom: var(--space-2);
    }

    .message-block {
        margin-top: var(--space-6);
        padding: var(--space-4);
        border-radius: var(--radius-md);
        text-align: center;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: var(--space-3);
    }
    .message-block.error { background-color: var(--status-failed-bg); color: var(--status-failed); }
    .message-block.success { background-color: var(--status-success-bg); color: var(--status-success); }
    .message-block img {
        width: 24px;
        height: 24px;
    }

</style>
<body>
    <div class="top-left">
        <a href="daftar_akun.php" class="btn btn-secondary-outline small">← Kembali ke Daftar</a>
    </div>
    <div class="admin-wrapper">
        <div class="admin-card">
            <h1 class="admin-header center">Ganti Password Akun</h1>
            <p class="subtitle">Untuk: <strong><?php echo htmlspecialchars($_GET['username'] ?? 'N/A'); ?></strong></p>
            <div class="admin-divider"></div>

            <form method="POST" action="../../CONTROLLER/ADMIN/update_password_controller.php">
                <input type="hidden" name="username" value="<?= htmlspecialchars($_GET['username'] ?? '') ?>">
                
                <div class="form-group">
                    <label for="pwd">Password Baru</label>
                    <input type="password" id="pwd" name="pwd" class="form-control" placeholder="Masukkan password baru" required>
                </div>

                <div class="form-group">
                    <label for="confirm">Konfirmasi Password Baru</label>
                    <input type="password" id="confirm" name="confirm" class="form-control" placeholder="Konfirmasi password baru" required>
                </div>
                
                <button type="submit" class="btn btn-primary w-full mt-4">Ubah Password</button>
            </form>

            <?php if (isset($_SESSION['error_msg'])): ?>
                <div class="message-block error">
                    <img src="../../ASSETS/IMAGES/fail.svg" alt="Error">
                    <p><?= $_SESSION['error_msg']; unset($_SESSION['error_msg']); ?></p>
                </div>
            <?php endif; ?>

            <?php if (isset($_SESSION['success_msg'])): ?>
                <div class="message-block success">
                    <img src="../../ASSETS/IMAGES/success.svg" alt="Success">
                    <p><?= $_SESSION['success_msg']; unset($_SESSION['success_msg']); ?></p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
