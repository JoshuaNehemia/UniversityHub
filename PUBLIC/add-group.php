<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buat Group Baru | University Hub</title>

    <link rel="stylesheet" href="STYLES/root.css">
    <link rel="stylesheet" href="STYLES/main.css">
    <link rel="stylesheet" href="STYLES/form.css">

    <script src="SCRIPTS/jquery_3_7_1.js"></script>
    <script src="SCRIPTS/config.js"></script>
    <script src="SCRIPTS/auth.js"></script>
</head>
<style>
/* =====================================================
   HARD RWD OVERRIDE — CREATE GROUP PAGE
   Layout & flow only (safe override)
===================================================== */

/* ---------- Global Safety ---------- */
* {
    box-sizing: border-box !important;
}

html, body {
    max-width: 100%;
    overflow-x: hidden;
    margin: 0;
}

/* ---------- Header ---------- */
.top-bar {
    display: flex !important;
    align-items: center;
    justify-content: space-between;
    padding: 12px 16px;
    flex-wrap: wrap;
}

.top-bar .brand h1 {
    font-size: 1.1rem;
    margin: 0;
}

.user-menu {
    display: flex;
    gap: 10px;
    align-items: center;
    flex-wrap: wrap;
}

/* ---------- Main Layout ---------- */
.dashboard-wrapper {
    display: flex !important;
    width: 100%;
    min-height: calc(100vh - 60px);
}

/* ---------- Sidebar ---------- */
.sidebar {
    width: 240px;
    min-width: 240px;
}

.sidebar-nav {
    display: flex;
    flex-direction: column;
}

.sidebar-nav .nav-link {
    padding: 12px 16px !important;
}

/* ---------- Content ---------- */
.main-content {
    flex: 1;
    padding: 20px;
    min-width: 0;
}

.content-header h2 {
    font-size: 1.4rem;
    margin-bottom: 8px;
}

/* ---------- Card & Form ---------- */
.card {
    width: 100% !important;
    max-width: 600px;
}

.form-group {
    margin-bottom: 16px;
}

.form-control {
    width: 100%;
}

/* ---------- Action Buttons ---------- */
form div[style*="justify-content:flex-end"] {
    flex-wrap: wrap;
}

form button {
    min-width: 120px;
}

/* =====================================================
   TABLET & BELOW (≤ 992px)
===================================================== */
@media (max-width: 992px) {

    .dashboard-wrapper {
        flex-direction: column !important;
    }

    .sidebar {
        width: 100% !important;
        min-width: 0;
        order: 1;
    }

    .sidebar-nav {
        flex-direction: row !important;
        overflow-x: auto;
    }

    .sidebar-nav .nav-link {
        white-space: nowrap;
        flex: 1;
        text-align: center;
    }

    .main-content {
        order: 2;
        padding: 16px;
    }

    .card {
        max-width: 100%;
    }
}

/* =====================================================
   MOBILE (≤ 576px)
===================================================== */
@media (max-width: 576px) {

    .top-bar {
        flex-direction: column;
        align-items: flex-start;
        gap: 8px;
    }

    .user-menu {
        width: 100%;
        justify-content: space-between;
    }

    .content-header h2 {
        font-size: 1.2rem;
    }

    .content-header p {
        font-size: 0.9rem;
    }

    /* Stack buttons */
    form div[style*="justify-content:flex-end"] {
        flex-direction: column;
        gap: 10px;
    }

    form button {
        width: 100%;
    }

    #status-message {
        max-width: 100% !important;
    }
}
</style>


<body>

    <header class="top-bar">
        <div class="brand">
            <h1>University Hub</h1>
        </div>
        <div class="user-menu">
            <span>Halo, <strong id="display-name">User</strong></span>
            <button onclick="logout()" class="btn-logout">Keluar</button>
        </div>
    </header>

    <div class="dashboard-wrapper">
        <aside class="sidebar">
            <nav class="sidebar-nav">
                <a href="index.php" class="nav-link active" style="padding-left: 50px;">Group Saya</a>
                <a href="group.php" class="nav-link" style="padding-left: 50px;">Cari Group</a>
                <a href="profil.php" class="nav-link" style="padding-left: 50px;">Profil Akun</a>
            </nav>
        </aside>

        <main class="main-content">
            <div style="margin-bottom: 20px;">
                <a href="index.php"
                    style="display:inline-flex; align-items:center; gap:5px; color:var(--text-secondary);">
                    <span>&larr;</span> Kembali ke Dashboard
                </a>
            </div>

            <div class="content-header">
                <h2>Buat Group Baru</h2>
                <p class="text-muted">Buat komunitas baru. Kode pendaftaran akan dibuat otomatis untuk group Privat.</p>
            </div>

            <div class="card" style="max-width: 600px;">

                <form id="form-add-group">

                    <div class="form-group">
                        <label for="nama" class="form-label">Nama Group <span style="color:red">*</span></label>
                        <input type="text" id="nama" name="nama" class="form-control"
                            placeholder="Contoh: Komunitas Coding 2024" required>
                    </div>

                    <div class="form-group">
                        <label for="deskripsi" class="form-label">Deskripsi Singkat</label>
                        <textarea id="deskripsi" name="deskripsi" class="form-control"
                            placeholder="Jelaskan tujuan group ini..." rows="3"></textarea>
                    </div>

                    <div class="form-group">
                        <label for="jenis" class="form-label">Jenis Group <span style="color:red">*</span></label>
                        <select id="jenis" name="jenis" class="form-control" required>
                            <option value="Publik">Publik (Semua orang bisa bergabung)</option>
                            <option value="Privat">Privat (Butuh kode undangan)</option>
                        </select>
                        <small class="text-muted" id="hint-privat" style="display:none; color:var(--first-color);">
                            * Kode undangan akan dibuat otomatis oleh sistem setelah group dibuat.
                        </small>
                    </div>

                    <div style="margin-top: 30px; display:flex; justify-content:flex-end; gap:10px;">
                        <button type="button" onclick="window.history.back()" class="btn btn-outline">Batal</button>
                        <button type="submit" id="btn-submit" class="btn btn-primary">Buat Group</button>
                    </div>

                </form>

            </div>
            <div id="status-message" style="margin-top:20px; max-width:600px;"></div>
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

        $("#jenis").on("change", function () {
            if ($(this).val() === "Privat") {
                $("#hint-privat").show();
            } else {
                $("#hint-privat").hide();
            }
        });

        $("#form-add-group").on("submit", function (e) {
            e.preventDefault();

            const nama = $("#nama").val().trim();
            const deskripsi = $("#deskripsi").val().trim();
            const jenis = $("#jenis").val();

            if (!nama) {
                alert("Nama group wajib diisi.");
                return;
            }

            const payload = {
                nama: nama,
                deskripsi: deskripsi,
                jenis: jenis
            };

            $("#btn-submit").prop("disabled", true).text("Memproses...");
            $("#status-message").html("");

            $.ajax({
                url: API_ADDRESS + "GROUP/",
                type: "POST",
                data: payload,
                dataType: "json",
                success: function (res) {
                    if (res.status === "success") {
                        $("#status-message").html(`<div class="alert alert-success">Group berhasil dibuat! Mengalihkan...</div>`);
                        setTimeout(function () {
                            window.location.href = "index.php";
                        }, 1500);
                    } else {
                        $("#status-message").html(`<div class="alert alert-danger">${res.message}</div>`);
                        $("#btn-submit").prop("disabled", false).text("Buat Group");
                    }
                },
                error: function (xhr) {
                    console.error(xhr);
                    $("#status-message").html(`<div class="alert alert-danger">Gagal membuat group.</div>`);
                    $("#btn-submit").prop("disabled", false).text("Buat Group");
                }
            });
        });
    });
</script>

</html>