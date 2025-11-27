<?php
require_once "../config/database.php";
require_once "../config/auth.php";

$auth = new Auth($pdo);

// Proteksi: jika user bukan mahasiswa → tendang ke login
if (!$auth->isLoggedIn() || $_SESSION['user']['role'] !== 'mahasiswa') {
    header("Location: ../login.php");
    exit;
}
?>


<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Mahasiswa - KPRS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="#">KPRS System</a>
            <div class="navbar-nav ms-auto">
                <span class="navbar-text me-3">
                    Halo, <?php echo $_SESSION['nama']; ?> (<?php echo $_SESSION['nim']; ?>)
                </span>
                <a class="nav-link" href="../logout.php">Logout</a>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <div class="row">
            <div class="col-md-3">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h6 class="mb-0">Menu Mahasiswa</h6>
                    </div>
                    <div class="list-group list-group-flush">
                        <a href="dashboard.php" class="list-group-item list-group-item-action active">Dashboard</a>
                        <a href="krs.php" class="list-group-item list-group-item-action">Lihat KRS</a>
                        <a href="kprs_ajukan.php" class="list-group-item list-group-item-action">Ajukan KPRS</a>
                        <a href="kprs_history.php" class="list-group-item list-group-item-action">History KPRS</a>
                        <a href="tambah_kelas.php" class="list-group-item list-group-item-action">Tambah Kelas</a>
                        <a href="notifikasi.php" class="list-group-item list-group-item-action">Notifikasi</a>
                    </div>
                </div>
            </div>
            
            <div class="col-md-9">
                <div class="card">
                    <div class="card-header">
                        <h4 class="mb-0">Dashboard Mahasiswa</h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="card text-white bg-success mb-3">
                                    <div class="card-body">
                                        <h5 class="card-title">KRS Aktif</h5>
                                        <p class="card-text display-6">5 Mata Kuliah</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card text-white bg-warning mb-3">
                                    <div class="card-body">
                                        <h5 class="card-title">KPRS Pending</h5>
                                        <p class="card-text display-6">2 Pengajuan</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card text-white bg-info mb-3">
                                    <div class="card-body">
                                        <h5 class="card-title">Total SKS</h5>
                                        <p class="card-text display-6">18 SKS</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <h5 class="mt-4">Informasi Penting</h5>
                        <ul>
                            <li>Periode KPRS: 1 Jan 2024 - 31 Jan 2024</li>
                            <li>DPA: <?php echo $_SESSION['dpa_id']; ?></li>
                            <li>Program Studi: <?php echo $_SESSION['prodi']; ?></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>