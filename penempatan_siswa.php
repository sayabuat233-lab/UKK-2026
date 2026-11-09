
<?php
// penempatan_siswa.php

// Tampilkan error saat pengembangan agar halaman putih mudah diperiksa.
// Matikan display_errors setelah aplikasi selesai diperbaiki.
error_reporting(E_ALL);
ini_set('display_errors', '1');

include 'includes/cek_session.php';
include 'config/koneksi.php';

// Mengambil daftar siswa aktif
$sqlSiswa = "SELECT id, nis, nama
             FROM t_siswa
             WHERE status_aktif = 1
             ORDER BY nama ASC";

$siswa = mysqli_query($koneksi, $sqlSiswa);

if (!$siswa) {
    die("Query siswa gagal: " . htmlspecialchars(mysqli_error($koneksi)));
}

// Mengambil daftar tahun ajaran aktif
$sqlTahun = "SELECT id, nama
             FROM t_tahun_ajaran
             WHERE status_aktif = 1
             ORDER BY id DESC";

$tahun = mysqli_query($koneksi, $sqlTahun);

if (!$tahun) {
    die("Query tahun ajaran gagal: " . htmlspecialchars(mysqli_error($koneksi)));
}

// Mengambil daftar kelas aktif
$sqlKelas = "SELECT id, nama, tingkat, jurusan
             FROM t_kelas
             WHERE status_aktif = 1
             ORDER BY nama ASC";

$kelas = mysqli_query($koneksi, $sqlKelas);

if (!$kelas) {
    die("Query kelas gagal: " . htmlspecialchars(mysqli_error($koneksi)));
}

// Mengambil data penempatan siswa
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

if (!$hasil) {
    die("Query penempatan siswa gagal: " . htmlspecialchars(mysqli_error($koneksi)));
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Penempatan Siswa</title>

    <!-- Bootstrap -->
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css"
        rel="stylesheet">

    <!-- DataTables -->
    <link
        href="https://cdn.datatables.net/2.3.4/css/dataTables.bootstrap5.min.css"
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
            background: #212529;
        }

        /* Menyesuaikan pembungkus sidebar lama */
        .layout-sidebar > .col-md-3,
        .layout-sidebar > .col-lg-2 {
            width: 100%;
            max-width: 100%;
            flex: 0 0 100%;
            min-height: 100vh;
        }

        /* Konten kanan */
        .layout-content {
            flex: 1 1 0;
            min-width: 0;
            padding: 24px;
            background: #f8f9fa;
        }

        /* Form */
        .form-card {
            max-width: 850px;
            padding: 20px;
            margin-bottom: 24px;
            background: #fff;
            border: 1px solid #dee2e6;
            border-radius: 8px;
        }

        .form-penempatan td {
            padding: 8px;
            vertical-align: middle;
        }

        .form-penempatan td:first-child {
            min-width: 135px;
        }

        .form-penempatan select,
        .form-penempatan input[type="date"] {
            width: 100%;
            max-width: 450px;
            min-width: 200px;
            padding: 8px;
            border: 1px solid #ced4da;
            border-radius: 5px;
        }

        /* Tabel */
        .table-responsive {
            width: 100%;
            overflow-x: auto;
        }

        #tabelPenempatan {
            width: 100% !important;
        }

        #tabelPenempatan thead th {
            white-space: nowrap;
            vertical-align: middle;
        }

        #tabelPenempatan tbody td {
            vertical-align: middle;
        }

        /* Tampilan perangkat kecil */
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

            .form-penempatan {
                width: 100%;
            }

            .form-penempatan td:first-child {
                min-width: 95px;
            }

            .form-penempatan select,
            .form-penempatan input[type="date"] {
                min-width: 0;
            }
        }
    </style>
</head>

<body>

<div class="layout-wrapper">

    <!-- SIDEBAR -->
    <aside class="layout-sidebar">
        <?php include 'layout/sidebar.php'; ?>
    </aside>

    <!-- KONTEN UTAMA -->
    <main class="layout-content">

        <h1 class="mb-3">Penempatan Siswa</h1>

        <div class="mb-4">
            <a href="dashboard.php" class="btn btn-secondary btn-sm">
                Kembali ke Dashboard
            </a>
        </div>

        <!-- Pesan sukses -->
        <?php if (isset($_SESSION['pesan_sukses'])) { ?>
            <div class="alert alert-success alert-dismissible fade show">
                <?= htmlspecialchars($_SESSION['pesan_sukses']) ?>
                <button type="button"
                        class="btn-close"
                        data-bs-dismiss="alert"
                        aria-label="Tutup"></button>
            </div>
            <?php unset($_SESSION['pesan_sukses']); ?>
        <?php } ?>

        <!-- Pesan error -->
        <?php if (isset($_SESSION['pesan_error'])) { ?>
            <div class="alert alert-danger alert-dismissible fade show">
                <?= htmlspecialchars($_SESSION['pesan_error']) ?>
                <button type="button"
                        class="btn-close"
                        data-bs-dismiss="alert"
                        aria-label="Tutup"></button>
            </div>
            <?php unset($_SESSION['pesan_error']); ?>
        <?php } ?>

        <!-- FORM PENEMPATAN -->
        <section class="form-card">

            <h3 class="mb-3">Form Penempatan Siswa</h3>

            <form action="proses_simpan_penempatan.php"
                  method="POST">

                <table class="form-penempatan">
                    <tbody>

                        <tr>
                            <td><label for="siswa_id">Siswa</label></td>
                            <td>:</td>
                            <td>
                                <select name="siswa_id"
                                        id="siswa_id"
                                        required>
                                    <option value="">-- Pilih Siswa --</option>

                                    <?php while ($s = mysqli_fetch_assoc($siswa)) { ?>
                                        <option value="<?= htmlspecialchars((string) $s['id']) ?>">
                                            <?= htmlspecialchars($s['nis'] . ' - ' . $s['nama']) ?>
                                        </option>
                                    <?php } ?>
                                </select>
                            </td>
                        </tr>

                        <tr>
                            <td><label for="tahun_ajaran_id">Tahun Ajaran</label></td>
                            <td>:</td>
                            <td>
                                <select name="tahun_ajaran_id"
                                        id="tahun_ajaran_id"
                                        required>
                                    <option value="">-- Pilih Tahun Ajaran --</option>

                                    <?php while ($ta = mysqli_fetch_assoc($tahun)) { ?>
                                        <option value="<?= htmlspecialchars((string) $ta['id']) ?>">
                                            <?= htmlspecialchars($ta['nama']) ?>
                                        </option>
                                    <?php } ?>
                                </select>
                            </td>
                        </tr>

                        <tr>
                            <td><label for="kelas_id">Kelas</label></td>
                            <td>:</td>
                            <td>
                                <select name="kelas_id"
                                        id="kelas_id"
                                        required>
                                    <option value="">-- Pilih Kelas --</option>

                                    <?php while ($k = mysqli_fetch_assoc($kelas)) { ?>
                                        <option value="<?= htmlspecialchars((string) $k['id']) ?>">
                                            <?= htmlspecialchars(
                                                $k['nama'] . ' - Tingkat ' .
                                                $k['tingkat'] . ' - ' .
                                                $k['jurusan']
                                            ) ?>
                                        </option>
                                    <?php } ?>
                                </select>
                            </td>
                        </tr>

                        <tr>
                            <td><label for="tanggal_mulai">Tanggal Mulai</label></td>
                            <td>:</td>
                            <td>
                                <input type="date"
                                       name="tanggal_mulai"
                                       id="tanggal_mulai"
                                       required>
                            </td>
                        </tr>

                        <tr>
                            <td><label for="tanggal_selesai">Tanggal Selesai</label></td>
                            <td>:</td>
                            <td>
                                <input type="date"
                                       name="tanggal_selesai"
                                       id="tanggal_selesai"
                                       required>
                            </td>
                        </tr>

                        <tr>
                            <td><label for="status_aktif">Status</label></td>
                            <td>:</td>
                            <td>
                                <select name="status_aktif"
                                        id="status_aktif"
                                        required>
                                    <option value="1">Aktif</option>
                                    <option value="0">Tidak Aktif</option>
                                </select>
                            </td>
                        </tr>

                        <tr>
                            <td colspan="3" class="pt-3">
                                <button type="submit" class="btn btn-primary">
                                    Simpan Penempatan
                                </button>
                            </td>
                        </tr>

                    </tbody>
                </table>

            </form>

        </section>

        <!-- TABEL PENEMPATAN -->
        <section>
            <h3 class="mb-3">Data Penempatan Siswa</h3>

            <div class="table-responsive">

                <table id="tabelPenempatan"
                       class="table table-striped table-bordered table-hover align-middle">

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
                                    <?= htmlspecialchars((string) ($row['tingkat'] ?? '-')) ?>
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
        </section>

    </main>
</div>

<!-- jQuery harus dimuat sebelum DataTables -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

<!-- DataTables -->
<script src="https://cdn.datatables.net/2.3.4/js/dataTables.min.js"></script>
<script src="https://cdn.datatables.net/2.3.4/js/dataTables.bootstrap5.min.js"></script>

<!-- Bootstrap -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>

<!-- Inisialisasi DataTables -->
<script>
$(document).ready(function () {

    $('#tabelPenempatan').DataTable({
        pageLength: 10,
        lengthMenu: [5, 10, 25, 50, 100],

        searching: true,
        ordering: true,
        paging: true,
        info: true,
        autoWidth: false,
        scrollX: true,

        language: {
            search: 'Cari:',
            searchPlaceholder: 'Cari penempatan siswa...',
            lengthMenu: 'Tampilkan _MENU_ data',
            info: 'Menampilkan _START_ sampai _END_ dari _TOTAL_ data',
            infoEmpty: 'Tidak ada data',
            infoFiltered: '(difilter dari _MAX_ total data)',
            zeroRecords: 'Data penempatan siswa tidak ditemukan',
            emptyTable: 'Belum ada data penempatan siswa',

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
