
<?php
session_start();
include "config/koneksi.php";

$nama = "Nazril Ilham";
$kelas = "XII RPL 2";
$sekolah = "SMK Muhammadiyah Tasikmalaya";
$jurusan = "Rekayasa Perangkat Lunak";
$hobi = "main bola";
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>About Me | Nazril Ilham</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css"
          rel="stylesheet">

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap"
          rel="stylesheet">

    <link rel="stylesheet" href="layout/style.css">

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: 'Poppins', sans-serif;
            background: #f5f7fb;
        }

        /* Layout utama: sidebar kiri, konten kanan */
        .about-layout {
            display: flex;
            flex-direction: row;
            align-items: stretch;
            min-height: 100vh;
            width: 100%;
        }

        /* Sidebar */
        .about-sidebar {
            flex: 0 0 250px;
            width: 250px;
            min-width: 250px;
            min-height: 100vh;
            background: #212529;
        }

        .about-sidebar > .col-md-3,
        .about-sidebar > .col-lg-2 {
            width: 100%;
            max-width: 100%;
            flex: 0 0 100%;
        }

        /* Konten kanan */
        .about-content {
            flex: 1;
            min-width: 0;
            padding: 30px;
        }

        .page-header {
            margin-bottom: 25px;
        }

        .page-title {
            font-size: 28px;
            font-weight: 700;
            color: #1f2937;
        }

        .profile-card,
        .info-card,
        .description-card {
            background: #fff;
            border-radius: 14px;
            padding: 24px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.04);
        }

        .profile-card {
            text-align: center;
            margin-bottom: 30px;
        }

        .profile-photo-wrapper {
            margin-bottom: 18px;
        }

        .profile-photo {
            width: 125px;
            height: 125px;
            object-fit: cover;
            border-radius: 50%;
            border: 4px solid #e8efff;
        }

        .profile-name {
            font-size: 25px;
            font-weight: 700;
            color: #1f2937;
        }

        .profile-role {
            color: #2563eb;
            font-weight: 500;
            margin-bottom: 15px;
        }

        .profile-description {
            max-width: 750px;
            margin: 0 auto 20px;
            color: #6b7280;
            line-height: 1.8;
        }

        .profile-divider {
            border-color: #e5e7eb;
            margin: 20px 0;
        }

        .back-button {
            display: inline-block;
            background: #2563eb;
            color: #fff;
            text-decoration: none;
            padding: 10px 20px;
            border-radius: 8px;
            font-size: 14px;
        }

        .back-button:hover {
            background: #1d4ed8;
            color: #fff;
        }

        .section-title {
            font-size: 19px;
            font-weight: 600;
            margin-bottom: 18px;
            color: #1f2937;
        }

        .info-card {
            height: 100%;
            padding: 20px;
        }

        .info-label {
            font-size: 11px;
            font-weight: 600;
            color: #9ca3af;
            margin-bottom: 8px;
        }

        .info-value {
            font-size: 15px;
            font-weight: 500;
            color: #1f2937;
            overflow-wrap: anywhere;
        }

        .description-card {
            height: 100%;
            line-height: 1.8;
            color: #6b7280;
        }

        .skill-item {
            display: inline-block;
            background: #eff6ff;
            color: #2563eb;
            padding: 7px 12px;
            margin: 0 5px 10px 0;
            border-radius: 7px;
            font-size: 12px;
            font-weight: 500;
        }

        /* Tampilan HP */
        @media (max-width: 767px) {
            .about-layout {
                flex-direction: column;
            }

            .about-sidebar {
                width: 100%;
                min-width: 0;
                min-height: auto;
                flex-basis: auto;
            }

            .about-content {
                width: 100%;
                padding: 18px;
            }

            .page-title {
                font-size: 23px;
            }
        }
    </style>
</head>

<body>

<div class="about-layout">

    <!-- SIDEBAR KIRI -->
    <aside class="about-sidebar">
        <?php include 'layout/sidebar.php'; ?>
    </aside>

    <!-- KONTEN KANAN -->
    <main class="about-content">

        <div class="page-header">
            <h1 class="page-title">Tentang Saya</h1>
            <p class="text-secondary small mb-0">
                Mengenal lebih dekat dengan saya.
            </p>
        </div>

        <!-- PROFIL -->
        <section class="profile-card">

            <div class="profile-photo-wrapper">
                <img
                    src="pppnazril.jpeg"
                    alt="Foto Nazril Ilham"
                    class="profile-photo"
                    onerror="this.style.display='none';"
                >
            </div>

            <h2 class="profile-name">
                <?php echo htmlspecialchars($nama); ?>
            </h2>

            <div class="profile-role">
                <?php echo htmlspecialchars($jurusan); ?>
            </div>

            <p class="profile-description">
                Halo, saya <?php echo htmlspecialchars($nama); ?>,
                seorang pelajar kelas
                <?php echo htmlspecialchars($kelas); ?> di
                <?php echo htmlspecialchars($sekolah); ?>.
                Saya memiliki ketertarikan pada teknologi,
                pengembangan website, dan desain antarmuka.
                Saya terus belajar untuk meningkatkan kemampuan
                serta menciptakan website yang bermanfaat,
                responsif, dan mudah digunakan.
            </p>

            <hr class="profile-divider">

            <a href="dashboard.php" class="back-button">
                Kembali ke Dashboard
            </a>

        </section>

        <!-- INFORMASI PERSONAL -->
        <section class="mb-4">

            <h3 class="section-title">Informasi Personal</h3>

            <div class="row g-3">

                <div class="col-sm-6 col-xl-4">
                    <div class="info-card">
                        <div class="info-label">NAMA LENGKAP</div>
                        <div class="info-value">
                            <?php echo htmlspecialchars($nama); ?>
                        </div>
                    </div>
                </div>

                <div class="col-sm-6 col-xl-4">
                    <div class="info-card">
                        <div class="info-label">KELAS</div>
                        <div class="info-value">
                            <?php echo htmlspecialchars($kelas); ?>
                        </div>
                    </div>
                </div>

                <div class="col-sm-6 col-xl-4">
                    <div class="info-card">
                        <div class="info-label">HOBI</div>
                        <div class="info-value">
                            <?php echo htmlspecialchars($hobi); ?>
                        </div>
                    </div>
                </div>

                <div class="col-sm-6 col-xl-6">
                    <div class="info-card">
                        <div class="info-label">ASAL SEKOLAH</div>
                        <div class="info-value">
                            <?php echo htmlspecialchars($sekolah); ?>
                        </div>
                    </div>
                </div>

                <div class="col-sm-6 col-xl-6">
                    <div class="info-card">
                        <div class="info-label">JURUSAN</div>
                        <div class="info-value">
                            <?php echo htmlspecialchars($jurusan); ?>
                        </div>
                    </div>
                </div>

            </div>
        </section>

        <!-- DESKRIPSI DAN KEAHLIAN -->
        <section class="row g-3">

            <div class="col-lg-7">
                <div class="description-card">
                    <h3 class="section-title">Deskripsi Diri</h3>

                    <p>
                        Saya adalah seorang pelajar yang sedang
                        mendalami Rekayasa Perangkat Lunak. Saya
                        senang mempelajari teknologi, memahami
                        cara kerja website, dan mencoba hal-hal
                        baru dalam pemrograman.
                    </p>

                    <p class="mt-3">
                        Saya ingin terus mengembangkan kemampuan
                        di bidang web development dan bercita-cita
                        menjadi seorang Web Developer yang mampu
                        menghasilkan karya digital berkualitas.
                    </p>
                </div>
            </div>

            <div class="col-lg-5">
                <div class="description-card">
                    <h3 class="section-title">Bidang yang Dipelajari</h3>

                    <span class="skill-item">HTML</span>
                    <span class="skill-item">CSS</span>
                    <span class="skill-item">PHP</span>
                    <span class="skill-item">Bootstrap</span>
                    <span class="skill-item">MySQL</span>
                    <span class="skill-item">Web Development</span>

                    <hr style="border-color:#e8ecf4; margin:16px 0;">

                    <p>
                        Berfokus pada pengembangan website dengan
                        struktur yang rapi, desain yang konsisten,
                        dan pengalaman pengguna yang baik.
                    </p>
                </div>
            </div>

        </section>

    </main>

</div>

</body>
</html>



        </main>
    </div>
</div>

</body>
</html>
