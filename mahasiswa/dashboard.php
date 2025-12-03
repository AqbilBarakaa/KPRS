<?php
require_once "../config/auth.php";
require_once "../config/database.php";

$auth = new Auth();
if (!$auth->isLoggedIn() || ($_SESSION['user']['role'] ?? '') !== 'mahasiswa') {
    header("Location: ../login.php"); exit;
}

$user = $_SESSION['user'];
$nama = htmlspecialchars($user['nama']);
$user_id = $user['id'];

$stmtInbox = $pdo->prepare("SELECT * FROM notifikasi WHERE user_id = ? AND user_type = 'mahasiswa' ORDER BY created_at DESC LIMIT 5");
$stmtInbox->execute([$user_id]);
$inbox = $stmtInbox->fetchAll();

$stmtSent = $pdo->prepare("SELECT * FROM notifikasi WHERE sender_id = ? AND sender_type = 'mahasiswa' ORDER BY created_at DESC LIMIT 5");
$stmtSent->execute([$user_id]);
$sent = $stmtSent->fetchAll();
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
                    <p>Selamat Datang di Portal Akademik.</p>
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
                            <table class="table table-bordered table-sm mb-0 small">
                                <thead class="table-light"><tr><th>Waktu</th><th>Pesan Masuk</th><th>Status</th></tr></thead>
                                <tbody>
                                    <?php if(empty($inbox)): ?>
                                        <tr><td colspan="3" class="text-center text-muted">Belum ada riwayat.</td></tr>
                                    <?php else: ?>
                                        <?php foreach($inbox as $n): ?>
                                        <tr class="<?= $n['is_read'] ? '' : 'fw-bold bg-light' ?>">
                                            <td width="20%"><?= date('d M H:i', strtotime($n['created_at'])) ?></td>
                                            <td>
                                                <span class="text-<?= $n['tipe']=='error'?'danger':($n['tipe']=='success'?'success':'primary') ?>"><?= htmlspecialchars($n['judul']) ?></span><br>
                                                <?= htmlspecialchars($n['pesan']) ?>
                                            </td>
                                            <td width="10%" class="text-center"><?= $n['is_read'] ? '<span class="text-muted">Dibaca</span>' : '<span class="badge bg-success">Baru</span>' ?></td>
                                        </tr>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="tab-pane fade" id="sent">
                        <div class="table-responsive">
                            <table class="table table-bordered table-sm mb-0 small">
                                <thead class="table-light"><tr><th>Waktu</th><th>Pesan Terkirim</th><th>Tipe</th></tr></thead>
                                <tbody>
                                    <?php if(empty($sent)): ?>
                                        <tr><td colspan="3" class="text-center text-muted">Belum ada riwayat.</td></tr>
                                    <?php else: ?>
                                        <?php foreach($sent as $s): ?>
                                        <tr>
                                            <td width="20%"><?= date('d M H:i', strtotime($s['created_at'])) ?></td>
                                            <td>
                                                <span class="fw-bold text-dark"><?= htmlspecialchars($s['judul']) ?></span><br>
                                                <span class="text-muted"><?= htmlspecialchars($s['pesan']) ?></span>
                                            </td>
                                            <td width="10%" class="text-center"><span class="badge bg-secondary">Keluar</span></td>
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
<script src="../assets/js/script.js"></script>
</body>
</html>