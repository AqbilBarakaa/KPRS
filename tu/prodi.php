<?php
require_once "../config/auth.php";
require_once "../config/database.php";

$auth = new Auth();
if (!$auth->isLoggedIn() || ($_SESSION['user']['role'] ?? '') !== 'tu') {
    header("Location: ../login.php"); exit;
}

$msg = ''; $err = '';

if (isset($_POST['hapus_id'])) {
    try {
        $pdo->prepare("DELETE FROM program_studi WHERE id = ?")->execute([$_POST['hapus_id']]);
        $msg = "Program Studi berhasil dihapus.";
    } catch (PDOException $e) {
        $err = "Gagal menghapus: Data ini sedang digunakan di tabel Dosen/Mata Kuliah.";
    }
}

$query = "SELECT ps.*, d.nama as nama_kaprodi 
          FROM program_studi ps 
          LEFT JOIN dosen d ON ps.kaprodi_id = d.id 
          ORDER BY ps.nama_prodi ASC";
$dataProdi = $pdo->query($query)->fetchAll();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8"><title>Program Studi | TU</title>
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
                    <div class="msg-header mb-0"><i class="fas fa-university me-2"></i> Data Program Studi</div>
                    <a href="prodi_form.php" class="btn btn-primary btn-sm"><i class="fas fa-plus"></i> Tambah Prodi</a>
                </div>

                <?php if ($msg) echo "<div class='alert alert-success alert-dismissible fade show'>$msg <button class='btn-close' data-bs-dismiss='alert'></button></div>"; ?>
                <?php if ($err) echo "<div class='alert alert-danger alert-dismissible fade show'>$err <button class='btn-close' data-bs-dismiss='alert'></button></div>"; ?>

                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover small align-middle">
                        <thead class="table-dark">
                            <tr><th>No</th><th>Kode</th><th>Nama Program Studi</th><th>Kaprodi</th><th>Aksi</th></tr>
                        </thead>
                        <tbody>
                            <?php if (count($dataProdi) == 0): ?>
                                <tr><td colspan="5" class="text-center">Belum ada data.</td></tr>
                            <?php endif; ?>

                            <?php $no=1; foreach($dataProdi as $row): ?>
                            <tr>
                                <td class="text-center"><?= $no++ ?></td>
                                <td class="text-center fw-bold"><?= htmlspecialchars($row['kode_prodi']) ?></td>
                                <td><?= htmlspecialchars($row['nama_prodi']) ?></td>
                                <td>
                                    <?php if($row['nama_kaprodi']): ?>
                                        <span class="badge bg-info text-dark"><?= htmlspecialchars($row['nama_kaprodi']) ?></span>
                                    <?php else: ?>
                                        <span class="text-muted">-</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-center">
                                    <a href="prodi_form.php?id=<?= $row['id'] ?>" class="btn btn-warning btn-sm"><i class="fas fa-edit"></i></a>
                                    <form method="POST" style="display:inline;" onsubmit="return confirm('Hapus Prodi ini?');">
                                        <input type="hidden" name="hapus_id" value="<?= $row['id'] ?>">
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
    <div class="col-md-3"><?php include "sidebar.php"; ?></div>
    </div>
</div>
<?php include "../footer.php"; ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>