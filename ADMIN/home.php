<?php
require_once('../MODELS/Akun.php');
use MODELS\Akun;

session_start();
CheckAccountIntegrity();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>University Hub Admin - Home</title>
    <link rel="stylesheet" href="../STYLES/root.css">
    <link rel="stylesheet" href="../STYLES/form.css">
    <script src="../SCRIPTS/jquery-3.7.1.min.js"></script>
</head>
<body>
    <div class="admin-wrapper">
        <div class="admin-card">
            <header class="admin-header">
                <h1>Hi, Admin!</h1>
                <p class="subtitle">Welcome to the University Hub administration page</p>
            </header>

            <div class="admin-divider"></div>

            <section class="admin-actions">
                <nav class="admin-nav">
                    <a href="buat_akun.php" class="admin-btn">Create Account</a>
                    <a href="daftar_akun.php" class="admin-btn">Account List</a>
                </nav>
            </section>
        </div>
    </div>

    <script>
        $(document).ready(function(){
            $(".admin-btn").hover(function(){
                $(this).addClass("hovered");
            }, function(){
                $(this).removeClass("hovered");
            });
        });
    </script>
</body>
</html>

<?php
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
?>