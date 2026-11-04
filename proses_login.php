
<?php
// proses_login.php
session_start();

include 'config/koneksi.php';

$email = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';

// Periksa input
if ($email === '' || $password === '') {
    $_SESSION['pesan_error'] = 'Email dan password wajib diisi!';
    header('Location: /UKK-2026/login.php');
    exit;
}

// Cari pengguna berdasarkan email
$stmt = mysqli_prepare(
    $koneksi,
    "SELECT * FROM t_users WHERE email = ? LIMIT 1"
);

if (!$stmt) {
    die("Query gagal: " . mysqli_error($koneksi));
}

mysqli_stmt_bind_param($stmt, "s", $email);
mysqli_stmt_execute($stmt);

$hasil = mysqli_stmt_get_result($stmt);
$data = mysqli_fetch_assoc($hasil);

// Periksa password
if ($data && password_verify($password, $data['password'])) {

    session_regenerate_id(true);

    $_SESSION['login'] = true;
    $_SESSION['id'] = $data['id'];
    $_SESSION['name'] = $data['name'];
    $_SESSION['role'] = strtolower(trim($data['role']));

    mysqli_stmt_close($stmt);

    // Arahkan berdasarkan role
    if ($_SESSION['role'] === 'admin') {
        header('Location: /UKK-2026/dashboard.php');
        exit;
    }

    if ($_SESSION['role'] === 'guru') {
        header('Location: /UKK-2026/dashboard_guru.php');
        exit;
    }

    // Jika role tidak sesuai
    session_destroy();
    session_start();
    $_SESSION['pesan_error'] = 'Role pengguna tidak dikenali!';
    header('Location: /UKK-2026/login.php');
    exit;

} else {

    mysqli_stmt_close($stmt);

    $_SESSION['pesan_error'] = 'Email atau password salah!';
    header('Location: /UKK-2026/login.php');
    exit;
}
?>
