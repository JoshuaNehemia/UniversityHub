<?php
session_start();

// DEFINE ========================================================================================================================
define("CONTROLLER_ADDRESS", "../CONTROLLER/change_password_controller.php");
define("JQUERY_ADDRESS", "../SCRIPTS/jquery-3.7.1.min.js");

CheckAccountIntegrity();

?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ubah Password</title>
    <link rel="stylesheet" href="../STYLES/root.css">
    <link rel="stylesheet" href="../STYLES/form.css">
    <script src="<?php echo JQUERY_ADDRESS; ?>"></script>
</head>
<body>
    <div class="top-left">
        <a href="home.php" class="user-btn small">← Kembali ke Home</a>
    </div>
    <div class="user-wrapper">
        <div class="user-card">
            <h1 class="user-header center">Ubah Password</h1>
            <div class="user-divider"></div>
            <form action="<?php echo CONTROLLER_ADDRESS; ?>" method="POST" onsubmit="return confirmSubmit()">
                <label for="current_password">Password Lama</label><br>
                <input type="password" name="current_password" id="current_password" required><br>

                <label for="new_password">Password Baru</label><br>
                <input type="password" name="new_password" id="new_password" required><br>

                <label for="confirm_password">Konfirmasi Password Baru</label><br>
                <input type="password" name="confirm_password" id="confirm_password" required><br>

                <input type="submit" class="btn-submit" value="Simpan">

                <script>
                    function confirmSubmit() {
                        return confirm("Apakah kamu yakin ingin mengubah password?");
                    }
                </script>
            </form>
        </div>
    </div>

    <?php
    // IF ERROR
    if (isset($_SESSION['error_msg'])) {
        echo '<div class="error block">';
        echo '<p class="error message">' . $_SESSION['error_msg'] . '</p>';
        echo '</div>';
        unset($_SESSION['error_msg']);
    } 
    // IF SUCCESS
    if (isset($_SESSION['success_msg'])) {
        echo '<div class="success block">';
        echo '<p class="success message">' . $_SESSION['success_msg'] . '</p>';
        echo '</div>';
        unset($_SESSION['success_msg']);
    } 
    if (isset($_SESSION['success_msg'])) {
        echo '<div class="success block">';
        echo '<p class="success message">' . $_SESSION['success_msg'] . '</p>';
        echo '</div>';
        echo "<script>alert('" . $_SESSION['success_msg'] . "');</script>";
        unset($_SESSION['success_msg']);
    }
    ?>
</body>
</html>

<?php
// FUNCTION ==================================================================================================================
function CheckAccountIntegrity()
{
    if (!isset($_SESSION['currentAccount'])) {
        header("Location: login.php");
    }
}
?>
