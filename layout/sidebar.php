
<?php
// layout/sidebar.php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$current_page = basename($_SERVER['PHP_SELF']);
$role = $_SESSION['role'] ?? '';

?>

<style>
    .sidebar {
        width: 260px;
        min-height: 100vh;
        background: #212529;
        color: white;
        padding: 15px;
        flex-shrink: 0;
        box-sizing: border-box;
    }

    .sidebar-title {
        color: white;
        font-size: 21px;
        margin-bottom: 25px;
    }

    .sidebar .nav-link {
        display: block;
        color: white;
        padding: 10px 15px;
        margin-bottom: 5px;
        border-radius: 6px;
        font-size: 14px;
        text-decoration: none;
    }

    .sidebar .nav-link:hover {
        background: #343a40;
        color: white;
    }

    .sidebar .nav-link.active {
        background: #0d6efd;
        color: white;
    }

    .sidebar hr {
        border-color: #454545;
        margin-top: 20px;
        margin-bottom: 15px;
    }

    .sidebar .logout {
        color: #dc3545;
    }

    @media (max-width: 768px) {
        .sidebar {
            width: 220px;
            padding: 10px;
        }

        .sidebar-title {
            font-size: 18px;
        }

        .sidebar .nav-link {
            font-size: 13px;
            padding: 9px 10px;
        }
    }
</style>

<?php if ($role === 'admin') { ?>

    <!-- SIDEBAR ADMIN -->
    <aside class="sidebar">

        <h4 class="sidebar-title">Pelanggaran Siswa</h4>

        <nav class="nav nav-pills flex-column">

            <a href="dashboard.php"
               class="nav-link <?= $current_page === 'dashboard.php' ? 'active' : '' ?>">
                Dashboard
            </a>

            <a href="kelola_siswa.php"
               class="nav-link <?= $current_page === 'kelola_siswa.php' ? 'active' : '' ?>">
                Data Siswa
            </a>

            <a href="kelola_guru.php"
               class="nav-link <?= $current_page === 'kelola_guru.php' ? 'active' : '' ?>">
                Data Guru
            </a>

            <a href="kelola_kelas.php"
               class="nav-link <?= $current_page === 'kelola_kelas.php' ? 'active' : '' ?>">
                Data Kelas
            </a>

            <a href="kelola_tahun_ajaran.php"
               class="nav-link <?= $current_page === 'kelola_tahun_ajaran.php' ? 'active' : '' ?>">
                Data Tahun Ajaran
            </a>

            <a href="kelola_walikelas.php"
               class="nav-link <?= $current_page === 'kelola_walikelas.php' ? 'active' : '' ?>">
                Data Wali Kelas
            </a>

            <a href="penempatan_siswa.php"
               class="nav-link <?= $current_page === 'penempatan_siswa.php' ? 'active' : '' ?>">
                Penempatan Siswa
            </a>

            <a href="kelola_pelanggaran_kategori.php"
               class="nav-link <?= $current_page === 'kelola_pelanggaran_kategori.php' ? 'active' : '' ?>">
                Kategori Pelanggaran
            </a>

            <a href="jenis_pelanggaran.php"
               class="nav-link <?= $current_page === 'jenis_pelanggaran.php' ? 'active' : '' ?>">
                Jenis Pelanggaran
            </a>

            <a href="laporan.php"
               class="nav-link <?= $current_page === 'laporan.php' ? 'active' : '' ?>">
                Laporan
            </a>

            <a href="about_me.php"
               class="nav-link <?= $current_page === 'about_me.php' ? 'active' : '' ?>">
                About Me
            </a>

            <hr>

            <a href="logout.php"
               class="nav-link logout"
               onclick="return confirm('Yakin ingin logout?')">
                Logout
            </a>

        </nav>
    </aside>

<?php } elseif ($role === 'guru') { ?>

    <!-- SIDEBAR GURU -->
    <aside class="sidebar">

        <h4 class="sidebar-title">Pelanggaran Siswa</h4>

        <nav class="nav nav-pills flex-column">

            <a href="dashboard_guru.php"
               class="nav-link <?= $current_page === 'dashboard_guru.php' ? 'active' : '' ?>">
                Dashboard
            </a>

            <a href="catat_pelanggaran.php"
               class="nav-link <?= $current_page === 'catat_pelanggaran.php' ? 'active' : '' ?>">
                Catat Pelanggaran
            </a>

            <a href="tindakan.php"
               class="nav-link <?= $current_page === 'tindakan.php' ? 'active' : '' ?>">
                Tindakan
            </a>

            <a href="riwayat.php"
               class="nav-link <?= $current_page === 'riwayat.php' ? 'active' : '' ?>">
                Riwayat
            </a>

            <a href="rekap_poin.php"
               class="nav-link <?= $current_page === 'rekap_poin.php' ? 'active' : '' ?>">
                Rekap Poin
            </a>

            <hr>

            <a href="logout.php"
               class="nav-link logout"
               onclick="return confirm('Yakin ingin logout?')">
                Logout
            </a>

        </nav>
    </aside>

<?php } ?>
