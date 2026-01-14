<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Tambah Mahasiswa | Admin - University Hub</title>

    <script src="../SCRIPTS/jquery_3_7_1.js"></script>
    <script src="../SCRIPTS/config.js"></script>
    <script src="../SCRIPTS/auth.js"></script>

    <link rel="stylesheet" href="../STYLES/main.css">
</head>

<body>

<header>
    <h1>Tambah Mahasiswa</h1>
</header>

<div class="container-layout">

    <aside>
        <nav>
            <a href="daftar-akun.php">Daftar Akun</a>
            <a href="../profil.php">Profil Saya</a>
        </nav>
    </aside>

    <main>

        <h2>Form Tambah Mahasiswa</h2>

        <div class="card" style="padding:20px; max-width:500px;">

            <label>Username</label>
            <input type="text" id="mhs-username" placeholder="misal: joshua99">

            <label>Password</label>
            <input type="password" id="mhs-password">

            <label>Nama Mahasiswa</label>
            <input type="text" id="mhs-nama" placeholder="Nama lengkap">

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

            <label>Foto Profil</label>
            <input type="file" id="mhs-foto" accept="image/*">

            <button id="btn-tambah-mhs" style="margin-top:20px;">
                Tambah Mahasiswa
            </button>

        </div>

        <div id="status-message" style="margin-top:20px; color:red;"></div>

    </main>

</div>

</body>

<script>
$(document).ready(function() {

    checkLoggedIn();

    $("#btn-tambah-mhs").on("click", function () {
        tambahMahasiswa();
    });

});


function tambahMahasiswa() {

    const username = $("#mhs-username").val().trim();
    const password = $("#mhs-password").val().trim();
    const nama = $("#mhs-nama").val().trim();
    const nrp = $("#mhs-nrp").val().trim();
    const gender = $("#mhs-gender").val();
    const angkatan = $("#mhs-angkatan").val();
    const tanggal_lahir = $("#mhs-tanggal-lahir").val();
    const fotoFile = $("#mhs-foto")[0].files[0];

    if (!username || !password || !nama || !nrp || !angkatan || !tanggal_lahir) {
        $("#status-message").text("Semua field wajib diisi.");
        return;
    }

    // --- USE FORMDATA ---
    let formData = new FormData();
    formData.append("username", username);
    formData.append("password", password);
    formData.append("nama", nama);
    formData.append("nrp", nrp);
    formData.append("gender", gender);
    formData.append("angkatan", angkatan);
    formData.append("tanggal_lahir", tanggal_lahir);

    if (fotoFile) {
        formData.append("foto", fotoFile);
    }

    const url = API_ADDRESS + "MAHASISWA/";

    console.group("TAMBAH MAHASISWA - START");
    console.group("REQUEST INFO");
    console.log("URL:", url);
    console.log("METHOD: POST");
    console.log("Payload (text fields):", {
        username, password, nama, nrp, gender, angkatan, tanggal_lahir
    });
    console.log("Foto:", fotoFile ? fotoFile.name : "Tidak ada foto");
    console.groupEnd();

    $.ajax({
        url: url,
        type: "POST",
        data: formData,
        processData: false,    // wajib
        contentType: false,    // wajib
        dataType: "json",

        success: function (res) {

            console.group("RESPONSE");
            console.log("SUCCESS:", res);
            console.groupEnd();

            if (res.status === "success") {
                $("#status-message")
                    .css("color", "green")
                    .text("Mahasiswa berhasil ditambahkan!");

                setTimeout(() => {
                    window.location.href = "index.php";
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
                xhr.responseJSON?.message || "Gagal menambahkan mahasiswa."
            );
        },

        complete: function () {
            console.groupEnd();
        }

    });

}
</script>

</html>
