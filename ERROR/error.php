<?php
$code = $_GET['kode'];
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
    <h1>Mohon maaf, Sepertinya website ini sedang bermasalah.</h1>
    <h2>
        <?php
            echo $code;
        ?>
    </h2>
    <p>
        <?php
        echo $msg;
        ?>
    </p>
</body>

</html>