<?php
if (session_status() === PHP_SESSION_NONE) session_start();
include 'includes/cek_session.php';
include 'config/koneksi.php';

if (!in_array($_SESSION['role'] ?? '', ['guru', 'admin'])) {
    header('Location: index.php');
    exit;
}

$sql = "SELECT 
            s.id,
            s.nama,
            COALESCE(SUM(p.poin), 0) AS total_poin,
            COUNT(DISTINCT ps.id) AS jumlah_pelanggaran
        FROM t_siswa s
        LEFT JOIN t_pelanggaran_siswa ps ON s.id = ps.siswa_id
        LEFT JOIN t_pelanggaran p ON ps.pelanggaran_id = p.id
        GROUP BY s.id, s.nama
        ORDER BY total_poin DESC, s.nama ASC";
$rekap = mysqli_query($koneksi, $sql);

$halaman_aktif = 'rekap';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rekap Poin - Pelanggaran Siswa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { 
            background: #f4f6fb; 
            font-family: system-ui, -apple-system, "Segoe UI", Roboto, sans-serif;
        }
        .app-wrapper { display: flex; min-height: 100vh; }
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
        .main-content {
            flex: 1;
            margin-left: 220px;
            padding: 2rem;
        }
        .card { 
            border-radius: 12px; 
            border: none; 
            box-shadow: 0 2px 12px rgba(0,0,0,0.04); 
            margin-bottom: 24px; 
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
        .poin-tinggi { background: #fff1f0; }
        .poin-sedang { background: #fffbeb; }
        .poin-rendah { background: #f0fdf4; }
        .badge-poin { background: #ef4444; color: #fff; font-weight: 600; }
        .badge-jumlah { background: #6366f1; color: #fff; }
        .stat-box { border-radius: 12px; padding: 16px; }
        .text-orange { color: #f59e0b; }
    </style>
</head>
<body>
<div class="app-wrapper">
    <aside class="sidebar">
        <div class="sidebar-header">Pelanggaran Siswa</div>
        <ul class="sidebar-menu">
            <li><a href="dashboard.php" class="<?= ($halaman_aktif === 'dashboard') ? 'aktif' : '' ?>">Dashboard</a></li>
            <li><a href="catat_pelanggaran.php" class="<?= ($halaman_aktif === 'catat') ? 'aktif' : '' ?>">Catat Pelanggaran</a></li>
            <li><a href="tindakan.php" class="<?= ($halaman_aktif === 'tindakan') ? 'aktif' : '' ?>">Tindakan</a></li>
            <li><a href="riwayat.php" class="<?= ($halaman_aktif === 'riwayat') ? 'aktif' : '' ?>">Riwayat</a></li>
            <li><a href="rekap_poin.php" class="<?= ($halaman_aktif === 'rekap') ? 'aktif' : '' ?>">Rekap Poin</a></li>
            <li><a href="logout.php" class="logout">Logout</a></li>
        </ul>
    </aside>

    <main class="main-content">
        <h4 class="fw-bold mb-4">📊 Rekapitulasi Poin Pelanggaran</h4>

        <div class="row mb-4">
            <div class="col-md-4">
                <div class="card stat-box poin-rendah">
                    <h6 class="text-success mb-1">Total Siswa</h6>
                    <h3 class="fw-bold">
                        <?php 
                        $total_siswa = mysqli_query($koneksi, "SELECT COUNT(*) AS jml FROM t_siswa");
                        echo mysqli_fetch_assoc($total_siswa)['jml'];
                        ?>
                    </h3>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card stat-box poin-sedang">
                    <h6 class="text-warning mb-1">Ada Pelanggaran</h6>
                    <h3 class="fw-bold">
                        <?php 
                        $ada = mysqli_query($koneksi, "SELECT COUNT(DISTINCT siswa_id) AS jml FROM t_pelanggaran_siswa");
                        echo mysqli_fetch_assoc($ada)['jml'] ?: 0;
                        ?>
                    </h3>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card stat-box poin-tinggi">
                    <h6 class="text-danger mb-1">Total Poin Dikumpulkan</h6>
                    <h3 class="fw-bold">
                        <?php 
                        $total = mysqli_query($koneksi, "SELECT SUM(p.poin) AS jml FROM t_pelanggaran_siswa ps LEFT JOIN t_pelanggaran p ON ps.pelanggaran_id = p.id");
                        echo mysqli_fetch_assoc($total)['jml'] ?: 0;
                        ?>
                    </h3>
                </div>
            </div>
        </div>

        <div class="table-card">
            <div class="p-3 border-bottom">
                <h5 class="fw-semibold mb-0">Daftar Poin Per Siswa</h5>
            </div>
            <div class="p-3">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th width="50">#</th>
                                <th>Nama Siswa</th>
                                <th width="150" class="text-center">Jumlah Pelanggaran</th>
                                <th width="150" class="text-center">Total Poin</th>
                                <th width="200">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $no = 1; 
                            if ($rekap && mysqli_num_rows($rekap) > 0):
                                while ($r = mysqli_fetch_assoc($rekap)):
                                    if ($r['total_poin'] == 0) {
                                        $status = ['text' => 'Bebas Pelanggaran', 'class' => 'text-success'];
                                        $baris = 'poin-rendah';
                                    } elseif ($r['total_poin'] < 20) {
                                        $status = ['text' => 'Peringatan Ringan', 'class' => 'text-warning'];
                                        $baris = '';
                                    } elseif ($r['total_poin'] < 50) {
                                        $status = ['text' => 'Peringatan Sedang', 'class' => 'text-orange'];
                                        $baris = 'poin-sedang';
                                    } else {
                                        $status = ['text' => 'Perlu Tindakan Khusus', 'class' => 'text-danger fw-bold'];
                                        $baris = 'poin-tinggi';
                                    }
                            ?>
                            <tr class="<?= $baris ?>">
                                <td><?= $no++ ?></td>
                                <td class="fw-medium"><?= htmlspecialchars($r['nama']) ?></td>
                                <td class="text-center">
                                    <span class="badge badge-jumlah"><?= $r['jumlah_pelanggaran'] ?></span>
                                </td>
                                <td class="text-center">
                                    <span class="badge badge-poin"><?= $r['total_poin'] ?></span>
                                </td>
                                <td class="<?= $status['class'] ?>"><?= $status['text'] ?></td>
                            </tr>
                            <?php 
                                endwhile; 
                            else:
                            ?>
                            <tr>
                                <td colspan="5" class="text-center text-muted py-4">
                                    Belum ada data siswa atau pelanggaran.<br>
                                    Pastikan data siswa sudah dimasukkan.
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
