<?php
require_once __DIR__ . '/../MODELS/Dosen.php';
require_once __DIR__ . '/../MODELS/Mahasiswa.php';

use MODELS\Dosen;
use MODELS\Mahasiswa;

session_start();
CheckAccountIntegrity();
$currentAccount = $_SESSION['currentAccount'];
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>University Hub - Home</title>
</head>

<body>
    <h1>Hi, Selamat Datang <?php echo $_SESSION['currentAccount']->getNama(); ?></h1>
    <img src="<?php echo $currentAccount->getFotoAddress()?>">
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