
<?php
// includes/cek_session.php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Periksa apakah pengguna sudah login
if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit;
}

// Periksa role pengguna
$role = $_SESSION['role'] ?? '';

if ($role !== 'admin' && $role !== 'guru') {
    session_unset();
    session_destroy();

    header("Location: login.php");
    exit;
}
?>
