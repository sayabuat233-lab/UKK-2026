<?php
// kelola_jenis_pelanggaran.php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include 'includes/cek_session.php';
include 'config/koneksi.php';

// Cek hak akses admin
if (($_SESSION['role'] ?? '') !== 'admin') {
    header('Location: dashboard_guru.php');
    exit;
}

// Ambil data jenis pelanggaran
$sql = "
    SELECT
        p.id,
        p.pelanggaran_kategori_id,
        p.kode,
        p.nama,
        p.poin,
        p.deskripsi,
        p.status_aktif,
        p.created_at,
        p.updated_at,
        pk.nama AS nama_kategori
    FROM t_pelanggaran AS p
    LEFT JOIN t_pelanggaran_kategori AS pk
        ON p.pelanggaran_kategori_id = pk.id
    ORDER BY p.id DESC
";

$hasil = mysqli_query($koneksi, $sql);

if (!$hasil) {
    die('Query gagal: ' . htmlspecialchars(mysqli_error($koneksi)));
}

// Fungsi pengaman output
function e($nilai)
{
    return htmlspecialchars(
        (string) ($nilai ?? '-'),
        ENT_QUOTES,
        'UTF-8'
    );
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jenis Pelanggaran</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- DataTables CSS -->
    <link href="https://cdn.datatables.net/2.3.4/css/dataTables.dataTables.min.css" rel="stylesheet">
    <!-- CSS Layout -->
    <link rel="stylesheet" href="layout/style.css">

    <style>
        body {
            margin: 0;
            background: #f3f5fb;
            font-family: Arial, sans-serif;
            color: #172b4d;
        }
        .container-fluid {
            min-height: 100vh;
        }
        .row-layout {
            display: flex;
            align-items: stretch;
            min-height: 100vh;
        }
        .page-content {
            flex: 1;
            min-width: 0;
            padding: 25px;
        }
        .page-title {
            font-size: 32px;
            font-weight: 600;
            margin-bottom: 8px;
        }
        .table-card {
            background: #fff;
            border: 1px solid #d9dce3;
            border-radius: 8px;
            padding: 22px;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.05);
        }
        .table-responsive {
            width: 100%;
        }
        #tabelJenisPelanggaran {
            width: 100% !important;
        }
        #tabelJenisPelanggaran thead th {
            background: #212529;
            color: #fff;
            white-space: nowrap;
        }
        #tabelJenisPelanggaran tbody td {
            vertical-align: middle;
        }
        .deskripsi {
            min-width: 160px;
            max-width: 280px;
            white-space: normal;
        }
        .aksi {
            white-space: nowrap;
        }
        @media (max-width: 768px) {
            .page-content {
                padding: 15px;
            }
            .page-title {
                font-size: 25px;
            }
            .table-card {
                padding: 12px;
            }
        }
        @media print {
            .sidebar, .no-print, .dt-search, .dt-length, .dt-paging {
                display: none !important;
            }
            .page-content {
                padding: 0;
            }
        }
    </style>
</head>
<body>
<div class="container-fluid p-0">
    <div class="row-layout">
        <!-- SIDEBAR -->
        <?php include 'layout/sidebar.php'; ?>

        <!-- KONTEN UTAMA -->
        <main class="page-content">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">
                <div>
                    <h1 class="page-title">Jenis Pelanggaran</h1>
                    <p class="text-secondary mb-0">Data jenis pelanggaran dan poin siswa.</p>
                </div>
                <a href="tambah_pelanggaran.php" class="btn btn-primary">
                    + Tambah Jenis Pelanggaran
                </a>
            </div>

            <div class="d-flex justify-content-between align-items-center mb-3 no-print">
                <a href="dashboard.php" class="btn btn-secondary btn-sm">
                    Kembali ke Dashboard
                </a>
                <button type="button" onclick="window.print()" class="btn btn-outline-primary btn-sm">
                    Cetak
                </button>
            </div>

            <!-- PESAN SUKSES -->
            <?php if (isset($_SESSION['pesan_sukses'])): ?>
                <div class="alert alert-success alert-dismissible fade show">
                    <?= e($_SESSION['pesan_sukses']) ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                <?php unset($_SESSION['pesan_sukses']); ?>
            <?php endif; ?>

            <!-- PESAN ERROR -->
            <?php if (isset($_SESSION['pesan_error'])): ?>
                <div class="alert alert-danger alert-dismissible fade show">
                    <?= e($_SESSION['pesan_error']) ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                <?php unset($_SESSION['pesan_error']); ?>
            <?php endif; ?>

            <!-- TABEL JENIS PELANGGARAN -->
            <div class="table-card">
                <div class="table-responsive">
                    <table id="tabelJenisPelanggaran" class="table table-striped table-bordered table-hover align-middle">
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th>Kode</th>
                                <th>Kategori</th>
                                <th>Nama Pelanggaran</th>
                                <th>Poin</th>
                                <th>Deskripsi</th>
                                <th>Status Aktif</th>
                                <th>Dibuat</th>
                                <th>Diubah</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $no = 1; ?>
                            <?php while ($data = mysqli_fetch_assoc($hasil)): ?>
                                <tr>
                                    <td><?= $no++ ?></td>
                                    <td><?= e($data['kode']) ?></td>
                                    <td><?= e($data['nama_kategori']) ?></td>
                                    <td><?= e($data['nama']) ?></td>
                                    <td class="text-center">
                                        <span class="badge bg-danger"><?= (int)$data['poin'] ?></span>
                                    </td>
                                    <td class="deskripsi"><?= e($data['deskripsi']) ?></td>
                                    <td>
                                        <?php if ((int)$data['status_aktif'] === 1): ?>
                                            <span class="badge bg-success">Aktif</span>
                                        <?php else: ?>
                                            <span class="badge bg-secondary">Tidak Aktif</span>
                                        <?php endif; ?>
                                    </td>
                                    <td><?= e($data['created_at']) ?></td>
                                    <td><?= e($data['updated_at']) ?></td>
                                    <td class="aksi">
                                        <a href="edit_pelanggaran.php?id=<?= (int)$data['id'] ?>" class="btn btn-warning btn-sm">Edit</a>
                                        <a href="hapus_pelanggaran.php?id=<?= (int)$data['id'] ?>" class="btn btn-danger btn-sm"
                                           onclick="return confirm('Yakin ingin menghapus jenis pelanggaran ini?');">Hapus</a>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>
</div>

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<!-- DataTables JS -->
<script src="https://cdn.datatables.net/2.3.4/js/dataTables.min.js"></script>
<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>

<script>
$(document).ready(function () {
    $('#tabelJenisPelanggaran').DataTable({
        pageLength: 10,
        lengthMenu: [5, 10, 25, 50, 100],
        order: [[0, 'asc'],
        language: {
            search: 'Cari:',
            lengthMenu: 'Tampilkan _MENU_ data',
            info: 'Menampilkan _START_ sampai _END_ dari _TOTAL_ data',
            infoEmpty: 'Tidak ada data',
            infoFiltered: '(difilter dari _MAX_ total data)',
            zeroRecords: 'Data tidak ditemukan',
            emptyTable: 'Belum ada data jenis pelanggaran',
            paginate: {
                first: 'Pertama',
                last: 'Terakhir',
                next: 'Berikutnya',
                previous: 'Sebelumnya'
            }
        }
    });
});
</script>
</body>
</html>
