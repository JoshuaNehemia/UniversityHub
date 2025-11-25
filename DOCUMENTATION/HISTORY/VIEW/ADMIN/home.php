<?php
require_once(__DIR__ .'/../../MODELS/Akun.php');
use MODELS\Akun;

session_start();
CheckAccountIntegrity();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>University Hub Admin | Home</title>
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

    .admin-wrapper {
        width: 100%;
        max-width: 600px;
    }

    .admin-card {
        background-color: var(--surface-color);
        border: 1px solid var(--border-color);
        border-radius: var(--radius-lg);
        padding: var(--space-8);
        box-shadow: var(--shadow-lg);
        text-align: center;
    }

    .admin-header h1 {
        font-size: var(--fs-3xl);
        color: var(--fifth-color);
        margin-bottom: var(--space-2);
    }

    .admin-header .subtitle {
        color: var(--text-secondary);
        font-size: var(--fs-lg);
        margin-bottom: var(--space-6);
    }

    .admin-divider {
        border-top: 1px solid var(--border-color);
        margin: var(--space-6) 0;
    }
    
    .admin-actions h2 {
        font-size: var(--fs-xl);
        color: var(--text-primary);
        margin-bottom: var(--space-4);
    }

    .admin-nav {
        display: flex;
        flex-direction: column;
        gap: var(--space-4);
    }

    .admin-btn {
        display: block;
        padding: var(--space-4) var(--space-6);
        background-color: var(--fourth-color);
        color: white;
        text-decoration: none;
        border-radius: var(--radius-md);
        font-weight: var(--fw-semibold);
        transition: background-color var(--transition-base), transform var(--transition-fast);
        font-size: var(--fs-lg);
    }

    .admin-btn:hover {
        background-color: var(--third-color);
        transform: translateY(-2px);
    }

</style>
<body>
    <div class="admin-wrapper">
        <div class="admin-card">
            <header class="admin-header">
                <h1>Hi, Admin!</h1>
                <p class="subtitle">Selamat datang di halaman administrasi.</p>
            </header>

            <div class="admin-divider"></div>

            <section class="admin-actions">
                 <h2>Manajemen Akun</h2>
                <nav class="admin-nav">
                    <a href="buat_akun.php" class="admin-btn">Buat Akun Baru</a>
                    <a href="daftar_akun.php" class="admin-btn">Lihat Daftar Akun</a>
                </nav>
            </section>
        </div>
    </div>

</body>
</html>

<?php
function CheckAccountIntegrity()
{
    if (!isset($_SESSION['currentAccount'])) {
        header("Location: ../login.php");
        exit(); 
    }

    $currentAccount = $_SESSION['currentAccount'];

    if (!($currentAccount->getJenis() == 'ADMIN')) {
        header("Location: ../error.php?code=403&msg=Anda tidak memiliki akses terhadap halaman ini!");
        exit(); 
    }
}
?>
