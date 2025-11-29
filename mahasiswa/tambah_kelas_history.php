<?php
require_once "../config/auth.php";
require_once "../config/database.php";

$auth = new Auth();
if (!$auth->isLoggedIn() || $_SESSION['user']['role'] !== 'mahasiswa') {
    header("Location: ../login.php");
    exit;
}

$mahasiswa_id = $_SESSION['user']['id'];

if (isset($_POST['batalkan_id'])) {
    $stmtDel = $pdo->prepare("DELETE FROM pengajuan_tambah_kelas WHERE id = ? AND mahasiswa_id = ? AND status = 'pending'");
    $stmtDel->execute([$_POST['batalkan_id'], $mahasiswa_id]);
    header("Location: tambah_kelas_history.php"); exit;
}

$stmt = $pdo->prepare("SELECT p.*, mk.kode_mk, mk.nama_mk, k.nama_kelas, d.nama as validator FROM pengajuan_tambah_kelas p JOIN mata_kuliah mk ON p.mata_kuliah_id = mk.id JOIN kelas k ON p.kelas_id = k.id LEFT JOIN dosen d ON p.validator_id = d.id WHERE p.mahasiswa_id = ? ORDER BY p.tanggal_pengajuan DESC");
$stmt->execute([$mahasiswa_id]);
$riwayat = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Riwayat Pengajuan | Portal Akademik</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>

<?php include 'header.php'; ?>

<div class="container">
    <div class="row">
        <div class="col-md-9">
            <div class="content-box">
                <div class="msg-header">Riwayat Pengajuan Tambah Kelas</div>
                <div class="table-responsive">
                    <table class="table table-hover table-bordered small">
                        <thead class="table-light">
                            <tr><th>Tgl</th><th>Matkul</th><th>Kelas</th><th>Status</th><th>Catatan</th><th>Aksi</th></tr>
                        </thead>
                        <tbody>
                            <?php if (!$riwayat): ?><tr><td colspan="6" class="text-center">Belum ada data.</td></tr><?php endif; ?>
                            <?php foreach ($riwayat as $r): ?>
                            <tr>
                                <td><?= date('d/m/y H:i', strtotime($r['tanggal_pengajuan'])) ?></td>
                                <td><?= htmlspecialchars($r['nama_mk']) ?></td>
                                <td><?= htmlspecialchars($r['nama_kelas']) ?></td>
                                <td>
                                    <?php if($r['status']=='pending') echo '<span class="badge bg-warning text-dark">Pending</span>'; 
                                          elseif($r['status']=='approved') echo '<span class="badge bg-success">Disetujui</span>';
                                          else echo '<span class="badge bg-danger">Ditolak</span>'; ?>
                                </td>
                                <td><?= htmlspecialchars($r['catatan_validasi'] ?? '-') ?></td>
                                <td>
                                    <?php if($r['status']=='pending'): ?>
                                    <form method="POST" onsubmit="return confirm('Batalkan?');">
                                        <input type="hidden" name="batalkan_id" value="<?= $r['id'] ?>">
                                        <button class="btn btn-danger btn-sm py-0">Batal</button>
                                    </form>
                                    <?php else: echo "-"; endif; ?>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <?php include 'sidebar.php'; ?>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>