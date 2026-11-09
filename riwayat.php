<?php
// riwayat.php — Riwayat Pelanggaran Siswa

if (session_status() === PHP_SESSION_NONE) session_start();
include 'includes/cek_session.php';
include 'config/koneksi.php';

if (!in_array($_SESSION['role'] ?? '', ['guru', 'admin'])) {
    header('Location: index.php');
    exit;
}

// Hapus data jika ada permintaan
if (isset($_GET['hapus'])) {
    $id = (int)$_GET['hapus'];
    mysqli_query($koneksi, "DELETE FROM t_pelanggaran_siswa WHERE id = $id");
    header('Location: riwayat.php');
    exit;
}

// Filter berdasarkan tanggal
$where = ['1=1'];
if (!empty($_GET['dari'])) {
    $dari = mysqli_real_escape_string($koneksi, $_GET['dari']);
    $where[] = "tanggal >= '$dari'";
}
if (!empty($_GET['sampai'])) {
    $sampai = mysqli_real_escape_string($koneksi, $_GET['sampai']);
    $where[] = "tanggal <= '$sampai'";
}
$filter = implode(' AND ', $where);

// Ambil data riwayat
$sql = "SELECT ps.*, s.nama AS nama_siswa, p.nama AS nama_pelanggaran, p.poin
        FROM t_pelanggaran_siswa ps
        LEFT JOIN t_siswa s ON ps.siswa_id = s.id
        LEFT JOIN t_pelanggaran p ON ps.pelanggaran_id = p.id
        WHERE $filter
        ORDER BY ps.tanggal DESC, ps.id DESC";
$riwayat = mysqli_query($koneksi, $sql);

// Tandai halaman aktif
$halaman_aktif = 'riwayat';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Pelanggaran - Pelanggaran Siswa</title>
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
        
        /* Konten Utama — di sebelah sidebar */
        .main-content {
            flex: 1;
            margin-left: 220px;
            padding: 2rem;
        }
        
        .card { 
            border-radius: 12px; 
            border: none; 
            box-shadow: 0 2px 12px rgba(0,0,0,0.04); 
            background: #fff; 
        }
        .table-card { 
            background: #fff; 
            border-radius: 12px; 
            overflow: hidden; 
            box-shadow: 0 2px 12px rgba(0,0,0,0.04); 
        }
        .table th { 
            background: #f8f9fa; 
            border: none; 
            font-weight: 600; 
            white-space: nowrap; 
            color: #495057; 
        }
        .table td { 
            border: none; 
            border-bottom: 1px solid #f1f3f5; 
            vertical-align: middle; 
        }
        .badge-poin { background: #ff6b6b; color: #fff; }
        .form-label { font-weight: 500; color: #495057; }
        .btn { border-radius: 6px; }
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
        <h4 class="fw-bold mb-4">📋 Riwayat Pelanggaran Siswa</h4>

        <!-- Filter Tanggal -->
        <div class="card p-3 mb-4">
            <form method="get" class="row align-items-end g-3">
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Dari Tanggal</label>
                    <input type="date" name="dari" class="form-control" value="<?= htmlspecialchars($_GET['dari'] ?? '') ?>">
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Sampai Tanggal</label>
                    <input type="date" name="sampai" class="form-control" value="<?= htmlspecialchars($_GET['sampai'] ?? '') ?>">
                </div>
                <div class="col-md-4">
                    <button type="submit" class="btn btn-primary w-100">Tampilkan</button>
                </div>
            </form>
        </div>

        <!-- Daftar Riwayat -->
        <div class="table-card">
            <div class="p-3 border-bottom d-flex justify-content-between align-items-center">
                <h5 class="fw-semibold mb-0">Daftar Pelanggaran</h5>
                <a href="catat_pelanggaran.php" class="btn btn-sm btn-primary">+ Catat Baru</a>
            </div>
            <div class="p-3">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th width="50">#</th>
                                <th>Tanggal</th>
                                <th>Nama Siswa</th>
                                <th>Jenis Pelanggaran</th>
                                <th width="100">Poin</th>
                                <th>Keterangan</th>
                                <th width="80" class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $no = 1; 
                            if ($riwayat && mysqli_num_rows($riwayat) > 0):
                                while ($r = mysqli_fetch_assoc($riwayat)): 
                            ?>
                            <tr>
                                <td><?= $no++ ?></td>
                                <td><?= !empty($r['tanggal']) ? date('d/m/Y', strtotime($r['tanggal'])) : '-' ?></td>
                                <td class="fw-medium"><?= htmlspecialchars($r['nama_siswa'] ?? '-') ?></td>
                                <td><?= htmlspecialchars($r['nama_pelanggaran'] ?? '-') ?></td>
                                <td><span class="badge badge-poin"><?= $r['poin_dikurangi'] ?? $r['poin'] ?? 0 ?></span></td>
                                <td class="text-muted small text-truncate" style="max-width: 200px;" title="<?= htmlspecialchars($r['keterangan'] ?? '') ?>">
                                    <?= htmlspecialchars($r['keterangan'] ?: '-') ?>
                                </td>
                                <td class="text-center">
                                    <a href="riwayat.php?hapus=<?= $r['id'] ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Yakin hapus data ini?')">Hapus</a>
                                </td>
                            </tr>
                            <?php 
                                endwhile; 
                            else:
                            ?>
                            <tr>
                                <td colspan="7" class="text-center text-muted py-4">
                                    Belum ada data pelanggaran.<br>
                                    <a href="catat_pelanggaran.php" class="btn btn-sm btn-primary mt-2">Catat Pelanggaran Sekarang</a>
                                </td>
                            </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
