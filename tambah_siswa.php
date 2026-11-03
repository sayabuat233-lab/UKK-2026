
<?php
// tambah_siswa.php

include 'includes/cek_session.php';
include 'config/koneksi.php';
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Tambah Data Siswa</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css"
        rel="stylesheet">

    <link rel="stylesheet" href="layout/style.css">

    <style>
        body {
            margin: 0;
            background: #f4f6fb;
            font-family: Arial, sans-serif;
            color: #17233f;
        }

        .siswa-content {
            padding: 30px !important;
            min-height: 100vh;
            min-width: 0;
        }

        .page-header {
            margin-bottom: 25px;
        }

        .page-header h2 {
            font-size: 28px;
            font-weight: 700;
            margin-bottom: 8px;
        }

        .page-header p {
            color: #778198;
        }

        .form-card {
            background: #fff;
            border: 1px solid #e8ecf4;
            border-radius: 12px;
            padding: 28px;
            max-width: 900px;
            width: 100%;
            box-shadow: 0 5px 20px rgba(23, 35, 63, .05);
        }

        .form-label {
            font-weight: 600;
            color: #343a40;
        }

        .form-control,
        .form-select {
            padding: 11px 13px;
            border: 1px solid #dce2ec;
            border-radius: 8px;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: #3659d9;
            box-shadow: 0 0 0 3px rgba(54, 89, 217, .12);
        }

        .btn {
            padding: 10px 22px;
            border-radius: 8px;
        }

        .btn-primary {
            background: #3659d9;
            border-color: #3659d9;
        }

        @media (max-width: 767px) {
            .siswa-content {
                padding: 20px 15px !important;
            }

            .form-card {
                padding: 18px;
            }

            .page-header h2 {
                font-size: 23px;
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
        <main class="col-md-9 col-lg-10 siswa-content">

            <div class="page-header">
                <h2>Tambah Data Siswa</h2>
                <p class="mb-0">
                    Tambahkan data siswa ke dalam sistem pelanggaran siswa.
                </p>
            </div>

            <?php if (isset($_SESSION['pesan_error'])) { ?>
                <div class="alert alert-danger">
                    <?= htmlspecialchars($_SESSION['pesan_error']) ?>
                </div>
                <?php unset($_SESSION['pesan_error']); ?>
            <?php } ?>

            <div class="form-card">

                <h5 class="mb-4">Formulir Data Siswa</h5>

                <form action="proses_tambah_siswa.php" method="POST">

                    <div class="mb-3">
                        <label for="nis" class="form-label">NIS</label>
                        <input
                            type="text"
                            name="nis"
                            id="nis"
                            class="form-control"
                            maxlength="30"
                            placeholder="Masukkan NIS"
                            required>
                    </div>

                    <div class="mb-3">
                        <label for="nisn" class="form-label">NISN</label>
                        <input
                            type="text"
                            name="nisn"
                            id="nisn"
                            class="form-control"
                            maxlength="20"
                            placeholder="Masukkan NISN"
                            required>
                    </div>

                    <div class="mb-3">
                        <label for="nama" class="form-label">Nama Siswa</label>
                        <input
                            type="text"
                            name="nama"
                            id="nama"
                            class="form-control"
                            maxlength="150"
                            placeholder="Masukkan nama lengkap siswa"
                            required>
                    </div>

                    <div class="mb-3">
                        <label for="jenis_kelamin" class="form-label">
                            Jenis Kelamin
                        </label>

                        <select
                            name="jenis_kelamin"
                            id="jenis_kelamin"
                            class="form-select"
                            required>

                            <option value="">-- Pilih Jenis Kelamin --</option>
                            <option value="L">Laki-laki</option>
                            <option value="P">Perempuan</option>

                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="tanggal_lahir" class="form-label">
                            Tanggal Lahir
                        </label>

                        <input
                            type="date"
                            name="tanggal_lahir"
                            id="tanggal_lahir"
                            class="form-control"
                            required>
                    </div>

                    <div class="mb-3">
                        <label for="alamat" class="form-label">Alamat</label>

                        <textarea
                            name="alamat"
                            id="alamat"
                            class="form-control"
                            rows="4"
                            placeholder="Masukkan alamat siswa"
                            required></textarea>
                    </div>

                    <div class="mb-4">
                        <label for="status_aktif" class="form-label">
                            Status Siswa
                        </label>

                        <select
                            name="status_aktif"
                            id="status_aktif"
                            class="form-select"
                            required>

                            <option value="1">Aktif</option>
                            <option value="0">Tidak Aktif</option>

                        </select>
                    </div>

                    <button type="submit" class="btn btn-primary">
                        Simpan Data
                    </button>

                    <a href="kelola_siswa.php" class="btn btn-secondary">
                        Kembali
                    </a>

                </form>

            </div>

        </main>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
