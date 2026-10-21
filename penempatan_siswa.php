<?php
// penempatan_siswa.php

include 'includes/cek_session.php';
include 'config/koneksi.php';

// Ambil daftar siswa
$siswa = mysqli_query(
    $koneksi,
    "SELECT id, nis, nama
     FROM t_siswa
     WHERE status_aktif = 1
     ORDER BY nama ASC"
);

// Ambil daftar tahun ajaran
$tahun = mysqli_query(
    $koneksi,
    "SELECT id, nama
     FROM t_tahun_ajaran
     WHERE status_aktif = 1
     ORDER BY id DESC"
);

// Ambil daftar kelas
$kelas = mysqli_query(
    $koneksi,
    "SELECT id, nama, tingkat, jurusan
     FROM t_kelas
     WHERE status_aktif = 1
     ORDER BY nama ASC"
);

// Ambil data penempatan siswa
$sql = "SELECT
            ks.id,
            s.nis,
            s.nama AS nama_siswa,
            ta.nama AS tahun_ajaran,
            k.nama AS nama_kelas,
            k.tingkat,
            k.jurusan,
            ks.tanggal_mulai,
            ks.tanggal_selesai,
            ks.status_aktif
        FROM t_kelas_siswa ks
        LEFT JOIN t_siswa s ON ks.siswa_id = s.id
        LEFT JOIN t_tahun_ajaran ta ON ks.tahun_ajaran_id = ta.id
        LEFT JOIN t_kelas k ON ks.kelas_id = k.id
        ORDER BY ks.id DESC";

$hasil = mysqli_query($koneksi, $sql);

if (!$siswa || !$tahun || !$kelas || !$hasil) {
    die("Query gagal: " . mysqli_error($koneksi));
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Penempatan Siswa</title>

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

            <h1>Penempatan Siswa</h1>

            <p>
                <a href="dashboard.php">Kembali ke Dashboard</a>
            </p>

            <!-- Pesan sukses -->
            <?php if (isset($_SESSION['pesan_sukses'])) { ?>
                <div class="alert alert-success">
                    <?= htmlspecialchars($_SESSION['pesan_sukses']) ?>
                </div>
                <?php unset($_SESSION['pesan_sukses']); ?>
            <?php } ?>

            <!-- Pesan error -->
            <?php if (isset($_SESSION['pesan_error'])) { ?>
                <div class="alert alert-danger">
                    <?= htmlspecialchars($_SESSION['pesan_error']) ?>
                </div>
                <?php unset($_SESSION['pesan_error']); ?>
            <?php } ?>

            <h3>Form Penempatan Siswa</h3>

            <form action="proses_simpan_penempatan.php" method="POST">

                <table cellpadding="6">

                    <tr>
                        <td>Siswa</td>
                        <td>:</td>
                        <td>
                            <select name="siswa_id" required>
                                <option value="">-- Pilih Siswa --</option>

                                <?php while ($s = mysqli_fetch_assoc($siswa)) { ?>
                                    <option value="<?= htmlspecialchars($s['id']) ?>">
                                        <?= htmlspecialchars($s['nis'] . ' - ' . $s['nama']) ?>
                                    </option>
                                <?php } ?>
                            </select>
                        </td>
                    </tr>

                    <tr>
                        <td>Tahun Ajaran</td>
                        <td>:</td>
                        <td>
                            <select name="tahun_ajaran_id" required>
                                <option value="">-- Pilih Tahun Ajaran --</option>

                                <?php while ($ta = mysqli_fetch_assoc($tahun)) { ?>
                                    <option value="<?= htmlspecialchars($ta['id']) ?>">
                                        <?= htmlspecialchars($ta['nama']) ?>
                                    </option>
                                <?php } ?>
                            </select>
                        </td>
                    </tr>

                    <tr>
                        <td>Kelas</td>
                        <td>:</td>
                        <td>
                            <select name="kelas_id" required>
                                <option value="">-- Pilih Kelas --</option>

                                <?php while ($k = mysqli_fetch_assoc($kelas)) { ?>
                                    <option value="<?= htmlspecialchars($k['id']) ?>">
                                        <?= htmlspecialchars(
                                            $k['nama'] . ' - Tingkat ' .
                                            $k['tingkat'] . ' - ' . $k['jurusan']
                                        ) ?>
                                    </option>
                                <?php } ?>
                            </select>
                        </td>
                    </tr>

                    <tr>
                        <td>Tanggal Mulai</td>
                        <td>:</td>
                        <td>
                            <input type="date" name="tanggal_mulai" required>
                        </td>
                    </tr>

                    <tr>
                        <td>Tanggal Selesai</td>
                        <td>:</td>
                        <td>
                            <input type="date" name="tanggal_selesai" required>
                        </td>
                    </tr>

                    <tr>
                        <td>Status</td>
                        <td>:</td>
                        <td>
                            <select name="status_aktif" required>
                                <option value="1">Aktif</option>
                                <option value="0">Tidak Aktif</option>
                            </select>
                        </td>
                    </tr>

                    <tr>
                        <td colspan="3">
                            <input type="submit"
                                   value="Simpan Penempatan"
                                   class="btn btn-primary">
                        </td>
                    </tr>

                </table>

            </form>

            <hr>

            <h3>Data Penempatan Siswa</h3>

            <div class="table-responsive">

                <table id="tabelPenempatan"
                       class="table table-striped table-bordered table-hover align-middle w-100">

                    <thead class="table-dark">
                        <tr>
                            <th>No</th>
                            <th>NIS</th>
                            <th>Nama Siswa</th>
                            <th>Tahun Ajaran</th>
                            <th>Kelas</th>
                            <th>Tingkat</th>
                            <th>Jurusan</th>
                            <th>Tanggal Mulai</th>
                            <th>Tanggal Selesai</th>
                            <th>Status</th>
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
                                <?= htmlspecialchars($row['nis'] ?? '-') ?>
                            </td>

                            <td>
                                <?= htmlspecialchars($row['nama_siswa'] ?? '-') ?>
                            </td>

                            <td>
                                <?= htmlspecialchars($row['tahun_ajaran'] ?? '-') ?>
                            </td>

                            <td>
                                <?= htmlspecialchars($row['nama_kelas'] ?? '-') ?>
                            </td>

                            <td>
                                <?= htmlspecialchars($row['tingkat'] ?? '-') ?>
                            </td>

                            <td>
                                <?= htmlspecialchars($row['jurusan'] ?? '-') ?>
                            </td>

                            <td>
                                <?= htmlspecialchars($row['tanggal_mulai'] ?? '-') ?>
                            </td>

                            <td>
                                <?= htmlspecialchars($row['tanggal_selesai'] ?? '-') ?>
                            </td>

                            <td>
                                <?php if ($row['status_aktif'] == 1) { ?>
                                    <span class="badge bg-success">Aktif</span>
                                <?php } else { ?>
                                    <span class="badge bg-secondary">Tidak Aktif</span>
                                <?php } ?>
                            </td>
                        </tr>

                    <?php } ?>

                    </tbody>

                </table>

            </div>

        </main>
    </div>
</div>

<!-- jQuery: wajib dimuat sebelum DataTables -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

<!-- DataTables -->
<script src="https://cdn.datatables.net/2.3.4/js/dataTables.min.js"></script>

<!-- DataTables Bootstrap 5 -->
<script src="https://cdn.datatables.net/2.3.4/js/dataTables.bootstrap5.min.js"></script>

<!-- Bootstrap 5 JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>

<!-- Aktifkan DataTables -->
<script>
$(document).ready(function () {

    $('#tabelPenempatan').DataTable({
        pageLength: 10,

        lengthMenu: [5, 10, 25, 50, 100],

        searching: true,
        ordering: true,
        paging: true,
        info: true,

        language: {
            search: "Cari:",
            searchPlaceholder: "Cari penempatan siswa...",
            lengthMenu: "Tampilkan _MENU_ data",
            info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
            infoEmpty: "Tidak ada data",
            infoFiltered: "(difilter dari _MAX_ total data)",
            zeroRecords: "Data penempatan siswa tidak ditemukan",
            emptyTable: "Belum ada data penempatan siswa",

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