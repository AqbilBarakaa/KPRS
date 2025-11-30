<?php
require_once "../config/auth.php";
require_once "../config/database.php";

$auth = new Auth();
if (!$auth->isLoggedIn() || ($_SESSION['user']['role'] ?? '') !== 'mahasiswa') {
    header("Location: ../login.php"); exit;
}

$id_kelas = $_GET['id'] ?? 0;

$stmt = $pdo->prepare("
    SELECT k.*, 
           mk.kode_mk, mk.nama_mk, mk.sks, mk.semester, mk.sifat, mk.prasyarat, mk.minimal_kelulusan,
           d.nama as nama_dosen, d.nidn
    FROM kelas k
    JOIN mata_kuliah mk ON k.mata_kuliah_id = mk.id
    LEFT JOIN dosen d ON k.dosen_pengampu_id = d.id
    WHERE k.id = ?
");
$stmt->execute([$id_kelas]);
$kelas = $stmt->fetch();

if (!$kelas) {
    echo "<script>alert('Kelas tidak ditemukan!'); window.location='informasi_matakuliah.php';</script>";
    exit;
}

$list_syarat = [];
$raw_prasyarat = $kelas['prasyarat'];

if (!empty($raw_prasyarat)) {
    $items = array_map('trim', explode(',', $raw_prasyarat));
    foreach ($items as $item) {
        if (preg_match('/^(\d+)\s*(SKS|sks)$/i', $item)) {
            $list_syarat[] = ['kode' => '-','matakuliah' => "Total SKS Lulus: $item",'syarat' => 'Terpenuhi'];
        } else {
            $is_coreq = (substr($item, -1) === '*'); 
            $kode_bersih = rtrim($item, '*');
            $stmtMK = $pdo->prepare("SELECT nama_mk FROM mata_kuliah WHERE kode_mk = ?");
            $stmtMK->execute([$kode_bersih]);
            $nama_mk_syarat = $stmtMK->fetchColumn();
            $list_syarat[] = [
                'kode' => $kode_bersih,
                'matakuliah' => $nama_mk_syarat ? $nama_mk_syarat : 'Mata Kuliah Tidak Ditemukan',
                'syarat' => $is_coreq ? 'Lulus / Ambil Bersamaan' : 'Lulus'
            ];
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Detail Kelas | Portal Akademik</title>
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
                <div class="msg-header">
                    Detail Kelas: <?= htmlspecialchars($kelas['nama_mk']) ?> (<?= htmlspecialchars($kelas['nama_kelas']) ?>)
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <table class="table table-bordered table-detail">
                            <tr><th>Mata Kuliah</th><td><?= htmlspecialchars($kelas['kode_mk']) ?> - <?= htmlspecialchars($kelas['nama_mk']) ?></td></tr>
                            <tr><th>Kelas</th><td class="fw-bold text-primary"><?= htmlspecialchars($kelas['nama_kelas']) ?></td></tr>
                            <tr><th>Dosen Pengampu</th><td><?= htmlspecialchars($kelas['nama_dosen']) ?> (NIDN: <?= htmlspecialchars($kelas['nidn']) ?>)</td></tr>
                            <tr><th>SKS / Semester</th><td><?= $kelas['sks'] ?> SKS / Semester <?= $kelas['semester'] ?></td></tr>
                            <tr><th>Sifat</th><td><?= ($kelas['sifat'] == 'Wajib') ? '<span class="badge bg-primary">Wajib</span>' : '<span class="badge bg-success">Pilihan</span>' ?></td></tr>
                            <tr><th>Jadwal & Ruangan</th><td>
                                <i class="far fa-calendar-alt text-muted me-1"></i> <?= $kelas['hari'] ?>, <?= substr($kelas['jam_mulai'],0,5) ?> - <?= substr($kelas['jam_selesai'],0,5) ?> WIB
                                <br>
                                <i class="fas fa-door-open text-muted me-1"></i> Ruang: <?= htmlspecialchars($kelas['ruangan'] ?? 'Belum ditentukan') ?>
                            </td></tr>
                            <tr><th>Prasyarat</th><td class="p-2">
                                <?php if (empty($list_syarat)): ?>
                                    <span class="text-muted">- Tidak ada prasyarat -</span>
                                <?php else: ?>
                                    <table class="table-prasyarat">
                                        <thead><tr><th width="20%">Kode</th><th width="50%">Matakuliah</th><th width="30%">Syarat</th></tr></thead>
                                        <tbody>
                                            <?php foreach ($list_syarat as $req): ?>
                                            <tr>
                                                <td><?= htmlspecialchars($req['kode']) ?></td>
                                                <td><?= htmlspecialchars($req['matakuliah']) ?></td>
                                                <td><?= htmlspecialchars($req['syarat']) ?></td>
                                            </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                <?php endif; ?>
                            </td></tr>
                            <tr><th>Minimal Kelulusan</th><td>Grade: <b><?= htmlspecialchars($kelas['minimal_kelulusan'] ?? '-') ?></b></td></tr>
                        </table>
                    </div>
                </div>

                <div class="mt-3">
                    <a href="informasi_matakuliah.php?semester=<?= $kelas['semester'] ?>" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-1"></i> Kembali ke Daftar
                    </a>
                </div>
            </div>
        </div>
        <div class="col-md-3"><?php include "sidebar.php"; ?></div>
    </div>
</div>
<?php include "../footer.php"; ?>
</body>
</html>