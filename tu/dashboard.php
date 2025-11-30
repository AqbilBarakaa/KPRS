<?php
// tu/dashboard.php
require_once "../config/auth.php";
require_once "../config/database.php";

$auth = new Auth();
if (!$auth->isLoggedIn() || ($_SESSION['user']['role'] ?? '') !== 'tu') {
    header("Location: ../login.php"); exit;
}

$user = $_SESSION['user'];
$nama = htmlspecialchars($user['nama']);
$user_id = $user['id'];

// Statistik
try {
    $jml_mhs = $pdo->query("SELECT COUNT(*) FROM mahasiswa")->fetchColumn();
    $jml_dosen = $pdo->query("SELECT COUNT(*) FROM dosen")->fetchColumn();
    $jml_mk = $pdo->query("SELECT COUNT(*) FROM mata_kuliah")->fetchColumn();
    $jml_kelas = $pdo->query("SELECT COUNT(*) FROM kelas")->fetchColumn();
} catch (Exception $e) { $jml_mhs = $jml_dosen = $jml_mk = $jml_kelas = 0; }

// --- PESAN MASUK & TERKIRIM ---
$inbox = $pdo->prepare("SELECT * FROM notifikasi WHERE user_id = ? AND user_type = 'tata_usaha' ORDER BY created_at DESC LIMIT 5");
$inbox->execute([$user_id]);
$listInbox = $inbox->fetchAll();

$sent = $pdo->prepare("SELECT * FROM notifikasi WHERE sender_id = ? AND sender_type = 'tata_usaha' ORDER BY created_at DESC LIMIT 5");
$sent->execute([$user_id]);
$listSent = $sent->fetchAll();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8"><title>Dashboard TU</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/style.css">
    <style>
        .stat-card { border: 1px solid #ddd; border-radius: 5px; padding: 15px; background: #fff; display: flex; align-items: center; justify-content: space-between; margin-bottom: 20px; transition: 0.3s; }
        .stat-card:hover { transform: translateY(-3px); box-shadow: 0 5px 15px rgba(0,0,0,0.1); }
        .stat-info h3 { margin: 0; font-size: 1.8rem; font-weight: bold; color: #333; }
        .stat-info p { margin: 0; color: #777; font-size: 0.9rem; }
        .border-left-primary { border-left: 5px solid #007bff; }
        .border-left-success { border-left: 5px solid #28a745; }
        .border-left-info { border-left: 5px solid #17a2b8; }
        .border-left-warning { border-left: 5px solid #ffc107; }
    </style>
</head>
<body>
<?php include "../header.php"; ?>
<div class="container">
    <div class="row">
        <div class="col-md-9">
            <div class="content-box">
                <div class="welcome-title">Selamat Datang, <?= $nama ?></div>
                <div class="welcome-text"><p>Anda berada di <strong>Panel Tata Usaha</strong>.</p></div>
            </div>

            <div class="row">
                <div class="col-md-6"><a href="mahasiswa.php" class="text-decoration-none"><div class="stat-card border-left-primary"><div class="stat-info"><h3><?= $jml_mhs ?></h3><p>Total Mahasiswa</p></div></div></a></div>
                <div class="col-md-6"><a href="dosen.php" class="text-decoration-none"><div class="stat-card border-left-success"><div class="stat-info"><h3><?= $jml_dosen ?></h3><p>Total Dosen</p></div></div></a></div>
                <div class="col-md-6"><a href="matakuliah.php" class="text-decoration-none"><div class="stat-card border-left-info"><div class="stat-info"><h3><?= $jml_mk ?></h3><p>Mata Kuliah</p></div></div></a></div>
                <div class="col-md-6"><a href="kelas.php" class="text-decoration-none"><div class="stat-card border-left-warning"><div class="stat-info"><h3><?= $jml_kelas ?></h3><p>Kelas Terbuka</p></div></div></a></div>
            </div>

            <div class="content-box">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <div class="msg-header mb-0">Kotak Pesan</div>
                    <a href="pesan.php" class="btn btn-sm btn-outline-primary">Lihat Semua</a>
                </div>                
                <ul class="nav nav-tabs mb-3" id="tuMsgTab" role="tablist">
                    <li class="nav-item"><button class="nav-link active" id="tu-inbox-tab" data-bs-toggle="tab" data-bs-target="#tu-inbox">Masuk</button></li>
                    <li class="nav-item"><button class="nav-link" id="tu-sent-tab" data-bs-toggle="tab" data-bs-target="#tu-sent">Terkirim</button></li>
                </ul>
                <div class="tab-content">
                    <div class="tab-pane fade show active" id="tu-inbox">
                        <div class="table-responsive"><table class="table table-bordered table-sm mb-0 small"><thead class="table-light"><tr><th>Waktu</th><th>Pesan</th><th>Status</th></tr></thead><tbody>
                            <?php if(empty($listInbox)): ?><tr><td colspan="3" class="text-center text-muted">Belum ada riwayat.</td></tr><?php else: foreach($listInbox as $n): ?>
                            <tr class="<?= $n['is_read']?'':'fw-bold bg-light' ?>"><td><?= date('d M H:i', strtotime($n['created_at'])) ?></td><td><span class="text-primary"><?= htmlspecialchars($n['judul']) ?></span><br><?= htmlspecialchars($n['pesan']) ?></td><td><?= $n['is_read']?'Dibaca':'Baru' ?></td></tr>
                            <?php endforeach; endif; ?>
                        </tbody></table></div>
                    </div>
                    <div class="tab-pane fade" id="tu-sent">
                        <div class="table-responsive"><table class="table table-bordered table-sm mb-0 small"><thead class="table-light"><tr><th>Waktu</th><th>Pesan Terkirim</th></tr></thead><tbody>
                            <?php if(empty($listSent)): ?><tr><td colspan="2" class="text-center text-muted">Belum ada riwayat.</td></tr><?php else: foreach($listSent as $s): ?>
                            <tr><td><?= date('d M H:i', strtotime($s['created_at'])) ?></td><td><span class="fw-bold"><?= htmlspecialchars($s['judul']) ?></span><br><?= htmlspecialchars($s['pesan']) ?></td></tr>
                            <?php endforeach; endif; ?>
                        </tbody></table></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3"><?php include "sidebar.php"; ?></div>
    </div>
</div>
<?php include "../footer.php"; ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="../assets/js/script.js"></script>
</body></html>