<?php
require_once "../config/auth.php";
require_once "../config/database.php";

$auth = new Auth();
if (!$auth->isLoggedIn() || !in_array($_SESSION['user']['role'], ['dosen', 'dosen_dpa', 'dosen_kaprodi'])) {
    header("Location: ../login.php"); exit;
}

$id_dosen = $_SESSION['user']['id'];
$query = "
    SELECT k.*, mk.kode_mk, mk.nama_mk, mk.sks, mk.semester
    FROM kelas k
    JOIN mata_kuliah mk ON k.mata_kuliah_id = mk.id
    WHERE k.dosen_pengampu_id = ?
    ORDER BY FIELD(k.hari, 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'), k.jam_mulai ASC
";

$stmt = $pdo->prepare($query);
$stmt->execute([$id_dosen]);
$jadwal = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Jadwal Mengajar | Portal Dosen</title>
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
                <div class="msg-header"><i class="fas fa-calendar-alt me-2"></i> Jadwal Mengajar Saya</div>
                
                <?php if(empty($jadwal)): ?>
                    <div class="alert alert-info text-center">
                        <i class="fas fa-info-circle me-2"></i> Belum ada jadwal mengajar yang ditentukan untuk Anda.
                    </div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover small align-middle">
                            <thead class="table-dark text-center">
                                <tr>
                                    <th>Hari</th>
                                    <th>Jam</th>
                                    <th>Kode MK</th>
                                    <th>Mata Kuliah</th>
                                    <th>SKS</th>
                                    <th>Kelas</th>
                                    <th>Ruangan</th>
                                    <th>Mahasiswa</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($jadwal as $row): ?>
                                <tr>
                                    <td class="text-center fw-bold"><?= $row['hari'] ?></td>
                                    <td class="text-center">
                                        <?= substr($row['jam_mulai'], 0, 5) ?> - <?= substr($row['jam_selesai'], 0, 5) ?>
                                    </td>
                                    <td class="text-center"><?= $row['kode_mk'] ?></td>
                                    <td><?= $row['nama_mk'] ?></td>
                                    <td class="text-center"><?= $row['sks'] ?></td>
                                    <td class="text-center fw-bold text-primary"><?= $row['nama_kelas'] ?></td>
                                    <td class="text-center"><?= htmlspecialchars($row['ruangan'] ?? '-') ?></td>
                                    <td class="text-center">
                                        <?= $row['terisi'] ?> / <?= $row['kuota'] ?>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <div class="col-md-3">
            <?php 
            if($_SESSION['user']['role'] == 'dosen_kaprodi') {
                include "sidebar.php"; 
            } else {
                include "sidebar.php";
            }
            ?>
        </div>
    </div>
</div>
<?php include "../footer.php"; ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>