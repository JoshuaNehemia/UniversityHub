<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cari Group | University Hub</title>

    <link rel="stylesheet" href="STYLES/root.css">
    <link rel="stylesheet" href="STYLES/main.css">
    <link rel="stylesheet" href="STYLES/form.css">
    <link rel="stylesheet" href="STYLES/mobile-fix.css">

    <script src="SCRIPTS/jquery_3_7_1.js"></script>
    <script src="SCRIPTS/config.js"></script>
    <script src="SCRIPTS/auth.js"></script>
</head>
<style>
/* =====================================================
   INTERNAL RWD OVERRIDES — SIZE & FLOW ONLY
   No color, no design, no JS dependency
===================================================== */

/* =====================
   TABLET & BELOW
===================== */
@media (max-width: 992px) {

    /* Layout becomes vertical */
    .dashboard-wrapper {
        display: flex;
        flex-direction: column;
    }

    /* Sidebar becomes top navigation */
    .sidebar {
        width: 100%;
        position: static;
        border-right: none;
        border-bottom: 1px solid var(--border-color);
    }

    .sidebar-nav {
        display: flex;
        flex-direction: row;
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
    }

    .nav-link {
        padding: 12px 20px !important;
        white-space: nowrap;
        border-right: none;
        border-bottom: 3px solid transparent;
    }

    .nav-link.active {
        border-bottom-color: var(--first-color);
    }

    /* Main content reset */
    .main-content {
        margin-left: 0;
        width: 100%;
        padding: 16px;
    }

    /* Search bar stack */
    .form-group[style*="display:flex"] {
        display: flex !important;
        flex-direction: column;
        gap: 10px;
    }

    .form-group input,
    .form-group button {
        width: 100%;
    }

    /* Grid: 2 columns */
    .group-card {
        width: calc(50% - 16px);
        max-width: 100%;
        min-width: 0;
    }
}

/* =====================
   MOBILE
===================== */
@media (max-width: 768px) {

    /* Header resize */
    .top-bar {
        height: 50px;
        padding: 0 12px;
    }

    .top-bar .user-menu span {
        display: none;
    }

    /* Grid: 1 column */
    .group-card {
        width: 100%;
    }

    /* 🔒 CARD STRUCTURE RESET (CRITICAL) */
    .group-card {
        display: flex;
        flex-direction: column;
        height: 100%;
    }

    .group-card-body {
        display: flex;
        flex-direction: column;
        flex: 1 1 auto;
    }

    /* 🔑 MEMBER / JOIN / OWNER ALWAYS VISIBLE */
    .group-card-action {
        display: flex !important;
        flex-direction: column;
        gap: 6px;
        margin-top: auto !important;
        padding-top: 10px;
    }

    .group-card-action > * {
        display: block !important;
        width: 100% !important;
        visibility: visible !important;
        opacity: 1 !important;
    }

    /* Buttons sizing */
    .group-card-action .btn {
        padding: 10px;
        font-size: 0.9rem;
    }

    /* Pagination stack */
    #pagination {
        display: flex;
        flex-direction: column;
        gap: 10px;
        align-items: stretch;
    }

    #pagination button {
        width: 100%;
    }
}

/* =====================
   SMALL PHONES
===================== */
@media (max-width: 480px) {

    .main-content {
        padding: 10px;
    }

    .group-card-header {
        height: 100px;
    }

    .group-card-title {
        font-size: 1.05rem;
    }

    .group-card-desc {
        font-size: 0.9rem;
    }
}
</style>

<body>

    <header class="top-bar">
        <div class="brand">
            <h1>University Hub</h1>
        </div>
        <div class="user-menu">
            <button id="themeToggle" onclick="toggleTheme()" class="btn-theme" title="Ganti Tema">🌙</button>
            <span>Halo, <strong id="display-name">User</strong></span>
            <button onclick="logout()" class="btn-logout">Keluar</button>
        </div>
    </header>

    <div class="dashboard-wrapper">

        <aside class="sidebar">
            <nav class="sidebar-nav">
                <a href="index.php" class="nav-link" style="padding-left: 50px;">Group Saya</a>
                <a href="group.php" class="nav-link active" style="padding-left: 50px;">Cari Group</a>
                <a href="profil.php" class="nav-link" style="padding-left: 50px;">Profil Akun</a>
            </nav>
        </aside>

        <main class="main-content">

            <div class="content-header">
                <h2>Pencarian Group</h2>
                <p class="text-muted">Temukan dan bergabung dengan komunitas baru.</p>
            </div>

            <div class="card" style="margin-bottom: 30px;">
                <div class="form-group" style="margin-bottom:0; display:flex; gap:10px;">
                    <input type="text" id="search-keyword" class="form-control" placeholder="Masukkan nama atau topik group..." style="flex:1;">
                    <button id="btn-search-group" class="btn btn-primary">
                        Cari
                    </button>
                </div>
            </div>

            <div id="group-result-list" class="grid-container"></div>

            <div id="status-message" class="mt-4 text-center text-muted"></div>

            <div id="pagination" style="margin-top:40px; display:none; justify-content:center; align-items:center; gap:15px;">
                <button id="page-prev" class="btn btn-outline">Previous</button>
                <span style="font-weight:bold; color:var(--text-secondary);">Halaman <span id="page-number">0</span></span>
                <button id="page-next" class="btn btn-outline">Next</button>
            </div>

        </main>

    </div>


    <div id="kode-popup"
        style="display:none; position:fixed; top:0; left:0; width:100%; height:100%;
            background:rgba(0,0,0,0.5); z-index:var(--z-modal); justify-content:center; align-items:center;">

        <div class="card" style="width:100%; max-width:400px; animation: fadeIn 0.3s;">
            <h3 style="margin-bottom:20px;">🔒 Masukkan Kode Akses</h3>
            <p class="text-muted" style="font-size:0.9rem;">Silakan masukkan kode pendaftaran untuk bergabung ke group ini.</p>

            <div class="form-group">
                <label class="form-label">Kode Pendaftaran</label>
                <input type="text" id="kode-input" class="form-control" placeholder="Contoh: A1B2C3">
            </div>

            <div style="display:flex; justify-content:flex-end; gap:10px; margin-top:20px;">
                <button id="btn-close-popup" class="btn btn-outline">Batal</button>
                <button id="btn-submit-kode" class="btn btn-primary">Gabung Sekarang</button>
            </div>
        </div>

    </div>

</body>

<script>
    var currentUser = "";
    var joinedGroupIds = []; 

    $(document).ready(function() {

        window.logout = function() {
            if(confirm("Apakah Anda yakin ingin keluar?")) {
                $.ajax({
                    url: API_ADDRESS + "AUTH/",
                    type: "DELETE",
                    success: function() {
                        window.location.href = 'login.php'; 
                    }
                });
            }
        };

        initPage();

        let offset = 0;
        window.selectedGroupId = null;

        $("#btn-search-group").on("click", function() {
            offset = 0;
            searchGroup($("#search-keyword").val(), offset);
        });

        $("#page-prev").on("click", function() {
            if (offset > 0) {
                offset--;
                searchGroup($("#search-keyword").val(), offset);
            }
        });

        $("#page-next").on("click", function() {
            offset++;
            searchGroup($("#search-keyword").val(), offset);
        });

        $("#btn-close-popup").on("click", function() {
            $("#kode-popup").fadeOut(200);
            $("#kode-input").val("");
            window.selectedGroupId = null;
        });

        $("#btn-submit-kode").on("click", function() {
            const kode = $("#kode-input").val().trim();
            if (!window.selectedGroupId) { alert("Error: Group ID hilang."); return; }
            if (kode.length === 0) { alert("Kode wajib diisi!"); return; }
            joinGroup(window.selectedGroupId, kode);
        });
    });


    function initPage() {
        $.ajax({
            url: API_ADDRESS + "AUTH/",
            type: "GET",
            data: { jenis: "account" },
            dataType: "json",
            success: function(res) {
                if (res.status === "success") {
                    currentUser = res.data.username;
                    $("#display-name").text(res.data.nama);
                    
                    window.SESSION = res.data;

                    fetchJoinedGroups().then(function() {
                        searchGroup("", 0);
                    });

                } else {
                    window.location.href = "login.php";
                }
            },
            error: function() {
                window.location.href = "login.php";
            }
        });
    }


    function fetchJoinedGroups() {
        return $.ajax({
            url: API_ADDRESS + "AUTH/",
            type: "GET",
            data: {
                jenis: "group",
                limit: 1000, 
                offset: 0
            },
            dataType: "json",
            success: function(res) {
                if (res.status === "success" && res.data) {
                    joinedGroupIds = res.data.map(g => g.id || g.idgrup);
                }
            }
        });
    }


    function searchGroup(keyword, offsetInput) {
        let baseUrl = API_ADDRESS;
        if(baseUrl.endsWith("/")) baseUrl = baseUrl.slice(0, -1);
        const url = baseUrl + "/GROUP/";
        
        const limit = 6; 

        $("#status-message").html("Sedang memuat data...");
        $("#group-result-list").css("opacity", "0.5");

        $.ajax({
            url: url,
            type: "GET",
            dataType: "json",
            data: {
                name: keyword, 
                page: offsetInput, 
                limit: limit
            },
            success: function(res) {
                const container = $("#group-result-list");
                container.empty();
                $("#status-message").empty();
                $("#group-result-list").css("opacity", "1");

                $("#page-number").text(offsetInput + 1);

                if (res.data && (res.data.length > 0 || offsetInput > 0)) {
                    $("#pagination").css("display", "flex");
                } else {
                    $("#pagination").hide();
                }

                if (res.status !== "success" || !res.data || res.data.length === 0) {
                    container.html("");
                    let pesan = res.message || "Tidak ada group yang ditemukan.";
                    
                    if(offsetInput === 0) {
                        $("#status-message").html(`
                            <div style="text-align:center; padding:40px;">
                                <img src="IMAGES/warning.svg" style="width:60px; opacity:0.5; margin-bottom:15px;">
                                <p>${pesan}</p>
                            </div>
                        `);
                    } else {
                        $("#status-message").text("Akhir dari daftar group.");
                    }
                    
                    $("#page-next").prop('disabled', true);
                    return;
                } 

                if(res.data.length < limit) {
                    $("#page-next").prop('disabled', true);
                } else {
                    $("#page-next").prop('disabled', false);
                }


                res.data.forEach(g => {
                    const groupID = g.id || g.idgrup; 

                    let badgeClass = g.jenis === 'Privat' ? 
                        'background-color:var(--status-waiting-bg); color:var(--status-waiting);' : 
                        'background-color:var(--status-success-bg); color:var(--status-success);';

                    let actionButton = "";
                    
                    if (g.pembuat === currentUser) {
                        actionButton = `
                            <span style="display:block; text-align:center; color:var(--text-secondary); font-size:0.85rem; padding:10px; background:#f5f5f5; border-radius:4px; border:1px solid #eee;">
                                 Milik Anda
                            </span>
                        `;
                    } 
                    else if (joinedGroupIds.includes(groupID)) {
                        actionButton = `
                            <span style="display:block; text-align:center; color:var(--status-success); font-size:0.85rem; padding:10px; background:var(--status-success-bg); border-radius:4px; font-weight:bold;">
                                ✓ Telah Bergabung
                            </span>
                        `;
                    } 
                    else {
                        actionButton = `
                            <button class="btn btn-primary btn-join-group" 
                                    style="width:100%; padding:8px;"
                                    data-id="${groupID}">
                                Gabung Group
                            </button>
                        `;
                    }

                    const card = `
                    <div class="group-card">
                        <div class="group-card-header" style="background-image: url('IMAGES/ubaya.jpg'); position:relative;">
                             <span style="position:absolute; top:10px; right:10px; padding:4px 10px; border-radius:15px; font-size:0.75rem; font-weight:bold; ${badgeClass}">
                                ${g.jenis}
                             </span>
                        </div>
                        
                        <div class="group-card-body">
                            <h3 class="group-card-title">
                                <a href="detail-group.php?id=${groupID}" style="text-decoration:none; color:inherit;">
                                    ${g.nama}
                                </a>
                            </h3>
                            <p class="group-card-desc">${g.deskripsi || 'Tidak ada deskripsi group.'}</p>
                            
                            <div class="group-card-action" style="display:flex; flex-direction:column; gap:5px;">
                                <a href="detail-group.php?id=${groupID}" class="btn btn-outline" style="text-align:center; padding:8px;">
                                    Lihat Detail
                                </a>
                                ${actionButton}
                            </div>
                        </div>
                    </div>
                `;
                    container.append(card);
                });

                $(".btn-join-group").on("click", function() {
                    const gid = $(this).data("id");
                    window.selectedGroupId = gid; 
                    $("#kode-popup").css("display", "flex").hide().fadeIn(200);
                    $("#kode-input").focus();
                });
            },
            error: function(xhr) {
                $("#status-message").text("Gagal memuat data group. Server Error.");
                $("#group-result-list").css("opacity", "1");
                console.error(xhr);
            }
        });
    }


    function joinGroup(idgroup, kode) {
        let baseUrl = API_ADDRESS;
        if(baseUrl.endsWith("/")) baseUrl = baseUrl.slice(0, -1);
        
        const url = baseUrl + "/JOIN/"; 
        const payload = { 
            idgrup: idgroup, 
            kode: kode,
            username: currentUser 
        };

        $.ajax({
            url: url,
            type: "POST",
            data: payload, 
            dataType: "json",
            success: function(res) {
                if (res.status === "success") {
                    alert("Berhasil bergabung ke group!");
                    $("#kode-popup").fadeOut();
                    $("#kode-input").val("");
                    
                    initPage(); 
                } else {
                    alert("Gagal join: " + (res.message || "Kode salah atau error lain."));
                }
            },
            error: function(xhr) {
                console.error(xhr); 
                let msg = "Terjadi kesalahan koneksi/server.";
                if(xhr.responseJSON && xhr.responseJSON.message) {
                    msg = xhr.responseJSON.message;
                }
                alert(msg);
            }
        });
    }
</script>
</html>