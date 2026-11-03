
<?php
// tambah_pelanggaran_kategori.php

include 'includes/cek_session.php';
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Tambah Kategori Pelanggaran</title>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
          rel="stylesheet">

    <!-- CSS Proyek -->
    <link rel="stylesheet" href="layout/style.css">

    <style>
        body {
            margin: 0;
            background-color: #f4f6fb;
            font-family: Arial, sans-serif;
            color: #17233f;
        }

        .kategori-content {
            padding: 30px !important;
            min-height: 100vh;
            min-width: 0;
            background-color: #f4f6fb;
        }

        .kategori-header {
            margin-bottom: 25px;
        }

        .kategori-header h2 {
            font-size: 28px;
            font-weight: 700;
            margin-bottom: 8px;
            color: #17233f;
        }

        .kategori-header p {
            color: #778198;
            margin-bottom: 0;
        }

        .kategori-card {
            background-color: #fff;
            border: 1px solid #e8ecf4;
            border-radius: 15px;
            padding: 28px;
            width: 100%;
            max-width: 900px;
            box-shadow: 0 5px 20px rgba(23, 35, 63, 0.05);
        }

        .kategori-card h5 {
            font-size: 20px;
            font-weight: 700;
            margin-bottom: 25px;
            color: #17233f;
        }

        .kategori-card .form-label {
            display: block;
            font-weight: 600;
            margin-bottom: 8px;
            color: #343a40;
        }

        .kategori-card .form-control,
        .kategori-card .form-select {
            width: 100%;
            padding: 11px 13px;
            border: 1px solid #dce2ec;
            border-radius: 8px;
            font-size: 14px;
            background-color: #fff;
        }

        .kategori-card textarea {
            resize: vertical;
        }

        .kategori-card .form-control:focus,
        .kategori-card .form-select:focus {
            border-color: #3659d9;
            box-shadow: 0 0 0 3px rgba(54, 89, 217, 0.12);
        }

        .kategori-card .btn {
            padding: 10px 22px;
            border-radius: 8px;
            font-weight: 600;
        }

        .kategori-card .btn-primary {
            background-color: #3659d9;
            border-color: #3659d9;
        }

        .kategori-card .btn-primary:hover {
            background-color: #2948bd;
            border-color: #2948bd;
        }

        @media (max-width: 767px) {
            .kategori-content {
                padding: 20px 15px !important;
            }

            .kategori-header h2 {
                font-size: 23px;
            }

            .kategori-card {
                padding: 20px;
            }
        }

        @media (max-width: 480px) {
            .kategori-content {
                padding: 15px 10px !important;
            }

            .kategori-header h2 {
                font-size: 20px;
            }

            .kategori-card {
                padding: 15px;
            }
        }
    </style>
</head>

<body>

<div class="container-fluid">
    <div class="row g-0">

        <!-- SIDEBAR -->
        <?php include 'layout/sidebar.php'; ?>

        <!-- KONTEN UTAMA -->
        <main class="col-md-9 col-lg-10 kategori-content">

            <div class="kategori-header">
                <h2>Tambah Kategori Pelanggaran</h2>
                <p>
                    Tambahkan kategori untuk mengelompokkan jenis
                    pelanggaran siswa.
                </p>
            </div>

            <!-- PESAN SUKSES -->
            <?php if (isset($_SESSION['pesan_sukses'])) { ?>
                <div class="alert alert-success alert-dismissible fade show">
                    <?= htmlspecialchars($_SESSION['pesan_sukses']) ?>
                    <button type="button" class="btn-close"
                            data-bs-dismiss="alert"></button>
                </div>
                <?php unset($_SESSION['pesan_sukses']); ?>
            <?php } ?>

            <!-- PESAN ERROR -->
            <?php if (isset($_SESSION['pesan_error'])) { ?>
                <div class="alert alert-danger alert-dismissible fade show">
                    <?= htmlspecialchars($_SESSION['pesan_error']) ?>
                    <button type="button" class="btn-close"
                            data-bs-dismiss="alert"></button>
                </div>
                <?php unset($_SESSION['pesan_error']); ?>
            <?php } ?>

            <!-- FORM -->
            <div class="kategori-card">

                <h5>Form Kategori Pelanggaran</h5>

                <form action="proses_tambah_pelanggaran_kategori.php"
                      method="POST">

                    <div class="mb-4">
                        <label for="nama" class="form-label">
                            Nama Kategori
                        </label>

                        <input
                            type="text"
                            name="nama"
                            id="nama"
                            class="form-control"
                            placeholder="Contoh: Pelanggaran Ringan"
                            maxlength="100"
                            required>
                    </div>

                    <div class="mb-4">
                        <label for="deskripsi" class="form-label">
                            Deskripsi
                        </label>

                        <textarea
                            name="deskripsi"
                            id="deskripsi"
                            class="form-control"
                            rows="4"
                            placeholder="Masukkan deskripsi kategori pelanggaran"></textarea>
                    </div>

                    <div class="mb-4">
                        <label for="status_aktif" class="form-label">
                            Status Aktif
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

                    <div class="d-flex flex-wrap gap-2">

                        <button type="submit" class="btn btn-primary">
                            Simpan
                        </button>

                        <a href="kelola_pelanggaran_kategori.php"
                           class="btn btn-secondary">
                            Kembali
                        </a>

                    </div>

                </form>

            </div>

        </main>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
