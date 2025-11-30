<?php
// tu/pesan.php
require_once "../config/auth.php";
require_once "../config/database.php";

$auth = new Auth();
if (!$auth->isLoggedIn() || ($_SESSION['user']['role'] ?? '') !== 'tu') {
    header("Location: ../login.php"); exit;
}

$user_id = $_SESSION['user']['id'];
$folder = $_GET['folder'] ?? 'inbox';

if ($folder == 'sent') {
    $sql = "SELECT * FROM notifikasi WHERE sender_id = ? AND sender_type = 'tata_usaha' ORDER BY created_at DESC";
    $titleBox = "Riwayat Aktivitas TU";
    $emptyMsg = "Belum ada aktivitas.";
} else {
    $sql = "SELECT * FROM notifikasi WHERE user_id = ? AND user_type = 'tata_usaha' ORDER BY created_at DESC";
    $titleBox = "Notifikasi Masuk";
    $emptyMsg = "Belum ada notifikasi.";
}

$stmtData = $pdo->prepare($sql);
$stmtData->execute([$user_id]);
$listPesan = $stmtData->fetchAll();

if ($folder == 'inbox' && !empty($listPesan)) {
    $pdo->prepare("UPDATE notifikasi SET is_read = 1 WHERE user_id = ? AND user_type = 'tata_usaha'")->execute([$user_id]);
}

$cntInbox = $pdo->query("SELECT COUNT(*) FROM notifikasi WHERE user_id=$user_id AND user_type='tata_usaha'")->fetchColumn();
$cntUnread = $pdo->query("SELECT COUNT(*) FROM notifikasi WHERE user_id=$user_id AND user_type='tata_usaha' AND is_read=0")->fetchColumn();
$cntSent = $pdo->query("SELECT COUNT(*) FROM notifikasi WHERE sender_id=$user_id AND sender_type='tata_usaha'")->fetchColumn();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8"><title>Pesan | TU</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/style.css">
    <style>
        .info-title { color: #003366; font-weight: bold; font-size: 1.2rem; margin-bottom: 10px; border-bottom: 1px solid #ddd; padding-bottom: 5px; }
        .folder-link { text-decoration: underline; color: #0000CC; cursor: pointer; }
        .folder-link.active { color: #d98c00; font-weight: bold; text-decoration: none; }
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
                <div class="msg-header">Kotak Pesan</div>
                <div class="row mb-4">
                    <div class="col-md-5">
                        <div class="msg-info-box">
                            <div class="d-flex align-items-center mb-2">
                                <i class="fas fa-inbox text-success me-2" style="font-size:1.2rem"></i>
                                <a href="?folder=inbox" class="folder-link <?= $folder=='inbox'?'active':'' ?>">Kotak Masuk</a>
                                <span class="ms-auto small">(<b><?= $cntUnread ?></b>/<?= $cntInbox ?>)</span>
                            </div>
                            <div class="d-flex align-items-center border-top pt-2">
                                <i class="fas fa-history text-success me-2" style="font-size:1.2rem"></i>
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
                                        <?php if($msg['link'] && $msg['link']!='#') echo " <a href='{$msg['link']}' class='ms-1'>[Link]</a>"; ?>
                                    </td>
                                    <td class="text-center"><span class="badge bg-secondary"><?= $msg['tipe'] ?></span></td>
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