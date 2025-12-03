<?php
require_once "../config/auth.php";
require_once "../config/database.php";

$auth = new Auth();
if (!$auth->isLoggedIn() || ($_SESSION['user']['role'] ?? '') !== 'tu') {
    header("Location: ../login.php"); exit;
}

$msg = ''; $err = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $tahun = $_POST['tahun'];
    $semester = $_POST['semester'];
    $krs_start = $_POST['krs_start'];
    $krs_end = $_POST['krs_end'];
    $kprs_start = $_POST['kprs_start'];
    $kprs_end = $_POST['kprs_end'];
    $status = $_POST['status'];

    try {
        $sql = "UPDATE periode_akademik SET 
                tahun_akademik = ?, semester = ?, 
                tanggal_mulai_krs = ?, tanggal_selesai_krs = ?, 
                tanggal_mulai_kprs = ?, tanggal_selesai_kprs = ?, 
                status = ? 
                WHERE id = 1";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$tahun, $semester, $krs_start, $krs_end, $kprs_start, $kprs_end, $status]);
        $msg = "Pengaturan periode berhasil disimpan.";
    } catch (Exception $e) {
        $err = "Gagal menyimpan: " . $e->getMessage();
    }
}

$data = $pdo->query("SELECT * FROM periode_akademik WHERE id = 1")->fetch();

function formatDateTimeInput($dbDate) {
    if (!$dbDate) return '';
    return date('Y-m-d\TH:i', strtotime($dbDate));
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Setting Periode | TU</title>
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
                <div class="msg-header"><i class="fas fa-clock me-2"></i> Pengaturan Waktu KRS</div>
                
                <?php if ($msg): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <?= $msg ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>

                <?php if ($err): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <?= $err ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>
                
                <form method="POST">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Tahun Akademik</label>
                            <input type="text" name="tahun" class="form-control" value="<?= htmlspecialchars($data['tahun_akademik']) ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Semester</label>
                            <select name="semester" class="form-select">
                                <option value="Ganjil" <?= $data['semester'] == 'Ganjil' ? 'selected' : '' ?>>Ganjil</option>
                                <option value="Genap" <?= $data['semester'] == 'Genap' ? 'selected' : '' ?>>Genap</option>
                            </select>
                        </div>
                    </div>

                    <div class="card mb-3 bg-light border-0">
                        <div class="card-body">
                            <h6 class="fw-bold text-primary border-bottom pb-2 mb-3">
                                <i class="fas fa-edit me-1"></i> Jadwal Pengisian KRS (Awal)
                            </h6>
                            <div class="row">
                                <div class="col-md-6 mb-2">
                                    <label class="form-label small fw-bold">Waktu Mulai</label>
                                    <input type="datetime-local" name="krs_start" class="form-control" 
                                           value="<?= formatDateTimeInput($data['tanggal_mulai_krs']) ?>" required>
                                </div>
                                <div class="col-md-6 mb-2">
                                    <label class="form-label small fw-bold">Waktu Selesai</label>
                                    <input type="datetime-local" name="krs_end" class="form-control" 
                                           value="<?= formatDateTimeInput($data['tanggal_selesai_krs']) ?>" required>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card mb-3 bg-light border-0">
                        <div class="card-body">
                            <h6 class="fw-bold text-danger border-bottom pb-2 mb-3">
                                <i class="fas fa-sync-alt me-1"></i> Jadwal Perubahan KRS (KPRS / Revisi)
                            </h6>
                            <div class="row">
                                <div class="col-md-6 mb-2">
                                    <label class="form-label small fw-bold">Waktu Mulai</label>
                                    <input type="datetime-local" name="kprs_start" class="form-control" 
                                           value="<?= formatDateTimeInput($data['tanggal_mulai_kprs']) ?>" required>
                                </div>
                                <div class="col-md-6 mb-2">
                                    <label class="form-label small fw-bold">Waktu Selesai</label>
                                    <input type="datetime-local" name="kprs_end" class="form-control" 
                                           value="<?= formatDateTimeInput($data['tanggal_selesai_kprs']) ?>" required>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold">Status Sistem</label>
                        <select name="status" class="form-select">
                            <option value="active" <?= $data['status'] == 'active' ? 'selected' : '' ?>>Aktif</option>
                            <option value="inactive" <?= $data['status'] == 'inactive' ? 'selected' : '' ?>>Tidak Aktif (Tutup)</option>
                        </select>
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary"><i class="fas fa-save me-2"></i> Simpan Pengaturan</button>
                    </div>
                </form>
            </div>
        </div>
    <div class="col-md-3"><?php include "sidebar.php"; ?></div>
    </div>
</div>

<?php include "../footer.php"; ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>