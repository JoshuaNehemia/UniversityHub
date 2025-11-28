<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Tambah Group | Dosen - University Hub</title>

    <script src="SCRIPTS/jquery_3_7_1.js"></script>
    <script src="SCRIPTS/config.js"></script>
    <script src="SCRIPTS/auth.js"></script>

    <link rel="stylesheet" href="STYLES/main.css">
</head>

<body>

<header>
    <h1>Tambah Group</h1>
</header>

<div class="container-layout">

    <aside>
        <nav>
            <a href="profil.php">Profil</a>
            <a href="group.php">Group</a>
            <a href="thread.php">Thread</a>
            <a href="event.php">Event</a>
        </nav>
    </aside>

    <main>

        <h2>Form Tambah Group</h2>

        <div class="card" style="padding:20px; max-width:500px;">

            <label>Nama Group</label>
            <input type="text" id="group-nama" placeholder="Nama group">

            <label>Jenis Group</label>
            <select id="group-jenis">
                <option value="Publik">Publik</option>
                <option value="Privat">Privat</option>
            </select>

            <div id="kode-section" style="display:none; margin-top:10px;">
                <label>Kode Pendaftaran</label>
                <input type="text" id="group-kode" placeholder="Contoh: ABC123">
            </div>

            <label>Deskripsi</label>
            <textarea id="group-deskripsi" rows="4"></textarea>

            <button id="btn-create-group" style="margin-top:20px;">
                Buat Group
            </button>

        </div>

        <div id="status-message" style="margin-top:20px; color:red;"></div>

    </main>

</div>

</body>

<script>
$(document).ready(function () {

    checkLoggedIn(); // pastikan user login

    // Jika jenis group Privat → tampilkan input kode
    $("#group-jenis").on("change", function () {
        if ($(this).val() === "Privat") {
            $("#kode-section").show();
        } else {
            $("#kode-section").hide();
        }
    });

    $("#btn-create-group").on("click", function () {
        createGroup();
    });
});


// =======================================================
// CREATE GROUP (POST /GROUP/)
// =======================================================
function createGroup() {

    const nama = $("#group-nama").val().trim();
    const jenis = $("#group-jenis").val();
    const deskripsi = $("#group-deskripsi").val().trim();
    const kode = $("#group-kode").val().trim();

    if (!nama || !deskripsi) {
        $("#status-message").text("Nama & Deskripsi wajib diisi.");
        return;
    }

    const payload = {
        nama,
        jenis,
        deskripsi
    };

    if (jenis === "Privat") {
        payload.kode = kode;
    }

    const url = API_ADDRESS + "GROUP/";

    console.group("CREATE GROUP - START");
    console.group("REQUEST INFO");
    console.log("URL:", url);
    console.log("METHOD: POST");
    console.log("PAYLOAD:", payload);
    console.groupEnd();

    $.ajax({
        url: url,
        type: "POST",
        data: (payload),
        dataType: "json",

        success: function (res) {
            console.group("RESPONSE");
            console.log("SUCCESS:", res);
            console.groupEnd();

            if (res.status === "success") {
                $("#status-message")
                    .css("color", "green")
                    .text("Group berhasil dibuat!");

                setTimeout(() => {
                    window.location.href = "group.php";
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
                xhr.responseJSON?.message || "Gagal membuat group."
            );
        },

        complete: function () {
            console.groupEnd();
        }
    });
}
</script>

</html>
