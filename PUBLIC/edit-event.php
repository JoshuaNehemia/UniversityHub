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
                <h2>Edit Group</h2>
                <p class="text-muted">Perbarui informasi komunitas Anda.</p>
            </div>

            <div class="card" style="max-width: 600px; padding:30px;">
                <div id="loading-form" style="text-align:center; padding:20px;">Memuat data...</div>

                <form id="form-edit-group" style="display:none;">
                    <div class="form-group">
                        <label class="form-label">Nama Group <span style="color:red">*</span></label>
                        <input type="text" id="nama" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Deskripsi</label>
                        <textarea id="deskripsi" class="form-control" rows="3"></textarea>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Jenis Group</label>
                        <select id="jenis" class="form-control">
                            <option value="Publik">Publik</option>
                            <option value="Privat">Privat</option>
                        </select>
                    </div>

                    <div style="margin-top: 30px; display:flex; justify-content:flex-end; gap:10px;">
                        <button type="button" id="btn-cancel" class="btn btn-outline">Batal</button>
                        <button type="submit" id="btn-save" class="btn btn-primary">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </main>
    </div>
</body>

<script>
    $(document).ready(function() {
        checkLoggedIn();
        const urlParams = new URLSearchParams(window.location.search);
        const groupId = urlParams.get('id');

        if (!groupId) { window.location.href = "index.php"; return; }

        $("#btn-back").attr("href", "detail-group.php?id=" + groupId);
        $("#btn-cancel").click(() => window.location.href = "detail-group.php?id=" + groupId);

        $.ajax({
            url: API_ADDRESS + "GROUP/?id=" + groupId,
            type: "GET",
            dataType: "json",
            success: function(res) {
                if (res.status === "success" && res.data) {
                    const g = Array.isArray(res.data) ? res.data[0] : res.data;
                    $("#nama").val(g.nama);
                    $("#deskripsi").val(g.deskripsi);
                    $("#jenis").val(g.jenis);
                    $("#loading-form").hide();
                    $("#form-edit-group").fadeIn();
                } else {
                    alert("Gagal memuat data.");
                    window.location.href = "index.php";
                }
            }
        });

        $("#form-edit-group").submit(function(e) {
            e.preventDefault();
            const btn = $("#btn-save");
            btn.prop("disabled", true).text("Menyimpan...");

            const payload = {
                id: groupId, 
                idgrup: groupId, 
                nama: $("#nama").val().trim(),
                deskripsi: $("#deskripsi").val().trim(),
                jenis: $("#jenis").val()
            };

            $.ajax({
                url: API_ADDRESS + "GROUP/",
                type: "PUT",
                data: JSON.stringify(payload),
                contentType: "application/json",
                success: function(res) {
                    if (res.status === "success") {
                        alert("Perubahan disimpan.");
                        window.location.href = "detail-group.php?id=" + groupId;
                    } else {
                        alert("Gagal: " + res.message);
                        btn.prop("disabled", false).text("Simpan Perubahan");
                    }
                },
                error: function(xhr) {
                    alert("Error: " + (xhr.responseJSON?.message || "Server Error"));
                    btn.prop("disabled", false).text("Simpan Perubahan");
                }
            });
        });
    });
</script>
</html>