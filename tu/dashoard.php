<?php
// tu/dashboard.php
require_once "../config/auth.php";
$auth = new Auth();

if (!$auth->isLoggedIn() || ($_SESSION['user']['role'] ?? '') !== 'tu') {
    header("Location: ../login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Dashboard Tata Usaha</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<nav class="navbar navbar-dark bg-secondary">
  <div class="container-fluid">
    <a class="navbar-brand" href="#">KPRS - TU</a>
    <div class="d-flex">
      <span class="navbar-text me-3"><?= htmlspecialchars($_SESSION['user']['nama']) ?></span>
      <a class="btn btn-outline-light" href="../logout.php">Logout</a>
    </div>
  </div>
</nav>

<div class="container mt-4">
    <h3>Dashboard Tata Usaha</h3>
    <p>Selamat datang, <strong><?= htmlspecialchars($_SESSION['user']['nama']) ?></strong>. Kamu adalah staf TU.</p>
</div>
</body>
</html>
