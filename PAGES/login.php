<?php
session_start();

define("LOGIN_CONTROLLER_ADDRESS", "../CONTROLLER/login_controller.php");

// If already logged in, redirect
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
</head>
<body>
    <div class="container">
        <h1>Log In</h1>
        <div class="form login">
            <form action="<?php echo ''. LOGIN_CONTROLLER_ADDRESS; ?>" method="POST">
                <label for="username">Username</label><br>
                <input type="text" name="username" placeholder="Fill your username"><br>

                <label for="password">Password</label><br>
                <input type="password" name="password" placeholder="Fill your password"><br>

                <input type="submit" value="Log In">
            </form>
        </div>

        <?php if (isset($_SESSION['error_msg'])){
            echo '<div class="error block">';
            echo '<p class="error message">' .$_SESSION['error_msg'] .'</p>';
            echo '</div>';
            unset($_SESSION['error_msg']);
        }?>
    </div>
</body>
</html>
