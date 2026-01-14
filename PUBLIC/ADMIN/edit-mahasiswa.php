<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Edit Mahasiswa | Admin - University Hub</title>

    <script src="../SCRIPTS/jquery_3_7_1.js"></script>
    <script src="../SCRIPTS/config.js"></script>
    <script src="../SCRIPTS/auth.js"></script>

    <link rel="stylesheet" href="../STYLES/main.css">
</head>

<body>

<header>
    <h1>Edit Mahasiswa</h1>
</header>

<div class="container-layout">

    <aside>
        <nav>
            <a href="daftar-akun.php">Daftar Akun</a>
            <a href="../profil.php">Profil Saya</a>
        </nav>
    </aside>

    <main>

        <h2>Form Edit Data Mahasiswa</h2>

        <div class="card" style="padding:20px; max-width:500px;">

            <label>Username</label>
            <input type="text" id="mhs-username" readonly>

            <label>Nama Mahasiswa</label>
            <input type="text" id="mhs-nama">

            <label>NRP</label>
            <input type="text" id="mhs-nrp">

            <label>Gender</label>
            <select id="mhs-gender">
                <option value="Pria">Pria</option>
                <option value="Wanita">Wanita</option>
            </select>

            <label>Angkatan</label>
            <input type="number" id="mhs-angkatan" min="2000" max="2100">

            <label>Tanggal Lahir</label>
            <input type="date" id="mhs-tanggal-lahir">

            <button id="btn-update-mhs" style="margin-top:20px;">
                Simpan Perubahan
            </button>

        </div>

        <div id="status-message" style="margin-top:20px; color:red;"></div>

    </main>

</div>

</body>

<script>
$(document).ready(function () {

    checkLoggedIn();

    const urlParams = new URLSearchParams(window.location.search);
    const username = urlParams.get("username");

    if (!username) {
        $("#status-message").text("Username tidak ditemukan.");
        return;
    }

    loadMahasiswa(username);

    $("#btn-update-mhs").on("click", function () {
        updateMahasiswa();
    });
});


function loadMahasiswa(username) {

    const url = API_ADDRESS + "MAHASISWA/";

    console.group("LOAD MAHASISWA - START");
    console.group("REQUEST INFO");
    console.log("URL:", url);
    console.log("Method:", "GET");
    console.log("Params:", { username });
    console.groupEnd();

    $.ajax({
        url: url,
        type: "GET",
        data: { username: username },
        dataType: "json",

        success: function (res) {

            console.group("RESPONSE");
            console.log(res);
            console.groupEnd();

            if (res.status !== "success") {
                $("#status-message").text(res.message);
                return;
            }

            const m = res.data;

            $("#mhs-username").val(m.username);
            $("#mhs-nama").val(m.nama);
            $("#mhs-nrp").val(m.nrp);
            $("#mhs-gender").val(m.gender);
            $("#mhs-angkatan").val(m.angkatan);

            if (m.tanggal_lahir) {
                $("#mhs-tanggal-lahir").val(m.tanggal_lahir);
            }
        },

        error: function (xhr) {
            console.group("ERROR");
            console.error(xhr);
            console.groupEnd();

            $("#status-message").text("Gagal memuat data mahasiswa.");
        },

        complete: function () {
            console.groupEnd();
        }
    });
}


// ===========================================================
// UPDATE MAHASISWA
// PUT /MAHASISWA/
// ===========================================================
function updateMahasiswa() {

    const payload = {
        username: $("#mhs-username").val(),
        nama: $("#mhs-nama").val(),
        nrp: $("#mhs-nrp").val(),
        gender: $("#mhs-gender").val(),
        angkatan: $("#mhs-angkatan").val(),
        tanggal_lahir: $("#mhs-tanggal-lahir").val()
    };

    const url = API_ADDRESS + "MAHASISWA/";

    console.group("UPDATE MAHASISWA - START");

    console.group("REQUEST INFO");
    console.log("URL:", url);
    console.log("Method:", "PUT");
    console.log("Payload:", payload);
    console.groupEnd();

    $.ajax({
        url: url,
        type: "PUT",
        contentType: "application/json",
        dataType: "json",
        data: JSON.stringify(payload),

        success: function (res) {
            console.group("RESPONSE");
            console.log(res);
            console.groupEnd();

            if (res.status === "success") {
                $("#status-message").css("color", "green")
                                    .text("Berhasil memperbarui data mahasiswa!");

                setTimeout(() => {
                    window.location.href = "daftar-akun.php";
                }, 1500);
            } else {
                $("#status-message").text(res.message);
            }
        },

        error: function (xhr) {
            console.group("ERROR");
            console.error(xhr);
            console.groupEnd();

            $("#status-message").text(
                xhr.responseJSON?.message || "Gagal memperbarui mahasiswa."
            );
        },

        complete: function () {
            console.groupEnd();
        }
    });
}
</script>

</html>
