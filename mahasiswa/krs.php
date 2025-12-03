<?php
require_once "../config/auth.php";
require_once "../config/database.php";

$auth = new Auth();
if (!$auth->isLoggedIn() || ($_SESSION['user']['role'] ?? '') !== 'mahasiswa') {
    header("Location: ../login.php"); exit;
}

$user_id = $_SESSION['user']['id'];

$stmtMhs = $pdo->prepare("SELECT m.*, d.nama as nama_dpa, d.id as id_dosen_dpa, ps.nama_prodi FROM mahasiswa m LEFT JOIN dosen d ON m.dpa_id = d.id LEFT JOIN program_studi ps ON m.prodi = ps.nama_prodi WHERE m.id = ?");
$stmtMhs->execute([$user_id]);
$mhs = $stmtMhs->fetch();

$periode = $pdo->query("SELECT * FROM periode_akademik WHERE id=1")->fetch();
$now = date('Y-m-d H:i:s');

$isKRS = ($now >= $periode['tanggal_mulai_krs'] && $now <= $periode['tanggal_selesai_krs']);
$isKPRS = ($now >= $periode['tanggal_mulai_kprs'] && $now <= $periode['tanggal_selesai_kprs']);
$isPeriodeAktif = ($isKRS || $isKPRS);
$isPascaKPRS = ($now > $periode['tanggal_selesai_kprs']);

if ($isPascaKPRS) {
    $stmtCekPending = $pdo->prepare("SELECT COUNT(*) FROM krs_awal WHERE mahasiswa_id = ? AND status IN ('draft', 'diajukan')");
    $stmtCekPending->execute([$user_id]);
    $countPending = $stmtCekPending->fetchColumn();

    if ($countPending > 0) {
        try {
            $pdo->prepare("UPDATE krs_awal SET status = 'disetujui' WHERE mahasiswa_id = ? AND status IN ('draft', 'diajukan')")
                ->execute([$user_id]);
            
            if (function_exists('kirimNotifikasi')) {
                kirimNotifikasi($pdo, $user_id, 'mahasiswa', 'KRS Otomatis Disetujui', 
                    'Masa KPRS telah berakhir. Sistem memvalidasi sisa mata kuliah yang belum divalidasi.', 'success');
            }
        } catch (Exception $e) {
        }
    }
}

$max_sks = 24; 
if ($mhs['semester'] > 1) {
    if ($mhs['ips'] < 2.00) $max_sks = 18;
    elseif ($mhs['ips'] < 2.50) $max_sks = 21;
    elseif ($mhs['ips'] < 3.00) $max_sks = 22;
    elseif ($mhs['ips'] < 3.50) $max_sks = 23;
}

if (isset($_POST['mulai_revisi'])) {
    try {
        $pdo->prepare("UPDATE krs_awal SET status = 'draft' WHERE mahasiswa_id = ? AND status = 'disetujui'")->execute([$user_id]);

        if (function_exists('kirimNotifikasi')) {
            kirimNotifikasi($pdo, $user_id, 'mahasiswa', 'Status Revisi Aktif', 
                'Mode revisi telah diaktifkan. Silakan sesuaikan KRS dan AJUKAN VALIDASI ULANG.', 'info');
            
            if ($mhs['id_dosen_dpa']) {
                kirimNotifikasi($pdo, $mhs['id_dosen_dpa'], 'dosen', 'Mahasiswa Melakukan Revisi', 
                    "Mahasiswa {$mhs['nama']} ({$mhs['nim']}) telah membuka kembali KRS-nya untuk revisi.", 'info', 'perwalian.php', $user_id, 'mahasiswa');
            }
        }
        header("Location: krs.php"); exit;
    } catch (Exception $e) { echo "<script>alert('Gagal: " . $e->getMessage() . "');</script>"; }
}

if (isset($_POST['hapus_krs'])) {
    $krs_ids = $_POST['krs_id'] ?? [];
    if (!empty($krs_ids)) {
        try {
            $pdo->beginTransaction();
            foreach ($krs_ids as $kid) {
                $stmtGet = $pdo->prepare("SELECT kelas_id, status FROM krs_awal WHERE id = ? AND mahasiswa_id = ?");
                $stmtGet->execute([$kid, $user_id]);
                $dataKrs = $stmtGet->fetch();

                if ($dataKrs && ($dataKrs['status'] == 'draft' || $dataKrs['status'] == 'ditolak')) {
                    $pdo->prepare("DELETE FROM krs_awal WHERE id = ?")->execute([$kid]);
                    $pdo->prepare("UPDATE kelas SET terisi = terisi - 1 WHERE id = ?")->execute([$dataKrs['kelas_id']]);
                }
            }
            $pdo->commit();
            echo "<script>alert('Berhasil dihapus.'); window.location='krs.php';</script>";
        } catch (Exception $e) { $pdo->rollBack(); }
    }
}

if (isset($_POST['ajukan_validasi'])) {
    try {
        $pdo->prepare("UPDATE krs_awal SET status = 'diajukan' WHERE mahasiswa_id = ? AND status = 'draft'")->execute([$user_id]);
        if ($mhs['id_dosen_dpa']) {
            $tipePesan = $isKPRS ? "Revisi KPRS" : "Pengajuan KRS";
            if (function_exists('kirimNotifikasi')) {
                kirimNotifikasi($pdo, $mhs['id_dosen_dpa'], 'dosen', "$tipePesan Masuk", "Mahasiswa {$mhs['nama']} mengajukan validasi ($tipePesan).", 'warning', 'perwalian.php', $user_id, 'mahasiswa');
            }
        }
        echo "<script>alert('Berhasil diajukan ke Dosen Wali!'); window.location='krs.php';</script>";
    } catch (Exception $e) { echo "<script>alert('Gagal: " . $e->getMessage() . "');</script>"; }
}

$queryKRS = "
    SELECT ka.id as krs_id, ka.status, ka.tanggal_daftar as tgl_ambil,
           k.nama_kelas, k.hari, k.jam_mulai, k.jam_selesai, k.ruangan,
           mk.kode_mk, mk.nama_mk, mk.sks
    FROM krs_awal ka
    JOIN kelas k ON ka.kelas_id = k.id
    JOIN mata_kuliah mk ON k.mata_kuliah_id = mk.id
    WHERE ka.mahasiswa_id = ?
    ORDER BY mk.semester ASC, mk.nama_mk ASC
";
$stmt = $pdo->prepare($queryKRS);
$stmt->execute([$user_id]);
$krsData = $stmt->fetchAll();

$krsUtama = [];
$krsTambahan = [];
$totalSKS = 0;
$adaDraft = false; 
$semuaDisetujui = true;
$startKPRS = $periode['tanggal_mulai_kprs'];

foreach ($krsData as $row) {
    $totalSKS += $row['sks'];
    if ($row['status'] == 'draft') $adaDraft = true;
    if ($row['status'] != 'disetujui') $semuaDisetujui = false;

    if ($isKPRS && $row['tgl_ambil'] >= $startKPRS) {
        $krsTambahan[] = $row;
    } else {
        $krsUtama[] = $row;
    }
}

if (empty($krsData)) $semuaDisetujui = false;

$sudahPernahRevisi = false;
if ($isKPRS) {
    $stmtCekRev = $pdo->prepare("SELECT id FROM notifikasi WHERE user_id = ? AND judul = 'Status Revisi Aktif' AND created_at >= ?");
    $stmtCekRev->execute([$user_id, $startKPRS]);
    if ($stmtCekRev->rowCount() > 0) {
        $sudahPernahRevisi = true;
    }
}

$modeEdit = $isPeriodeAktif;

if ($semuaDisetujui && !empty($krsData)) {
    $modeEdit = false;
}

if ($isPascaKPRS) {
    $modeEdit = false;
    $semuaDisetujui = true; 
}

$bisaHapus = $modeEdit;
$bisaTambah = $modeEdit;
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8"><title>KRS | Portal Akademik</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/style.css">
    <style>
        .info-title { color: #003366; font-weight: bold; font-size: 1.2rem; margin-bottom: 15px; border-bottom: 1px solid #ddd; padding-bottom: 5px; }
        .table-krs-header { background-color: #003366; color: white; text-align: center; }
        .chk-del { cursor: pointer; transform: scale(1.2); }
        .info-box-krs { background-color: #fdfbf0; border: 1px solid #ddd; padding: 15px; margin-bottom: 20px; border-radius: 4px; }
        .info-table { width: 100%; font-size: 0.9rem; }
        .info-table td { padding: 3px 0; vertical-align: top; }
        .label-col { font-weight: bold; width: 130px; color: #003366; }
        .sep-col { width: 15px; text-align: center; font-weight: bold; }
        .val-col { color: #333; font-weight: 500; }
        .sub-header { font-weight: bold; color: #003366; margin-top: 20px; margin-bottom: 5px; border-bottom: 2px solid #eee; padding-bottom: 5px; }
    </style>
</head>
<body>

<?php include "../header.php"; ?>

<div class="container">
    <div class="row">
        <div class="col-md-9">
            <div class="content-box">
                <div class="d-flex justify-content-between align-items-center mb230411100073-2">
                    <div class="msg-header mb-0 border-0">Kartu Rencana Studi</div>
                    <?php if($isKRS): ?> <span class="badge bg-success">Masa KRS</span>
                    <?php elseif($isKPRS): ?> <span class="badge bg-warning text-dark">Masa KPRS</span>
                    <?php elseif($isPascaKPRS): ?> <span class="badge bg-secondary"></span>
                    <?php else: ?> <span class="badge bg-secondary">Belum Dibuka</span>
                    <?php endif; ?>
                </div>
                <div style="border-bottom: 1px solid #ddd; margin-bottom: 15px;"></div>

                <div class="info-box-krs">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="info-table">
                                <tr><td class="label-col">NIM</td><td class="sep-col">:</td><td class="val-col"><?= htmlspecialchars($mhs['nim']) ?></td></tr>
                                <tr><td class="label-col">Nama</td><td class="sep-col">:</td><td class="val-col"><?= htmlspecialchars(strtoupper($mhs['nama'])) ?></td></tr>
                                <tr><td class="label-col">Dosen Wali</td><td class="sep-col">:</td><td class="val-col"><?= htmlspecialchars($mhs['nama_dpa'] ?? '-') ?></td></tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="info-table">
                                <tr><td class="label-col">Tahun Ajaran</td><td class="sep-col">:</td><td class="val-col"><?= $periode['tahun_akademik'] ?> <?= $periode['semester'] ?></td></tr>
                                <tr><td class="label-col">Semester</td><td class="sep-col">:</td><td class="val-col"><?= htmlspecialchars($mhs['semester']) ?></td></tr>
                                <tr><td class="label-col">Jatah SKS</td><td class="sep-col">:</td><td class="val-col"><?= $max_sks ?></td></tr>
                            </table>
                        </div>
                    </div>
                </div>

                <?php if($isPascaKPRS && $semuaDisetujui && !empty($krsData)): ?>
                    <div class="alert alert-success alert-dismissible fade show small py-2 mb-3" role="alert">
                        <i class="fas fa-check-circle me-1"></i> KRS Anda telah divalidasi.
                        <button type="button" class="btn-close btn-sm py-2" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>

                <form method="POST" id="formKRS">
                    <div class="mb-3 d-flex gap-2 flex-wrap">
                        
                        <?php if ($semuaDisetujui && !empty($krsData)): ?>
                            <a href="cetak_krs.php" target="_blank" class="btn btn-success btn-sm">
                                <i class="fas fa-print me-1"></i> Cetak KRS
                            </a>
                            
                            <?php if ($isKPRS && !$sudahPernahRevisi): ?>
                                <button type="submit" name="mulai_revisi" class="btn btn-info btn-sm text-white fw-bold" onclick="return confirm('Mulai Revisi? Status matkul akan kembali ke Draft.')">
                                    <i class="fas fa-edit me-1"></i> Revisi KRS
                                </button>
                            <?php endif; ?>

                        <?php else: ?>
                            
                            <?php if ($bisaTambah): ?>
                                <a href="tambah_krs.php" class="btn btn-primary btn-sm"><i class="fas fa-plus-circle me-1"></i> <?= $isKPRS ? 'Tambah Matkul' : 'Tambah Matakuliah' ?></a>
                            <?php endif; ?>

                            <?php if ($adaDraft): ?>
                                <button type="submit" name="ajukan_validasi" class="btn btn-warning btn-sm fw-bold" onclick="return confirm('Ajukan Validasi ke Dosen Wali?')">
                                    <i class="fas fa-paper-plane me-1"></i> Ajukan Validasi
                                </button>
                            <?php endif; ?>

                            <button type="submit" name="hapus_krs" id="btnHapus" class="btn btn-danger btn-sm d-none" onclick="return confirm('Hapus terpilih?')">
                                <i class="fas fa-trash me-1"></i> Hapus Terpilih
                            </button>

                        <?php endif; ?>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-bordered table-sm small align-middle table-hover">
                            <thead class="table-krs-header">
                                <tr>
                                    <?php if($bisaHapus): ?><th width="5%">Hapus</th><?php endif; ?>
                                    <th>No</th><th>Kode</th><th>Matakuliah</th><th>SKS</th><th>Kelas</th><th>Jadwal</th><th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if(empty($krsUtama) && empty($krsTambahan)): ?>
                                    <tr><td colspan="<?= $bisaHapus ? 8 : 7 ?>" class="text-center py-4 text-muted">Belum mengambil mata kuliah.</td></tr>
                                <?php else: ?>
                                    <?php $no=1; foreach($krsUtama as $r): ?>
                                    <tr>
                                        <?php if($bisaHapus): ?>
                                            <td class="text-center">
                                                <?php if(($r['status'] != 'disetujui' || $isKPRS) && !$isPascaKPRS): ?>
                                                    <input type="checkbox" name="krs_id[]" value="<?= $r['krs_id'] ?>" class="form-check-input chk-del">
                                                <?php else: ?>
                                                    <i class="fas fa-lock text-secondary"></i>
                                                <?php endif; ?>
                                            </td>
                                        <?php endif; ?>
                                        <td class="text-center"><?= $no++ ?></td>
                                        <td class="text-center"><?= $r['kode_mk'] ?></td>
                                        <td><?= $r['nama_mk'] ?></td>
                                        <td class="text-center"><?= $r['sks'] ?></td>
                                        <td class="text-center fw-bold"><?= $r['nama_kelas'] ?></td>
                                        <td class="text-center"><?= $r['hari'] ?>, <?= substr($r['jam_mulai'],0,5) ?></td>
                                        <td class="text-center">
                                            <?php 
                                                if($r['status']=='disetujui') echo '<span class="badge bg-success">Disetujui</span>';
                                                elseif($r['status']=='ditolak') echo '<span class="badge bg-danger">Ditolak</span>';
                                                else echo '<span class="badge bg-warning text-dark">Draft</span>';
                                            ?>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                                
                                <?php if(empty($krsTambahan) && !empty($krsUtama)): ?>
                                <tr class="table-secondary fw-bold">
                                    <td colspan="<?= $bisaHapus ? 4 : 3 ?>" class="text-end pe-3">Total SKS</td>
                                    <td class="text-center"><?= $totalSKS ?></td>
                                    <td colspan="3"></td>
                                </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>

                    <?php if(!empty($krsTambahan)): ?>
                        <div class="sub-header mt-4">Matakuliah Ditambah (KPRS)</div>
                        <div class="table-responsive">
                            <table class="table table-bordered table-sm small align-middle table-hover">
                                <thead class="table-secondary text-center">
                                    <tr>
                                        <?php if($bisaHapus): ?><th width="5%">Hapus</th><?php endif; ?>
                                        <th>No</th><th>Kode</th><th>Matakuliah</th><th>SKS</th><th>Kelas</th><th>Jadwal</th><th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $noKprs=1; foreach($krsTambahan as $r): ?>
                                    <tr class="table-warning">
                                        <?php if($bisaHapus): ?>
                                            <td class="text-center">
                                                <input type="checkbox" name="krs_id[]" value="<?= $r['krs_id'] ?>" class="form-check-input chk-del">
                                            </td>
                                        <?php endif; ?>
                                        <td class="text-center"><?= $noKprs++ ?></td>
                                        <td class="text-center"><?= $r['kode_mk'] ?></td>
                                        <td><?= $r['nama_mk'] ?></td>
                                        <td class="text-center"><?= $r['sks'] ?></td>
                                        <td class="text-center fw-bold"><?= $r['nama_kelas'] ?></td>
                                        <td class="text-center"><?= $r['hari'] ?>, <?= substr($r['jam_mulai'],0,5) ?></td>
                                        <td class="text-center">
                                            <?php 
                                                if($r['status']=='disetujui') echo '<span class="badge bg-success">Disetujui</span>';
                                                elseif($r['status']=='ditolak') echo '<span class="badge bg-danger">Ditolak</span>';
                                                else echo '<span class="badge bg-warning text-dark">Revisi</span>';
                                            ?>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                    <tr class="table-secondary fw-bold">
                                        <td colspan="<?= $bisaHapus ? 4 : 3 ?>" class="text-end pe-3">Total SKS</td>
                                        <td class="text-center"><?= $totalSKS ?></td>
                                        <td colspan="3"></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </form>

                <?php if($bisaHapus): ?>
                    <div class="text-muted small fst-italic mt-2">
                        * Centang kotak di kolom "Hapus" lalu klik tombol "Hapus Terpilih".
                    </div>
                <?php endif; ?>

            </div>
        </div>
        <div class="col-md-3"><?php include "sidebar.php"; ?></div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.addEventListener("DOMContentLoaded", function() {
    const checkboxes = document.querySelectorAll('.chk-del');
    const btnHapus = document.getElementById('btnHapus');
    if(checkboxes.length > 0 && btnHapus) {
        checkboxes.forEach(chk => {
            chk.addEventListener('change', function() {
                const anyChecked = Array.from(checkboxes).some(c => c.checked);
                if(anyChecked) btnHapus.classList.remove('d-none');
                else btnHapus.classList.add('d-none');
            });
        });
    }
});
</script>
</body></html>