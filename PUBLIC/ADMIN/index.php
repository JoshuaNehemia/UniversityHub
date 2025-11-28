<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Daftar Akun | University Hub</title>

    <script src="../SCRIPTS/jquery_3_7_1.js"></script>
    <script src="../SCRIPTS/config.js"></script>
    <script src="../SCRIPTS/auth.js"></script>

    <link rel="stylesheet" href="../STYLES/main.css">
</head>

<body>

    <header>
        <h1>Daftar Akun</h1>
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

            <h2>List Mahasiswa & Dosen</h2>
            <button onclick="window.location.href='add-mahasiswa.php'" style="margin-top:15px;">
                Tambah Mahasiswa
            </button>
            <button onclick="window.location.href='add-dosen.php'" style="margin-top:15px;">
                Tambah Dosen
            </button>

            <!-- FORM FILTER -->
            <div class="card" style="padding:20px; max-width:500px;">

                <label>Pilih Jenis Akun</label>
                <select id="select-jenis">
                    <option value="MAHASISWA">Mahasiswa</option>
                    <option value="DOSEN">Dosen</option>
                </select>

                <label>Keyword</label>
                <input type="text" id="keyword-input" placeholder="Cari nama / username">

                <button id="btn-cari" style="margin-top:15px;">
                    Cari
                </button>

            </div>

            <!-- PAGINATION -->
            <div id="pagination" style="margin-top:20px; margin-bottom:20px;">
                <button id="page-prev">Prev</button>
                <span id="page-number" style="margin:0 10px;">0</span>
                <button id="page-next">Next</button>
            </div>

            <!-- LIST -->
            <div id="list-akun"></div>

            <div id="status-message" style="margin-top:20px;"></div>

        </main>

    </div>

</body>

<script>
    $(document).ready(function() {

        checkLoggedIn();

        let offset = 0;
        const limit = 10;

        loadAkun("MAHASISWA", "", offset);

        $("#btn-cari").on("click", function() {
            offset = 0;
            loadAkun($("#select-jenis").val(), $("#keyword-input").val(), offset);
        });

        $("#page-prev").on("click", function() {
            if (offset > 0) {
                offset--;
                loadAkun($("#select-jenis").val(), $("#keyword-input").val(), offset);
            }
        });

        $("#page-next").on("click", function() {
            offset++;
            loadAkun($("#select-jenis").val(), $("#keyword-input").val(), offset);
        });

    });


    // ==========================================================
    // LOAD AKUN (MAHASISWA / DOSEN)
    // ==========================================================
    function loadAkun(jenis, keyword, offset) {

        const url = API_ADDRESS + jenis + "/";

        console.group("LOAD AKUN - START");

        console.group("REQUEST INFO");
        console.log("URL:", url);
        console.log("Jenis:", jenis);
        console.log("Keyword:", keyword);
        console.log("Offset:", offset);
        console.groupEnd();

        $.ajax({
            url: url,
            type: "GET",
            dataType: "json",
            data: {
                keyword: keyword || "",
                limit: 10,
                offset: offset
            },

            success: function(res) {
                console.group("RESPONSE");
                console.log("SUCCESS:", res);
                console.groupEnd();

                const list = $("#list-akun");
                list.empty();
                $("#page-number").text(offset);

                if (res.status !== "success" || res.data.length === 0) {
                    list.html("<p>Tidak ada data ditemukan.</p>");
                    return;
                }

                res.data.forEach(item => {

                    let fotoPath = "";
                    let extraInfo = "";
                    let editPage = "";

                    if (jenis === "MAHASISWA") {
                        fotoPath = `http://localhost/UniversityHub/APP/DATABASE/PROFILE/MAHASISWA/${item.nrp}.${item.foto_extention}`;
                        extraInfo = `NRP: ${item.nrp}`;
                        editPage = `edit-mahasiswa.php?username=${item.username}`;
                    }

                    if (jenis === "DOSEN") {
                        fotoPath = `http://localhost/UniversityHub/APP/DATABASE/PROFILE/DOSEN/${item.npk}.${item.foto_extention}`;
                        extraInfo = `NPK: ${item.npk}`;
                        editPage = `edit-dosen.php?username=${item.username}`;
                    }

                    const card = `
                    <div class="card" style="padding:15px; margin-top:15px; max-width:450px;">

                        <img src="${fotoPath}"
                            class="foto-member"
                            style="width:100px; height:100px; object-fit:cover; border-radius:50%;">

                        <p><strong>${item.nama}</strong></p>
                        <p>${extraInfo}</p>
                        <p>Username: ${item.username}</p>

                        <button class="btn-edit-akun" data-edit="${editPage}">
                            Edit Profil
                        </button>

                        <button class="btn-edit-password"
                                data-username="${item.username}">
                            Edit Password
                        </button>

                        <button class="btn-delete-akun"
                                data-username="${item.username}">
                            Hapus Akun
                        </button>

                    </div>
                `;

                    list.append(card);
                });

                $(".btn-edit-akun").on("click", function() {
                    window.location.href = $(this).data("edit");
                });

                $(".btn-edit-password").on("click", function() {
                    const username = $(this).data("username");
                    window.location.href = `edit-password.php?username=${username}`;
                });

                $(".btn-delete-akun").on("click", function() {
                    const username = $(this).data("username");
                    if (confirm("Yakin ingin menghapus akun ini?")) {
                        deleteAkun(jenis, username);
                    }
                });

            },

            error: function(xhr) {
                console.group("ERROR");
                console.error(xhr);
                console.groupEnd();
                $("#status-message").text("Gagal mengambil data akun.");
            },

            complete: function() {
                console.groupEnd();
            }
        });

    }


    // ==========================================================
    // DELETE AKUN
    // ==========================================================
    function deleteAkun(jenis, username) {

        const url = API_ADDRESS + jenis + "/";

        const payload = {
            username: username
        };

        console.group("DELETE AKUN - START");

        console.group("REQUEST INFO");
        console.log("URL:", url);
        console.log("Method:", "DELETE");
        console.log("Payload:", payload);
        console.groupEnd();

        $.ajax({
            url: url,
            type: "DELETE",
            contentType: "application/json",
            data: JSON.stringify(payload),
            dataType: "json",

            success: function(res) {
                console.group("RESPONSE");
                console.log("SUCCESS:", res);
                console.groupEnd();

                alert(res.message);
                location.reload();
            },

            error: function(xhr) {
                console.group("ERROR");
                console.error(xhr);
                console.groupEnd();
                alert(xhr.responseJSON?.message || "Gagal menghapus akun.");
            },

            complete: function() {
                console.groupEnd();
            }
        });
    }
</script>

</html>