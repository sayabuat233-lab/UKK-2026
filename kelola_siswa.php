
<?php
// kelola_siswa.php

include 'includes/cek_session.php';
include 'config/koneksi.php';

$sql = "SELECT * FROM t_siswa ORDER BY nama ASC";
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

    <title>Kelola Siswa</title>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css"
          rel="stylesheet">

    <!-- DataTables Bootstrap 5 -->
    <link href="https://cdn.datatables.net/2.3.4/css/dataTables.bootstrap5.min.css"
          rel="stylesheet">

    <!-- CSS Utama -->
    <link rel="stylesheet" href="layout/style.css">

    <style>
        /* Layout utama: sidebar kiri dan konten kanan */
        .app-layout {
            display: flex;
            align-items: stretch;
            width: 100%;
            min-height: 100vh;
        }

        /* Sidebar tetap 260px mengikuti sidebar.php */
        .app-layout > .sidebar {
            flex: 0 0 260px;
            width: 260px;
            min-height: 100vh;
        }

        /* Konten halaman di sebelah kanan */
        .content-area {
            flex: 1 1 0;
            min-width: 0;
            padding: 30px;
            background: #f4f6fb;
        }

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

        #tabelSiswa {
            width: 100% !important;
        }

        #tabelSiswa th {
            vertical-align: middle;
            white-space: nowrap;
        }

        #tabelSiswa td {
            vertical-align: middle;
        }

        /* Layar tablet dan HP */
        @media (max-width: 768px) {
            .app-layout {
                align-items: stretch;
            }

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

            <h1 class="page-title">Kelola Siswa</h1>

            <a href="tambah_siswa.php" class="btn btn-primary">
                + Tambah Siswa
            </a>

        </div>

        <!-- TOMBOL KEMBALI -->
        <div class="mb-3">
            <a href="dashboard.php" class="btn btn-secondary">
                Kembali ke Dashboard
            </a>
        </div>

        <!-- TABEL SISWA -->
        <div class="card table-card">
            <div class="card-body">

                <div class="table-responsive">

                    <table id="tabelSiswa"
                           class="table table-striped table-bordered table-hover align-middle">

                        <thead class="table-dark">
                            <tr>
                                <th>No.</th>
                                <th>NIS</th>
                                <th>NISN</th>
                                <th>Nama Siswa</th>
                                <th>Jenis Kelamin</th>
                                <th>Tanggal Lahir</th>
                                <th>Alamat</th>
                                <th>Status Aktif</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php
                            $no = 1;

                            while ($row = mysqli_fetch_assoc($hasil)) {
                            ?>

                            <tr>
                                <td><?= $no++ ?></td>

                                <td>
                                    <?= htmlspecialchars($row['nis'] ?? '') ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars($row['nisn'] ?? '') ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars($row['nama'] ?? '') ?>
                                </td>

                                <td>
                                    <?php
                                    if (($row['jenis_kelamin'] ?? '') === 'L') {
                                        echo 'Laki-laki';
                                    } elseif (($row['jenis_kelamin'] ?? '') === 'P') {
                                        echo 'Perempuan';
                                    } else {
                                        echo '-';
                                    }
                                    ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars($row['tanggal_lahir'] ?? '') ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars($row['alamat'] ?? '') ?>
                                </td>

                                <td>
                                    <?php if (($row['status_aktif'] ?? 0) == 1) { ?>
                                        <span class="badge bg-success">Aktif</span>
                                    <?php } else { ?>
                                        <span class="badge bg-secondary">Tidak Aktif</span>
                                    <?php } ?>
                                </td>

                                <td class="text-nowrap">
                                    <a href="edit_siswa.php?id=<?= urlencode((string)$row['id']) ?>"
                                       class="btn btn-warning btn-sm">
                                        Edit
                                    </a>

                                    <a href="hapus_siswa.php?id=<?= urlencode((string)$row['id']) ?>"
                                       class="btn btn-danger btn-sm"
                                       onclick="return confirm('Yakin ingin menghapus data siswa ini?');">
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
    <!-- AKHIR KONTEN UTAMA -->

</div>

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

<!-- DataTables -->
<script src="https://cdn.datatables.net/2.3.4/js/dataTables.min.js"></script>

<!-- DataTables Bootstrap 5 -->
<script src="https://cdn.datatables.net/2.3.4/js/dataTables.bootstrap5.min.js"></script>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>

<!-- Konfigurasi DataTables -->
<script>
$(document).ready(function () {
    $('#tabelSiswa').DataTable({
        pageLength: 10,
        lengthMenu: [5, 10, 25, 50, 100],
        order: [[3, 'asc']],
        scrollX: true,

        language: {
            search: "Cari:",
            lengthMenu: "Tampilkan _MENU_ data",
            info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data siswa",
            infoEmpty: "Tidak ada data siswa",
            infoFiltered: "(difilter dari _MAX_ total data)",
            zeroRecords: "Data siswa tidak ditemukan",
            emptyTable: "Belum ada data siswa",

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
