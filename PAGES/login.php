<?php
session_start();

// DEFINE ========================================================================================================================
define("LOGIN_CONTROLLER_ADDRESS", "../CONTROLLER/login_controller.php");
define("JQUERY_ADDRESS", "../SCRIPTS/jquery-3.7.1.min.js");

// CHECK ACCOUNT INTEGRITY
if (isset($_SESSION['currentAccount'])) {
    header("Location: " . LOGIN_CONTROLLER_ADDRESS);
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>University Hub - Log In</title>
    <link rel="stylesheet" href="../STYLES/root.css">
    <link rel="stylesheet" href="../STYLES/form.css">
    <script src="<?php echo JQUERY_ADDRESS; ?>"></script>
</head>
<body>
    <div class="login-container">
        <div class="login-left">
            <div class="branding">
                <img src="../ASSETS/IMAGES/university.png" alt="University Logo" class="brand-image">
                <p class="welcome-text">Welcome to your academic portal.<br>Log in to continue.</p>
            </div>
        </div>

        <div class="login-right">
            <div class="form-box">
                <h2>LOG IN</h2>
                <form action="<?php echo LOGIN_CONTROLLER_ADDRESS; ?>" method="POST">
                    <label for="username">Username</label>
                    <input type="text" name="username" placeholder="Fill your username" required>

                    <label for="password">Password</label>
                    <input type="password" name="password" placeholder="Fill your password" required>

                    <input type="submit" value="Log In" class="btn-submit">
                </form>

                <?php 
                // IF ERROR
                if (isset($_SESSION['error_msg'])){
                    echo '<div class="error block">';
                    echo '<p class="error message">' .$_SESSION['error_msg'] .'</p>';
                    echo '</div>';
                    unset($_SESSION['error_msg']);
                }?>
            </div>
        </div>
    </div>
</body>
</html>