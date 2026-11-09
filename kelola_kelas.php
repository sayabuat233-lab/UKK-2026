
<?php
// kelola_kelas.php

include 'includes/cek_session.php';
include 'config/koneksi.php';

$sql = "SELECT * FROM t_kelas ORDER BY nama ASC";
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

    <title>Kelola Kelas</title>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css"
          rel="stylesheet">

    <!-- DataTables Bootstrap 5 CSS -->
    <link href="https://cdn.datatables.net/2.3.4/css/dataTables.bootstrap5.min.css"
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

        /* KONTEN KANAN */
        .content-area {
            flex: 1 1 0;
            min-width: 0;
            padding: 30px;
            min-height: 100vh;
            background: #f4f6fb;
        }

        /* JUDUL DAN TOMBOL */
        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 15px;
            margin-bottom: 25px;
        }

        .page-title {
            margin: 0;
            font-size: 30px;
            font-weight: 700;
            color: #17233f;
        }

        /* KARTU TABEL */
        .table-card {
            width: 100%;
            max-width: 100%;
            border: 1px solid #e8ecf4;
            border-radius: 12px;
            background: #fff;
            box-shadow: 0 5px 20px rgba(23, 35, 63, 0.04);
        }

        .table-card .card-body {
            min-width: 0;
            padding: 22px;
        }

        .table-responsive {
            width: 100%;
        }

        #tabelKelas {
            width: 100% !important;
        }

        #tabelKelas th {
            white-space: nowrap;
            vertical-align: middle;
        }

        #tabelKelas td {
            vertical-align: middle;
        }

        /* TABLET DAN HP */
        @media (max-width: 768px) {
            .app-layout > .sidebar {
                flex-basis: 220px;
                width: 220px;
            }

            .content-area {
                padding: 18px 12px;
            }

            .page-title {
                font-size: 24px;
            }

            .table-card .card-body {
                padding: 12px;
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

        <!-- JUDUL DAN TOMBOL TAMBAH -->
        <div class="page-header">

            <h1 class="page-title">Kelola Kelas</h1>

            <a href="tambah_kelas.php" class="btn btn-primary">
                + Tambah Kelas
            </a>

        </div>

        <!-- TOMBOL KEMBALI -->
        <div class="mb-3">
            <a href="dashboard.php" class="btn btn-secondary">
                Kembali ke Dashboard
            </a>
        </div>

        <!-- TABEL KELAS -->
        <div class="card table-card">
            <div class="card-body">

                <div class="table-responsive">

                    <table id="tabelKelas"
                           class="table table-striped table-bordered table-hover align-middle">

                        <thead class="table-dark">
                            <tr>
                                <th>ID</th>
                                <th>Nama Kelas</th>
                                <th>Tingkat</th>
                                <th>Jurusan</th>
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
                                    <?= htmlspecialchars((string)$row['id']) ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars($row['nama'] ?? '') ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars((string)($row['tingkat'] ?? '')) ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars($row['jurusan'] ?? '') ?>
                                </td>

                                <td>
                                    <?php if (($row['status_aktif'] ?? 0) == 1) { ?>
                                        <span class="badge bg-success">Aktif</span>
                                    <?php } else { ?>
                                        <span class="badge bg-secondary">Tidak Aktif</span>
                                    <?php } ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars($row['created_at'] ?? '') ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars($row['updated_at'] ?? '') ?>
                                </td>

                                <td class="text-nowrap">
                                    <div class="d-flex gap-2">

                                        <a href="edit_kelas.php?id=<?= urlencode((string)$row['id']) ?>"
                                           class="btn btn-warning btn-sm">
                                            Edit
                                        </a>

                                        <a href="hapus_kelas.php?id=<?= urlencode((string)$row['id']) ?>"
                                           class="btn btn-danger btn-sm"
                                           onclick="return confirm('Yakin ingin menghapus data kelas ini?');">
                                            Hapus
                                        </a>

                                    </div>
                                </td>
                            </tr>

                            <?php } ?>
                        </tbody>

                    </table>

                </div>

            </div>
        </div>

    </main>
    <!-- AKHIR KONTEN UTAMA -->

</div>

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

<!-- DataTables -->
<script src="https://cdn.datatables.net/2.3.4/js/dataTables.min.js"></script>
<script src="https://cdn.datatables.net/2.3.4/js/dataTables.bootstrap5.min.js"></script>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>

<!-- INISIALISASI DATATABLES -->
<script>
$(document).ready(function () {
    $('#tabelKelas').DataTable({
        pageLength: 10,
        lengthMenu: [5, 10, 25, 50, 100],
        ordering: true,
        searching: true,
        paging: true,
        info: true,
        autoWidth: false,

        columnDefs: [
            {
                targets: 7,
                orderable: false,
                searchable: false
            }
        ],

        language: {
            search: "Cari:",
            searchPlaceholder: "Cari data kelas...",
            lengthMenu: "Tampilkan _MENU_ data",
            info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
            infoEmpty: "Menampilkan 0 sampai 0 dari 0 data",
            infoFiltered: "(difilter dari _MAX_ total data)",
            zeroRecords: "Data kelas tidak ditemukan",
            emptyTable: "Belum ada data kelas",

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
