<?php
// kelola_walikelas.php

include 'includes/cek_session.php';
include 'config/koneksi.php';

$sql = "SELECT
            w.*,
            k.nama AS nama_kelas,
            g.nama AS nama_guru
        FROM t_wali_kelas w
        LEFT JOIN t_kelas k ON w.kelas_id = k.id
        LEFT JOIN t_guru g ON w.guru_id = g.id
        ORDER BY w.id DESC";

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

    <title>Kelola Wali Kelas</title>

    <!-- Bootstrap 5 -->
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css"
        rel="stylesheet">

    <!-- DataTables Bootstrap 5 -->
    <link
        href="https://cdn.datatables.net/2.3.4/css/dataTables.bootstrap5.min.css"
        rel="stylesheet">

    <!-- CSS asli -->
    <link rel="stylesheet" href="layout/style.css">
</head>

<body>

<div class="container-fluid">
    <div class="row">

        <!-- SIDEBAR -->
        <?php include 'layout/sidebar.php'; ?>

        <!-- KONTEN -->
        <main class="col-md-9 col-lg-10 p-4">

            <h1>Kelola Wali Kelas</h1>

            <p>
                <a href="dashboard.php">Kembali ke Dashboard</a> |
                <a href="tambah_walikelas.php">Tambah Wali Kelas</a>
            </p>

            <div class="table-responsive">

                <table id="tabelWaliKelas"
                       class="table table-striped table-bordered table-hover align-middle w-100">

                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>Tahun Ajaran ID</th>
                            <th>Kelas</th>
                            <th>Guru</th>
                            <th>Tanggal Mulai</th>
                            <th>Tanggal Selesai</th>
                            <th>Status</th>
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
                                <?= htmlspecialchars($row['tahun_ajaran_id']) ?>
                            </td>

                            <td>
                                <?= htmlspecialchars($row['nama_kelas'] ?? '-') ?>
                            </td>

                            <td>
                                <?= htmlspecialchars($row['nama_guru'] ?? '-') ?>
                            </td>

                            <td>
                                <?= htmlspecialchars($row['tanggal_mulai'] ?? '-') ?>
                            </td>

                            <td>
                                <?= htmlspecialchars($row['tanggal_selesai'] ?? '-') ?>
                            </td>

                            <td>
                                <?php
                                echo ($row['status_aktif'] == 1)
                                    ? 'Aktif'
                                    : 'Tidak Aktif';
                                ?>
                            </td>

                            <td class="text-nowrap">
                                <a href="edit_walikelas.php?id=<?= urlencode($row['id']) ?>"
                                   class="btn btn-warning btn-sm">
                                    Edit
                                </a>

                                <a href="hapus_walikelas.php?id=<?= urlencode($row['id']) ?>"
                                   class="btn btn-danger btn-sm"
                                   onclick="return confirm('Yakin ingin menghapus data wali kelas ini?');">
                                    Hapus
                                </a>
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

<!-- DataTables -->
<script src="https://cdn.datatables.net/2.3.4/js/dataTables.min.js"></script>

<!-- DataTables Bootstrap 5 -->
<script src="https://cdn.datatables.net/2.3.4/js/dataTables.bootstrap5.min.js"></script>

<!-- Bootstrap 5 JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>

<!-- Konfigurasi DataTables -->
<script>
$(document).ready(function () {

    $('#tabelWaliKelas').DataTable({
        pageLength: 10,
        lengthMenu: [5, 10, 25, 50, 100],

        searching: true,
        ordering: true,
        paging: true,
        info: true,

        // Kolom Aksi tidak ikut diurutkan atau dicari
        columnDefs: [
            {
                targets: 7,
                orderable: false,
                searchable: false
            }
        ],

        language: {
            search: "Cari:",
            searchPlaceholder: "Cari wali kelas...",
            lengthMenu: "Tampilkan _MENU_ data",
            info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
            infoEmpty: "Tidak ada data",
            infoFiltered: "(difilter dari _MAX_ total data)",
            zeroRecords: "Data wali kelas tidak ditemukan",
            emptyTable: "Belum ada data wali kelas",

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