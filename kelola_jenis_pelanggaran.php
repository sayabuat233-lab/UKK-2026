
<?php
// kelola_jenis_pelanggaran.php

include 'includes/cek_session.php';
include 'config/koneksi.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (($_SESSION['role'] ?? '') !== 'admin') {
    header('Location: dashboard_guru.php');
    exit;
}

// Mengambil data jenis pelanggaran
$sql = "SELECT * FROM t_pelanggaran ORDER BY id DESC";
$hasil = mysqli_query($koneksi, $sql);

if (!$hasil) {
    die("Query gagal: " . htmlspecialchars(mysqli_error($koneksi)));
}

function e($nilai) {
    return htmlspecialchars((string)($nilai ?? '-'), ENT_QUOTES, 'UTF-8');
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Kelola Jenis Pelanggaran</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css"
          rel="stylesheet">

    <link href="https://cdn.datatables.net/2.3.2/css/dataTables.dataTables.min.css"
          rel="stylesheet">

    <style>
        body {
            margin: 0;
            background: #f3f5fb;
            font-family: Arial, sans-serif;
            color: #172b4d;
        }

        .page-content {
            padding: 25px;
            min-width: 0;
            flex: 1;
        }

        .page-title {
            font-size: 36px;
            font-weight: 500;
            margin-bottom: 20px;
        }

        .table-card {
            background: #fff;
            border: 1px solid #d9dce3;
            border-radius: 6px;
            padding: 24px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, .05);
        }

        .table-responsive {
            width: 100%;
        }

        #tabelJenisPelanggaran {
            width: 100% !important;
            color: #111;
        }

        #tabelJenisPelanggaran thead th {
            background: #212529;
            color: #fff;
            white-space: nowrap;
        }

        #tabelJenisPelanggaran tbody td {
            vertical-align: middle;
        }

        @media (max-width: 768px) {
            .page-content {
                padding: 15px;
            }

            .page-title {
                font-size: 26px;
            }

            .table-card {
                padding: 12px;
            }
        }
    </style>
</head>

<body>

<div class="container-fluid">
    <div class="row g-0 flex-nowrap">

        <?php include 'layout/sidebar.php'; ?>

        <main class="page-content">

            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">
                <h1 class="page-title mb-0">
                    Kelola Jenis Pelanggaran
                </h1>

                <a href="tambah_pelanggaran.php"
                   class="btn btn-primary">
                    + Tambah Jenis Pelanggaran
                </a>
            </div>

            <div class="mb-3">
                <a href="dashboard.php" class="btn btn-secondary btn-sm">
                    Kembali ke Dashboard
                </a>
            </div>

            <?php if (isset($_SESSION['pesan_sukses'])): ?>
                <div class="alert alert-success">
                    <?= e($_SESSION['pesan_sukses']) ?>
                </div>
                <?php unset($_SESSION['pesan_sukses']); ?>
            <?php endif; ?>

            <?php if (isset($_SESSION['pesan_error'])): ?>
                <div class="alert alert-danger">
                    <?= e($_SESSION['pesan_error']) ?>
                </div>
                <?php unset($_SESSION['pesan_error']); ?>
            <?php endif; ?>

            <div class="table-card">
                <div class="table-responsive">

                    <table id="tabelJenisPelanggaran"
                           class="table table-striped table-bordered table-hover align-middle">

                        <thead>
                            <tr>
                                <th>ID</th>
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
                            <?php while ($data = mysqli_fetch_assoc($hasil)): ?>
                                <tr>
                                    <td><?= e($data['id']) ?></td>
                                    <td><?= e($data['nama_pelanggaran']) ?></td>
                                    <td><?= e($data['poin']) ?></td>
                                    <td><?= e($data['deskripsi'] ?? '-') ?></td>

                                    <td>
                                        <?php if ((int)$data['status_aktif'] === 1): ?>
                                            <span class="badge bg-success">Aktif</span>
                                        <?php else: ?>
                                            <span class="badge bg-secondary">Tidak Aktif</span>
                                        <?php endif; ?>
                                    </td>

                                    <td><?= e($data['created_at'] ?? '-') ?></td>
                                    <td><?= e($data['updated_at'] ?? '-') ?></td>

                                    <td>
                                        <div class="d-flex gap-1">
                                            <a href="edit_pelanggaran.php?id=<?= urlencode($data['id']) ?>"
                                               class="btn btn-warning btn-sm">
                                                Edit
                                            </a>

                                            <a href="hapus_pelanggaran.php?id=<?= urlencode($data['id']) ?>"
                                               class="btn btn-danger btn-sm"
                                               onclick="return confirm('Yakin ingin menghapus jenis pelanggaran ini?')">
                                                Hapus
                                            </a>
                                        </div>
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

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/2.3.2/js/dataTables.min.js"></script>

<script>
$(document).ready(function () {
    $('#tabelJenisPelanggaran').DataTable({
        pageLength: 10,
        lengthMenu: [10, 25, 50, 100],
        order: [[0, 'desc']],
        language: {
            search: 'Cari:',
            lengthMenu: 'Tampilkan _MENU_ data',
            info: 'Menampilkan _START_ sampai _END_ dari _TOTAL_ data',
            infoEmpty: 'Tidak ada data',
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

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
