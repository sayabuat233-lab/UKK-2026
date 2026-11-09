
<?php
// hapus_siswa.php

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
if (!isset($_GET['id']) || !ctype_digit($_GET['id']) || (int) $_GET['id'] <= 0) {
    header("Location: kelola_siswa.php?pesan=id_tidak_valid");
    exit;
}

$id = (int) $_GET['id'];

// Cek apakah siswa tersedia
$stmt = mysqli_prepare(
    $koneksi,
    "SELECT id FROM t_siswa WHERE id = ?"
);

if (!$stmt) {
    die("Gagal memeriksa data siswa: " . mysqli_error($koneksi));
}

mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$hasil = mysqli_stmt_get_result($stmt);

if (mysqli_num_rows($hasil) === 0) {
    mysqli_stmt_close($stmt);
    header("Location: kelola_siswa.php?pesan=siswa_tidak_ditemukan");
    exit;
}

mysqli_stmt_close($stmt);

// Hapus data siswa
$stmt = mysqli_prepare(
    $koneksi,
    "DELETE FROM t_siswa WHERE id = ?"
);

if (!$stmt) {
    die("Gagal menyiapkan penghapusan: " . mysqli_error($koneksi));
}

mysqli_stmt_bind_param($stmt, "i", $id);
$hapus = mysqli_stmt_execute($stmt);

if ($hapus && mysqli_stmt_affected_rows($stmt) > 0) {
    mysqli_stmt_close($stmt);

    header("Location: kelola_siswa.php?pesan=hapus_berhasil");
    exit;
} else {
    $error = mysqli_stmt_error($stmt);
    mysqli_stmt_close($stmt);

    if ($error !== '') {
        die("Gagal menghapus data siswa. Data mungkin masih digunakan oleh tabel lain. Detail: " . htmlspecialchars($error));
    }

    header("Location: kelola_siswa.php?pesan=siswa_tidak_ditemukan");
    exit;
}
?>