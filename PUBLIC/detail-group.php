<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Detail Group | University Hub</title>

    <meta name="description" content="Lihat detail grup, daftar member, dan event yang tersedia di University Hub.">
    <script src="SCRIPTS/jquery_3_7_1.js"></script>
    <script src="SCRIPTS/config.js"></script>
    <script src="SCRIPTS/auth.js"></script>
    <script src="SCRIPTS/group.js"></script>
    <link rel="stylesheet" href="STYLES/main.css">
    <style>
        .hidden {
            display: none;
        }

        .tab-link.active {
            font-weight: bold;
            text-decoration: underline;
        }
    </style>
</head>

<body>
    <header>
        <h1>University Hub</h1>
    </header>

    <div class="container-layout">
        <aside>
            <nav>
                <a href="profil.php">Profil</a>
                <a href="group.php">Group</a>
                <a href="thread.php">Thread</a>
                <a href="event.php">Event</a>
            </nav>
        </aside>

        <main>
            <section id="detail-group">
                <h2 id="nama-group">Loading...</h2>
                <p><strong>Deskripsi:</strong> <span id="deskripsi-group">-</span></p>
                <p><strong>Dibuat oleh:</strong> <span id="pembuat-group">-</span></p>
                <p><small id="tanggal-pembentukan-group"></small></p>
                <div class="dosen_only">
                    <button id="btn-edit-group">Edit</button>
                    <button type="button" id="btn-add-member" style="margin-top:15px;">
                        Tambah Member
                    </button>
                    <button type="button" id="btn-add-event" style="margin-top:15px;">
                        Tambah Event
                    </button>
                </div>

            </section>

            <section id="tabs">
                <a data-tab="member" class="tab-link active">Member</a>
                <a data-tab="event" class="tab-link">Event</a>
            </section>

            <section id="event-group" class="hidden">
                <h3>Daftar Event</h3>
                <div id="list-event-container"></div>
                <div id="event-pagination" class="pagination"></div>
            </section>

            <section id="member-group">
                <h3>Daftar Member</h3>
                <div id="list-member-container"></div>
            </section>
        </main>
    </div>

    <div id="status-message"></div>

    <footer>
        © 2025 UniversityHub — Projek Mata Kuliah Full Stack Programming Universitas Surabaya.
    </footer>
</body>

<script>
    $(document).ready(function() {
        const urlParams = new URLSearchParams(window.location.search);
        const idgroup = urlParams.get("idgroup");
        const initialTab = urlParams.get("tabs") || "member";

        if (!idgroup) {
            $("#status-message").html("<b style='color:red'>Error: ID Group tidak ditemukan.</b>");
            return;
        }

        let limit = 5;
        let offsetMember = 0;
        let offsetEvent = 0;

        console.log("Dokumen siap");

        // Initialize tabs
        initTabs(initialTab);

        // Load data
        getGroupDetail(idgroup);
        getGroupMember(idgroup);
        getGroupEvent(idgroup);
        checkLoggedIn();
    });


    function initTabs(defaultTab = "member") {
        $(".tab-link").on("click", function(e) {
            e.preventDefault();

            // Tab active handling
            $(".tab-link").removeClass("active");
            $(this).addClass("active");

            const tab = $(this).data("tab");

            // Hide all sections
            $("#member-group, #event-group").addClass("hidden");

            // Show selected tab
            if (tab === "member") {
                $("#member-group").removeClass("hidden");
            } else if (tab === "event") {
                $("#event-group").removeClass("hidden");
            }
        });

        $(".tab-link[data-tab='" + defaultTab + "']").click();
    }

    $(document).on("click", ".btn-remove-event", function() {
        const eventId = $(this).data("id");
        const urlParams = new URLSearchParams(window.location.search);
        const idgroup = urlParams.get("idgroup");

        if (confirm("Yakin menghapus event ini?")) {
            removeEvent(idgroup, eventId);
        }
    });

    $(document).on("click", ".btn-remove-member", function() {
        const username = $(this).data("username");
        const urlParams = new URLSearchParams(window.location.search);
        const idgroup = urlParams.get("idgroup");

        if (confirm("Yakin menghapus member ini?")) {
            removeMember(idgroup, username);
        }
    });

    $("#btn-edit-group").on("click", function() {
        const urlParams = new URLSearchParams(window.location.search);
        const idgroup = urlParams.get("idgroup");
        window.location.href = "edit-group.php?idgroup=" + idgroup;
    });

    $("#btn-add-member").on("click", function() {
        const urlParams = new URLSearchParams(window.location.search);
        const idgroup = urlParams.get("idgroup");
        window.location.href = "add-member.php?idgroup=" + idgroup;
    });

    $("#btn-add-event").on("click", function() {
        const urlParams = new URLSearchParams(window.location.search);
        const idgroup = urlParams.get("idgroup");
        window.location.href = "add-event.php?idgroup=" + idgroup;
    });

    $(document).on("click", ".btn-edit-event", function() {
        const urlParams = new URLSearchParams(window.location.search);
        const idgroup = urlParams.get("idgroup");
        const idevent = $(this).data("id");

        if (!idevent) {
            console.error("ID event tidak ditemukan di atribut data-id.");
            return;
        }
        window.location.href = "edit-event.php?idevent=" + idevent + "&idgroup="+idgroup;
    });
</script>

</html>