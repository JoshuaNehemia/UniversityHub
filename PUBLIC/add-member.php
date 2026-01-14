<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Anggota | University Hub</title>
    <link rel="stylesheet" href="STYLES/root.css">
    <link rel="stylesheet" href="STYLES/main.css">
    <link rel="stylesheet" href="STYLES/form.css">
    <script src="SCRIPTS/jquery_3_7_1.js"></script>
    <script src="SCRIPTS/config.js"></script>
    <script src="SCRIPTS/auth.js"></script>
    <style>
        .search-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 20px; margin-top: 20px; }
        .member-card { background: var(--bg-card); border: 1px solid var(--border-color); border-radius: 10px; padding: 20px; display: flex; align-items: center; gap: 15px; transition: 0.2s; }
        .member-card:hover { border-color: var(--first-color); box-shadow: 0 4px 10px rgba(0,0,0,0.05); }
        .member-img { width: 50px; height: 50px; border-radius: 50%; object-fit: cover; background: #eee; }
        .tab-btn { padding: 15px 30px; border: none; background: none; cursor: pointer; font-weight: bold; color: var(--text-secondary); transition: all 0.3s; }
        .tab-btn.active { color: var(--first-color); border-bottom: 3px solid var(--first-color); }
    </style>
</head>
<body>
    <header class="top-bar">
        <div class="brand"><h1>University Hub</h1></div>
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
                <a id="btn-back" href="#" style="display:inline-flex; align-items:center; gap:5px; color:var(--text-secondary); text-decoration:none;">
                    <span>&larr;</span> Kembali ke Detail Group
                </a>
            </div>

            <div class="content-header">
                <h2>Tambah Anggota</h2>
                <p class="text-muted">Pilih mahasiswa atau dosen untuk ditambahkan ke group.</p>
            </div>

            <div class="card" style="margin-bottom:30px;">
                <div style="display:flex; gap:15px; border-bottom: 2px solid var(--border-color);">
                    <button id="tab-mahasiswa" class="tab-btn active">Mahasiswa</button>
                    <button id="tab-dosen" class="tab-btn">Dosen</button>
                </div>
                <div style="margin-top: 20px;">
                    <input type="text" id="keyword-input" class="form-control" placeholder="Cari nama..." style="max-width: 400px;">
                </div>
            </div>

            <h3 style="font-size:1.1rem; margin-bottom:15px; color:var(--text-secondary);">Daftar <span id="jenis-label">Mahasiswa</span></h3>
            <div id="list-hasil-pencarian" class="search-grid">
                <p class="text-muted" style="grid-column: 1/-1;">Memuat data...</p>
            </div>
            
            <div id="pagination" style="margin-top:30px; display:none; justify-content:center; gap:15px; align-items:center;">
                <button id="page-prev" class="btn btn-outline btn-sm">Previous</button>
                <span id="page-number" style="font-weight:bold;">1</span>
                <button id="page-next" class="btn btn-outline btn-sm">Next</button>
            </div>
        </main>
    </div>
</body>

<script>
    $(document).ready(function() {
        checkLoggedIn();
        
        const urlParams = new URLSearchParams(window.location.search);
        const idgroup = urlParams.get("idgroup");

        if (!idgroup) { alert("ID Group hilang."); window.location.href = "index.php"; return; }
        $("#btn-back").attr("href", "detail-group.php?id=" + idgroup);

        let offset = 0;
        let currentJenis = "MAHASISWA";
        window.groupMembers = []; 

        loadGroupMembers(idgroup);

        function doSearch() {
            cariMember(currentJenis, $("#keyword-input").val(), idgroup, offset);
        }

        $("#tab-mahasiswa").click(function() {
            currentJenis = "MAHASISWA";
            $("#jenis-label").text("Mahasiswa");
            $(".tab-btn").removeClass("active");
            $(this).addClass("active");
            offset = 0;
            doSearch();
        });

        $("#tab-dosen").click(function() {
            currentJenis = "DOSEN";
            $("#jenis-label").text("Dosen");
            $(".tab-btn").removeClass("active");
            $(this).addClass("active");
            offset = 0;
            doSearch();
        });

        let searchTimeout;
        $("#keyword-input").on("keyup", function() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(function() {
                offset = 0;
                doSearch();
            }, 300);
        });

        $("#page-prev").click(function() { if(offset > 0) { offset--; doSearch(); } });
        $("#page-next").click(function() { offset++; doSearch(); });

        function loadGroupMembers(groupId) {
            $.ajax({
                url: API_ADDRESS + "MEMBER/",
                type: "GET",
                data: { idgroup: groupId },
                success: function(res) {
                    if (res.status === "success" && res.data) {
                        window.groupMembers = [];
                        if (Array.isArray(res.data)) {
                            window.groupMembers = res.data.map(m => m.username);
                        } else {
                            if (res.data.DOSEN) window.groupMembers = window.groupMembers.concat(res.data.DOSEN.map(m => m.username));
                            if (res.data.MAHASISWA) window.groupMembers = window.groupMembers.concat(res.data.MAHASISWA.map(m => m.username));
                        }
                    }
                    doSearch();
                },
                error: function() {
                    doSearch();
                }
            });
        }
    });

    function cariMember(jenis, keyword, idgroup, offset) {
        const limit = 9; 
        const list = $("#list-hasil-pencarian");
        list.html('<p class="text-muted">Memuat...</p>');

        $.ajax({
            url: API_ADDRESS + jenis + "/",
            type: "GET",
            dataType: "json",
            data: { limit: limit, offset: offset, keyword: keyword },
            success: function(res) {
                list.empty();
                $("#page-number").text(offset + 1);

                if (res.status !== "success" || !res.data || res.data.length === 0) {
                    list.html("<p class='text-muted' style='grid-column: 1/-1;'>Tidak ada hasil ditemukan.</p>");
                    $("#pagination").hide();
                    return;
                }
                
                $("#pagination").css("display", "flex");

                res.data.forEach(item => {
                    let idNomor, fotoPath;
                    if (jenis === "MAHASISWA") {
                        idNomor = item.nrp;
                        fotoPath = `../APP/DATABASE/PROFILE/MAHASISWA/${item.nrp}.${item.foto_extention}`;
                    } else {
                        idNomor = item.npk;
                        fotoPath = `../APP/DATABASE/PROFILE/DOSEN/${item.npk}.${item.foto_extention}`;
                    }

                    const isMember = window.groupMembers && window.groupMembers.includes(item.username);
                    
                    let actionButton;
                    if (isMember) {
                        actionButton = `<span style="color:var(--status-success); font-weight:bold; font-size:0.8rem;">✓ Sudah berada di grup</span>`;
                    } else {
                        actionButton = `<button class="btn btn-sm btn-outline btn-add-member" 
                            data-username="${item.username}" 
                            style="color:var(--first-color); border-color:var(--first-color);">
                            + Add
                        </button>`;
                    }

                    const card = `
                        <div class="member-card">
                            <img src="${fotoPath}" onerror="this.src='IMAGES/default_profile_picture.svg'" class="member-img">
                            <div style="flex:1; overflow:hidden;">
                                <h4 style="margin:0; font-size:1rem; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">${item.nama}</h4>
                                <span style="font-size:0.8rem; color:var(--text-secondary);">${idNomor}</span>
                            </div>
                            ${actionButton}
                        </div>
                    `;
                    list.append(card);
                });

                $("#list-hasil-pencarian").off("click", ".btn-add-member").on("click", ".btn-add-member", function() {
                    const btn = $(this);
                    btn.prop("disabled", true).text("...");
                    tambahMember(btn.data("username"), idgroup, btn);
                });
            },
            error: function() { list.html("<p style='color:red;'>Gagal terhubung ke server.</p>"); }
        });
    }

    function tambahMember(username, idgroup, btnElement) {
        let baseUrl = API_ADDRESS;
        if(baseUrl.endsWith("/")) baseUrl = baseUrl.slice(0, -1);
        
        const payload = {
            idgrup: idgroup,
            username: username
        };

        $.ajax({
            url: baseUrl + "/MEMBER/", 
            type: "POST",
            data: payload,
            dataType: "text", 
            success: function(responseText) {
                try {
                    const res = JSON.parse(responseText);
                    if (res.status === "success") {
                        alert("✓ Berhasil ditambahkan ke grup!");
                        btnElement.replaceWith(`<span style="color:var(--status-success); font-weight:bold; font-size:0.8rem;">✓ Sudah berada di grup</span>`);
                        window.groupMembers.push(username);
                    } else {
                        alert("Gagal menambahkan member: " + (res.message || "Terjadi kesalahan"));
                        btnElement.prop("disabled", false).text("+ Add");
                    }
                } catch(e) {
                    alert("Error: Tidak dapat memproses response dari server");
                    btnElement.prop("disabled", false).text("+ Add");
                }
            },
            error: function(xhr) {
                let errorMsg = "Terjadi kesalahan";
                try {
                    const response = JSON.parse(xhr.responseText);
                    errorMsg = response.message || errorMsg;
                } catch(e) {
                    errorMsg = xhr.statusText || errorMsg;
                }
                alert("Gagal menambahkan member: " + errorMsg);
                btnElement.prop("disabled", false).text("+ Add");
            }
        });
    }
</script>
</html>