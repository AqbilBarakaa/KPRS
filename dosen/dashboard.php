<?php
require_once "../config/auth.php";
require_once "../config/database.php";

$auth = new Auth();
if (!$auth->isLoggedIn() || !in_array($_SESSION['user']['role'], ['dosen', 'dosen_dpa'])) {
    header("Location: ../login.php"); exit;
}

$user = $_SESSION['user'];
$nama = htmlspecialchars($user['nama']);
$hariIndo = ['Sunday'=>'Minggu','Monday'=>'Senin','Tuesday'=>'Selasa','Wednesday'=>'Rabu','Thursday'=>'Kamis','Friday'=>'Jumat','Saturday'=>'Sabtu'];
$hariIni = $hariIndo[date('l')];
$id_dosen = $user['id'];

$stmtJadwal = $pdo->prepare("
    SELECT k.*, mk.nama_mk, mk.kode_mk 
    FROM kelas k 
    JOIN mata_kuliah mk ON k.mata_kuliah_id = mk.id 
    WHERE k.dosen_pengampu_id = ? AND k.hari = ? 
    ORDER BY k.jam_mulai ASC
");
$stmtJadwal->execute([$id_dosen, $hariIni]);
$jadwalHariIni = $stmtJadwal->fetchAll();
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

<?php include "../header.php"; ?>

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
                        <div class="card-header">Jadwal Mengajar (<?= $hariIni ?>)</div> <div class="card-body">
                            <?php if(empty($jadwalHariIni)): ?>
                                <h5 class="card-title"><i class="fas fa-calendar-check me-2"></i> Bebas Tugas</h5>
                                <p class="card-text">Tidak ada jadwal kuliah hari ini.</p>
                            <?php else: ?>
                                <ul class="list-unstyled mb-0">
                                    <?php foreach($jadwalHariIni as $j): ?>
                                    <li class="mb-2 border-bottom border-light pb-1">
                                        <i class="fas fa-clock me-1"></i> <strong><?= substr($j['jam_mulai'],0,5) ?></strong> 
                                        - <?= $j['kode_mk'] ?> <?= $j['nama_mk'] ?> 
                                        (Kelas <?= $j['nama_kelas'] ?>)
                                    </li>
                                    <?php endforeach; ?>
                                </ul>
                                <div class="mt-2 text-end">
                                    <a href="jadwal_mengajar.php" class="text-white small text-decoration-underline">Lihat semua</a>
                                </div>
                            <?php endif; ?>
                            </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <?php include "sidebar.php"; ?>
        </div>
    </div>
</div>
<?php include "../footer.php"; ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>