
<?php
// laporan.php

include 'includes/cek_session.php';
include 'config/koneksi.php';

// Filter laporan
$tanggal_awal = $_GET['tanggal_awal'] ?? '';
$tanggal_akhir = $_GET['tanggal_akhir'] ?? '';
$id_kelas = $_GET['id_kelas'] ?? '';

$where = [];

if ($tanggal_awal !== '' && $tanggal_akhir !== '') {
    $awal = mysqli_real_escape_string($koneksi, $tanggal_awal);
    $akhir = mysqli_real_escape_string($koneksi, $tanggal_akhir);

    if ($awal <= $akhir) {
        $where[] = "ps.tanggal BETWEEN '$awal' AND '$akhir'";
    }
} elseif ($tanggal_awal !== '') {
    $awal = mysqli_real_escape_string($koneksi, $tanggal_awal);
    $where[] = "ps.tanggal >= '$awal'";
} elseif ($tanggal_akhir !== '') {
    $akhir = mysqli_real_escape_string($koneksi, $tanggal_akhir);
    $where[] = "ps.tanggal <= '$akhir'";
}

if ($id_kelas !== '' && ctype_digit($id_kelas)) {
    $where[] = "k.id = " . (int) $id_kelas;
}

$filter = '';

if (!empty($where)) {
    $filter = ' WHERE ' . implode(' AND ', $where);
}

// Ambil data laporan
$sql = "
    SELECT
        ps.id,
        ps.tanggal,
        ps.keterangan,
        ps.poin,
        s.nis,
        s.nama AS nama_siswa,
        k.nama AS nama_kelas,
        jp.nama_pelanggaran,
        g.nama AS nama_guru
    FROM pelanggaran_siswa ps
    INNER JOIN penempatan_siswa pen
        ON ps.penempatan_siswa_id = pen.id
    INNER JOIN data_siswa s
        ON pen.siswa_id = s.id_siswa
    INNER JOIN data_kelas k
        ON pen.kelas_id = k.id
    LEFT JOIN jenis_pelanggaran jp
        ON ps.jenis_pelanggaran_id = jp.id
    LEFT JOIN guru g
        ON ps.guru_id = g.id
    $filter
    ORDER BY ps.tanggal DESC, ps.id DESC
";

$hasil = mysqli_query($koneksi, $sql);

if (!$hasil) {
    die('Query laporan gagal: ' . mysqli_error($koneksi));
}

// Hitung jumlah pelanggaran dan total poin
$total_data = mysqli_num_rows($hasil);
$total_poin = 0;

while ($baris = mysqli_fetch_assoc($hasil)) {
    $total_poin += (int) $baris['poin'];
}

mysqli_data_seek($hasil, 0);

// Ambil daftar kelas untuk filter
$query_kelas = mysqli_query(
    $koneksi,
    "SELECT id, nama FROM data_kelas ORDER BY nama ASC"
);

if (!$query_kelas) {
    die('Query kelas gagal: ' . mysqli_error($koneksi));
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Laporan Pelanggaran Siswa</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css"
        rel="stylesheet">

    <style>
        body {
            margin: 0;
            background: #f3f5fb;
            font-family: Arial, sans-serif;
            color: #172b4d;
        }

        .main-content {
            margin-left: 292px;
            padding: 25px;
            min-height: 100vh;
        }

        .page-title {
            font-size: 30px;
            font-weight: 700;
        }

        .report-card {
            background: white;
            padding: 24px;
            border-radius: 12px;
            border: 1px solid #e8ecf4;
            box-shadow: 0 3px 12px rgba(0, 0, 0, .04);
            margin-bottom: 22px;
        }

        .summary-card {
            background: white;
            padding: 22px;
            border-radius: 10px;
            border: 1px solid #e8ecf4;
            height: 100%;
        }

        .summary-number {
            font-size: 30px;
            font-weight: 700;
            color: #3659d9;
        }

        .table th {
            background: #3659d9;
            color: white;
            white-space: nowrap;
        }

        .table td {
            vertical-align: middle;
        }

        @media print {
            .sidebar,
            .filter-section,
            .no-print,
            .btn {
                display: none !important;
            }

            .main-content {
                margin: 0;
                padding: 0;
            }

            body {
                background: white;
            }

            .report-card,
            .summary-card {
                box-shadow: none;
                border: 1px solid #ddd;
            }

            .table th {
                background: #ddd !important;
                color: black !important;
                print-color-adjust: exact;
                -webkit-print-color-adjust: exact;
            }
        }

        @media (max-width: 768px) {
            .main-content {
                margin-left: 0;
                padding: 15px;
            }

            .page-title {
                font-size: 24px;
            }
        }
    </style>
</head>

<body>

<div class="container-fluid">
    <div class="row g-0">

        <!-- SIDEBAR -->
        <?php include 'layout/sidebar.php'; ?>

        <!-- KONTEN -->
        <main class="col main-content">

            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">
                <div>
                    <h1 class="page-title mb-2">
                        Laporan Pelanggaran Siswa
                    </h1>

                    <p class="text-secondary mb-0">
                        Rekap data pelanggaran dan poin siswa.
                    </p>
                </div>

                <button
                    type="button"
                    onclick="window.print()"
                    class="btn btn-primary no-print">
                    Cetak Laporan
                </button>
            </div>

            <!-- FILTER -->
            <div class="report-card filter-section">

                <h5 class="mb-3">Filter Laporan</h5>

                <form method="GET" action="laporan.php">

                    <div class="row g-3">

                        <div class="col-md-4">
                            <label for="tanggal_awal" class="form-label">
                                Tanggal Awal
                            </label>

                            <input
                                type="date"
                                name="tanggal_awal"
                                id="tanggal_awal"
                                class="form-control"
                                value="<?= htmlspecialchars($tanggal_awal) ?>">
                        </div>

                        <div class="col-md-4">
                            <label for="tanggal_akhir" class="form-label">
                                Tanggal Akhir
                            </label>

                            <input
                                type="date"
                                name="tanggal_akhir"
                                id="tanggal_akhir"
                                class="form-control"
                                value="<?= htmlspecialchars($tanggal_akhir) ?>">
                        </div>

                        <div class="col-md-4">
                            <label for="id_kelas" class="form-label">
                                Kelas
                            </label>

                            <select
                                name="id_kelas"
                                id="id_kelas"
                                class="form-select">

                                <option value="">Semua Kelas</option>

                                <?php while ($kelas = mysqli_fetch_assoc($query_kelas)) { ?>
                                    <option
                                        value="<?= (int) $kelas['id'] ?>"
                                        <?= ($id_kelas !== '' && (string) $kelas['id'] === $id_kelas) ? 'selected' : '' ?>>

                                        <?= htmlspecialchars($kelas['nama']) ?>

                                    </option>
                                <?php } ?>

                            </select>
                        </div>

                    </div>

                    <div class="mt-3 d-flex flex-wrap gap-2">

                        <button type="submit" class="btn btn-primary">
                            Tampilkan
                        </button>

                        <a href="laporan.php" class="btn btn-secondary">
                            Reset Filter
                        </a>

                    </div>

                </form>

            </div>

            <!-- RINGKASAN -->
            <div class="row g-3 mb-4">

                <div class="col-md-6">
                    <div class="summary-card">
                        <div class="text-secondary">
                            Total Pelanggaran
                        </div>

                        <div class="summary-number">
                            <?= number_format($total_data, 0, ',', '.') ?>
                        </div>

                        <small class="text-secondary">
                            Jumlah catatan pelanggaran
                        </small>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="summary-card">
                        <div class="text-secondary">
                            Total Poin Pelanggaran
                        </div>

                        <div class="summary-number">
                            <?= number_format($total_poin, 0, ',', '.') ?>
                        </div>

                        <small class="text-secondary">
                            Akumulasi poin seluruh catatan
                        </small>
                    </div>
                </div>

            </div>

            <!-- TABEL LAPORAN -->
            <div class="report-card">

                <h5 class="mb-3">Detail Laporan Pelanggaran</h5>

                <div class="table-responsive">

                    <table class="table table-bordered table-striped">

                        <thead>
                            <tr>
                                <th>No.</th>
                                <th>Tanggal</th>
                                <th>NIS</th>
                                <th>Nama Siswa</th>
                                <th>Kelas</th>
                                <th>Jenis Pelanggaran</th>
                                <th>Poin</th>
                                <th>Guru Pencatat</th>
                                <th>Keterangan</th>
                            </tr>
                        </thead>

                        <tbody>

                            <?php if ($total_data > 0) { ?>

                                <?php $no = 1; ?>

                                <?php while ($data = mysqli_fetch_assoc($hasil)) { ?>

                                    <tr>
                                        <td><?= $no++ ?></td>

                                        <td>
                                            <?= htmlspecialchars(
                                                date('d-m-Y', strtotime($data['tanggal']))
                                            ) ?>
                                        </td>

                                        <td>
                                            <?= htmlspecialchars($data['nis'] ?? '-') ?>
                                        </td>

                                        <td>
                                            <?= htmlspecialchars($data['nama_siswa'] ?? '-') ?>
                                        </td>

                                        <td>
                                            <?= htmlspecialchars($data['nama_kelas'] ?? '-') ?>
                                        </td>

                                        <td>
                                            <?= htmlspecialchars($data['nama_pelanggaran'] ?? '-') ?>
                                        </td>

                                        <td class="text-center">
                                            <span class="badge bg-danger">
                                                <?= (int) $data['poin'] ?>
                                            </span>
                                        </td>

                                        <td>
                                            <?= htmlspecialchars($data['nama_guru'] ?? '-') ?>
                                        </td>

                                        <td>
                                            <?= htmlspecialchars($data['keterangan'] ?? '-') ?>
                                        </td>
                                    </tr>

                                <?php } ?>

                            <?php } else { ?>

                                <tr>
                                    <td colspan="9" class="text-center py-4">
                                        Tidak ada data pelanggaran yang ditemukan.
                                    </td>
                                </tr>

                            <?php } ?>

                        </tbody>

                    </table>

                </div>

                <div class="text-secondary mt-3">
                    Total <?= number_format($total_data, 0, ',', '.') ?>
                    catatan pelanggaran.
                </div>

            </div>

        </main>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
