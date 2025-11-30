<?php
// dosen/kaprodi/validasi_tambah_kelas.php
require_once "../../config/auth.php";
require_once "../../config/database.php";

$auth = new Auth();
if (!$auth->isLoggedIn() || $_SESSION['user']['role'] !== 'dosen_kaprodi') {
    header("Location: ../../login.php"); exit;
}

$kaprodi_id = $_SESSION['user']['id'];
$msg = ''; $err = '';

// --- PROSES VALIDASI ---
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_pengajuan = $_POST['id_pengajuan'];
    $aksi = $_POST['aksi']; 
    $catatan = trim($_POST['catatan'] ?? '');

    try {
        $pdo->beginTransaction();
        $stmtGet = $pdo->prepare("SELECT * FROM pengajuan_tambah_kelas WHERE id = ? FOR UPDATE");
        $stmtGet->execute([$id_pengajuan]);
        $data = $stmtGet->fetch();

        if (!$data || $data['status'] !== 'pending') {
            throw new Exception("Data sudah diproses.");
        }

        if ($aksi === 'approve') {
            // Update jadi approved (Lanjut ke TU)
            $pdo->prepare("UPDATE pengajuan_tambah_kelas SET status='approved', validator_id=?, catatan_validasi=?, tanggal_validasi=NOW() WHERE id=?")
                ->execute([$kaprodi_id, $catatan, $id_pengajuan]);
            
            // Jika ada kelas spesifik, auto update kuota & KRS
            if (!empty($data['kelas_id'])) {
                $pdo->prepare("UPDATE kelas SET terisi = terisi + 1 WHERE id = ?")->execute([$data['kelas_id']]);
                $cek = $pdo->prepare("SELECT id FROM krs_awal WHERE mahasiswa_id=? AND kelas_id=?");
                $cek->execute([$data['mahasiswa_id'], $data['kelas_id']]);
                if ($cek->rowCount() == 0) {
                    $pdo->prepare("INSERT INTO krs_awal (mahasiswa_id, kelas_id, status) VALUES (?,?,'terdaftar')")
                        ->execute([$data['mahasiswa_id'], $data['kelas_id']]);
                }
            }
            $msg = "Pengajuan disetujui. Diteruskan ke TU.";
        } else {
            $pdo->prepare("UPDATE pengajuan_tambah_kelas SET status='rejected', validator_id=?, catatan_validasi=?, tanggal_validasi=NOW() WHERE id=?")
                ->execute([$kaprodi_id, $catatan, $id_pengajuan]);
            $msg = "Pengajuan ditolak.";
        }
        $pdo->commit();
    } catch (Exception $e) {
        $pdo->rollBack();
        $err = "Gagal: " . $e->getMessage();
    }
}

// --- AMBIL DATA PENDING (GROUPING) ---
$queryPending = "
    SELECT p.*, m.nim, m.nama as nama_mhs, mk.kode_mk, mk.nama_mk, k.nama_kelas, k.kuota, k.terisi 
    FROM pengajuan_tambah_kelas p 
    JOIN mahasiswa m ON p.mahasiswa_id = m.id 
    JOIN mata_kuliah mk ON p.mata_kuliah_id = mk.id 
    LEFT JOIN kelas k ON p.kelas_id = k.id 
    WHERE p.status = 'pending' 
    ORDER BY mk.nama_mk ASC, p.tanggal_pengajuan ASC
";
$rawPending = $pdo->query($queryPending)->fetchAll();
$groupedPending = [];
foreach ($rawPending as $row) {
    $groupedPending[$row['kode_mk'] . " - " . $row['nama_mk']][] = $row;
}

// --- AMBIL DATA RIWAYAT (FLAT TABLE) ---
// Mengambil status Approved (oleh Kaprodi), Rejected, atau Completed (Sudah diproses TU)
$queryHistory = "
    SELECT p.*, m.nim, m.nama as nama_mhs, mk.nama_mk, k.nama_kelas
    FROM pengajuan_tambah_kelas p 
    JOIN mahasiswa m ON p.mahasiswa_id = m.id 
    JOIN mata_kuliah mk ON p.mata_kuliah_id = mk.id 
    LEFT JOIN kelas k ON p.kelas_id = k.id 
    WHERE p.status IN ('approved', 'rejected', 'completed')
    ORDER BY p.tanggal_validasi DESC LIMIT 50
";
$listHistory = $pdo->query($queryHistory)->fetchAll();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Validasi Tambah Kelas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="../../assets/css/style.css">
</head>
<body>

<?php include "../../header.php"; ?>

<div class="container">
    <div class="row">
        <div class="col-md-9">
            <div class="content-box">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div class="msg-header mb-0">Validasi Pengajuan</div>
                </div>

                <?php if ($msg) echo "<div class='alert alert-success'>$msg</div>"; ?>
                <?php if ($err) echo "<div class='alert alert-danger'>$err</div>"; ?>

                <ul class="nav nav-tabs mb-3" id="valTab" role="tablist">
                    <li class="nav-item">
                        <button class="nav-link active" id="pending-tab" data-bs-toggle="tab" data-bs-target="#pending" type="button">
                            Menunggu Validasi <span class="badge bg-warning text-dark"><?= count($rawPending) ?></span>
                        </button>
                    </li>
                    <li class="nav-item">
                        <button class="nav-link" id="history-tab" data-bs-toggle="tab" data-bs-target="#history" type="button">
                            Riwayat Validasi
                        </button>
                    </li>
                </ul>

                <div class="tab-content" id="valTabContent">
                    
                    <div class="tab-pane fade show active" id="pending">
                        <?php if (empty($groupedPending)): ?>
                            <div class="alert alert-info text-center">Tidak ada pengajuan pending.</div>
                        <?php else: ?>
                            <?php foreach ($groupedPending as $mkTitle => $listRequests): ?>
                                <div class="card mb-4 border-0 shadow-sm">
                                    <div class="card-header bg-light border-bottom fw-bold text-primary">
                                        <i class="fas fa-book me-2"></i> <?= htmlspecialchars($mkTitle) ?>
                                        <span class="badge bg-secondary float-end"><?= count($listRequests) ?> Pengajuan</span>
                                    </div>
                                    <div class="card-body p-0">
                                        <div class="table-responsive">
                                            <table class="table table-hover align-middle mb-0 table-bordered">
                                                <thead class="table-secondary">
                                                    <tr>
                                                        <th>Mahasiswa</th>
                                                        <th>Target</th>
                                                        <th>Alasan</th>
                                                        <th width="25%">Aksi</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php foreach ($listRequests as $row): ?>
                                                    <tr>
                                                        <td><b><?= htmlspecialchars($row['nama_mhs']) ?></b><br><small><?= htmlspecialchars($row['nim']) ?></small></td>
                                                        <td><?= $row['nama_kelas'] ? htmlspecialchars($row['nama_kelas']) : '<span class="badge bg-secondary">General</span>' ?></td>
                                                        <td><small>"<?= htmlspecialchars($row['alasan']) ?>"</small></td>
                                                        <td>
                                                            <form method="POST">
                                                                <input type="hidden" name="id_pengajuan" value="<?= $row['id'] ?>">
                                                                <input type="text" name="catatan" class="form-control form-control-sm mb-1" placeholder="Catatan...">
                                                                <div class="d-flex gap-1">
                                                                    <button type="submit" name="aksi" value="approve" class="btn btn-success btn-sm w-100">Terima</button>
                                                                    <button type="submit" name="aksi" value="reject" class="btn btn-danger btn-sm w-100">Tolak</button>
                                                                </div>
                                                            </form>
                                                        </td>
                                                    </tr>
                                                    <?php endforeach; ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>

                    <div class="tab-pane fade" id="history">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover small align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>Tgl Validasi</th>
                                        <th>Mahasiswa</th>
                                        <th>Mata Kuliah</th>
                                        <th>Status Akhir</th>
                                        <th>Catatan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if(empty($listHistory)): ?>
                                        <tr><td colspan="5" class="text-center">Belum ada riwayat.</td></tr>
                                    <?php endif; ?>
                                    <?php foreach($listHistory as $h): ?>
                                    <tr>
                                        <td><?= date('d/m/y H:i', strtotime($h['tanggal_validasi'])) ?></td>
                                        <td><?= htmlspecialchars($h['nama_mhs']) ?></td>
                                        <td><?= htmlspecialchars($h['nama_mk']) ?></td>
                                        <td>
                                            <?php 
                                                if($h['status']=='approved') echo '<span class="badge bg-success">Disetujui (Ke TU)</span>';
                                                elseif($h['status']=='completed') echo '<span class="badge bg-primary">Selesai (Oleh TU)</span>';
                                                elseif($h['status']=='rejected') echo '<span class="badge bg-danger">Ditolak</span>';
                                            ?>
                                        </td>
                                        <td><?= htmlspecialchars($h['catatan_validasi'] ?? '-') ?></td>
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
<?php include "../../footer.php"; ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body></html>