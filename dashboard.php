
<?php
session_start();

if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit;
}

$name = $_SESSION['name'];
$role = $_SESSION['role'];

$menu = $_GET['menu'] ?? 'dashboard';

$menus = ['dashboard', 'siswa', 'guru', 'kelas', 'laporan'];

if (!in_array($menu, $menus, true)) {
    $menu = 'dashboard';
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>

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


<!-- BAGIAN KANAN -->
<main class="main-content">

    <?php if ($menu == 'dashboard') : ?>

        <h2>Dashboard</h2>

        <p>
            Selamat datang,
            <b><?= htmlspecialchars($name); ?></b>
        </p>

        <p>
            Role: <b><?= htmlspecialchars($role); ?></b>
        </p>

        <div class="row g-4">

            <div class="col-md-4">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h5>Data Siswa</h5>
                        <h2>120</h2>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h5>Data Guru</h5>
                        <h2>25</h2>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h5>Kelas</h5>
                        <h2>12</h2>
                    </div>
                </div>
            </div>

        </div>

    <?php elseif ($menu == 'siswa') : ?>

        <h2>Data Siswa</h2>
        <p>Halaman data siswa.</p>
        <a href="data_siswa.php" class="btn btn-primary">
            Buka Data Siswa
        </a>

    <?php elseif ($menu == 'guru') : ?>

        <h2>Data Guru</h2>
        <p>Halaman data guru.</p>
        <a href="data_guru.php" class="btn btn-primary">
            Buka Data Guru
        </a>

    <?php elseif ($menu == 'kelas') : ?>3
        

        <h2>Kelas</h2>
        <p>Halaman data kelas.</p>
        <a href="admin.php?menu=2" class="btn btn-primary">
            Buka Data Kelas
        </a>

    <?php elseif ($menu == 'laporan') : ?>

        <h2>Laporan</h2>
        <p>Halaman laporan pelanggaran siswa.</p>
        <a href="laporan.php" class="btn btn-primary">
            Buka Laporan
        </a>

    <?php endif; ?>

</main>

</body>
</html>