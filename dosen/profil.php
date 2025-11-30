<?php
require_once "../config/auth.php";
require_once "../config/database.php";

$auth = new Auth();
if (!$auth->isLoggedIn() || !in_array($_SESSION['user']['role'], ['dosen', 'dosen_dpa', 'dosen_kaprodi'])) {
    header("Location: ../login.php"); exit;
}

$user_id = $_SESSION['user']['id'];
$stmt = $pdo->prepare("SELECT * FROM dosen WHERE id = ?");
$stmt->execute([$user_id]);
$data = $stmt->fetch();

if (!$data) $auth->logout();

$fotoPath = "../assets/img/uploads/" . ($data['foto'] ?? 'default.png');
$fotoUrl = (!empty($data['foto']) && file_exists($fotoPath)) ? $fotoPath : "https://via.placeholder.com/150x180.png?text=FOTO";
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Profil Dosen | Portal Akademik</title>
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
                <div class="msg-header"><i class="fas fa-user-tie me-2"></i> Profil Dosen</div>
                
                <div class="row">
                    <div class="col-md-3 text-center mb-4">
                        <div class="card p-2 shadow-sm">
                            <img src="<?= $fotoUrl ?>" alt="Foto Profil" class="img-fluid" style="width: 150px; height: 180px; object-fit: cover;">
                        </div>
                    </div>
                    <div class="col-md-9">
                        <table class="table table-striped table-hover">
                            <tbody>
                                <tr><th style="width: 30%;">NIDN</th><td><?= htmlspecialchars($data['nidn']) ?></td></tr>
                                <tr><th>Nama Lengkap</th><td><?= htmlspecialchars($data['nama']) ?></td></tr>
                                <tr><th>Email</th><td><?= htmlspecialchars($data['email'] ?? '-') ?></td></tr>
                                <tr><th>Program Studi</th><td><?= htmlspecialchars($data['prodi'] ?? '-') ?></td></tr>
                                <tr><th>Jabatan</th><td>
                                    <?php if($data['jabatan'] == 'Kaprodi'): ?>
                                        <span class="badge bg-primary">KAPRODI</span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary">DOSEN</span>
                                    <?php endif; ?>
                                </td></tr>
                            </tbody>
                        </table>
                        <div class="alert alert-info py-2 small">
                            <i class="fas fa-info-circle"></i> Data profil dikelola oleh Bagian Tata Usaha (TU).
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <?php 
                if ($_SESSION['user']['role'] == 'dosen_kaprodi') {
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