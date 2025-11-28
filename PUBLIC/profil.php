<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Profil Akun | University Hub</title>

    <script src="SCRIPTS/jquery_3_7_1.js"></script>
    <script src="SCRIPTS/config.js"></script>
    <script src="SCRIPTS/auth.js"></script>

    <link rel="stylesheet" href="STYLES/main.css">
</head>

<body>

<header>
    <h1>Profil Akun</h1>
</header>

<div class="container-layout">

    <aside>
        <nav>
            <a href="profil.php" class="active">Profil</a>
            <a href="group.php">Group</a>
            <a href="thread.php">Thread</a>
            <a href="event.php">Event</a>
        </nav>
    </aside>

    <main>
        <h2>Informasi Akun</h2>

        <div class="card" style="padding:20px; max-width:500px;" id="profile-card">

            <img id="profile-photo" 
                 src="" 
                 alt="Foto Profil">

            <p><strong>Nama:</strong> <span id="nama"></span></p>
            <p><strong>Username:</strong> <span id="username"></span></p>
            <p><strong>Role:</strong> <span id="role"></span></p>
            
            <p id="mahasiswa-nrp-block" style="display:none;">
                <strong>NRP:</strong> <span id="nrp"></span>
                <strong>Gender:</strong> <span id="gender"></span>
                <strong>Angkatan:</strong> <span id="angkatan"></span>
            </p>

            <p id="dosen-npk-block" style="display:none;">
                <strong>NPK:</strong> <span id="npk"></span>
            </p>
        </div>

        <div id="status-message" style="margin-top:20px; color:red;"></div>
    </main>

</div>

</body>

<script>
$(document).ready(function () {

    checkLoggedIn();
    loadProfile();

});


// ========================================================
// LOAD PROFILE FROM API: AUTH/?jenis=account
// ========================================================
function loadProfile() {

    const url = API_ADDRESS + "AUTH/";

    console.group("LOAD PROFILE - START");

    console.group("REQUEST INFO");
    console.log("URL:", url);
    console.log("Method:", "GET");
    console.log("Params:", { jenis: "account" });
    console.groupEnd();

    $.ajax({
        url: url,
        type: "GET",
        data: { jenis: "account" },
        dataType: "json",

        success: function (res) {

            console.group("RESPONSE");
            console.log("SUCCESS:", res);
            console.groupEnd();

            if (res.status !== "success") {
                $("#status-message").text(res.message);
                return;
            }

            const d = res.data;

            // SET COMMON DATA
            $("#nama").text(d.nama);
            $("#username").text(d.username);
            $("#role").text(d.jenis);
            $("#email").text(d.email);

            // TAMPILKAN MAHASISWA
            if (d.jenis === "MAHASISWA") {

                $("#mahasiswa-nrp-block").show();
                $("#nrp").text(d.nrp);
                $("#gender").text(d.gender);
                $("#angkatan").text(d.angkatan);

                const foto = `http://localhost/UniversityHub/APP/DATABASE/PROFILE/MAHASISWA/${d.nrp}.${d.foto_extention}`;
                $("#profile-photo").attr("src", foto);
            }

            // TAMPILKAN DOSEN
            if (d.jenis === "DOSEN") {

                $("#dosen-npk-block").show();
                $("#npk").text(d.npk);

                const foto = `http://localhost/UniversityHub/APP/DATABASE/PROFILE/DOSEN/${d.npk}.${d.foto_extention}`;
                $("#profile-photo").attr("src", foto);
            }
        },

        error: function (xhr) {
            console.group("ERROR");
            console.error(xhr);
            console.groupEnd();

            $("#status-message").text(
                xhr.responseJSON?.message || "Gagal memuat profil akun."
            );
        },

        complete: function () {
            console.groupEnd();
        }
    });
}
</script>

</html>
