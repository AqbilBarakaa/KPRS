<?php
// tu/dosen.php
require_once "../config/auth.php";
require_once "../config/database.php";

$auth = new Auth();
if (!$auth->isLoggedIn() || ($_SESSION['user']['role'] ?? '') !== 'tu') {
    header("Location: ../login.php"); exit;
}

$msg = ''; $err = '';

// --- HAPUS DATA ---
if (isset($_POST['hapus_id'])) {
    $id = $_POST['hapus_id'];
    try {
        // Ambil nama foto sebelum dihapus
        $stmtGet = $pdo->prepare("SELECT foto FROM dosen WHERE id = ?");
        $stmtGet->execute([$id]);
        $fotoLama = $stmtGet->fetchColumn();

        // Hapus data
        $pdo->prepare("DELETE FROM dosen WHERE id = ?")->execute([$id]);

        // Hapus file fisik
        if ($fotoLama && file_exists("../assets/img/uploads/" . $fotoLama)) {
            unlink("../assets/img/uploads/" . $fotoLama);
        }
        $msg = "Data dosen berhasil dihapus.";
    } catch (PDOException $e) {
        $err = "Gagal menghapus: Data dosen ini mungkin sedang digunakan.";
    }
}

// --- AMBIL DATA ---
$dosenBiasa = $pdo->query("SELECT * FROM dosen WHERE jabatan = 'Dosen' ORDER BY nama ASC")->fetchAll();
$kaprodi = $pdo->query("SELECT * FROM dosen WHERE jabatan = 'Kaprodi' ORDER BY nama ASC")->fetchAll();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Data Dosen | TU</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/style.css">
    <style>.table-foto { width: 40px; height: 50px; object-fit: cover; border-radius: 4px; border: 1px solid #ddd; }</style>
</head>
<body>

<?php include "header.php"; ?>

<div class="container">
    <div class="row">
        
        <div class="col-md-9">
            <div class="content-box">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div class="msg-header mb-0">Data Dosen</div>
                    <a href="dosen_form.php" class="btn btn-primary btn-sm">Tambah Dosen</a>
                </div>

                <?php if ($msg) echo "<div class='alert alert-success'>$msg</div>"; ?>
                <?php if ($err) echo "<div class='alert alert-danger'>$err</div>"; ?>

                <ul class="nav nav-tabs mb-3" id="dosenTab" role="tablist">
                    <li class="nav-item">
                        <button class="nav-link active" id="biasa-tab" data-bs-toggle="tab" data-bs-target="#biasa" type="button">Dosen Biasa</button>
                    </li>
                    <li class="nav-item">
                        <button class="nav-link" id="kaprodi-tab" data-bs-toggle="tab" data-bs-target="#kaprodi" type="button">Kaprodi</button>
                    </li>
                </ul>

                <div class="tab-content" id="dosenTabContent">
                    
                    <div class="tab-pane fade show active" id="biasa">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-hover small align-middle text-center">
                                <thead class="table-dark">
                                    <tr><th>No</th><th>Foto</th><th>NIDN</th><th>Nama</th><th>Email</th><th>Prodi</th><th>Aksi</th></tr>
                                </thead>
                                <tbody>
                                    <?php $no=1; foreach($dosenBiasa as $row): ?>
                                    <?php $fotoUrl = (!empty($row['foto']) && file_exists("../assets/img/uploads/".$row['foto'])) ? "../assets/img/uploads/".$row['foto'] : "https://via.placeholder.com/40x50.png?text=FOTO"; ?>
                                    <tr>
                                        <td><?= $no++ ?></td>
                                        <td><img src="<?= $fotoUrl ?>" class="table-foto"></td>
                                        <td><?= htmlspecialchars($row['nidn']) ?></td>
                                        <td class="text-start"><?= htmlspecialchars($row['nama']) ?></td>
                                        <td><?= htmlspecialchars($row['email']) ?></td>
                                        <td><?= htmlspecialchars($row['prodi']) ?></td>
                                        <td>
                                            <a href="dosen_form.php?id=<?= $row['id'] ?>" class="btn btn-warning btn-sm">Edit</a>
                                            <form method="POST" style="display:inline;" onsubmit="return confirm('Hapus data ini?');">
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

                    <div class="tab-pane fade" id="kaprodi">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-hover small align-middle text-center">
                                <thead class="bg-primary text-white">
                                    <tr><th>No</th><th>Foto</th><th>NIDN</th><th>Nama</th><th>Email</th><th>Prodi</th><th>Aksi</th></tr>
                                </thead>
                                <tbody>
                                    <?php $no=1; foreach($kaprodi as $row): ?>
                                    <?php $fotoUrl = (!empty($row['foto']) && file_exists("../assets/img/uploads/".$row['foto'])) ? "../assets/img/uploads/".$row['foto'] : "https://via.placeholder.com/40x50.png?text=FOTO"; ?>
                                    <tr>
                                        <td><?= $no++ ?></td>
                                        <td><img src="<?= $fotoUrl ?>" class="table-foto"></td>
                                        <td><?= htmlspecialchars($row['nidn']) ?></td>
                                        <td class="text-start fw-bold"><?= htmlspecialchars($row['nama']) ?></td>
                                        <td><?= htmlspecialchars($row['email']) ?></td>
                                        <td><?= htmlspecialchars($row['prodi']) ?></td>
                                        <td>
                                            <a href="dosen_form.php?id=<?= $row['id'] ?>" class="btn btn-warning btn-sm">Edit</a>
                                            <form method="POST" style="display:inline;" onsubmit="return confirm('Hapus Kaprodi?');">
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