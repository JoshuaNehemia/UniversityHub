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
                    <button>edit</button>
                </div>
            </section>

            <section id="tabs">
                <a href="#" data-tab="member" class="tab-link active">Member</a>
                <a href="#" data-tab="event" class="tab-link">Event</a>
            </section>

            <section id="event-group" class="hidden">
                <h3>Daftar Event</h3>
                <div id="list-event-container">

                </div>
            </section>


            <section id="member-group">
                <h3>Daftar Member</h3>
                <div id="list-member-container">

                </div>
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

        checkLoggedIn();
        getGroupDetail(idgroup);
        getGroupMember(idgroup);
        getGroupEvent(idgroup);
    });
</script>

</html>