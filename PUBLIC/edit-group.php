<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Edit Group | University Hub</title>

    <script src="SCRIPTS/jquery_3_7_1.js"></script>
    <script src="SCRIPTS/config.js"></script>
    <script src="SCRIPTS/auth.js"></script>

    <link rel="stylesheet" href="STYLES/main.css">
</head>

<body>

    <header>
        <h1>Edit Group</h1>
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
            <h2>Edit Informasi Group</h2>

            <form id="edit-group-form">

                <label>Nama Group</label>
                <input type="text" id="nama-group" required>

                <label>Jenis Group</label>
                <select id="jenis-group" required>
                    <option value="Privat">Privat</option>
                    <option value="Publik">Publik</option>
                </select>

                <label>Deskripsi Group</label>
                <textarea id="deskripsi-group" rows="4" required></textarea>

                <button type="submit">Simpan Perubahan</button>
            </form>


            <div id="status-message" style="margin-top:20px;"></div>
        </main>
    </div>

</body>

<script>
    $(document).ready(function() {

        const urlParams = new URLSearchParams(window.location.search);
        const idgroup = urlParams.get("idgroup");

        if (!idgroup) {
            $("#status-message").html("ID Group tidak ditemukan.");
            return;
        }

        loadGroupData(idgroup);
        checkLoggedIn();

        $("#edit-group-form").on("submit", function(e) {
            e.preventDefault();
            saveGroupEdit(idgroup);
        });
    });

    function loadGroupData(idgroup) {

        console.group("LOAD GROUP DATA - START");

        console.group("REQUEST INFO");
        console.log("URL:", API_ADDRESS + "GROUP/");
        console.log("Method: GET");
        console.log("Parameter:", {
            id: idgroup
        });
        console.groupEnd();

        $.ajax({
            url: API_ADDRESS + "GROUP/",
            type: "GET",
            dataType: "json",
            data: {
                id: idgroup
            },

            success: function(res) {
                console.group("RESPONSE");
                console.log("Status:", res.status);
                console.log("Data:", res.data);
                console.groupEnd();

                if (res.status === "success") {
                    $("#nama-group").val(res.data.nama);
                    $("#deskripsi-group").val(res.data.deskripsi);
                } else {
                    $("#status-message").text(res.message);
                }
            },

            error: function(xhr) {
                console.group("ERROR");
                console.log("Status Code:", xhr.status);
                console.log("Response:", xhr.responseJSON);
                console.groupEnd();

                let msg = xhr.responseJSON?.message || "Gagal mengambil data group.";
                $("#status-message").text(msg);
            },

            complete: function() {
                console.groupEnd(); // END OF MAIN GROUP
            }
        });
    }

    function saveGroupEdit(idgroup) {

        const payload = {
            nama: $("#nama-group").val(),
            deskripsi: $("#deskripsi-group").val(),
            jenis: $("#jenis-group").val(),
            id: idgroup
        };

        console.group("EDIT GROUP - START");

        console.group("REQUEST INFO");
        console.log("URL:", API_ADDRESS + "GROUP/");
        console.log("Method: PUT");
        console.groupEnd();

        console.group("PAYLOAD");
        console.log(payload);
        console.groupEnd();

        $.ajax({
            url: API_ADDRESS + "GROUP/",
            type: "PUT",
            data: JSON.stringify(payload),
            contentType: "application/json",
            dataType: "json",

            success: function(res) {
                console.group("RESPONSE");
                console.log("Status:", res.status);
                console.log("Data:", res.data);
                console.groupEnd();

                if (res.status === "success") {
                    $("#status-message").text("Berhasil mengupdate group!");

                    setTimeout(() => {
                        window.location.href = "detail-group.php?idgroup=" + idgroup;
                    }, 1500);

                } else {
                    $("#status-message").css("color", "red").text(res.message);
                }
            },

            error: function(xhr) {
                console.group("ERROR");
                console.log("Status Code:", xhr.status);
                console.log("Response:", xhr.responseJSON);
                console.groupEnd();

                let msg = xhr.responseJSON?.message || "Terjadi kesalahan saat mengupdate.";
                $("#status-message").css("color", "red").text(msg);
            },

            complete: function() {
                console.groupEnd(); // END MAIN GROUP
            }
        });
    }
</script>

</html>