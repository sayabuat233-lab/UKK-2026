<?php
// dashboard_guru.php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include 'includes/cek_session.php';
include 'config/koneksi.php';

// Hanya guru & admin yang boleh akses
if (!in_array($_SESSION['role'] ?? '', ['guru', 'admin'])) {
    header('Location: index.php');
    exit;
}

// 1. Pelanggaran hari ini
$q_pelanggaran_hari_ini = mysqli_query($koneksi, "
    SELECT COUNT(*) AS total 
    FROM t_pelanggaran_siswa 
    WHERE DATE(tanggal) = CURDATE()
");
$pelanggaran_hari_ini = mysqli_fetch_assoc($q_pelanggaran_hari_ini)['total'] ?? 0;

// 2. Total pelanggaran tercatat
$q_total_pelanggaran = mysqli_query($koneksi, "SELECT COUNT(*) AS total FROM t_pelanggaran_siswa");
$total_pelanggaran = mysqli_fetch_assoc($q_total_pelanggaran)['total'] ?? 0;

// 3. Total poin — ambil dari tabel t_pelanggaran agar aman
$q_total_poin = mysqli_query($koneksi, "
    SELECT SUM(p.poin) AS total 
    FROM t_pelanggaran_siswa ps
    JOIN t_pelanggaran p ON ps.pelanggaran_id = p.id
");
$total_poin = mysqli_fetch_assoc($q_total_poin)['total'] ?? 0;

// 4. Siswa yang diperingatkan (total poin >= 3)
$q_siswa_diperingatkan = mysqli_query($koneksi, "
    SELECT COUNT(DISTINCT ps.siswa_id) AS total 
    FROM t_pelanggaran_siswa ps
    JOIN t_pelanggaran p ON ps.pelanggaran_id = p.id
    GROUP BY ps.siswa_id
    HAVING SUM(p.poin) >= 3
");
$siswa_diperingatkan = mysqli_num_rows($q_siswa_diperingatkan);

$nama_pengguna = $_SESSION['nama_lengkap'] ?? 'Guru';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Guru - Pelanggaran Siswa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="layout/style.css">
    <style>
        body { font-family: 'Poppins', sans-serif; background: #f4f6fb; }
        .app-layout { display: flex; min-height: 100vh; }
        .sidebar { flex: 0 0 260px; width: 260px; min-height: 100vh; }
        .content-area { flex: 1; padding: 35px; }
        .stat-card { background: #fff; border-radius: 18px; padding: 25px; border: 1px solid #e8ecf4; box-shadow: 0 8px 25px rgba(23,35,63,0.04); transition: .2s; }
        .stat-card:hover { transform: translateY(-4px); box-shadow: 0 12px 28px rgba(23,35,63,0.08); }
        .stat-number { font-size: 34px; font-weight: 700; color: #17233f; }
        .menu-card { background: #fff; border-radius: 14px; padding: 22px; text-align: center; border: 1px solid #e8ecf4; text-decoration: none; color: inherit; transition: .2s; }
        .menu-card:hover { transform: translateY(-4px); box-shadow: 0 8px 20px rgba(0,0,0,0.08); color: inherit; }
        .menu-icon { font-size: 2rem; margin-bottom: 10px; }
    </style>
</head>
<body>
<div class="app-layout">
    <?php include 'layout/sidebar.php'; ?>
    <main class="content-area">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h4 class="fw-bold text-dark mb-1">Selamat Datang, <?= htmlspecialchars($nama_pengguna) ?> 👋</h4>
                <p class="text-muted small mb-0">Sistem Pencatatan Pelanggaran Siswa</p>
            </div>
            <span class="badge bg-primary px-3 py-2">Hari ini: <?= date('d/m/Y') ?></span>
        </div>

        <div class="row g-4 mb-5">
            <div class="col-md-3 col-sm-6">
                <div class="stat-card">
                    <p class="text-muted small mb-2">Pelanggaran Hari Ini</p>
                    <h3 class="stat-number text-primary"><?= $pelanggaran_hari_ini ?></h3>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="stat-card">
                    <p class="text-muted small mb-2">Total Tercatat</p>
                    <h3 class="stat-number text-success"><?= $total_pelanggaran ?></h3>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="stat-card">
                    <p class="text-muted small mb-2">Total Poin Dikurangi</p>
                    <h3 class="stat-number text-warning"><?= $total_poin ?></h3>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="stat-card">
                    <p class="text-muted small mb-2">Siswa Diperhatikan</p>
                    <h3 class="stat-number text-danger"><?= $siswa_diperingatkan ?></h3>
                </div>
            </div>
        </div>

        <h5 class="fw-bold mb-3">Menu Cepat</h5>
        <div class="row g-4">
            <div class="col-md-3 col-sm-6">
                <a href="catat_pelanggaran.php" class="menu-card d-block">
                    <div class="menu-icon text-primary">📝</div>
                    <h6 class="fw-semibold">Catat Pelanggaran</h6>
                    <small class="text-muted">Input pelanggaran</small>
                </a>
            </div>
            <div class="col-md-3 col-sm-6">
                <a href="tindakan.php" class="menu-card d-block">
                    <div class="menu-icon text-success">⚖️</div>
                    <h6 class="fw-semibold">Tindakan</h6>
                    <small class="text-muted">Pemberian sanksi</small>
                </a>
            </div>
            <div class="col-md-3 col-sm-6">
                <a href="riwayat.php" class="menu-card d-block">
                    <div class="menu-icon text-warning">📋</div>
                    <h6 class="fw-semibold">Riwayat</h6>
                    <small class="text-muted">Histori lengkap</small>
                </a>
            </div>
            <div class="col-md-3 col-sm-6">
                <a href="laporan.php" class="menu-card d-block">
                    <div class="menu-icon text-info">📊</div>
                    <h6 class="fw-semibold">Laporan</h6>
                    <small class="text-muted">Cetak & ringkasan</small>
                </a>
            </div>
            <div class="col-md-3 col-sm-6">
                <a href="Rekap.php" class="menu-card d-block">
                    <div class="menu-icon text-danger">📈</div>
                    <h6 class="fw-semibold">Rekap Poin</h6>
                    <small class="text-muted">Poin per siswa</small>
                </a>
            </div>
        </div>
    </main>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
