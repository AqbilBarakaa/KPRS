<?php
require_once "../config/auth.php";
require_once "../config/database.php";

$auth = new Auth();
if (!$auth->isLoggedIn() || !in_array($_SESSION['user']['role'], ['dosen', 'dosen_dpa', 'dosen_kaprodi'])) {
    header("Location: ../login.php"); exit;
}

$dosen_id = $_SESSION['user']['id'];
$mhs_id = $_GET['mhs_id'] ?? 0;
$msg = ''; $err = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $aksi = $_POST['aksi'];
    $catatan = trim($_POST['catatan'] ?? '');

    try {
        if ($aksi == 'approve') {
            $stmtUpd = $pdo->prepare("UPDATE krs_awal SET status = 'disetujui' WHERE mahasiswa_id = ? AND status = 'diajukan'");
            $stmtUpd->execute([$mhs_id]);
            
            kirimNotifikasi($pdo, $mhs_id, 'mahasiswa', 'KRS Disetujui', 
                'KRS Anda telah disetujui oleh Dosen Wali. Anda dapat mencetak Kartu Studi sekarang.', 
                'success', 'validasi_krs.php', $dosen_id, 'dosen');

            $msg = "KRS Mahasiswa berhasil disetujui.";
        } elseif ($aksi == 'reject') {
            $stmtUpd = $pdo->prepare("UPDATE krs_awal SET status = 'ditolak' WHERE mahasiswa_id = ? AND status = 'diajukan'");
            $stmtUpd->execute([$mhs_id]);

            kirimNotifikasi($pdo, $mhs_id, 'mahasiswa', 'KRS Ditolak/Revisi', 
                'Terdapat perbaikan pada KRS Anda. Catatan Dosen: ' . $catatan, 
                'error', 'validasi_krs.php', $dosen_id, 'dosen');

            $msg = "KRS Mahasiswa dikembalikan (ditolak) untuk perbaikan.";
        }
    } catch (Exception $e) {
        $err = "Terjadi kesalahan: " . $e->getMessage();
    }
}

$stmtMhs = $pdo->prepare("SELECT * FROM mahasiswa WHERE id = ? AND dpa_id = ?");
$stmtMhs->execute([$mhs_id, $dosen_id]);
$mhs = $stmtMhs->fetch();

if (!$mhs) {
    echo "<script>alert('Mahasiswa tidak ditemukan atau bukan bimbingan Anda.'); window.location='perwalian.php';</script>";
    exit;
}

$queryKRS = "
    SELECT ka.*, 
           k.nama_kelas, k.hari, k.jam_mulai, k.jam_selesai, k.ruangan,
           mk.kode_mk, mk.nama_mk, mk.sks
    FROM krs_awal ka
    JOIN kelas k ON ka.kelas_id = k.id
    JOIN mata_kuliah mk ON k.mata_kuliah_id = mk.id
    WHERE ka.mahasiswa_id = ?
    ORDER BY mk.semester ASC
";
$stmt = $pdo->prepare($queryKRS);
$stmt->execute([$mhs_id]);
$krsData = $stmt->fetchAll();

$totalSKS = 0;
$adaPengajuan = false;
foreach($krsData as $row) {
    $totalSKS += $row['sks'];
    if ($row['status'] == 'diajukan') $adaPengajuan = true;
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

<?php include "../header.php"; ?>

<div class="container">
    <div class="row">
        <div class="col-md-9">
            <div class="content-box">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div class="msg-header mb-0"><i class="fas fa-check-double me-2"></i> Validasi KRS Mahasiswa</div>
                    <a href="perwalian.php" class="btn btn-secondary btn-sm">Kembali</a>
                </div>

                <?php if ($msg) echo "<div class='alert alert-success alert-dismissible fade show'>$msg <button class='btn-close' data-bs-dismiss='alert'></button></div>"; ?>
                <?php if ($err) echo "<div class='alert alert-danger alert-dismissible fade show'>$err <button class='btn-close' data-bs-dismiss='alert'></button></div>"; ?>

                <div class="card mb-4 bg-light border-0 p-2">
                    <div class="row">
                        <div class="col-md-6">
                            <strong>Nama:</strong> <?= htmlspecialchars($mhs['nama']) ?><br>
                            <strong>NIM:</strong> <?= htmlspecialchars($mhs['nim']) ?>
                        </div>
                        <div class="col-md-6 text-end">
                            <strong>Prodi:</strong> <?= htmlspecialchars($mhs['prodi']) ?><br>
                            <strong>Semester:</strong> <?= htmlspecialchars($mhs['semester']) ?>
                        </div>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-bordered table-sm small align-middle">
                        <thead class="table-dark text-center">
                            <tr>
                                <th>No</th>
                                <th>Kode</th>
                                <th>Matakuliah</th>
                                <th>Kelas</th>
                                <th>SKS</th>
                                <th>Jadwal</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(empty($krsData)): ?>
                                <tr><td colspan="7" class="text-center py-4">Mahasiswa belum mengambil mata kuliah.</td></tr>
                            <?php else: $no=1; foreach($krsData as $r): ?>
                            <tr class="<?= ($r['status']=='diajukan') ? 'table-warning' : '' ?>">
                                <td class="text-center"><?= $no++ ?></td>
                                <td class="text-center"><?= $r['kode_mk'] ?></td>
                                <td><?= $r['nama_mk'] ?></td>
                                <td class="text-center fw-bold"><?= $r['nama_kelas'] ?></td>
                                <td class="text-center"><?= $r['sks'] ?></td>
                                <td class="text-center"><?= $r['hari'] ?>, <?= substr($r['jam_mulai'],0,5) ?></td>
                                <td class="text-center">
                                    <?php 
                                    if($r['status']=='draft') echo '<span class="badge bg-secondary">Draft</span>';
                                    elseif($r['status']=='diajukan') echo '<span class="badge bg-warning text-dark">Menunggu</span>';
                                    elseif($r['status']=='disetujui') echo '<span class="badge bg-success">Disetujui</span>';
                                    elseif($r['status']=='ditolak') echo '<span class="badge bg-danger">Ditolak</span>';
                                    ?>
                                </td>
                            </tr>
                            <?php endforeach; endif; ?>
                            <tr class="table-secondary fw-bold">
                                <td colspan="4" class="text-end pe-3">Total SKS</td>
                                <td class="text-center"><?= $totalSKS ?></td>
                                <td colspan="2"></td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <?php if ($adaPengajuan): ?>
                <div class="card mt-4 shadow-sm border-warning">
                    <div class="card-header bg-warning text-dark fw-bold">
                        <i class="fas fa-exclamation-circle me-2"></i> Terdapat Mata Kuliah yang perlu divalidasi
                    </div>
                    <div class="card-body">
                        <form method="POST">
                            <div class="mb-3">
                                <label class="form-label">Catatan untuk Mahasiswa (Opsional jika tolak):</label>
                                <textarea name="catatan" class="form-control" rows="2" placeholder="Contoh: SKS berlebih, jadwal bentrok, dll..."></textarea>
                            </div>
                            <div class="d-flex gap-2">
                                <button type="submit" name="aksi" value="approve" class="btn btn-success flex-grow-1" onclick="return confirm('Setujui semua pengajuan ini?')">
                                    <i class="fas fa-check-circle me-1"></i> Setujui Semua
                                </button>
                                <button type="submit" name="aksi" value="reject" class="btn btn-danger flex-grow-1" onclick="return confirm('Tolak pengajuan ini?')">
                                    <i class="fas fa-times-circle me-1"></i> Tolak / Minta Revisi
                                </button>
                            </div>
                        </form>
                    </div>
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