
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
            color: #1f2937;
        }

        /* LAYOUT UTAMA */
        .about-layout {
            display: flex;
            align-items: stretch;
            min-height: 100vh;
            width: 100%;
        }

        /* SIDEBAR */
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

        /* KONTEN UTAMA */
        .about-content {
            flex: 1;
            min-width: 0;
            padding: 25px 30px;
        }

        .page-header {
            margin-bottom: 22px;
        }

        .page-title {
            font-size: 28px;
            font-weight: 700;
            margin-bottom: 5px;
            color: #1f2937;
        }

        .page-subtitle {
            color: #64748b;
            font-size: 13px;
            margin: 0;
        }

        /* KARTU PROFIL */
        .profile-card {
            background: #ffffff;
            border: 1px solid #e5eaf3;
            border-radius: 18px;
            padding: 30px 28px;
            margin-bottom: 28px;
            text-align: center;
            box-shadow: 0 8px 25px rgba(31, 41, 55, 0.05);
        }

        /* LINGKARAN BIRU */
        .profile-photo-wrapper {
            width: 145px !important;
            height: 145px !important;
            margin: 0 auto 18px;
            display: flex;
            justify-content: center;
            align-items: center;
            border-radius: 50%;
            background: linear-gradient(135deg, #2563eb, #a5b4fc);
            box-shadow: 0 10px 25px rgba(37, 99, 235, 0.18);
            overflow: hidden;
            flex-shrink: 0;
        }

        /* FOTO MEMENUHI LINGKARAN */
        .profile-card .profile-photo {
            display: block;
            width: 137px !important;
            height: 137px !important;
            min-width: 137px !important;
            min-height: 137px !important;
            max-width: none !important;
            padding: 0 !important;
            margin: 0 !important;
            object-fit: cover !important;
            object-position: center 25%;
            border: 3px solid #ffffff !important;
            border-radius: 50% !important;
            flex-shrink: 0;
            background: #ffffff;
        }

        /* NAMA PROFIL */
        .profile-name {
            font-size: 26px;
            font-weight: 700;
            color: #1f2937;
            margin-bottom: 10px;
        }

        /* JURUSAN */
        .profile-role {
            display: inline-block;
            padding: 8px 16px;
            margin-bottom: 17px;
            border-radius: 30px;
            background: #eff4ff;
            color: #2563eb;
            font-size: 12px;
            font-weight: 600;
        }

        /* DESKRIPSI */
        .profile-description {
            max-width: 720px;
            margin: 0 auto 22px;
            color: #64748b;
            font-size: 13px;
            line-height: 1.9;
        }

        /* GARIS PEMBATAS */
        .profile-divider {
            width: 55px;
            height: 4px;
            margin: 0 auto 20px;
            border: 0;
            border-radius: 10px;
            background: #c7d2fe;
            opacity: 1;
        }

        /* TOMBOL DASHBOARD */
        .back-button {
            display: inline-flex;
            justify-content: center;
            align-items: center;
            background: #2563eb;
            color: #ffffff;
            text-decoration: none;
            padding: 12px 20px;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 600;
            transition: all 0.2s ease;
        }

        .back-button:hover {
            background: #1d4ed8;
            color: #ffffff;
            transform: translateY(-2px);
            box-shadow: 0 5px 12px rgba(37, 99, 235, 0.2);
        }

        /* JUDUL BAGIAN */
        .section-title {
            font-size: 18px;
            font-weight: 600;
            margin-bottom: 16px;
            color: #1f2937;
        }

        /* KARTU INFORMASI */
        .info-card {
            height: 100%;
            padding: 20px;
            background: #ffffff;
            border: 1px solid #e5eaf3;
            border-radius: 14px;
            box-shadow: 0 5px 16px rgba(31, 41, 55, 0.04);
            transition: transform 0.2s ease,
                        box-shadow 0.2s ease;
        }

        .info-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(31, 41, 55, 0.07);
        }

        .info-label {
            font-size: 10px;
            font-weight: 600;
            letter-spacing: 0.4px;
            color: #94a3b8;
            margin-bottom: 8px;
        }

        .info-value {
            font-size: 14px;
            font-weight: 500;
            color: #1f2937;
            overflow-wrap: anywhere;
        }

        /* KARTU DESKRIPSI DAN KEAHLIAN */
        .description-card {
            height: 100%;
            padding: 24px;
            background: #ffffff;
            border: 1px solid #e5eaf3;
            border-radius: 14px;
            box-shadow: 0 5px 16px rgba(31, 41, 55, 0.04);
            color: #64748b;
            font-size: 13px;
            line-height: 1.9;
        }

        .description-card p:last-child {
            margin-bottom: 0;
        }

        /* LABEL KEAHLIAN */
        .skill-item {
            display: inline-block;
            background: #eff6ff;
            color: #2563eb;
            padding: 8px 12px;
            margin: 0 5px 9px 0;
            border-radius: 8px;
            font-size: 12px;
            font-weight: 500;
        }

        /* TAMPILAN HP */
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

            .profile-card {
                padding: 25px 18px;
                border-radius: 15px;
            }

            .profile-photo-wrapper {
                width: 135px !important;
                height: 135px !important;
            }

            .profile-card .profile-photo {
                width: 127px !important;
                height: 127px !important;
                min-width: 127px !important;
                min-height: 127px !important;
            }

            .profile-name {
                font-size: 23px;
            }

            .profile-description {
                font-size: 12px;
            }

            .back-button {
                width: 100%;
            }

            .description-card {
                padding: 20px;
            }
        }
    </style>
</head>

<body>

<div class="about-layout">

    <!-- SIDEBAR -->
    <aside class="about-sidebar">
        <?php include 'layout/sidebar.php'; ?>
    </aside>

    <!-- KONTEN UTAMA -->
    <main class="about-content">

        <div class="page-header">
            <h1 class="page-title">Tentang Saya</h1>
            <p class="page-subtitle">
                Mengenal lebih dekat dengan saya.
            </p>
        </div>

        <!-- PROFIL UTAMA -->
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
                pemrograman serta menciptakan website yang
                bermanfaat, responsif, dan mudah digunakan.
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
        <section class="row g-3 mb-4">

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
                    <h3 class="section-title">
                        Bidang yang Dipelajari
                    </h3>

                    <span class="skill-item">HTML</span>
                    <span class="skill-item">CSS</span>
                    <span class="skill-item">PHP</span>
                    <span class="skill-item">Bootstrap</span>
                    <span class="skill-item">MySQL</span>
                    <span class="skill-item">Web Development</span>

                    <hr style="border-color: #e5eaf3; margin: 16px 0;">

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
