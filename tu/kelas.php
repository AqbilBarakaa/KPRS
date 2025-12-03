<?php
require_once "../config/auth.php";
require_once "../config/database.php";
$auth = new Auth();
if (!$auth->isLoggedIn() || ($_SESSION['user']['role'] ?? '') !== 'tu') { header("Location: ../login.php"); exit; }

if (isset($_POST['hapus_id'])) {
    try { $pdo->prepare("DELETE FROM kelas WHERE id=?")->execute([$_POST['hapus_id']]); } catch (Exception $e) { $err="Gagal hapus."; }
}

$query = "SELECT k.*, mk.nama_mk, mk.kode_mk, d.nama as nama_dosen FROM kelas k JOIN mata_kuliah mk ON k.mata_kuliah_id = mk.id LEFT JOIN dosen d ON k.dosen_pengampu_id = d.id ORDER BY mk.nama_mk ASC";
$data = $pdo->query($query)->fetchAll();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8"><title>Data Kelas | TU</title>
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
                <div class="d-flex justify-content-between mb-3">
                    <div class="msg-header mb-0">Data Kelas</div>
                    <a href="kelas_form.php" class="btn btn-primary btn-sm">Buka Kelas</a>
                </div>
                <div class="table-responsive">
                    <table class="table table-bordered table-striped small align-middle">
                        <thead class="table-dark">
                            <tr><th>MK</th><th>Kelas</th><th>Ruang</th><th>Dosen</th><th>Jadwal</th><th>Kuota</th><th>Aksi</th></tr>
                        </thead>
                        <tbody>
                            <?php foreach($data as $r): ?>
                            <tr>
                                <td><?= $r['nama_mk'] ?> (<?= $r['kode_mk'] ?>)</td>
                                <td class="text-center fw-bold"><?= $r['nama_kelas'] ?></td>
                                <td class="text-center"><?= htmlspecialchars($r['ruangan'] ?? '-') ?></td> 
                                <td><?= htmlspecialchars($r['nama_dosen'] ?? '-') ?></td>
                                <td><?= $r['hari'] ?>, <?= substr($r['jam_mulai'],0,5) ?>-<?= substr($r['jam_selesai'],0,5) ?></td>
                                <td><?= $r['terisi'] ?> / <?= $r['kuota'] ?></td>
                                <td>
                                    <a href="kelas_form.php?id=<?= $r['id'] ?>" class="btn btn-warning btn-sm"><i class="fas fa-edit"></i></a>
                                    <form method="POST" style="display:inline" onsubmit="return confirm('Hapus?');">
                                        <input type="hidden" name="hapus_id" value="<?= $r['id'] ?>">
                                        <button class="btn btn-danger btn-sm"><i class="fas fa-trash"></i></button>
                                    </form>
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