
<?php
session_start();

// Jika sudah login, arahkan ke dashboard sesuai role
if (isset($_SESSION['id'])) {
    if (($_SESSION['role'] ?? '') === 'admin') {
        header("Location: dashboard.php");
        exit;
    } elseif (($_SESSION['role'] ?? '') === 'guru') {
        header("Location: dashboard_guru.php");
        exit;
    }
}

$pesan = $_SESSION['pesan_error'] ?? '';
unset($_SESSION['pesan_error']);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sistem Pelanggaran Siswa</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css"
          rel="stylesheet">
</head>

<body class="bg-dark text-white">

<div class="container min-vh-100 d-flex justify-content-center align-items-center">

    <div class="card shadow p-4" style="width: 400px;">
        <div class="card-body">

            <h2 class="text-center mb-4">Login</h2>

            <?php if ($pesan !== ''): ?>
                <div class="alert alert-danger">
                    <?= htmlspecialchars($pesan, ENT_QUOTES, 'UTF-8') ?>
                </div>
            <?php endif; ?>

            <form action="proses_login.php" method="POST">

                <div class="mb-3">
                    <label for="email" class="form-label text-dark">Email</label>
                    <input type="email"
                           id="email"
                           name="email"
                           class="form-control"
                           autocomplete="username"
                           required>
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label text-dark">Password</label>
                    <input type="password"
                           id="password"
                           name="password"
                           class="form-control"
                           autocomplete="current-password"
                           required>
                </div>

                <button type="submit" class="btn btn-dark w-100">
                    Login
                </button>

            </form>

        </div>
    </div>

</div>

</body>
</html>
