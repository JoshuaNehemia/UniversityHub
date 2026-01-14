<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Group | University Hub</title>
    <link rel="stylesheet" href="STYLES/root.css">
    <link rel="stylesheet" href="STYLES/main.css">
    <link rel="stylesheet" href="STYLES/form.css">
    <link rel="stylesheet" href="STYLES/mobile-fix.css">
    <link rel="stylesheet" href="STYLES/table.css">
    <script src="SCRIPTS/jquery_3_7_1.js"></script>
    <script src="SCRIPTS/config.js"></script>
    <script src="SCRIPTS/auth.js"></script>

    <style>
        /* =====================================================
   HARD RWD FIX — FORCE MOBILE BEHAVIOR
   Overrides inline styles safely
===================================================== */

        * {
            box-sizing: border-box;
        }

        html,
        body {
            max-width: 100%;
            overflow-x: hidden;
        }

        /* ===============================
   MODAL / POPUP — FIXED
================================ */
        .modal {
            position: fixed !important;
            inset: 0;
            z-index: 2000;
            background: rgba(0, 0, 0, .5);
            display: none;
            padding: 16px;
        }

        .modal[style*="display: flex"],
        .modal[style*="display:flex"] {
            display: flex !important;
        }

        .modal-content {
            margin: auto;
            width: 100%;
            max-width: 500px;
            max-height: 90vh;
            overflow-y: auto;
        }

        /* Prevent popup overflow */
        #kode-popup {
            padding: 16px;
        }

        #kode-popup>.card {
            width: 100% !important;
            max-width: 420px;
            margin: auto;
        }

        /* ===============================
   CONTENT GRID — FORCE STACK
================================ */
        #content-container {
            display: grid !important;
            grid-template-columns: 2fr 1fr;
            gap: 30px;
        }

        /* Tablet */
        @media (max-width: 992px) {
            #content-container {
                grid-template-columns: 1fr !important;
            }
        }

        /* Mobile HARD FORCE */
        @media (max-width: 768px) {

            #content-container {
                grid-template-columns: 1fr !important;
                gap: 20px;
            }

            /* Members MUST go below */
            #content-container>div {
                width: 100%;
                min-width: 0;
            }
        }

        /* ===============================
   MEMBER TABLE — NO SIDE SCROLL
================================ */
        .table-container {
            width: 100%;
            overflow-x: hidden !important;
        }

        .table {
            width: 100%;
            table-layout: fixed;
        }

        .table th,
        .table td {
            word-wrap: break-word;
            white-space: normal;
        }

        /* ===============================
   FLEX OVERFLOW FIX
================================ */
        .card,
        .card * {
            min-width: 0;
        }

        /* Thread card */
        .card[onclick]>div {
            flex-wrap: wrap;
        }

        /* Event card */
        .card[style*="display: flex"] {
            flex-wrap: wrap;
        }

        .card img {
            width: 100%;
            height: auto;
        }

        /* ===============================
   BUTTONS — MOBILE FRIENDLY
================================ */
        .btn,
        button {
            max-width: 100%;
        }

        @media (max-width: 576px) {

            .btn,
            button {
                width: 100%;
            }
        }

        /* ===============================
   LOCK OVERLAY
================================ */
        .lock-overlay {
            width: 90%;
            max-width: 420px;
            padding: 16px;
        }

        /* ===============================
   SEARCH RESULT
================================ */
        .search-result-item {
            flex-wrap: wrap;
            gap: 8px;
        }

        /* ===============================
   BACK LINK
================================ */
        .back-link {
            flex-wrap: wrap;
        }

        /* ===============================
   TEXT SCALE
================================ */
        @media (max-width: 360px) {
            h2 {
                font-size: 1.2rem;
            }

            h3 {
                font-size: 1rem;
            }

            p,
            small {
                font-size: 0.85rem;
            }
        }

        /* Table base fix */
        .table-container {
            width: 100%;
            overflow-x: hidden !important;
        }

        .table {
            width: 100%;
            table-layout: fixed;
        }

        .table td {
            padding: 10px 8px;
            vertical-align: middle;
        }

        /* Flex inside table cell */
        .table td>div {
            display: flex !important;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
            width: 100%;
            min-width: 0;
        }

        /* Member name */
        .table td span {
            flex: 1;
            min-width: 0;
            word-break: break-word;
        }

        /* Delete button */
        .table td button {
            flex-shrink: 0;
            min-width: 32px;
            height: 32px;
            padding: 0;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 0.85rem;
            line-height: 1;
        }

        /* ===============================
   MOBILE STACK (VERY SMALL)
================================ */
        @media (max-width: 480px) {

            .table td>div {
                flex-direction: row;
            }

            .table td button {
                width: 36px;
                height: 36px;
            }
        }

        /* ===============================
   TOUCH TARGET FIX
================================ */
        @media (pointer: coarse) {
            .table td button {
                min-width: 40px;
                height: 40px;
            }
        }

        * {
            box-sizing: border-box;
        }

        html,
        body {
            max-width: 100%;
            overflow-x: hidden;
        }

        .modal {
            position: fixed !important;
            inset: 0;
            z-index: 2000;
            background: rgba(0, 0, 0, .5);
            display: none;
            padding: 16px;
        }

        .modal[style*="display: flex"] {
            display: flex !important;
        }

        .modal-content {
            margin: auto;
            width: 100%;
            max-width: 500px;
            max-height: 90vh;
            overflow-y: auto;
        }

        body.modal-open {
            overflow: hidden;
        }

        /* ===============================
   CREATE THREAD MODAL — BACKGROUND FIX
================================ */

        /* Overlay */
        #modal-create-thread {
            position: fixed !important;
            inset: 0;
            z-index: 3000;
            display: none;
            background: rgba(0, 0, 0, 0.8);
            /* DARK BACKGROUND */
            backdrop-filter: blur(10px);
            /* OPTIONAL BLUR */
            -webkit-backdrop-filter: blur(10px);
            padding: 16px;
        }

        /* Force flex when shown via JS */
        #modal-create-thread[style*="display: flex"],
        #modal-create-thread[style*="display:flex"] {
            display: flex !important;
        }

        /* Centered modal card */
        #modal-create-thread .modal-content {
            margin: auto;
            width: 100%;
            max-width: 420px;
            max-height: 90vh;
            overflow-y: auto;
            border-radius: 12px;
        }

        /* Prevent body scroll when modal open */
        body.modal-open {
            overflow: hidden;
        }
    </style>

</head>

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
                <a href="index.php" class="nav-link">Group Saya</a>
                <a href="group.php" class="nav-link active">Cari Group</a>
                <a href="profil.php" class="nav-link">Profil Akun</a>
            </nav>
        </aside>

        <main class="main-content">
            <div style="margin-bottom: 20px;">
                <a href="group.php" class="back-link">
                    <span>&larr;</span> Kembali ke pencarian
                </a>
            </div>

            <div id="loading-indicator" style="text-align:center; padding:50px;">
                <p class="text-muted">Memuat detail group...</p>
            </div>
            <div id="error-message" style="text-align:center; padding:50px; display:none; color:red;"></div>

            <div id="group-content" style="display:none; animation: fadeIn 0.3s;">

                <div class="card" style="margin-bottom: 30px; border-left: 5px solid var(--first-color);">
                    <div
                        style="display:flex; justify-content:space-between; align-items:flex-start; flex-wrap:wrap; gap:20px;">
                        <div style="flex:1;">
                            <span id="group-type-badge" class="status-badge" style="margin-bottom:10px;">Type</span>
                            <h2 id="group-name" style="margin-bottom:10px; color:var(--first-color);">Nama Group</h2>
                            <p id="group-desc" class="text-muted">Deskripsi group...</p>

                            <div id="group-code-container"
                                style="display:none; margin-top:15px; padding:10px 15px; background-color: #f8f9fa; border: 1px dashed #6c757d; border-radius: 6px;">
                                <span class="text-muted" style="font-size:0.9rem;">🔑 Kode Akses:</span>
                                <strong id="group-code-text"
                                    style="font-family:monospace; font-size:1.2rem; margin-left:10px; color:var(--first-color); letter-spacing: 2px;">-</strong>
                            </div>

                            <div style="margin-top:15px; font-size:0.9rem; color:var(--text-secondary);">
                                📅 Dibuat pada: <span id="group-date">-</span><br>
                                👤 Oleh: <span id="group-creator">-</span>
                            </div>
                        </div>
                        <div style="display:flex; gap:10px; flex-wrap:wrap;">
                            <button id="btn-join" class="btn btn-primary" style="display:none;">Gabung Group</button>
                            <button id="btn-leave" class="btn btn-outline"
                                style="display:none; color:var(--status-failed); border-color:var(--status-failed);">Keluar
                                Group</button>

                            <button id="btn-add-member" class="btn btn-secondary" style="display:none;">+ Tambah
                                Anggota</button>
                            <a id="btn-edit" href="#" class="btn btn-secondary" style="display:none;">Edit Group</a>
                            <a id="btn-add-event" href="#" class="btn btn-primary" style="display:none;">+ Buat
                                Event</a>
                        </div>
                    </div>
                </div>

                <div id="restricted-area" style="position:relative;">

                    <div id="lock-message" class="lock-overlay" style="display:none;">
                        <img src="IMAGES/warning.svg" style="width:50px; opacity:0.6; margin-bottom:15px;">
                        <h3>Konten Terkunci</h3>
                        <p class="text-muted">Anda harus bergabung dengan group ini untuk melihat diskusi, event, dan
                            daftar anggota.</p>
                    </div>

                    <div id="content-container" style="display: grid; grid-template-columns: 2fr 1fr; gap: 30px;">
                        <div>
                            <div style="margin-bottom: 30px;">
                                <div class="content-header"
                                    style="border-bottom:none; margin-bottom:10px; display:flex; justify-content:space-between; align-items:center;">
                                    <h3>Diskusi (Threads)</h3>
                                    <button id="btn-create-thread" class="btn btn-sm btn-primary"
                                        style="display:none;">+ Topik Baru</button>
                                </div>
                                <div id="thread-list">
                                    <div class="card">
                                        <p class="text-muted">Memuat topik diskusi...</p>
                                    </div>
                                </div>
                            </div>

                            <div>
                                <div class="content-header" style="border-bottom:none; margin-bottom:10px;">
                                    <h3>Daftar Event</h3>
                                </div>
                                <div id="event-list">
                                    <div class="card">
                                        <p class="text-muted">Memuat event...</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div>
                            <div class="content-header" style="border-bottom:none; margin-bottom:10px;">
                                <h3>Anggota Group</h3>
                            </div>
                            <div class="table-container">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>Nama Anggota</th>
                                        </tr>
                                    </thead>
                                    <tbody id="member-list-body"></tbody>
                                </table>
                                <div id="member-empty-state"
                                    style="padding:15px; text-align:center; color:var(--text-secondary); display:none;">
                                    Belum ada anggota.</div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </main>
    </div>

    <div id="modal-add-member" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Tambah Mahasiswa</h3><span class="close-modal"
                    onclick="$('#modal-add-member').fadeOut()">&times;</span>
            </div>
            <input type="text" id="search-mhs-input" class="form-input" placeholder="Ketik Nama atau NRP..."
                autocomplete="off">
            <div id="search-results" style="max-height: 200px; overflow-y: auto; margin-top:10px;"></div>
        </div>
    </div>

    <div id="modal-create-thread" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Buat Topik Diskusi</h3><span class="close-modal"
                    onclick="$('#modal-create-thread').fadeOut()">&times;</span>
            </div>
            <form id="form-create-thread">
                <div class="form-group">
                    <label class="form-label">Judul Topik <span style="color:red">*</span></label>
                    <input type="text" id="thread-title-input" class="form-control"
                        placeholder="Masukkan judul topik diskusi..." required>
                </div>
                <button type="submit" class="btn btn-primary" style="margin-top:15px; width:100%;">Buat Thread</button>
            </form>
        </div>
    </div>

    <div id="kode-popup"
        style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.5); z-index:1002; justify-content:center; align-items:center;">
        <div class="card" style="width:100%; max-width:400px;">
            <h3>🔒 Masukkan Kode Akses</h3>
            <input type="text" id="kode-input" class="form-control" placeholder="Kode Group..."
                style="margin-top:15px;">
            <div style="margin-top:15px; display:flex; gap:10px; justify-content:flex-end;">
                <button onclick="$('#kode-popup').fadeOut()" class="btn btn-outline">Batal</button>
                <button id="btn-submit-kode" class="btn btn-primary">Gabung</button>
            </div>
        </div>
    </div>

</body>

<script>
    let currentGroupId = null;

    $(document).ready(function () {
        $.ajax({
            url: API_ADDRESS + "AUTH/", type: "GET", data: { jenis: "account" }, dataType: "json",
            success: function (res) {
                if (res.status === "success") {
                    window.SESSION = res.data;
                    $("#display-name").text(res.data.nama);

                    const urlParams = new URLSearchParams(window.location.search);
                    currentGroupId = urlParams.get('id');
                    if (!currentGroupId) { window.location.href = "group.php"; return; }

                    loadGroupDetail(currentGroupId);
                } else { window.location.href = 'login.php'; }
            },
            error: function () { window.location.href = 'login.php'; }
        });

        $("#btn-join").click(function () { $("#kode-popup").css("display", "flex").hide().fadeIn(); });
        $("#btn-submit-kode").click(function () { joinGroupWithCode($("#kode-input").val()); });
        $("#btn-add-member").click(function () {
            window.location.href = "add-member.php?idgroup=" + currentGroupId;
        });
        $("#search-mhs-input").keyup(function () { if ($(this).val().length >= 3) searchMahasiswa($(this).val()); });
        $("#btn-create-thread").click(function () { $("#modal-create-thread").css("display", "flex").hide().fadeIn(); });
        $("#form-create-thread").submit(function (e) { e.preventDefault(); createNewThread(); });

        $("#btn-leave").click(function () {
            if (confirm("Keluar dari group?")) {
                let baseUrl = API_ADDRESS.replace(/\/$/, "");
                $.ajax({
                    url: baseUrl + "/MEMBER/?action=leave&idgroup=" + currentGroupId, type: "DELETE",
                    success: function (res) { if (res.status === "success") { alert("Berhasil keluar."); location.reload(); } else alert(res.message); }
                });
            }
        });

        $("#btn-create-thread").on("click", function () {
            $("#modal-create-thread").fadeIn();
        });

    });

    function loadGroupDetail(id) {
        let baseUrl = API_ADDRESS.replace(/\/$/, "");
        $.ajax({
            url: baseUrl + "/GROUP/", type: "GET", dataType: "json", data: { id: id },
            success: function (res) {
                $("#loading-indicator").hide();
                if (res.status !== "success" || !res.data) { $("#error-message").show().text("Group tidak ditemukan."); return; }
                $("#group-content").fadeIn();
                const data = Array.isArray(res.data) ? res.data[0] : res.data;
                const realGroupId = data.id || data.idgrup;

                $("#group-name").text(data.nama);
                $("#group-desc").text(data.deskripsi || "Tidak ada deskripsi.");
                $("#group-type-badge").text(data.jenis);
                $("#group-date").text(data.tanggal_dibuat || "-");
                $("#group-creator").text(data.pembuat || data.username_pembuat);

                if (data.jenis === 'Privat') $("#group-type-badge").css({ 'background': 'var(--status-waiting-bg)', 'color': 'var(--status-waiting)' });
                else $("#group-type-badge").css({ 'background': 'var(--status-success-bg)', 'color': 'var(--status-success)' });

                checkMembershipAndLoadContent(realGroupId, data.pembuat || data.username_pembuat, data.kode);
            },
            error: function (xhr) { $("#loading-indicator").hide(); $("#error-message").show().text("Error memuat group."); }
        });
    }


    function checkMembershipAndLoadContent(groupId, ownerUsername, groupCode) {
        let baseUrl = API_ADDRESS.replace(/\/$/, "");

        $.ajax({
            url: baseUrl + "/MEMBER/", type: "GET", dataType: "json", data: { idgroup: groupId },
            success: function (res) {
                let allMembers = [];
                if (res.status === "success" && res.data) {
                    if (Array.isArray(res.data)) allMembers = res.data;
                    else { if (res.data.DOSEN) allMembers = allMembers.concat(res.data.DOSEN); if (res.data.MAHASISWA) allMembers = allMembers.concat(res.data.MAHASISWA); }
                }

                const currentUser = window.SESSION.username;
                const userRole = window.SESSION.jenis;
                const isMember = allMembers.some(m => m.username === currentUser);
                const isOwner = (currentUser === ownerUsername);
                const isAdmin = (userRole === "ADMIN");
                const isDosen = (userRole === "DOSEN");

                if (isOwner || isAdmin) {
                    $("#btn-edit").show().attr("href", "edit-group.php?id=" + groupId);
                    $("#btn-add-event").show().attr("href", "add-event.php?group_id=" + groupId);
                    $("#btn-add-member").show();
                    $("#btn-join").hide(); $("#btn-leave").hide();
                    if (groupCode) { $("#group-code-text").text(groupCode); $("#group-code-container").show(); }
                } else if (isMember) {
                    $("#btn-leave").show(); $("#btn-join").hide();
                } else {
                    $("#btn-join").show(); $("#btn-leave").hide();
                }

                if (isMember || isOwner || isAdmin) {
                    $("#lock-message").hide();
                    $("#content-container").removeClass("blur-content");

                    renderMemberList(allMembers, ownerUsername, groupId);
                    loadGroupThreads(groupId, isMember || isOwner || isAdmin);
                    loadGroupEvents(groupId, ownerUsername);
                } else {
                    $("#content-container").addClass("blur-content");
                    $("#lock-message").show();
                    $("#thread-list").empty();
                    $("#event-list").empty();
                }
            }
        });
    }

    function renderMemberList(members, owner, groupId) {
        const tbody = $("#member-list-body"); tbody.empty();
        const currentUser = window.SESSION.username;
        const canDelete = (currentUser === owner) || (window.SESSION.jenis === "ADMIN");

        if (members.length > 0) {
            $("#member-empty-state").hide();
            members.forEach(m => {
                const name = m.nama || m.nama_mahasiswa || m.username;
                let delBtn = (canDelete && m.username !== currentUser) ? `<button onclick="kickMember('${groupId}','${m.username}')" class="btn btn-sm btn-outline" style="color:red;border-color:red;padding:2px 6px;">x</button>` : "";
                tbody.append(`<tr><td><div style="display:flex;justify-content:space-between;"><span>${name}</span>${delBtn}</div></td></tr>`);
            });
        } else { $("#member-empty-state").show(); }
    }

    function loadGroupThreads(id, canCreateThread) {
        let baseUrl = API_ADDRESS.replace(/\/$/, "");
        $.ajax({
            url: baseUrl + "/THREAD/",
            type: "GET",
            data: { idgrup: id },
            dataType: "text",
            success: function (responseText) {
                const c = $("#thread-list");
                c.empty();

                if (canCreateThread) {
                    $("#btn-create-thread").show();
                } else {
                    $("#btn-create-thread").hide();
                }

                try {
                    const res = JSON.parse(responseText);
                    if (res.status === "success" && res.data && res.data.length > 0) {
                        res.data.forEach(t => {
                            console.log(t);

                            const currentUser = window.SESSION.username;
                            const isCreator = (t.pembuat === currentUser);
                            const isAdmin = (window.SESSION.jenis === "ADMIN");
                            const isClosed = (t.status === "Close");
                            const canDelete = (isCreator || isAdmin) && !isClosed;

                            let statusBadge = `
                                <span style="
                                    padding: 3px 10px;
                                    border-radius: 12px;
                                    font-size: 0.75rem;
                                    font-weight: 600;
                                    background: ${isClosed ? 'var(--status-failed-bg)' : 'var(--status-success-bg)'};
                                    color: ${isClosed ? 'var(--status-failed)' : 'var(--status-success)'};
                                ">
                                    ${isClosed ? 'Closed' : 'Open'}
                                </span>
                                `;

                            let deleteBtn = '';
                            if (canDelete) {
                                deleteBtn = `
                                    <button onclick="event.stopPropagation(); deleteThread(${t.idthread || t.id}, '${t.judul}');" 
                                            class="btn btn-sm" 
                                            style="padding: 4px 8px; background: #f44336; color: white; border: none; border-radius: 4px; font-size: 0.75rem;">
                                        🗑️
                                    </button>
                                `;
                            }

                            c.append(`
                                <div class="card" style="margin-bottom:12px; padding:15px; cursor:pointer; transition: all 0.2s; border-left: 3px solid var(--first-color);" 
                                     onmouseover="this.style.boxShadow='0 2px 8px rgba(0,0,0,0.1)'" 
                                     onmouseout="this.style.boxShadow=''"
                                     onclick="window.location.href='detail-thread.php?id=${t.idthread || t.id}'">
                                    <div style="display: flex; justify-content: space-between; align-items: start; gap: 10px;">
                                        <div style="display:flex; gap:8px; align-items:center;">
                                            ${statusBadge}
                                        </div>
                                        <div style="flex: 1; min-width: 0;">
                                            <h5 style="margin: 0 0 5px 0; color: var(--first-color); font-size: 1rem;">${t.judul || 'Topik'}</h5>
                                            <small style="color: var(--text-secondary); font-size: 0.85rem;">
                                                👤 Oleh: ${t.pembuat} ${t.tanggal_pembuatan ? '• 📅 Dibuat pada: ' + t.tanggal_pembuatan : ''}
                                            </small>
                                        </div>
                                        ${deleteBtn}
                                    </div>
                                </div>
                            `);
                        });
                    } else {
                        c.html("<small>Belum ada topik diskusi.</small>");
                    }
                } catch (err) {
                    console.error("Error parsing threads:", err);
                    c.html("<small>Error memuat topik.</small>");
                }
            },
            error: function (xhr) {
                console.error("Error loading threads:", xhr);
                $("#thread-list").html("<small>Gagal memuat topik.</small>");
            }
        });
    }

    function loadGroupEvents(id, owner) {
        let baseUrl = API_ADDRESS.replace(/\/$/, "");
        $.ajax({
            url: baseUrl + "/EVENT/",
            type: "GET",
            data: { idgrup: id },
            dataType: "text",
            success: function (responseText) {
                const c = $("#event-list");
                c.empty();
                try {
                    const res = JSON.parse(responseText);
                    if (res.status === "success" && res.data && res.data.length > 0) {
                        res.data.forEach(e => {
                            let posterHtml = '';
                            if (e.poster_extention && e.poster_extention !== '') {
                                const posterPath = `../APP/DATABASE/POSTER/${e.id}.${e.poster_extention}`;
                                posterHtml = `
                                    <div style="flex-shrink: 0; width: 180px; height: 180px; overflow: hidden; border-radius: 8px; background: #f0f0f0;">
                                        <img src="${posterPath}" alt="Poster" 
                                             style="width: 100%; height: 100%; object-fit: cover;" 
                                             onerror="this.parentElement.innerHTML='<div style=\\'display:flex;align-items:center;justify-content:center;height:100%;color:#999;font-size:0.8rem;\\'>No Image</div>'">
                                    </div>
                                `;
                            }

                            const currentUser = window.SESSION.username;
                            const isOwner = (currentUser === owner);
                            const isAdmin = (window.SESSION.jenis === "ADMIN");
                            const canDelete = isOwner || isAdmin;

                            let deleteBtn = '';
                            if (canDelete) {
                                deleteBtn = `
                                    <button onclick="deleteEvent(${e.id}, '${e.judul}')" 
                                            class="btn btn-sm" 
                                            style="padding: 4px 10px; background: #f44336; color: white; border: none; border-radius: 4px; cursor: pointer; font-size: 0.8rem; margin-top: 8px;">
                                        🗑️ Hapus Event
                                    </button>
                                `;
                            }

                            const eventCard = `
                                <div class="card" style="margin-bottom: 15px; border-left: 4px solid #4CAF50; padding: 15px; display: flex; gap: 15px; align-items: start;">
                                    ${posterHtml}
                                    <div style="flex: 1; min-width: 0;">
                                        <h4 style="margin: 0 0 8px 0; color: var(--first-color); font-size: 1.1rem;">${e.judul}</h4>
                                        <div style="display: flex; align-items: center; gap: 5px; margin-bottom: 8px; color: var(--text-secondary); font-size: 0.9rem;">
                                            <span>📅</span>
                                            <span>${e.tanggal}</span>
                                        </div>
                                        ${e.keterangan ? `<p style="margin: 0; color: var(--text-secondary); font-size: 0.9rem; line-height: 1.4;">${e.keterangan}</p>` : ''}
                                        <div style="margin-top: 8px; display: flex; align-items: center; gap: 10px;">
                                            <span style="display: inline-block; padding: 3px 10px; background: ${e.jenis === 'Publik' ? 'var(--status-success-bg)' : 'var(--status-waiting-bg)'}; color: ${e.jenis === 'Publik' ? 'var(--status-success)' : 'var(--status-waiting)'}; border-radius: 12px; font-size: 0.75rem; font-weight: 500;">
                                                ${e.jenis}
                                            </span>
                                            ${deleteBtn}
                                        </div>
                                    </div>
                                </div>
                            `;
                            c.append(eventCard);
                        });
                    } else {
                        c.html("<small>Belum ada event.</small>");
                    }
                } catch (err) {
                    console.error("Error parsing events:", err);
                    c.html("<small>Error memuat event.</small>");
                }
            },
            error: function (xhr) {
                console.error("Error loading events:", xhr);
                $("#event-list").html("<small>Gagal memuat event.</small>");
            }
        });
    }

    function joinGroupWithCode(kode) {
        if (!kode) { alert("Masukkan kode!"); return; }
        let baseUrl = API_ADDRESS.replace(/\/$/, "");
        $.ajax({
            url: baseUrl + "/JOIN/", type: "POST", data: { idgrup: currentGroupId, kode: kode, username: window.SESSION.username },
            success: function (res) {
                if (res.status === "success") { alert("Berhasil join!"); location.reload(); }
                else alert(res.message);
            }
        });
    }

    function kickMember(gid, uname) {
        if (!confirm("Keluarkan member?")) return;
        let baseUrl = API_ADDRESS.replace(/\/$/, "");
        $.ajax({ url: baseUrl + "/MEMBER/?idgroup=" + gid, type: "DELETE", data: JSON.stringify({ username: uname }), success: function () { location.reload(); } });
    }

    function createNewThread() {
        let baseUrl = API_ADDRESS.replace(/\/$/, "");
        const threadTitle = $("#thread-title-input").val().trim();

        if (!threadTitle) {
            alert("Judul topik tidak boleh kosong!");
            return;
        }

        // Prevent double submit
        const submitBtn = $("#form-create-thread button[type='submit']");
        submitBtn.prop("disabled", true);

        $.ajax({
            url: baseUrl + "/THREAD/",
            type: "POST",
            data: {
                idgrup: currentGroupId,
                username: window.SESSION.username,
                judul: threadTitle
            },
            dataType: "text",
            success: function (responseText) {
                try {
                    const r = JSON.parse(responseText);
                    if (r.status === "success") {
                        $("#modal-create-thread").fadeOut();
                        $("#thread-title-input").val("");
                        location.reload();
                    } else {
                        alert(r.message || "Gagal membuat thread");
                    }
                } catch {
                    alert("Response error");
                }
            },
            error: function () {
                alert("Gagal membuat thread");
            },
            complete: function () {
                submitBtn.prop("disabled", false);
            }
        });
    }


    function searchMahasiswa(k) {
        let baseUrl = API_ADDRESS.replace(/\/$/, "");
        $.ajax({
            url: baseUrl + "/MEMBER/", data: { search: k }, success: function (res) {
                const c = $("#search-results"); c.empty();
                if (res.data) res.data.forEach(m => c.append(`<div class="search-result-item"><span>${m.nama}</span> <button class="btn btn-sm btn-primary" onclick="addMhs('${m.username}')">+</button></div>`));
            }
        });
    }
    function addMhs(u) {
        let baseUrl = API_ADDRESS.replace(/\/$/, "");
        $.ajax({ url: baseUrl + "/JOIN/", type: "POST", data: { username: u, idgrup: currentGroupId }, success: function (r) { if (r.status === "success") { alert("Ditambahkan"); location.reload(); } else { alert(r.message); } } });
    }

    function deleteEvent(eventId, eventTitle) {
        if (!confirm(`Hapus event "${eventTitle}"?\n\nEvent yang dihapus tidak dapat dikembalikan.`)) {
            return;
        }

        let baseUrl = API_ADDRESS.replace(/\/$/, "");
        $.ajax({
            url: baseUrl + "/EVENT/",
            type: "DELETE",
            data: JSON.stringify({ id: eventId }),
            contentType: "application/json",
            dataType: "text",
            success: function (responseText) {
                try {
                    const res = JSON.parse(responseText);
                    if (res.status === "success") {
                        alert("Event berhasil dihapus!");
                        location.reload();
                    } else {
                        alert("Gagal menghapus event: " + (res.message || "Terjadi kesalahan"));
                    }
                } catch (e) {
                    alert("Error: Tidak dapat memproses response");
                }
            },
            error: function (xhr) {
                let errorMsg = "Gagal menghapus event";
                try {
                    const res = JSON.parse(xhr.responseText);
                    errorMsg = res.message || errorMsg;
                } catch (e) { }
                alert(errorMsg);
            }
        });
    }

    function deleteThread(threadId, threadTitle) {
        if (!confirm(`Tutup thread?\n\nThread akan ditandai sebagai "Closed" dan tidak dapat dibuka kembali.`)) {
            return;
        }

        let baseUrl = API_ADDRESS.replace(/\/$/, "");
        $.ajax({
            url: baseUrl + "/THREAD/",
            type: "DELETE",
            data: JSON.stringify({ id: threadId }),
            contentType: "application/json",
            dataType: "text",
            success: function (responseText) {
                try {
                    const res = JSON.parse(responseText);
                    if (res.status === "success") {
                        alert("Topik berhasil dihapus!");
                        location.reload();
                    } else {
                        alert("Gagal menghapus thread: " + (res.message || "Terjadi kesalahan"));
                    }
                } catch (e) {
                    alert("Error: Tidak dapat memproses response");
                }
            },
            error: function (xhr) {
                let errorMsg = "Gagal menghapus thread";
                try {
                    const res = JSON.parse(xhr.responseText);
                    errorMsg = res.message || errorMsg;
                } catch (e) { }
                alert(errorMsg);
            }
        });
    }
</script>

</html>