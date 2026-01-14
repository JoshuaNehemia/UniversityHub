<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard | University Hub</title>
    <meta name="description" content="University Hub Dashboard.">

    <link rel="stylesheet" href="STYLES/root.css">
    <link rel="stylesheet" href="STYLES/main.css">
    <link rel="stylesheet" href="STYLES/form.css">
    <link rel="stylesheet" href="STYLES/mobile-fix.css">

    <script src="SCRIPTS/jquery_3_7_1.js"></script>
    <script src="SCRIPTS/config.js"></script>
    <script src="SCRIPTS/auth.js"></script>
</head>
<style>
    /* ===============================
   RESPONSIVE OVERRIDES (INTERNAL)
   No design change, size only
================================ */

    /* ---------- Tablet & below ---------- */
    @media (max-width: 992px) {

        /* Layout stack */
        .dashboard-wrapper {
            flex-direction: column;
        }

        /* Sidebar becomes horizontal */
        .sidebar {
            width: 100%;
            position: static;
            border-right: none;
            border-bottom: 1px solid var(--border-color);
        }

        .sidebar-nav {
            flex-direction: row;
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }

        .nav-link {
            white-space: nowrap;
            padding: 12px 20px;
            font-size: 0.9rem;
            border-right: none;
            border-bottom: 3px solid transparent;
        }

        .nav-link.active {
            border-bottom-color: var(--first-color);
        }

        /* Main content */
        .main-content {
            margin-left: 0;
            padding: 16px;
        }

        /* Cards: 2 columns */
        .group-card {
            width: calc(50% - 16px);
            min-width: 0;
        }

        .grid-container {
            gap: 16px;
        }

        .dashboard-footer {
            margin-left: 0 !important;
            width: 100%;
            text-align: center;
            padding: 16px;
        }
    }

    /* ---------- Mobile ---------- */
    @media (max-width: 768px) {

        /* Header */
        .top-bar {
            height: 50px;
            padding: 0 12px;
        }

        .top-bar .brand h1 {
            font-size: 1rem;
        }

        .top-bar .user-menu span {
            display: none;
            /* hide "Halo, User" */
        }

        /* Buttons */
        .btn,
        .btn-outline,
        .btn-primary {
            width: 100%;
            font-size: 0.9rem;
            padding: 10px 14px;
        }

        /* Cards: full width */
        .group-card {
            width: 100%;
        }

        .group-card-title {
            font-size: 1.1rem;
        }

        .group-card-desc {
            font-size: 0.9rem;
        }

        /* Content header */
        .content-header h2 {
            font-size: 1.4rem;
        }
    }

    /* ---------- Small phones ---------- */
    @media (max-width: 480px) {

        .main-content {
            padding: 10px;
        }

        .group-card-header {
            height: 100px;
        }

        .nav-link {
            padding: 10px 14px;
            font-size: 0.85rem;
        }

        .dashboard-footer {
            font-size: 0.75rem;
            padding: 12px;
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
                <a href="index.php" class="nav-link active">Group Saya</a>
                <a href="group.php" class="nav-link">Cari Group</a>
                <a href="profil.php" class="nav-link">Profil Akun</a>
            </nav>
        </aside>

        <main class="main-content">
            <div class="content-header">
                <h2>Daftar Group</h2>
                <p class="text-muted">Group yang Anda ikuti.</p>
            </div>

            <section class="dosen_only" style="display:none;">
                <button onclick="window.location.href='add-group.php'" class="btn btn-primary" style="margin-top:15px;">
                    + Buat Group Baru
                </button>
            </section>

            <section id="daftar-group" class="grid-container">
                <p class="text-muted">Memuat data...</p>
            </section>

            <div id="loading-state" class="text-muted" style="margin-top:20px; display:none;">Memuat data...</div>

        </main>
    </div>

    <footer class="dashboard-footer">
        © 2025 UniversityHub — Projek Mata Kuliah Full Stack Programming Universitas Surabaya.
    </footer>

</body>

<script>
    $(document).ready(function () {
        $.ajax({
            url: API_ADDRESS + "AUTH/",
            type: "GET",
            data: { jenis: "account" },
            dataType: "json",
            success: function (res) {
                if (res.status === "success") {
                    window.SESSION = res.data;
                    $("#display-name").text(res.data.nama);

                    if (res.data.jenis === "DOSEN") {
                        $(".dosen_only").show();
                    } else {
                        $(".dosen_only").hide();
                    }

                    getGroupJoinedByUser();
                } else {
                    window.location.href = 'login.php';
                }
            },
            error: function () { window.location.href = 'login.php'; }
        });

        window.logout = function () {
            if (confirm("Keluar dari aplikasi?")) {
                $.ajax({
                    url: API_ADDRESS + "AUTH/", type: "DELETE",
                    success: function () { window.location.href = 'login.php'; }
                });
            }
        };
    });

    function getGroupJoinedByUser() {
        $("#loading-state").show();
        let baseUrl = API_ADDRESS;
        if (baseUrl.endsWith("/")) baseUrl = baseUrl.slice(0, -1);

        $.ajax({
            url: baseUrl + "/GROUP/",
            type: "GET",
            data: {
                mine: 'true',
                limit: 100,
                page: 0
            },
            dataType: "json",
            success: function (res) {
                $("#loading-state").hide();
                const container = $("#daftar-group");
                container.empty();

                if (res.status !== "success" || !res.data || res.data.length === 0) {
                    container.html(`<div style="width:100%; text-align:center; padding:30px;">
                        <img src="IMAGES/warning.svg" style="width:50px; opacity:0.5; margin-bottom:10px;">
                        <p class="text-muted">Anda belum bergabung dengan group manapun.</p>
                        <a href="group.php" class="btn btn-outline" style="margin-top:10px;">Cari Group</a>
                    </div>`);
                    return;
                }

                res.data.forEach(g => {
                    const badgeColor = g.jenis === 'Privat' ? 'var(--status-waiting-bg)' : 'var(--status-success-bg)';
                    const badgeText = g.jenis === 'Privat' ? 'var(--status-waiting)' : 'var(--status-success)';

                    const realID = g.id || g.idgrup;

                    const html = `
                        <div class="group-card" onclick="window.location.href='detail-group.php?id=${realID}'" style="cursor:pointer;">
                            <div class="group-card-header" style="background-image: url('IMAGES/ubaya.jpg'); position:relative;">
                                    <span style="position:absolute; top:10px; right:10px; padding:4px 10px; border-radius:15px; font-size:0.75rem; font-weight:bold; background-color:${badgeColor}; color:${badgeText};">
                                    ${g.jenis}
                                    </span>
                            </div>
                            <div class="group-card-body">
                                <h3 class="group-card-title">${g.nama}</h3>
                                <p class="group-card-desc">${g.deskripsi || 'Tidak ada deskripsi.'}</p>
                                <small class="text-muted" style="margin-top:auto;">Dibuat: ${g.tanggal_dibuat || '-'}</small>
                            </div>
                        </div>
                    `;
                    container.append(html);
                });
            },
            error: function (xhr) {
                console.error(xhr);
                $("#loading-state").hide();
                $("#daftar-group").html(`<p style='color:red; text-align:center;'>Gagal memuat data group.</p>`);
            }
        });
    }
</script>

</html>