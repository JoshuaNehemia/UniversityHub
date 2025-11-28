<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- SEO Title -->
    <title>University Hub | Portal Login Mahasiswa & Staf</title>

    <!-- SEO Description -->
    <meta name="description"
        content="Masuk ke University Hub untuk mengakses informasi akademik, grup mahasiswa, layanan kampus, dan sistem administrasi universitas.">

    <!-- SEO Keywords -->
    <meta name="keywords"
        content="University Hub, login mahasiswa, portal kampus, sistem akademik, universitas, login staf">

    <!-- Canonical URL -->
    <link rel="canonical" href="http://localhost/UniversityHub/login">

    <!-- Open Graph for Social Sharing -->
    <meta property="og:title" content="University Hub | Login">
    <meta property="og:description"
        content="Masuk ke akun University Hub Anda untuk mengelola aktivitas akademik dan layanan kampus.">
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
            "name": "University Hub Login",
            "description": "Halaman login untuk portal mahasiswa dan staf University Hub.",
            "url": "http://localhost/UniversityHub/login"
        }
    </script>

    <!-- Scripts -->
    <script src="SCRIPTS/jquery_3_7_1.js"></script>
    <script src="SCRIPTS/config.js"></script>
    <script src="SCRIPTS/auth.js"></script>

    <style>
        .success{
            color:green;
        }
        .error{
            color:red;
        }
    </style>
</head>

<body>
    <main>
        <h1>University Hub</h1>
        <h2>Masuk ke Akun</h2>

        <form id="form-login">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" placeholder="Masukkan username Anda" required>

            <label for="password">Password:</label>
            <input type="password" id="password" name="password" placeholder="Masukkan password Anda" required>

            <input type="submit" id="submit" value="Masuk">
        </form>
    </main>
    <div id="status-message"></div>
    <footer>
        © 2025 UniversityHub — Projek Mata Kuliah Full Stack Programming Universitas Surabaya.
    </footer>
</body>
<script>
    $(document).ready(function() {
        console.log("Dokumen siap");

        $('#form-login').on('submit', function(e) {
            e.preventDefault();
            var formData = $(this).serialize();
            login(formData);
        });
    });
</script>


</html>