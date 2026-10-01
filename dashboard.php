<?php

session_start();

if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit;
}

$name = $_SESSION['name'];
$role = $_SESSION['role'];

?>

<!DOCTYPE html>
<html>
<head>
     <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <title>Dashboard</title>
</head>
<body>
    <div class="container-fluid">
    <div class="row">

        <!-- SIDEBAR -->
        <div class="col-md-3 col-lg-2 bg-dark min-vh-100 p-3">

            <h4 class="text-white mb-4">
                My Website
            </h4>

            <ul class="nav nav-pills flex-column">

                <li class="nav-item mb-2">
                    <a href="#" class="nav-link active">
                        Dashboard
                    </a>
                </li>

                <li class="nav-item mb-2">
                    <a href="data_siswa.php" class="nav-link text-white">
                        Data Siswa
                    </a>
                </li>

                <li class="nav-item mb-2">
                    <a href="data_guru.php" class="nav-link text-white">
                        Data Guru
                    </a>
                </li>

                <li class="nav-item mb-2">
                    <a href="#" class="nav-link text-white">
                        Kelas
                    </a>
                </li>

                <li class="nav-item mb-2">
                    <a href="laporan.php" class="nav-link text-white">
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

        </div>


        <!-- KONTEN -->
        <main class="col-md-9 col-lg-10 p-4">

            <h2>Dashboard</h2>

            <p>
                Selamat datang di halaman dashboard.
            </p>

            <div class="row">

                <!-- CARD DATA SISWA -->
                <div class="col-md-4 mb-3">
                    <div class="card shadow-sm">

                        <div class="card-body">

                            <h5 class="card-title">
                                Data Siswa
                            </h5>

                            <h2>
                                120
                            </h2>

                        </div>

                    </div>
                </div>


                <!-- CARD DATA GURU -->
                <div class="col-md-4 mb-3">
                    <div class="card shadow-sm">

                        <div class="card-body">

                            <h5 class="card-title">
                                Data Guru
                            </h5>

                            <h2>
                                25
                            </h2>

                        </div>

                    </div>
                </div>


                <!-- CARD KELAS -->
                <div class="col-md-4 mb-3">
                    <div class="card shadow-sm">

                        <div class="card-body">

                            <h5 class="card-title">
                                Kelas
                            </h5>

                            <h2>
                                12
                            </h2>

                        </div>

                    </div>
                </div>

            </div>

        </main>

    </div>
</div>

<h2>Dashboard</h2>

<p>
    Selamat datang, <b><?= htmlspecialchars($name); ?></b>
</p>

<p>
    Role: <b><?= htmlspecialchars($role); ?></b>
</p>

<hr>

<?php if ($role == 'admin') : ?>

    <h3>MENU ADMIN</h3>

    <ul>
        <li>
            <a href="admin.php?menu=1">
                Menu 1 - Data Siswa
            </a>
        </li>

        <li>
            <a href="admin.php?menu=2">
                Menu 2 - Data Guru & Kelas
            </a>
        </li>

        <li>
            <a href="admin.php?menu=3">
                Menu 3 - Data Pelanggaran
            </a>
        </li>

        <li>
            <a href="admin.php?menu=4">
                Menu 4 - Laporan Pelanggaran
            </a>
        </li>
    </ul>

<?php elseif ($role == 'guru') : ?>

    <h3>MENU GURU</h3>

    <ul>
        <li>
            <a href="guru.php?menu=3">
                Menu 3 - Pencatatan Pelanggaran
            </a>
        </li>

        <li>
            <a href="guru.php?menu=4">
                Menu 4 - Laporan Pelanggaran
            </a>
        </li>
    </ul>

<?php endif; ?>

<hr>

<a href="logout.php">Logout</a>

</body>
</html>