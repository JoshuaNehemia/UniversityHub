<?php
session_start();

define("LOGIN_CONTROLLER_ADDRESS", "../CONTROLLER/TOOLS/login_controller.php");
define("ASSET_ADDRESS", "../ASSETS/");

if (isset($_GET['from'])) {
    $from = $_GET['from'];
} else {
    $from = "index.php";
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>University Hub Login | Student & Faculty Portal</title>
    <meta name="description" content="Log in to University Hub, the student and faculty portal for accessing courses, grades, and campus resources securely.">
    <script src="../SCRIPTS/jquery-3.7.1.min.js"></script>
</head>

<body>
    <main class="login-container">
        <section class="login-form-wrapper">
            <div class="login-form-container">
                <h1>University Hub</h1>
                <h2>Student & Faculty Portal Login</h2>

                <form>
                    <div class="form-group">
                        <label for="username" class="form-label">Username</label>
                        <input type="text" id="username" name="username" class="form-control" placeholder="Enter your username" required aria-label="Username">
                    </div>

                    <div class="form-group">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" id="password" name="password" class="form-control" placeholder="Enter your password" required aria-label="Password">
                    </div>
                    <input type="hidden" name="from" value="<?php echo htmlspecialchars($from); ?>">
                    <button type="submit" class="btn btn-primary login-btn" id="submitButton">Log In</button>
                </form>
            </div>
            <div id="statusMessage" style="margin-top: 10px; font-weight: bold; font-family: sans-serif;"></div>
        </section>
    </main>
</body>
<script>
    $(document).ready(function() {

        $('form').on('submit', function(e) {
            e.preventDefault();

            var $form = $(this);
            var $status = $('#statusMessage');
            var $btn = $('#submitButton');
            var formData = $form.serialize();

            $btn.prop('disabled', true);
            $btn.text('Please wait...');

            $.post('<?php echo LOGIN_CONTROLLER_ADDRESS; ?>', formData, function(data) {

                    if (data.status === 'success') {
                        console.log("Server response:" + data);
                        $status.text("Success! Redirecting...");
                        $status.css('color', 'green');
                        window.location.href = data.route;

                    } else {
                        var msg = data.message;
                        $status.text(data.message);
                        $status.css("color", "red");
                    }

                }, 'json')
                .fail(function() {
                    $status.text("Error: Connection failed.");
                    $status.css('color', 'red');
                })
                .always(function() {
                    $btn.prop('disabled', false);
                    $btn.text('Log In');
                });

        });
    });
</script>

</html>