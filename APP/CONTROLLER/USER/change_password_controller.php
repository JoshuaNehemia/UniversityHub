<?php
require_once(__DIR__ . '/../MODELS/Akun.php');
require_once(__DIR__ . '/../MODELS/Dosen.php');
require_once(__DIR__ . '/../MODELS/Mahasiswa.php');

use MODELS\Akun;
use MODELS\Dosen;
use MODELS\Mahasiswa;

session_start();

// DEFINE ========================================================================================================================
define("LOGIN_PAGE_ADDRESS", "../VIEW/USER/login.php");
define("UBAH_PAGE_ADDRESS", "../PAGES/ubah_password.php");

// MAIN LOGIC ====================================================================================================================
main();

// FUNCTION ======================================================================================================================
function main() 
{
    try {
        CheckAccountIntegrity();
        
        if (
            !isset($_POST['current_password']) || 
            !isset($_POST['new_password']) || 
            !isset($_POST['confirm_password'])
        ) {
            throw new Exception("Data tidak lengkap, mohon isi semua field.");
        }

        $username = $_SESSION['currentAccount']->getUsername();
        $current_password = $_POST['current_password'];
        $new_password = $_POST['new_password'];
        $confirm_password = $_POST['confirm_password'];

        // Validasi
        if (!Akun::VerifyPassword($username, $current_password))
        {
            throw new Exception("Password lama salah.");
        }
        if (!($new_password == $confirm_password)) 
        {
            throw new Exception("Konfirmasi password baru tidak cocok.");
        }
        if ($new_password == $current_password) 
        {
            throw new Exception("Password baru harus berbeda dari password lama.");
        }
        // if (strlen($new_password) < 6) {
        //     throw new Exception("Password baru minimal 6 karakter.");
        // }

        // Update password
        Akun::UpdatePasswordInDatabase($username, $new_password);

        $_SESSION['success_msg'] = "Password berhasil diperbarui.";
        header("Location: " . UBAH_PAGE_ADDRESS);
        exit();
    } catch (Exception $e) {
        $_SESSION['error_msg'] = $e->getMessage();
        header("Location: " . UBAH_PAGE_ADDRESS);
        exit();
    } 
}

function CheckAccountIntegrity()
{
    if (!isset($_SESSION['currentAccount'])) {
        header("Location: " . LOGIN_PAGE_ADDRESS);
    }
}
