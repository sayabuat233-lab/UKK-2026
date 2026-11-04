
<?php
// includes/cek_session.php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['id']) || !isset($_SESSION['login']) || $_SESSION['login'] !== true) {
    header('Location: /UKK-2026/login.php');
    exit;
}
?>
