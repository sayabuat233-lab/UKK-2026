
<?php
// proses_tambah_guru.php

include 'includes/cek_session.php';
include 'config/koneksi.php';

mysqli_report(MYSQLI_REPORT_OFF);

// Ambil data dari form
$nip = trim($_POST['nip'] ?? '');
$nama = trim($_POST['nama'] ?? '');
$email = trim($_POST['email'] ?? '');
$status_aktif = $_POST['status_aktif'] ?? '1';

// Validasi input
if ($nip === '' || $nama === '' || $email === '') {
    exit('NIP, nama, dan email wajib diisi.');
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    exit('Format email tidak valid.');
}

if (!in_array((string) $status_aktif, ['0', '1'], true)) {
    exit('Status aktif tidak valid.');
}

$status_aktif = (int) $status_aktif;

// Mulai transaksi database
mysqli_begin_transaction($koneksi);

try {

    // 1. Periksa apakah email sudah terdaftar di t_users
    $stmt = mysqli_prepare(
        $koneksi,
        "SELECT id FROM t_users WHERE email = ? LIMIT 1"
    );

    if (!$stmt) {
        throw new Exception(mysqli_error($koneksi));
    }

    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);

    $hasil = mysqli_stmt_get_result($stmt);
    $user = mysqli_fetch_assoc($hasil);

    mysqli_stmt_close($stmt);

    if ($user) {

        // Email sudah terdaftar, gunakan akun tersebut
        $user_id = (int) $user['id'];

    } else {

        // 2. Buat akun guru otomatis jika email belum terdaftar
        $password_default = password_hash(
            'Guru123!',
            PASSWORD_DEFAULT
        );

        $role = 'guru';

        $stmt = mysqli_prepare(
            $koneksi,
            "INSERT INTO t_users (name, email, password, role)
             VALUES (?, ?, ?, ?)"
        );

        if (!$stmt) {
            throw new Exception(mysqli_error($koneksi));
        }

        mysqli_stmt_bind_param(
            $stmt,
            "ssss",
            $nama,
            $email,
            $password_default,
            $role
        );

        if (!mysqli_stmt_execute($stmt)) {
            throw new Exception(mysqli_stmt_error($stmt));
        }

        $user_id = mysqli_insert_id($koneksi);

        mysqli_stmt_close($stmt);
    }

    // 3. Periksa apakah NIP sudah digunakan
    $stmt = mysqli_prepare(
        $koneksi,
        "SELECT nip FROM t_guru WHERE nip = ? LIMIT 1"
    );

    if (!$stmt) {
        throw new Exception(mysqli_error($koneksi));
    }

    mysqli_stmt_bind_param($stmt, "s", $nip);
    mysqli_stmt_execute($stmt);

    $hasil = mysqli_stmt_get_result($stmt);
    $nip_ada = mysqli_fetch_assoc($hasil);

    mysqli_stmt_close($stmt);

    if ($nip_ada) {
        throw new Exception('NIP tersebut sudah terdaftar.');
    }

    // 4. Simpan data guru
    $stmt = mysqli_prepare(
        $koneksi,
        "INSERT INTO t_guru
         (nip, nama, email, status_aktif, user_id)
         VALUES (?, ?, ?, ?, ?)"
    );

    if (!$stmt) {
        throw new Exception(mysqli_error($koneksi));
    }

    mysqli_stmt_bind_param(
        $stmt,
        "sssii",
        $nip,
        $nama,
        $email,
        $status_aktif,
        $user_id
    );

    if (!mysqli_stmt_execute($stmt)) {
        throw new Exception(mysqli_stmt_error($stmt));
    }

    mysqli_stmt_close($stmt);

    // Simpan semua perubahan
    mysqli_commit($koneksi);

    // Kembali ke halaman data guru
    header("Location: kelola_guru.php");
    exit;

} catch (Throwable $e) {

    // Batalkan perubahan jika terjadi kesalahan
    mysqli_rollback($koneksi);

    exit(
        "Gagal menambahkan guru: " .
        htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8')
    );
}
?>
