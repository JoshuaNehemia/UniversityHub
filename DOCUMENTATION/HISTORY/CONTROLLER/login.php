<?php
require_once(__DIR__ . '/../../MODELS/Akun.php');
require_once(__DIR__ . '/../../MODELS/Dosen.php');
require_once(__DIR__ . '/../../MODELS/Mahasiswa.php');
require_once(__DIR__ . '/dependencies.php');

use MODELS\Akun;
use MODELS\Dosen;
use MODELS\Mahasiswa;

// Define
define("USERNAME", "username");
define("PASSWORD", "password");

// Run
main();

function main()
{
    try {
        startSession();
        check_data_validity();
        $username = $_POST[USERNAME];
        $password = $_POST[PASSWORD];
        $_SESSION[CURRENT_ACCOUNT] = login($username, $password);
        $response = array(
            "status" => "success",
            // "payload" => print_r($_SESSION[CURRENT_ACCOUNT]),
            "route" => routing($_SESSION[CURRENT_ACCOUNT]->getJenis())
        );
    } catch (Exception $e) {
        $response = array(
            "status" => "failed",
            "message" => $e->getMessage()
        );
    } finally {
        echo json_encode($response);
    }
}

function check_data_validity()
{
    if (!($_SERVER['REQUEST_METHOD'] === 'POST')) {
        throw new Exception("Data sent through an illegal method");
    }

    if (!(isset($_POST[USERNAME]) && isset($_POST[PASSWORD]))) {
        throw new Exception("Data is not completely sent");
    }
}

function login($username, $password)
{
    $jenis = Akun::get_account_role($username);
    $currentAccount = 0;
    switch ($jenis) {
        case ACCOUNT_ROLES[0]:
            //Mahasiswa login
            $currentAccount = mahasiswa_login($username, $password);
            break;
        case ACCOUNT_ROLES[1]:
            //Dosen login
            $currentAccount = dosen_login($username, $password);
            break;
        case ACCOUNT_ROLES[2]:
            //Admin login
            $currentAccount = admin_login($username, $password);
            break;
        default:
            throw new Exception("Something wrong with your account please contact the admin");
    }
    return $currentAccount;
}

function mahasiswa_login($username, $password)
{
    $currentAccount = Mahasiswa::login($username, $password);
    $currentAccount->setFotoAddress(PICTURE_DATABASE . ACCOUNT_ROLES[0] . "/" . $currentAccount->getNRP() . "." . $currentAccount->getFotoExtention());
    return $currentAccount;
}

function dosen_login($username, $password)
{
    $currentAccount = Dosen::login($username, $password);
    $currentAccount->setFotoAddress(PICTURE_DATABASE . ACCOUNT_ROLES[1] . "/" . $currentAccount->getNPK() . "." . $currentAccount->getFotoExtention());
    return $currentAccount;
}

function admin_login($username, $password)
{
    $currentAccount = Akun::login($username, $password);
    return $currentAccount;
}

function routing($jenis)
{
    if (!(isset($_POST['from']))) {
        $route = "index.php";
    }

    $route = $_POST['from'];
    if($jenis === ACCOUNT_ROLES[2]){
        $route = $jenis ."/" . $route;
    }
    else{
        $route = "USER/" . $route;
    }
    return $route;
}
