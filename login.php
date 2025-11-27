<?php
// login.php
require_once "config/auth.php";

$auth = new Auth();

if ($auth->isLoggedIn()) {
    $auth->redirectBasedOnRole();
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($auth->login($username, $password)) {
        $auth->redirectBasedOnRole();
    } else {
        $error = "Username atau password salah.";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Login - Sistem KPRS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card shadow">
                <div class="card-header bg-dark text-white text-center">
                    <h4 class="mb-0">SISTEM KPRS - Login</h4>
                </div>
                <div class="card-body">
                    <?php if ($error): ?>
                        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
                    <?php endif; ?>

                    <form method="POST" action="">
                        <div class="mb-3">
                            <label class="form-label">Username (NIM / NIDN / NIP)</label>
                            <input type="text" name="username" class="form-control" required autofocus>
                            <div class="form-text"><small>Mahasiswa = NIM • Dosen = NIDN • Tata Usaha = NIP</small></div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Password</label>
                            <input type="password" name="password" class="form-control" required>
                        </div>

                        <button class="btn btn-primary w-100" type="submit">Login</button>
                    </form>
                </div>
                <div class="card-footer text-muted text-center">
                    &copy; <?= date('Y') ?> Sistem KPRS
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>
