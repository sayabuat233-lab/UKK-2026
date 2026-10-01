<?php

session_start();

if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit;
}

include 'config/koneksi.php';

/*
|--------------------------------------------------------------------------
| PERBAIKI FOREIGN KEY YANG SALAH
|--------------------------------------------------------------------------
| t_siswa.id tidak boleh menjadi foreign key ke t_kelas_siswa.siswa_id
*/

$cek_fk = mysqli_query($koneksi, "
    SELECT CONSTRAINT_NAME
    FROM information_schema.KEY_COLUMN_USAGE
    WHERE TABLE_SCHEMA = DATABASE()
    AND TABLE_NAME = 't_siswa'
    AND CONSTRAINT_NAME = 't_siswa_ibfk_1'
");

if ($cek_fk && mysqli_num_rows($cek_fk) > 0) {

    mysqli_query($koneksi, "
        ALTER TABLE t_siswa
        DROP FOREIGN KEY t_siswa_ibfk_1
    ");

}


/*
|--------------------------------------------------------------------------
| PROSES TAMBAH DATA SISWA
|--------------------------------------------------------------------------
*/

if (isset($_POST['simpan'])) {

    $nis = mysqli_real_escape_string(
        $koneksi,
        $_POST['nis']
    );

    $nisn = mysqli_real_escape_string(
        $koneksi,
        $_POST['nisn']
    );

    $nama = mysqli_real_escape_string(
        $koneksi,
        $_POST['nama']
    );

    $jenis_kelamin = mysqli_real_escape_string(
        $koneksi,
        $_POST['jenis_kelamin']
    );

    $tanggal_lahir = mysqli_real_escape_string(
        $koneksi,
        $_POST['tanggal_lahir']
    );

    $alamat = mysqli_real_escape_string(
        $koneksi,
        $_POST['alamat']
    );

    $status_aktif = (int) $_POST['status_aktif'];


    /*
    |--------------------------------------------------------------------------
    | INSERT DATA
    |--------------------------------------------------------------------------
    */

    $sql = "
        INSERT INTO t_siswa
        (
            nis,
            nisn,
            nama,
            jenis_kelamin,
            tanggal_lahir,
            alamat,
            status_aktif
        )
        VALUES
        (
            '$nis',
            '$nisn',
            '$nama',
            '$jenis_kelamin',
            '$tanggal_lahir',
            '$alamat',
            '$status_aktif'
        )
    ";


    if (mysqli_query($koneksi, $sql)) {

        header("Location: data_siswa.php");
        exit;

    } else {

        $error = mysqli_error($koneksi);

    }

}

?>

<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Tambah Data Siswa</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

</head>

<body>

<div class="container mt-4">

    <h2>Tambah Data Siswa</h2>

    <hr>


    <?php if (isset($error)) { ?>

        <div class="alert alert-danger">

            <strong>Gagal menambahkan data!</strong>

            <br>

            <?= htmlspecialchars($error); ?>

        </div>

    <?php } ?>


    <form method="POST">


        <!-- NIS -->

        <div class="mb-3">

            <label class="form-label">
                NIS
            </label>

            <input
                type="text"
                name="nis"
                class="form-control"
                required
            >

        </div>


        <!-- NISN -->

        <div class="mb-3">

            <label class="form-label">
                NISN
            </label>

            <input
                type="text"
                name="nisn"
                class="form-control"
                required
            >

        </div>


        <!-- NAMA -->

        <div class="mb-3">

            <label class="form-label">
                Nama Siswa
            </label>

            <input
                type="text"
                name="nama"
                class="form-control"
                required
            >

        </div>


        <!-- JENIS KELAMIN -->

        <div class="mb-3">

            <label class="form-label">
                Jenis Kelamin
            </label>

            <select
                name="jenis_kelamin"
                class="form-select"
                required
            >

                <option value="">
                    -- Pilih Jenis Kelamin --
                </option>

                <option value="L">
                    Laki-laki
                </option>

                <option value="P">
                    Perempuan
                </option>

            </select>

        </div>


        <!-- TANGGAL LAHIR -->

        <div class="mb-3">

            <label class="form-label">
                Tanggal Lahir
            </label>

            <input
                type="date"
                name="tanggal_lahir"
                class="form-control"
                required
            >

        </div>


        <!-- ALAMAT -->

        <div class="mb-3">

            <label class="form-label">
                Alamat
            </label>

            <textarea
                name="alamat"
                class="form-control"
                rows="3"
                required
            ></textarea>

        </div>


        <!-- STATUS -->

        <div class="mb-3">

            <label class="form-label">
                Status Aktif
            </label>

            <select
                name="status_aktif"
                class="form-select"
                required
            >

                <option value="1">
                    Aktif
                </option>

                <option value="0">
                    Tidak Aktif
                </option>

            </select>

        </div>


        <!-- BUTTON -->

        <button
            type="submit"
            name="simpan"
            class="btn btn-primary"
        >
            Simpan
        </button>


        <a
            href="data_siswa.php"
            class="btn btn-secondary"
        >
            Kembali
        </a>


    </form>

</div>

</body>

</html>