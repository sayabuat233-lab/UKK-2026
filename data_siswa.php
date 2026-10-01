<?php

session_start();

if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit;
}

include 'config/koneksi.php';

$query = mysqli_query($koneksi, "SELECT * FROM t_siswa ORDER BY id DESC");

if (!$query) {
    die("Query error: " . mysqli_error($koneksi));
}

?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Data Siswa</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>

<div class="container mt-4">

    <h2>Data Siswa</h2>

    <a href="dashboard.php" class="btn btn-secondary mb-3">
        Kembali
    </a>

    <a href="tambah_siswa.php" class="btn btn-primary mb-3">
        + Tambah Siswa
    </a>

    <div class="table-responsive">

        <table class="table table-bordered table-striped">

            <thead>
                <tr>
                    <th>No</th>
                    <th>NIS</th>
                    <th>NISN</th>
                    <th>Nama</th>
                    <th>Jenis Kelamin</th>
                    <th>Tanggal Lahir</th>
                    <th>Alamat</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>

            <tbody>

            <?php
            $no = 1;

            while ($siswa = mysqli_fetch_assoc($query)) {
            ?>

                <tr>

                    <td><?= $no++; ?></td>

                    <td><?= htmlspecialchars($siswa['nis']); ?></td>

                    <td><?= htmlspecialchars($siswa['nisn']); ?></td>

                    <td><?= htmlspecialchars($siswa['nama']); ?></td>

                    <td>
                        <?php
                        if ($siswa['jenis_kelamin'] == 'L') {
                            echo "Laki-laki";
                        } elseif ($siswa['jenis_kelamin'] == 'P') {
                            echo "Perempuan";
                        } else {
                            echo "-";
                        }
                        ?>
                    </td>

                    <td><?= htmlspecialchars($siswa['tanggal_lahir']); ?></td>

                    <td><?= htmlspecialchars($siswa['alamat']); ?></td>

                    <td>
                        <?php
                        if ($siswa['status_aktif'] == 1) {
                            echo "Aktif";
                        } else {
                            echo "Tidak Aktif";
                        }
                        ?>
                    </td>

                    <td>

                        <a href="edit_siswa.php?id=<?= $siswa['id']; ?>"
                           class="btn btn-warning btn-sm">
                            Edit
                        </a>

                        <a href="hapus_siswa.php?id=<?= $siswa['id']; ?>"
                           class="btn btn-danger btn-sm"
                           onclick="return confirm('Yakin ingin menghapus data siswa ini?')">
                            Hapus
                        </a>

                    </td>

                </tr>

            <?php
            }
            ?>

            </tbody>

        </table>

    </div>

</div>

</body>
</html>