<?php
session_start();

if (isset($_SESSION['login'])) {
    header("Location: dashboard.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login - Sistem Pelanggaran Siswa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
</head>
<body class="bg-dark text-white">

    <div class="container min-vh-100 d-flex justify-content-center align-items-center">

        <div class="card shadow p-4" style="width: 400px;">

            <div class="card-body">

                <h2 class="text-center mb-4">Login</h2>

                <form action="proses_login.php" method="POST">

                    <div class="mb-3">
                        <label class="form-label text-dark">Email</label>
                        <input type="email" name="email" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label text-dark">Password</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>

                    <button type="submit" class="btn btn-dark w-100">
                        Login
                    </button>

                </form>


</div>
</form>

</body>
</html>