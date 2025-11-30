<?php
// tu/sidebar.php
$currentPage = basename($_SERVER['PHP_SELF']);
$user = $_SESSION['user'];
$nama = htmlspecialchars($user['nama']);
$nip = htmlspecialchars($user['username']);

// Ambil foto terbaru
global $pdo;
$stmtFoto = $pdo->prepare("SELECT foto FROM tata_usaha WHERE id = ?");
$stmtFoto->execute([$user['id']]);
$fotoDb = $stmtFoto->fetchColumn();
$fotoProfil = (!empty($fotoDb) && file_exists("../assets/img/uploads/$fotoDb")) ? "../assets/img/uploads/$fotoDb" : "https://via.placeholder.com/100x120.png?text=STAFF+TU";
$cntPending = $pdo->query("SELECT COUNT(*) FROM pengajuan_tambah_kelas WHERE status='approved'")->fetchColumn();
?>
<div class="sidebar-box">
    <div class="sidebar-header">Informasi Pengguna</div>
    <div class="sidebar-content">
        <div class="profile-img-box">
            <img src="<?= $fotoProfil ?>" alt="Foto Profil" class="profile-img">
        </div>
        <div class="profile-info">
            <div class="profile-name"><?= $nama ?></div>
            <div class="mb-1">NIP: <?= $nip ?></div>
            <div class="fw-bold text-primary">STAFF TATA USAHA</div>
            <div class="mt-2 text-end"><a href="../logout.php" class="text-secondary text-decoration-none">[ Logout ]</a></div>
        </div>
    </div>
</div>

<div class="sidebar-box">
    <div class="sidebar-header">Menu Tata Usaha</div>
    <ul class="menu-list">
        <li><a href="dashboard.php" class="<?= $currentPage == 'dashboard.php' ? 'menu-active' : 'menu-default' ?>">Dashboard</a></li>
        <li><a href="profil.php" class="<?= $currentPage == 'profil.php' ? 'menu-active' : 'menu-default' ?>">Profil</a></li>
        <li><a href="mahasiswa.php" class="<?= strpos($currentPage, 'mahasiswa') !== false ? 'menu-active' : 'menu-default' ?>">Data Mahasiswa</a></li>
        <li><a href="dosen.php" class="<?= strpos($currentPage, 'dosen') !== false ? 'menu-active' : 'menu-default' ?>">Data Dosen</a></li>
        <li><a href="prodi.php" class="<?= strpos($currentPage, 'prodi') !== false ? 'menu-active' : 'menu-default' ?>">Data Program Studi</a></li>
        <li><a href="matakuliah.php" class="<?= strpos($currentPage, 'matakuliah') !== false ? 'menu-active' : 'menu-default' ?>">Data Mata Kuliah</a></li>
        <li><a href="kelas.php" class="<?= strpos($currentPage, 'kelas') !== false ? 'menu-active' : 'menu-default' ?>">Data Kelas</a></li>
        <li><a href="staff.php" class="<?= strpos($currentPage, 'staff') !== false ? 'menu-active' : 'menu-default' ?>">Data Staff TU</a></li>
        <li>
            <a href="pengajuan.php" class="<?= $currentPage == 'pengajuan.php' ? 'menu-active' : 'menu-default' ?>">
                Pengajuan Kelas 
                <?php if($cntPending > 0): ?>
                    <span class="badge bg-danger ms-1"><?= $cntPending ?></span>
                <?php endif; ?>
            </a>
        </li>
        <li>
            <a href="pesan.php" class="<?= $currentPage == 'pesan.php' ? 'menu-active' : 'menu-default' ?>">
                Pesan
            </a>
        </li>
        <li><a href="ubah_password.php" class="<?= $currentPage == 'ubah_password.php' ? 'menu-active' : 'menu-default' ?>">Ubah Password</a></li>
    </ul>
</div>