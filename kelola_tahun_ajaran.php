
<?php
// kelola_tahun_ajaran.php

include 'includes/cek_session.php';
include 'config/koneksi.php';

$sql = "SELECT * FROM t_tahun_ajaran ORDER BY tanggal_mulai DESC";
$hasil = mysqli_query($koneksi, $sql);

if (!$hasil) {
    die("Query gagal: " . mysqli_error($koneksi));
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Kelola Tahun Ajaran</title>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css"
          rel="stylesheet">

    <!-- DataTables CSS Bootstrap 5 -->
    <link href="https://cdn.datatables.net/2.3.2/css/dataTables.dataTables.min.css"
          rel="stylesheet">

    <!-- CSS Layout -->
    <link rel="stylesheet" href="layout/style.css">

    <!-- Samakan tampilan dengan kelola_walikelas.php -->
    <style>
        #tabelTahunAjaran {
            width: 100% !important;
            border-collapse: collapse !important;
            background-color: #ffffff;
        }

        #tabelTahunAjaran thead th {
            background-color: #212529 !important;
            color: #ffffff !important;
            font-weight: bold;
            border: 1px solid #dee2e6 !important;
            padding: 10px;
            white-space: nowrap;
        }

        #tabelTahunAjaran tbody td {
            border: 1px solid #dee2e6 !important;
            padding: 8px 10px;
            vertical-align: middle;
        }

        #tabelTahunAjaran tbody tr:nth-child(odd) {
            background-color: #f1f1f1 !important;
        }

        #tabelTahunAjaran tbody tr:nth-child(even) {
            background-color: #ffffff !important;
        }

        #tabelTahunAjaran tbody tr:hover td {
            background-color: #e9ecef !important;
        }

        #tabelTahunAjaran .btn-warning {
            background-color: #ffc107;
            border-color: #ffc107;
            color: #000;
        }

        #tabelTahunAjaran .btn-danger {
            background-color: #dc3545;
            border-color: #dc3545;
            color: #fff;
        }

        #tabelTahunAjaran .btn {
            white-space: nowrap;
        }

        .dt-container .dt-paging .dt-paging-button.current {
            background: #0d6efd !important;
            color: #ffffff !important;
            border: 1px solid #0d6efd !important;
        }

        .dt-container .dt-paging .dt-paging-button {
            color: #0d6efd !important;
        }

        .dt-container .dt-paging .dt-paging-button:hover {
            background: #0d6efd !important;
            color: #ffffff !important;
            border: 1px solid #0d6efd !important;
        }

        .dt-container .dt-length,
        .dt-container .dt-search {
            margin-bottom: 8px;
        }

        .dt-container .dt-search input,
        .dt-container .dt-length select {
            border: 1px solid #ced4da;
            border-radius: 4px;
            padding: 5px 8px;
        }

        .table-responsive {
            width: 100%;
        }
    </style>
</head>

<body>

<div class="container-fluid">
    <div class="row">

        <!-- SIDEBAR -->
        <?php include 'layout/sidebar.php'; ?>

        <!-- KONTEN -->
        <main class="col-md-9 col-lg-10 p-4">

            <h1 class="mb-2">Kelola Tahun Ajaran</h1>

            <p class="mb-4">
                <a href="dashboard.php">Kembali ke Dashboard</a> |
                <a href="tambah_tahun_ajaran.php">Tambah Tahun Ajaran</a>
            </p>

            <div class="table-responsive">
                <table id="tabelTahunAjaran"
                       class="table table-striped table-bordered table-hover align-middle w-100">

                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nama Tahun Ajaran</th>
                            <th>Tanggal Mulai</th>
                            <th>Tanggal Selesai</th>
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
                                <?= htmlspecialchars($row['id']) ?>
                            </td>

                            <td>
                                <?= htmlspecialchars($row['nama']) ?>
                            </td>

                            <td>
                                <?= htmlspecialchars($row['tanggal_mulai']) ?>
                            </td>

                            <td>
                                <?= htmlspecialchars($row['tanggal_selesai']) ?>
                            </td>

                            <td>
                                <?php if ($row['status_aktif'] == 1) { ?>
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

                            <td>
                                <div class="d-flex gap-1">
                                    <a href="edit_tahun_ajaran.php?id=<?= urlencode($row['id']) ?>"
                                       class="btn btn-warning btn-sm">
                                        Edit
                                    </a>

                                    <a href="hapus_tahun_ajaran.php?id=<?= urlencode($row['id']) ?>"
                                       class="btn btn-danger btn-sm"
                                       onclick="return confirm('Yakin ingin menghapus tahun ajaran ini?');">
                                        Hapus
                                    </a>
                                </div>
                            </td>
                        </tr>
                    <?php } ?>
                    </tbody>

                </table>
            </div>

        </main>
    </div>
</div>

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

<!-- DataTables JavaScript -->
<script src="https://cdn.datatables.net/2.3.2/js/dataTables.min.js"></script>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>

<!-- Pengaturan DataTables -->
<script>
$(document).ready(function () {
    $('#tabelTahunAjaran').DataTable({
        pageLength: 10,
        lengthMenu: [5, 10, 25, 50, 100],
        searching: true,
        ordering: true,
        paging: true,
        info: true,
        autoWidth: false,
        scrollX: true,

        columnDefs: [
            {
                targets: 7,
                orderable: false,
                searchable: false
            }
        ],

        language: {
            search: "Cari:",
            searchPlaceholder: "Cari tahun ajaran...",
            lengthMenu: "Tampilkan _MENU_ data",
            info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
            infoEmpty: "Tidak ada data",
            infoFiltered: "(difilter dari _MAX_ total data)",
            zeroRecords: "Data tahun ajaran tidak ditemukan",
            emptyTable: "Belum ada data tahun ajaran",

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
</html