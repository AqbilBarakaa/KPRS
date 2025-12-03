<?php
$currentPage = basename($_SERVER['PHP_SELF']);
$user = $_SESSION['user'];
$nama = htmlspecialchars($user['nama']);
$nidn = htmlspecialchars($user['username']);

global $pdo;
$stmtFoto = $pdo->prepare("SELECT foto FROM dosen WHERE id = ?");
$stmtFoto->execute([$user['id']]);
$fotoDb = $stmtFoto->fetchColumn();
$fotoProfil = (!empty($fotoDb) && file_exists("../assets/img/uploads/$fotoDb")) ? "../assets/img/uploads/$fotoDb" : "https://via.placeholder.com/100x120.png?text=DOSEN";

$cntUnread = $pdo->query("SELECT COUNT(*) FROM notifikasi WHERE user_id={$user['id']} AND user_type='dosen' AND is_read=0")->fetchColumn();
?>
<div class="sidebar-box">
    <div class="sidebar-header">Informasi Pengguna</div>
    <div class="sidebar-content">
        <div class="profile-img-box">
            <img src="<?= $fotoProfil ?>" alt="Foto Profil" class="profile-img">
        </div>
        <div class="profile-info">
            <div class="profile-name"><?= $nama ?></div>
            <div class="mb-1">NIDN: <?= $nidn ?></div>
            <div class="fw-bold text-primary">DOSEN</div>
            <div class="mt-2 text-end">
                <a href="../logout.php" class="text-secondary text-decoration-none">[ Logout ]</a>
            </div>
        </div>
    </div>
</div>

<div class="sidebar-box">
    <div class="sidebar-header">Menu Dosen</div>
    <ul class="menu-list">
        <li>
            <a href="dashboard.php" class="<?= $currentPage == 'dashboard.php' ? 'menu-active' : 'menu-default' ?>">
                Dashboard
            </a>
        </li>
        <li>
            <a href="profil.php" class="<?= $currentPage == 'profil.php' ? 'menu-active' : 'menu-default' ?>">
                Profil
            </a>
        </li>
        <li>
            <a href="perwalian.php" class="<?= $currentPage == 'perwalian.php' ? 'menu-active' : 'menu-default' ?>">
                Mahasiswa Perwalian
            </a>
        </li>
        <li>
            <a href="jadwal_mengajar.php" class="<?= $currentPage == 'jadwal_mengajar.php' ? 'menu-active' : 'menu-default' ?>">
                Jadwal Mengajar
            </a>
        </li>
        <li>
            <a href="pesan.php" class="<?= $currentPage == 'pesan.php' ? 'menu-active' : 'menu-default' ?>">
                Pesan
                <?php if($cntUnread > 0): ?>
                    <span class="badge bg-danger ms-1"><?= $cntUnread ?></span>
                <?php endif; ?>
            </a>
        </li>
        <li>
            <a href="ubah_password.php" class="<?= $currentPage == 'ubah_password.php' ? 'menu-active' : 'menu-default' ?>">
                Ubah Password
            </a>
        </li>
    </ul>
</div>