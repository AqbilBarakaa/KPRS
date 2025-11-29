<?php
// tu/staff.php
require_once "../config/auth.php";
require_once "../config/database.php";

$auth = new Auth();
if (!$auth->isLoggedIn() || ($_SESSION['user']['role'] ?? '') !== 'tu') {
    header("Location: ../login.php"); exit;
}

$msg = ''; $err = '';

// --- HAPUS DATA ---
if (isset($_POST['hapus_id'])) {
    if ($_POST['hapus_id'] == $_SESSION['user']['id']) {
        $err = "Tidak dapat menghapus akun yang sedang digunakan.";
    } else {
        try {
            $stmtGet = $pdo->prepare("SELECT foto FROM tata_usaha WHERE id = ?");
            $stmtGet->execute([$_POST['hapus_id']]);
            $fotoLama = $stmtGet->fetchColumn();

            $pdo->prepare("DELETE FROM tata_usaha WHERE id = ?")->execute([$_POST['hapus_id']]);
            
            if ($fotoLama && file_exists("../assets/img/uploads/" . $fotoLama)) {
                unlink("../assets/img/uploads/" . $fotoLama);
            }
            $msg = "Data staff berhasil dihapus.";
        } catch (Exception $e) {
            $err = "Gagal menghapus.";
        }
    }
}

// --- AMBIL DATA ---
$data = $pdo->query("SELECT * FROM tata_usaha ORDER BY nama ASC")->fetchAll();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8"><title>Data Staff TU</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/style.css">
    <style>.table-foto { width: 40px; height: 50px; object-fit: cover; border-radius: 4px; border: 1px solid #ddd; }</style>
</head>
<body>
<?php include "header.php"; ?>
<div class="container">
    <div class="row">
        
        <div class="col-md-9">
            <div class="content-box">
                <div class="d-flex justify-content-between mb-3">
                    <div class="msg-header mb-0"><i class="fas fa-user-tie me-2"></i> Data Staff Tata Usaha</div>
                    <a href="staff_form.php" class="btn btn-primary btn-sm"><i class="fas fa-plus"></i> Tambah Staff</a>
                </div>
                
                <?php if($msg) echo "<div class='alert alert-success alert-dismissible fade show'>$msg <button class='btn-close' data-bs-dismiss='alert'></button></div>"; ?>
                <?php if($err) echo "<div class='alert alert-danger alert-dismissible fade show'>$err <button class='btn-close' data-bs-dismiss='alert'></button></div>"; ?>

                <div class="table-responsive">
                    <table class="table table-bordered table-striped small align-middle">
                        <thead class="table-dark">
                            <tr><th>No</th><th>Foto</th><th>NIP</th><th>Nama</th><th>Email</th><th>Aksi</th></tr>
                        </thead>
                        <tbody>
                            <?php $no=1; foreach($data as $r): ?>
                            <?php 
                                $fotoUrl = (!empty($r['foto']) && file_exists("../assets/img/uploads/".$r['foto'])) ? "../assets/img/uploads/".$r['foto'] : "https://via.placeholder.com/40x50.png?text=FOTO";
                            ?>
                            <tr>
                                <td><?= $no++ ?></td>
                                <td><img src="<?= $fotoUrl ?>" class="table-foto"></td>
                                <td><?= htmlspecialchars($r['nip']) ?></td>
                                <td><?= htmlspecialchars($r['nama']) ?></td>
                                <td><?= htmlspecialchars($r['email']) ?></td>
                                <td>
                                    <a href="staff_form.php?id=<?= $r['id'] ?>" class="btn btn-warning btn-sm"><i class="fas fa-edit"></i></a>
                                    <?php if($r['id'] != $_SESSION['user']['id']): ?>
                                    <form method="POST" style="display:inline" onsubmit="return confirm('Hapus staff ini?');">
                                        <input type="hidden" name="hapus_id" value="<?= $r['id'] ?>">
                                        <button class="btn btn-danger btn-sm"><i class="fas fa-trash"></i></button>
                                    </form>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-md-3"><?php include "sidebar.php"; ?></div>

    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body></html>