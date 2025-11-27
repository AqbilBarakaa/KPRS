<?php
require_once "config/database.php";
require_once "config/auth.php";

$auth = new Auth($pdo);

if ($auth->isLoggedIn()) {
    $auth->redirectBasedOnRole();
}

$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];

    if ($auth->login($username, $password)) {
        $auth->redirectBasedOnRole();
    } else {
        $error = "Username atau password salah!";
    }
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Login KPRS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-5">

                <div class="card shadow-lg">
                    <div class="card-header text-center bg-dark text-white">
                        <h3>SISTEM KPRS</h3>
                        <small>Universitas Trunojoyo Madura</small>
                    </div>
                    <div class="card-body">

                        <?php if ($error): ?>
                            <div class="alert alert-danger">
                                <?= $error ?>
                            </div>
                        <?php endif; ?>

                        <form method="POST">
                            <div class="mb-3">
                                <label class="form-label">Username (NIM / NIDN / NIP)</label>
                                <input type="text" name="username" class="form-control" required autofocus>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Password</label>
                                <input type="password" name="password" class="form-control" required>
                            </div>

                            <button class="btn btn-primary w-100">LOGIN</button>
                        </form>

                    </div>
                </div>

            </div>
        </div>
    </div>
</body>

</html>
