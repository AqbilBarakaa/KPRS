<?php
// mahasiswa/sidebar.php

// 1. Deteksi Halaman Aktif
$currentPage = basename($_SERVER['PHP_SELF']);

// 2. Logika Foto Otomatis (Cek Database jika belum diset)
if (!isset($fotoSidebarUrl)) {
    global $pdo; 
    $sb_uid = $_SESSION['user']['id'] ?? 0;
    $sb_foto = null;

    if ($pdo && $sb_uid) {
        try {
            $stmtSb = $pdo->prepare("SELECT foto FROM mahasiswa WHERE id = ?");
            $stmtSb->execute([$sb_uid]);
            $sb_foto = $stmtSb->fetchColumn();
        } catch (Exception $e) { /* silent fail */ }
    }

    $pathFisik = "assets/img/uploads/" . ($sb_foto ?? 'default.png');
    // Cek path relatif (asumsi dipanggil dari folder mahasiswa/)
    $checkPath = "../" . $pathFisik; 
    
    if (!empty($sb_foto) && file_exists(__DIR__ . "/../" . $checkPath)) {
        $fotoSidebarUrl = "../" . $pathFisik;
    } elseif (!empty($sb_foto) && file_exists($checkPath)) {
        $fotoSidebarUrl = $checkPath;
    } else {
        $fotoSidebarUrl = "https://via.placeholder.com/100x120.png?text=FOTO";
    }
}

// 3. Data User
$sb_nama  = $_SESSION['user']['nama'] ?? 'Mahasiswa';
$sb_nim   = $_SESSION['user']['username'] ?? '-';
$sb_prodi = $_SESSION['user']['prodi'] ?? 'TEKNIK INFORMATIKA'; 
?>

<div class="sidebar-box">
    <div class="sidebar-header">Informasi Pengguna</div>
    <div class="sidebar-content">
        <div class="profile-img-box">
            <img src="<?= $fotoSidebarUrl ?>" alt="Foto Profil" class="profile-img">
        </div>
        <div class="profile-info">
            <div class="profile-name"><?= htmlspecialchars($sb_nama) ?></div>
            <div class="mb-1"><?= htmlspecialchars($sb_nim) ?></div>
            <div class="fw-bold text-primary"><?= htmlspecialchars($sb_prodi) ?></div>
            <div class="mt-2 text-end">
                <a href="../logout.php" class="text-secondary text-decoration-none">[ Logout ]</a>
            </div>
        </div>
    </div>
</div>

<div class="sidebar-box">
    <div class="sidebar-header">Academics</div>
    <ul class="menu-list">
        <li>
            <a href="dashboard.php" class="<?= $currentPage == 'dashboard.php' ? 'menu-active' : 'menu-default' ?>">
                Halaman Depan
            </a>
        </li>
        <li>
            <a href="profil.php" class="<?= $currentPage == 'profil.php' ? 'menu-active' : 'menu-default' ?>">
                Profil
            </a>
        </li>
        <li>
            <a href="informasi_matakuliah.php" class="<?= $currentPage == 'informasi_matakuliah.php' ? 'menu-active' : 'menu-default' ?>">
                Informasi Matakuliah
            </a>
        </li>        
        <li><a href="#" class="menu-default">Kartu Rencana Studi</a></li>
        <li>
            <a href="tambah_kelas.php" class="<?= ($currentPage == 'tambah_kelas.php' || $currentPage == 'tambah_kelas_history.php') ? 'menu-active' : 'menu-default' ?>">
                Pengajuan Tambah Kelas
            </a>
        </li>
            <?php if($currentPage == 'tambah_kelas.php' || $currentPage == 'tambah_kelas_history.php'): ?>
        <li style="padding-left: 15px;">
            <a href="tambah_kelas_history.php" class="<?= $currentPage == 'tambah_kelas_history.php' ? 'fw-bold text-primary' : 'text-muted' ?>">
                <small><i class="fas fa-history me-1"></i> Riwayat Pengajuan</small>
            </a>
        </li>
        <?php endif; ?>

        <li>
            <a href="ubah_password.php" class="<?= $currentPage == 'ubah_password.php' ? 'menu-active' : 'menu-default' ?>">
                Ubah Password
            </a>
        </li>
    </ul>
</div>