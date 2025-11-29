<?php
// mahasiswa/tambah_kelas.php
require_once "../config/auth.php";
require_once "../config/database.php";

$auth = new Auth();
if (!$auth->isLoggedIn() || ($_SESSION['user']['role'] ?? '') !== 'mahasiswa') {
    header("Location: ../login.php"); exit;
}

$mahasiswa_id = $_SESSION['user']['id'];
$message = ''; $error = '';

// Ambil Filter Semester dari GET atau Default 1
$filter_semester = $_GET['sem'] ?? 1;

// --- PROSES PENGAJUAN ---
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $mk_id = $_POST['mk_id'] ?? '';
    $alasan = trim($_POST['alasan'] ?? '');

    if (empty($mk_id) || empty($alasan)) {
        $error = "Harap pilih mata kuliah dan isi alasan pengajuan.";
    } else {
        try {
            // 1. Ambil Info Mata Kuliah & Prasyarat
            $stmtMK = $pdo->prepare("SELECT kode_mk, nama_mk, prasyarat FROM mata_kuliah WHERE id = ?");
            $stmtMK->execute([$mk_id]);
            $mkInfo = $stmtMK->fetch();

            if (!$mkInfo) {
                $error = "Mata kuliah tidak valid.";
            } else {
                $prasyaratStr = $mkInfo['prasyarat'];

                // 2. Cek Prasyarat
                $memenuhiSyarat = true;
                $alasanGagal = "";

                if (!empty($prasyaratStr)) {
                    $syaratArr = array_map('trim', explode(',', $prasyaratStr));
                    foreach ($syaratArr as $syarat) {
                        if (preg_match('/^(\d+)\s*(SKS|sks)$/i', $syarat, $matches)) {
                            $minSks = intval($matches[1]);
                            $stmtSks = $pdo->prepare("SELECT SUM(mk.sks) FROM krs_awal ka JOIN kelas k ON ka.kelas_id = k.id JOIN mata_kuliah mk ON k.mata_kuliah_id = mk.id WHERE ka.mahasiswa_id = ? AND ka.status = 'selesai'");
                            $stmtSks->execute([$mahasiswa_id]);
                            $totalSksLulus = $stmtSks->fetchColumn() ?: 0;
                            if ($totalSksLulus < $minSks) { $memenuhiSyarat = false; $alasanGagal .= "SKS kurang ($totalSksLulus/$minSks). "; }
                        } else {
                            $isCoReq = (substr($syarat, -1) === '*'); 
                            $kodeSyarat = rtrim($syarat, '*');
                            $sqlCek = "SELECT ka.status FROM krs_awal ka JOIN kelas k ON ka.kelas_id = k.id JOIN mata_kuliah mk ON k.mata_kuliah_id = mk.id WHERE ka.mahasiswa_id = ? AND mk.kode_mk = ?";
                            $stmtCek = $pdo->prepare($sqlCek);
                            $stmtCek->execute([$mahasiswa_id, $kodeSyarat]);
                            $status = $stmtCek->fetchColumn();
                            
                            if ($isCoReq) {
                                if (!$status || ($status!='selesai' && $status!='terdaftar')) { $memenuhiSyarat = false; $alasanGagal .= "Belum ambil MK: $kodeSyarat. "; }
                            } else {
                                if (!$status || $status!='selesai') { $memenuhiSyarat = false; $alasanGagal .= "Belum lulus MK: $kodeSyarat. "; }
                            }
                        }
                    }
                }

                if (!$memenuhiSyarat) {
                    $error = "<b>Syarat Tidak Terpenuhi:</b> " . $alasanGagal;
                } else {
                    // 3. Simpan Pengajuan
                    $cekPending = $pdo->prepare("SELECT id FROM pengajuan_tambah_kelas WHERE mahasiswa_id = ? AND mata_kuliah_id = ? AND status = 'pending'");
                    $cekPending->execute([$mahasiswa_id, $mk_id]);

                    if ($cekPending->rowCount() > 0) {
                        $error = "Anda sudah mengajukan mata kuliah ini (status pending).";
                    } else {
                        $sqlInsert = "INSERT INTO pengajuan_tambah_kelas (mahasiswa_id, mata_kuliah_id, alasan, status, tanggal_pengajuan) VALUES (?, ?, ?, 'pending', NOW())";
                        $stmtIns = $pdo->prepare($sqlInsert);
                        $stmtIns->execute([$mahasiswa_id, $mk_id, $alasan]);
                        $message = "Pengajuan berhasil dikirim! Menunggu validasi Kaprodi.";
                    }
                }
            }
        } catch (Exception $e) { $error = "Error: " . $e->getMessage(); }
    }
}

// --- AMBIL DAFTAR MATAKULIAH SESUAI SEMESTER TERPILIH ---
$stmtListMK = $pdo->prepare("SELECT id, kode_mk, nama_mk, prasyarat FROM mata_kuliah WHERE semester = ? ORDER BY nama_mk ASC");
$stmtListMK->execute([$filter_semester]);
$listMK = $stmtListMK->fetchAll();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Ajukan Tambah Kelas | Portal Akademik</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>

<?php include "header.php"; ?>

<div class="container">
    <div class="row">
        <div class="col-md-9">
            <div class="content-box">
                <div class="msg-header">Form Pengajuan Tambah Kelas</div>
                
                <?php if ($message): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert" id="success-alert">
                        <i class="fas fa-check-circle me-2"></i> <?= $message ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>
                <?php if ($error): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fas fa-exclamation-triangle me-2"></i> <?= $error ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <form method="GET" action="">
                    <div class="mb-4">
                        <label class="form-label fw-bold">Pilih Semester</label>
                        <select name="sem" class="form-select" onchange="this.form.submit()">
                            <?php for($i=1; $i<=8; $i++): ?>
                                <option value="<?= $i ?>" <?= ($filter_semester == $i) ? 'selected' : '' ?>>Semester <?= $i ?></option>
                            <?php endfor; ?>
                        </select>
                        <div class="form-text text-muted">
                            Pilih semester terlebih dahulu untuk menampilkan daftar mata kuliah.
                        </div>
                    </div>
                </form>

                <form method="POST">
                    <div class="mb-4">
                        <label class="form-label fw-bold">Pilih Mata Kuliah (Semester <?= $filter_semester ?>)</label>
                        <select name="mk_id" class="form-select" required>
                            <option value="">-- Pilih Mata Kuliah --</option>
                            <?php foreach ($listMK as $row): ?>
                                <?php $syaratText = $row['prasyarat'] ? " (Syarat: {$row['prasyarat']})" : ""; ?>
                                <option value="<?= $row['id'] ?>">
                                    <?= htmlspecialchars($row['kode_mk'] . ' - ' . $row['nama_mk']) . $syaratText ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <div class="form-text text-muted">
                            Pastikan Anda memenuhi prasyarat (Lulus MK Prasyarat atau Total SKS) sebelum mengajukan.
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold">Alasan Pengajuan</label>
                        <textarea name="alasan" class="form-control" rows="4" required placeholder="Contoh: Saya ingin mengambil mata kuliah ini karena kuota kelas penuh..."></textarea>
                    </div>

                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary btn-lg">Kirim Pengajuan</button>
                        <a href="dashboard.php" class="btn btn-outline-secondary">Batal</a>
                    </div>
                </form>
            </div>
        </div>

        <div class="col-md-3">
            <?php include "sidebar.php"; ?>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="../assets/js/script.js"></script>
</body>
</html>