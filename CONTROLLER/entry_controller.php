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

// Check user udah pernah  di session
if (isset($_SESSION['currentAccount'])) {
    if ($_SESSION['currentAccount']['isadmin']) {
        echo "<p>You are <br>LOGGED IN AS ADMIN<br>Go to Admin Page!<br><a href='../ADMIN/home.php'>Click Here!</a></p>";
        //header("Location: ../ADMIN/home.php");
    }
    if ($_SESSION['currentAccount']['nrp_mahasiswa']) {
        echo "<p>You are <br>LOGGED IN AS MAHASISWA<br>Go to MAHASISWA Page!<br><a href='../PAGES/home.php'>Click Here!</a></p>";
        //header("Location: ../PAGES/home.php");
    }
    if ($_SESSION['currentAccount']['npk_dosen']) {
        echo "<p>You are <br>LOGGED IN AS DOSEN<br>Go to DOSEN Page!<br><a href='../PAGES/home.php'>Click Here!</a></p>";
        //header("Location: ../PAGES/home.php");
    }
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
    $debug[] = "FETCHING CONNECTION";

    // Dapat dari Database
    $debug[] = "MAKING TO CLASS";
    $arrAkun = Akun::LogIn($username, $password);
    $debug['Akun dari DB'] = $arrAkun;

    if ($arrAkun['isadmin'] == 1) {
        //  masukin session
        $_SESSION['currentAccount'] = $arrAkun;
        // Jadi Admin
        echo "<p>You are ADMIN<br>Go to Admin Page!<br><a href='../ADMIN/home.php'>Click Here!</a></p>";
        // header location (Kalau lagi nge-DEBUG di Comment)
        // header('Location: ../ADMIN/home.php');
    }

    if(isset($arrAkun['nrp_mahasiswa'])){

    }

    if(isset($arrAkun['npk_dosen'])){

    }
    $headto .= "";
    $debug['destination'] = $headto;
}

function signup()
{
    global $debug, $headto;
    $debug[] = "CURRENTLY SIGN UP";
    if ($_POST['jenis'] == "DOSEN") {
        //Buat Akun Dosen
        $debug[] = "CREATING DOSEN";
    } else if ($_POST['jenis'] == "MAHASISWA") {
        //Buat Akun Mahasiswa
        $debug[] = "CREATING MAHASISWA";
    }
}


if ($_POST['type'] == "login") {
    login();
} else if ($_POST['type'] == "signup") {
    signup();
}

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