
<?php
// dashboard.php

include 'includes/cek_session.php';
include 'config/koneksi.php';

// Hitung jumlah siswa
$query_siswa = mysqli_query(
    $koneksi,
    "SELECT COUNT(*) AS total FROM t_siswa"
);

if (!$query_siswa) {
    die("Query siswa gagal: " . mysqli_error($koneksi));
}

$total_siswa = mysqli_fetch_assoc($query_siswa)['total'];

// Hitung jumlah guru
$query_guru = mysqli_query(
    $koneksi,
    "SELECT COUNT(*) AS total FROM t_guru"
);

if (!$query_guru) {
    die("Query guru gagal: " . mysqli_error($koneksi));
}

$total_guru = mysqli_fetch_assoc($query_guru)['total'];

// Hitung jumlah kelas
$query_kelas = mysqli_query(
    $koneksi,
    "SELECT COUNT(*) AS total FROM t_kelas"
);

if (!$query_kelas) {
    die("Query kelas gagal: " . mysqli_error($koneksi));
}

$total_kelas = mysqli_fetch_assoc($query_kelas)['total'];

$nama_pengguna = $_SESSION['name'] ?? 'Pengguna';
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Dashboard - Pelanggaran Siswa</title>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css"
          rel="stylesheet">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap"
          rel="stylesheet">

    <!-- CSS Utama -->
    <link rel="stylesheet" href="layout/style.css">

    <style>
        /* LAYOUT UTAMA */
        .app-layout {
            display: flex;
            align-items: stretch;
            width: 100%;
            min-height: 100vh;
        }

        /* SIDEBAR KIRI */
        .app-layout > .sidebar {
            flex: 0 0 260px;
            width: 260px;
            min-height: 100vh;
        }

        /* KONTEN DASHBOARD */
        .content-area {
            flex: 1 1 0;
            min-width: 0;
            padding: 35px;
            min-height: 100vh;
            background: #f4f6fb;
        }

        /* HEADER */
        .dashboard-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 15px;
            margin-bottom: 30px;
        }

        .dashboard-subtitle {
            color: #3659d9;
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 2px;
            text-transform: uppercase;
            margin-bottom: 8px;
        }

        .dashboard-title {
            color: #17233f;
            font-size: 30px;
            font-weight: 700;
            margin: 0 0 8px;
        }

        .dashboard-description {
            color: #778198;
            font-size: 13px;
            margin: 0;
        }

        .user-greeting {
            padding: 12px 18px;
            background: #fff;
            border: 1px solid #e8ecf4;
            border-radius: 12px;
            color: #17233f;
            font-size: 13px;
            font-weight: 600;
        }

        /* KARTU STATISTIK */
        .stat-card {
            height: 100%;
            padding: 25px;
            border: 1px solid #e8ecf4;
            border-radius: 18px;
            background: #fff;
            box-shadow: 0 8px 25px rgba(23, 35, 63, 0.04);
            transition: transform .2s ease, box-shadow .2s ease;
        }

        .stat-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 28px rgba(23, 35, 63, 0.08);
        }

        .stat-top {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 10px;
            margin-bottom: 22px;
        }

        .stat-label {
            color: #778198;
            font-size: 13px;
            font-weight: 500;
            margin: 0;
        }

        .stat-icon {
            display: flex;
            justify-content: center;
            align-items: center;
            width: 48px;
            height: 48px;
            border-radius: 14px;
            font-size: 22px;
        }

        .icon-siswa {
            color: #3659d9;
            background: #edf1ff;
        }

        .icon-guru {
            color: #13845b;
            background: #e5f7ef;
        }

        .icon-kelas {
            color: #c07813;
            background: #fff3df;
        }

        .stat-number {
            color: #17233f;
            font-size: 34px;
            font-weight: 700;
            line-height: 1.2;
            margin: 0 0 8px;
        }

        .stat-caption {
            color: #929bb0;
            font-size: 12px;
            margin: 0;
        }

        /* BAGIAN SELANJUTNYA */
        .welcome-card {
            padding: 28px;
            margin-top: 10px;
            border: 1px solid #e8ecf4;
            border-radius: 18px;
            background: #fff;
        }

        .welcome-title {
            color: #17233f;
            font-size: 18px;
            font-weight: 700;
            margin-bottom: 10px;
        }

        .welcome-text {
            color: #778198;
            font-size: 13px;
            line-height: 1.9;
            margin-bottom: 20px;
        }

        .dashboard-link {
            display: inline-block;
            padding: 10px 16px;
            margin: 0 8px 8px 0;
            border-radius: 9px;
            background: #edf1ff;
            color: #3659d9;
            text-decoration: none;
            font-size: 12px;
            font-weight: 600;
            transition: .2s;
        }

        .dashboard-link:hover {
            background: #3659d9;
            color: #fff;
        }

        .dashboard-footer {
            padding-top: 28px;
            text-align: center;
            color: #929bb0;
            font-size: 11px;
        }

        /* RESPONSIF */
        @media (max-width: 768px) {
            .app-layout > .sidebar {
                flex-basis: 220px;
                width: 220px;
            }

            .content-area {
                padding: 20px 12px;
            }

            .dashboard-title {
                font-size: 24px;
            }

            .stat-card {
                padding: 18px;
            }

            .stat-number {
                font-size: 28px;
            }

            .welcome-card {
                padding: 20px;
            }
        }
    </style>
</head>

<body>

<div class="app-layout">

    <!-- SIDEBAR -->
    <?php include 'layout/sidebar.php'; ?>

    <!-- KONTEN UTAMA -->
    <main class="content-area">

        <!-- HEADER DASHBOARD -->
        <header class="dashboard-header">

            <div>
                <div class="dashboard-subtitle">
                    Sistem Informasi Sekolah
                </div>

                <h1 class="dashboard-title">
                    Dashboard
                </h1>

                <p class="dashboard-description">
                    Ringkasan data sistem pelanggaran siswa.
                </p>
            </div>

            <div class="user-greeting">
                Halo, <?= htmlspecialchars($nama_pengguna) ?>!
            </div>

        </header>

        <!-- KARTU STATISTIK -->
        <div class="row g-4 mb-4">

            <!-- DATA SISWA -->
            <div class="col-12 col-md-6 col-xl-4">
                <div class="stat-card">

                    <div class="stat-top">
                        <p class="stat-label">Total Data Siswa</p>

                        <div class="stat-icon icon-siswa">
                            <svg xmlns="http://www.w3.org/2000/svg"
                                 width="25" height="25"
                                 fill="none" viewBox="0 0 24 24"
                                 stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round"
                                      stroke-linejoin="round"
                                      d="M16 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2m6-10a4 4 0 1 0 0-8 4 4 0 0 0 0 8Zm10 10v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75"/>
                            </svg>
                        </div>
                    </div>

                    <h2 class="stat-number">
                        <?= number_format((int)$total_siswa, 0, ',', '.') ?>
                    </h2>

                    <p class="stat-caption">
                        Jumlah seluruh data siswa
                    </p>

                </div>
            </div>

            <!-- DATA GURU -->
            <div class="col-12 col-md-6 col-xl-4">
                <div class="stat-card">

                    <div class="stat-top">
                        <p class="stat-label">Total Data Guru</p>

                        <div class="stat-icon icon-guru">
                            <svg xmlns="http://www.w3.org/2000/svg"
                                 width="25" height="25"
                                 fill="none" viewBox="0 0 24 24"
                                 stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round"
                                      stroke-linejoin="round"
                                      d="M12 14a4 4 0 1 0 0-8 4 4 0 0 0 0 8Zm-7 7v-2a7 7 0 0 1 14 0v2M3 4h18M5 4v3m14-3v3"/>
                            </svg>
                        </div>
                    </div>

                    <h2 class="stat-number">
                        <?= number_format((int)$total_guru, 0, ',', '.') ?>
                    </h2>

                    <p class="stat-caption">
                        Jumlah seluruh data guru
                    </p>

                </div>
            </div>

            <!-- DATA KELAS -->
            <div class="col-12 col-md-6 col-xl-4">
                <div class="stat-card">

                    <div class="stat-top">
                        <p class="stat-label">Total Data Kelas</p>

                        <div class="stat-icon icon-kelas">
                            <svg xmlns="http://www.w3.org/2000/svg"
                                 width="25" height="25"
                                 fill="none" viewBox="0 0 24 24"
                                 stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round"
                                      stroke-linejoin="round"
                                      d="M3 21V5l9-3 9 3v16M3 21h18M9 21v-5h6v5M7 8h2m6 0h2M7 12h2m6 0h2"/>
                            </svg>
                        </div>
                    </div>

                    <h2 class="stat-number">
                        <?= number_format((int)$total_kelas, 0, ',', '.') ?>
                    </h2>

                    <p class="stat-caption">
                        Jumlah seluruh data kelas
                    </p>

                </div>
            </div>

        </div>

        <!-- KARTU SELAMAT DATANG -->
        <section class="welcome-card">

            <h2 class="welcome-title">
                Selamat Datang di Sistem Pelanggaran Siswa
            </h2>

            <p class="welcome-text">
                Gunakan menu di sebelah kiri untuk mengelola data sekolah.
                Kamu dapat membuka data siswa, guru, kelas, tahun ajaran,
                serta fitur lain sesuai hak akses akun yang digunakan.
            </p>

            <?php if (($_SESSION['role'] ?? '') === 'admin') { ?>

                <a href="kelola_siswa.php" class="dashboard-link">
                    Data Siswa
                </a>

                <a href="kelola_guru.php" class="dashboard-link">
                    Data Guru
                </a>

                <a href="kelola_kelas.php" class="dashboard-link">
                    Data Kelas
                </a>

                <a href="kelola_tahun_ajaran.php" class="dashboard-link">
                    Tahun Ajaran
                </a>

                <a href="kelola_walikelas.php" class="dashboard-link">
                    Wali Kelas
                </a>

                <a href="penempatan_siswa.php" class="dashboard-link">
                    Penempatan Siswa
                </a>

            <?php } else { ?>

                <a href="catat_pelanggaran.php" class="dashboard-link">
                    Catat Pelanggaran
                </a>

                <a href="riwayat.php" class="dashboard-link">
                    Riwayat Pelanggaran
                </a>

                <a href="Rekap.php" class="dashboard-link">
                    Rekap Poin
                </a>

            <?php } ?>

        </section>

        <!-- FOOTER -->
        <footer class="dashboard-footer">
            Sistem Informasi Pelanggaran Siswa
        </footer>

    </main>
    <!-- AKHIR KONTEN UTAMA -->

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
