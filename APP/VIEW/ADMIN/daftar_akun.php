<?php
require_once(__DIR__ . "/../../MODELS/Akun.php");
require_once(__DIR__ . "/../../CONTROLLER/ADMIN/account_list_controller.php");

use MODELS\Akun;

session_start();

// DEFINE ========================================================================================================================
define("JQUERY_ADDRESS", "../../../SCRIPTS/jquery-3.7.1.min.js");
define("EDIT_PAGE", "edit_data_akun.php");
define("DELETE_CONTROLLER", "../../CONTROLLER/ADMIN/delete_account_controller.php");
define("DISPLAY_PER_PAGE", 5);
define("OFFSET_PAGE", 2);

// ===============================================================================================================================
// LOGIC
$label = "";
$jenis = "";
CheckAccountIntegrity();
CheckAccount();
$keyword = "";
$currentPage = 1;

if (isset($_GET['currentPage'])) {
    $currentPage = $_GET['currentPage'];
}
if (isset($_GET['keyword'])) {
    $keyword = $_GET['keyword'];
}

$data = GetAccountList($jenis, ($currentPage - 1) * DISPLAY_PER_PAGE, DISPLAY_PER_PAGE, $keyword);
$numRows = GetNumRows($jenis, $keyword);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UniversityHub - Admin</title>
    <link rel="stylesheet" href="../STYLES/root.css">
    <link rel="stylesheet" href="../STYLES/form.css">
    <link rel="stylesheet" href="../STYLES/table.css">
    <script src="<?php echo JQUERY_ADDRESS; ?>"></script>
</head>

<body>
    <div class="top-left">
        <a href="home.php" class="admin-btn small">← Kembali ke Home</a>
    </div>
    <div class="admin-wrapper">
        <div class="admin-card-daftar-akun">
            <h1 class="admin-header center">Daftar Akun <?php echo ucfirst($label) ?></h1>
            <div class="admin-divider"></div>

            <form id="selector-jenis" class="selector-box">
                <label for="jenis">Lihat Akun apa?</label>
                <div class="radio-group">
                    <label><input type="radio" name="jenis" value="MAHASISWA" <?php if ($jenis === ENUM_JENIS[0]) echo "checked"; ?>> Mahasiswa</label>
                    <label><input type="radio" name="jenis" value="DOSEN" <?php if ($jenis === ENUM_JENIS[1]) echo "checked"; ?>> Dosen</label>
                </div>
            </form>

            <div class="table-container">
                <?php
                echo MakeTable($data);
                ?>
            </div>

            <p class="info-data">Ditemukan <strong><?php echo $numRows; ?></strong> data</p>

            <div class="pagination">
                <?php echo MakePaging($numRows, $currentPage); ?>
            </div>
        </div>
    </div>
    <?php
    if (isset($_SESSION['error_msg'])) {
        echo '<div class="error block">';
        echo '<p class="error message">' . $_SESSION['error_msg'] . '</p>';
        echo '</div>';
        unset($_SESSION['error_msg']);
    }
    if (isset($_SESSION['success_msg'])) {
        echo '<div class="success block">';
        echo '<p class="success message">' . $_SESSION['success_msg'] . '</p>';
        echo '</div>';
        unset($_SESSION['success_msg']);
    }
    ?>
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
// FUNCTION ======================================================================================================================
function CheckAccountIntegrity()
{
    if (!isset($_SESSION['currentAccount'])) {
        header("Location: ../PAGES/login.php");
    }

    $currentAccount = $_SESSION['currentAccount'];

    if (!($currentAccount->getJenis() == 'ADMIN')) {
        //header("Location: ../ERROR/error.php?code=403&msg=Anda tidak memiliki akses terhadap halaman ini!");
    }
}

function CheckAccount()
{
    global $label, $jenis;
    if (isset($_GET['jenis'])) {
        $jenis = $_GET['jenis'];
    } else {
        $jenis = ENUM_JENIS[0];
    }
    $label = strtolower($jenis);
}

function GetColumn(&$data)
{
    if (!isset($data[0]) || !is_array($data[0])) {
        return [];
    }

    $column = [];
    foreach ($data[0] as $key => $val) {
        $column[] = $key;
    }
    return $column;
}

function MakeTableHead(&$data)
{
    $head = "<thead>";
    $column = GetColumn($data);
    foreach ($column as $dat) {
        $head .= "<th>" . $dat . "</th>";
    }
    $head .= "<th>Edit</th>";
    $head .= "<th>Delete</th>";
    $head .= "</thead>";
    return $head;
}

function MakeTableBody($data)
{
    $body = "<tbody>";
    foreach ($data as $row) {
        $body .= "<tr>";
        foreach ($row as $dat) {
            $body .= "<td>" . $dat . "</td>";
        }
        $body .= "<td><a href='" . EDIT_PAGE . "?username=" . $row['username'] . "'>edit</a></td>";
        $body .= "<td><a href='" . DELETE_CONTROLLER . "?username=" . $row['username'] . "'>delete</a></td>";
        $body .= "</tr>";
    }
    $body .= "</tbody>";
    return $body;
}

function MakeTable($data)
{
    if (isset($data) && count($data) > 0) {
        $table = "<table>";
        $table .= MakeTableHead($data);
        $table .= MakeTableBody($data);
        $table .= "</table>";
        return $table;
    } else {
        return "<p class='info-data'>Tidak ada data tersedia.</p>";
    }
}
function DisplayInformation()
{
    global $numRows;
    echo "<p>Ditemukan {$numRows} buah data</p>";
}

function MakePaging($numRows, $currentPage)
{
    global $jenis;
    $paging = "";
    if ($numRows > DISPLAY_PER_PAGE) {
        $firstPage = 1;
        $totalPages = ceil($numRows / DISPLAY_PER_PAGE);
        $smallestPage = max($firstPage, ($currentPage - OFFSET_PAGE));
        $largestPage = min($totalPages, ($currentPage + OFFSET_PAGE));

        if (($largestPage - $smallestPage) < ((2 * OFFSET_PAGE))) {
            if ($smallestPage == $firstPage) {
                $largestPage = $firstPage + (2 * OFFSET_PAGE);
            } else if ($largestPage == $totalPages) {
                $smallestPage = $totalPages - (2 * OFFSET_PAGE);
            }
        }
        if ($largestPage > $totalPages) $largestPage = $totalPages;
        if ($smallestPage < $firstPage) $smallestPage = $firstPage;

        $previousPage = max($firstPage, ($currentPage - 1));
        $nextPage = min($totalPages, ($currentPage + 1));

        $paging .= "<div class='page-nav'>";
        $paging .= "<a href='?currentPage=" . $firstPage . "&jenis=" . $jenis . "'>" . "« First" . "</a>";
        $paging .= "<a href='?currentPage=" . $previousPage . "&jenis=" . $jenis . "'>" . "‹ Prev" . "</a>";

        for ($i = $smallestPage; $i <= $largestPage; $i++) {
            $paging .= "<a href='?currentPage={$i}&jenis={$jenis}'>{$i}</a>";
        }

        $paging .= "<a href='?currentPage=" . $nextPage . "&jenis=" . $jenis . "'>" . "Next ›" . "</a>";
        $paging .= "<a href='?currentPage=" . $largestPage . "&jenis=" . $jenis . "'>" . "Last »" . "</a>";
        $paging .= "</div>";
    }
    return $paging;
}
?>