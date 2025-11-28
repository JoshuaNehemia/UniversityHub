<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Cari Grup | University Hub</title>

    <script src="SCRIPTS/jquery_3_7_1.js"></script>
    <script src="SCRIPTS/config.js"></script>
    <script src="SCRIPTS/auth.js"></script>

    <link rel="stylesheet" href="STYLES/main.css">
</head>

<body>

    <header>
        <h1>Pencarian Group</h1>
    </header>

    <div class="container-layout">

        <aside>
            <nav>
                <a href="profil.php">Profil</a>
                <a href="group.php">Group Saya</a>
                <a href="thread.php">Thread</a>
            </nav>
        </aside>

        <main>

            <h2>Cari Group</h2>

            <!-- FORM PENCARIAN -->
            <div class="card" style="padding:20px; max-width:500px;">
                <label>Kata Kunci</label>
                <input type="text" id="search-keyword" placeholder="nama grup...">

                <button id="btn-search-group" style="margin-top:15px;">
                    Cari Group
                </button>
            </div>

            <!-- PAGINATION -->
            <div id="pagination" style="margin-top:20px; margin-bottom:20px;">
                <button id="page-prev">Prev</button>
                <span id="page-number" style="margin:0 10px;">0</span>
                <button id="page-next">Next</button>
            </div>

            <!-- LIST GRUP -->
            <div id="group-result-list"></div>

            <div id="status-message" style="margin-top:20px;"></div>

        </main>

    </div>


    <!-- POPUP MASUKKAN KODE -->
    <div id="kode-popup"
        style="display:none; position:fixed; top:0; left:0; width:100%; height:100%;
            background:rgba(0,0,0,0.5); justify-content:center; align-items:center;">

        <div class="card" style="padding:20px; width:300px; background:white;">
            <h3>Masukkan Kode Group</h3>

            <input type="text" id="kode-input" placeholder="Kode pendaftaran">

            <button id="btn-submit-kode" style="margin-top:15px;">Submit</button>
            <button id="btn-close-popup" style="margin-top:10px;">Batal</button>
        </div>

    </div>


</body>

<script>
    $(document).ready(function() {

        checkLoggedIn();

        let offset = 0;

        console.group("INIT PAGE");
        console.log("Page:", "search-group.php loaded");
        console.log("Default offset:", offset);
        console.groupEnd();

        // ===============================================
        // AUTO LOAD DATA AWAL
        // ===============================================
        searchGroup("", offset);

        // ------------------------------------
        // SEARCH BUTTON
        // ------------------------------------
        $("#btn-search-group").on("click", function() {
            console.group("SEARCH BUTTON CLICKED");
            offset = 0;
            console.log("Keyword:", $("#search-keyword").val());
            console.log("Offset reset:", offset);
            console.groupEnd();

            searchGroup($("#search-keyword").val(), offset);
        });

        // ------------------------------------
        // PAGINATION PREV
        // ------------------------------------
        $("#page-prev").on("click", function() {
            console.group("PAGE PREV CLICKED");
            if (offset > 0) {
                offset--;
                console.log("New offset:", offset);
                searchGroup($("#search-keyword").val(), offset);
            } else {
                console.log("Offset sudah 0, tidak bisa mundur");
            }
            console.groupEnd();
        });

        // ------------------------------------
        // PAGINATION NEXT
        // ------------------------------------
        $("#page-next").on("click", function() {
            console.group("PAGE NEXT CLICKED");
            offset++;
            console.log("New offset:", offset);
            console.groupEnd();

            searchGroup($("#search-keyword").val(), offset);
        });

        // ------------------------------------
        // CLOSE POPUP
        // ------------------------------------
        $("#btn-close-popup").on("click", function() {
            console.group("POPUP CLOSED");
            console.log("Popup ditutup oleh user");
            console.groupEnd();

            $("#kode-popup").hide();
            $("#kode-input").val("");
            selectedGroupId = null;
        });

        // ------------------------------------
        // SUBMIT KODE BUTTON
        // ------------------------------------
        $("#btn-submit-kode").on("click", function() {
            const kode = $("#kode-input").val().trim();

            console.group("SUBMIT KODE CLICKED");
            console.log("Kode input:", kode);
            console.log("Selected Group ID:", selectedGroupId);

            if (!selectedGroupId) {
                console.error("selectedGroupId is NULL");
                alert("Group ID tidak ditemukan!");
                console.groupEnd();
                return;
            }

            if (kode.length === 0) {
                console.error("Kode kosong");
                alert("Kode tidak boleh kosong!");
                console.groupEnd();
                return;
            }

            console.groupEnd(); 

            joinGroup(selectedGroupId, kode);
        });

    });


    // ====================================================================
    // GET GROUP LIST LOGGING
    // ====================================================================
    function searchGroup(keyword, offset) {

        const url = API_ADDRESS + "GROUP/";

        console.group("SEARCH GROUP - START");

        console.group("REQUEST INFO");
        console.log("URL:", url);
        console.log("Method:", "GET");
        console.log("Keyword:", keyword);
        console.log("Offset:", offset);
        console.groupEnd();

        $.ajax({
            url: url,
            type: "GET",
            dataType: "json",
            data: {
                keyword: keyword,
                offset: offset,
                limit: 5
            },

            success: function(res) {
                console.group("RESPONSE");
                console.log("SUCCESS:", res);
                console.groupEnd();

                const container = $("#group-result-list");
                container.empty();

                $("#page-number").text(offset);

                if (res.status !== "success" || res.data.length === 0) {
                    console.warn("Tidak ada group ditemukan.");
                    container.html("<p>Tidak ada group ditemukan.</p>");
                    return;
                }

                res.data.forEach(g => {

                    console.group("RENDER GROUP CARD");
                    console.log("Group:", g);
                    console.groupEnd();

                    const card = `
                    <div class="card"
                         style="margin-top:10px; padding:15px; max-width:450px;">

                        <h3>${g.nama}</h3>
                        <p>Jenis: ${g.jenis}</p>
                        <p>${g.deskripsi}</p>

                        <button class="btn-join-group"
                                data-id="${g.id}"
                                style="margin-top:10px;">
                            Join Group
                        </button>
                    </div>
                `;

                    container.append(card);
                });

                // CLICK JOIN GROUP
                $(".btn-join-group").on("click", function() {
                    const gid = $(this).data("id");
                    selectedGroupId = gid;

                    console.group("JOIN GROUP CLICKED");
                    console.log("Selected Group ID:", selectedGroupId);
                    console.groupEnd();

                    $("#kode-popup").css("display", "flex");
                });


            },

            error: function(xhr) {
                console.group("ERROR");
                console.error(xhr);
                console.groupEnd();

                $("#status-message").text("Gagal memuat data group.");
            },

            complete: function() {
                console.groupEnd();
            }
        });

    }


    // ====================================================================
    // JOIN GROUP - POST /JOIN/{idgroup}/
    // ====================================================================
    function joinGroup(idgroup, kode) {

        const url = API_ADDRESS + "JOIN/" + idgroup + "/";

        const payload = {
            kode: kode
        };

        console.group("JOIN GROUP - START");

        console.group("REQUEST INFO");
        console.log("URL:", url);
        console.log("Method:", "POST");
        console.log("Payload:", payload);
        console.groupEnd();

        $.ajax({
            url: url,
            type: "POST",
            data: payload,
            dataType: "json",

            success: function(res) {

                console.group("RESPONSE");
                console.log("SUCCESS:", res);
                console.groupEnd();

                if (res.status === "success") {
                    console.log("JOIN SUCCESS - CODE VALID");
                    alert("Berhasil bergabung ke group!");
                    $("#kode-popup").hide();
                    $("#kode-input").val("");
                } else {
                    console.warn("JOIN FAILED:", res.message);
                    alert("Gagal join: " + res.message);
                }
            },

            error: function(xhr) {
                console.group("ERROR");
                console.error("Join group error:", xhr);
                console.groupEnd();

                alert(xhr.responseJSON?.message || "Gagal join group.");
            },

            complete: function() {
                console.groupEnd();
            }
        });
    }
</script>

</html>