<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Tambah Event | University Hub</title>

    <script src="SCRIPTS/jquery_3_7_1.js"></script>
    <script src="SCRIPTS/config.js"></script>
    <script src="SCRIPTS/auth.js"></script>

    <link rel="stylesheet" href="STYLES/main.css">
</head>

<body>

<header>
    <h1>Tambah Event Group</h1>
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

        <h2>Form Tambah Event</h2>

        <div class="card" style="padding:20px; max-width:500px;">

            <label>Judul Event</label>
            <input type="text" id="event-judul" required>

            <label>Keterangan Event</label>
            <textarea id="event-keterangan" rows="3" required></textarea>

            <label>Tanggal Event</label>
            <input type="datetime-local" id="event-tanggal" required>

            <label>Jenis Event</label>
            <select id="event-jenis" required>
                <option value="Publik">Publik</option>
                <option value="Privat">Privat</option>
            </select>

            <label>Foto Event</label>
            <input type="file" id="event-foto" accept="image/*" required>

            <button id="btn-submit-event" style="margin-top:15px;">Tambah Event</button>
        </div>

        <div id="status-message" style="margin-top:20px;"></div>

    </main>

</div>

</body>

<script>
$(document).ready(function () {

    const urlParams = new URLSearchParams(window.location.search);
    const idgroup = urlParams.get("idgroup");

    if (!idgroup) {
        $("#status-message").html("ID Group tidak ditemukan.");
        return;
    }

    checkLoggedIn();

    $("#btn-submit-event").on("click", function () {
        submitEvent(idgroup);
    });
});


// ======================================================
// SUBMIT EVENT
// ======================================================
function submitEvent(idgroup) {

    const judul = $("#event-judul").val().trim();
    const ket = $("#event-keterangan").val().trim();
    const tgl = $("#event-tanggal").val();
    const jenis = $("#event-jenis").val();
    const foto = $("#event-foto")[0].files[0];

    if (!judul || !ket || !tgl || !jenis || !foto) {
        $("#status-message").css("color", "red").text("Semua field wajib diisi.");
        return;
    }

    // Format tanggal ke Y-m-d H:i:s
    const tanggalFormatted = tgl.replace("T", " ") + ":00";

    let formData = new FormData();
    formData.append("judul", judul);
    formData.append("keterangan", ket);
    formData.append("tanggal", tanggalFormatted);
    formData.append("jenis", jenis);
    formData.append("foto", foto);

    const url = API_ADDRESS + "EVENT/" + idgroup + "/";
    const method = "POST";

    console.group("ADD EVENT - START");

    console.group("REQUEST INFO");
    console.log("URL:", url);
    console.log("Method:", method);
    console.log("Data:", {
        judul,
        ket,
        tanggalFormatted,
        jenis,
        fotoName: foto.name
    });
    console.groupEnd();

    console.group("AJAX");

    $.ajax({
        url: url,
        type: method,
        data: formData,
        processData: false,  // wajib untuk file upload
        contentType: false,  // wajib untuk multipart form
        dataType: "json",

        success: function (res) {
            console.log("SUCCESS:", res);

            if (res.status === "success") {
                $("#status-message").css("color", "green")
                    .text("Event berhasil ditambahkan!");

                // redirect kembali ke halaman event group
                setTimeout(() => {
                    window.location.href = "detail-group.php?idgroup=" + idgroup;
                }, 1500);

            } else {
                $("#status-message").css("color", "red")
                    .text(res.message);
            }
        },

        error: function (xhr) {
            console.error("ERROR:", xhr);
            let msg = xhr.responseJSON?.message || "Terjadi kesalahan server.";
            $("#status-message").css("color", "red").text(msg);
        },

        complete: function () {
            console.groupEnd(); // AJAX
            console.groupEnd(); // START
        }
    });
}
</script>

</html>
