<?php
// tu/profil.php
require_once "../config/auth.php";
require_once "../config/database.php";

$auth = new Auth();
if (!$auth->isLoggedIn() || ($_SESSION['user']['role'] ?? '') !== 'tu') {
    header("Location: ../login.php"); exit;
}

$user_id = $_SESSION['user']['id'];
$stmt = $pdo->prepare("SELECT * FROM tata_usaha WHERE id = ?");
$stmt->execute([$user_id]);
$data = $stmt->fetch();

if (!$data) $auth->logout();

// Foto
$fotoPath = "../assets/img/uploads/" . ($data['foto'] ?? 'default.png');
$fotoUrl = (!empty($data['foto']) && file_exists($fotoPath)) ? $fotoPath : "https://via.placeholder.com/150x180.png?text=FOTO";
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Profil Saya | TU</title>
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
                <div class="msg-header"><i class="fas fa-user-circle me-2"></i> Profil Saya</div>
                
                <div class="row">
                    <div class="col-md-3 text-center mb-4">
                        <div class="card p-2 shadow-sm">
                            <img src="<?= $fotoUrl ?>" alt="Foto Profil" class="img-fluid" style="width: 150px; height: 180px; object-fit: cover;">
                        </div>
                    </div>
                    <div class="col-md-9">
                        <table class="table table-striped table-hover">
                            <tbody>
                                <tr><th style="width: 30%;">NIP</th><td><?= htmlspecialchars($data['nip']) ?></td></tr>
                                <tr><th>Nama Lengkap</th><td><?= htmlspecialchars($data['nama']) ?></td></tr>
                                <tr><th>Email</th><td><?= htmlspecialchars($data['email'] ?? '-') ?></td></tr>
                                <tr><th>Jabatan</th><td>Staf Tata Usaha</td></tr>
                            </tbody>
                        </table>
                        <div class="alert alert-info py-2 small">
                            <i class="fas fa-info-circle"></i> Untuk mengubah data diri, silakan gunakan menu <b>Data Staff TU</b> (jika memiliki akses).
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3"><?php include "sidebar.php"; ?></div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>