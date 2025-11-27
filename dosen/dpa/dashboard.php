<?php
// dosen/dpa/dashboard.php
require_once "../../config/auth.php";
$auth = new Auth();

$role = $_SESSION['user']['role'] ?? '';
if (!$auth->isLoggedIn() || $role !== 'dosen_dpa') {
    header("Location: ../../login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Dashboard DPA</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<nav class="navbar navbar-dark bg-warning">
  <div class="container-fluid">
    <a class="navbar-brand" href="#">KPRS - DPA</a>
    <div class="d-flex">
      <span class="navbar-text me-3"><?= htmlspecialchars($_SESSION['user']['nama']) ?></span>
      <a class="btn btn-outline-dark" href="../../logout.php">Logout</a>
    </div>
  </div>
</nav>

<div class="container mt-4">
    <h3>Dashboard DPA</h3>
    <p>Selamat datang, <strong><?= htmlspecialchars($_SESSION['user']['nama']) ?></strong>.</p>
    <p>Fungsionalitas: validasi KPRS mahasiswa yang menjadi tanggung jawab DPA.</p>
</div>
</body>
</html>
