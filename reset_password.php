
<?php
// reset_password.php
include 'config/koneksi.php';

$email = 'user001@sekolah.test';
$password_baru = 'password123';

$hash = password_hash($password_baru, PASSWORD_DEFAULT);

$stmt = mysqli_prepare(
    $koneksi,
    "UPDATE t_users SET password = ? WHERE email = ?"
);

if (!$stmt) {
    die("Query gagal: " . mysqli_error($koneksi));
}

mysqli_stmt_bind_param($stmt, "ss", $hash, $email);
mysqli_stmt_execute($stmt);

if (mysqli_stmt_affected_rows($stmt) > 0) {
    echo "Password berhasil diubah.<br>";
    echo "Email: " . htmlspecialchars($email) . "<br>";
    echo "Password baru: " . htmlspecialchars($password_baru);
} else {
    echo "Password tidak berubah atau email tidak ditemukan.";
}

mysqli_stmt_close($stmt);
?>
