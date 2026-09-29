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
    <title>Dashboard</title>
</head>
<body>

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