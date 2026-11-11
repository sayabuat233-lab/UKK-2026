<?php
// proses_edit_guru.php

include 'includes/cek_session.php';
include 'config/koneksi.php';

$id = (int) ($_POST['id'] ?? 0);
$nip = trim($_POST['nip'] ?? '');
$nama = trim($_POST['nama'] ?? '');
$email = trim($_POST['email'] ?? '');
$status_aktif = $_POST['status_aktif'] ?? '';

$password = $_POST['password'] ?? '';
$konfirmasi_password = $_POST['konfirmasi_password'] ?? '';

if ($id <= 0 || $nama === '' || $nip === '' || $email === '') {
    die("Data guru tidak lengkap.");
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    die("Email tidak valid.");
}

if (!in_array($status_aktif, ['0', '1'], true)) {
    die("Status guru tidak valid.");
}

mysqli_begin_transaction($koneksi);

try {

    // Ambil user_id yang SUDAH ada di data guru (jika ada)
    $stmt_get_user = mysqli_prepare($koneksi, "SELECT user_id FROM t_guru WHERE id = ?");
    mysqli_stmt_bind_param($stmt_get_user, "i", $id);
    mysqli_stmt_execute($stmt_get_user);
    mysqli_stmt_bind_result($stmt_get_user, $user_id);
    mysqli_stmt_fetch($stmt_get_user);
    mysqli_stmt_close($stmt_get_user);

    // Update data guru
    $sql = "UPDATE t_guru SET
                nip = ?,
                nama = ?,
                email = ?,
                status_aktif = ?
            WHERE id = ?";

    $stmt = mysqli_prepare($koneksi, $sql);

    mysqli_stmt_bind_param(
        $stmt,
        "sssii",
        $nip,
        $nama,
        $email,
        $status_aktif,
        $id
    );

    if (!mysqli_stmt_execute($stmt)) {
        throw new Exception(mysqli_stmt_error($stmt));
    }

    mysqli_stmt_close($stmt);

    // Update password HANYA jika diisi DAN punya akun
    if ($password !== '') {

        if (empty($user_id)) {
            // ⚠️ UBAH DISINI: tidak lempar error, tapi kasih peringatan
            $_SESSION['pesan_info'] = "Data tersimpan, tapi password tidak diubah karena guru belum memiliki akun login.";
        } else {
            if (strlen($password) < 8) {
                throw new Exception("Password minimal 8 karakter.");
            }

            if ($password !== $konfirmasi_password) {
                throw new Exception("Konfirmasi password tidak cocok.");
            }

            $cek = mysqli_prepare(
                $koneksi,
                "SELECT id FROM t_users WHERE id = ? AND role = 'guru'"
            );
            mysqli_stmt_bind_param($cek, "i", $user_id);
            mysqli_stmt_execute($cek);
            mysqli_stmt_store_result($cek);

            if (mysqli_stmt_num_rows($cek) == 0) {
                $_SESSION['pesan_info'] = "Data tersimpan, tapi akun login tidak ditemukan.";
            } else {
                $hash = password_hash($password, PASSWORD_DEFAULT);
                $update_user = mysqli_prepare(
                    $koneksi,
                    "UPDATE t_users SET password = ?, name = ?, email = ?
                     WHERE id = ? AND role = 'guru'"
                );
                mysqli_stmt_bind_param(
                    $update_user,
                    "sssi",
                    $hash,
                    $nama,
                    $email,
                    $user_id
                );
                if (!mysqli_stmt_execute($update_user)) {
                    throw new Exception(mysqli_stmt_error($update_user));
                }
                mysqli_stmt_close($update_user);
            }
            mysqli_stmt_close($cek);
        }
    }

    mysqli_commit($koneksi);

    header("Location: kelola_guru.php");
    exit;

} catch (Exception $e) {
    mysqli_rollback($koneksi);
    die("Gagal mengubah data guru: " . htmlspecialchars($e->getMessage()));
}
?>
