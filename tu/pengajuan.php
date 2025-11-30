<?php
// tu/pengajuan.php
require_once "../config/auth.php";
require_once "../config/database.php";

$auth = new Auth();
if (!$auth->isLoggedIn() || ($_SESSION['user']['role'] ?? '') !== 'tu') {
    header("Location: ../login.php"); exit;
}

$msg = ''; $err = '';

// --- TANDAI SELESAI ---
if (isset($_POST['selesai_id'])) {
    try {
        $id = $_POST['selesai_id'];
        // Ubah status dari 'approved' menjadi 'completed'
        $pdo->prepare("UPDATE pengajuan_tambah_kelas SET status='completed' WHERE id=?")->execute([$id]);
        $msg = "Pengajuan ditandai selesai.";
    } catch (Exception $e) { $err = "Gagal memproses."; }
}

// Ambil Data: Approved (Masuk) & Completed (Riwayat)
$stmtNew = $pdo->query("SELECT p.*, m.nim, m.nama as nama_mhs, mk.nama_mk, mk.kode_mk 
                        FROM pengajuan_tambah_kelas p 
                        JOIN mahasiswa m ON p.mahasiswa_id = m.id 
                        JOIN mata_kuliah mk ON p.mata_kuliah_id = mk.id 
                        WHERE p.status = 'approved' ORDER BY p.tanggal_validasi ASC");
$dataNew = $stmtNew->fetchAll();

$stmtHist = $pdo->query("SELECT p.*, m.nim, m.nama as nama_mhs, mk.nama_mk, mk.kode_mk 
                         FROM pengajuan_tambah_kelas p 
                         JOIN mahasiswa m ON p.mahasiswa_id = m.id 
                         JOIN mata_kuliah mk ON p.mata_kuliah_id = mk.id 
                         WHERE p.status = 'completed' ORDER BY p.tanggal_validasi DESC LIMIT 50");
$dataHist = $stmtHist->fetchAll();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8"><title>Pengajuan Kelas | TU</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
<?php include "../header.php"; ?>
<div class="container">
    <div class="row">
        <div class="col-md-9">
            <div class="content-box">
                <div class="msg-header">Pengajuan Tambah Kelas (Dari Kaprodi)</div>
                
                <?php if($msg) echo "<div class='alert alert-success'>$msg</div>"; ?>

                <ul class="nav nav-tabs mb-3" id="tuTab" role="tablist">
                    <li class="nav-item">
                        <button class="nav-link active" id="new-tab" data-bs-toggle="tab" data-bs-target="#new" type="button">
                            Permintaan Baru <span class="badge bg-danger"><?= count($dataNew) ?></span>
                        </button>
                    </li>
                    <li class="nav-item">
                        <button class="nav-link" id="done-tab" data-bs-toggle="tab" data-bs-target="#done" type="button">
                            Riwayat Selesai
                        </button>
                    </li>
                </ul>

                <div class="tab-content">
                    
                    <div class="tab-pane fade show active" id="new">
                        <div class="alert alert-warning small">
                            <i class="fas fa-info-circle"></i> Daftar ini adalah pengajuan yang <b>sudah disetujui Kaprodi</b>.
                            Silakan buka kelas/kuota di menu <b>Data Kelas</b>, lalu klik <b>Tandai Selesai</b> di sini.
                        </div>
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover align-middle small">
                                <thead class="table-dark">
                                    <tr><th>Tgl Validasi</th><th>Mahasiswa</th><th>Mata Kuliah</th><th>Catatan Kaprodi</th><th>Aksi</th></tr>
                                </thead>
                                <tbody>
                                    <?php if(empty($dataNew)): ?>
                                        <tr><td colspan="5" class="text-center">Tidak ada permintaan baru.</td></tr>
                                    <?php endif; ?>
                                    <?php foreach($dataNew as $r): ?>
                                    <tr>
                                        <td><?= date('d M Y', strtotime($r['tanggal_validasi'])) ?></td>
                                        <td><?= htmlspecialchars($r['nama_mhs']) ?><br><small><?= htmlspecialchars($r['nim']) ?></small></td>
                                        <td><?= htmlspecialchars($r['nama_mk']) ?> (<?= $r['kode_mk'] ?>)</td>
                                        <td><?= htmlspecialchars($r['catatan_validasi'] ?? '-') ?></td>
                                        <td>
                                            <form method="POST" onsubmit="return confirm('Sudah diproses?');">
                                                <input type="hidden" name="selesai_id" value="<?= $r['id'] ?>">
                                                <button class="btn btn-success btn-sm w-100">Tandai Selesai</button>
                                            </form>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="tab-pane fade" id="done">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped small align-middle">
                                <thead class="table-light">
                                    <tr><th>Tgl Selesai</th><th>Mahasiswa</th><th>Mata Kuliah</th><th>Status</th></tr>
                                </thead>
                                <tbody>
                                    <?php foreach($dataHist as $h): ?>
                                    <tr>
                                        <td><?= date('d M Y', strtotime($h['tanggal_validasi'])) ?></td>
                                        <td><?= htmlspecialchars($h['nama_mhs']) ?></td>
                                        <td><?= htmlspecialchars($h['nama_mk']) ?></td>
                                        <td><span class="badge bg-secondary">Selesai</span></td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>
            </div>
        </div>
        <div class="col-md-3"><?php include "sidebar.php"; ?></div>
    </div>
</div>
<?php include "../footer.php"; ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body></html>