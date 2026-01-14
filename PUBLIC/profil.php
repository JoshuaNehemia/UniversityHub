<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Akun | University Hub</title>

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
       RWD FIX — PROFILE PAGE (FIXED NAVBAR)
       ===================================================== */

    /* 1. Global Reset & Safety
       ----------------------------------------------------- */
    * {
        box-sizing: border-box;
    }

    html,
    body {
        max-width: 100%;
        overflow-x: hidden;
        margin: 0;
        font-size: 16px;
    }

    .dashboard-wrapper {
        width: 100%;
        max-width: 100%;
        overflow-x: hidden;
        padding-top: 0;
    }

    /* =====================================================
       2. SIDEBAR & NAVIGATION (CRITICAL FIX)
       ----------------------------------------------------- */

    /* Desktop Default */
    .sidebar {
        display: block;
        /* Ensure it's visible on desktop by default */
    }

    /* Mobile/Tablet Breakpoint (increased to 991px to cover all tablets) */
    @media (max-width: 991px) {

        /* Force dashboard to stack vertically */
        .dashboard-wrapper {
            display: flex;
            flex-direction: column;
        }

        /* FIX: Force the sidebar to appear. 
           We use !important to override any external CSS that might set display:none 
        */
        .sidebar {
            display: block !important;
            width: 100% !important;
            height: auto !important;
            position: relative !important;
            /* Unstick it if it was fixed */
            top: auto !important;
            left: auto !important;
            border-right: none !important;
            border-bottom: 1px solid var(--border-color, #ccc);
            padding: 0 !important;
            background: var(--surface-color, #fff);
            z-index: 100;
            /* Ensure it sits on top of content */
            margin-bottom: 20px;
        }

        /* Transform the vertical list into a horizontal row */
        .sidebar-nav {
            display: flex !important;
            flex-direction: row;
            justify-content: space-between;
            align-items: stretch;
            width: 100%;
            margin: 0;
            padding: 0;
            overflow-x: auto;
            /* Allow scroll on very narrow screens */
        }

        /* Link Styling for Mobile Nav */
        .sidebar-nav .nav-link,
        .sidebar-nav .nav-link[style] {
            flex: 1;
            /* Distribute width equally */
            padding: 12px 5px !important;
            /* Override HTML inline styles */
            text-align: center;
            border-left: none !important;
            /* Remove desktop active border */
            border-bottom: 3px solid transparent;
            /* Add mobile active border indicator */
            font-size: 0.9rem;
            display: flex;
            justify-content: center;
            align-items: center;
            white-space: nowrap;
            color: var(--text-color, #333);
            /* Ensure text is visible */
        }

        /* Active State for Mobile */
        .sidebar-nav .nav-link.active {
            background: transparent !important;
            border-bottom-color: var(--first-color, #007bff);
            color: var(--first-color, #007bff);
            font-weight: 600;
        }
    }

    /* Small Phone Adjustments */
    @media (max-width: 480px) {
        .sidebar-nav .nav-link {
            font-size: 0.8rem;
            padding: 10px 4px !important;
        }
    }

    /* =====================================================
       3. PROFILE CARD LAYOUT
       ----------------------------------------------------- */

    .card,
    .card[style] {
        display: flex !important;
        flex-direction: row;
        flex-wrap: wrap;
        width: 100% !important;
        max-width: 100% !important;
        gap: 0 !important;
    }

    /* LEFT SIDE: Profile Photo */
    .card>div:first-child {
        flex: 0 0 250px;
        border-right: 1px solid var(--border-color, #eee);
        padding: 30px 20px;
    }

    /* RIGHT SIDE: Form */
    .card>div:last-child {
        flex: 1;
        padding: 30px;
        min-width: 300px;
    }

    /* Stack on Mobile/Tablet */
    @media (max-width: 991px) {

        .card,
        .card[style] {
            flex-direction: column !important;
        }

        .card>div:first-child {
            width: 100%;
            flex: none;
            border-right: none !important;
            border-bottom: 1px solid var(--border-color, #eee);
            padding: 20px;
        }

        .card>div:last-child {
            width: 100%;
            flex: none;
            padding: 20px;
            min-width: 0;
        }

        .card[style*="gap"] {
            gap: 0 !important;
        }
    }

    /* =====================================================
       4. FORM ELEMENTS
       ----------------------------------------------------- */

    .form-control {
        width: 100%;
    }

    /* Fix Mahasiswa/Dosen flex blocks */
    #mahasiswa-block>div[style],
    #dosen-block>div[style] {
        display: flex !important;
        flex-wrap: wrap !important;
        gap: 20px !important;
    }

    #mahasiswa-block>div>div,
    #dosen-block>div>div {
        flex: 1 1 45%;
        min-width: 200px;
    }

    @media (max-width: 480px) {
        #mahasiswa-block>div[style] {
            gap: 15px !important;
        }

        #mahasiswa-block>div>div {
            flex: 1 1 100% !important;
            width: 100% !important;
        }
    }

    /* =====================================================
       5. VISUALS & UTILITIES
       ----------------------------------------------------- */

    #profile-photo {
        width: 150px;
        height: 150px;
        max-width: 100%;
        object-fit: cover;
    }

    @media (max-width: 480px) {
        #profile-photo {
            width: 120px;
            height: 120px;
        }

        h2 {
            font-size: 1.5rem;
        }

        h3 {
            font-size: 1.1rem;
        }
    }

    /* Touch Targets */
    @media (pointer: coarse) {

        button,
        .btn,
        .nav-link,
        input {
            min-height: 44px;
        }
    }

    /* =====================================================
   🔥 HARD RWD NAVBAR OVERRIDE — FINAL FIX
   ===================================================== */

    /* ====== MOBILE & TABLET ====== */
    @media (max-width: 991px) {

        /* Kill all sidebar legacy behavior */
        .sidebar {
            position: static !important;
            width: 100% !important;
            height: auto !important;
            margin: 0 !important;
            padding: 0 !important;
            border-right: none !important;
            border-bottom: 1px solid var(--border-color, #ddd);
            background: var(--surface-color, #fff);
            overflow: hidden;
        }

        /* NAV CONTAINER */
        .sidebar-nav {
            display: flex !important;
            flex-direction: row !important;
            align-items: stretch !important;
            justify-content: space-between !important;
            width: 100% !important;
            margin: 0 !important;
            padding: 0 !important;
            list-style: none;
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }

        /* LINKS — HARD RESET */
        .sidebar-nav .nav-link {
            all: unset;
            /* 💣 nukes inline + external styles */
            box-sizing: border-box;

            flex: 1 0 auto;
            min-width: 110px;

            display: flex;
            align-items: center;
            justify-content: center;

            padding: 12px 8px !important;
            text-align: center;
            font-size: 0.85rem;
            font-weight: 500;

            color: var(--text-color, #333);
            cursor: pointer;

            border-bottom: 3px solid transparent;
            white-space: nowrap;
        }

        /* ACTIVE STATE */
        .sidebar-nav .nav-link.active {
            color: var(--first-color, #007bff);
            border-bottom-color: var(--first-color, #007bff);
            font-weight: 600;
        }

        /* PREVENT MAIN CONTENT PUSH */
        .main-content {
            width: 100% !important;
            margin-left: 0 !important;
            padding-left: 0 !important;
        }
    }

    /* ====== SMALL PHONES ====== */
    @media (max-width: 480px) {
        .sidebar-nav .nav-link {
            font-size: 0.75rem;
            padding: 10px 6px !important;
            min-width: 90px;
        }
    }

    /* =====================================================
   🚨 NUCLEAR RWD NAVBAR FORCE-SHOW FIX
   ===================================================== */

    /* ---- GLOBAL SAFETY ---- */
    @media (max-width: 991px) {

        /* FORCE SIDEBAR TO EXIST */
        aside.sidebar {
            display: block !important;
            visibility: visible !important;
            opacity: 1 !important;

            position: relative !important;
            transform: none !important;
            left: 0 !important;
            right: 0 !important;
            top: 0 !important;

            width: 100vw !important;
            height: auto !important;
            min-height: unset !important;

            margin: 0 !important;
            padding: 0 !important;

            background: var(--surface-color, #fff) !important;
            border: none !important;
            border-bottom: 1px solid #ddd !important;

            z-index: 9999 !important;
        }

        /* FORCE NAV CONTAINER */
        aside.sidebar nav.sidebar-nav {
            display: flex !important;
            flex-direction: row !important;
            justify-content: space-around !important;
            align-items: center !important;

            width: 100% !important;
            margin: 0 !important;
            padding: 0 !important;

            overflow-x: auto !important;
            white-space: nowrap !important;
        }

        /* FORCE LINKS (DESTROY INLINE STYLES) */
        aside.sidebar nav.sidebar-nav a.nav-link {
            all: unset !important;
            box-sizing: border-box !important;

            display: flex !important;
            align-items: center !important;
            justify-content: center !important;

            flex: 1 0 auto !important;
            min-width: 100px !important;

            padding: 14px 8px !important;
            text-align: center !important;

            font-size: 0.85rem !important;
            font-weight: 500 !important;

            color: var(--text-color, #333) !important;
            cursor: pointer !important;

            border-bottom: 3px solid transparent !important;
        }

        /* ACTIVE TAB */
        aside.sidebar nav.sidebar-nav a.nav-link.active {
            color: var(--first-color, #007bff) !important;
            border-bottom-color: var(--first-color, #007bff) !important;
            font-weight: 600 !important;
        }

        /* PREVENT CONTENT SHIFT */
        .main-content {
            margin: 0 !important;
            padding-left: 0 !important;
            width: 100% !important;
        }
    }

    /* EXTRA SMALL DEVICES */
    @media (max-width: 480px) {
        aside.sidebar nav.sidebar-nav a.nav-link {
            font-size: 0.75rem !important;
            padding: 12px 6px !important;
            min-width: 90px !important;
        }
    }
/* =====================================================
   ✅ RWD ORDER FIX — ASIDE UNDER HEADER
   ===================================================== */
@media (max-width: 991px) {

    body {
        display: flex;
        flex-direction: column;
    }

    header.top-bar {
        position: relative !important;
        width: 100%;
        z-index: 10000;
    }

    .dashboard-wrapper {
        display: flex !important;
        flex-direction: column !important;
        width: 100%;
    }

    /* FORCE ASIDE TO BE FIRST ELEMENT UNDER HEADER */
    .dashboard-wrapper > aside.sidebar {
        order: 0 !important;
        margin: 0 !important;
    }

    .dashboard-wrapper > main.main-content {
        order: 1 !important;
        margin-top: 0 !important;
    }
}

    
</style>

<body class="login-page">

    <header class="top-bar">
        <div class="brand">
            <h1>University Hub</h1>
        </div>
        <div class="user-menu">
            <button id="themeToggle" onclick="toggleTheme()" class="btn-theme" title="Ganti Tema">🌙</button>
            <span>Halo, <strong id="display-name-header">User</strong></span>
            <button onclick="logout()" class="btn-logout">Keluar</button>
        </div>
    </header>

    <div class="dashboard-wrapper">

        <aside class="sidebar">
            <nav class="sidebar-nav">
                <a href="index.php" class="nav-link" style="padding-left: 50px;">Group Saya</a>
                <a href="group.php" class="nav-link" style="padding-left: 50px;">Cari Group</a>
                <a href="profil.php" class="nav-link active" style="padding-left: 50px;">Profil Akun</a>
            </nav>
        </aside>

        <main class="main-content">

            <div class="content-header">
                <h2>Profil Saya</h2>
                <p class="text-muted">Kelola informasi akun dan data diri Anda.</p>
            </div>

            <div class="card" style="max-width: 800px; display: flex; flex-wrap: wrap; gap: 30px;">

                <div
                    style="flex: 1; min-width: 250px; text-align: center; border-right: 1px solid var(--border-color);">
                    <div style="margin-bottom: 20px;">
                        <img id="profile-photo" src="IMAGES/default_profile_picture.svg" alt="Foto Profil"
                            style="width: 150px; height: 150px; object-fit: cover; border-radius: 50%; border: 4px solid var(--surface-color); box-shadow: var(--shadow-md);">
                    </div>

                    <h3 id="display-nama" style="margin-bottom: 5px;">Memuat...</h3>
                    <span id="display-role" class="status-badge"
                        style="background-color: var(--first-color); color: white;">ROLE</span>

                    <div style="margin-top: 20px;">
                        <button class="btn btn-outline" style="font-size: 0.8rem;" disabled>Ubah Foto (Coming
                            Soon)</button>
                    </div>
                </div>

                <div style="flex: 2; min-width: 300px;">
                    <h3
                        style="margin-bottom: 20px; color: var(--text-secondary); font-size: 1.1rem; border-bottom: 1px solid var(--border-color); padding-bottom: 10px;">
                        Informasi Dasar</h3>

                    <form>
                        <div class="form-group">
                            <label class="form-label">Username</label>
                            <input type="text" id="username" class="form-control" readonly value="...">
                        </div>

                        <div class="form-group">
                            <label class="form-label">Nama Lengkap</label>
                            <input type="text" id="nama" class="form-control" readonly value="...">
                        </div>

                        <div id="mahasiswa-block" style="display:none;">
                            <div class="form-group">
                                <label class="form-label">NRP</label>
                                <input type="text" id="nrp" class="form-control" readonly>
                            </div>
                            <div style="display: flex; gap: 20px;">
                                <div class="form-group" style="flex:1;">
                                    <label class="form-label">Gender</label>
                                    <input type="text" id="gender" class="form-control" readonly>
                                </div>
                                <div class="form-group" style="flex:1;">
                                    <label class="form-label">Angkatan</label>
                                    <input type="text" id="angkatan" class="form-control" readonly>
                                </div>
                            </div>
                        </div>

                        <div id="dosen-block" style="display:none;">
                            <div class="form-group">
                                <label class="form-label">NPK</label>
                                <input type="text" id="npk" class="form-control" readonly>
                            </div>
                        </div>

                    </form>
                </div>

            </div>

            <div id="status-message" style="margin-top:20px; text-align: center;"></div>

        </main>
    </div>

</body>

<script>
    $(document).ready(function () {
        window.logout = function () {
            alert("Anda telah keluar.");
            window.location.href = 'login.php';
        };

        checkLoggedIn();
        loadProfile();

    });


    function loadProfile() {

        const url = API_ADDRESS + "AUTH/";

        $("#display-nama").text("Memuat data...");

        $.ajax({
            url: url,
            type: "GET",
            data: { jenis: "account" },
            dataType: "json",

            success: function (res) {

                if (res.status !== "success") {
                    $("#status-message").html(`<div class="alert alert-danger">${res.message}</div>`);
                    return;
                }

                const d = res.data;

                $("#display-name-header").text(d.nama);
                $("#display-nama").text(d.nama);
                $("#display-role").text(d.jenis);

                $("#username").val(d.username);
                $("#nama").val(d.nama);

                if (d.jenis === "MAHASISWA") {
                    $("#mahasiswa-block").show();
                    $("#nrp").val(d.nrp);
                    $("#gender").val(d.gender);
                    $("#angkatan").val(d.angkatan);

                    const fotoUrl = `../APP/DATABASE/PROFILE/MAHASISWA/${d.nrp}.${d.foto_extention}`;
                    setProfileImage(fotoUrl);
                }

                if (d.jenis === "DOSEN") {
                    $("#dosen-block").show();
                    $("#npk").val(d.npk);

                    const fotoUrl = `../APP/DATABASE/PROFILE/DOSEN/${d.npk}.${d.foto_extention}`;
                    setProfileImage(fotoUrl);
                }
            },

            error: function (xhr) {
                console.error(xhr);
                $("#status-message").html(`<div class="alert alert-danger">Gagal memuat profil. Server Error.</div>`);
                $("#display-nama").text("Error");
            }
        });
    }

    function setProfileImage(url) {
        const img = $("#profile-photo");
        img.attr("src", url);

        img.on("error", function () {
            $(this).attr("src", "IMAGES/default_profile_picture.svg");
        });
    }
</script>

</html>