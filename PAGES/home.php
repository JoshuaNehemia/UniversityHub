<?php
require_once __DIR__ . '/../MODELS/Dosen.php';
require_once __DIR__ . '/../MODELS/Mahasiswa.php';

use MODELS\Dosen;
use MODELS\Mahasiswa;

session_start();
CheckAccountIntegrity();
$currentAccount = $_SESSION['currentAccount'];
$imageElementOpen = "<img src='";
$imageElementClose = "'>"
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>University Hub - Home</title>
    <link rel="stylesheet" href="../STYLES/root.css">
</head>

<body>
    <h1>Hi, Selamat Datang <?php echo $_SESSION['currentAccount']->getNama(); ?></h1>
    <?php DisplayPicture() ?>
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

function DisplayPicture()
{
    global $currentAccount,$imageElementOpen,$imageElementClose;
    $file = $currentAccount->getFotoAddress();;
    if (file_exists($file)) {
        echo $imageElementOpen.$file.$imageElementClose;
    } 
    else{
        $defaultAddress = "../ASSETS/IMAGES/default_profile_picture.svg";
        echo $imageElementOpen.$defaultAddress.$imageElementClose;
    }
}
?>