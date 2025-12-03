<?php
require_once "../config/auth.php";
require_once "../config/database.php";

$auth = new Auth();
if (!$auth->isLoggedIn() || ($_SESSION['user']['role'] ?? '') !== 'mahasiswa') {
    header("Location: ../login.php"); exit;
}
$user_id = $_SESSION['user']['id'];

$periode = $pdo->query("SELECT * FROM periode_akademik WHERE id=1")->fetch();
$now = date('Y-m-d H:i:s'); 

$isKRS = ($now >= $periode['tanggal_mulai_krs'] && $now <= $periode['tanggal_selesai_krs']);
$isKPRS = ($now >= $periode['tanggal_mulai_kprs'] && $now <= $periode['tanggal_selesai_kprs']);

if (!$isKRS && !$isKPRS) {
    echo "<script>
        alert('Masa pengisian KRS/KPRS sudah ditutup.\\n\\nWaktu Sekarang: $now\\nBuka KRS: {$periode['tanggal_mulai_krs']}\\nTutup KRS: {$periode['tanggal_selesai_krs']}'); 
        window.location='krs.php';
    </script>"; 
    exit;
}

$semester_aktif_tipe = $periode['semester']; 
$target_semesters = ($semester_aktif_tipe == 'Ganjil') ? [1, 3, 5, 7] : [2, 4, 6, 8];

$stmtTaken = $pdo->prepare("
    SELECT k.mata_kuliah_id 
    FROM krs_awal ka 
    JOIN kelas k ON ka.kelas_id = k.id 
    WHERE ka.mahasiswa_id = ?
");
$stmtTaken->execute([$user_id]);
$takenMK = $stmtTaken->fetchAll(PDO::FETCH_COLUMN);

$placeholders = implode(',', array_fill(0, count($target_semesters), '?'));
$query = "
    SELECT k.id, k.nama_kelas, k.hari, k.jam_mulai, k.jam_selesai, k.kuota, k.terisi,
           mk.id as mk_id, mk.kode_mk, mk.nama_mk, mk.sks, mk.sifat, mk.semester,
           d.nama as nama_dosen
    FROM kelas k
    JOIN mata_kuliah mk ON k.mata_kuliah_id = mk.id
    LEFT JOIN dosen d ON k.dosen_pengampu_id = d.id
    WHERE mk.semester IN ($placeholders)
    ORDER BY mk.semester ASC, mk.kode_mk ASC, k.nama_kelas ASC
";

$stmt = $pdo->prepare($query);
$stmt->execute($target_semesters);
$dataKelas = $stmt->fetchAll();

$grouped = [];
foreach ($target_semesters as $s) { $grouped[$s] = []; }
foreach($dataKelas as $row) {
    $grouped[$row['semester']][] = $row;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Isi KRS | Portal Akademik</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/style.css">
    <style>
        .info-title { color: #003366; font-weight: bold; font-size: 1.2rem; margin-bottom: 10px; border-bottom: 1px solid #ddd; padding-bottom: 5px; }
        .accordion-button { background-color: transparent !important; color: blue !important; text-decoration: underline; padding: 8px 0; box-shadow: none !important; font-size: 1rem; justify-content: flex-start; border: none; }
        .accordion-button:not(.collapsed) { color: darkblue !important; font-weight: bold; }
        .accordion-button::after { display: none; }
        .table-header-blue { background-color: #e6f2ff; color: #003366; font-weight: bold; text-align: center; vertical-align: middle; font-size: 0.85rem; }
        .table-row-bordered td { border: 1px solid #ccc; vertical-align: middle; font-size: 0.9rem; }
    </style>
</head>
<body>

<?php include "../header.php"; ?>

<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="content-box mb-5">
                
                <div class="info-title mb-0">
                    Pilih Mata Kuliah (Semester <?= $semester_aktif_tipe ?>)
                </div>
                
                <div class="alert alert-info small py-2 mb-3 mt-3">
                    <i class="fas fa-info-circle me-1"></i> 
                    Silakan pilih kelas yang diinginkan. 
                    <br><b>Catatan:</b> Mata kuliah yang sudah Anda ambil tidak akan muncul di daftar ini.
                </div>

                <form method="POST" action="proses_tambah_krs.php">
                    
                    <div class="accordion" id="accordionKRS">
                        <?php foreach($target_semesters as $i): ?>
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="heading<?= $i ?>">
                                    <button class="accordion-button <?= $i==$target_semesters[0]?'':'collapsed' ?>" type="button" data-bs-toggle="collapse" data-bs-target="#collapse<?= $i ?>">
                                        Paket Semester <?= $i ?>
                                    </button>
                               </h2>
                                <div id="collapse<?= $i ?>" class="accordion-collapse collapse <?= $i==$target_semesters[0]?'show':'' ?>" data-bs-parent="#accordionKRS">
                                    <div class="accordion-body pt-0">
                                        
                                        <div class="table-responsive">
                                            <table class="table table-bordered table-sm table-hover mb-0">
                                                <thead>
                                                    <tr class="table-header-blue">
                                                        <th width="5%">PILIH</th>
                                                        <th width="10%">KODE</th>
                                                        <th width="30%">MATAKULIAH</th>
                                                        <th width="25%">NAMA DOSEN</th>
                                                        <th width="10%">KELAS</th>
                                                        <th width="5%">W/P</th>
                                                        <th width="5%">SKS</th>
                                                        <th width="15%">JADWAL</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php 
                                                    $listKelasRaw = $grouped[$i];
                                                    
                                                    $listKelas = array_filter($listKelasRaw, function($r) use ($takenMK) {
                                                        return !in_array($r['mk_id'], $takenMK);
                                                    });

                                                    if (empty($listKelas)): 
                                                    ?>
                                                        <tr><td colspan="8" class="text-center text-muted fst-italic p-3">
                                                            <?php if(empty($listKelasRaw)): ?>
                                                                Tidak ada kelas dibuka untuk semester ini.
                                                            <?php else: ?>
                                                                Semua mata kuliah di semester ini sudah Anda ambil.
                                                            <?php endif; ?>
                                                        </td></tr>
                                                    <?php else: ?>
                                                        <?php foreach($listKelas as $r): ?>
                                                        <tr class="table-row-bordered">
                                                            <td class="text-center">
                                                                <input type="checkbox" 
                                                                       name="kelas_id[]" 
                                                                       value="<?= $r['id'] ?>" 
                                                                       class="form-check-input chk-mk" 
                                                                       data-mk-id="<?= $r['mk_id'] ?>"
                                                                       style="cursor:pointer;">
                                                            </td>
                                                            <td class="text-center"><?= htmlspecialchars($r['kode_mk']) ?></td>
                                                            <td><?= htmlspecialchars($r['nama_mk']) ?></td>
                                                            <td><?= htmlspecialchars($r['nama_dosen'] ?? '-') ?></td>
                                                            <td class="text-center fw-bold text-primary"><?= htmlspecialchars($r['nama_kelas']) ?></td>
                                                            <td class="text-center"><?= ($r['sifat'] == 'Wajib') ? 'W' : 'P' ?></td>
                                                            <td class="text-center"><?= $r['sks'] ?></td>
                                                            <td class="text-center">
                                                                <?= $r['hari'] ?>, <?= substr($r['jam_mulai'],0,5) ?>
                                                            </td>
                                                        </tr>
                                                        <?php endforeach; ?>
                                                    <?php endif; ?>
                                                </tbody>
                                            </table>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    
                    <div class="mt-4 mb-2 d-flex gap-2">
                        <button type="submit" name="simpan_krs" class="btn btn-primary">
                            <i class="fas fa-plus-circle me-1"></i> Tambah Matakuliah
                        </button>
                        <a href="krs.php" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-1"></i> Kembali
                        </a>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
document.addEventListener("DOMContentLoaded", function() {
    const checkboxes = document.querySelectorAll('.chk-mk');

    checkboxes.forEach(chk => {
        chk.addEventListener('change', function() {
            if (this.checked) {
                const currentMkId = this.getAttribute('data-mk-id');

                checkboxes.forEach(other => {
                    if (other !== this && other.getAttribute('data-mk-id') === currentMkId) {
                        other.checked = false; 
                    }
                });
            }
        });
    });
});
</script>

</body>
</html>