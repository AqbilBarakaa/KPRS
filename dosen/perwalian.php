<?php
require_once "../config/auth.php";
require_once "../config/database.php";

$auth = new Auth();
if (!$auth->isLoggedIn() || !in_array($_SESSION['user']['role'], ['dosen', 'dosen_dpa', 'dosen_kaprodi'])) {
    header("Location: ../login.php"); exit;
}

$dosen_id = $_SESSION['user']['id'];

$query = "
    SELECT m.*, ps.nama_prodi,
           (SELECT COUNT(*) FROM krs_awal k WHERE k.mahasiswa_id = m.id AND k.status != 'draft') as total_mk,
           (SELECT COUNT(*) FROM krs_awal k WHERE k.mahasiswa_id = m.id AND k.status = 'diajukan') as pending_mk
    FROM mahasiswa m
    LEFT JOIN program_studi ps ON m.prodi = ps.nama_prodi 
    WHERE m.dpa_id = ?
    ORDER BY m.nim ASC
";
$stmt = $pdo->prepare($query);
$stmt->execute([$dosen_id]);
$listMhs = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Mahasiswa Perwalian | Portal Dosen</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/style.css">
    <style>
        .table-foto { width: 40px; height: 50px; object-fit: cover; border-radius: 4px; border: 1px solid #ddd; }
    </style>
</head>
<body>

<?php include "../header.php"; ?>

<div class="container">
    <div class="row">
        <div class="col-md-9">
            <div class="content-box">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div class="msg-header mb-0"><i class="fas fa-users me-2"></i> Mahasiswa Perwalian</div>
                    <span class="badge bg-primary"><?= count($listMhs) ?> Mahasiswa</span>
                </div>

                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover small align-middle">
                        <thead class="table-dark">
                            <tr class="text-center">
                                <th>No</th>
                                <th>Foto</th>
                                <th>NIM</th>
                                <th>Nama Mahasiswa</th>
                                <th>L/P</th>
                                <th>Status KRS</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($listMhs)): ?>
                                <tr><td colspan="7" class="text-center p-4">Anda belum memiliki mahasiswa bimbingan.</td></tr>
                            <?php endif; ?>

                            <?php $no=1; foreach($listMhs as $row): ?>
                            <?php 
                                $fotoUrl = (!empty($row['foto']) && file_exists("../assets/img/uploads/".$row['foto'])) 
                                    ? "../assets/img/uploads/".$row['foto'] 
                                    : "https://via.placeholder.com/40x50.png?text=FOTO";
                            ?>
                            <tr>
                                <td class="text-center"><?= $no++ ?></td>
                                <td class="text-center"><img src="<?= $fotoUrl ?>" class="table-foto"></td>
                                <td class="text-center"><?= htmlspecialchars($row['nim']) ?></td>
                                <td><?= htmlspecialchars($row['nama']) ?></td>
                                <td class="text-center"><?= htmlspecialchars($row['jenis_kelamin'] ?? '-') ?></td>
                                <td class="text-center">
                                    <?php if($row['pending_mk'] > 0): ?>
                                        <span class="badge bg-warning text-dark">Butuh Validasi</span>
                                    <?php elseif($row['total_mk'] > 0): ?>
                                        <span class="badge bg-success">Sudah KRS</span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary">Belum Isi</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-center">
                                    <a href="validasi_krs.php?mhs_id=<?= $row['id'] ?>" class="btn btn-primary btn-sm">
                                        <i class="fas fa-search me-1"></i> Cek KRS
                                    </a>
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
</body>
</html>