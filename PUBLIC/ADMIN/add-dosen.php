<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Tambah Dosen | Admin - University Hub</title>

    <script src="../SCRIPTS/jquery_3_7_1.js"></script>
    <script src="../SCRIPTS/config.js"></script>
    <script src="../SCRIPTS/auth.js"></script>

    <link rel="stylesheet" href="../STYLES/main.css">
</head>

<body>

<header>
    <h1>Tambah Dosen</h1>
</header>

<div class="container-layout">

    <aside>
        <nav>
            <a href="daftar-akun.php">Daftar Akun</a>
            <a href="../profil.php">Profil Saya</a>
        </nav>
    </aside>

    <main>

        <h2>Form Tambah Dosen</h2>

        <div class="card" style="padding:20px; max-width:500px;">

            <label>NPK</label>
            <input type="text" id="dsn-npk" placeholder="misal: 198812345">

            <label>Nama Dosen</label>
            <input type="text" id="dsn-nama" placeholder="Nama lengkap dosen">

            <label>Username</label>
            <input type="text" id="dsn-username" placeholder="misal: dosen_john">

            <label>Password</label>
            <input type="password" id="dsn-password">

            <label>Foto Profil</label>
            <input type="file" id="dsn-foto" accept="image/*">

            <button id="btn-tambah-dosen" style="margin-top:20px;">
                Tambah Dosen
            </button>

        </div>

        <div id="status-message" style="margin-top:20px; color:red;"></div>

    </main>

</div>

</body>

<script>
$(document).ready(function() {

    checkLoggedIn();

    $("#btn-tambah-dosen").on("click", function () {
        tambahDosen();
    });

});


// ===========================================================
// TAMBAH DOSEN
// POST /DOSEN/
// ===========================================================
function tambahDosen() {

    const npk = $("#dsn-npk").val().trim();
    const nama = $("#dsn-nama").val().trim();
    const username = $("#dsn-username").val().trim();
    const password = $("#dsn-password").val().trim();
    const fotoFile = $("#dsn-foto")[0].files[0];

    if (!npk || !nama || !username || !password) {
        $("#status-message").text("Semua field wajib diisi.");
        return;
    }

    let formData = new FormData();
    formData.append("npk", npk);
    formData.append("nama", nama);
    formData.append("username", username);
    formData.append("password", password);

    if (fotoFile) {
        formData.append("foto", fotoFile);
    }

    const url = API_ADDRESS + "DOSEN/";

    console.group("TAMBAH DOSEN - START");
    console.group("REQUEST INFO");
    console.log("URL:", url);
    console.log("METHOD: POST");
    console.log("Payload:", { npk, nama, username });
    console.log("Foto:", fotoFile ? fotoFile.name : "Tidak ada");
    console.groupEnd();

    $.ajax({
        url: url,
        type: "POST",
        data: formData,
        processData: false,
        contentType: false,
        dataType: "json",

        success: function(res) {
            console.group("RESPONSE");
            console.log("SUCCESS:", res);
            console.groupEnd();

            if (res.status === "success") {
                $("#status-message")
                    .css("color", "green")
                    .text("Dosen berhasil ditambahkan!");

                setTimeout(() => {
                    window.location.href = "index.php";
                }, 1500);

            } else {
                $("#status-message").text(res.message);
            }
        },

        error: function(xhr) {
            console.group("ERROR");
            console.error(xhr);
            console.groupEnd();

            $("#status-message").text(
                xhr.responseJSON?.message || "Gagal menambahkan dosen."
            );
        },

        complete: function() {
            console.groupEnd();
        }
    });

}
</script>

</html>
