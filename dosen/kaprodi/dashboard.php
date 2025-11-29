<?php
require_once "../../config/auth.php";
require_once "../../config/database.php";

$auth = new Auth();
if (!$auth->isLoggedIn() || $_SESSION['user']['role'] !== 'dosen_kaprodi') {
    header("Location: ../../login.php"); exit;
}

$user = $_SESSION['user'];
$nama = htmlspecialchars($user['nama']);

// Hitung Statistik Pending
$pendingCount = $pdo->query("SELECT COUNT(*) FROM pengajuan_tambah_kelas WHERE status = 'pending'")->fetchColumn();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Dashboard Kaprodi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="../../assets/css/style.css">
</head>
<body>

<?php include "../header.php"; ?>

<div class="container">
    <div class="row">
        <div class="col-md-9">
            <div class="content-box">
                <div class="welcome-title">Selamat Datang, <?= $nama ?></div>
                <div class="welcome-text">
                    <p>Anda berada di <strong>Panel Kepala Program Studi</strong>. Silakan validasi pengajuan akademik dan pantau perkembangan kurikulum.</p>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="card bg-white border-warning mb-3 shadow-sm">
                        <div class="card-body text-center">
                            <h1 class="display-4 fw-bold text-warning"><?= $pendingCount ?></h1>
                            <p class="card-text text-muted">Pengajuan Tambah Kelas Pending</p>
                            <a href="validasi_tambah_kelas.php" class="btn btn-warning btn-sm text-dark">Lihat Validasi</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card bg-white border-primary mb-3 shadow-sm">
                        <div class="card-body text-center">
                            <h1 class="display-4 fw-bold text-primary">0</h1>
                            <p class="card-text text-muted">Laporan Masuk Hari Ini</p>
                            <a href="#" class="btn btn-primary btn-sm">Lihat Laporan</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <?php include "sidebar.php"; ?>
        </div>
    </div>
    
    <div class="text-center mt-5 mb-3 text-muted small">Portal Akademik Kelompok 5 &copy; 2025.</div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>