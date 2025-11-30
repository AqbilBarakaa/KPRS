<?php
require_once "../config/auth.php";
require_once "../config/database.php";

$auth = new Auth();
if (!$auth->isLoggedIn() || ($_SESSION['user']['role'] ?? '') !== 'mahasiswa') {
    header("Location: ../login.php"); exit;
}

$user = $_SESSION['user'];

$stmtProdi = $pdo->query("SELECT * FROM program_studi ORDER BY nama_prodi ASC");
$listProdi = $stmtProdi->fetchAll();

$selected_prodi_id = $_GET['prodi_id'] ?? null;

if (!$selected_prodi_id) {
    $userProdiName = $user['prodi'] ?? '';
    $stmtFind = $pdo->prepare("SELECT id FROM program_studi WHERE nama_prodi = ?");
    $stmtFind->execute([$userProdiName]);
    $selected_prodi_id = $stmtFind->fetchColumn();

    if (!$selected_prodi_id && count($listProdi) > 0) {
        $selected_prodi_id = $listProdi[0]['id'];
    }
}

$query = "
    SELECT mk.semester, mk.kode_mk, mk.nama_mk, mk.sks, mk.sifat, 
           k.id as kelas_id, k.nama_kelas, 
           d.nama as nama_dosen
    FROM mata_kuliah mk
    LEFT JOIN kelas k ON k.mata_kuliah_id = mk.id
    LEFT JOIN dosen d ON k.dosen_pengampu_id = d.id
    WHERE mk.prodi_id = ? 
    ORDER BY mk.semester ASC, mk.kode_mk ASC, k.nama_kelas ASC
";
$stmt = $pdo->prepare($query);
$stmt->execute([$selected_prodi_id]);
$allData = $stmt->fetchAll();

$dataPerSemester = [];
for ($i = 1; $i <= 8; $i++) {
    $dataPerSemester[$i] = [];
}
foreach ($allData as $row) {
    $sem = $row['semester'];
    if ($sem >= 1 && $sem <= 8) {
        $dataPerSemester[$sem][] = $row;
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Informasi Matakuliah | Portal Akademik</title>
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
                <div class="msg-header">Informasi Matakuliah Ditawarkan</div>
                
                <p class="small text-dark">
                    <strong>Keterangan :</strong><br>
                    Informasi Matakuliah Ditawarkan berisi seluruh matakuliah yang ditawarkan pada semester aktif. 
                    Pilih Program Studi dan Klik pada link <strong>Paket Semester</strong> untuk melihat daftar kelas.
                </p>

                <div class="semester-title">Semester Gasal 2025/2026</div>

                <div class="card bg-light border p-2 mb-3" style="max-width: 600px;">
                    <form class="d-flex align-items-center" method="GET" action="">
                        <label class="me-2 small fw-bold text-nowrap">Program Studi</label>
                        <select name="prodi_id" class="form-select form-select-sm me-2" onchange="this.form.submit()">
                            <?php foreach ($listProdi as $p): ?>
                                <option value="<?= $p['id'] ?>" <?= ($selected_prodi_id == $p['id']) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($p['nama_prodi']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <button type="submit" class="btn btn-sm btn-light border">lihat</button>
                    </form>
                </div>

                <div class="accordion" id="accordionMatkul">
                    <?php for($i=1; $i<=8; $i++): ?>
                        <div class="accordion-item" style="border:none; background:transparent; margin-bottom:5px;">
                            <h2 class="accordion-header" id="heading<?= $i ?>">
                                <button class="accordion-button custom-link collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse<?= $i ?>" aria-expanded="false" aria-controls="collapse<?= $i ?>">
                                    Paket Semester <?= $i ?>
                                </button>
                            </h2>
                            <div id="collapse<?= $i ?>" class="accordion-collapse collapse" aria-labelledby="heading<?= $i ?>" data-bs-parent="#accordionMatkul">
                                <div class="accordion-body pt-0">
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-sm table-hover mb-0">
                                            <thead>
                                                <tr class="table-header-blue">
                                                    <th width="5%">NO</th>
                                                    <th width="10%">KODE</th>
                                                    <th width="30%">MATAKULIAH</th>
                                                    <th width="30%">NAMA DOSEN</th>
                                                    <th width="10%">KELAS</th>
                                                    <th width="5%">W/P</th>
                                                    <th width="5%">SKS</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php $listMK = $dataPerSemester[$i]; ?>
                                                <?php if (empty($listMK)): ?>
                                                    <tr><td colspan="7" class="text-center text-muted fst-italic p-3">Belum ada data mata kuliah untuk semester ini.</td></tr>
                                                <?php else: ?>
                                                    <?php $no=1; foreach($listMK as $row): ?>
                                                    <tr class="table-row-bordered">
                                                        <td class="text-center"><?= $no++ ?></td>
                                                        <td><?= htmlspecialchars($row['kode_mk']) ?></td>
                                                        <td><?= htmlspecialchars($row['nama_mk']) ?></td>
                                                        <td><?= htmlspecialchars($row['nama_dosen'] ?? '-') ?></td>
                                                        <td class="text-center">
                                                            <?php if (!empty($row['nama_kelas'])): ?>
                                                                <a href="detail_kelas.php?id=<?= $row['kelas_id'] ?>" class="fw-bold text-decoration-none">
                                                                    <?= htmlspecialchars($row['nama_kelas']) ?>
                                                                </a>
                                                            <?php else: ?>
                                                                <span class="text-muted small">-</span>
                                                            <?php endif; ?>
                                                        </td>
                                                        <td class="text-center"><?= ($row['sifat'] == 'Wajib') ? 'W' : 'P' ?></td>
                                                        <td class="text-center"><?= $row['sks'] ?></td>
                                                    </tr>
                                                    <?php endforeach; ?>
                                                <?php endif; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endfor; ?>
                </div>

                <div class="mt-4">
                    <strong style="color: #003366;">Petunjuk:</strong>
                    <ul class="ms-2 small text-muted">
                        <li>Klik link nama kelas untuk melihat detil kelas.</li>
                    </ul>
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
</body>
</html>