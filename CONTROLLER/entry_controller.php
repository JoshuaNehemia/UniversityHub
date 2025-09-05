<?php
session_start();
$debug = [];
$headto = "../PAGES/";

require_once __DIR__ . '/../DATABASE/Connection.php';
require_once __DIR__ . '/../MODELS/Akun.php';
require_once __DIR__ . '/../MODELS/Dosen.php';
require_once __DIR__ . '/../MODELS/Mahasiswa.php';

use MODELS\Akun;
use MODELS\Dosen;
use MODELS\Mahasiswa;
use DATABASE\Connection;

function IsLoggedIn(){
    return isset($_SESSION['currentAccount']);
}

function Transfering($currentAccount){
    if($currentAccount->getJenis()=='ADMIN'){

    }
}

function AdminLogIn($arrAkun)
{
    // Buat kelas trus masukin session
    $akun = new Akun($arrAkun['username'], 'Admin', 'ADMIN');
    $_SESSION['currentAccount'] = $akun;

    // Masuk sebagai Admin
    echo "<p>Succesfully logged in asADMIN<br>
    Go to Admin Page!<br>
    <a href='../ADMIN/home.php'>Click Here!</a>
    </p>";

    $headto = "../ADMIN/home.php";
}

function MahasiswaLogIn($arrAkun) {

}

function DosenLogIn($arrAkun) {

}


function login()
{
    global $debug, $headto;
    $debug[] = "CURRENTLY LOG IN";
    $username = $_POST['username'];
    $password = $_POST['password'];

    $debug['username'] = $username;
    $debug['password'] = $password;

    // Cari di Database
    $debug[] = "FETCHING DATA FROM DATABASE";

    // Dapat dari Database
    $arrAkun = Akun::LogIn($username, $password);
    $debug['Akun dari DB'] = $arrAkun;

    if ($arrAkun['isadmin'] == 1) {
        AdminlogIn($arrAkun);
    }
    else if (isset($arrAkun['nrp_mahasiswa'])) {
    }
    else if (isset($arrAkun['npk_dosen'])) {
    }
    $headto .= "";
    $debug['destination'] = $headto;
}


// Check user udah pernah  di session
if (IsLoggedIn()) {
    $currentAccount = $_SESSION['currentAccount'];
    echo '<p> You are LOGGED IN</p>';
    Transfering($currentAccount);
} else {
    login();
}

//Comment if not debugging
if (1 == 0) {
    header("Location: " . $headto);
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>University Hub - Controller</title>
</head>
<style>
    body {
        font-family: 'Consolas', 'Menlo', 'Courier New', monospace;
        text-align: center;
    }

    table {
        width: 80%;
        margin: 25px auto;
        border-collapse: collapse;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        text-align: left;
    }

    thead th {
        background-color: #2c3e50;
        color: #ecf0f1;
        font-weight: bold;
        padding: 15px;
        font-size: 0.85em;
        letter-spacing: 0.5px;
    }

    tbody td {
        padding: 12px 15px;
        border-bottom: 1px solid #ddd;
        color: #333;
    }

    tbody td:first-child {
        font-weight: bold;
        color: #34495e;
        background-color: #f8f9fa;
    }

    tbody tr:nth-of-type(even) {
        background-color: #f3f3f3;
    }

    tbody tr:nth-of-type(even) td:first-child {
        background-color: #e9ecef;
    }

    tbody tr:hover {
        background-color: #d1e7fd;
    }

    tbody tr:hover td:first-child {
        background-color: #b9d7f9;
    }
</style>

<body>
    <h1>
        Controller Page - Entry
    </h1>
    <h2>
        (You Shouldn't See This if You Are Not Debugging)
    </h2>
    <table>
        <thead>
            <th>
                KEYS
            </th>
            <th>
                VALUES
            </th>
        </thead>
        <tbody>
            <?php
            foreach ($debug as $key => $value) {
                if (is_array($value)) {
                    $debug_string = print_r($value, true);
                    echo "<tr><td>" . $key . "</td><td>" . $debug_string . "</td>";
                } else {
                    echo "<tr><td>" . $key . "</td><td>" . $value . "</td>";
                }
            }
            ?>
        </tbody>

    </table>
</body>

</html>