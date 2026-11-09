<?php
// catat_pelanggaran.php

if (session_status() === PHP_SESSION_NONE) session_start();
include 'includes/cek_session.php';
include 'config/koneksi.php';

if (!in_array($_SESSION['role'] ?? '', ['guru', 'admin'])) {
    header('Location: index.php');
    exit;
}

$tanggal = date('Y-m-d');
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $siswa_id = (int)($_POST['siswa_id'] ?? 0);
    $pelanggaran_id = (int)($_POST['pelanggaran_id'] ?? 0);
    $keterangan = trim($_POST['keterangan'] ?? '');
    $tanggal = trim($_POST['tanggal'] ?? date('Y-m-d'));
    $guru_id = $_SESSION['user_id'] ?? 0;

    if ($siswa_id <= 0 || $pelanggaran_id <= 0) {
        $error = 'Silakan pilih siswa dan jenis pelanggaran.';
    } else {
        $q_poin = mysqli_query($koneksi, "SELECT poin FROM t_pelanggaran WHERE id = $pelanggaran_id");
        $poin = mysqli_fetch_assoc($q_poin)['poin'] ?? 0;

        $sql = "INSERT INTO t_pelanggaran_siswa (siswa_id, pelanggaran_id, poin_dikurangi, keterangan, tanggal, guru_id) 
                VALUES ($siswa_id, $pelanggaran_id, $poin, '" . mysqli_real_escape_string($koneksi, $keterangan) . "', '$tanggal', $guru_id)";
        
        if (mysqli_query($koneksi, $sql)) {
            $_SESSION['pesan_sukses'] = 'Pelanggaran berhasil dicatat!';
            header('Location: riwayat.php');
            exit;
        } else {
            $error = 'Gagal menyimpan: ' . mysqli_error($koneksi);
        }
    }
}

// Ambil data siswa
$siswa = mysqli_query($koneksi, "SELECT id, nama FROM t_siswa ORDER BY nama");

// Ambil data pelanggaran
$pelanggaran = mysqli_query($koneksi, "SELECT id, nama, poin FROM t_pelanggaran ORDER BY nama");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Catat Pelanggaran</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="layout/style.css">
    <style>
        body { 
            background: #f4f6fb; 
            margin: 0;
        }
        .app-layout { 
            display: flex; 
            min-height: 100vh; 
            align-items: flex-start;
        }
        .sidebar {
            flex: 0 0 260px;
            width: 260px;
            min-height: 100vh;
        }
        .content-area {
            flex: 1;
            padding: 35px;
            min-width: 0;
        }
        .card { 
            border-radius: 16px; 
            border: none; 
            box-shadow: 0 4px 20px rgba(0,0,0,0.05); 
            background: #fff;
        }
        .form-control, .form-select { 
            border: 1px solid #ced4da; 
            border-radius: 8px;
        }
        .btn {
            border-radius: 8px;
        }
        @media (max-width: 768px) {
            .app-layout { flex-direction: column; }
            .sidebar { width: 100%; flex: none; }
            .content-area { padding: 20px; }
        }
    </style>
</head>
<body>
<div class="app-layout">
    <?php include 'layout/sidebar.php'; ?>
    
    <main class="content-area">
        <h4 class="fw-bold mb-4">📝 Catat Pelanggaran Siswa</h4>

        <?php if ($error): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <?php if (isset($_SESSION['pesan_sukses'])): ?>
            <div class="alert alert-success alert-dismissible fade show">
                <?= htmlspecialchars($_SESSION['pesan_sukses']) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            <?php unset($_SESSION['pesan_sukses']); ?>
        <?php endif; ?>

        <div class="card p-4">
            <form method="post">
                <div class="row mb-3 align-items-center">
                    <label class="col-sm-3 col-form-label fw-semibold">Tanggal</label>
                    <div class="col-sm-9">
                        <input type="date" name="tanggal" value="<?= htmlspecialchars($tanggal) ?>" class="form-control" required>
                    </div>
                </div>

                <div class="row mb-3 align-items-center">
                    <label class="col-sm-3 col-form-label fw-semibold">Nama Siswa</label>
                    <div class="col-sm-9">
                        <select name="siswa_id" class="form-select" required>
                            <option value="">-- Pilih Siswa --</option>
                            <?php while ($s = mysqli_fetch_assoc($siswa)): ?>
                                <option value="<?= $s['id'] ?>">
                                    <?= htmlspecialchars($s['nama']) ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                </div>

                <div class="row mb-3 align-items-center">
                    <label class="col-sm-3 col-form-label fw-semibold">Jenis Pelanggaran</label>
                    <div class="col-sm-9">
                        <select name="pelanggaran_id" class="form-select" required>
                            <option value="">-- Pilih Pelanggaran --</option>
                            <?php while ($p = mysqli_fetch_assoc($pelanggaran)): ?>
                                <option value="<?= $p['id'] ?>">
                                    <?= htmlspecialchars($p['nama']) ?> (<?= $p['poin'] ?> poin)
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                </div>

                <div class="row mb-4">
                    <label class="col-sm-3 col-form-label fw-semibold">Keterangan</label>
                    <div class="col-sm-9">
                        <textarea name="keterangan" class="form-control" rows="3" placeholder="Opsional: tambahkan catatan..."></textarea>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-9 offset-sm-3">
                        <button type="submit" class="btn btn-primary px-4">Simpan Pelanggaran</button>
                        <a href="dashboard_guru.php" class="btn btn-secondary px-4 ms-2">Batal</a>
                    </div>
                </div>
            </form>
        </div>
    </main>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
