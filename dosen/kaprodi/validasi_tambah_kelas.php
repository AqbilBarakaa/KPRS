<?php
require_once "../../config/auth.php";
require_once "../../config/database.php";

$auth = new Auth();
if (!$auth->isLoggedIn() || $_SESSION['user']['role'] !== 'dosen_kaprodi') {
    header("Location: ../../login.php"); exit;
}

$kaprodi_id = $_SESSION['user']['id'];
$msg = ''; $err = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['aksi'])) {
    $aksi = $_POST['aksi'];       
    $catatan = trim($_POST['catatan'] ?? '');
    
    if (isset($_POST['mk_target'])) {
        $mk_id = $_POST['mk_target'];
        try {
            $pdo->beginTransaction();
            
            $stmtMk = $pdo->prepare("SELECT nama_mk FROM mata_kuliah WHERE id = ?");
            $stmtMk->execute([$mk_id]);
            $nama_mk = $stmtMk->fetchColumn();

            $stmtGet = $pdo->prepare("SELECT id, mahasiswa_id, kelas_id FROM pengajuan_tambah_kelas WHERE mata_kuliah_id = ? AND status = 'pending'");
            $stmtGet->execute([$mk_id]);
            $listPengajuan = $stmtGet->fetchAll();

            if (empty($listPengajuan)) throw new Exception("Tidak ada pengajuan pending.");

            $count = 0;
            foreach ($listPengajuan as $p) {
                $statusBaru = ($aksi === 'approve') ? 'approved' : 'rejected';
                
                $pdo->prepare("UPDATE pengajuan_tambah_kelas SET status=?, validator_id=?, catatan_validasi=?, tanggal_validasi=NOW() WHERE id=?")
                    ->execute([$statusBaru, $kaprodi_id, $catatan, $p['id']]);

                if ($aksi === 'approve' && !empty($p['kelas_id'])) {
                    $pdo->prepare("UPDATE kelas SET terisi = terisi + 1 WHERE id = ?")->execute([$p['kelas_id']]);
                }

                if (function_exists('kirimNotifikasi')) {
                    $judulMhs = ($aksi === 'approve') ? "Pengajuan Disetujui" : "Pengajuan Ditolak";
                    $pesanMhs = "Pengajuan tambah kelas $nama_mk " . ($aksi === 'approve' ? "disetujui" : "ditolak") . ". Catatan: " . ($catatan ?: '-');
                    $tipeMhs = ($aksi === 'approve') ? 'success' : 'error';
                    
                    kirimNotifikasi($pdo, $p['mahasiswa_id'], 'mahasiswa', $judulMhs, $pesanMhs, $tipeMhs, 'validasi_tambah_kelas.php');
                }
                $count++;
            }
            
            if (function_exists('kirimNotifikasi')) {
                $judulKap = "Validasi Berhasil";
                $pesanKap = "Anda telah " . ($aksi === 'approve' ? "menyetujui" : "menolak") . " $count pengajuan untuk mata kuliah $nama_mk.";
                kirimNotifikasi($pdo, $kaprodi_id, 'dosen', $judulKap, $pesanKap, 'info', '#', $kaprodi_id, 'dosen');
            }

            if ($aksi === 'approve') {
                $stmtTU = $pdo->query("SELECT id FROM tata_usaha");
                while ($tu = $stmtTU->fetch()) {
                    kirimNotifikasi($pdo, $tu['id'], 'tata_usaha', 'Pengajuan Kelas Baru', "Terdapat $count pengajuan untuk Mata Kuliah $nama_mk yang telah disetujui Kaprodi.", 'warning', 'pengajuan.php');
                }
            }
            
            $pdo->commit();
            $msg = "Berhasil memproses $count pengajuan.";
        } catch (Exception $e) {
            $pdo->rollBack();
            $err = "Gagal: " . $e->getMessage();
        }
    } 
    elseif (isset($_POST['id_pengajuan'])) {
    }
}

$queryPending = "
    SELECT p.*, m.nim, m.nama as nama_mhs, mk.kode_mk, mk.nama_mk, mk.semester, k.nama_kelas 
    FROM pengajuan_tambah_kelas p 
    JOIN mahasiswa m ON p.mahasiswa_id = m.id 
    JOIN mata_kuliah mk ON p.mata_kuliah_id = mk.id 
    LEFT JOIN kelas k ON p.kelas_id = k.id 
    WHERE p.status = 'pending' 
    ORDER BY mk.semester ASC, mk.nama_mk ASC, p.tanggal_pengajuan ASC
";
$rawPending = $pdo->query($queryPending)->fetchAll();

$groupedData = [];
foreach ($rawPending as $row) {
    $sem = $row['semester'];
    $mkKey = $row['kode_mk'] . " - " . $row['nama_mk'];
    $groupedData[$sem][$row['mata_kuliah_id']]['info'] = ['nama' => $mkKey];
    $groupedData[$sem][$row['mata_kuliah_id']]['list'][] = $row;
}

$queryHistory = "
    SELECT 
        DATE_FORMAT(p.tanggal_validasi, '%Y-%m-%d %H:%i') as tgl_group,
        mk.nama_mk, mk.kode_mk, p.status, p.catatan_validasi,
        COUNT(*) as jumlah_mhs
    FROM pengajuan_tambah_kelas p 
    JOIN mata_kuliah mk ON p.mata_kuliah_id = mk.id 
    WHERE p.validator_id = ? AND p.status != 'pending'
    GROUP BY tgl_group, mk.id, p.status, p.catatan_validasi
    ORDER BY tgl_group DESC LIMIT 20
";
$stmtHist = $pdo->prepare($queryHistory);
$stmtHist->execute([$kaprodi_id]);
$listHistory = $stmtHist->fetchAll();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8"><title>Validasi Tambah Kelas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="../../assets/css/style.css">
    <style>
        .accordion-button:not(.collapsed) { background-color: #e7f1ff; color: #0c63e4; }
        .card-mk { border-left: 4px solid #0d6efd; }
    </style>
</head>
<body>

<?php include "../../header.php"; ?>

<div class="container">
    <div class="row">
        <div class="col-md-9">
            <div class="content-box">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div class="msg-header mb-0">Validasi Pengajuan Tambah Kelas</div>
                </div>

                <?php if ($msg): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fas fa-check-circle me-2"></i> <?= $msg ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>

                <?php if ($err): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fas fa-exclamation-triangle me-2"></i> <?= $err ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>
                
                <ul class="nav nav-tabs mb-3" id="kaprodiTab" role="tablist">
                    <li class="nav-item">
                        <?php foreach ($groupedData as $sem => $matkulGroup): ?>
                            <button class="nav-link active" id="pending-tab" data-bs-toggle="tab" data-bs-target="#pending" type="button">
                                Menunggu Validasi <span class="badge bg-warning text-dark"><?= count($matkulGroup) ?></span>
                            </button>
                        <?php endforeach; ?>
                    </li>
                    <li class="nav-item">
                        <button class="nav-link" id="history-tab" data-bs-toggle="tab" data-bs-target="#history" type="button">
                            Riwayat Validasi
                        </button>
                    </li>
                </ul>

                <div class="tab-content" id="kaprodiTabContent">
                    
                    <div class="tab-pane fade show active" id="pending">
                        <?php if (empty($groupedData)): ?>
                            <div class="alert alert-info text-center py-4">Tidak ada pengajuan pending.</div>
                        <?php else: ?>
                            <div class="accordion" id="accSemester">
                                <?php foreach ($groupedData as $sem => $matkulGroup): ?>
                                    <div class="accordion-item mb-3 border">
                                        <h2 class="accordion-header" id="head<?= $sem ?>">
                                            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapse<?= $sem ?>">
                                                <strong>Semester <?= $sem ?></strong>
                                                <span class="badge bg-secondary ms-2"><?= count($matkulGroup) ?> Mata Kuliah</span>
                                            </button>
                                        </h2>
                                        <div id="collapse<?= $sem ?>" class="accordion-collapse collapse show" data-bs-parent="#accSemester">
                                            <div class="accordion-body bg-light">
                                                
                                                <?php foreach ($matkulGroup as $mkId => $data): ?>
                                                    <div class="card card-mk mb-3 shadow-sm">
                                                        <div class="card-body">
                                                            <div class="d-flex justify-content-between align-items-center mb-2">
                                                                <h6 class="fw-bold mb-0 text-primary"><?= $data['info']['nama'] ?></h6>
                                                                <span class="badge bg-info text-dark"><?= count($data['list']) ?> Mahasiswa</span>
                                                            </div>

                                                            <div class="table-responsive mb-3">
                                                                <table class="table table-sm table-bordered mb-0 bg-white small">
                                                                    <thead class="table-light">
                                                                        <tr><th>No</th><th>Mahasiswa</th><th>Alasan</th><th>Tgl</th></tr>
                                                                    </thead>
                                                                    <tbody>
                                                                        <?php $no=1; foreach ($data['list'] as $mhs): ?>
                                                                        <tr>
                                                                            <td class="text-center"><?= $no++ ?></td>
                                                                            <td><b><?= htmlspecialchars($mhs['nama_mhs']) ?></b><br><?= htmlspecialchars($mhs['nim']) ?></td>
                                                                            <td><?= htmlspecialchars($mhs['alasan']) ?></td>
                                                                            <td><?= date('d/m H:i', strtotime($mhs['tanggal_pengajuan'])) ?></td>
                                                                        </tr>
                                                                        <?php endforeach; ?>
                                                                    </tbody>
                                                                </table>
                                                            </div>

                                                            <form method="POST" class="row g-2 align-items-center bg-light p-2 border rounded">
                                                                <input type="hidden" name="mk_target" value="<?= $mkId ?>">
                                                                <div class="col-md-8">
                                                                    <input type="text" name="catatan" class="form-control form-control-sm" placeholder="Berikan Catatan (Opsional)...">
                                                                </div>
                                                                <div class="col-md-2">
                                                                    <button type="submit" name="aksi" value="approve" class="btn btn-success btn-sm w-100 fw-bold" onclick="return confirm('ACC Semua?')"><i class="fas fa-check-double"></i> ACC</button>
                                                                </div>
                                                                <div class="col-md-2">
                                                                    <button type="submit" name="aksi" value="reject" class="btn btn-danger btn-sm w-100 fw-bold" onclick="return confirm('Tolak Semua?')"><i class="fas fa-times"></i> Tolak</button>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                <?php endforeach; ?>

                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="tab-pane fade" id="history">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-hover small align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>Waktu Validasi</th>
                                        <th>Mata Kuliah</th>
                                        <th class="text-center">Jumlah Mhs</th>
                                        <th>Status</th>
                                        <th>Catatan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if(empty($listHistory)): ?>
                                        <tr><td colspan="5" class="text-center py-4">Belum ada riwayat validasi.</td></tr>
                                    <?php else: ?>
                                        <?php foreach($listHistory as $h): ?>
                                        <tr>
                                            <td><?= date('d M Y, H:i', strtotime($h['tgl_group'])) ?></td>
                                            <td>
                                                <span class="fw-bold"><?= htmlspecialchars($h['nama_mk']) ?></span>
                                                <br><small class="text-muted"><?= htmlspecialchars($h['kode_mk']) ?></small>
                                            </td>
                                            <td class="text-center">
                                                <span class="badge bg-secondary"><?= $h['jumlah_mhs'] ?> Orang</span>
                                            </td>
                                            <td>
                                                <?php 
                                                if($h['status']=='approved') echo '<span class="badge bg-success">Disetujui</span>';
                                                elseif($h['status']=='completed') echo '<span class="badge bg-primary">Selesai</span>';
                                                elseif($h['status']=='rejected') echo '<span class="badge bg-danger">Ditolak</span>';
                                                ?>
                                            </td>
                                            <td><?= htmlspecialchars($h['catatan_validasi'] ?? '-') ?></td>
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
        <div class="col-md-3"><?php include "sidebar.php"; ?></div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>