<?php

//DEFINE
define("DISPLAY_PER_PAGE", 10);

if (!isset($_SESSION['currentAccount'])) {

}
else{

}


if (isset($_GET['start'])) {
    $startFromIndex = $_GET['start'];
}
else{
    $startFromIndex = 0;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UniversityHub - Admin</title>
</head>

<body>
    <h1>Daftar Akun</h1>
</body>

</html>