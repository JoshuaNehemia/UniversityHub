<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Thread | University Hub</title>
    <link rel="stylesheet" href="STYLES/root.css">
    <link rel="stylesheet" href="STYLES/main.css">
    <link rel="stylesheet" href="STYLES/form.css">
    <script src="SCRIPTS/jquery_3_7_1.js"></script>
    <script src="SCRIPTS/config.js"></script>
    <script src="SCRIPTS/auth.js"></script>

    <style>
        /* =====================================================
   HARD RWD OVERRIDE — DETAIL THREAD
   NO HTML / NO JS CHANGE
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
   THREAD HEADER FIX
================================ */
        .card>div[style*="display: flex"] {
            flex-wrap: wrap !important;
            gap: 12px;
        }

        #thread-status-container {
            flex-shrink: 0;
        }

        #btn-delete-thread {
            flex-shrink: 0;
        }

        /* Mobile: stack header content */
        @media (max-width: 768px) {
            .card>div[style*="display: flex"] {
                flex-direction: column !important;
                align-items: flex-start !important;
            }

            #btn-delete-thread {
                width: 100%;
                text-align: center;
            }
        }

        /* ===============================
   CHAT BOX — NO SIDE SCROLL
================================ */
        #chat-box {
            width: 100%;
            max-width: 100%;
            overflow-x: hidden !important;
            overflow-y: auto;
            word-break: break-word;
        }

        .chat-message {
            width: 100%;
            max-width: 100%;
            word-break: break-word;
        }

        /* Prevent flex overflow */
        .chat-message * {
            min-width: 0;
        }

        /* ===============================
   CHAT FORM — MOBILE SAFE
================================ */
        #form-chat {
            display: flex;
            gap: 10px;
        }

        @media (max-width: 576px) {
            #form-chat {
                flex-direction: column;
                gap: 8px;
            }

            #form-chat input,
            #form-chat button {
                width: 100%;
            }
        }

        /* ===============================
   SIDEBAR & MAIN CONTENT
================================ */
        .dashboard-wrapper {
            max-width: 100%;
            overflow-x: hidden;
        }

        @media (max-width: 768px) {
            .sidebar {
                display: none;
            }

            .main-content {
                width: 100%;
                padding: 16px;
            }
        }

        /* ===============================
   BACK LINK
================================ */
        #back-link {
            flex-wrap: wrap;
            max-width: 100%;
        }

        /* ===============================
   TEXT SCALING (SMALL PHONE)
================================ */
        @media (max-width: 360px) {
            h2 {
                font-size: 1.2rem;
            }

            h3 {
                font-size: 1rem;
            }

            .chat-text {
                font-size: 0.85rem;
            }

            .chat-time {
                font-size: 0.7rem;
            }
        }

        /* ===============================
   TOUCH TARGET SAFETY
================================ */
        @media (pointer: coarse) {
            button {
                min-height: 40px;
            }
        }

        /* =====================================================
   CHAT BUBBLE UI — CLEAR DISTINCTION
===================================================== */

        /* Chat container */
        #chat-box {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        /* Base bubble */
        .chat-message {
            max-width: 78%;
            padding: 10px 14px;
            border-radius: 14px;
            border-left: none;
            box-shadow: none;
            background: var(--bg-card);
            word-break: break-word;
        }

        /* Username */
        .chat-username {
            font-size: 0.75rem;
            font-weight: 600;
            margin-bottom: 4px;
        }

        /* Message text */
        .chat-text {
            font-size: 0.9rem;
            line-height: 1.4;
        }

        /* Time */
        .chat-time {
            font-size: 0.65rem;
            margin-top: 6px;
            text-align: right;
            opacity: 0.7;
        }

        /* ===============================
   OTHER USER (LEFT)
================================ */
        .chat-message {
            align-self: flex-start;
            background: var(--bg-card);
            border-radius: 14px 14px 14px 4px;
        }

        /* ===============================
   OWN MESSAGE (RIGHT)
================================ */
        .chat-message-own {
            align-self: flex-end;
            background: color-mix(in srgb, var(--first-color) 18%, var(--bg-card));
            border-radius: 14px 14px 4px 14px;
        }

        /* Hide username for own message (optional UX) */
        .chat-message-own .chat-username {
            display: none;
        }

        /* ===============================
   MOBILE OPTIMIZATION
================================ */
        @media (max-width: 576px) {
            .chat-message {
                max-width: 92%;
            }
        }

        /* ===============================
   LONG CONTENT SAFETY
================================ */
        .chat-message * {
            min-width: 0;
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
                <a href="index.php" class="nav-link" style="padding-left: 50px;">Group Saya</a>
                <a href="group.php" class="nav-link active" style="padding-left: 50px;">Cari Group</a>
                <a href="profil.php" class="nav-link" style="padding-left: 50px;">Profil Akun</a>
            </nav>
        </aside>

        <main class="main-content">
            <div style="margin-bottom: 20px;">
                <a href="#" id="back-link"
                    style="display:inline-flex; align-items:center; gap:5px; color:var(--text-secondary);">
                    <span>&larr;</span> Kembali ke group
                </a>
            </div>

            <div id="loading-indicator" style="text-align:center; padding:50px;">
                <p class="text-muted">Memuat thread...</p>
            </div>
            <div id="error-message" style="text-align:center; padding:50px; display:none; color:red;"></div>

            <div id="thread-content" style="display:none; animation: fadeIn 0.3s;">
                <!-- Thread Header -->
                <div class="card" style="margin-bottom: 20px; border-left: 5px solid var(--first-color);">
                    <div style="display: flex; justify-content: space-between; align-items: start;">
                        <div style="flex: 1;">
                            <h2 id="thread-title" style="margin: 0 0 10px 0; color: var(--first-color);">Judul Thread
                            </h2>
                            <div style="color: var(--text-secondary); font-size: 0.9rem;">
                                👤 Oleh: <span id="thread-creator">-</span> •
                                📅 Dibuat pada: <span id="thread-date">-</span>
                            </div>
                        </div>
                        <div id="thread-status-container"></div>
                        <button id="btn-delete-thread" class="btn btn-outline"
                            style="display:none; color:#f44336; border-color:#f44336;">
                            🗑️ Hapus Thread
                        </button>
                    </div>
                </div>

                <!-- Chat Messages -->
                <div class="card" style="margin-bottom: 20px;">
                    <h3 style="margin: 0 0 15px 0;">Chat</h3>
                    <div id="chat-box" style="max-height: 500px; overflow-y: auto; margin-bottom: 15px;">
                        <p class="text-muted">Memuat chat...</p>
                    </div>

                    <!-- Add Comment Form -->
                    <form id="form-chat" style="display: flex; gap: 10px;">
                        <input type="text" id="chat-input" class="form-control" placeholder="Tulis pesan..."
                            autocomplete="off" required style="flex: 1;">
                        <button type="submit" class="btn btn-primary">Kirim</button>
                    </form>
                </div>
            </div>
        </main>
    </div>

</body>

<script>
    let currentThreadId = null;
    let currentGroupId = null;
    let currentUser = null;
    let threadCreator = null;

    $(document).ready(function () {
        $.ajax({
            url: API_ADDRESS + "AUTH/", type: "GET", data: { jenis: "account" }, dataType: "json",
            success: function (res) {
                if (res.status === "success") {
                    window.SESSION = res.data;
                    currentUser = res.data.username;
                    $("#display-name").text(res.data.nama);

                    const urlParams = new URLSearchParams(window.location.search);
                    currentThreadId = urlParams.get('id');
                    if (!currentThreadId) {
                        window.location.href = "index.php";
                        return;
                    }

                    loadThreadDetail(currentThreadId);
                    loadChatMessages(currentThreadId);

                    setInterval(() => loadChatMessages(currentThreadId), 3000);
                } else {
                    window.location.href = 'login.php';
                }
            },
            error: function () { window.location.href = 'login.php'; }
        });

        $("#form-chat").submit(function (e) {
            e.preventDefault();
            sendMessage();
        });

        $("#btn-delete-thread").click(function () {
            deleteThread();
        });
    });

    function loadThreadDetail(threadId) {
        let baseUrl = API_ADDRESS.replace(/\/$/, "");
        $.ajax({
            url: baseUrl + "/THREAD/",
            type: "GET",
            dataType: "text",
            data: { id: threadId },
            success: function (responseText) {
                $("#loading-indicator").hide();
                try {
                    const res = JSON.parse(responseText);
                    if (res.status !== "success" || !res.data) {
                        $("#error-message").show().text("Thread tidak ditemukan.");
                        return;
                    }

                    $("#thread-content").fadeIn();
                    const data = Array.isArray(res.data) ? res.data[0] : res.data;

                    $("#thread-title").text(data.judul || "Topik Diskusi");
                    $("#thread-creator").text(data.pembuat || "-");
                    $("#thread-date").text(data.tanggal_pembuatan || "-");

                    threadCreator = data.pembuat;
                    currentGroupId = data.idgrup;

                    if (currentGroupId) {
                        $("#back-link").attr("href", "detail-group.php?id=" + currentGroupId);
                    }

                    const isClosed = (data.status === "Close");
                    $("#thread-status-container").html(`
                        <span style="
                            padding: 4px 12px;
                            border-radius: 12px;
                            font-size: 0.75rem;
                            font-weight: 600;
                            background: ${isClosed ? 'var(--status-failed-bg)' : 'var(--status-success-bg)'};
                            color: ${isClosed ? 'var(--status-failed)' : 'var(--status-success)'};
                        ">
                            ${isClosed ? 'Closed' : 'Open'}
                        </span>
                    `);

                    const canDelete = (currentUser === threadCreator || window.SESSION.jenis === "ADMIN");

                    if (canDelete && !isClosed) {
                        $("#btn-delete-thread").show();
                    }

                    if (isClosed) {
                        $("#form-chat").hide();
                        $("#chat-box").after(
                            "<p class='text-muted'>Thread telah ditutup. Chat tidak dapat ditambahkan.</p>"
                        );
                    }

                } catch (err) {
                    console.error("Error parsing thread:", err);
                    $("#error-message").show().text("Error memuat thread.");
                }
            },
            error: function (xhr) {
                $("#loading-indicator").hide();
                $("#error-message").show().text("Error memuat thread.");
            }
        });
    }

    function loadChatMessages(threadId) {
        let baseUrl = API_ADDRESS.replace(/\/$/, "");
        $.ajax({
            url: baseUrl + "/CHAT/",
            type: "GET",
            dataType: "text",
            data: { idthread: threadId },
            success: function (responseText) {
                try {
                    const res = JSON.parse(responseText);
                    const chatBox = $("#chat-box");
                    chatBox.empty();

                    if (res.status === "success" && res.data && res.data.length > 0) {
                        res.data.forEach(chat => {
                            console.log(chat);
                            const isOwn = chat.pengirim === currentUser;
                            const messageClass = isOwn ? 'chat-message chat-message-own' : 'chat-message';

                            chatBox.append(`
                                <div class="${messageClass}">
                                    <div class="chat-username">${chat.nama_pengirim || chat.pengirim}</div>
                                    <div class="chat-text">${chat.isi || ''}</div>
                                    <div class="chat-time">${chat.tanggal_pembuatan || ''}</div>
                                </div>
                            `);
                        });

                        chatBox.scrollTop(chatBox[0].scrollHeight);
                    } else {
                        chatBox.html("<p class='text-muted'>Belum ada pesan. Kirimkan pesan Anda!</p>");
                    }
                } catch (err) {
                    console.error("Error parsing chat:", err);
                }
            },
            error: function (xhr) {
                console.error("Error loading chat:", xhr);
            }
        });
    }

    function sendMessage() {
        const message = $("#chat-input").val().trim();
        if (!message) return;

        let baseUrl = API_ADDRESS.replace(/\/$/, "");
        $.ajax({
            url: baseUrl + "/CHAT/",
            type: "POST",
            data: {
                idthread: currentThreadId,
                username: currentUser,
                isi: message
            },
            dataType: "text",
            success: function (responseText) {
                try {
                    const res = JSON.parse(responseText);
                    if (res.status === "success") {
                        $("#chat-input").val("");
                        loadChatMessages(currentThreadId);
                    } else {
                        alert("Gagal mengirim pesan: " + (res.message || "Terjadi kesalahan"));
                    }
                } catch (e) {
                    alert("Error: Tidak dapat memproses response");
                }
            },
            error: function (xhr) {
                alert("Gagal mengirim pesan");
            }
        });
    }

    function deleteThread() {
        if (!confirm(`Tutup thread?\n\nThread akan ditandai sebagai "Closed" dan tidak dapat dibuka kembali.`)) {
            return;
        }

        let baseUrl = API_ADDRESS.replace(/\/$/, "");
        $.ajax({
            url: baseUrl + "/THREAD/",
            type: "DELETE",
            data: JSON.stringify({ id: currentThreadId }),
            contentType: "application/json",
            dataType: "text",
            success: function (responseText) {
                try {
                    const res = JSON.parse(responseText);
                    if (res.status === "success") {
                        alert("Thread berhasil dihapus!");
                        window.location.href = "detail-group.php?id=" + currentGroupId;
                    } else {
                        alert("Gagal menghapus thread: " + (res.message || "Terjadi kesalahan"));
                    }
                } catch (e) {
                    alert("Error: Tidak dapat memproses response");
                }
            },
            error: function (xhr) {
                alert("Gagal menghapus thread");
            }
        });
    }
</script>

</html>