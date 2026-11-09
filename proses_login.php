
<?php
// proses_login.php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include 'config/koneksi.php';

// Pastikan formulir dikirim menggunakan POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: login.php');
    exit;
}

$email = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';

// Periksa input
if ($email === '' || $password === '') {
    $_SESSION['pesan_error'] = 'Email dan password wajib diisi!';
    header('Location: login.php');
    exit;
}

// Cari pengguna berdasarkan email
$stmt = mysqli_prepare(
    $koneksi,
    "SELECT id, name, email, password, role
     FROM t_users
     WHERE email = ?
     LIMIT 1"
);

if (!$stmt) {
    die('Query login gagal: ' . htmlspecialchars(mysqli_error($koneksi)));
}

mysqli_stmt_bind_param($stmt, 's', $email);
mysqli_stmt_execute($stmt);

$hasil = mysqli_stmt_get_result($stmt);
$data = mysqli_fetch_assoc($hasil);

// Periksa email dan password
if (!$data || !password_verify($password, $data['password'])) {
    mysqli_stmt_close($stmt);

    $_SESSION['pesan_error'] = 'Email atau password salah!';
    header('Location: login.php');
    exit;
}

mysqli_stmt_close($stmt);

// Periksa role pengguna
$role = strtolower(trim($data['role'] ?? ''));

if (!in_array($role, ['admin', 'guru'], true)) {
    $_SESSION['pesan_error'] = 'Role pengguna tidak dikenali!';
    header('Location: login.php');
    exit;
}

// Buat session login
session_regenerate_id(true);

$_SESSION['login'] = true;
$_SESSION['id'] = $data['id'];
$_SESSION['name'] = $data['name'];
$_SESSION['role'] = $role;

// Arahkan ke dashboard sesuai role
if ($role === 'admin') {
    header('Location: dashboard.php');
    exit;
}

header('Location: dashboard_guru.php');
exit;
?>
