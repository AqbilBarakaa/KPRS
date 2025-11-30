<?php
require_once "../config/auth.php";
require_once "../config/database.php";

$auth = new Auth();
if (!$auth->isLoggedIn() || ($_SESSION['user']['role'] ?? '') !== 'tu') {
    header("Location: ../login.php"); exit;
}

$msg = ''; $err = '';

if (isset($_POST['hapus_id'])) {
    $id = $_POST['hapus_id'];
    try {
        $stmtGetFoto = $pdo->prepare("SELECT foto FROM mahasiswa WHERE id = ?");
        $stmtGetFoto->execute([$id]);
        $fotoLama = $stmtGetFoto->fetchColumn();

        $pdo->prepare("DELETE FROM mahasiswa WHERE id = ?")->execute([$id]);

        if ($fotoLama && file_exists("../assets/img/uploads/" . $fotoLama)) {
            unlink("../assets/img/uploads/" . $fotoLama);
        }
        $msg = "Data mahasiswa berhasil dihapus.";
    } catch (PDOException $e) {
        $err = "Gagal menghapus: Data mungkin berelasi dengan tabel lain.";
    }
}

$query = "SELECT m.*, d.nama AS nama_dpa FROM mahasiswa m LEFT JOIN dosen d ON m.dpa_id = d.id ORDER BY m.nim ASC";
$dataMhs = $pdo->query($query)->fetchAll();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Data Mahasiswa | TU</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/style.css">
    <style>.table-foto { width: 40px; height: 50px; object-fit: cover; border-radius: 4px; border: 1px solid #ddd; }</style>
</head>
<body>

<?php include "../header.php"; ?>

<div class="container">
    <div class="row">
        
        <div class="col-md-9">
            <div class="content-box">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div class="msg-header mb-0">Data Mahasiswa</div>
                    <a href="mahasiswa_form.php" class="btn btn-primary btn-sm">Tambah Mahasiswa</a>
                </div>

                <?php if ($msg) echo "<div class='alert alert-success'>$msg</div>"; ?>
                <?php if ($err) echo "<div class='alert alert-danger'>$err</div>"; ?>

                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover align-middle text-center small">
                        <thead class="table-dark">
                            <tr><th>No</th><th>Foto</th><th>NIM</th><th>Nama</th><th>L/P</th><th>Prodi</th><th>Sem</th><th>Dosen Wali</th><th>Aksi</th></tr>
                        </thead>
                        <tbody>
                            <?php $no=1; foreach ($dataMhs as $row): ?>
                            <?php $fotoUrl = (!empty($row['foto']) && file_exists("../assets/img/uploads/".$row['foto'])) ? "../assets/img/uploads/".$row['foto'] : "https://via.placeholder.com/40x50.png?text=IMG"; ?>
                            <tr>
                                <td><?= $no++ ?></td>
                                <td><img src="<?= $fotoUrl ?>" class="table-foto"></td>
                                <td><?= htmlspecialchars($row['nim']) ?></td>
                                <td class="text-start"><?= htmlspecialchars($row['nama']) ?></td>
                                <td><?= htmlspecialchars($row['jenis_kelamin'] ?? '-') ?></td>
                                <td><?= htmlspecialchars($row['prodi']) ?></td>
                                <td><?= htmlspecialchars($row['semester']) ?></td>
                                <td class="text-start"><?= htmlspecialchars($row['nama_dpa'] ?? '-') ?></td>
                                <td>
                                    <a href="mahasiswa_form.php?id=<?= $row['id'] ?>" class="btn btn-warning btn-sm">Edit</a>
                                    <form method="POST" style="display:inline;" onsubmit="return confirm('Hapus?');">
                                        <input type="hidden" name="hapus_id" value="<?= $row['id'] ?>">
                                        <button class="btn btn-danger btn-sm">Hapus</button>
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
</body>
</html>