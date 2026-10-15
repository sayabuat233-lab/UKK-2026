
<?php
session_start();

include 'config/koneksi.php';
$pesan = '';
$edit = null;

// TAMBAH DATA
if (isset($_POST['tambah'])) {
    $tahun = trim($_POST['tahun_ajaran']);
    $status = $_POST['status'];

    if (preg_match('/^\d{4}\/\d{4}$/', $tahun)
        && in_array($status, ['Aktif', 'Tidak Aktif'], true)) {

        $stmt = mysqli_prepare(
            $koneksi,
            "INSERT INTO t_tahun_ajaran (tahun_ajaran, status)
             VALUES (?, ?)"
        );

        mysqli_stmt_bind_param($stmt, "ss", $tahun, $status);

        if (mysqli_stmt_execute($stmt)) {
            header("Location: kelola_tahun_ajaran.php");
            exit;
        } else {
            $pesan = "Gagal menambah data. Tahun ajaran mungkin sudah ada.";
        }
    } else {
        $pesan = "Format tahun ajaran harus YYYY/YYYY.";
    }
}

// AMBIL DATA UNTUK EDIT
if (isset($_GET['edit'])) {
    $id = (int) $_GET['edit'];

    $stmt = mysqli_prepare(
        $koneksi,
        "SELECT * FROM t_tahun_ajaran WHERE id = ?"
    );
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);

    $hasil = mysqli_stmt_get_result($stmt);
    $edit = mysqli_fetch_assoc($hasil);

    if (!$edit) {
        $pesan = "Data tahun ajaran tidak ditemukan.";
    }
}

// SIMPAN PERUBAHAN
if (isset($_POST['update'])) {
    $id = (int) $_POST['id'];
    $tahun = trim($_POST['tahun_ajaran']);
    $status = $_POST['status'];

    if (preg_match('/^\d{4}\/\d{4}$/', $tahun)
        && in_array($status, ['Aktif', 'Tidak Aktif'], true)) {

        $stmt = mysqli_prepare(
            $koneksi,
            "UPDATE t_tahun_ajaran
             SET tahun_ajaran = ?, status = ?
             WHERE id = ?"
        );

        mysqli_stmt_bind_param($stmt, "ssi", $tahun, $status, $id);

        if (mysqli_stmt_execute($stmt)) {
            header("Location: kelola_tahun_ajaran.php");
            exit;
        } else {
            $pesan = "Gagal mengubah data. Periksa tahun ajaran.";
        }
    } else {
        $pesan = "Format tahun ajaran tidak valid.";
    }
}

// HAPUS DATA
if (isset($_GET['hapus'])) {
    $id = (int) $_GET['hapus'];

    $stmt = mysqli_prepare(
        $koneksi,
        "DELETE FROM t_tahun_ajaran WHERE id = ?"
    );

    mysqli_stmt_bind_param($stmt, "i", $id);

    if (mysqli_stmt_execute($stmt)) {
        header("Location: kelola_tahun_ajaran.php");
        exit;
    } else {
        $pesan = "Gagal menghapus data. Periksa relasi database.";
    }
}

// TAMPIL DATA
$data = mysqli_query(
    $koneksi,
    "SELECT * FROM t_tahun_ajaran ORDER BY id DESC"
);

function aman($teks) {
    return htmlspecialchars((string) $teks, ENT_QUOTES, 'UTF-8');
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Kelola Tahun Ajaran</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css"
          rel="stylesheet">

    <style>
        body {
            margin: 0;
        }

        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            width: 317px;
            height: 100vh;
            background: #212529;
            padding: 16px;
        }

        .sidebar .nav-link {
            color: white;
            padding: 10px 16px;
            margin-bottom: 8px;
            border-radius: 6px;
        }

        .sidebar .nav-link.active {
            background: #0d6efd;
            color: white;
        }

        .sidebar .nav-link:hover:not(.active) {
            background: #343a40;
        }

        .main-content {
            margin-left: 317px;
            padding: 24px;
            min-height: 100vh;
        }

        @media (max-width: 767px) {
            .sidebar {
                width: 200px;
            }

            .main-content {
                margin-left: 200px;
                padding: 16px;
            }
        }
    </style>
</head>

<body>

<!-- SIDEBAR KIRI -->
<aside class="sidebar">

    <h4 class="text-white mb-4">My Website</h4>

    <ul class="nav nav-pills flex-column">

        <li class="nav-item">
            <a href="dashboard.php?menu=dashboard"
               class="nav-link <?= $menu == 'dashboard' ? 'active' : '' ?>">
                Dashboard
            </a>
        </li>

        <li class="nav-item">
            <a href="dashboard.php?menu=siswa"
               class="nav-link <?= $menu == 'siswa' ? 'active' : '' ?>">
                Data Siswa
            </a>
        </li>
                <li class="nav-item">
            <a href="kelola_tahun_ajaran.php"
               class="nav-link <?= $menu == 'tahun_ajaran' ? 'active' : '' ?>">
                Tahun Ajaran
            </a>
        </li>
        <li class="nav-item">
            <a href="dashboard.php?menu=guru"
               class="nav-link <?= $menu == 'guru' ? 'active' : '' ?>">
                Data Guru
            </a>
        </li>

        <li class="nav-item">
            <a href="dashboard.php?menu=kelas"
               class="nav-link <?= $menu == 'kelas' ? 'active' : '' ?>">
                Kelas
            </a>
        </li>
        <li class="nav-item">
            <a href="dashboard.php?menu=laporan"
               class="nav-link <?= $menu == 'laporan' ? 'active' : '' ?>">
                Laporan
            </a>
        </li>

        <hr class="text-secondary">

        <li class="nav-item">
            <a href="logout.php" class="nav-link text-danger">
                Logout
            </a>
        </li>

    </ul>
</aside>

<main class="main-content"> 
    <div class="container mt-4">
    <h3>Kelola Tahun Ajaran</h3>
    <p>Data tahun ajaran pada sistem pelanggaran siswa</p>

    <?php if ($pesan != ''): ?>
        <div class="alert alert-warning">
            <?= aman($pesan) ?>
        </div>
    <?php endif; ?>

    <div class="card mb-4">
        <div class="card-header">
            <?= $edit ? 'Edit Tahun Ajaran' : 'Tambah Tahun Ajaran' ?>
        </div>

        <div class="card-body">
            <form method="POST" action="kelola_tahun_ajaran.php">
                <?php if ($edit): ?>
                    <input type="hidden" name="id"
                           value="<?= aman($edit['id']) ?>">
                <?php endif; ?>

                <div class="mb-3">
                    <label class="form-label">Tahun Ajaran</label>
                    <input type="text" name="tahun_ajaran"
                           class="form-control"
                           placeholder="Contoh: 2026/2027"
                           pattern="[0-9]{4}/[0-9]{4}"
                           value="<?= aman($edit['tahun_ajaran'] ?? '') ?>"
                           required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select" required>
                        <option value="Aktif"
                            <?= (($edit['status'] ?? '') == 'Aktif')
                                ? 'selected' : '' ?>>
                            Aktif
                        </option>
                        <option value="Tidak Aktif"
                            <?= (($edit['status'] ?? '') == 'Tidak Aktif')
                                ? 'selected' : '' ?>>
                            Tidak Aktif
                        </option>
                    </select>
                </div>

                <?php if ($edit): ?>
                    <button type="submit" name="update"
                            class="btn btn-primary">Simpan Perubahan</button>
                    <a href="kelola_tahun_ajaran.php"
                       class="btn btn-secondary">Batal</a>
                <?php else: ?>
                    <button type="submit" name="tambah"
                            class="btn btn-primary">Tambah Tahun Ajaran</button>
                <?php endif; ?>
            </form>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-bordered table-striped">
            <thead class="table-dark">
                <tr>
                    <th>No</th>
                    <th>Tahun Ajaran</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (mysqli_num_rows($data) > 0): ?>
                    <?php $no = 1; ?>
                    <?php while ($row = mysqli_fetch_assoc($data)): ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td><?= aman($row['nama']) ?></td>
                            <td>
                                <?php if ($row['status_aktif'] == 'Aktif'): ?>
                                    <span class="badge bg-success">Aktif</span>
                                <?php else: ?>
                                    <span class="badge bg-secondary">Tidak Aktif</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <a class="btn btn-warning btn-sm"
                                   href="kelola_tahun_ajaran.php?edit=<?= (int) $row['id'] ?>">
                                    Edit
                                </a>

                                <a class="btn btn-danger btn-sm"
                                   href="kelola_tahun_ajaran.php?hapus=<?= (int) $row['id'] ?>"
                                   onclick="return confirm('Yakin ingin menghapus tahun ajaran ini?')">
                                    Hapus
                                </a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="4" class="text-center">
                            Belum ada data tahun ajaran.
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
</main>



</body>
</html>
