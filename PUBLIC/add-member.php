<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Tambah Member Group | University Hub</title>

    <script src="SCRIPTS/jquery_3_7_1.js"></script>
    <script src="SCRIPTS/config.js"></script>
    <script src="SCRIPTS/auth.js"></script>

    <link rel="stylesheet" href="STYLES/main.css">
</head>

<body>

    <header>
        <h1>Tambah Member Group</h1>
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

            <h2>Cari Member untuk Ditambahkan</h2>

            <!-- FORM PENCARIAN -->
            <div class="card" style="padding:20px; max-width:500px;">

                <label>Pilih Jenis Akun</label>
                <select id="select-jenis" style="margin-bottom:10px;">
                    <option value="MAHASISWA">Mahasiswa</option>
                    <option value="DOSEN">Dosen</option>
                </select>

                <label>Masukkan Keyword</label>
                <input type="text" id="keyword-input" placeholder="Cari nama / NRP / username">

                <button id="btn-cari-member" style="margin-top:15px;">
                    Cari Member
                </button>
            </div>

            <div id="pagination" style="margin-top:20px; margin-bottom:20px;">
                <button id="page-prev">Prev</button>
                <span id="page-number" style="margin:0 10px;">0</span>
                <button id="page-next">Next</button>
            </div>

            <div id="list-hasil-pencarian"></div>

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

        checkLoggedIn();

        let offset = 0;

        // AUTO LOAD daftar mahasiswa halaman pertama
        cariMember("MAHASISWA", "", idgroup, offset);

        $("#btn-cari-member").on("click", function() {
            offset = 0;
            cariMember($("#select-jenis").val(), $("#keyword-input").val(), idgroup, offset);
        });

        $("#page-prev").on("click", function() {
            if (offset > 0) {
                offset--;
                cariMember($("#select-jenis").val(), $("#keyword-input").val(), idgroup, offset);
            }
        });

        $("#page-next").on("click", function() {
            offset++;
            cariMember($("#select-jenis").val(), $("#keyword-input").val(), idgroup, offset);
        });

    });


    // =============================================
    // FUNGSI UTAMA: CARI MEMBER
    // =============================================
    function cariMember(jenis, keyword, idgroup, offset) {

        const limit = 10;

        console.group("CARI " + jenis + " - START");

        const url = API_ADDRESS + jenis + "/";
        const method = "GET";

        console.group("REQUEST INFO");
        console.log("URL:", url);
        console.log("Method:", method);
        console.log("Keyword:", keyword);
        console.log("Limit:", limit);
        console.log("Offset:", offset);
        console.groupEnd();

        console.group("AJAX");

        $.ajax({
            url: url,
            type: method,
            dataType: "json",
            data: {
                limit: limit,
                offset: offset,
                keyword: keyword
            },

            success: function(res) {
                console.log("SUCCESS:", res);

                const list = $("#list-hasil-pencarian");
                list.empty();

                $("#page-number").text(offset);

                if (res.status !== "success" || res.data.length === 0) {
                    list.html("<p>Tidak ada hasil ditemukan.</p>");
                    return;
                }

                res.data.forEach(item => {

                    let card = "";

                    if (jenis === "MAHASISWA") {
                        card = `
    <div class="card member" style="margin-top:10px; padding:15px;">
        <img src="http://localhost/UniversityHub/APP/DATABASE/PROFILE/MAHASISWA/${item.nrp}.${item.foto_extention}" class="foto-member">

        <p>${item.nama}</p>
        <p>Username: ${item.username}</p>

        <button class="btn-add-member"
            data-username="${item.username}"
            data-group="${idgroup}"
            style="margin-top:10px;">
            Tambah ke Group
        </button>
    </div>
`;

                    }

                    if (jenis === "DOSEN") {
                        card = `
    <div class="card member" style="margin-top:10px; padding:15px;">
        <img src="http://localhost/UniversityHub/APP/DATABASE/PROFILE/DOSEN/${item.npk}.${item.foto_extention}" class="foto-member">

        <p>${item.nama}</p>
        <p>Username: ${item.username}</p>

        <button class="btn-add-member"
            data-username="${item.username}"
            data-group="${idgroup}"
            style="margin-top:10px;">
            Tambah ke Group
        </button>
    </div>
`;

                    }

                    list.append(card);
                });

                $(".btn-add-member").on("click", function() {
                    tambahMember($(this).data("username"), idgroup);
                });
            },

            error: function(xhr, status, error) {
                console.error("ERROR:", {
                    xhr,
                    status,
                    error
                });
                $("#list-hasil-pencarian").html("Terjadi kesalahan server.");
            },

            complete: function() {
                console.groupEnd(); // AJAX
                console.groupEnd(); // START
            }
        });

    }


    // =============================================
    // FUNGSI TAMBAH MEMBER KE GROUP
    // =============================================
    function tambahMember(username, idgroup) {

        const url = API_ADDRESS + "MEMBER/" + idgroup + "/";
        const method = "POST";

        const payload = {
            username: username
        };

        console.group("ADD MEMBER - START");

        console.group("REQUEST INFO");
        console.log("URL:", url);
        console.log("Method:", method);
        console.log("Payload:", payload);
        console.groupEnd();

        $.ajax({
            url: url,
            type: method,
            dataType: "json",
            data: payload,

            success: function(res) {
                console.log("SUCCESS:", res);

                if (res.status === "success") {
                    alert("Member berhasil ditambahkan!");
                } else {
                    alert("Gagal: " + res.message);
                }
            },

            error: function(xhr) {
                console.error("ERROR:", xhr);
                alert(xhr.responseJSON?.message || "Terjadi kesalahan.");
            },

            complete: function() {
                console.groupEnd();
            }
        });

    }
</script>

</html>