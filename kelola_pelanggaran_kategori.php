
<?php
// kelola_kategori_pelanggaran.php

error_reporting(E_ALL);
ini_set('display_errors', '1');

include 'includes/cek_session.php';
include 'config/koneksi.php';

// Ambil data kategori pelanggaran
$sql = "SELECT * FROM t_pelanggaran_kategori ORDER BY id DESC";
$hasil = mysqli_query($koneksi, $sql);

if (!$hasil) {
    die("Query gagal: " . htmlspecialchars(mysqli_error($koneksi)));
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Kelola Kategori Pelanggaran</title>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css"
          rel="stylesheet">

    <!-- DataTables Bootstrap 5 -->
    <link href="https://cdn.datatables.net/2.3.4/css/dataTables.bootstrap5.min.css"
          rel="stylesheet">

    <!-- CSS utama proyek -->
    <link rel="stylesheet" href="layout/style.css">

    <style>
        html,
        body {
            margin: 0;
            padding: 0;
            min-height: 100%;
        }

        /* Layout utama */
        .layout-wrapper {
            display: flex;
            flex-direction: row;
            align-items: stretch;
            width: 100%;
            min-height: 100vh;
        }

        /* Sidebar kiri */
        .layout-sidebar {
            flex: 0 0 250px;
            width: 250px;
            min-width: 250px;
            min-height: 100vh;
            background-color: #212529;
        }

        /* Menyesuaikan sidebar lama yang memakai grid Bootstrap */
        .layout-sidebar > .col-md-3,
        .layout-sidebar > .col-lg-2 {
            flex: 0 0 100%;
            width: 100%;
            max-width: 100%;
            min-height: 100vh;
        }

        /* Konten kanan */
        .layout-content {
            flex: 1 1 0;
            min-width: 0;
            padding: 24px;
            background-color: #f8f9fa;
        }

        /* Tabel */
        .table-responsive {
            width: 100%;
        }

        #tabelKategori {
            width: 100% !important;
        }

        #tabelKategori thead th {
            white-space: nowrap;
            vertical-align: middle;
        }

        #tabelKategori tbody td {
            vertical-align: middle;
        }

        #tabelKategori .btn {
            white-space: nowrap;
        }

        /* Tampilan layar kecil */
        @media (max-width: 767.98px) {
            .layout-wrapper {
                flex-direction: column;
            }

            .layout-sidebar {
                flex: 0 0 auto;
                width: 100%;
                min-width: 0;
                min-height: auto;
            }

            .layout-sidebar > .col-md-3,
            .layout-sidebar > .col-lg-2 {
                min-height: auto;
            }

            .layout-content {
                width: 100%;
                padding: 15px;
            }

            .header-kategori {
                align-items: flex-start !important;
                flex-direction: column;
                gap: 12px;
            }
        }
    </style>
</head>

<body>

<div class="layout-wrapper">

    <!-- SIDEBAR KIRI -->
    <aside class="layout-sidebar">
        <?php include 'layout/sidebar.php'; ?>
    </aside>

    <!-- KONTEN KANAN -->
    <main class="layout-content">

        <div class="header-kategori d-flex justify-content-between align-items-center mb-3">
            <h1 class="mb-0">Kelola Kategori Pelanggaran</h1>

            <a href="tambah_pelanggaran_kategori.php"
               class="btn btn-primary">
                + Tambah Kategori Pelanggaran
            </a>
        </div>

        <div class="mb-3">
            <a href="dashboard.php" class="btn btn-secondary btn-sm">
                Kembali ke Dashboard
            </a>
        </div>

        <div class="card shadow-sm">
            <div class="card-body">

                <div class="table-responsive">
                    <table id="tabelKategori"
                           class="table table-striped table-bordered table-hover align-middle">

                        <thead class="table-dark">
                            <tr>
                                <th>ID</th>
                                <th>Nama Kategori</th>
                                <th>Deskripsi</th>
                                <th>Status Aktif</th>
                                <th>Dibuat</th>
                                <th>Diubah</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php while ($row = mysqli_fetch_assoc($hasil)) { ?>
                                <tr>
                                    <td>
                                        <?= htmlspecialchars((string) $row['id']) ?>
                                    </td>

                                    <td>
                                        <?= htmlspecialchars($row['nama'] ?? '') ?>
                                    </td>

                                    <td>
                                        <?= htmlspecialchars($row['deskripsi'] ?? '-') ?>
                                    </td>

                                    <td>
                                        <?php if (($row['status_aktif'] ?? 0) == 1) { ?>
                                            <span class="badge bg-success">Aktif</span>
                                        <?php } else { ?>
                                            <span class="badge bg-secondary">Tidak Aktif</span>
                                        <?php } ?>
                                    </td>

                                    <td>
                                        <?= htmlspecialchars($row['created_at'] ?? '-') ?>
                                    </td>

                                    <td>
                                        <?= htmlspecialchars($row['updated_at'] ?? '-') ?>
                                    </td>

                                    <td class="text-nowrap">
                                        <a href="edit_kategori_pelanggaran.php?id=<?= urlencode((string) $row['id']) ?>"
                                           class="btn btn-warning btn-sm">
                                            Edit
                                        </a>

                                        <a href="hapus_kategori_pelanggaran.php?id=<?= urlencode((string) $row['id']) ?>"
                                           class="btn btn-danger btn-sm"
                                           onclick="return confirm('Yakin ingin menghapus kategori pelanggaran ini?');">
                                            Hapus
                                        </a>
                                    </td>
                                </tr>
                            <?php } ?>
                        </tbody>

                    </table>
                </div>

            </div>
        </div>

    </main>

</div>

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

<!-- DataTables -->
<script src="https://cdn.datatables.net/2.3.4/js/dataTables.min.js"></script>
<script src="https://cdn.datatables.net/2.3.4/js/dataTables.bootstrap5.min.js"></script>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>

<!-- Konfigurasi DataTables -->
<script>
$(document).ready(function () {

    $('#tabelKategori').DataTable({
        pageLength: 10,
        lengthMenu: [5, 10, 25, 50, 100],

        searching: true,
        ordering: true,
        paging: true,
        info: true,
        autoWidth: false,
        scrollX: true,

        order: [[0, 'desc']],

        columnDefs: [
            {
                targets: 6,
                orderable: false,
                searchable: false
            }
        ],

        language: {
            search: "Cari:",
            searchPlaceholder: "Cari kategori pelanggaran...",
            lengthMenu: "Tampilkan _MENU_ data",
            info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data kategori",
            infoEmpty: "Tidak ada data kategori",
            infoFiltered: "(difilter dari _MAX_ total data)",
            zeroRecords: "Data kategori tidak ditemukan",
            emptyTable: "Belum ada data kategori",

            paginate: {
                first: "Pertama",
                last: "Terakhir",
                next: "Berikutnya",
                previous: "Sebelumnya"
            }
        }
    });

});
</script>

</body>
</html>
