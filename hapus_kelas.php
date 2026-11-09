
<?php
// hapus_kelas.php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include 'includes/cek_session.php';
include 'config/koneksi.php';

// Validasi ID kelas
if (
    !isset($_GET['id']) ||
    !ctype_digit((string) $_GET['id']) ||
    (int) $_GET['id'] <= 0
) {
    header("Location: kelola_kelas.php?pesan=id_tidak_valid");
    exit;
}

$id = (int) $_GET['id'];

// Cek data kelas
$stmt = mysqli_prepare(
    $koneksi,
    "SELECT nama FROM t_kelas WHERE id = ?"
);

if (!$stmt) {
    die("Gagal memeriksa kelas: " . mysqli_error($koneksi));
}

mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);

$hasil = mysqli_stmt_get_result($stmt);
$data = mysqli_fetch_assoc($hasil);

mysqli_stmt_close($stmt);

if (!$data) {
    header("Location: kelola_kelas.php?pesan=kelas_tidak_ditemukan");
    exit;
}

// Hapus kelas
$stmt = mysqli_prepare(
    $koneksi,
    "DELETE FROM t_kelas WHERE id = ?"
);

if (!$stmt) {
    die("Gagal menyiapkan penghapusan: " . mysqli_error($koneksi));
}

mysqli_stmt_bind_param($stmt, "i", $id);

if (mysqli_stmt_execute($stmt)) {
    $jumlah = mysqli_stmt_affected_rows($stmt);
    mysqli_stmt_close($stmt);

    if ($jumlah > 0) {
        header("Location: kelola_kelas.php?pesan=hapus_berhasil");
        exit;
    }

    header("Location: kelola_kelas.php?pesan=kelas_tidak_ditemukan");
    exit;
}

$kode_error = mysqli_stmt_errno($stmt);
$error = mysqli_stmt_error($stmt);

mysqli_stmt_close($stmt);

if ($kode_error === 1451) {
    die(
        "Kelas tidak dapat dihapus karena masih digunakan " .
        "oleh data lain, seperti penempatan siswa atau wali kelas."
    );
}

die("Gagal menghapus kelas: " . htmlspecialchars($error));
?>
