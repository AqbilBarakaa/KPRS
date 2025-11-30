<?php
// dosen/kaprodi/dashboard.php
require_once "../../config/auth.php";
require_once "../../config/database.php";

$auth = new Auth();
if (!$auth->isLoggedIn() || $_SESSION['user']['role'] !== 'dosen_kaprodi') {
    header("Location: ../../login.php"); exit;
}

$user = $_SESSION['user'];
$nama = htmlspecialchars($user['nama']);
$user_id = $user['id'];

// Statistik
$pendingCount = $pdo->query("SELECT COUNT(*) FROM pengajuan_tambah_kelas WHERE status = 'pending'")->fetchColumn();

// Pesan (User type = 'dosen' karena Kaprodi adalah dosen)
$inbox = $pdo->prepare("SELECT * FROM notifikasi WHERE user_id = ? AND user_type = 'dosen' ORDER BY created_at DESC LIMIT 5");
$inbox->execute([$user_id]);
$listInbox = $inbox->fetchAll();

$sent = $pdo->prepare("SELECT * FROM notifikasi WHERE sender_id = ? AND sender_type = 'dosen' ORDER BY created_at DESC LIMIT 5");
$sent->execute([$user_id]);
$listSent = $sent->fetchAll();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8"><title>Dashboard Kaprodi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="../../assets/css/style.css">
    <style>.stat-card { border: 1px solid #ddd; padding: 15px; background: #fff; display: flex; align-items: center; justify-content: space-between; margin-bottom: 20px; transition: 0.3s; } .stat-card:hover { transform: translateY(-3px); box-shadow: 0 5px 15px rgba(0,0,0,0.1); }</style>
</head>
<body>
<?php include "../../header.php"; ?>
<div class="container">
    <div class="row">
        <div class="col-md-9">
            <div class="content-box">
                <div class="welcome-title">Selamat Datang, <?= $nama ?></div>
                <div class="welcome-text"><p>Anda berada di <strong>Panel Kepala Program Studi</strong>.</p></div>
            </div>

            <div class="row">
                <div class="col-md-6"><div class="card bg-white border-warning mb-3 shadow-sm"><div class="card-body text-center"><h1 class="display-4 fw-bold text-warning"><?= $pendingCount ?></h1><p class="card-text text-muted">Pengajuan Pending</p><a href="validasi_tambah_kelas.php" class="btn btn-warning btn-sm text-dark">Validasi</a></div></div></div>
                <div class="col-md-6"><div class="card bg-white border-primary mb-3 shadow-sm"><div class="card-body text-center"><h1 class="display-4 fw-bold text-primary">0</h1><p class="card-text text-muted">Laporan Hari Ini</p><a href="#" class="btn btn-primary btn-sm">Lihat</a></div></div></div>
            </div>

            <div class="content-box">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <div class="msg-header mb-0">Kotak Pesan</div>
                    <a href="pesan.php" class="btn btn-sm btn-outline-primary">Lihat Semua</a>
                </div>
                <ul class="nav nav-tabs mb-3" id="kpTab" role="tablist">
                    <li class="nav-item"><button class="nav-link active" id="kp-inbox-tab" data-bs-toggle="tab" data-bs-target="#kp-inbox">Masuk</button></li>
                    <li class="nav-item"><button class="nav-link" id="kp-sent-tab" data-bs-toggle="tab" data-bs-target="#kp-sent">Terkirim</button></li>
                </ul>
                <div class="tab-content">
                    <div class="tab-pane fade show active" id="kp-inbox">
                        <div class="table-responsive"><table class="table table-bordered table-sm mb-0 small"><thead class="table-light"><tr><th>Waktu</th><th>Pesan</th><th>Status</th></tr></thead><tbody>
                            <?php if(empty($listInbox)): ?><tr><td colspan="3" class="text-center text-muted">Belum ada riwayat.</td></tr><?php else: foreach($listInbox as $n): ?>
                            <tr class="<?= $n['is_read']?'':'fw-bold bg-light' ?>"><td><?= date('d M H:i', strtotime($n['created_at'])) ?></td><td><span class="text-primary"><?= htmlspecialchars($n['judul']) ?></span><br><?= htmlspecialchars($n['pesan']) ?></td><td><?= $n['is_read']?'Dibaca':'Baru' ?></td></tr>
                            <?php endforeach; endif; ?>
                        </tbody></table></div>
                    </div>
                    <div class="tab-pane fade" id="kp-sent">
                        <div class="table-responsive"><table class="table table-bordered table-sm mb-0 small"><thead class="table-light"><tr><th>Waktu</th><th>Riwayat Validasi</th></tr></thead><tbody>
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
<?php include "../../footer.php"; ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="../../assets/js/script.js"></script>
</body></html>