<?php
require_once "../config/auth.php";
require_once "../config/database.php";

$auth = new Auth();
if (!$auth->isLoggedIn() || ($_SESSION['user']['role'] ?? '') !== 'tu') {
    header("Location: ../login.php"); exit;
}

$user = $_SESSION['user'];
$nama = htmlspecialchars($user['nama']);

try {
    $jml_mhs = $pdo->query("SELECT COUNT(*) FROM mahasiswa")->fetchColumn();
    $jml_dosen = $pdo->query("SELECT COUNT(*) FROM dosen")->fetchColumn();
    $jml_mk = $pdo->query("SELECT COUNT(*) FROM mata_kuliah")->fetchColumn();
    $jml_kelas = $pdo->query("SELECT COUNT(*) FROM kelas")->fetchColumn();
} catch (Exception $e) { $jml_mhs = $jml_dosen = $jml_mk = $jml_kelas = 0; }
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Dashboard TU | Portal Akademik</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/style.css">
    <style>
        .stat-card {
            border: 1px solid #ddd; border-radius: 5px; padding: 15px; background: #fff;
            display: flex; align-items: center; justify-content: space-between; margin-bottom: 20px; transition: 0.3s;
        }
        .stat-card:hover { transform: translateY(-3px); box-shadow: 0 5px 15px rgba(0,0,0,0.1); }
        .stat-icon { font-size: 2.5rem; color: #ddd; }
        .stat-info h3 { margin: 0; font-size: 1.8rem; font-weight: bold; color: #333; }
        .stat-info p { margin: 0; color: #777; font-size: 0.9rem; }
        .border-left-primary { border-left: 5px solid #007bff; }
        .border-left-success { border-left: 5px solid #28a745; }
        .border-left-info { border-left: 5px solid #17a2b8; }
        .border-left-warning { border-left: 5px solid #ffc107; }
    </style>
</head>
<body>

<?php include "header.php"; ?>

<div class="container">
    <div class="row">
        
        <div class="col-md-9">
            <div class="content-box">
                <div class="welcome-title">Selamat Datang, <?= $nama ?></div>
                <div class="welcome-text">
                    <p>Anda berada di <strong>Panel Tata Usaha</strong>. Di sini Anda dapat mengelola Data Master (Mahasiswa, Dosen, Mata Kuliah, Kelas) serta memantau aktivitas akademik.</p>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <a href="mahasiswa.php" class="text-decoration-none">
                        <div class="stat-card border-left-primary">
                            <div class="stat-info"><h3><?= $jml_mhs ?></h3><p>Total Mahasiswa</p></div>
                            <div class="stat-icon text-primary"><i class="fas fa-user-graduate"></i></div>
                        </div>
                    </a>
                </div>
                <div class="col-md-6">
                    <a href="dosen.php" class="text-decoration-none">
                        <div class="stat-card border-left-success">
                            <div class="stat-info"><h3><?= $jml_dosen ?></h3><p>Total Dosen</p></div>
                            <div class="stat-icon text-success"><i class="fas fa-chalkboard-teacher"></i></div>
                        </div>
                    </a>
                </div>
                <div class="col-md-6">
                    <a href="matakuliah.php" class="text-decoration-none">
                        <div class="stat-card border-left-info">
                            <div class="stat-info"><h3><?= $jml_mk ?></h3><p>Mata Kuliah</p></div>
                            <div class="stat-icon text-info"><i class="fas fa-book"></i></div>
                        </div>
                    </a>
                </div>
                <div class="col-md-6">
                    <a href="kelas.php" class="text-decoration-none">
                        <div class="stat-card border-left-warning">
                            <div class="stat-info"><h3><?= $jml_kelas ?></h3><p>Kelas Terbuka</p></div>
                            <div class="stat-icon text-warning"><i class="fas fa-door-open"></i></div>
                        </div>
                    </a>
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
<script src="../assets/js/script.js"></script>
</body>
</html>