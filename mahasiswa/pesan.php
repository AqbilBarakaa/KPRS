<?php
require_once "../config/auth.php";
require_once "../config/database.php";

$auth = new Auth();
if (!$auth->isLoggedIn() || ($_SESSION['user']['role'] ?? '') !== 'mahasiswa') {
    header("Location: ../login.php"); exit;
}

$user_id = $_SESSION['user']['id'];
$folder = $_GET['folder'] ?? 'inbox';

if ($folder == 'sent') {
    $sql = "SELECT * FROM notifikasi WHERE sender_id = ? AND sender_type = 'mahasiswa' ORDER BY created_at DESC";
    $titleBox = "Pesan Terkirim";
    $emptyMsg = "Belum ada aktivitas pengajuan.";
} else {
    $sql = "SELECT * FROM notifikasi WHERE user_id = ? AND user_type = 'mahasiswa' ORDER BY created_at DESC";
    $titleBox = "Notifikasi Masuk";
    $emptyMsg = "Belum ada notifikasi baru.";
}

$stmtData = $pdo->prepare($sql);
$stmtData->execute([$user_id]);
$listPesan = $stmtData->fetchAll();

if ($folder == 'inbox' && !empty($listPesan)) {
    $pdo->prepare("UPDATE notifikasi SET is_read = 1 WHERE user_id = ? AND user_type = 'mahasiswa'")->execute([$user_id]);
}

$cntInbox = $pdo->query("SELECT COUNT(*) FROM notifikasi WHERE user_id=$user_id AND user_type='mahasiswa'")->fetchColumn();
$cntUnread = $pdo->query("SELECT COUNT(*) FROM notifikasi WHERE user_id=$user_id AND user_type='mahasiswa' AND is_read=0")->fetchColumn();
$cntSent = $pdo->query("SELECT COUNT(*) FROM notifikasi WHERE sender_id=$user_id AND sender_type='mahasiswa'")->fetchColumn();

?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Kotak Pesan | Portal Akademik</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/style.css">
    <style>
        .info-title { color: #003366; font-weight: bold; font-size: 1.2rem; margin-bottom: 10px; border-bottom: 1px solid #ddd; padding-bottom: 5px; }
        .folder-link { text-decoration: underline; color: #0000CC; font-weight: normal; cursor: pointer; }
        .folder-link.active { color: #d98c00; font-weight: bold; text-decoration: none; }
        .folder-link:hover { color: #b36b00; }
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
                <div class="msg-header">Kotak Pesan</div>

                <p class="small text-dark mb-4">
                    <strong>Keterangan :</strong><br>
                    Halaman ini berisi riwayat notifikasi sistem akademik dan catatan aktivitas pengajuan Anda.
                </p>

                <div class="row mb-4">
                    <div class="col-md-5">
                        <div class="msg-info-box">
                            <div class="d-flex align-items-center mb-2">
                                <i class="fas fa-inbox folder-icon"></i>
                                <a href="?folder=inbox" class="folder-link <?= $folder=='inbox'?'active':'' ?>">Kotak Masuk</a>
                                <span class="ms-auto small">
                                    (<b><?= $cntUnread ?></b>/<?= $cntInbox ?>)
                                </span>
                            </div>
                            <div class="d-flex align-items-center border-top pt-2">
                                <i class="fas fa-paper-plane folder-icon"></i>
                                <a href="?folder=sent" class="folder-link <?= $folder=='sent'?'active':'' ?>">Kotak Terkirim</a>
                                <span class="ms-auto small">(<?= $cntSent ?>) Pesan</span>
                            </div>
                            </div>
                    </div>
                </div>

                <div class="info-title" style="font-size: 1rem;"><?= $titleBox ?></div>

                <div class="table-msg-header mb-3">
                    <?php if(empty($listPesan)): ?>
                        <div class="d-flex align-items-center justify-content-center p-4">
                            <i class="fas fa-info-circle fa-2x text-secondary me-3"></i>
                            <div>
                                <strong class="text-dark">INFORMASI</strong><br>
                                <span class="small text-muted"><?= $emptyMsg ?></span>
                            </div>
                        </div>
                    <?php else: ?>
                        <table class="table table-hover table-sm mb-0 small">
                            <thead>
                                <tr>
                                    <th width="20%">Waktu</th>
                                    <th>Pesan</th>
                                    <th width="15%" class="text-center">Kategori</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($listPesan as $msg): ?>
                                <tr class="<?= ($folder=='inbox' && !$msg['is_read']) ? 'fw-bold bg-light' : '' ?>">
                                    <td class="align-middle"><?= date('d M Y H:i', strtotime($msg['created_at'])) ?></td>
                                    <td class="align-middle">
                                        <span class="text-primary fw-bold"><?= htmlspecialchars($msg['judul']) ?></span><br>
                                        <span class="text-dark"><?= htmlspecialchars($msg['pesan']) ?></span>
                                        
                                        <?php if(!empty($msg['link']) && $msg['link'] != '#'): ?>
                                            <br><a href="<?= $msg['link'] ?>" class="text-decoration-none fst-italic small text-success"><i class="fas fa-link"></i> Lihat Detail</a>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-center align-middle">
                                        <?php if($msg['tipe'] == 'success'): ?>
                                            <span class="badge bg-success">Selesai</span>
                                        <?php elseif($msg['tipe'] == 'error'): ?>
                                            <span class="badge bg-danger">Ditolak</span>
                                        <?php elseif($msg['tipe'] == 'warning'): ?>
                                            <span class="badge bg-warning text-dark">Pending</span>
                                        <?php else: ?>
                                            <span class="badge bg-info text-dark">Info</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php endif; ?>
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