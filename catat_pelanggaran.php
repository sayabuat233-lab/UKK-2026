
<?php
// catat_pelanggaran.php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include 'includes/cek_session.php';
include 'config/koneksi.php';

mysqli_report(MYSQLI_REPORT_OFF);

// Hak akses
if (!in_array($_SESSION['role'] ?? '', ['guru', 'admin'], true)) {
    header('Location: login.php');
    exit;
}

$error = '';
$tanggal = date('Y-m-d');

// ID akun yang sedang login dari tabel t_users
$user_id = (int) ($_SESSION['id'] ?? $_SESSION['user_id'] ?? 0);

// Cari ID guru berdasarkan user_id akun login
$guru_id = 0;

if ($user_id > 0) {
    $stmt_guru = mysqli_prepare(
        $koneksi,
        "SELECT id FROM t_guru WHERE user_id = ? LIMIT 1"
    );

    if ($stmt_guru) {
        mysqli_stmt_bind_param($stmt_guru, "i", $user_id);
        mysqli_stmt_execute($stmt_guru);

        $hasil_guru = mysqli_stmt_get_result($stmt_guru);
        $data_guru = mysqli_fetch_assoc($hasil_guru);

        if ($data_guru) {
            $guru_id = (int) $data_guru['id'];
        }

        mysqli_stmt_close($stmt_guru);
    }
}

// Proses menyimpan pelanggaran
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $siswa_id = (int) ($_POST['siswa_id'] ?? 0);
    $pelanggaran_id = (int) ($_POST['pelanggaran_id'] ?? 0);
    $keterangan = trim($_POST['keterangan'] ?? '');
    $tanggal = trim($_POST['tanggal'] ?? date('Y-m-d'));

    if ($guru_id <= 0) {
        $error = 'Akun yang sedang login belum terhubung dengan data guru. Pastikan user_id pada tabel t_guru sama dengan id akun pada tabel t_users.';

    } elseif ($siswa_id <= 0 || $pelanggaran_id <= 0) {
        $error = 'Silakan pilih siswa dan jenis pelanggaran.';

    } elseif (
        !preg_match('/^\d{4}-\d{2}-\d{2}$/', $tanggal) ||
        !checkdate(
            (int) substr($tanggal, 5, 2),
            (int) substr($tanggal, 8, 2),
            (int) substr($tanggal, 0, 4)
        )
    ) {
        $error = 'Tanggal pelanggaran tidak valid.';

    } else {

        // Pastikan siswa tersedia
        $stmt_cek_siswa = mysqli_prepare(
            $koneksi,
            "SELECT id FROM t_siswa WHERE id = ?"
        );

        mysqli_stmt_bind_param($stmt_cek_siswa, "i", $siswa_id);
        mysqli_stmt_execute($stmt_cek_siswa);
        $cek_siswa = mysqli_stmt_get_result($stmt_cek_siswa);
        $siswa_valid = mysqli_num_rows($cek_siswa) > 0;
        mysqli_stmt_close($stmt_cek_siswa);

        // Pastikan jenis pelanggaran tersedia
        $stmt_cek_pelanggaran = mysqli_prepare(
            $koneksi,
            "SELECT id FROM t_pelanggaran WHERE id = ?"
        );

        mysqli_stmt_bind_param(
            $stmt_cek_pelanggaran,
            "i",
            $pelanggaran_id
        );

        mysqli_stmt_execute($stmt_cek_pelanggaran);
        $cek_pelanggaran = mysqli_stmt_get_result(
            $stmt_cek_pelanggaran
        );

        $pelanggaran_valid = mysqli_num_rows($cek_pelanggaran) > 0;

        mysqli_stmt_close($stmt_cek_pelanggaran);

        if (!$siswa_valid) {
            $error = 'Data siswa tidak ditemukan.';

        } elseif (!$pelanggaran_valid) {
            $error = 'Jenis pelanggaran tidak ditemukan.';

        } else {

            // guru_id harus menggunakan t_guru.id
            $sql = "INSERT INTO t_pelanggaran_siswa
                    (siswa_id, pelanggaran_id, keterangan, tanggal, guru_id)
                    VALUES (?, ?, ?, ?, ?)";

            $stmt = mysqli_prepare($koneksi, $sql);

            if (!$stmt) {
                $error = 'Query gagal: ' . mysqli_error($koneksi);

            } else {

                mysqli_stmt_bind_param(
                    $stmt,
                    "iissi",
                    $siswa_id,
                    $pelanggaran_id,
                    $keterangan,
                    $tanggal,
                    $guru_id
                );

                if (mysqli_stmt_execute($stmt)) {

                    mysqli_stmt_close($stmt);

                    $_SESSION['pesan_sukses'] =
                        'Pelanggaran berhasil dicatat!';

                    header('Location: riwayat.php');
                    exit;

                } else {

                    $error = 'Gagal menyimpan pelanggaran: ' .
                        mysqli_stmt_error($stmt);

                    mysqli_stmt_close($stmt);
                }
            }
        }
    }
}

// Ambil data siswa
$siswa = mysqli_query(
    $koneksi,
    "SELECT id, nama FROM t_siswa ORDER BY nama ASC"
);

// Ambil data jenis pelanggaran
$pelanggaran = mysqli_query(
    $koneksi,
    "SELECT id, nama, poin FROM t_pelanggaran ORDER BY nama ASC"
);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Catat Pelanggaran - Pelanggaran Siswa</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

    <link rel="stylesheet" href="layout/style.css">

    <style>
        body {
            margin: 0;
            background: #f4f6fb;
            font-family: Arial, sans-serif;
        }

        .app-layout {
            display: flex;
            min-height: 100vh;
            align-items: stretch;
        }

        .sidebar {
            flex: 0 0 260px;
            width: 260px;
            min-height: 100vh;
        }

        .content-area {
            flex: 1;
            min-width: 0;
            padding: 30px 35px;
        }

        .page-title {
            color: #172b4d;
            font-weight: 700;
            margin-bottom: 24px;
        }

        .form-card {
            background: #fff;
            padding: 25px;
            border-radius: 16px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, .05);
        }

        .form-control,
        .form-select {
            padding: 12px;
            border: 1px solid #ced4da;
            border-radius: 8px;
        }

        .btn {
            border-radius: 8px;
            padding: 9px 20px;
        }

        @media (max-width: 768px) {
            .app-layout {
                flex-direction: column;
            }

            .sidebar {
                width: 100%;
                min-height: auto;
                flex: none;
            }

            .content-area {
                padding: 20px 15px;
            }

            .form-card {
                padding: 18px;
            }
        }
    </style>
</head>

<body>

<div class="app-layout">

    <?php include 'layout/sidebar.php'; ?>

    <main class="content-area">

        <h4 class="page-title">
            Catat Pelanggaran Siswa
        </h4>

        <?php if ($error !== ''): ?>
            <div class="alert alert-danger">
                <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>

        <?php if (isset($_SESSION['pesan_sukses'])): ?>
            <div class="alert alert-success alert-dismissible fade show">
                <?= htmlspecialchars($_SESSION['pesan_sukses']) ?>

                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="alert"
                    aria-label="Tutup"
                ></button>
            </div>

            <?php unset($_SESSION['pesan_sukses']); ?>
        <?php endif; ?>

        <?php if (!$siswa || !$pelanggaran): ?>
            <div class="alert alert-danger">
                Gagal mengambil data:
                <?= htmlspecialchars(mysqli_error($koneksi)) ?>
            </div>
        <?php endif; ?>

        <div class="form-card">

            <form method="POST" action="catat_pelanggaran.php">

                <div class="row mb-3 align-items-center">
                    <label class="col-md-3 col-form-label fw-semibold">
                        Tanggal
                    </label>

                    <div class="col-md-9">
                        <input
                            type="date"
                            name="tanggal"
                            class="form-control"
                            value="<?= htmlspecialchars($tanggal) ?>"
                            required
                        >
                    </div>
                </div>

                <div class="row mb-3 align-items-center">
                    <label class="col-md-3 col-form-label fw-semibold">
                        Nama Siswa
                    </label>

                    <div class="col-md-9">
                        <select name="siswa_id" class="form-select" required>
                            <option value="">-- Pilih Siswa --</option>

                            <?php if ($siswa): ?>
                                <?php while ($s = mysqli_fetch_assoc($siswa)): ?>
                                    <option value="<?= (int) $s['id'] ?>">
                                        <?= htmlspecialchars($s['nama']) ?>
                                    </option>
                                <?php endwhile; ?>
                            <?php endif; ?>
                        </select>
                    </div>
                </div>

                <div class="row mb-3 align-items-center">
                    <label class="col-md-3 col-form-label fw-semibold">
                        Jenis Pelanggaran
                    </label>

                    <div class="col-md-9">
                        <select
                            name="pelanggaran_id"
                            class="form-select"
                            required
                        >
                            <option value="">
                                -- Pilih Pelanggaran --
                            </option>

                            <?php if ($pelanggaran): ?>
                                <?php while ($p = mysqli_fetch_assoc($pelanggaran)): ?>
                                    <option value="<?= (int) $p['id'] ?>">
                                        <?= htmlspecialchars($p['nama']) ?>
                                        (<?= (int) $p['poin'] ?> poin)
                                    </option>
                                <?php endwhile; ?>
                            <?php endif; ?>
                        </select>
                    </div>
                </div>

                <div class="row mb-4">
                    <label class="col-md-3 col-form-label fw-semibold">
                        Keterangan
                    </label>

                    <div class="col-md-9">
                        <textarea
                            name="keterangan"
                            class="form-control"
                            rows="3"
                            placeholder="Tambahkan keterangan jika diperlukan"
                        ></textarea>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-9 offset-md-3">

                        <button
                            type="submit"
                            class="btn btn-primary"
                            <?= (!$siswa || !$pelanggaran || $guru_id <= 0)
                                ? 'disabled' : '' ?>
                        >
                            Simpan Pelanggaran
                        </button>

                        <a
                            href="dashboard_guru.php"
                            class="btn btn-secondary ms-2"
                        >
                            Batal
                        </a>

                    </div>
                </div>

            </form>

        </div>

    </main>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
