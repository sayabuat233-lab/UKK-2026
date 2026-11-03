
<?php
// proses_tambah_pelanggaran_kategori.php

include 'includes/cek_session.php';
include 'config/koneksi.php';

// Pastikan form dikirim menggunakan POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: tambah_pelanggaran_kategori.php");
    exit;
}

// Ambil dan validasi data
$nama = trim($_POST['nama'] ?? '');
$deskripsi = trim($_POST['deskripsi'] ?? '');
$status_aktif = $_POST['status_aktif'] ?? '';

if ($nama === '') {
    $_SESSION['pesan_error'] = 'Nama kategori wajib diisi.';
    header("Location: tambah_pelanggaran_kategori.php");
    exit;
}

if (!in_array($status_aktif, ['0', '1'], true)) {
    $_SESSION['pesan_error'] = 'Status aktif tidak valid.';
    header("Location: tambah_pelanggaran_kategori.php");
    exit;
}

// Simpan ke tabel t_pelanggaran_kategori
$sql = "INSERT INTO t_pelanggaran_kategori
        (nama, deskripsi, status_aktif)
        VALUES (?, ?, ?)";

$stmt = mysqli_prepare($koneksi, $sql);

if (!$stmt) {
    $_SESSION['pesan_error'] = 'Gagal menyiapkan query penyimpanan.';
    header("Location: tambah_pelanggaran_kategori.php");
    exit;
}

$status = (int) $status_aktif;

mysqli_stmt_bind_param(
    $stmt,
    "ssi",
    $nama,
    $deskripsi,
    $status
);

if (mysqli_stmt_execute($stmt)) {
    mysqli_stmt_close($stmt);

    $_SESSION['pesan_sukses'] = 'Kategori pelanggaran berhasil ditambahkan.';
    header("Location: kelola_pelanggaran_kategori.php");
    exit;
}

mysqli_stmt_close($stmt);

$_SESSION['pesan_error'] = 'Gagal menambahkan kategori pelanggaran.';
header("Location: tambah_pelanggaran_kategori.php");
exit;
?>
