<?php

session_start();

include "includes/cek_session.php";
include "config/koneksi.php";

// Ambil data guru
$sql = "SELECT * FROM t_guru ORDER BY nama ASC";

$query = mysqli_query($koneksi, $sql);

if (!$query) {
    die("Query gagal: " . mysqli_error($koneksi));
}

?>

<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Data Guru</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">

</head>

<body>

<div class="container mt-4">

    <div class="d-flex justify-content-between align-items-center mb-3">

        <div>
            <h2>Data Guru</h2>

            <p>
                Data guru pada sistem pelanggaran siswa
            </p>
        </div>

        <a href="dashboard.php" class="btn btn-secondary">
            Kembali
        </a>

    </div>


    <a href="tambah_guru.php" class="btn btn-primary mb-3">
        Tambah Guru
    </a>


    <div class="table-responsive">

        <table class="table table-bordered table-striped">

            <thead class="table-dark">

                <tr>

                    <th>No</th>
                    <th>NIP</th>
                    <th>Nama</th>
                    <th>Email</th>
                    <th>Status</th>
                    <th>User ID</th>
                    <th>Aksi</th>

                </tr>

            </thead>

            <tbody>

            <?php

            $no = 1;

            while ($guru = mysqli_fetch_assoc($query)) {

            ?>

                <tr>

                    <td>
                        <?= $no++; ?>
                    </td>

                    <td>
                        <?= htmlspecialchars($guru['nip']); ?>
                    </td>

                    <td>
                        <?= htmlspecialchars($guru['nama']); ?>
                    </td>

                    <td>
                        <?= htmlspecialchars($guru['email']); ?>
                    </td>

                    <td>

                        <?php

                        if ($guru['status_aktif'] == 1) {

                            echo "Aktif";

                        } else {

                            echo "Tidak Aktif";

                        }

                        ?>

                    </td>

                    <td>
                        <?= htmlspecialchars($guru['user_id']); ?>
                    </td>

                    <td>

                        <a href="edit_guru.php?id=<?= $guru['id']; ?>"
                           class="btn btn-warning btn-sm">
                            Edit
                        </a>

                        <a href="hapus_guru.php?id=<?= $guru['id']; ?>"
                           class="btn btn-danger btn-sm"
                           onclick="return confirm('Yakin ingin menghapus data guru ini?');">
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