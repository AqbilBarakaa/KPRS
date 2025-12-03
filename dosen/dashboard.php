<?php
// dosen/dashboard.php
require_once "../config/auth.php";
require_once "../config/database.php";

$auth = new Auth();
// Cek akses: Dosen Biasa atau DPA
if (!$auth->isLoggedIn() || !in_array($_SESSION['user']['role'], ['dosen', 'dosen_dpa'])) {
    header("Location: ../login.php"); exit;
}

$user = $_SESSION['user'];
$nama = htmlspecialchars($user['nama']);
$user_id = $user['id'];

// --- CEK ALERT KRS & KPRS ---
$stmtPeriode = $pdo->prepare("SELECT tanggal_selesai_krs, tanggal_selesai_kprs, tahun_akademik, semester 
                               FROM periode_akademik 
                               WHERE status = 'active' 
                               LIMIT 1");
$stmtPeriode->execute();
$periodeAktif = $stmtPeriode->fetch();

$alertKRS = false;
$alertKPRS = false;
$sisaHariKRS = 0;
$sisaHariKPRS = 0;
$periodeInfo = '';

if ($periodeAktif) {
    $periodeInfo = $periodeAktif['tahun_akademik'] . ' - ' . $periodeAktif['semester'];
    $today = new DateTime();
    
    // Cek KRS
    if ($periodeAktif['tanggal_selesai_krs']) {
        $deadlineKRS = new DateTime($periodeAktif['tanggal_selesai_krs']);
        if ($deadlineKRS >= $today) {
            $sisaHariKRS = $today->diff($deadlineKRS)->days;
            if ($sisaHariKRS <= 2) {
                $alertKRS = true;
            }
        }
    }
    
    // Cek KPRS
    if ($periodeAktif['tanggal_selesai_kprs']) {
        $deadlineKPRS = new DateTime($periodeAktif['tanggal_selesai_kprs']);
        if ($deadlineKPRS >= $today) {
            $sisaHariKPRS = $today->diff($deadlineKPRS)->days;
            if ($sisaHariKPRS <= 2) {
                $alertKPRS = true;
            }
        }
    }
}

// --- 1. PESAN MASUK (INBOX) ---
$stmtInbox = $pdo->prepare("SELECT * FROM notifikasi WHERE user_id = ? AND user_type = 'dosen' ORDER BY created_at DESC LIMIT 5");
$stmtInbox->execute([$user_id]);
$inbox = $stmtInbox->fetchAll();

// --- 2. PESAN TERKIRIM (SENT) ---
$stmtSent = $pdo->prepare("SELECT * FROM notifikasi WHERE sender_id = ? AND sender_type = 'dosen' ORDER BY created_at DESC LIMIT 5");
$stmtSent->execute([$user_id]);
$sent = $stmtSent->fetchAll();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Dashboard Dosen</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/style.css">
    <style>
        .nav-tabs .nav-link.active { font-weight: bold; border-top: 3px solid #0d6efd; }
        .table-sm td, .table-sm th { font-size: 0.85rem; }
    </style>
</head>
<body>

<?php include "../header.php"; ?>

<div class="container">
    <div class="row">
        <div class="col-md-9">
            
            <?php if ($alertKRS): ?>
            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                <strong><i class="fas fa-exclamation-triangle me-2"></i>Perhatian!</strong> 
                Masa KRS <?= htmlspecialchars($periodeInfo) ?> akan berakhir dalam <strong><?= $sisaHariKRS ?> hari</strong> 
                (<?= date('d M Y, H:i', strtotime($periodeAktif['tanggal_selesai_krs'])) ?>).
                Segera cek KRS Mahasiswa bimbingan Anda dan lakukan validasi!
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            <?php endif; ?>

            <?php if ($alertKPRS): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong><i class="fas fa-exclamation-circle me-2"></i>Perhatian!</strong> 
                Masa KPRS <?= htmlspecialchars($periodeInfo) ?> akan berakhir dalam <strong><?= $sisaHariKPRS ?> hari</strong> 
                (<?= date('d M Y, H:i', strtotime($periodeAktif['tanggal_selesai_kprs'])) ?>). 
                Segera Validasi KRS mahasiswa bimbingan Anda!
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            <?php endif; ?>

            <div class="content-box">
                <div class="welcome-title">Selamat Datang, <?= $nama ?></div>
                <div class="welcome-text">
                    <p>Selamat datang di <strong>Portal Akademik Dosen</strong>. Anda dapat mengelola kegiatan akademik, perwalian, dan penilaian mahasiswa melalui panel ini.</p>
                </div>
            </div>

            <div class="row mb-4">
                <div class="col-md-6">
                    <div class="card text-white bg-primary mb-3 h-100">
                        <div class="card-header fw-bold"><i class="fas fa-calendar-alt me-2"></i> Jadwal Mengajar</div>
                        <div class="card-body">
                            <p class="card-text small">Cek jadwal dan ruangan mengajar Anda hari ini.</p>
                            <a href="jadwal_mengajar.php" class="btn btn-sm btn-light text-primary fw-bold">Lihat Jadwal</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card text-white bg-success mb-3 h-100">
                        <div class="card-header fw-bold"><i class="fas fa-users me-2"></i> Perwalian</div>
                        <div class="card-body">
                            <p class="card-text small">Kelola KRS dan konsultasi mahasiswa bimbingan.</p>
                            <a href="perwalian.php" class="btn btn-sm btn-light text-success fw-bold">Lihat Mahasiswa</a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="content-box">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <div class="msg-header mb-0">Kotak Pesan</div>
                    <a href="pesan.php" class="btn btn-sm btn-outline-primary">Lihat Semua</a>
                </div>
                
                <ul class="nav nav-tabs mb-3" id="msgTab" role="tablist">
                    <li class="nav-item">
                        <button class="nav-link active" id="inbox-tab" data-bs-toggle="tab" data-bs-target="#inbox" type="button">Kotak Masuk</button>
                    </li>
                    <li class="nav-item">
                        <button class="nav-link" id="sent-tab" data-bs-toggle="tab" data-bs-target="#sent" type="button">Terkirim</button>
                    </li>
                </ul>

                <div class="tab-content" id="msgTabContent">
                    
                    <div class="tab-pane fade show active" id="inbox">
                        <div class="table-responsive">
                            <table class="table table-bordered table-sm mb-0 table-hover">
                                <thead class="table-light">
                                    <tr><th>Waktu</th><th>Pesan Masuk</th><th class="text-center">Status</th></tr>
                                </thead>
                                <tbody>
                                    <?php if(empty($inbox)): ?>
                                        <tr><td colspan="3" class="text-center text-muted py-3">Belum ada riwayat.</td></tr>
                                    <?php else: ?>
                                        <?php foreach($inbox as $n): ?>
                                        <tr class="<?= $n['is_read'] ? '' : 'fw-bold bg-light' ?>">
                                            <td width="20%"><?= date('d M H:i', strtotime($n['created_at'])) ?></td>
                                            <td>
                                                <span class="text-<?= $n['tipe']=='error'?'danger':($n['tipe']=='success'?'success':'primary') ?>">
                                                    <?= htmlspecialchars($n['judul']) ?>
                                                </span><br>
                                                <span class="text-muted small"><?= htmlspecialchars($n['pesan']) ?></span>
                                            </td>
                                            <td width="15%" class="text-center">
                                                <?= $n['is_read'] ? '<span class="badge bg-secondary">Dibaca</span>' : '<span class="badge bg-success">Baru</span>' ?>
                                            </td>
                                        </tr>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="tab-pane fade" id="sent">
                        <div class="table-responsive">
                            <table class="table table-bordered table-sm mb-0 table-hover">
                                <thead class="table-light">
                                    <tr><th>Waktu</th><th>Pesan Terkirim</th></tr>
                                </thead>
                                <tbody>
                                    <?php if(empty($sent)): ?>
                                        <tr><td colspan="2" class="text-center text-muted py-3">Belum ada riwayat.</td></tr>
                                    <?php else: ?>
                                        <?php foreach($sent as $s): ?>
                                        <tr>
                                            <td width="20%"><?= date('d M H:i', strtotime($s['created_at'])) ?></td>
                                            <td>
                                                <span class="fw-bold text-dark"><?= htmlspecialchars($s['judul']) ?></span><br>
                                                <span class="text-muted small"><?= htmlspecialchars($s['pesan']) ?></span>
                                            </td>
                                        </tr>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </tbody>
                            </table>
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