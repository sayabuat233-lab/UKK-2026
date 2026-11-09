<?php
// tindakan.php — Kelola Tindakan/Sanksi Pelanggaran

if (session_status() === PHP_SESSION_NONE) session_start();
include 'includes/cek_session.php';
include 'config/koneksi.php';

if (!in_array($_SESSION['role'] ?? '', ['guru', 'admin'])) {
    header('Location: index.php');
    exit;
}

// Otomatis buat tabel jika belum ada
$cek_tabel = mysqli_query($koneksi, "SHOW TABLES LIKE 't_tindakan'");
if (mysqli_num_rows($cek_tabel) == 0) {
    $buat_tabel = "CREATE TABLE `t_tindakan` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `nama_tindakan` varchar(100) NOT NULL,
        `keterangan` text DEFAULT NULL,
        `min_poin` int(11) NOT NULL DEFAULT 0,
        PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";
    mysqli_query($koneksi, $buat_tabel);
}

$error = $sukses = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['simpan_tindakan'])) {
    $nama_tindakan = trim($_POST['nama_tindakan'] ?? '');
    $keterangan = trim($_POST['keterangan'] ?? '');
    $min_poin = (int)($_POST['min_poin'] ?? 0);

    if (empty($nama_tindakan)) {
        $error = 'Nama tindakan tidak boleh kosong!';
    } else {
        $cek = mysqli_query($koneksi, "SELECT id FROM t_tindakan WHERE nama_tindakan = '" . mysqli_real_escape_string($koneksi, $nama_tindakan) . "'");
        if ($cek && mysqli_num_rows($cek) > 0) {
            $error = 'Tindakan sudah terdaftar!';
        } else {
            $sql = "INSERT INTO t_tindakan (nama_tindakan, keterangan, min_poin) 
                    VALUES (
                        '" . mysqli_real_escape_string($koneksi, $nama_tindakan) . "',
                        '" . mysqli_real_escape_string($koneksi, $keterangan) . "',
                        $min_poin
                    )";
            if (mysqli_query($koneksi, $sql)) {
                $sukses = 'Tindakan berhasil ditambahkan!';
            } else {
                $error = 'Gagal menambahkan: ' . mysqli_error($koneksi);
            }
        }
    }
}

if (isset($_GET['hapus'])) {
    $id = (int)$_GET['hapus'];
    mysqli_query($koneksi, "DELETE FROM t_tindakan WHERE id = $id");
    header('Location: tindakan.php');
    exit;
}

$daftar_tindakan = mysqli_query($koneksi, "SELECT * FROM t_tindakan ORDER BY min_poin ASC, nama_tindakan ASC");

// Tandai halaman aktif
$halaman_aktif = 'tindakan';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tindakan - Pelanggaran Siswa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { 
            background: #f4f6fb; 
            font-family: system-ui, -apple-system, "Segoe UI", Roboto, sans-serif;
        }
        
        /* Layout Utama */
        .app-wrapper { display: flex; min-height: 100vh; }
        
        /* Sidebar — RAPIH & TETAP DI KIRI */
        .sidebar {
            width: 220px;
            background: #212529;
            color: #fff;
            padding: 1.5rem 0;
            position: fixed;
            height: 100vh;
            top: 0;
            left: 0;
        }
        .sidebar-header {
            font-size: 1rem;
            font-weight: 600;
            padding: 0 1.25rem 1.5rem;
            border-bottom: 1px solid #343a40;
            margin-bottom: 1rem;
        }
        .sidebar-menu { list-style: none; padding: 0; margin: 0; }
        .sidebar-menu li { margin: 0.25rem 0; }
        .sidebar-menu a {
            display: block;
            padding: 0.65rem 1.25rem;
            color: #adb5bd;
            text-decoration: none;
            font-size: 0.9rem;
            transition: all 0.2s;
        }
        .sidebar-menu a:hover {
            background: rgba(255,255,255,0.05);
            color: #fff;
        }
        .sidebar-menu a.aktif {
            background: #0d6efd;
            color: #fff;
            font-weight: 500;
        }
        .sidebar-menu a.logout {
            color: #dc3545;
            margin-top: 2rem;
        }
        .sidebar-menu a.logout:hover {
            background: rgba(220, 53, 69, 0.1);
            color: #ff6b6b;
        }
        
        /* Konten Utama — DI SEBELAH SIDEBAR */
        .main-content {
            flex: 1;
            margin-left: 220px;
            padding: 2rem;
        }
        .page-title {
            font-size: 1.25rem;
            font-weight: 600;
            color: #212529;
            margin-bottom: 1.5rem;
        }
        .card {
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 2px 12px rgba(0,0,0,0.04);
            padding: 1.75rem;
            margin-bottom: 1.5rem;
        }
        .form-label { font-weight: 500; color: #495057; }
        .form-control, .form-select {
            border-radius: 8px;
            border: 1px solid #ced4da;
            padding: 0.5rem 0.75rem;
        }
        .btn { border-radius: 6px; padding: 0.5rem 1.25rem; font-size: 0.9rem; }
        .table { background: #fff; border-radius: 12px; overflow: hidden; }
        .table th { border-top: none; font-weight: 600; color: #495057; background: #f8f9fa; }
        .table td { border-top: none; border-bottom: 1px solid #f1f3f5; }
    </style>
</head>
<body>
<div class="app-wrapper">

    <!-- Sidebar -->
    <aside class="sidebar">
        <div class="sidebar-header">Pelanggaran Siswa</div>
        <ul class="sidebar-menu">
            <li>
                <a href="dashboard.php" class="<?= ($halaman_aktif === 'dashboard') ? 'aktif' : '' ?>">Dashboard</a>
            </li>
            <li>
                <a href="catat_pelanggaran.php" class="<?= ($halaman_aktif === 'catat') ? 'aktif' : '' ?>">Catat Pelanggaran</a>
            </li>
            <li>
                <a href="tindakan.php" class="<?= ($halaman_aktif === 'tindakan') ? 'aktif' : '' ?>">Tindakan</a>
            </li>
            <li>
                <a href="riwayat.php" class="<?= ($halaman_aktif === 'riwayat') ? 'aktif' : '' ?>">Riwayat</a>
            </li>
            <li>
                <a href="Rekap.php" class="<?= ($halaman_aktif === 'rekap') ? 'aktif' : '' ?>">Rekap Poin</a>
            </li>
            <li>
                <a href="logout.php" class="logout">Logout</a>
            </li>
        </ul>
    </aside>

    <main class="main-content">
        <h5 class="page-title">⚖️ Kelola Tindakan & Sanksi</h5>

        <?php if ($error): ?>
            <div class="alert alert-danger alert-dismissible fade show">
                <?= htmlspecialchars($error) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>
        <?php if ($sukses): ?>
            <div class="alert alert-success alert-dismissible fade show">
                <?= htmlspecialchars($sukses) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <!-- Form Tambah Tindakan -->
        <div class="card">
            <h6 class="fw-semibold mb-3">➕ Tambah Tindakan Baru</h6>
            <form method="post">
                <div class="row mb-3 align-items-center">
                    <label class="col-sm-3 col-form-label">Nama Tindakan</label>
                    <div class="col-sm-9">
                        <input type="text" name="nama_tindakan" class="form-control" placeholder="Contoh: Peringatan Lisan" required>
                    </div>
                </div>
                <div class="row mb-3 align-items-center">
                    <label class="col-sm-3 col-form-label">Minimal Poin</label>
                    <div class="col-sm-9">
                        <input type="number" name="min_poin" class="form-control" placeholder="Poin minimal" min="0" required>
                    </div>
                </div>
                <div class="row mb-4">
                    <label class="col-sm-3 col-form-label">Keterangan</label>
                    <div class="col-sm-9">
                        <textarea name="keterangan" class="form-control" rows="3" placeholder="Keterangan tindakan..."></textarea>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-9 offset-sm-3">
                        <button type="submit" name="simpan_tindakan" class="btn btn-primary">Simpan Tindakan</button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Daftar Tindakan -->
        <div class="card">
            <h6 class="fw-semibold mb-3">📋 Daftar Tindakan / Sanksi</h6>
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th width="50">#</th>
                            <th>Nama Tindakan</th>
                            <th width="130">Min. Poin</th>
                            <th>Keterangan</th>
                            <th width="100" class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $no = 1; 
                        if ($daftar_tindakan && mysqli_num_rows($daftar_tindakan) > 0):
                            while ($t = mysqli_fetch_assoc($daftar_tindakan)): 
                        ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td class="fw-medium"><?= htmlspecialchars($t['nama_tindakan']) ?></td>
                            <td><span class="badge bg-primary"><?= $t['min_poin'] ?> poin</span></td>
                            <td class="text-muted small"><?= htmlspecialchars($t['keterangan'] ?? '-') ?></td>
                            <td class="text-center">
                                <a href="tindakan.php?hapus=<?= $t['id'] ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Yakin hapus?')">Hapus</a>
                            </td>
                        </tr>
                        <?php 
                            endwhile; 
                        else:
                        ?>
                        <tr>
                            <td colspan="5" class="text-center text-muted py-4">
                                Belum ada data tindakan.<br>Silakan tambahkan di atas dulu.
                            </td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
