```php
<?php
// tambah_walikelas.php

include 'includes/cek_session.php';
include 'config/koneksi.php';

$tahun = mysqli_query(
    $koneksi,
    "SELECT id, nama FROM t_tahun_ajaran ORDER BY id DESC"
);

$kelas = mysqli_query(
    $koneksi,
    "SELECT id, nama FROM t_kelas ORDER BY nama ASC"
);

$guru = mysqli_query(
    $koneksi,
    "SELECT id, nama FROM t_guru ORDER BY nama ASC"
);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Wali Kelas</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">

    <style>
        body {
            background-color: #f3f5fb;
            font-family: Arial, sans-serif;
            margin: 0;
        }

        .sidebar {
            width: 292px;
            height: 100vh;
            background-color: #212529;
            position: fixed;
            top: 0;
            left: 0;
            padding: 20px 15px;
            overflow-y: auto;
            z-index: 1000;
        }

        .sidebar h4 {
            color: white;
            text-align: center;
            margin-bottom: 25px;
        }

        .sidebar a {
            display: block;
            color: #dee2e6;
            text-decoration: none;
            padding: 12px 15px;
            margin-bottom: 5px;
            border-radius: 6px;
        }

        .sidebar a:hover {
            background-color: #343a40;
            color: white;
        }

        .sidebar a.active {
            background-color: #0d6efd;
            color: white;
        }

        .main-content {
            margin-left: 292px;
            padding: 25px;
            min-height: 100vh;
        }

        .page-header {
            margin-bottom: 25px;
        }

        .form-card {
            background-color: white;
            border-radius: 10px;
            padding: 25px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.06);
        }

        .form-label {
            font-weight: 600;
        }

        .form-control,
        .form-select {
            padding: 10px 12px;
        }

        .btn {
            padding: 10px 18px;
        }

        @media (max-width: 768px) {
            .sidebar {
                width: 220px;
            }

            .main-content {
                margin-left: 220px;
                padding: 15px;
            }
        }

        @media (max-width: 576px) {
            .sidebar {
                width: 180px;
                padding: 15px 8px;
            }

            .sidebar a {
                padding: 10px 8px;
                font-size: 13px;
            }

            .sidebar h4 {
                font-size: 17px;
            }

            .main-content {
                margin-left: 180px;
                padding: 12px;
            }

            .form-card {
                padding: 15px;
            }
        }
    </style>
</head>

<body>

<div class="sidebar">
    <h4>Sistem UKK</h4>

    <a href="dashboard.php">Dashboard</a>
    <a href="kelola_siswa.php">Data Siswa</a>
    <a href="kelola_guru.php">Data Guru</a>
    <a href="kelola_kelas.php">Data Kelas</a>
    <a href="kelola_tahun_ajaran.php">Data Tahun Ajaran</a>
    <a href="kelola_walikelas.php" class="active">Data Wali Kelas</a>
    <a href="penempatan_siswa.php">Penempatan Siswa</a>
    <a href="kategori_pelanggaran.php">Kategori Pelanggaran</a>
    <a href="about.php">About Me</a>
    <a href="logout.php">Logout</a>
</div>

<div class="main-content">

    <div class="page-header">
        <h2>Tambah Wali Kelas</h2>
        <p class="text-muted mb-0">
            Tambahkan data wali kelas baru ke dalam sistem.
        </p>
    </div>

    <div class="form-card">

        <form action="proses_tambah_walikelas.php" method="POST">

            <div class="mb-3">
                <label for="tahun_ajaran_id" class="form-label">
                    Tahun Ajaran
                </label>

                <select name="tahun_ajaran_id" id="tahun_ajaran_id"
                        class="form-select" required>
                    <option value="">-- Pilih Tahun Ajaran --</option>

                    <?php while ($row = mysqli_fetch_assoc($tahun)) { ?>
                        <option value="<?php echo (int) $row['id']; ?>">
                            <?php echo htmlspecialchars($row['nama']); ?>
                        </option>
                    <?php } ?>
                </select>
            </div>

            <div class="mb-3">
                <label for="kelas_id" class="form-label">
                    Kelas
                </label>

                <select name="kelas_id" id="kelas_id"
                        class="form-select" required>
                    <option value="">-- Pilih Kelas --</option>

                    <?php while ($row = mysqli_fetch_assoc($kelas)) { ?>
                        <option value="<?php echo (int) $row['id']; ?>">
                            <?php echo htmlspecialchars($row['nama']); ?>
                        </option>
                    <?php } ?>
                </select>
            </div>

            <div class="mb-3">
                <label for="guru_id" class="form-label">
                    Guru
                </label>

                <select name="guru_id" id="guru_id"
                        class="form-select" required>
                    <option value="">-- Pilih Guru --</option>

                    <?php while ($row = mysqli_fetch_assoc($guru)) { ?>
                        <option value="<?php echo (int) $row['id']; ?>">
                            <?php echo htmlspecialchars($row['nama']); ?>
                        </option>
                    <?php } ?>
                </select>
            </div>

            <div class="mb-3">
                <label for="tanggal_mulai" class="form-label">
                    Tanggal Mulai
                </label>

                <input type="date" name="tanggal_mulai"
                       id="tanggal_mulai" class="form-control" required>
            </div>

            <div class="mb-3">
                <label for="tanggal_selesai" class="form-label">
                    Tanggal Selesai
                </label>

                <input type="date" name="tanggal_selesai"
                       id="tanggal_selesai" class="form-control" required>
            </div>

            <div class="mb-4">
                <label for="status_aktif" class="form-label">
                    Status
                </label>

                <select name="status_aktif" id="status_aktif"
                        class="form-select" required>
                    <option value="1">Aktif</option>
                    <option value="0">Tidak Aktif</option>
                </select>
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">
                    Simpan
                </button>

                <a href="kelola_walikelas.php" class="btn btn-secondary">
                    Kembali
                </a>
            </div>

        </form>

    </div>

</div>

</body>
</html>
```