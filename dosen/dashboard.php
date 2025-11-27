<?php
// dosen/dashboard.php
require_once "../config/auth.php";
$auth = new Auth();

$role = $_SESSION['user']['role'] ?? '';
if (!$auth->isLoggedIn() || !in_array($role, ['dosen','dosen_dpa','dosen_kaprodi'])) {
    header("Location: ../login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Dashboard Dosen</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-success">
  <div class="container-fluid">
    <a class="navbar-brand" href="#">KPRS - Dosen</a>
    <div class="d-flex">
      <span class="navbar-text me-3 text-white"><?= htmlspecialchars($_SESSION['user']['nama']) ?> (<?= htmlspecialchars($role) ?>)</span>
      <a class="btn btn-outline-light" href="../logout.php">Logout</a>
    </div>
  </div>
</nav>

<div class="container mt-4">
    <h3>Dashboard Dosen</h3>
    <p>Halo, <strong><?= htmlspecialchars($_SESSION['user']['nama']) ?></strong>. Jabatan: <?= htmlspecialchars($_SESSION['user']['jabatan'] ?? '-') ?>.</p>

    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Tugas Dosen</h5>
                    <p class="card-text">Kelola validasi KPRS, lihat daftar mahasiswa, dsb.</p>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>
