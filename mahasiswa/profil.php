<?php
require_once "../config/auth.php";
require_once "../config/database.php";

$auth = new Auth();
if (!$auth->isLoggedIn() || ($_SESSION['user']['role'] ?? '') !== 'mahasiswa') {
    header("Location: ../login.php");
    exit;
}

$user_id = $_SESSION['user']['id'];
$stmt = $pdo->prepare("SELECT m.*, d.nama AS nama_dpa FROM mahasiswa m LEFT JOIN dosen d ON m.dpa_id = d.id WHERE m.id = ?");
$stmt->execute([$user_id]);
$data = $stmt->fetch();

if (!$data) $auth->logout();

$jk = ($data['jenis_kelamin'] ?? '') == 'L' ? 'Laki-laki' : (($data['jenis_kelamin'] ?? '') == 'P' ? 'Perempuan' : '-');
$fotoPath = "../assets/img/uploads/" . ($data['foto'] ?? 'default.png');
$fotoProfilBesar = (!empty($data['foto']) && file_exists($fotoPath)) ? $fotoPath : "https://via.placeholder.com/150x180.png?text=FOTO";
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Profil | Portal Akademik</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>

<?php include 'header.php'; ?>

<div class="container">
    <div class="row">
        <div class="col-md-9">
            <div class="content-box">
                <div class="msg-header">Biodata Mahasiswa</div>
                <div class="row">
                    <div class="col-md-3 text-center mb-4">
                        <div class="card p-2 shadow-sm">
                            <img src="<?= $fotoProfilBesar ?>" alt="Foto Profil" class="img-fluid" style="width: 150px; height: 180px; object-fit: cover;">
                        </div>
                        <div class="mt-2"><span class="badge bg-success">Status: Aktif</span></div>
                    </div>
                    <div class="col-md-9">
                        <table class="table table-striped table-hover">
                            <tbody>
                                <tr><th style="width: 30%;">NIM</th><td><?= htmlspecialchars($data['nim']) ?></td></tr>
                                <tr><th>Nama Lengkap</th><td><?= htmlspecialchars($data['nama']) ?></td></tr>
                                <tr><th>Jenis Kelamin</th><td><?= $jk ?></td></tr>
                                <tr><th>Program Studi</th><td><?= htmlspecialchars($data['prodi'] ?? '-') ?></td></tr>
                                <tr><th>Semester</th><td><?= htmlspecialchars($data['semester'] ?? '1') ?></td></tr>
                                <tr><th>Email</th><td><?= htmlspecialchars($data['email'] ?? '-') ?></td></tr>
                                <tr><th>Dosen Wali</th><td><?= htmlspecialchars($data['nama_dpa'] ?? 'Belum ditentukan') ?></td></tr>
                            </tbody>
                        </table>
                        <div class="alert alert-info mt-3 py-2 small">
                            <i class="fas fa-info-circle"></i> Data profil dikelola oleh Bagian Tata Usaha (TU).
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <?php include 'sidebar.php'; ?>
        </div>
    </div>
    <div class="text-center mt-5 mb-3 text-muted small">Portal Akademik Kelompok 5 &copy; 2025.</div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="../assets/js/script.js"></script>
</body>
</html>