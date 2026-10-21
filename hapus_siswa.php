<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Cek session login
if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit;
}

// Koneksi database
include 'config/koneksi.php';

// Ambil ID siswa dari URL
if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("ID siswa tidak ditemukan.");
}

$id = (int) $_GET['id'];

// Cek apakah siswa tersedia
$cek = mysqli_query(
    $koneksi,
    "SELECT id FROM t_siswa WHERE id = $id"
);

if (!$cek) {
    die("Gagal memeriksa data: " . mysqli_error($koneksi));
}

if (mysqli_num_rows($cek) == 0) {
    die("Data siswa tidak ditemukan.");
}

// Hapus data siswa
$hapus = mysqli_query(
    $koneksi,
    "DELETE FROM t_siswa WHERE id = $id"
);

if ($hapus) {
    header("Location: data_siswa.php?pesan=hapus_berhasil");
    exit;
} else {
    echo "Gagal menghapus data siswa: ";
    echo mysqli_error($koneksi);
}
?>
