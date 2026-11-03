```php
<?php
// edit_siswa.php
include 'includes/cek_session.php';
include 'config/koneksi.php';

if (!isset($_GET['id']) || !ctype_digit($_GET['id'])) {
    header("Location: kelola_siswa.php");
    exit;
}

$id = (int) $_GET['id'];

$sql = "SELECT * FROM t_siswa WHERE id = $id";
$hasil = mysqli_query($koneksi, $sql);

if (!$hasil || mysqli_num_rows($hasil) == 0) {
    header("Location: kelola_siswa.php");
    exit;
}

$data = mysqli_fetch_assoc($hasil);
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Edit Siswa - Pelanggaran Siswa</title>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
          rel="stylesheet">

    <style>
        body {
            margin: 0;
            background-color: #f3f5fb;
            font-family: Arial, sans-serif;
        }

        /* SIDEBAR TETAP */
        .sidebar {
            width: 292px;
            height: 100vh;
            background-color: #212529;
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
            background-color: #343a40;
            color: white;
        }

        .sidebar .nav-link.active {
            background-color: #0d6efd;
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

        /* KONTEN UTAMA */
        .main-content {
            margin-left: 292px;
            padding: 20px 22px;
            min-height: 100vh;
        }

        .page-title {
            font-size: 36px;
            font-weight: 400;
            color: #172b4d;
            margin-bottom: 25px;
        }

        .form-card {
            background: white;
            border: 1px solid #d6d6d6;
            border-radius: 7px;
            padding: 25px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
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
                width: 220px;
            }

            .main-content {
                margin-left: 220px;
                padding: 15px;
            }

            .page-title {
                font-size: 28px;
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
                padding: 15px;
            }

            .page-title {
                font-size: 24px;
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

            <a href="kelola_siswa.php" class="nav-link active">
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

            <a href="kelola_walikelas.php" class="nav-link">
                Data Wali Kelas
            </a>

            <a href="penempatan_siswa.php" class="nav-link">
                Penempatan Siswa
            </a>

            <a href="kategori_pelanggaran.php" class="nav-link">
                Kategori Pelanggaran
            </a>

            <a href="about.php" class="nav-link">
                About Me
            </a>

            <hr>

            <a href="logout.php" class="nav-link logout"
               onclick="return confirm('Yakin ingin logout?')">
                Logout
            </a>

        </nav>
    </aside>

    <!-- KONTEN UTAMA -->
    <main class="main-content">

        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4">

            <h1 class="page-title mb-0">Edit Siswa</h1>

            <a href="kelola_siswa.php" class="btn btn-primary">
                Kembali ke Data Siswa
            </a>

        </div>

        <!-- FORM EDIT SISWA -->
        <div class="form-card">

            <h5 class="mb-1">Form Edit Siswa</h5>

            <p class="text-secondary mb-4">
                Ubah data siswa sesuai informasi terbaru.
            </p>

            <form action="proses_edit_siswa.php" method="POST">

                <input type="hidden" name="id"
                       value="<?= htmlspecialchars($data['id']) ?>">

                <div class="row g-3">

                    <!-- NIS -->
                    <div class="col-md-6">
                        <label for="nis" class="form-label">NIS</label>

                        <input type="text"
                               class="form-control"
                               id="nis"
                               name="nis"
                               value="<?= htmlspecialchars($data['nis']) ?>"
                               required>
                    </div>

                    <!-- NISN -->
                    <div class="col-md-6">
                        <label for="nisn" class="form-label">NISN</label>

                        <input type="text"
                               class="form-control"
                               id="nisn"
                               name="nisn"
                               value="<?= htmlspecialchars($data['nisn']) ?>"
                               required>
                    </div>

                    <!-- NAMA SISWA -->
                    <div class="col-md-6">
                        <label for="nama" class="form-label">Nama Siswa</label>

                        <input type="text"
                               class="form-control"
                               id="nama"
                               name="nama"
                               value="<?= htmlspecialchars($data['nama']) ?>"
                               required>
                    </div>

                    <!-- JENIS KELAMIN -->
                    <div class="col-md-6">
                        <label for="jenis_kelamin" class="form-label">
                            Jenis Kelamin
                        </label>

                        <select class="form-select"
                                id="jenis_kelamin"
                                name="jenis_kelamin"
                                required>

                            <option value="L"
                                <?= $data['jenis_kelamin'] == 'L' ? 'selected' : '' ?>>
                                Laki-laki
                            </option>

                            <option value="P"
                                <?= $data['jenis_kelamin'] == 'P' ? 'selected' : '' ?>>
                                Perempuan
                            </option>

                        </select>
                    </div>

                    <!-- TANGGAL LAHIR -->
                    <div class="col-md-6">
                        <label for="tanggal_lahir" class="form-label">
                            Tanggal Lahir
                        </label>

                        <input type="date"
                               class="form-control"
                               id="tanggal_lahir"
                               name="tanggal_lahir"
                               value="<?= htmlspecialchars($data['tanggal_lahir']) ?>"
                               required>
                    </div>

                    <!-- STATUS AKTIF -->
                    <div class="col-md-6">
                        <label for="status_aktif" class="form-label">
                            Status Aktif
                        </label>

                        <select class="form-select"
                                id="status_aktif"
                                name="status_aktif"
                                required>

                            <option value="1"
                                <?= $data['status_aktif'] == 1 ? 'selected' : '' ?>>
                                Aktif
                            </option>

                            <option value="0"
                                <?= $data['status_aktif'] == 0 ? 'selected' : '' ?>>
                                Tidak Aktif
                            </option>

                        </select>
                    </div>

                    <!-- ALAMAT -->
                    <div class="col-12">
                        <label for="alamat" class="form-label">Alamat</label>

                        <textarea class="form-control"
                                  id="alamat"
                                  name="alamat"
                                  rows="4"
                                  required><?= htmlspecialchars($data['alamat']) ?></textarea>
                    </div>

                </div>

                <hr class="my-4">

                <!-- TOMBOL -->
                <div class="d-flex justify-content-end gap-2 flex-wrap">

                    <a href="kelola_siswa.php" class="btn btn-secondary">
                        Batal
                    </a>

                    <button type="submit" class="btn btn-primary">
                        Update Data Siswa
                    </button>

               