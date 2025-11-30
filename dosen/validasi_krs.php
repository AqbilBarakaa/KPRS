<?php
// dosen/validasi_krs.php
require_once "../config/auth.php";
require_once "../config/database.php";

$auth = new Auth();
if (!$auth->isLoggedIn() || !in_array($_SESSION['user']['role'], ['dosen', 'dosen_dpa', 'dosen_kaprodi'])) {
    header("Location: ../login.php"); exit;
}

$mhs_id = $_GET['mhs_id'] ?? 0;
$msg = '';

// --- PROSES VALIDASI (APPROVE ALL) ---
if (isset($_POST['validasi_all'])) {
    try {
        // Update semua status 'terdaftar' menjadi 'disetujui' untuk mahasiswa ini
        $stmtUpdate = $pdo->prepare("UPDATE krs_awal SET status = 'disetujui' WHERE mahasiswa_id = ? AND status = 'terdaftar'");
        $stmtUpdate->execute([$mhs_id]);
        $msg = "KRS Mahasiswa berhasil divalidasi/disetujui.";
    } catch (Exception $e) {
        $msg = "Gagal memvalidasi: " . $e->getMessage();
    }
}

// --- AMBIL DATA MAHASISWA ---
$stmtMhs = $pdo->prepare("SELECT * FROM mahasiswa WHERE id = ?");
$stmtMhs->execute([$mhs_id]);
$mhs = $stmtMhs->fetch();

if (!$mhs) { die("Mahasiswa tidak ditemukan."); }

// --- AMBIL DATA KRS ---
$queryKRS = "
    SELECT krs.*, k.nama_kelas, k.hari, k.jam_mulai, k.jam_selesai, k.ruangan,
           mk.kode_mk, mk.nama_mk, mk.sks, mk.semester
    FROM krs_awal krs
    JOIN kelas k ON krs.kelas_id = k.id
    JOIN mata_kuliah mk ON k.mata_kuliah_id = mk.id
    WHERE krs.mahasiswa_id = ?
    ORDER BY mk.semester ASC, mk.nama_mk ASC
";
$stmtKRS = $pdo->prepare($queryKRS);
$stmtKRS->execute([$mhs_id]);
$krsData = $stmtKRS->fetchAll();

// Hitung Total SKS
$totalSKS = 0;
$belumValid = 0;
foreach ($krsData as $k) {
    $totalSKS += $k['sks'];
    if ($k['status'] == 'terdaftar') $belumValid++;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Validasi KRS | Portal Dosen</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>

<?php include "header.php"; ?>

<div class="container">
    <div class="row">
        <div class="col-md-9">
            <div class="content-box">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div class="msg-header mb-0">Validasi Kartu Rencana Studi (KRS)</div>
                    <a href="perwalian.php" class="btn btn-secondary btn-sm"><i class="fas fa-arrow-left"></i> Kembali</a>
                </div>

                <?php if ($msg): ?>
                    <div class="alert alert-success alert-dismissible fade show">
                        <i class="fas fa-check-circle me-2"></i> <?= $msg ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <div class="card bg-light mb-4">
                    <div class="card-body py-2">
                        <div class="row">
                            <div class="col-md-6">
                                <strong>Nama:</strong> <?= htmlspecialchars($mhs['nama']) ?><br>
                                <strong>NIM:</strong> <?= htmlspecialchars($mhs['nim']) ?>
                            </div>
                            <div class="col-md-6 text-md-end">
                                <strong>Prodi:</strong> <?= htmlspecialchars($mhs['prodi']) ?><br>
                                <strong>Semester:</strong> <?= htmlspecialchars($mhs['semester']) ?>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover small align-middle">
                        <thead class="table-dark">
                            <tr>
                                <th>Kode</th>
                                <th>Mata Kuliah</th>
                                <th class="text-center">SKS</th>
                                <th>Kelas</th>
                                <th>Jadwal</th>
                                <th class="text-center">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($krsData)): ?>
                                <tr><td colspan="6" class="text-center p-3">Mahasiswa ini belum mengambil mata kuliah (KRS Kosong).</td></tr>
                            <?php else: ?>
                                <?php foreach ($krsData as $row): ?>
                                <tr>
                                    <td><?= htmlspecialchars($row['kode_mk']) ?></td>
                                    <td><?= htmlspecialchars($row['nama_mk']) ?></td>
                                    <td class="text-center"><?= $row['sks'] ?></td>
                                    <td class="fw-bold text-center"><?= htmlspecialchars($row['nama_kelas']) ?></td>
                                    <td>
                                        <?= $row['hari'] ?>, <?= substr($row['jam_mulai'],0,5) ?>-<?= substr($row['jam_selesai'],0,5) ?><br>
                                        <small class="text-muted">R. <?= $row['ruangan'] ?? '-' ?></small>
                                    </td>
                                    <td class="text-center">
                                        <?php if ($row['status'] == 'disetujui'): ?>
                                            <span class="badge bg-success">Disetujui</span>
                                        <?php elseif ($row['status'] == 'terdaftar'): ?>
                                            <span class="badge bg-warning text-dark">Belum Divalidasi</span>
                                        <?php else: ?>
                                            <span class="badge bg-secondary"><?= ucfirst($row['status']) ?></span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                                <tr class="table-light fw-bold">
                                    <td colspan="2" class="text-end">Total SKS Yang Diambil:</td>
                                    <td class="text-center"><?= $totalSKS ?></td>
                                    <td colspan="3"></td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                <?php if (!empty($krsData) && $belumValid > 0): ?>
                    <div class="d-grid gap-2 mt-3">
                        <form method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menyetujui semua mata kuliah ini?');">
                            <input type="hidden" name="validasi_all" value="1">
                            <button type="submit" class="btn btn-success btn-lg w-100">
                                <i class="fas fa-check-double me-2"></i> Setujui Semua KRS
                            </button>
                        </form>
                    </div>
                <?php elseif ($belumValid == 0 && !empty($krsData)): ?>
                    <div class="alert alert-success mt-3 text-center">
                        <i class="fas fa-check-circle me-2"></i> Semua mata kuliah mahasiswa ini sudah divalidasi.
                    </div>
                <?php endif; ?>

            </div>
        </div>

        <div class="col-md-3">
            <?php include "sidebar.php"; ?>
        </div>
    </div>
</div>
<?php include "../footer.php"; ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>