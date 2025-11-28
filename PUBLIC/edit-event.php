<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Edit Event | University Hub</title>

    <script src="SCRIPTS/jquery_3_7_1.js"></script>
    <script src="SCRIPTS/config.js"></script>
    <script src="SCRIPTS/auth.js"></script>

    <link rel="stylesheet" href="STYLES/main.css">
</head>

<body>

<header>
    <h1>Edit Event</h1>
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

        <h2>Form Edit Event</h2>

        <div class="card" style="padding:20px; max-width:500px;">

            <input type="hidden" id="event-id">

            <label>Judul Event</label>
            <input type="text" id="event-judul" required>

            <label>Keterangan Event</label>
            <textarea id="event-keterangan" rows="3" required></textarea>

            <label>Tanggal Event</label>
            <input type="datetime-local" id="event-tanggal" required>

            <label>Jenis Event</label>
            <select id="event-jenis">
                <option value="Publik">Publik</option>
                <option value="Privat">Privat</option>
            </select>

            <div id="poster-preview" style="margin-top:15px;"></div>

            <button id="btn-update-event" style="margin-top:20px;">
                Simpan Perubahan
            </button>

        </div>

        <div id="status-message" style="margin-top:20px;"></div>

    </main>

</div>

</body>


<!-- ============================================================
     JAVASCRIPT
=============================================================== -->
<script>
$(document).ready(function () {

    const urlParams = new URLSearchParams(window.location.search);
    const idevent = urlParams.get("idevent");
    const idgroup = urlParams.get("idgroup");

    if (!idevent || !idgroup) {
        $("#status-message").html("Data event tidak lengkap.");
        return;
    }

    checkLoggedIn();

    $("#event-id").val(idevent);

    loadEventDetail(idgroup, idevent);

    $("#btn-update-event").on("click", function () {
        updateEvent(idgroup, idevent);
    });
});


// ========================================================
// LOAD EVENT DETAIL
// GET EVENT/{idgroup}/?idevent=
// ========================================================
function loadEventDetail(idgroup, idevent) {

    const url = API_ADDRESS + "EVENT/" + idgroup + "/";

    console.group("LOAD EVENT DETAIL - START");
    console.group("REQUEST INFO");
    console.log("URL:", url);
    console.log("Method:", "GET");
    console.log("Params:", { idevent });
    console.groupEnd();

    $.ajax({
        url: url,
        type: "GET",
        data: { idevent: idevent },
        dataType: "json",

        success: function(res) {

            console.group("RESPONSE");
            console.log("SUCCESS:", res);
            console.groupEnd();

            if (res.status !== "success") {
                $("#status-message").text(res.message);
                return;
            }

            const d = res.data;

            $("#event-judul").val(d.judul);
            $("#event-keterangan").val(d.keterangan);

            const tgl = d.tanggal.replace(" ", "T").slice(0, 16);
            $("#event-tanggal").val(tgl);

            $("#event-jenis").val(d.jenis);

            // Jika kamu punya poster extension, tampilkan:
            if (d.poster_extention) {
                $("#poster-preview").html(`
                    <p>Poster Saat Ini:</p>
                    <img src="http://localhost/UniversityHub/APP/DATABASE/EVENT/${d.id}.${d.poster_extention}" 
                         style="width:200px; border-radius:10px;">
                `);
            }
        },

        error: function(xhr) {
            console.group("ERROR");
            console.error(xhr);
            console.groupEnd();

            $("#status-message").text("Gagal memuat data event.");
        },

        complete: function () {
            console.groupEnd();
        }
    });
}


// ========================================================
// UPDATE EVENT (PUT) — JSON OBJECT
// ========================================================
function updateEvent(idgroup, idevent) {

    const judul = $("#event-judul").val();
    const ket = $("#event-keterangan").val();
    const tgl = $("#event-tanggal").val();
    const jenis = $("#event-jenis").val();

    if (!judul || !ket || !tgl || !jenis) {
        $("#status-message").css("color", "red").text("Semua field wajib diisi.");
        return;
    }

    const tanggalFinal = tgl.replace("T", " ") + ":00";

    const payload = {
        idevent: idevent,
        judul: judul,
        keterangan: ket,
        tanggal: tanggalFinal,
        jenis: jenis
    };

    const url = API_ADDRESS + "EVENT/" + idgroup + "/";

    console.group("UPDATE EVENT - START");
    console.group("REQUEST INFO");
    console.log("URL:", url);
    console.log("Method:", "PUT");
    console.log("Payload:", payload);
    console.groupEnd();

    $.ajax({
        url: url,
        type: "PUT",
        contentType: "application/json",
        data: JSON.stringify(payload),
        dataType: "json",

        success: function (res) {

            console.group("RESPONSE");
            console.log("SUCCESS:", res);
            console.groupEnd();

            if (res.status === "success") {
                $("#status-message").css("color", "green").text("Event berhasil diperbarui!");

                setTimeout(() => {
                    window.location.href = "detail-group.php?idgroup=" + idgroup;
                }, 1500);
            } else {
                $("#status-message").css("color", "red").text(res.message);
            }
        },

        error: function(xhr) {
            console.group("ERROR");
            console.error(xhr);
            console.groupEnd();

            $("#status-message").css("color", "red")
                .text(xhr.responseJSON?.message || "Gagal update event.");
        },

        complete: function () {
            console.groupEnd();
        }
    });
}
</script>

</html>
