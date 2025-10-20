<?php
require_once(__DIR__ ."/../../MODELS/Akun.php");
require_once(__DIR__ ."/../../CONTROLLER/account_list_controller.php");

use MODELS\Akun;

session_start();

// DEFINE ========================================================================================================================
define("JQUERY_ADDRESS", "../../SCRIPTS/jquery-3.7.1.min.js");
define("DISPLAY_PER_PAGE", 5);
define("OFFSET_PAGE", 2);

// ===============================================================================================================================
// TABLE ELEMENT FOR DISPLAY
// (kalau ganti css disini)
// ===============================================================================================================================

// Table
$tableOpen   = "<table class='styled-table'>";
$tableClose  = "</table>";

// Table Head
$tableHeadOpen   = "<thead>";
$tableHeadClose  = "</thead>";

// Table Body
$tableBodyOpen   = "<tbody>";
$tableBodyClose  = "</tbody>";

// Table Foot
$tableFootOpen   = "<tfoot>";
$tableFootClose  = "</tfoot>";

// Table Row
$tableRowOpen   = "<tr>";
$tableRowClose  = "</tr>";

// Table Header Cell
$tableHeaderCellOpen   = "<th>";
$tableHeaderCellClose  = "</th>";

// Table Data Cell
$tableDataCellOpen   = "<td>";
$tableDataCellClose  = "</td>";

// Table Caption
$tableCaptionOpen   = "<caption>";
$tableCaptionClose  = "</caption>";

// Column Edit
$editPageAddress = "edit_data_akun.php";
$editColumnHead = "<th>Edit</th>";
$editColumnCellOpen = "<td><a class='btn-action edit' href='{$editPageAddress}?username=";
$editColumnCellClose = "'>Edit</a></td>";

// Column Delete
$deleteControllerAddress = "../../CONTROLLER/delete_account_controller.php";
$deleteColumnHead = "<th>Delete</th>";
$deleteColumnCellOpen = "<td><a href='{$deleteControllerAddress}?username=";
$deleteColumnCellClose = "'>Delete</a></td>";
// $deleteColumnCellMid = "&jenis=";
// $deleteColumnCellClose = "' onclick=\"return confirm('Yakin ingin menghapus akun ini?');\">Delete</a></td>";

// ===============================================================================================================================
// ELEMENT FOR PAGING
// (kalau ganti css disini)
// ===============================================================================================================================
$pagingHyperLinkOpen = "<a class='page-link' href='?currentPage=";
$pagingHyperLinkJenis = "&jenis=";
$pagingHyperLinkMid = "'>";
$pagingHyperLinkClose = "</a>";

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

$data = GetAccountList($jenis, ($currentPage - 1)*DISPLAY_PER_PAGE, DISPLAY_PER_PAGE, $keyword);
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
                echo MakeTable();
                ?>
            </div>

            <p class="info-data">Ditemukan <strong><?php echo $numRows; ?></strong> data</p>

            <div class="pagination">
                <?php echo MakePaging($numRows, $currentPage); ?>
            </div>
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
    global $tableHeadOpen, $tableHeaderCellOpen, $tableHeaderCellClose, $tableRowOpen, $tableRowClose, $tableHeadClose, $editColumnHead, $deleteColumnHead;
    $head = $tableHeadOpen;
    $head .= $tableRowOpen;
    $column = GetColumn($data);
    foreach ($column as $dat) {
        $head .= $tableHeaderCellOpen . $dat . $tableHeaderCellClose;
    }
    $head .= $editColumnHead;
    $head .= $deleteColumnHead;
    $head .= $tableRowClose;
    $head .= $tableHeadClose;
    return $head;
}

function MakeTableBody($data)
{
    global $tableBodyOpen, $tableRowOpen, $tableRowClose, $tableDataCellOpen, $tableDataCellClose, $tableBodyClose, $editColumnCellOpen, $editColumnCellClose, $deleteColumnCellOpen, $deleteColumnCellClose;
    $body = $tableBodyOpen;
    foreach ($data as $row) {
        $body .= $tableRowOpen;
        foreach ($row as $dat) {
            $body .= $tableDataCellOpen . $dat . $tableDataCellClose;
        }
        $body .= $editColumnCellOpen . $row['username'] . $editColumnCellClose;
        $body .= $deleteColumnCellOpen . $row['username'] . $deleteColumnCellClose;
        $body .= $tableRowClose;
    }
    $body .= $tableBodyClose;
    return $body;
}

function MakeTable()
{
    global $data, $tableOpen, $tableClose;
    if (isset($data) && count($data) > 0) {
        $table = $tableOpen;
        $table .= MakeTableHead($data);
        $table .= MakeTableBody($data);
        $table .= $tableClose;
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

function MakePaging($numRows, $currentPage) {
    global $pagingHyperLinkClose, $pagingHyperLinkMid, $pagingHyperLinkOpen, $pagingHyperLinkJenis, $jenis;
    $paging = "";
    if ($numRows > DISPLAY_PER_PAGE) {
        $firstPage = 1;
        $totalPages = ceil($numRows / DISPLAY_PER_PAGE);
        $smallestPage = max($firstPage, ($currentPage - OFFSET_PAGE));
        $largestPage = min($totalPages, ($currentPage + OFFSET_PAGE));

        if (($largestPage - $smallestPage) < ((2*OFFSET_PAGE))) {
            if ($smallestPage == $firstPage) {
                $largestPage = $firstPage + (2*OFFSET_PAGE);
            } else if ($largestPage == $totalPages) {
                $smallestPage = $totalPages - (2*OFFSET_PAGE);
            }
        }
        if ($largestPage > $totalPages) $largestPage = $totalPages;
        if ($smallestPage < $firstPage) $smallestPage = $firstPage;

        $previousPage = max($firstPage, ($currentPage - 1));
        $nextPage = min($totalPages, ($currentPage + 1));

        $paging .= "<div class='page-nav'>";
        $paging .= $pagingHyperLinkOpen . $firstPage . $pagingHyperLinkJenis . $jenis . $pagingHyperLinkMid . "« First" . $pagingHyperLinkClose;
        $paging .= $pagingHyperLinkOpen . $previousPage . $pagingHyperLinkJenis . $jenis . $pagingHyperLinkMid . "‹ Prev" . $pagingHyperLinkClose;

        for ($i = $smallestPage; $i <= $largestPage; $i++) {
            $activeClass = ($i == $currentPage) ? " active" : "";
            $paging .= "<a class='page-link{$activeClass}' href='?currentPage={$i}{$pagingHyperLinkJenis}{$jenis}'>{$i}</a>";
        }

        $paging .= $pagingHyperLinkOpen . $nextPage . $pagingHyperLinkJenis . $jenis . $pagingHyperLinkMid . "Next ›" . $pagingHyperLinkClose;
        $paging .= $pagingHyperLinkOpen . $totalPages . $pagingHyperLinkJenis . $jenis . $pagingHyperLinkMid . "Last »" . $pagingHyperLinkClose;
        $paging .= "</div>";
    }
    return $paging;
}
?>