<?php
require_once(__DIR__ . "/../../MODELS/Akun.php");
require_once(__DIR__ . "/../../CONTROLLER/ADMIN/account_list_controller.php");

use MODELS\Akun;

session_start();

// DEFINE ========================================================================================================================
define("JQUERY_ADDRESS", "../../../SCRIPTS/jquery-3.7.1.min.js");
define("EDIT_PAGE", "edit_data_akun.php");
define("UPDATE_PASSWORD_PAGE", "ganti_password.php");
define("DELETE_CONTROLLER", "../../CONTROLLER/ADMIN/delete_account_controller.php");
define("IMAGE_DATABASE", "../../../DATABASE/");
define("DISPLAY_PER_PAGE", 5);
define("OFFSET_PAGE", 2);
define("ENUM_JENIS", array("MAHASISWA", "DOSEN"));

// ===============================================================================================================================
// LOGIC
$label = "";
$jenis = "";
CheckAccountIntegrity();
CheckAccount();
$keyword = "";
$currentPage = 1;

if (isset($_GET['currentPage'])) {
    $currentPage = (int)$_GET['currentPage'];
}
if (isset($_GET['keyword'])) {
    $keyword = $_GET['keyword'];
}

$data = GetAccountList($jenis, ($currentPage - 1) * DISPLAY_PER_PAGE, DISPLAY_PER_PAGE, $keyword);
$numRows = GetNumRows($jenis, $keyword);
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UniversityHub - Daftar Akun</title>
    <link rel="stylesheet" href="../../ASSETS/STYLES/root.css">
    <link rel="stylesheet" href="../../ASSETS/STYLES/main.css">
    <link rel="stylesheet" href="../../ASSETS/STYLES/form.css">
    <link rel="stylesheet" href="../../ASSETS/STYLES/table.css">
    <script src="<?php echo JQUERY_ADDRESS; ?>"></script>
</head>
<style>
    body {
        background-color: var(--main-bg-color);
        font-family: var(--font-sans);
        padding: var(--space-8);
    }

    .admin-wrapper {
        width: 100%;
        max-width: 1600px;
        margin: var(--space-8) auto;
    }

    .top-left {
        position: absolute;
        top: var(--space-6);
        left: var(--space-6);
    }

    .admin-card-daftar-akun {
        background-color: var(--surface-color);
        border: 1px solid var(--border-color);
        border-radius: var(--radius-lg);
        padding: var(--space-8);
        box-shadow: var(--shadow-lg);
    }

    .admin-header.center {
        text-align: center;
        margin-bottom: var(--space-6);
        font-size: var(--fs-3xl);
        color: var(--fifth-color);
    }

    .admin-divider {
        border-top: 1px solid var(--border-color);
        margin-bottom: var(--space-6);
    }

    .controls-container {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: var(--space-6);
        flex-wrap: wrap;
        gap: var(--space-6);
    }

    .selector-box {
        padding: var(--space-4);
        background-color: var(--main-bg-color);
        border-radius: var(--radius-md);
        display: flex;
        align-items: center;
        gap: var(--space-4);
    }

    .selector-box label {
        font-weight: var(--fw-semibold);
        color: var(--text-primary);
    }

    .radio-group {
        display: flex;
        gap: var(--space-4);
    }

    .search-form {
        display: flex;
        gap: var(--space-2);
    }

    .table-container {
        overflow-x: auto;
    }

    .table-container table {
        width: 100%;
        border-collapse: collapse;
    }

    .table-container th,
    .table-container td {
        padding: var(--space-4);
        white-space: nowrap;
    }

    .profile-image {
        width: 50px;
        height: 50px;
        object-fit: cover;
        border-radius: 50%;
    }

    .action-btn {
        padding: var(--space-2) var(--space-3);
        border-radius: var(--radius-sm);
        text-decoration: none;
        color: white;
        font-size: var(--fs-sm);
        font-weight: var(--fw-semibold);
        white-space: nowrap;
    }

    .btn-edit {
        background-color: var(--status-info);
    }

    .btn-password {
        background-color: var(--status-warning);
        color: var(--fifth-color);
    }

    .btn-delete {
        background-color: var(--status-failed);
    }

    .info-data {
        text-align: right;
        margin: var(--space-4) 0;
        color: var(--text-secondary);
    }

    .pagination {
        display: flex;
        justify-content: center;
        margin-top: var(--space-6);
    }

    .page-nav {
        display: flex;
        gap: var(--space-2);
    }

    .page-nav a {
        text-decoration: none;
        padding: var(--space-2) var(--space-4);
        border: 1px solid var(--border-color);
        border-radius: var(--radius-sm);
        color: var(--text-color);
        transition: background-color var(--transition-fast), color var(--transition-fast);
    }

    .page-nav a:hover {
        background-color: var(--fourth-color);
        color: white;
    }

    .page-nav a.active {
        background-color: var(--first-color);
        color: white;
        border-color: var(--first-color);
    }
</style>

<body>
    <div class="top-left">
        <a href="home.php" class="btn btn-secondary-outline small">Kembali ke Home</a>
    </div>
    <div class="admin-wrapper">
        <div class="admin-card-daftar-akun">
            <h1 class="admin-header center">Daftar Akun <?php echo ucfirst($label) ?></h1>
            <div class="admin-divider"></div>

            <div class="controls-container">
                <form id="selector-jenis" class="selector-box">
                    <label for="jenis">Tampilkan Akun:</label>
                    <div class="radio-group">
                        <label><input type="radio" name="jenis" value="MAHASISWA" <?php if ($jenis === ENUM_JENIS[0]) echo "checked"; ?>> Mahasiswa</label>
                        <label><input type="radio" name="jenis" value="DOSEN" <?php if ($jenis === ENUM_JENIS[1]) echo "checked"; ?>> Dosen</label>
                    </div>
                </form>
                <form class="search-form" method="GET">
                    <input type="hidden" name="jenis" value="<?php echo $jenis; ?>">
                    <input type="text" name="keyword" class="form-control" placeholder="Cari berdasarkan nama..." value="<?php echo htmlspecialchars($keyword); ?>">
                    <button type="submit" class="btn btn-primary">Cari</button>
                </form>
            </div>

            <div class="table-container">
                <?php echo MakeTable($data); ?>
            </div>

            <p class="info-data">Menampilkan <strong><?php echo count($data); ?></strong> dari <strong><?php echo $numRows; ?></strong> total data</p>

            <div class="pagination">
                <?php echo MakePaging($numRows, $currentPage); ?>
            </div>

            <?php if (isset($_SESSION['error_msg'])) {
                echo '<div class="alert alert-danger mt-4">' . $_SESSION['error_msg'] . '</div>';
                unset($_SESSION['error_msg']);
            } ?>
            <?php if (isset($_SESSION['success_msg'])) {
                echo '<div class="alert alert-success mt-4">' . $_SESSION['success_msg'] . '</div>';
                unset($_SESSION['success_msg']);
            } ?>
        </div>
    </div>
</body>

</html>
<script>
    $(document).ready(function() {
        $('input[name="jenis"]').on('change', function() {
            let selected = $(this).val();
            window.location.href = "?jenis=" + selected;
        });
    });
</script>

<?php
function CheckAccountIntegrity()
{
    if (!isset($_SESSION['currentAccount'])) {
        header("Location: ../PAGES/login.php");
        exit();
    }
    $currentAccount = $_SESSION['currentAccount'];
    if (!($currentAccount->getJenis() == 'ADMIN')) {
        header("Location: ../error.php?code=403&msg=Anda tidak memiliki akses terhadap halaman ini!");
        exit();
    }
}

function CheckAccount()
{
    global $label, $jenis;
    if (isset($_GET['jenis']) && in_array($_GET['jenis'], ENUM_JENIS)) {
        $jenis = $_GET['jenis'];
    } else {
        $jenis = ENUM_JENIS[0];
        $label = strtolower($jenis);
    }
}

function MakeTableHead($data)
{
    if (empty($data)) return "<thead></thead>";
    $head = "<thead><tr>";
    $column = array_keys($data[0]);
    $head .= "<th>Foto</th>";
    foreach ($column as $colName) {
        $head .= "<th>" . htmlspecialchars(ucwords(str_replace('_', ' ', $colName))) . "</th>";
    }
    $head .= "<th>Edit</th>";
    $head .= "<th>Password</th>";
    $head .= "<th>Delete</th>";
    $head .= "</tr></thead>";
    return $head;
}

function MakeTableBody($data)
{
    global $jenis;
    $body = "<tbody>";
    foreach ($data as $row) {
        $code = "";
        $foto_ext_col = '';
        if ($jenis === "MAHASISWA") {
            $code = $row['nrp'];
            $foto_ext_col = "foto_extention";
        } else if ($jenis === "DOSEN") {
            $code = $row['npk'];
            $foto_ext_col = "foto_extension";
        }

        $body .= "<tr>";

        $imagePath = IMAGE_DATABASE . $jenis . "/" . $code . "." . $row[$foto_ext_col];
        $defaultImage = "../../ASSETS/IMAGES/default_profile_picture.svg";
        $imageSrc = file_exists($imagePath) ? $imagePath : $defaultImage;
        $body .= "<td><img src='" . $imageSrc . "' alt='Foto Profil' class='profile-image'></td>";

        foreach ($row as $cell) {
            $body .= "<td>" . htmlspecialchars($cell) . "</td>";
        }

        $body .= "<td><a href='" . EDIT_PAGE . "?username=" . $row['username'] . "' class='action-btn btn-edit'>Edit</a></td>";
        $body .= "<td><a href='" . UPDATE_PASSWORD_PAGE . "?username=" . $row['username'] . "' class='action-btn btn-password'>Password</a></td>";
        $body .= "<td><a href='" . DELETE_CONTROLLER . "?username=" . $row['username'] . "&jenis=" . $jenis . "&code=" . $code . "' class='action-btn btn-delete' onclick='return confirm(\"Apakah Anda yakin ingin menghapus akun ini?\")'>Delete</a></td>";
        $body .= "</tr>";
    }
    $body .= "</tbody>";
    return $body;
}


function MakeTable($data)
{
    if (!empty($data)) {
        $table = "<table>";
        $table .= MakeTableHead($data);
        $table .= MakeTableBody($data);
        $table .= "</table>";
        return $table;
    } else {
        return "<p class='info-data' style='text-align:center;'>Tidak ada data yang cocok dengan pencarian Anda.</p>";
    }
}

function MakePaging($numRows, $currentPage)
{
    global $jenis, $keyword;
    $paging = "";
    if ($numRows > DISPLAY_PER_PAGE) {
        $totalPages = ceil($numRows / DISPLAY_PER_PAGE);
        $paging .= "<nav class='page-nav'>";

        if ($currentPage > 1) {
            $paging .= "<a href='?currentPage=1&jenis={$jenis}&keyword={$keyword}'>« First</a>";
            $paging .= "<a href='?currentPage=" . ($currentPage - 1) . "&jenis={$jenis}&keyword={$keyword}'>‹ Prev</a>";
        }

        $start = max(1, $currentPage - OFFSET_PAGE);
        $end = min($totalPages, $currentPage + OFFSET_PAGE);

        for ($i = $start; $i <= $end; $i++) {
            $activeClass = ($i == $currentPage) ? "active" : "";
            $paging .= "<a href='?currentPage={$i}&jenis={$jenis}&keyword={$keyword}' class='{$activeClass}'>{$i}</a>";
        }

        if ($currentPage < $totalPages) {
            $paging .= "<a href='?currentPage=" . ($currentPage + 1) . "&jenis={$jenis}&keyword={$keyword}'>Next ›</a>";
            $paging .= "<a href='?currentPage={$totalPages}&jenis={$jenis}&keyword={$keyword}'>Last »</a>";
        }

        $paging .= "</nav>";
    }
    return $paging;
}
?>