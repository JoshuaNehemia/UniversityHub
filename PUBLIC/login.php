<?php
$bgimage = "IMAGES/ubaya.jpg";

?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- SEO Title -->
    <title>Login | University Hub</title>

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

    <!-- CSS External -->
    <link rel="stylesheet" href="STYLES/root.css">
    <link rel="stylesheet" href="STYLES/main.css">
    <link rel="stylesheet" href="STYLES/form.css">

    <!-- Scripts -->
    <script src="SCRIPTS/jquery_3_7_1.js"></script>
    <script src="SCRIPTS/config.js"></script>
    <script src="SCRIPTS/auth.js"></script>
</head>

<body>
    <main class="login-container">

        <!-- Left side image -->
        <section class="login-image" style="background-image:
            linear-gradient(var(--login-image-overlay), var(--login-image-overlay)),
            url('<?php echo $bgimage ?>');">
        </section>

        <!-- Right form -->
        <section class="login-form-wrapper">

            <div class="login-form-container">
                <h1>University Hub</h1>
                <h2>Masuk ke Akun</h2>

                <form id="form-login">

                    <div class="form-group">
                        <label for="username" class="form-label">Username</label>
                        <input type="text" id="username" name="username" class="form-control"
                            placeholder="Masukkan username Anda" required>
                    </div>

                    <div class="form-group">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" id="password" name="password" class="form-control"
                            placeholder="Masukkan password Anda" required>
                    </div>

                    <div class="remember-me">
                        <input type="checkbox" id="remember" name="remember">
                        <label for="remember">Ingat saya</label>
                    </div>

                    <button type="submit" class="btn btn-primary login-btn">Masuk</button>
                </form>

                <div id="status-message"></div>

            </div>

        </section>

    </main>

    <footer class="footer-login">
        © 2025 UniversityHub — Projek Mata Kuliah Full Stack Programming Universitas Surabaya.
    </footer>
</body>
<script>
    $(document).ready(function () {
        console.log("Dokumen siap");

        $('#form-login').on('submit', function (e) {
            e.preventDefault();
            var formData = $(this).serialize();
            login(formData);
        });
    });
</script>


</html>