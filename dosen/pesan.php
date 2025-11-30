<?php
// dosen/pesan.php
require_once "../config/auth.php";
require_once "../config/database.php";

$auth = new Auth();
if (!$auth->isLoggedIn() || !in_array($_SESSION['user']['role'], ['dosen', 'dosen_dpa'])) {
    header("Location: ../login.php"); exit;
}

$user_id = $_SESSION['user']['id'];
$folder = $_GET['folder'] ?? 'inbox';

// --- LOGIKA QUERY ---
if ($folder == 'sent') {
    // Log Aktivitas (Apa yang dilakukan dosen ini)
    $sql = "SELECT * FROM notifikasi WHERE sender_id = ? AND sender_type = 'dosen' ORDER BY created_at DESC";
    $titleBox = "Riwayat Aktivitas (Terkirim)";
    $emptyMsg = "Belum ada aktivitas tercatat.";
} else {
    // Notifikasi Masuk (Misal: Mahasiswa bimbingan mengajukan KRS)
    $sql = "SELECT * FROM notifikasi WHERE user_id = ? AND user_type = 'dosen' ORDER BY created_at DESC";
    $titleBox = "Notifikasi Masuk";
    $emptyMsg = "Belum ada notifikasi baru.";
}

$stmtData = $pdo->prepare($sql);
$stmtData->execute([$user_id]);
$listPesan = $stmtData->fetchAll();

// Update status dibaca
if ($folder == 'inbox' && !empty($listPesan)) {
    $pdo->prepare("UPDATE notifikasi SET is_read = 1 WHERE user_id = ? AND user_type = 'dosen'")->execute([$user_id]);
}

// Counters
$cntInbox = $pdo->query("SELECT COUNT(*) FROM notifikasi WHERE user_id=$user_id AND user_type='dosen'")->fetchColumn();
$cntUnread = $pdo->query("SELECT COUNT(*) FROM notifikasi WHERE user_id=$user_id AND user_type='dosen' AND is_read=0")->fetchColumn();
$cntSent = $pdo->query("SELECT COUNT(*) FROM notifikasi WHERE sender_id=$user_id AND sender_type='dosen'")->fetchColumn();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8"><title>Pesan | Dosen</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/style.css">
    <style>
        .info-title { color: #003366; font-weight: bold; font-size: 1.2rem; margin-bottom: 10px; border-bottom: 1px solid #ddd; padding-bottom: 5px; }
        .folder-link { text-decoration: underline; color: #0000CC; cursor: pointer; }
        .folder-link.active { color: #d98c00; font-weight: bold; text-decoration: none; }
        .folder-icon { color: #669966; font-size: 1.2rem; margin-right: 8px; }
        .msg-info-box { border: 1px solid #ddd; padding: 10px; background-color: #fff; margin-bottom: 20px; }
        .table-msg-header { background: linear-gradient(to bottom, #f0f8ff, #dbe9f4); border: 1px solid #a1c6e7; color: #003366; font-weight: bold; padding: 5px; }
    </style>
</head>
<body>
<?php include "../header.php"; ?>
<div class="container">
    <div class="row">
        <div class="col-md-9">
            <div class="content-box">
                <div class="msg-header">Pusat Pesan</div>
                <div class="row mb-4">
                    <div class="col-md-5">
                        <div class="msg-info-box">
                            <div class="d-flex align-items-center mb-2">
                                <i class="fas fa-inbox folder-icon"></i>
                                <a href="?folder=inbox" class="folder-link <?= $folder=='inbox'?'active':'' ?>">Kotak Masuk</a>
                                <span class="ms-auto small">(<b><?= $cntUnread ?></b>/<?= $cntInbox ?>)</span>
                            </div>
                            <div class="d-flex align-items-center border-top pt-2">
                                <i class="fas fa-history folder-icon"></i>
                                <a href="?folder=sent" class="folder-link <?= $folder=='sent'?'active':'' ?>">Riwayat Aktivitas</a>
                                <span class="ms-auto small">(<?= $cntSent ?>)</span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="info-title" style="font-size: 1rem;"><?= $titleBox ?></div>
                <div class="table-msg-header mb-3">
                    <table class="table table-hover table-sm mb-0 small">
                        <thead><tr><th width="20%">Waktu</th><th>Pesan</th><th width="15%" class="text-center">Tipe</th></tr></thead>
                        <tbody>
                            <?php if(empty($listPesan)): ?>
                                <tr><td colspan="3" class="text-center text-muted p-3"><?= $emptyMsg ?></td></tr>
                            <?php else: foreach($listPesan as $msg): ?>
                                <tr class="<?= ($folder=='inbox' && !$msg['is_read']) ? 'fw-bold bg-light' : '' ?>">
                                    <td><?= date('d M H:i', strtotime($msg['created_at'])) ?></td>
                                    <td>
                                        <span class="text-primary fw-bold"><?= htmlspecialchars($msg['judul']) ?></span><br>
                                        <?= htmlspecialchars($msg['pesan']) ?>
                                        <?php if($msg['link'] && $msg['link']!='#') echo " <a href='{$msg['link']}' class='ms-1'>[Lihat]</a>"; ?>
                                    </td>
                                    <td class="text-center"><span class="badge bg-secondary"><?= ucfirst($msg['tipe']) ?></span></td>
                                </tr>
                            <?php endforeach; endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-md-3"><?php include "sidebar.php"; ?></div>
    </div>
</div>
<?php include "../footer.php"; ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body></html>