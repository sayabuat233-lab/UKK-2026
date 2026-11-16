
<?php
// edit_walikelas.php

include 'includes/cek_session.php';
include 'config/koneksi.php';

mysqli_report(MYSQLI_REPORT_OFF);

$error = '';
$data = null;

// Validasi ID dari URL atau form
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
}

if (!$id || $id < 1) {
    header("Location: kelola_walikelas.php");
    exit;
}

// ========================================
// PROSES SIMPAN EDIT DALAM FILE YANG SAMA
// ========================================
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $tahun_ajaran_id = filter_input(
        INPUT_POST,
        'tahun_ajaran_id',
        FILTER_VALIDATE_INT
    );

    $kelas_id = filter_input(
        INPUT_POST,
        'kelas_id',
        FILTER_VALIDATE_INT
    );

    $guru_id = filter_input(
        INPUT_POST,
        'guru_id',
        FILTER_VALIDATE_INT
    );

    $tanggal_mulai = trim($_POST['tanggal_mulai'] ?? '');
    $tanggal_selesai = trim($_POST['tanggal_selesai'] ?? '');

    $status_aktif = filter_input(
        INPUT_POST,
        'status_aktif',
        FILTER_VALIDATE_INT
    );

    if (
        !$tahun_ajaran_id ||
        !$kelas_id ||
        !$guru_id ||
        !in_array($status_aktif, [0, 1], true) ||
        $tanggal_mulai === '' ||
        $tanggal_selesai === ''
    ) {
        $error = 'Semua data wajib diisi dengan benar.';
    } else {

        $mulai = DateTime::createFromFormat(
            '!Y-m-d',
            $tanggal_mulai
        );

        $selesai = DateTime::createFromFormat(
            '!Y-m-d',
            $tanggal_selesai
        );

        if (
            !$mulai ||
            !$selesai ||
            $mulai->format('Y-m-d') !== $tanggal_mulai ||
            $selesai->format('Y-m-d') !== $tanggal_selesai
        ) {
            $error = 'Format tanggal tidak valid.';
        } elseif ($tanggal_selesai < $tanggal_mulai) {
            $error = 'Tanggal selesai tidak boleh sebelum tanggal mulai.';
        } else {

            // PENTING: nama tabel menggunakan t_wali_kelas
            $sql = "UPDATE t_wali_kelas
                    SET tahun_ajaran_id = ?,
                        kelas_id = ?,
                        guru_id = ?,
                        tanggal_mulai = ?,
                        tanggal_selesai = ?,
                        status_aktif = ?
                    WHERE id = ?";

            $stmt = mysqli_prepare($koneksi, $sql);

            if (!$stmt) {
                $error = 'Query update gagal: ' .
                    mysqli_error($koneksi);
            } else {

                mysqli_stmt_bind_param(
                    $stmt,
                    "iiissii",
                    $tahun_ajaran_id,
                    $kelas_id,
                    $guru_id,
                    $tanggal_mulai,
                    $tanggal_selesai,
                    $status_aktif,
                    $id
                );

                if (mysqli_stmt_execute($stmt)) {
                    mysqli_stmt_close($stmt);

                    header("Location: kelola_walikelas.php");
                    exit;
                } else {
                    $error = 'Gagal menyimpan: ' .
                        mysqli_stmt_error($stmt);

                    mysqli_stmt_close($stmt);
                }
            }
        }
    }
}

// ========================================
// AMBIL DATA WALI KELAS
// ========================================
$stmt = mysqli_prepare(
    $koneksi,
    "SELECT * FROM t_wali_kelas WHERE id = ?"
);

if (!$stmt) {
    die(
        'Query data wali kelas gagal: ' .
        htmlspecialchars(mysqli_error($koneksi))
    );
}

mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);

$hasil = mysqli_stmt_get_result($stmt);
$data = mysqli_fetch_assoc($hasil);

mysqli_stmt_close($stmt);

if (!$data) {
    die('Data wali kelas tidak ditemukan.');
}

// ========================================
// AMBIL DATA PILIHAN FORM
// ========================================
$tahun = mysqli_query(
    $koneksi,
    "SELECT id, nama FROM t_tahun_ajaran ORDER BY id DESC"
);

if (!$tahun) {
    die('Query tahun ajaran gagal: ' .
        htmlspecialchars(mysqli_error($koneksi)));
}

$kelas = mysqli_query(
    $koneksi,
    "SELECT id, nama FROM t_kelas ORDER BY nama ASC"
);

if (!$kelas) {
    die('Query kelas gagal: ' .
        htmlspecialchars(mysqli_error($koneksi)));
}

$guru = mysqli_query(
    $koneksi,
    "SELECT id, nama FROM t_guru ORDER BY nama ASC"
);

if (!$guru) {
    die('Query guru gagal: ' .
        htmlspecialchars(mysqli_error($koneksi)));
}

// Jika validasi gagal, tampilkan lagi nilai yang dikirim
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data['tahun_ajaran_id'] =
        $_POST['tahun_ajaran_id'] ?? $data['tahun_ajaran_id'];

    $data['kelas_id'] =
        $_POST['kelas_id'] ?? $data['kelas_id'];

    $data['guru_id'] =
        $_POST['guru_id'] ?? $data['guru_id'];

    $data['tanggal_mulai'] =
        $_POST['tanggal_mulai'] ?? $data['tanggal_mulai'];

    $data['tanggal_selesai'] =
        $_POST['tanggal_selesai'] ?? $data['tanggal_selesai'];

    $data['status_aktif'] =
        $_POST['status_aktif'] ?? $data['status_aktif'];
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Edit Wali Kelas - Pelanggaran Siswa</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet">

    <style>
        body {
            margin: 0;
            background: #f3f5fb;
            font-family: Arial, sans-serif;
            color: #172b4d;
        }

        .sidebar {
            width: 260px;
            height: 100vh;
            background: #212529;
            color: white;
            position: fixed;
            top: 0;
            left: 0;
            padding: 15px;
            overflow-y: auto;
            z-index: 1000;
        }

        .sidebar-title {
            font-size: 21px;
            color: white;
            padding: 5px 0 20px;
            margin: 0;
        }

        .sidebar .nav-link {
            color: white;
            padding: 10px 15px;
            margin-bottom: 5px;
            border-radius: 5px;
            font-size: 14px;
        }

        .sidebar .nav-link:hover {
            background: #343a40;
            color: white;
        }

        .sidebar .nav-link.active {
            background: #0d6efd;
            color: white;
        }

        .sidebar hr {
            border-color: #454545;
            margin-top: 20px;
            margin-bottom: 15px;
        }

        .sidebar .logout {
            color: #dc3545;
        }

        .main-content {
            margin-left: 260px;
            padding: 25px;
            min-height: 100vh;
        }

        .page-title {
            font-size: 36px;
            font-weight: 400;
            color: #172b4d;
        }

        .form-card {
            background: white;
            border: 1px solid #d6d6d6;
            border-radius: 7px;
            padding: 25px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, .05);
        }

        .form-label {
            font-weight: 500;
            color: #212529;
        }

        .form-control,
        .form-select {
            padding: 10px 12px;
            border-radius: 5px;
        }

        .btn {
            border-radius: 5px;
            padding: 9px 16px;
        }

        @media (max-width: 768px) {
            .sidebar {
                width: 200px;
                padding: 10px;
            }

            .main-content {
                margin-left: 200px;
                padding: 15px;
            }

            .page-title {
                font-size: 28px;
            }
        }

        @media (max-width: 576px) {
            .sidebar {
                width: 150px;
                padding: 7px;
            }

            .sidebar-title {
                font-size: 16px;
            }

            .sidebar .nav-link {
                font-size: 11px;
                padding: 8px;
            }

            .main-content {
                margin-left: 150px;
                padding: 12px;
            }

            .form-card {
                padding: 15px;
            }

            .page-title {
                font-size: 23px;
            }
        }
    </style>
</head>

<body>

    <!-- SIDEBAR -->
    <aside class="sidebar">

        <h5 class="sidebar-title">Pelanggaran Siswa</h5>

        <nav class="nav flex-column">

            <a href="dashboard.php" class="nav-link">
                Dashboard
            </a>

            <a href="kelola_siswa.php" class="nav-link">
                Data Siswa
            </a>

            <a href="kelola_guru.php" class="nav-link">
                Data Guru
            </a>

            <a href="kelola_kelas.php" class="nav-link">
                Data Kelas
            </a>

            <a href="kelola_tahun_ajaran.php" class="nav-link">
                Data Tahun Ajaran
            </a>

            <a href="kelola_walikelas.php" class="nav-link active">
                Data Wali Kelas
            </a>

            <a href="penempatan_siswa.php" class="nav-link">
                Penempatan Siswa
            </a>

            <a href="kelola_pelanggaran_kategori.php" class="nav-link">
                Kategori Pelanggaran
            </a>

            <a href="about.php" class="nav-link">
                About Me
            </a>

            <hr>

            <a href="logout.php"
               class="nav-link logout"
               onclick="return confirm('Yakin ingin logout?')">
                Logout
            </a>

        </nav>
    </aside>

    <!-- KONTEN UTAMA -->
    <main class="main-content">

        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4">

            <h1 class="page-title mb-0">Edit Wali Kelas</h1>

            <a href="kelola_walikelas.php" class="btn btn-primary">
                Kembali ke Data Wali Kelas
            </a>

        </div>

        <div class="form-card">

            <h5 class="mb-1">Form Edit Wali Kelas</h5>

            <p class="text-secondary mb-4">
                Ubah informasi wali kelas sesuai data terbaru.
            </p>

            <?php if ($error !== '') { ?>
                <div class="alert alert-danger">
                    <?= htmlspecialchars($error) ?>
                </div>
            <?php } ?>

            <!-- FORM UPDATE KE FILE INI SENDIRI -->
            <form action="edit_walikelas.php?id=<?= (int) $id ?>"
                  method="POST">

                <input
                    type="hidden"
                    name="id"
                    value="<?= (int) $id ?>">

                <div class="row g-3">

                    <!-- TAHUN AJARAN -->
                    <div class="col-md-6">

                        <label for="tahun_ajaran_id" class="form-label">
                            Tahun Ajaran
                        </label>

                        <select
                            name="tahun_ajaran_id"
                            id="tahun_ajaran_id"
                            class="form-select"
                            required>

                            <option value="">-- Pilih Tahun Ajaran --</option>

                            <?php while ($row = mysqli_fetch_assoc($tahun)) { ?>
                                <option
                                    value="<?= (int) $row['id'] ?>"
                                    <?= (string) $row['id'] ===
                                        (string) $data['tahun_ajaran_id']
                                        ? 'selected' : '' ?>>

                                    <?= htmlspecialchars($row['nama']) ?>

                                </option>
                            <?php } ?>

                        </select>
                    </div>

                    <!-- KELAS -->
                    <div class="col-md-6">

                        <label for="kelas_id" class="form-label">
                            Kelas
                        </label>

                        <select
                            name="kelas_id"
                            id="kelas_id"
                            class="form-select"
                            required>

                            <option value="">-- Pilih Kelas --</option>

                            <?php while ($row = mysqli_fetch_assoc($kelas)) { ?>
                                <option
                                    value="<?= (int) $row['id'] ?>"
                                    <?= (string) $row['id'] ===
                                        (string) $data['kelas_id']
                                        ? 'selected' : '' ?>>

                                    <?= htmlspecialchars($row['nama']) ?>

                                </option>
                            <?php } ?>

                        </select>
                    </div>

                    <!-- GURU -->
                    <div class="col-md-6">

                        <label for="guru_id" class="form-label">
                            Nama Guru
                        </label>

                        <select
                            name="guru_id"
                            id="guru_id"
                            class="form-select"
                            required>

                            <option value="">-- Pilih Guru --</option>

                            <?php while ($row = mysqli_fetch_assoc($guru)) { ?>
                                <option
                                    value="<?= (int) $row['id'] ?>"
                                    <?= (string) $row['id'] ===
                                        (string) $data['guru_id']
                                        ? 'selected' : '' ?>>

                                    <?= htmlspecialchars($row['nama']) ?>

                                </option>
                            <?php } ?>

                        </select>
                    </div>

                    <!-- TANGGAL MULAI -->
                    <div class="col-md-6">

                        <label for="tanggal_mulai" class="form-label">
                            Tanggal Mulai
                        </label>

                        <input
                            type="date"
                            name="tanggal_mulai"
                            id="tanggal_mulai"
                            class="form-control"
                            value="<?= htmlspecialchars($data['tanggal_mulai'] ?? '') ?>"
                            required>
                    </div>

                    <!-- TANGGAL SELESAI -->
                    <div class="col-md-6">

                        <label for="tanggal_selesai" class="form-label">
                            Tanggal Selesai
                        </label>

                        <input
                            type="date"
                            name="tanggal_selesai"
                            id="tanggal_selesai"
                            class="form-control"
                            value="<?= htmlspecialchars($data['tanggal_selesai'] ?? '') ?>"
                            required>
                    </div>

                    <!-- STATUS -->
                    <div class="col-md-6">

                        <label for="status_aktif" class="form-label">
                            Status Aktif
                        </label>

                        <select
                            name="status_aktif"
                            id="status_aktif"
                            class="form-select"
                            required>

                            <option
                                value="1"
                                <?= (string) $data['status_aktif'] === '1'
                                    ? 'selected' : '' ?>>
                                Aktif
                            </option>

                            <option
                                value="0"
                                <?= (string) $data['status_aktif'] === '0'
                                    ? 'selected' : '' ?>>
                                Tidak Aktif
                            </option>

                        </select>
                    </div>

                </div>

                <hr class="my-4">

                <div class="d-flex justify-content-end gap-2 flex-wrap">

                    <a href="kelola_walikelas.php"
                       class="btn btn-secondary">
                        Batal
                    </a>

                    <button type="submit" class="btn btn-primary">
                        Simpan Edit
                    </button>

                </div>

            </form>
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
