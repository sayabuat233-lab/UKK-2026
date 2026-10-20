```php
<?php
// proses_tambah_guru.php
include 'includes/cek_session.php';
include 'config/koneksi.php';

$nip = $_POST['nip'];
$nama = $_POST['nama'];
$email = $_POST['email'];
$status_aktif = $_POST['status_aktif'];

// Mencari user berdasarkan email
$sql_user = "SELECT id FROM t_users WHERE email = '$email'";
$data_user = mysqli_query($koneksi, $sql_user);

if (mysqli_num_rows($data_user) > 0) {

    $user = mysqli_fetch_assoc($data_user);
    $user_id = $user['id'];

    // Menyimpan data guru
    $sql = "INSERT INTO t_guru
            (nip, nama, email, status_aktif, user_id)
            VALUES
            ('$nip', '$nama', '$email', '$status_aktif', '$user_id')";

    $hasil = mysqli_query($koneksi, $sql);

    if ($hasil) {
        header("Location: kelola_guru.php");
        exit;
    } else {
        echo "Gagal menambahkan data guru: " . mysqli_error($koneksi);
    }

} else {
    echo "Email belum terdaftar di tabel t_users. Buat akun guru terlebih dahulu.";
}
?>
```