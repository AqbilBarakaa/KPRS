<?php
require_once "../config/auth.php";
require_once "../config/database.php";

$auth = new Auth();
if (!$auth->isLoggedIn() || ($_SESSION['user']['role'] ?? '') !== 'tu') {
    header("Location: ../login.php"); exit;
}

$user_id = $_SESSION['user']['id'];
$msg = ''; $err = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $pass_lama = $_POST['pass_lama'] ?? '';
    $pass_baru = $_POST['pass_baru'] ?? '';
    $pass_ulangi = $_POST['pass_ulangi'] ?? '';

    if (empty($pass_lama) || empty($pass_baru) || empty($pass_ulangi)) {
        $err = "Semua kolom harus diisi.";
    } elseif ($pass_baru !== $pass_ulangi) {
        $err = "Password baru tidak cocok.";
    } else {
        $stmt = $pdo->prepare("SELECT password FROM tata_usaha WHERE id = ?");
        $stmt->execute([$user_id]);
        $current = $stmt->fetchColumn();

        $is_valid = false;
        if (strpos($current, '$2y$') === 0) {
            $is_valid = password_verify($pass_lama, $current);
        } else {
            $is_valid = ($pass_lama === $current); 
        }

        if (!$is_valid) {
            $err = "Password lama salah.";
        } else {
            $new_hash = password_hash($pass_baru, PASSWORD_DEFAULT);
            $pdo->prepare("UPDATE tata_usaha SET password = ? WHERE id = ?")->execute([$new_hash, $user_id]);
            $msg = "Password berhasil diubah.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Ubah Password | TU</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
<?php include "../header.php"; ?>
<div class="container">
    <div class="row">
        <div class="col-md-9">
            <div class="content-box">
                <div class="msg-header">Ubah Password</div>
                <div class="mb-4 text-muted small">
                    Gunakan kombinasi huruf dan angka yang kuat untuk keamanan akun Anda.
                </div>

                <?php if ($msg): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert" id="success-alert">
                        <i class="fas fa-check-circle me-2"></i> <?= $msg ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>
                <?php if ($err): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fas fa-exclamation-triangle me-2"></i> <?= $err ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <div class="card rounded-0 shadow-sm border">
                    <div class="form-header">Form Ganti Password</div>
                    <div class="card-body p-0">
                        <form method="POST">
                            <table class="table table-bordered mb-0 form-table">
                                <tr>
                                    <th>Password Lama</th>
                                    <td><input type="password" name="pass_lama" class="form-control form-control-sm" required></td>
                                </tr>
                                <tr>
                                    <th>Password Baru</th>
                                    <td><input type="password" name="pass_baru" class="form-control form-control-sm" required></td>
                                </tr>
                                <tr>
                                    <th>Ulangi Password Baru</th>
                                    <td><input type="password" name="pass_ulangi" class="form-control form-control-sm" required></td>
                                </tr>
                            </table>
                            <div class="p-3 bg-light border-top text-start">
                                <button type="submit" class="btn btn-primary btn-sm px-4">Simpan Perubahan</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <?php include "sidebar.php"; ?>
        </div>
    </div>
</div>
<?php include "../footer.php"; ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/js/all.min.js"></script>
<script src="../assets/js/script.js"></script>
</body>
</html>