<?php
// mahasiswa/tambah_kelas_history.php
require_once "../config/auth.php";
require_once "../config/database.php";

$auth = new Auth();
if (!$auth->isLoggedIn() || ($_SESSION['user']['role'] ?? '') !== 'mahasiswa') {
    header("Location: ../login.php");
    exit;
}

$mahasiswa_id = $_SESSION['user']['id'];

// --- PROSES BATALKAN ---
if (isset($_POST['batalkan_id'])) {
    $id_batal = $_POST['batalkan_id'];
    // Hapus hanya jika status masih pending
    $stmtDel = $pdo->prepare("DELETE FROM pengajuan_tambah_kelas WHERE id = ? AND mahasiswa_id = ? AND status = 'pending'");
    $stmtDel->execute([$id_batal, $mahasiswa_id]);
    
    header("Location: tambah_kelas_history.php");
    exit;
}

// --- AMBIL DATA RIWAYAT ---
// PERBAIKAN: Gunakan LEFT JOIN ke tabel kelas, karena saat request awal kelas_id masih NULL
$stmt = $pdo->prepare("
    SELECT p.*, mk.kode_mk, mk.nama_mk, 
           k.nama_kelas, 
           d.nama as nama_validator
    FROM pengajuan_tambah_kelas p
    JOIN mata_kuliah mk ON p.mata_kuliah_id = mk.id
    LEFT JOIN kelas k ON p.kelas_id = k.id  
    LEFT JOIN dosen d ON p.validator_id = d.id
    WHERE p.mahasiswa_id = ?
    ORDER BY p.tanggal_pengajuan DESC
");
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

<?php include "../header.php"; ?>

<div class="container">
    <div class="row">
        <div class="col-md-9">
            <div class="content-box">
                <div class="msg-header">
                    Riwayat Pengajuan Tambah Kelas
                </div>
                
                <div class="table-responsive">
                    <table class="table table-hover table-bordered align-middle small">
                        <thead class="table-light text-center">
                            <tr>
                                <th>Tanggal</th>
                                <th>Mata Kuliah</th>
                                <th>Target Kelas</th>
                                <th>Status</th>
                                <th>Catatan</th>
                                <th width="100">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (count($riwayat) === 0): ?>
                                <tr>
                                    <td colspan="6" class="text-center py-4 text-muted">
                                        <i class="fas fa-info-circle mb-2 d-block" style="font-size: 1.5rem;"></i>
                                        Belum ada riwayat pengajuan.
                                    </td>
                                </tr>
                            <?php endif; ?>

                            <?php foreach ($riwayat as $r): ?>
                            <tr>
                                <td class="text-center"><?= date('d/m/Y H:i', strtotime($r['tanggal_pengajuan'])) ?></td>
                                <td>
                                    <span class="fw-bold"><?= htmlspecialchars($r['nama_mk']) ?></span>
                                    <br><small class="text-muted"><?= htmlspecialchars($r['kode_mk']) ?></small>
                                </td>
                                <td class="text-center">
                                    <?php if ($r['nama_kelas']): ?>
                                        <span class="badge bg-info text-dark"><?= htmlspecialchars($r['nama_kelas']) ?></span>
                                    <?php else: ?>
                                        <span class="text-muted fst-italic">- Belum ditentukan -</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-center">
                                    <?php 
                                    if ($r['status'] == 'pending') {
                                        echo '<span class="badge bg-warning text-dark">Menunggu Validasi</span>';
                                    } elseif ($r['status'] == 'approved') {
                                        echo '<span class="badge bg-primary">Disetujui Kaprodi</span><br><small class="text-muted">Menunggu TU</small>';
                                    } elseif ($r['status'] == 'completed') {
                                        echo '<span class="badge bg-success">Selesai (Kelas Dibuka)</span>';
                                    } elseif ($r['status'] == 'rejected') {
                                        echo '<span class="badge bg-danger">Ditolak</span>';
                                    }
                                    ?>
                                </td>
                                <td>
                                    <?php if ($r['catatan_validasi']): ?>
                                        <?= htmlspecialchars($r['catatan_validasi']) ?>
                                    <?php else: ?>
                                        <span class="text-muted">-</span>
                                    <?php endif; ?>
                                    
                                    <?php if ($r['nama_validator']): ?>
                                        <br><small class="text-muted" style="font-size: 0.75rem;">Validator: <?= htmlspecialchars($r['nama_validator']) ?></small>
                                    <?php endif; ?>
                                </td>
                                <td class="text-center">
                                    <?php if ($r['status'] === 'pending'): ?>
                                        <form method="POST" onsubmit="return confirm('Yakin ingin membatalkan pengajuan ini?');">
                                            <input type="hidden" name="batalkan_id" value="<?= $r['id'] ?>">
                                            <button type="submit" class="btn btn-danger btn-sm py-1 w-100">
                                                <i class="fas fa-times me-1"></i> Batal
                                            </button>
                                        </form>
                                    <?php else: ?>
                                        <button class="btn btn-secondary btn-sm w-100" disabled>Selesai</button>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
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