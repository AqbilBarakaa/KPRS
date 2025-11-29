<?php
require_once "../config/auth.php";
require_once "../config/database.php";

$auth = new Auth();
if (!$auth->isLoggedIn() || !in_array($_SESSION['user']['role'], ['dosen', 'dosen_dpa'])) {
    header("Location: ../login.php"); exit;
}

$user = $_SESSION['user'];
$nama = htmlspecialchars($user['nama']);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Dashboard Dosen</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>

<?php include "header.php"; ?>

<div class="container">
    <div class="row">
        <div class="col-md-9">
            <div class="content-box">
                <div class="welcome-title">Selamat Datang, <?= $nama ?></div>
                <div class="welcome-text">
                    <p>Selamat datang di <strong>Portal Akademik Dosen</strong>. Anda dapat mengelola kegiatan akademik, perwalian, dan penilaian mahasiswa melalui panel ini.</p>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="card text-white bg-primary mb-3">
                        <div class="card-header">Jadwal Mengajar</div>
                        <div class="card-body">
                            <h5 class="card-title"><i class="fas fa-calendar-alt me-2"></i> Hari Ini</h5>
                            <p class="card-text">Tidak ada jadwal kuliah hari ini.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card text-white bg-success mb-3">
                        <div class="card-header">Perwalian</div>
                        <div class="card-body">
                            <h5 class="card-title"><i class="fas fa-users me-2"></i> Mahasiswa Bimbingan</h5>
                            <p class="card-text">Kelola KRS dan konsultasi mahasiswa wali Anda.</p>
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