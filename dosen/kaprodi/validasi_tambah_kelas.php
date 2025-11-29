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
        
        // Ambil data pengajuan untuk lock row
        $stmtGet = $pdo->prepare("SELECT * FROM pengajuan_tambah_kelas WHERE id = ? FOR UPDATE");
        $stmtGet->execute([$id_pengajuan]);
        $data = $stmtGet->fetch();

        if (!$data || $data['status'] !== 'pending') {
            throw new Exception("Data tidak valid atau sudah diproses.");
        }

        if ($aksi === 'approve') {
            // 1. Update Status Pengajuan
            $pdo->prepare("UPDATE pengajuan_tambah_kelas SET status='approved', validator_id=?, catatan_validasi=?, tanggal_validasi=NOW() WHERE id=?")
                ->execute([$kaprodi_id, $catatan, $id_pengajuan]);

            // 2. Logika Tambahan: Jika pengajuan ini spesifik ke kelas tertentu (misal minta masuk kelas A yang penuh)
            // maka update kuota. Tapi jika hanya request Matkul, Kaprodi mungkin harus membuka kelas baru manual di menu TU.
            // Di sini kita asumsikan flow sederhana: Approve = Selesai.
            
            // (Opsional) Jika ada kelas_id, update terisi
            if (!empty($data['kelas_id'])) {
                $pdo->prepare("UPDATE kelas SET terisi = terisi + 1 WHERE id = ?")->execute([$data['kelas_id']]);
                
                // Masukkan ke KRS
                $cekKrs = $pdo->prepare("SELECT id FROM krs_awal WHERE mahasiswa_id = ? AND kelas_id = ?");
                $cekKrs->execute([$data['mahasiswa_id'], $data['kelas_id']]);
                if ($cekKrs->rowCount() == 0) {
                    $pdo->prepare("INSERT INTO krs_awal (mahasiswa_id, kelas_id, status) VALUES (?, ?, 'terdaftar')")
                        ->execute([$data['mahasiswa_id'], $data['kelas_id']]);
                }
            }

            $msg = "Pengajuan disetujui.";
        } else {
            // Reject
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

// --- AMBIL DATA & GROUPING ---
// Kita ambil semua pending, diurutkan berdasarkan Nama MK agar mudah dikelompokkan
$query = "
    SELECT p.*, m.nim, m.nama as nama_mhs, 
           mk.kode_mk, mk.nama_mk, 
           k.nama_kelas, k.kuota, k.terisi 
    FROM pengajuan_tambah_kelas p 
    JOIN mahasiswa m ON p.mahasiswa_id = m.id 
    JOIN mata_kuliah mk ON p.mata_kuliah_id = mk.id 
    LEFT JOIN kelas k ON p.kelas_id = k.id 
    WHERE p.status = 'pending' 
    ORDER BY mk.nama_mk ASC, p.tanggal_pengajuan ASC
";
$rawData = $pdo->query($query)->fetchAll();

// Proses Grouping by Mata Kuliah
$groupedData = [];
foreach ($rawData as $row) {
    $key = $row['kode_mk'] . " - " . $row['nama_mk'];
    $groupedData[$key][] = $row;
}
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

<?php include "../header.php"; ?>

<div class="container">
    <div class="row">
        <div class="col-md-9">
            <div class="content-box">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div class="msg-header mb-0">Validasi Pengajuan</div>
                    <span class="badge bg-warning text-dark"><?= count($rawData) ?> Total Pending</span>
                </div>

                <?php if ($msg) echo "<div class='alert alert-success'>$msg</div>"; ?>
                <?php if ($err) echo "<div class='alert alert-danger'>$err</div>"; ?>

                <?php if (empty($groupedData)): ?>
                    <div class="alert alert-info text-center">Tidak ada pengajuan pending saat ini.</div>
                <?php else: ?>
                    
                    <?php foreach ($groupedData as $mkTitle => $listRequests): ?>
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
                                                <th width="25%">Mahasiswa</th>
                                                <th width="15%">Target Kelas</th>
                                                <th width="35%">Alasan</th>
                                                <th width="25%">Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($listRequests as $row): ?>
                                            <tr>
                                                <td>
                                                    <div class="fw-bold"><?= htmlspecialchars($row['nama_mhs']) ?></div>
                                                    <small class="text-muted"><?= htmlspecialchars($row['nim']) ?></small>
                                                </td>
                                                <td>
                                                    <?php if($row['nama_kelas']): ?>
                                                        <span class="badge bg-info text-dark"><?= htmlspecialchars($row['nama_kelas']) ?></span>
                                                        <br>
                                                        <small class="text-muted">
                                                            Isi: <?= $row['terisi'] ?>/<?= $row['kuota'] ?>
                                                        </small>
                                                    <?php else: ?>
                                                        <span class="badge bg-secondary">Belum ada kelas</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <small class="fst-italic">"<?= htmlspecialchars($row['alasan']) ?>"</small>
                                                    <div class="text-muted" style="font-size: 0.75rem;">
                                                        <?= date('d M Y, H:i', strtotime($row['tanggal_pengajuan'])) ?>
                                                    </div>
                                                </td>
                                                <td>
                                                    <form method="POST">
                                                        <input type="hidden" name="id_pengajuan" value="<?= $row['id'] ?>">
                                                        <input type="text" name="catatan" class="form-control form-control-sm mb-1" placeholder="Catatan (Opsional)">
                                                        <div class="d-flex gap-1">
                                                            <button type="submit" name="aksi" value="approve" class="btn btn-success btn-sm w-100" onclick="return confirm('Terima pengajuan ini?')">
                                                                <i class="fas fa-check"></i>
                                                            </button>
                                                            <button type="submit" name="aksi" value="reject" class="btn btn-danger btn-sm w-100" onclick="return confirm('Tolak pengajuan ini?')">
                                                                <i class="fas fa-times"></i>
                                                            </button>
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
        </div>

        <div class="col-md-3">
            <?php include "sidebar.php"; ?>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>