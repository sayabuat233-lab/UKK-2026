<?php

session_start();

if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit;
}

if ($_SESSION['role'] != 'guru') {
    echo "Akses ditolak!";
    exit;
}

$menu = isset($_GET['menu']) ? $_GET['menu'] : '';

?>

<!DOCTYPE html>
<html>
<head>
    <title>Guru</title>
</head>
<body>

<h2>Halaman Guru</h2>

<?php

if ($menu == '3') {

    echo "<h3>MENU 3 - PENCATATAN PELANGGARAN</h3>";
    echo "<p>Guru dapat mencatat pelanggaran siswa.</p>";

} elseif ($menu == '4') {

    echo "<h3>MENU 4 - LAPORAN PELANGGARAN</h3>";
    echo "<p>Guru dapat melihat laporan/poin pelanggaran siswa.</p>";

} else {

    echo "<p>Menu tidak ditemukan.</p>";

}

?>

<br>

<a href="dashboard.php">Kembali ke Dashboard</a>

</body>
</html>