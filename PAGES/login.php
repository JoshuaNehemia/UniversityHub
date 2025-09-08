<?php
session_start();

// Check user udah pernah  di session
if (isset($_SESSION['currentAccount'])) {
    echo "<h1>UDAH PERNAH LOGIN</h1>";
    header("Location: ../CONTROLLER/entry_controller.php");
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>University - Log In</title>
</head>

<body>
    <div class="container">
        <h1>Log In</h1>
        <div class="form login">
            <form action="../CONTROLLER/entry_controller.php" method="POST">
                <label for="username">Username</label><br>
                <input type="text" name="username" placeholder="Fill your username"><br>

                <label for="password">Password</label><br>
                <input type="password" name="password" placeholder="Fill your password"><br>

                <input type="submit">
            </form>
        </div>
        <div class="error block">
            <p class="error message">
                <?php 
                if (isset($_GET['error_msg'])) {
                    $err_no = $_GET['error_msg'];
                    echo "<p>";
                    switch ($err_no) {
                        case 1:
                            echo "Username atau password salah";
                            break;
                        case 2:
                            echo "Akun anda telah dibuat, namun terjadi kesalahan pada data anda.<br>Mohon hubungi admin";
                            break;
                        case 3:
                            echo "Proses log in terjadi kesalahan<br>Mohon ulangi";
                            break;
                    }
                }
                ?>
            </p>
        </div>
    </div>
</body>

</html>