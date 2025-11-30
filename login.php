<?php
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
        $error = "Username atau Password salah.";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login Portal Akademik</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body class="login-page">

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-4 col-lg-4">
            <div class="card login-card">
                <div class="login-header">
                    <h1 class="login-title">Portal Akademik</h1>
                    <div class="login-subtitle">Kelompok 5</div>
                </div>

                <div class="card-body p-4">
                    <div class="text-center mb-4">
                        <h5 class="fw-bold text-dark">Silakan Login</h5>
                        <p class="text-muted small">Masukkan akun Anda untuk melanjutkan</p>
                    </div>

                    <?php if ($error): ?>
                        <div class="alert alert-danger alert-dismissible fade show d-flex align-items-center" role="alert">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            <div><?= htmlspecialchars($error) ?></div>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>

                    <form method="POST" action="">
                        <div class="mb-3">
                            <label class="form-label fw-bold small text-secondary">USERNAME</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0"><i class="fas fa-user text-muted"></i></span>
                                <input type="text" name="username" class="form-control border-start-0 ps-0" placeholder="NIM / NIDN / NIP" required autofocus>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold small text-secondary">PASSWORD</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0"><i class="fas fa-lock text-muted"></i></span>
                                <input type="password" name="password" class="form-control border-start-0 ps-0" placeholder="Masukkan Password" required>
                            </div>
                        </div>

                        <div class="d-grid mb-3">
                            <button class="btn btn-login btn-lg py-2" type="submit">MASUK</button>
                        </div>
                    </form>
                </div>

                <div class="card-footer bg-light text-center py-3">
                    <div class="footer-text">
                        &copy; <?= date('Y') ?> Portal Akademik Kelompok 5<br>
                        All Rights Reserved.
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>