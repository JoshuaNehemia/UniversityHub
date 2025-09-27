<?php
require_once __DIR__ . '/../MODELS/Dosen.php';
require_once __DIR__ . '/../MODELS/Mahasiswa.php';

use MODELS\Dosen;
use MODELS\Mahasiswa;

session_start();
define("JQUERY_ADDRESS", "../SCRIPTS/jquery-3.7.1.min.js");
CheckAccountIntegrity();
$currentAccount = $_SESSION['currentAccount'];
$imageElementOpen = "<img src='";
$imageElementClose = "' alt='Foto Profil' class='profile-picture'>";
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>University Hub - Home</title>
    <link rel="stylesheet" href="../STYLES/root.css">
    <link rel="stylesheet" href="../STYLES/form.css">
    <script src="<?php echo JQUERY_ADDRESS; ?>"></script>
</head>

<body>
    <nav class="top-tabs">
        <ul>
            <li class="tab-link active" data-tab="tab-1">Profil</li>
            <li class="tab-link" data-tab="tab-2">Pengaturan</li>
        </ul>
    </nav>
    <div class="home-wrapper">
        <div class="home-card">
            <div id="tab-1" class="tab-content active">
                <div class="home-header">
                    <h1>Selamat Datang <?php echo $currentAccount->getNama(); ?></h1>
                    <?php DisplayPicture(); ?>
                </div>
                <div class="home-info">
                    <p><strong>Nama:</strong> <?php echo $currentAccount->getNama(); ?></p>
                    <p><strong>Role:</strong> <?php echo ($currentAccount instanceof Dosen) ? 'Dosen' : 'Mahasiswa'; ?></p>
                    <?php if ($currentAccount instanceof Mahasiswa): ?>
                        <p><strong>NRP:</strong> <?php echo $currentAccount->getNRP(); ?></p>
                    <?php elseif ($currentAccount instanceof Dosen): ?>
                        <p><strong>NPK:</strong> <?php echo $currentAccount->getNPK(); ?></p>
                    <?php endif; ?>
                </div>
            </div>
            <div id="tab-2" class="tab-content">
                <div class="home-header">
                    <h2>Pengaturan Akun</h2>
                </div>
                <div class="home-settings">
                    <a href="ubah_password.php" class="btn-primary">Ubah Password</a>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(function(){
            $('.tab-link').click(function(){
                var tab_id = $(this).attr('data-tab');
                $('.tab-link').removeClass('active');
                $('.tab-content').removeClass('active');
                $(this).addClass('active');
                $("#" + tab_id).addClass('active');
            });
        });
    </script>
</body>

</html>
<?php
// FUNCTION ==================================================================================================================
function CheckAccountIntegrity()
{
    if (!isset($_SESSION['currentAccount'])) {
        header("Location: login.php");
        exit();
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