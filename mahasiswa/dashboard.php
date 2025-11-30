<?php
require_once "../config/auth.php";
require_once "../config/database.php";

$auth = new Auth();
if (!$auth->isLoggedIn() || ($_SESSION['user']['role'] ?? '') !== 'mahasiswa') {
    header("Location: ../login.php");
    exit;
}

$user = $_SESSION['user'];
$nama = htmlspecialchars($user['nama']);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Dashboard | Portal Akademik</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
<?php include "../header.php"; ?>
<div class="container">
    <div class="row">
        <div class="col-md-9">
            <div class="content-box">
                <div class="welcome-title">Selamat Datang <?= strtoupper($nama) ?></div>
                <div class="welcome-text">
                    <p>Selamat Datang di Portal Akademik. Portal Akademik adalah sistem yang memungkinkan para civitas akademika Universitas Trunojoyo Madura untuk menerima informasi dengan lebih cepat melalui Internet.</p>
                </div>
            </div>

            <div class="content-box">
                <div class="msg-header"><i class="fas fa-envelope me-2"></i> Kotak Pesan</div>
                <div class="alert alert-info"><i class="fas fa-info-circle"></i> Belum ada pesan baru.</div>
            </div>
        </div>

        <div class="col-md-3">
            <?php include 'sidebar.php'; ?>
        </div>
    </div>
</div>
<?php include "../footer.php"; ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="../assets/js/script.js"></script>
</body>
</html>