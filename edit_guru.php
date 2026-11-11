<?php
// edit_guru.php

include 'includes/cek_session.php';
include 'config/koneksi.php';

// Ambil ID guru
if (!isset($_GET['id']) || !ctype_digit($_GET['id'])) {
    header("Location: kelola_guru.php");
    exit;
}

$id = (int)$_GET['id'];

// Ambil data guru
$stmt = mysqli_prepare($koneksi, "SELECT * FROM t_guru WHERE id = ?");
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$hasil = mysqli_stmt_get_result($stmt);

// Pastikan data ditemukan
if (!$hasil || mysqli_num_rows($hasil) == 0) {
    header("Location: kelola_guru.php");
    exit;
}

// Ambil data ke dalam variabel $data
$data = mysqli_fetch_assoc($hasil);

// Jika $data tetap kosong, kembali ke daftar
if (!$data) {
    header("Location: kelola_guru.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Edit Guru - Pelanggaran Siswa</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
          rel="stylesheet">

    <style>
        body {
            margin: 0;
            background-color: #f3f5fb;
            font-family: Arial, sans-serif;
        }

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

            <a href="dashboard.php" class="nav-link">Dashboard</a>

            <a href="kelola_siswa.php" class="nav-link">Data Siswa</a>

            <a href="kelola_guru.php" class="nav-link active">Data Guru</a>

            <a href="kelola_kelas.php" class="nav-link">Data Kelas</a>

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

            <a href="about.php" class="nav-link">About Me</a>

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

            <h1 class="page-title mb-0">Edit Guru</h1>

            <a href="kelola_guru.php" class="btn btn-primary">
                Kembali ke Data Guru
            </a>

        </div>

        <?php if (isset($_SESSION['pesan_error'])) { ?>
            <div class="alert alert-danger">
                <?= htmlspecialchars($_SESSION['pesan_error']) ?>
            </div>
            <?php unset($_SESSION['pesan_error']); ?>
        <?php } ?>

        <!-- FORM EDIT GURU -->
        <div class="form-card">

            <h5 class="mb-1">Form Edit Guru</h5>

            <p class="text-secondary mb-4">
                Ubah informasi guru sesuai data terbaru.
            </p>

            <form action="proses_edit_guru.php"
                  method="POST"
                  id="formEditGuru">

                <input type="hidden" name="id"
                       value="<?= (int)$data['id'] ?>">

                <div class="row g-3">

                    <!-- NIP -->
                    <div class="col-md-6">
                        <label for="nip" class="form-label">NIP</label>
                        <input type="text"
                               class="form-control"
                               id="nip"
                               name="nip"
                               maxlength="18"
                               value="<?= htmlspecialchars($data['nip'] ?? '') ?>"
                               required>
                    </div>

                    <!-- NAMA -->
                    <div class="col-md-6">
                        <label for="nama" class="form-label">Nama Guru</label>
                        <input type="text"
                               class="form-control"
                               id="nama"
                               name="nama"
                               value="<?= htmlspecialchars($data['nama'] ?? '') ?>"
                               required>
                    </div>

                    <!-- EMAIL -->
                    <div class="col-md-6">
                        <label for="email" class="form-label">Email</label>
                        <input type="email"
                               class="form-control"
                               id="email"
                               name="email"
                               value="<?= htmlspecialchars($data['email'] ?? '') ?>"
                               required>
                    </div>

                    <!-- STATUS -->
                    <div class="col-md-6">
                        <label for="status_aktif" class="form-label">Status Aktif</label>
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

                    <!-- PASSWORD BARU -->
                    <div class="col-md-6">
                        <label for="password" class="form-label">Password Baru</label>
                        <input type="password"
                               class="form-control"
                               id="password"
                               name="password"
                               minlength="8"
                               autocomplete="new-password"
                               placeholder="Masukkan password baru">
                        <small class="text-secondary">
                            Kosongkan jika tidak ingin mengganti password.
                        </small>
                    </div>

                    <!-- KONFIRMASI PASSWORD -->
                    <div class="col-md-6">
                        <label for="konfirmasi_password" class="form-label">Konfirmasi Password</label>
                        <input type="password"
                               class="form-control"
                               id="konfirmasi_password"
                               name="konfirmasi_password"
                               autocomplete="new-password"
                               placeholder="Ulangi password baru">
                        <small id="pesanPassword" class="form-text">
                            Password harus sama.
                        </small>
                    </div>

                </div>

                <hr class="my-4">

                <div class="d-flex justify-content-end gap-2 flex-wrap">
                    <a href="kelola_guru.php" class="btn btn-secondary">
                        Batal
                    </a>
                    <button type="submit" class="btn btn-primary">
                        Update Data Guru
                    </button>
                </div>

            </form>

        </div>

    </main>

    <!-- VALIDASI PASSWORD -->
    <script>
        const form = document.getElementById('formEditGuru');
        const password = document.getElementById('password');
        const konfirmasi = document.getElementById('konfirmasi_password');
        const pesan = document.getElementById('pesanPassword');

        function cekPassword() {
            if (password.value === '' && konfirmasi.value === '') {
                konfirmasi.setCustomValidity('');
                pesan.textContent = 'Kosongkan jika tidak ingin mengganti password.';
                pesan.className = 'form-text';
                return;
            }

            if (password.value.length < 8) {
                konfirmasi.setCustomValidity('Password minimal 8 karakter.');
                pesan.textContent = 'Password minimal 8 karakter.';
                pesan.className = 'form-text text-danger';
                return;
            }

            if (password.value !== konfirmasi.value) {
                konfirmasi.setCustomValidity('Password tidak sama.');
                pesan.textContent = 'Konfirmasi password tidak cocok!';
                pesan.className = 'form-text text-danger';
            } else {
                konfirmasi.setCustomValidity('');
                pesan.textContent = 'Password cocok!';
                pesan.className = 'form-text text-success';
            }
        }

        password.addEventListener('input', cekPassword);
        konfirmasi.addEventListener('input', cekPassword);

        form.addEventListener('submit', function(e) {
            cekPassword();
            if (!form.checkValidity()) {
                e.preventDefault();
                form.reportValidity();
            }
        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
