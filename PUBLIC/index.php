<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- SEO Title -->
    <title>University Hub | Portal Mahasiswa Dosen</title>

    <!-- SEO Description -->
    <meta name="description"
        content="University Hub untuk informasi akademik, grup mahasiswa, layanan kampus, dan sistem administrasi universitas.">

    <!-- SEO Keywords -->
    <meta name="keywords"
        content="University Hub, mahasiswa, portal kampus, sistem akademik, universitas, staf">

    <!-- Canonical URL -->
    <link rel="canonical" href="http://localhost/UniversityHub/login">

    <!-- Open Graph for Social Sharing -->
    <meta property="og:title" content="University Hub | Login">
    <meta property="og:description"
        content="University Hub mengelola aktivitas akademik dan layanan kampus.">
    <meta property="og:type" content="website">
    <meta property="og:url" content="http://localhost/UniversityHub/">
    <meta property="og:image" content="http://localhost/UniversityHub/PUBLIC/ASSETS/IMAGES/universityhub-banner.png">

    <!-- Twitter Card -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="University Hub | Login">
    <meta name="twitter:description" content="Masuk ke akun University Hub Anda.">
    <meta name="twitter:image" content="http://localhost/UniversityHub/PUBLIC/ASSETS/IMAGES/universityhub-banner.png">

    <!-- Schema.org JSON-LD Structured Data -->
    <script type="application/ld+json">
        {
            "@context": "https://schema.org",
            "@type": "WebPage",
            "name": "University Hub",
            "description": "Halaman utama untuk portal mahasiswa dan dosen University Hub.",
            "url": "http://localhost/UniversityHub/login"
        }
    </script>

    <!-- Scripts -->
    <script src="SCRIPTS/jquery_3_7_1.js"></script>
    <script src="SCRIPTS/config.js"></script>
    <script src="SCRIPTS/auth.js"></script>
</head>

<body>
    <header>
        <h1>University Hub</h1>
    </header>
    <aside>
        <nav>
            <a href="profil.php">Profil</a>
            <a href="group.php">Group</a>
            <a href="thread.php">Thread</a>
            <a href="event.php">Event</a>
        </nav>
    </aside>
    <main>
        <h2>Daftar Group</h2>
        <section id="daftar-group">
        </section>
    </main>
    <div id="status-message"></div>
    <footer>
        © 2025 UniversityHub — Projek Mata Kuliah Full Stack Programming Universitas Surabaya.
    </footer>
</body>
<script>
    $(document).ready(function() {
        console.log("Dokumen siap");
        checkLoggedIn();
        getGroupJoinedByUser();
    });
</script>
</html>