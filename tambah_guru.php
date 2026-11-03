
<?php
// tambah_guru.php

include 'includes/cek_session.php';
include 'config/koneksi.php';
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Tambah Guru - Pelanggaran Siswa</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css"
        rel="stylesheet">

    <style>
        body {
            margin: 0;
            background: #f3f5fb;
            font-family: Arial, sans-serif;
        }

        .sidebar {
            width: 292px;
            height: 100vh;
            background: #212529;
            color: white;
            position: fixed;
            top: 0;
            left: 0;
            padding: 0 15px;
            overflow-y: auto;
            z-index: 1000;
        }

        .sidebar-title {
            font-size: 21px;
            color: white;
            padding: 15px 0 20px;
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
            margin-left: 292px;
            padding: 25px;
            min-height: 100vh;
        }

        .page-title {
            font-size: 32px;
            font-weight: 600;
            color: #172b4d;
        }

        .form-card {
            background: white;
            border: 1px solid #e8ecf4;
            border-radius: 10px;
            padding: 28px;
            max-width: 1000px;
            box-shadow: 0 3px 12px rgba(0, 0, 0, .05);
        }

        .form-label {
            font-weight: 600;
            color: #212529;
        }

        .form-control,
        .form-select {
            padding: 11px 12px;
            border-radius: 6px;
        }

        .btn {
            padding: 10px 18px;
            border-radius: 6px;
        }

        @media (max-width: 768px) {
            .sidebar {
                width: 220px;
            }

            .main-content {
                margin-left: 220px;
                padding: 15px;
            }

            .page-title {
                font-size: 26px;
            }
        }

        @media (max-width: 576px) {
            .sidebar {
                width: 180px;
                padding: 0 8px;
            }

            .sidebar-title {
                font-size: 17px;
            }

            .sidebar .nav-link {
                font-size: 12px;
                padding: 9px;
            }

            .main-content {
                margin-left: 180px;
                padding: 12px;
            }

            .form-card {
                padding: 16px;
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

            <a href="kelola_guru.php" class="nav-link active">
                Data Guru
            </a>

            <a href="kelola_kelas.php" class="nav-link">
                Data Kelas
            </a>

            <a href="kelola_tahun_ajaran.php" class="nav-link">
                Data Tahun Ajaran
            </a>

            <a href="kelola_walikelas.php" class="nav-link">
                Data Wali Kelas
            </a>

            <a href="penempatan_siswa.php" class="nav-link">
                Penempatan Siswa
            </a>

            <a href="kelola_pelanggaran_kategori.php" class="nav-link">
                Kategori Pelanggaran
            </a>

            <a href="about_me.php" class="nav-link">
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

        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">

            <div>
                <h1 class="page-title mb-2">Tambah Guru</h1>
                <p class="text-secondary mb-0">
                    Masukkan data guru sesuai struktur database.
                </p>
            </div>

            <a href="kelola_guru.php" class="btn btn-secondary">
                Kembali
            </a>

        </div>

        <?php if (isset($_SESSION['pesan_error'])) { ?>
            <div class="alert alert-danger">
                <?= htmlspecialchars($_SESSION['pesan_error']) ?>
            </div>
            <?php unset($_SESSION['pesan_error']); ?>
        <?php } ?>

        <div class="form-card">

            <h5 class="mb-2">Form Data Guru</h5>

            <p class="text-secondary mb-4">
                Lengkapi seluruh kolom yang wajib diisi.
            </p>

            <form action="proses_tambah_guru.php" method="POST">

                <div class="row g-3">

                    <div class="col-md-6">
                        <label for="nip" class="form-label">NIP</label>

                        <input
                            type="text"
                            name="nip"
                            id="nip"
                            class="form-control"
                            maxlength="30"
                            placeholder="Masukkan NIP guru"
                            required>
                    </div>

                    <div class="col-md-6">
                        <label for="nama" class="form-label">Nama Guru</label>

                        <input
                            type="text"
                            name="nama"
                            id="nama"
                            class="form-control"
                            maxlength="150"
                            placeholder="Masukkan nama lengkap guru"
                            required>
                    </div>

                    <div class="col-md-6">
                        <label for="email" class="form-label">Email</label>

                        <input
                            type="email"
                            name="email"
                            id="email"
                            class="form-control"
                            maxlength="150"
                            placeholder="contoh@email.com"
                            required>
                    </div>

                    <div class="col-md-6">
                        <label for="user_id" class="form-label">
                            ID User
                        </label>

                        <input
                            type="number"
                            name="user_id"
                            id="user_id"
                            class="form-control"
                            min="1"
                            placeholder="Masukkan ID user yang sudah terdaftar"
                            required>

                        <small class="text-secondary">
                            Isi dengan ID user yang valid pada tabel akun pengguna.
                        </small>
                    </div>

                    <div class="col-md-6">
                        <label for="status_aktif" class="form-label">
                            Status Guru
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

                </div>

                <hr class="my-4">

                <div class="d-flex flex-wrap gap-2">

                    <button type="submit" class="btn btn-primary">
                        Simpan Data Guru
                    </button>

                    <a href="kelola_guru.php" class="btn btn-outline-secondary">
                        Batal
                    </a>

                </div>

            </form>

        </div>

    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>