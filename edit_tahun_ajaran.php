
<?php
// edit_tahun_ajaran.php

include 'includes/cek_session.php';
include 'config/koneksi.php';

if (
    !isset($_GET['id']) ||
    !ctype_digit((string) $_GET['id']) ||
    (int) $_GET['id'] <= 0
) {
    header("Location: kelola_tahun_ajaran.php");
    exit;
}

$id = (int) $_GET['id'];

$stmt = mysqli_prepare(
    $koneksi,
    "SELECT * FROM t_tahun_ajaran WHERE id = ?"
);

mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);

$hasil = mysqli_stmt_get_result($stmt);
$data = mysqli_fetch_assoc($hasil);

mysqli_stmt_close($stmt);

if (!$data) {
    header("Location: kelola_tahun_ajaran.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Edit Tahun Ajaran</title>

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
            margin-left: 260px;
            width: calc(100% - 260px);
            min-height: 100vh;
            padding: 25px;
            box-sizing: border-box;
        }

        .page-title {
            font-size: 32px;
            font-weight: 600;
            color: #172b4d;
        }

        .form-card {
            background: #fff;
            padding: 25px;
            border: 1px solid #dee2e6;
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, .04);
        }

        .form-label {
            font-weight: 500;
        }

        .form-control,
        .form-select {
            padding: 10px 12px;
            border-radius: 6px;
        }

        .btn {
            padding: 9px 16px;
            border-radius: 6px;
        }

        @media (max-width: 767.98px) {
            .main-content {
                margin-left: 0;
                width: 100%;
                padding: 15px;
            }

            .page-title {
                font-size: 25px;
            }
        }
    </style>
</head>

<body>

<div class="container-fluid">
    <div class="row g-0">

        <?php include 'layout/sidebar.php'; ?>

        <main class="main-content">

            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">

                <h1 class="page-title mb-0">
                    Edit Tahun Ajaran
                </h1>

                <a href="kelola_tahun_ajaran.php"
                   class="btn btn-primary">
                    Kembali ke Data Tahun Ajaran
                </a>

            </div>

            <div class="form-card">

                <h5 class="mb-1">Form Edit Tahun Ajaran</h5>

                <p class="text-secondary mb-4">
                    Ubah informasi tahun ajaran sesuai data terbaru.
                </p>

                <form action="proses_edit_tahun_ajaran.php" method="POST">

                    <input
                        type="hidden"
                        name="id"
                        value="<?= (int) $data['id'] ?>">

                    <div class="row g-3">

                        <div class="col-md-6">
                            <label for="nama" class="form-label">
                                Nama Tahun Ajaran
                            </label>

                            <input
                                type="text"
                                class="form-control"
                                id="nama"
                                name="nama"
                                value="<?= htmlspecialchars($data['nama'] ?? '') ?>"
                                placeholder="Contoh: 2026/2027"
                                required>
                        </div>

                        <div class="col-md-6">
                            <label for="status_aktif" class="form-label">
                                Status Aktif
                            </label>

                            <select
                                class="form-select"
                                id="status_aktif"
                                name="status_aktif"
                                required>

                                <option value="1"
                                    <?= (string) ($data['status_aktif'] ?? '') === '1' ? 'selected' : '' ?>>
                                    Aktif
                                </option>

                                <option value="0"
                                    <?= (string) ($data['status_aktif'] ?? '') === '0' ? 'selected' : '' ?>>
                                    Tidak Aktif
                                </option>

                            </select>
                        </div>

                        <div class="col-md-6">
                            <label for="tanggal_mulai" class="form-label">
                                Tanggal Mulai
                            </label>

                            <input
                                type="date"
                                class="form-control"
                                id="tanggal_mulai"
                                name="tanggal_mulai"
                                value="<?= htmlspecialchars($data['tanggal_mulai'] ?? '') ?>"
                                required>
                        </div>

                        <div class="col-md-6">
                            <label for="tanggal_selesai" class="form-label">
                                Tanggal Selesai
                            </label>

                            <input
                                type="date"
                                class="form-control"
                                id="tanggal_selesai"
                                name="tanggal_selesai"
                                value="<?= htmlspecialchars($data['tanggal_selesai'] ?? '') ?>"
                                required>
                        </div>

                    </div>

                    <hr class="my-4">

                    <div class="d-flex justify-content-end gap-2">

                        <a href="kelola_tahun_ajaran.php"
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
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
