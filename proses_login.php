<?php

session_start();

include "config/koneksi.php";

$email    = $_POST['email'];
$password = $_POST['password'];

$email = mysqli_real_escape_string($koneksi, $email);

$query = mysqli_query(
    $koneksi,
    "SELECT * FROM t_users WHERE email = '$email' LIMIT 1"
);

if (mysqli_num_rows($query) == 1) {

    $user = mysqli_fetch_assoc($query);

    if ($password === $user['password']) {

        $_SESSION['id']    = $user['id'];
        $_SESSION['name']  = $user['name'];
        $_SESSION['email'] = $user['email'];
        $_SESSION['role']  = strtolower($user['role']);

        header("Location: dashboard.php");
        exit;

    } else {

        echo "<script>
                alert('Password salah!');
                window.location='login.php';
              </script>";
        exit;
    }
} else {

    echo "<script>
            alert('Email tidak ditemukan!');
            window.location='login.php';
          </script>";
    exit;
}
?>