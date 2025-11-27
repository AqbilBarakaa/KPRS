<?php
// mahasiswa/dashboard.php
require_once "../config/auth.php";
$auth = new Auth();

if (!$auth->isLoggedIn() || ($_SESSION['user']['role'] ?? '') !== 'mahasiswa') {
    header("Location: ../login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Dashboard Mahasiswa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
  <div class="container-fluid">
    <a class="navbar-brand" href="#">KPRS - Mahasiswa</a>
    <div class="d-flex">
      <span class="navbar-text me-3 text-white">
        <?= htmlspecialchars($_SESSION['user']['nama']) ?> (<?= htmlspecialchars($_SESSION['user']['username']) ?>)
      </span>
      <a class="btn btn-outline-light" href="../logout.php">Logout</a>
    </div>
  </div>
</nav>

<div class="container mt-4">
    <h3>Dashboard Mahasiswa</h3>
    <p>Selamat datang, <strong><?= htmlspecialchars($_SESSION['user']['nama']) ?></strong>.</p>

    <div class="card">
        <div class="card-body">
            <p><strong>Role:</strong> Mahasiswa</p>
            <p>Di sini kamu dapat mengajukan KPRS, melihat jadwal, dsb. (contoh halaman)</p>
        </div>
    </div>
</div>
</body>
</html>
