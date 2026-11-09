
<?php
// edit_kelas.php

include 'includes/cek_session.php';
include 'config/koneksi.php';

if (!isset($_GET['id']) || !ctype_digit($_GET['id']) || (int) $_GET['id'] <= 0) {
    header("Location: kelola_kelas.php");
    exit;
}

$id = (int) $_GET['id'];

$stmt = mysqli_prepare($koneksi, "SELECT * FROM t_kelas WHERE id = ?");

if (!$stmt) {
    die("Gagal menyiapkan query: " . mysqli_error($koneksi));
}

mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$hasil = mysqli_stmt_get_result($stmt);

if (mysqli_num_rows($hasil) === 0) {
    mysqli_stmt_close($stmt);
    header("Location: kelola_kelas.php");
    exit;
}

$data = mysqli_fetch_assoc($hasil);
mysqli_stmt_close($stmt);
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Edit Kelas - Pelanggaran Siswa</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css"
        rel="stylesheet">

    <style>
        body {
            margin: 0;
            background-color: #f3f5fb;
            font-family: Arial, sans-serif;
            color: #172b4d;
        }

        /* SIDEBAR */
        .sidebar {
            width: 260px;
            height: 100vh;
            background-color: #212529;
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
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        }

        .form-label {
            font-weight: 500;
            color: #212529;
        }

        .form-control {
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

            <a href="kelola_kelas.php" class="nav-link active">
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

            <h1 class="page-title mb-0">Edit Kelas</h1>

            <a href="kelola_kelas.php" class="btn btn-primary">
                Kembali ke Data Kelas
            </a>

        </div>

        <!-- FORM EDIT KELAS -->
        <div class="form-card">

            <h5 class="mb-1">Form Edit Kelas</h5>

            <p class="text-secondary mb-4">
                Ubah informasi kelas sesuai data terbaru.
            </p>

            <form action="proses_edit_kelas.php" method="POST">

                <input
                    type="hidden"
                    name="id"
                    value="<?= (int) $data['id'] ?>">

                <div class="row g-3">

                    <!-- NAMA KELAS -->
                    <div class="col-md-6">

                        <label for="nama" class="form-label">
                            Nama Kelas
                        </label>

                        <input
                            type="text"
                            class="form-control"
                            id="nama"
                            name="nama"
                            value="<?= htmlspecialchars($data['nama'] ?? '') ?>"
                            placeholder="Contoh: BD"
                            required>

                    </div>

                    <!-- TINGKAT -->
                    <div class="col-md-6">

                        <label for="tingkat" class="form-label">
                            Tingkat
                        </label>

                        <input
                            type="text"
                            class="form-control"
                            id="tingkat"
                            name="tingkat"
                            value="<?= htmlspecialchars($data['tingkat'] ?? '') ?>"
                            placeholder="Contoh: XI"
                            required>

                    </div>

                    <!-- JURUSAN -->
                    <div class="col-md-6">

                        <label for="jurusan" class="form-label">
                            Jurusan
                        </label>

                        <input
                            type="text"
                            class="form-control"
                            id="jurusan"
                            name="jurusan"
                            value="<?= htmlspecialchars($data['jurusan'] ?? '') ?>"
                            placeholder="Contoh: RPL"
                            required>

                    </div>

                </div>

                <!-- TOMBOL -->
                <div class="d-flex justify-content-end gap-2 mt-4">

                    <a href="kelola_kelas.php" class="btn btn-secondary">
                        Batal
                    </a>

                    <button type="submit" class="btn btn-primary">
                        Simpan Edit
                    </button>

                </div>

            </form>

        </div>

    </main>

</body>
</html>
