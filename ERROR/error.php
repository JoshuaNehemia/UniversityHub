<?php
$code = $_GET['code'];
$msg = $_GET['msg'];
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>University Hub - Error</title>
</head>

<body>
    <h1 class="error code">
        <?php
            echo $code;
        ?>
    </h1>
    <p class = "error message">
        <?php
        echo $msg;
        ?>
    </p>
</body>

</html>