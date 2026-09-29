<?php

session_start();

if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit;
}

if ($_SESSION['role'] != 'admin') {
    echo "Akses ditolak!";
    exit;
}

$menu = isset($_GET['menu']) ? $_GET['menu'] : '';

?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin</title>
</head>
<body>

<h2>Halaman Admin</h2>

<?php

if ($menu == '1') {

    echo "<h3>MENU 1 - DATA SISWA</h3>";
    echo "<p>Di sini nanti CRUD data siswa.</p>";

} elseif ($menu == '2') {

    echo "<h3>MENU 2 - DATA GURU & KELAS</h3>";
    echo "<p>Di sini nanti pengelolaan guru dan kelas.</p>";

} elseif ($menu == '3') {

    echo "<h3>MENU 3 - DATA PELANGGARAN</h3>";
    echo "<p>Di sini nanti pengelolaan data pelanggaran.</p>";

} elseif ($menu == '4') {

    echo "<h3>MENU 4 - LAPORAN PELANGGARAN</h3>";
    echo "<p>Di sini nanti laporan dan poin pelanggaran siswa.</p>";

} else {

    echo "<p>Menu tidak ditemukan.</p>";

}

?>

<br>

<a href="dashboard.php">Kembali ke Dashboard</a>

</body>
</html>