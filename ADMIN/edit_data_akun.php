<?php
session_start();
define("ENUM_JENIS", array("MAHASISWA", "DOSEN", "ADMIN"));
$label = "";
$jenis = "";
CheckAccountIntegrity();
CheckAccountType();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UniversityHub - Admin</title>
</head>
<body>
    
</body>
</html>
<?php
// FUNCTION ======================================================================================================================
function CheckAccountIntegrity()
{
    if (!isset($_SESSION['currentAccount'])) {
        header("Location: ../PAGES/login.php");
    }

    $currentAccount = $_SESSION['currentAccount'];

    if (!($currentAccount->getJenis() == 'ADMIN')) {
        header("Location: ../ERROR/error.php?code=403&msg=Anda tidak memiliki akses terhadap halaman ini!");
    }
}

function CheckAccountType()
{
    global $label, $jenis;
    if (isset($_GET['jenis'])) {
        $jenis = $_GET['jenis'];
    } else {
        $jenis = ENUM_JENIS[0];
    }
    $label = strtolower($jenis);
}
?>