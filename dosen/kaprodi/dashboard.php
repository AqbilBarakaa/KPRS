<?php
// dosen/kaprodi/dashboard.php
require_once "../../config/auth.php";
$auth = new Auth();

$role = $_SESSION['user']['role'] ?? '';
if (!$auth->isLoggedIn() || $role !== 'dosen_kaprodi') {
    header("Location: ../../login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Dashboard Kaprodi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<nav class="navbar navbar-dark bg-info">
  <div class="container-fluid">
    <a class="navbar-brand" href="#">KPRS - Kaprodi</a>
    <div class="d-flex">
      <span class="navbar-text me-3"><?= htmlspecialchars($_SESSION['user']['nama']) ?></span>
      <a class="btn btn-outline-light" href="../../logout.php">Logout</a>
    </div>
  </div>
</nav>

<div class="container mt-4">
    <h3>Dashboard Kaprodi</h3>
    <p>Halo, <strong><?= htmlspecialchars($_SESSION['user']['nama']) ?></strong>. Ini halaman khusus kaprodi.</p>
</div>
</body>
</html>
