<?php
require_once "../config/auth.php";
require_once "../config/database.php";

$auth = new Auth();
if (!$auth->isLoggedIn() || ($_SESSION['user']['role'] ?? '') !== 'tu') {
    header("Location: ../login.php"); exit;
}

$tu_id = $_SESSION['user']['id'];
$msg = ''; $err = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    try {
        $pdo->beginTransaction();

        if (isset($_POST['mk_target'])) {
            $mk_id = $_POST['mk_target'];
            
            $stmtGet = $pdo->prepare("SELECT id, mahasiswa_id, validator_id FROM pengajuan_tambah_kelas WHERE mata_kuliah_id = ? AND status = 'approved'");
            $stmtGet->execute([$mk_id]);
            $listPengajuan = $stmtGet->fetchAll();

            $stmtMkInfo = $pdo->prepare("SELECT nama_mk FROM mata_kuliah WHERE id = ?");
            $stmtMkInfo->execute([$mk_id]);
            $nama_mk = $stmtMkInfo->fetchColumn();

            if (empty($listPengajuan)) throw new Exception("Tidak ada pengajuan yang perlu diproses.");

            $count = 0;
            $validator_id = null;

            foreach ($listPengajuan as $p) {
                $pdo->prepare("UPDATE pengajuan_tambah_kelas SET status='completed' WHERE id=?")->execute([$p['id']]);
                
                if ($p['validator_id']) $validator_id = $p['validator_id'];

                if (function_exists('kirimNotifikasi')) {
                    kirimNotifikasi($pdo, $p['mahasiswa_id'], 'mahasiswa', 'Kelas Dibuka!', 
                        "Bagian TU telah memproses pengajuan tambah kelas $nama_mk. Silakan cek jadwal dan ambil KRS.", 
                        'success', 'krs.php', null, null); 
                }
                $count++;
            }

            if ($validator_id && function_exists('kirimNotifikasi')) {
                kirimNotifikasi($pdo, $validator_id, 'dosen', 'Pengajuan Diproses TU', 
                    "TU telah menyelesaikan proses $count pengajuan untuk mata kuliah $nama_mk.", 
                    'info', 'pengajuan.php', null, null);
            }

            if (function_exists('kirimNotifikasi')) {
                kirimNotifikasi($pdo, $validator_id, 'dosen', 'Pengajuan Diproses TU', "TU telah menyelesaikan proses $count pengajuan untuk mata kuliah $nama_mk.", 'info', 'pengajuan.php', $tu_id, 'tata_usaha');
            }

            $msg = "Berhasil memproses $count pengajuan.";
        }
        elseif (isset($_POST['selesai_id'])) {
            $id = $_POST['selesai_id'];
            $stmtGet = $pdo->prepare("SELECT p.mahasiswa_id, p.validator_id, mk.nama_mk FROM pengajuan_tambah_kelas p JOIN mata_kuliah mk ON p.mata_kuliah_id=mk.id WHERE p.id=?");
            $stmtGet->execute([$id]);
            $data = $stmtGet->fetch();
            
            if($data) {
                $pdo->prepare("UPDATE pengajuan_tambah_kelas SET status='completed' WHERE id=?")->execute([$id]);
                kirimNotifikasi($pdo, $data['mahasiswa_id'], 'mahasiswa', 'Kelas Dibuka!', "TU telah memproses pengajuan $data[nama_mk].", 'success', 'pengajuan.php', null, null);
                kirimNotifikasi($pdo, $tu_id, 'tata_usaha', 'Proses Selesai', "Memproses 1 pengajuan $data[nama_mk].", 'info', '#', $tu_id, 'tata_usaha');
                $msg = "Pengajuan ditandai selesai.";
            }
        }

        $pdo->commit();
    } catch (Exception $e) {
        $pdo->rollBack();
        $err = "Gagal memproses: " . $e->getMessage();
    }
}

$queryNew = "
    SELECT p.*, m.nim, m.nama as nama_mhs, mk.id as mk_id, mk.nama_mk, mk.kode_mk, mk.semester
    FROM pengajuan_tambah_kelas p
    JOIN mahasiswa m ON p.mahasiswa_id = m.id
    JOIN mata_kuliah mk ON p.mata_kuliah_id = mk.id
    WHERE p.status = 'approved'
    ORDER BY mk.semester ASC, mk.nama_mk ASC, p.tanggal_validasi ASC
";
$rawNew = $pdo->query($queryNew)->fetchAll();

$groupedNew = [];
foreach ($rawNew as $row) {
    $sem = $row['semester'];
    $mkKey = $row['mk_id'];
    
    $groupedNew[$sem][$mkKey]['info'] = [
        'kode' => $row['kode_mk'],
        'nama' => $row['nama_mk']
    ];
    $groupedNew[$sem][$mkKey]['list'][] = $row;
}

$queryHist = "
    SELECT 
        DATE_FORMAT(p.tanggal_validasi, '%Y-%m-%d %H:%i') as tgl_group,
        mk.semester, mk.nama_mk, mk.kode_mk,
        COUNT(*) as jumlah_mhs
    FROM pengajuan_tambah_kelas p
    JOIN mata_kuliah mk ON p.mata_kuliah_id = mk.id
    WHERE p.status = 'completed'
    GROUP BY tgl_group, mk.id
    ORDER BY tgl_group DESC, mk.semester ASC
    LIMIT 50
";
$listHist = $pdo->query($queryHist)->fetchAll();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8"><title>Verifikasi Pengajuan | TU</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/style.css">
    <style>
        .accordion-button:not(.collapsed) { background-color: #e7f1ff; color: #0c63e4; }
        .card-mk { border-left: 4px solid #0d6efd; }
    </style>
</head>
<body>
<?php include "../header.php"; ?>
<div class="container">
    <div class="row">
        <div class="col-md-9">
            <div class="content-box">
                <?php foreach ($groupedNew as $sem => $matkulGroup): ?>
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div class="msg-header mb-0">Daftar Permintaan Tambah Kelas (Dari Kaprodi)</div>
                        <span class="badge bg-danger"><?= count($matkulGroup) ?> Permintaan</span>
                    </div>
                <?php endforeach; ?> 
                               
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
                
                <ul class="nav nav-tabs mb-3" id="tuTab" role="tablist">
                    <li class="nav-item">
                        <button class="nav-link active" id="new-tab" data-bs-toggle="tab" data-bs-target="#new" type="button">
                            Permintaan Baru
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
                        
                        <?php if(empty($groupedNew)): ?>
                            <div class="alert alert-info text-center py-4">Tidak ada permintaan baru dari Kaprodi.</div>
                        <?php else: ?>
                            
                            <div class="accordion" id="accSemester">
                                <?php foreach ($groupedNew as $sem => $matkulGroup): ?>
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
                                                                <h6 class="fw-bold mb-0 text-primary">
                                                                    <?= $data['info']['kode'] ?> - <?= $data['info']['nama'] ?>
                                                                </h6>
                                                                <span class="badge bg-info text-dark"><?= count($data['list']) ?> Mahasiswa</span>
                                                            </div>

                                                            <div class="table-responsive mb-3">
                                                                <table class="table table-sm table-bordered mb-0 bg-white small">
                                                                    <thead class="table-light">
                                                                        <tr>
                                                                            <th>No</th>
                                                                            <th>Mahasiswa</th>
                                                                            <th>Catatan Kaprodi</th>
                                                                            <th>Tgl Validasi</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                        <?php $no=1; foreach ($data['list'] as $mhs): ?>
                                                                        <tr>
                                                                            <td class="text-center"><?= $no++ ?></td>
                                                                            <td>
                                                                                <b><?= htmlspecialchars($mhs['nama_mhs']) ?></b><br>
                                                                                <?= htmlspecialchars($mhs['nim']) ?>
                                                                            </td>
                                                                            <td><?= htmlspecialchars($mhs['catatan_validasi'] ?? '-') ?></td>
                                                                            <td><?= date('d/m H:i', strtotime($mhs['tanggal_validasi'])) ?></td>
                                                                        </tr>
                                                                        <?php endforeach; ?>
                                                                    </tbody>
                                                                </table>
                                                            </div>

                                                            <div class="bg-warning bg-opacity-10 p-2 border rounded d-flex align-items-center justify-content-between">
                                                                <div class="small text-muted fst-italic me-2">
                                                                    <i class="fas fa-info-circle"></i> Pastikan kelas sudah dibuka di menu <b>Data Kelas</b> sebelum menandai selesai.
                                                                </div>
                                                                <form method="POST">
                                                                    <input type="hidden" name="mk_target" value="<?= $mkId ?>">
                                                                    <button type="submit" class="btn btn-success btn-sm fw-bold" onclick="return confirm('Tandai SEMUA pengajuan matkul ini sebagai Selesai?')">
                                                                        Tandai Selesai Semua
                                                                    </button>
                                                                </form>
                                                            </div>

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

                    <div class="tab-pane fade" id="done">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped small align-middle table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>Tgl Proses</th>
                                        <th>Semester</th>
                                        <th>Mata Kuliah</th>
                                        <th class="text-center">Jumlah Mhs</th>
                                        <th class="text-center">Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if(empty($listHist)): ?>
                                        <tr><td colspan="5" class="text-center py-4">Belum ada riwayat selesai.</td></tr>
                                    <?php else: ?>
                                        <?php foreach($listHist as $h): ?>
                                        <tr>
                                            <td><?= date('d M Y', strtotime($h['tgl_group'])) ?></td>
                                            <td>Semester <?= $h['semester'] ?></td>
                                            <td>
                                                <b><?= htmlspecialchars($h['nama_mk']) ?></b><br>
                                                <small class="text-muted"><?= htmlspecialchars($h['kode_mk']) ?></small>
                                            </td>
                                            <td class="text-center">
                                                <span class="badge bg-secondary"><?= $h['jumlah_mhs'] ?> Orang</span>
                                            </td>
                                            <td class="text-center"><span class="badge bg-primary">Selesai</span></td>
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
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body></html>