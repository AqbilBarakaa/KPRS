<?php
require_once "../config/auth.php";
require_once "../config/database.php";

$auth = new Auth();
if (!$auth->isLoggedIn() || ($_SESSION['user']['role'] ?? '') !== 'tu') {
    header("Location: ../login.php"); exit;
}

if (isset($_POST['hapus_id'])) {
    try {
        $pdo->prepare("DELETE FROM mata_kuliah WHERE id = ?")->execute([$_POST['hapus_id']]);
    } catch (Exception $e) {
        $err = "Gagal menghapus. Mata kuliah ini mungkin sudah memiliki Kelas aktif.";
    }
}

$mkWajib = $pdo->query("SELECT mk.*, ps.nama_prodi 
                        FROM mata_kuliah mk 
                        LEFT JOIN program_studi ps ON mk.prodi_id = ps.id 
                        WHERE mk.sifat = 'Wajib' 
                        ORDER BY mk.semester ASC, mk.kode_mk ASC")->fetchAll();

$mkPilihan = $pdo->query("SELECT mk.*, ps.nama_prodi 
                          FROM mata_kuliah mk 
                          LEFT JOIN program_studi ps ON mk.prodi_id = ps.id 
                          WHERE mk.sifat = 'Pilihan' 
                          ORDER BY mk.semester ASC, mk.kode_mk ASC")->fetchAll();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Mata Kuliah | TU</title>
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
                    <div class="msg-header mb-0">Data Mata Kuliah</div>
                    <a href="matakuliah_form.php" class="btn btn-primary btn-sm"><i class="fas fa-plus"></i> Tambah MK</a>
                </div>

                <?php if(isset($err)) echo "<div class='alert alert-danger'>$err</div>"; ?>
                
                <ul class="nav nav-tabs mb-3" id="mkTab" role="tablist">
                    <li class="nav-item">
                        <button class="nav-link active" id="wajib-tab" data-bs-toggle="tab" data-bs-target="#wajib" type="button">
                            MK Wajib <span class="badge bg-secondary ms-1"><?= count($mkWajib) ?></span>
                        </button>
                    </li>
                    <li class="nav-item">
                        <button class="nav-link" id="pilihan-tab" data-bs-toggle="tab" data-bs-target="#pilihan" type="button">
                            MK Pilihan <span class="badge bg-secondary ms-1"><?= count($mkPilihan) ?></span>
                        </button>
                    </li>
                </ul>

                <div class="tab-content" id="mkTabContent">
                    
                    <div class="tab-pane fade show active" id="wajib">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped small align-middle table-hover">
                                <thead class="table-dark">
                                    <tr><th>Kode</th><th>Nama Mata Kuliah</th><th>SKS</th><th>Sem</th><th>Min. Lulus</th><th>Prasyarat</th><th>Aksi</th></tr>
                                </thead>
                                <tbody>
                                    <?php if(count($mkWajib) == 0): ?>
                                        <tr><td colspan="7" class="text-center">Belum ada data.</td></tr>
                                    <?php endif; ?>

                                    <?php foreach($mkWajib as $r): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($r['kode_mk']) ?></td>
                                        <td><?= htmlspecialchars($r['nama_mk']) ?></td>
                                        <td><?= $r['sks'] ?></td>
                                        <td><?= $r['semester'] ?></td>
                                        <td class="text-center fw-bold"><?= $r['minimal_kelulusan'] ?></td>
                                        <td><?= htmlspecialchars($r['prasyarat'] ?? '-') ?></td>
                                        <td>
                                            <a href="matakuliah_form.php?id=<?= $r['id'] ?>" class="btn btn-warning btn-sm"><i class="fas fa-edit"></i></a>
                                            <form method="POST" style="display:inline" onsubmit="return confirm('Hapus MK Wajib ini?');">
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

                    <div class="tab-pane fade" id="pilihan">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped small align-middle table-hover">
                                <thead class="bg-primary text-white">
                                    <tr><th>Kode</th><th>Nama Mata Kuliah</th><th>SKS</th><th>Sem</th><th>Min. Lulus</th><th>Prasyarat</th><th>Aksi</th></tr>
                                </thead>
                                <tbody>
                                    <?php if(count($mkPilihan) == 0): ?>
                                        <tr><td colspan="7" class="text-center">Belum ada data.</td></tr>
                                    <?php endif; ?>

                                    <?php foreach($mkPilihan as $r): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($r['kode_mk']) ?></td>
                                        <td><?= htmlspecialchars($r['nama_mk']) ?></td>
                                        <td><?= $r['sks'] ?></td>
                                        <td><?= $r['semester'] ?></td>
                                        <td class="text-center fw-bold"><?= $r['minimal_kelulusan'] ?></td>
                                        <td><?= htmlspecialchars($r['prasyarat'] ?? '-') ?></td>
                                        <td>
                                            <a href="matakuliah_form.php?id=<?= $r['id'] ?>" class="btn btn-warning btn-sm"><i class="fas fa-edit"></i></a>
                                            <form method="POST" style="display:inline" onsubmit="return confirm('Hapus MK Pilihan ini?');">
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

                </div> </div>
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