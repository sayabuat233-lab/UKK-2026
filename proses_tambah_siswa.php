
<?php
// proses_tambah_siswa.php

include 'includes/cek_session.php';
include 'config/koneksi.php';

// Pastikan formulir dikirim menggunakan POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: tambah_siswa.php");
    exit;
}

// Ambil data dari formulir
$nis            = trim($_POST['nis'] ?? '');
$nisn           = trim($_POST['nisn'] ?? '');
$nama           = trim($_POST['nama'] ?? '');
$jenis_kelamin  = $_POST['jenis_kelamin'] ?? '';
$tanggal_lahir  = $_POST['tanggal_lahir'] ?? '';
$alamat         = trim($_POST['alamat'] ?? '');
$status_aktif   = $_POST['status_aktif'] ?? '';

// Validasi data
if (
    $nis === '' ||
    $nisn === '' ||
    $nama === '' ||
    !in_array($jenis_kelamin, ['L', 'P'], true) ||
    $tanggal_lahir === '' ||
    $alamat === '' ||
    !in_array($status_aktif, ['0', '1'], true)
) {
    $_SESSION['pesan_error'] = "Semua data wajib diisi dengan benar.";
    header("Location: tambah_siswa.php");
    exit;
}

// Simpan data ke tabel t_siswa
$sql = "INSERT INTO t_siswa
        (nis, nisn, nama, jenis_kelamin, tanggal_lahir, alamat, status_aktif)
        VALUES (?, ?, ?, ?, ?, ?, ?)";

$stmt = mysqli_prepare($koneksi, $sql);

if (!$stmt) {
    $_SESSION['pesan_error'] = "Query gagal disiapkan.";
    header("Location: tambah_siswa.php");
    exit;
}

mysqli_stmt_bind_param(
    $stmt,
    "ssssssi",
    $nis,
    $nisn,
    $nama,
    $jenis_kelamin,
    $tanggal_lahir,
    $alamat,
    $status_aktif
);

if (mysqli_stmt_execute($stmt)) {

    mysqli_stmt_close($stmt);

    $_SESSION['pesan_sukses'] = "Data siswa berhasil ditambahkan.";

    // Kembali ke halaman daftar siswa
    header("Location: kelola_siswa.php");
    exit;

} else {

    $nomor_error = mysqli_stmt_errno($stmt);

    mysqli_stmt_close($stmt);

    if ($nomor_error == 1062) {
        $_SESSION['pesan_error'] = "NIS atau NISN sudah terdaftar.";
    } else {
        $_SESSION['pesan_error'] = "Data siswa gagal disimpan. Periksa kembali data dan struktur database.";
    }

    header("Location: tambah_siswa.php");
    exit;
}
?>