<?php
session_start();

define("LOGIN_CONTROLLER_ADDRESS", "../CONTROLLER/TOOLS/login_controller.php");
define("ASSET_ADDRESS", "../ASSETS/");

$from = $_GET['from'] ?? "index.php";
$ubayabgimage = ASSET_ADDRESS . "IMAGES/ubaya.jpg";
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>University Hub Login | Student & Faculty Portal</title>
    <meta name="description" content="Log in to University Hub, the student and faculty portal for accessing courses, grades, and campus resources securely.">
    <link rel="stylesheet" href="<?php echo ASSET_ADDRESS . "STYLES/root.css"; ?>">
    <link rel="stylesheet" href="<?php echo ASSET_ADDRESS . "STYLES/main.css"; ?>">
    <link rel="stylesheet" href="<?php echo ASSET_ADDRESS . "STYLES/form.css"; ?>">
</head>

<style>
    html,
    body {
        height: 100%;
        overflow: hidden;
    }

    body {
        display: flex;
        font-family: var(--font-sans);
    }

    .login-container {
        display: flex;
        width: 100%;
        height: 100%;
    }

    .login-image {
        width: 55%;
        height: 100%;
        background-image: linear-gradient(rgba(0, 0, 0, 0.3), rgba(0, 0, 0, 0.3)), url("<?php echo $ubayabgimage; ?>");
        background-size: cover;
        background-position: center;
    }

    .login-form-wrapper {
        width: 45%;
        height: 100%;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        padding: var(--space-12);
        background-color: var(--surface-color);
        overflow-y: auto;
    }

    .login-form-container {
        width: 100%;
        max-width: 400px;
    }

    .login-form-container h1 {
        font-family: var(--font-heading);
        font-size: var(--fs-4xl);
        color: var(--first-color);
        margin-bottom: var(--space-2);
    }

    .login-form-container h2 {
        font-family: var(--font-sans);
        font-size: var(--fs-lg);
        color: var(--text-secondary);
        font-weight: var(--fw-normal);
        margin-bottom: var(--space-8);
    }

    .form-group {
        margin-bottom: var(--space-5);
    }

    .remember-me {
        display: flex;
        align-items: center;
        gap: var(--space-2);
        margin-top: var(--space-4);
        margin-bottom: var(--space-6);
    }

    .remember-me label {
        color: var(--text-secondary);
        font-size: var(--fs-sm);
        margin-bottom: 0;
    }

    #remember {
        width: 1rem;
        height: 1rem;
        accent-color: var(--first-color);
    }

    .login-btn {
        width: 100%;
        padding: var(--space-4);
        font-size: var(--fs-lg);
    }

    .error-message {
        margin-top: var(--space-6);
        text-align: center;
    }

    @media (max-width: 992px) {
        .login-image {
            display: none;
        }

        .login-form-wrapper {
            width: 100%;
            padding: var(--space-8);
        }
    }
</style>

<body>
    <main class="login-container">
        <section class="login-image" aria-hidden="true"></section>

        <section class="login-form-wrapper">
            <div class="login-form-container">
                <h1>University Hub</h1>
                <h2>Student & Faculty Portal Login</h2>

                <form action="<?php echo LOGIN_CONTROLLER_ADDRESS; ?>" method="POST">
                    <div class="form-group">
                        <label for="username" class="form-label">Username</label>
                        <input type="text" id="username" name="username" class="form-control" placeholder="Enter your username" required aria-label="Username">
                    </div>

                    <div class="form-group">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" id="password" name="password" class="form-control" placeholder="Enter your password" required aria-label="Password">
                    </div>

                    <div class="remember-me">
                        <input type="checkbox" id="remember" name="remember">
                        <label for="remember">Remember me</label>
                    </div>

                    <input type="hidden" name="from" value="<?php echo htmlspecialchars($from); ?>">
                    <button type="submit" class="btn btn-primary login-btn">Log In</button>
                </form>

                <?php if (isset($_SESSION['error_msg'])) : ?>
                    <div class="alert alert-danger error-message">
                        <p><?php echo $_SESSION['error_msg']; ?></p>
                    </div>
                    <?php unset($_SESSION['error_msg']); ?>
                <?php endif; ?>
            </div>
        </section>
    </main>
</body>

</html>
