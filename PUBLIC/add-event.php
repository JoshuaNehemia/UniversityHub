<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buat Event | University Hub</title>
    <link rel="stylesheet" href="STYLES/root.css">
    <link rel="stylesheet" href="STYLES/main.css">
    <link rel="stylesheet" href="STYLES/form.css">
    <script src="SCRIPTS/jquery_3_7_1.js"></script>
    <script src="SCRIPTS/config.js"></script>
    <script src="SCRIPTS/auth.js"></script>
</head>
<style>
/* =====================================================
   HARD RWD OVERRIDE — CREATE EVENT PAGE
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
    align-items: center;
    gap: 10px;
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

.form-group {
    margin-bottom: 16px;
}

.form-control {
    width: 100%;
}

/* Poster preview safety */
#poster-preview img {
    max-width: 100% !important;
    height: auto;
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
        <div class="brand"><h1>University Hub</h1></div>
        <div class="user-menu"><span>Halo, <strong id="display-name">User</strong></span></div>
    </header>

    <div class="dashboard-wrapper">
        <aside class="sidebar">
            <nav class="sidebar-nav">
                <a href="index.php" class="nav-link active" style="padding-left: 50px;">Group Saya</a>
            </nav>
        </aside>

        <main class="main-content">
            <div style="margin-bottom: 20px;">
                <a id="btn-back" href="#" style="color:var(--text-secondary); text-decoration:none;">&larr; Kembali</a>
            </div>

            <div class="content-header">
                <h2>Buat Event Baru</h2>
                <p class="text-muted">Jadwalkan kegiatan untuk group ini.</p>
            </div>

            <div class="card" style="max-width: 600px; padding:30px;">
                <form id="form-add-event">
                    <input type="hidden" id="idgrup">

                    <div class="form-group">
                        <label class="form-label">Judul Event <span style="color:red">*</span></label>
                        <input type="text" id="judul" class="form-control" required placeholder="Contoh: Diskusi Mingguan">
                    </div>

                    <div class="form-group">
                        <label class="form-label">Waktu Pelaksanaan <span style="color:red">*</span></label>
                        <input type="datetime-local" id="tanggal" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Deskripsi</label>
                        <textarea id="keterangan" class="form-control" rows="4"></textarea>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Jenis Event</label>
                        <select id="jenis" class="form-control">
                            <option value="Privat">Privat (Hanya Anggota)</option>
                            <option value="Publik">Publik (Umum)</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Poster Event (Opsional)</label>
                        <input type="file" id="poster" class="form-control" accept="image/jpeg,image/jpg,image/png">
                        <small class="text-muted">Format: JPG, JPEG, PNG. Maksimal 5MB</small>
                        <div id="poster-preview" style="margin-top: 10px; display: none;">
                            <img id="preview-img" src="" alt="Preview" style="max-width: 200px; max-height: 200px; border-radius: 8px; border: 2px solid var(--border-color);">
                        </div>
                    </div>

                    <div style="margin-top: 30px; display:flex; justify-content:flex-end; gap:10px;">
                        <button type="button" id="btn-cancel" class="btn btn-outline">Batal</button>
                        <button type="submit" id="btn-submit" class="btn btn-primary">Simpan Event</button>
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
        const groupId = urlParams.get('group_id');

        if (!groupId) { alert("ID Group tidak valid."); window.location.href = "index.php"; return; }
        
        $("#btn-back").attr("href", "detail-group.php?id=" + groupId);
        $("#btn-cancel").click(function(){ window.location.href = "detail-group.php?id=" + groupId; });
        $("#idgrup").val(groupId);

        // Image preview
        $("#poster").change(function() {
            const file = this.files[0];
            if (file) {
                // Validate file size (5MB)
                if (file.size > 5 * 1024 * 1024) {
                    alert("Ukuran file terlalu besar! Maksimal 5MB");
                    $(this).val('');
                    $("#poster-preview").hide();
                    return;
                }
                
                // Show preview
                const reader = new FileReader();
                reader.onload = function(e) {
                    $("#preview-img").attr('src', e.target.result);
                    $("#poster-preview").show();
                };
                reader.readAsDataURL(file);
            } else {
                $("#poster-preview").hide();
            }
        });

        $("#form-add-event").submit(function(e) {
            e.preventDefault();
            const btn = $("#btn-submit");
            btn.prop("disabled", true).text("Menyimpan...");
            $("#status-message").html("");

            let rawDate = $("#tanggal").val(); 
            let sqlDate = rawDate.replace("T", " ") + ":00";

            const formData = new FormData();
            formData.append('idgrup', groupId);
            formData.append('judul', $("#judul").val().trim());
            formData.append('tanggal', sqlDate);
            formData.append('keterangan', $("#keterangan").val().trim());
            formData.append('jenis', $("#jenis").val());

            const posterFile = $("#poster")[0].files[0];
            if (posterFile) {
                formData.append('poster', posterFile);
            }

            $.ajax({
                url: API_ADDRESS + "EVENT/", 
                type: "POST",
                data: formData,
                processData: false,  
                contentType: false,  
                dataType: "text",
                success: function(responseText) {
                    try {
                        const res = JSON.parse(responseText);
                        if (res.status === "success") {
                            $("#status-message").html(`<div class="alert alert-success">Event berhasil dibuat!</div>`);
                            setTimeout(() => { window.location.href = "detail-group.php?id=" + groupId; }, 1000);
                        } else {
                            $("#status-message").html(`<div class="alert alert-danger">${res.message}</div>`);
                            btn.prop("disabled", false).text("Simpan Event");
                        }
                    } catch(e) {
                        $("#status-message").html(`<div class="alert alert-danger">Error: Tidak dapat memproses response</div>`);
                        btn.prop("disabled", false).text("Simpan Event");
                    }
                },
                error: function(xhr) {
                    let msg = "Gagal menghubungi server.";
                    try {
                        const res = JSON.parse(xhr.responseText);
                        msg = res.message || msg;
                    } catch(e) {}
                    $("#status-message").html(`<div class="alert alert-danger">${msg}</div>`);
                    btn.prop("disabled", false).text("Simpan Event");
                }
            });
        });
    });
</script>
</html>