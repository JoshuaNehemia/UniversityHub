<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Group | University Hub</title>

    <link rel="stylesheet" href="STYLES/root.css">
    <link rel="stylesheet" href="STYLES/main.css">
    <link rel="stylesheet" href="STYLES/form.css">

    <script src="SCRIPTS/jquery_3_7_1.js"></script>
    <script src="SCRIPTS/config.js"></script>
    <script src="SCRIPTS/auth.js"></script>
</head>
<style>
/* =====================================================
   HARD RWD OVERRIDE — EDIT GROUP PAGE
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

/* ---------- Layout ---------- */
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

/* ---------- Main Content ---------- */
.main-content {
    flex: 1;
    padding: 20px;
    min-width: 0;
}

.content-header h2 {
    font-size: 1.4rem;
    margin-bottom: 6px;
}

/* ---------- Card & Form ---------- */
.card {
    width: 100% !important;
    max-width: 600px;
}

#loading-form {
    font-size: 0.95rem;
}

.form-group {
    margin-bottom: 16px;
}

.form-control {
    width: 100%;
}

/* ---------- Buttons ---------- */
form div[style*="justify-content:flex-end"] {
    flex-wrap: wrap;
}

form button {
    min-width: 130px;
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

    /* Stack action buttons */
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
            <button id="themeToggle" onclick="toggleTheme()" class="btn-theme" title="Ganti Tema">🌙</button>
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
                <a id="btn-back" href="#" style="display:inline-flex; align-items:center; gap:5px; color:var(--text-secondary);">
                    <span>&larr;</span> Kembali
                </a>
            </div>

            <div class="content-header">
                <h2>Edit Group</h2>
                <p class="text-muted">Perbarui informasi komunitas Anda.</p>
            </div>

            <div class="card" style="max-width: 600px;">
                <div id="loading-form" style="text-align:center; padding:20px;">
                    Memuat data group...
                </div>

                <form id="form-edit-group" style="display:none;">
                    
                    <input type="hidden" id="idgrup" name="idgrup">

                    <div class="form-group">
                        <label for="nama" class="form-label">Nama Group <span style="color:red">*</span></label>
                        <input type="text" id="nama" name="nama" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label for="deskripsi" class="form-label">Deskripsi Singkat</label>
                        <textarea id="deskripsi" name="deskripsi" class="form-control" rows="3"></textarea>
                    </div>

                    <div class="form-group">
                        <label for="jenis" class="form-label">Jenis Group <span style="color:red">*</span></label>
                        <select id="jenis" name="jenis" class="form-control" required>
                            <option value="Publik">Publik</option>
                            <option value="Privat">Privat</option>
                        </select>
                        <small class="text-muted">Mengubah ke 'Privat' akan otomatis membuat kode akses baru jika belum ada.</small>
                    </div>

                    <div style="margin-top: 30px; display:flex; justify-content:flex-end; gap:10px;">
                        <button type="button" id="btn-cancel" class="btn btn-outline">Batal</button>
                        <button type="submit" id="btn-save" class="btn btn-primary">Simpan Perubahan</button>
                    </div>

                </form>
            </div>
            <div id="status-message" style="margin-top:20px; max-width:600px;"></div>
        </main>
    </div>

</body>

<script>
    $(document).ready(function() {
        checkLoggedIn();

        const urlParams = new URLSearchParams(window.location.search);
        const groupId = urlParams.get('id');

        if (!groupId) {
            alert("ID Group tidak ditemukan.");
            window.location.href = "index.php";
            return;
        }

        $("#btn-back").attr("href", "detail-group.php?id=" + groupId);
        $("#btn-cancel").click(function(){
            window.location.href = "detail-group.php?id=" + groupId;
        });

        loadGroupData(groupId);

        $("#form-edit-group").on("submit", function(e) {
            e.preventDefault();
            updateGroup(groupId);
        });
    });


    function loadGroupData(id) {
        $.ajax({
            url: API_ADDRESS + "GROUP/?id=" + id, 
            type: "GET",
            dataType: "json",
            success: function(res) {
                if (res.status === "success" && res.data) {
                    const g = Array.isArray(res.data) ? res.data[0] : res.data;
                    
                    $("#idgrup").val(g.idgrup);
                    $("#nama").val(g.nama);
                    $("#deskripsi").val(g.deskripsi);
                    $("#jenis").val(g.jenis);

                    $("#loading-form").hide();
                    $("#form-edit-group").fadeIn();
                } else {
                    alert("Gagal memuat data group.");
                    window.location.href = "index.php";
                }
            },
            error: function(err) {
                console.error(err);
                alert("Terjadi kesalahan koneksi.");
            }
        });
    }


    function updateGroup(id) {
        const payload = {
            id: id, 
            nama: $("#nama").val().trim(),
            deskripsi: $("#deskripsi").val().trim(),
            jenis: $("#jenis").val()
        };

        const btn = $("#btn-save");
        btn.prop("disabled", true).text("Menyimpan...");
        $("#status-message").html("");

        $.ajax({
            url: API_ADDRESS + "GROUP/", 
            type: "PUT",                 
            data: JSON.stringify(payload), 
            contentType: "application/json",
            dataType: "json",
            success: function(res) {
                if (res.status === "success") {
                    alert("Perubahan berhasil disimpan!");
                    window.location.href = "detail-group.php?id=" + id;
                } else {
                    $("#status-message").html(`<div class="alert alert-danger">${res.message}</div>`);
                    btn.prop("disabled", false).text("Simpan Perubahan");
                }
            },
            error: function(xhr) {
                console.error(xhr);
                let msg = "Gagal menyimpan perubahan.";
                if(xhr.responseJSON && xhr.responseJSON.message) msg = xhr.responseJSON.message;
                
                $("#status-message").html(`<div class="alert alert-danger">${msg}</div>`);
                btn.prop("disabled", false).text("Simpan Perubahan");
            }
        });
    }
</script>
</html>