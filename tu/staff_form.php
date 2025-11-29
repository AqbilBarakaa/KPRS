<?php
// tu/staff_form.php
require_once "../config/auth.php";
require_once "../config/database.php";

$auth = new Auth();
if (!$auth->isLoggedIn() || ($_SESSION['user']['role'] ?? '') !== 'tu') { header("Location: ../login.php"); exit; }

$id = $_GET['id'] ?? null;
$is_edit = !empty($id);
$nip=''; $nama=''; $email=''; $foto_db=''; $err='';

if ($is_edit) {
    $stmt = $pdo->prepare("SELECT * FROM tata_usaha WHERE id=?");
    $stmt->execute([$id]);
    $d = $stmt->fetch();
    if($d){ $nip=$d['nip']; $nama=$d['nama']; $email=$d['email']; $foto_db=$d['foto']; }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nip = trim($_POST['nip']);
    $nama = trim($_POST['nama']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    if (!$nip || !$nama) {
        $err = "NIP dan Nama wajib diisi.";
    } else {
        try {
            // Upload Foto
            $foto_nama = $foto_db;
            if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
                $ext = strtolower(pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION));
                if(in_array($ext, ['jpg','jpeg','png'])) {
                    $new_name = "TU_" . $nip . "_" . time() . "." . $ext;
                    if(move_uploaded_file($_FILES['foto']['tmp_name'], "../assets/img/uploads/" . $new_name)){
                        if($is_edit && $foto_db && file_exists("../assets/img/uploads/".$foto_db)) unlink("../assets/img/uploads/".$foto_db);
                        $foto_nama = $new_name;
                    }
                }
            }

            if ($is_edit) {
                // UPDATE (Tanpa kolom role)
                $sql = "UPDATE tata_usaha SET nip=?, nama=?, email=?, foto=? WHERE id=?";
                $pdo->prepare($sql)->execute([$nip, $nama, $email, $foto_nama, $id]);
                if (!empty($password)) {
                    $hash = password_hash($password, PASSWORD_DEFAULT);
                    $pdo->prepare("UPDATE tata_usaha SET password=? WHERE id=?")->execute([$hash, $id]);
                }
            } else {
                // INSERT (Tanpa kolom role)
                $cek = $pdo->prepare("SELECT id FROM tata_usaha WHERE nip=?");
                $cek->execute([$nip]);
                if ($cek->rowCount() > 0) throw new Exception("NIP sudah terdaftar.");
                
                $pass = password_hash($password ?: $nip, PASSWORD_DEFAULT);
                $sql = "INSERT INTO tata_usaha (nip, nama, password, email, foto) VALUES (?,?,?,?,?)";
                $pdo->prepare($sql)->execute([$nip, $nama, $pass, $email, $foto_nama]);
            }
            header("Location: staff.php"); exit;
        } catch(Exception $e) { $err = $e->getMessage(); }
    }
}
$fotoPreview = ($is_edit && !empty($foto_db) && file_exists("../assets/img/uploads/$foto_db")) ? "../assets/img/uploads/$foto_db" : "https://via.placeholder.com/150x180.png?text=FOTO";
?>
<!DOCTYPE html>
<html lang="id">
<head><meta charset="utf-8"><title>Form Staff TU</title><link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet"><link rel="stylesheet" href="../assets/css/style.css"></head>
<body>
<?php include "header.php"; ?>
<div class="container mt-4"><div class="row justify-content-center"><div class="col-md-8"><div class="card shadow">
    <div class="card-header bg-primary text-white">Form Staff Tata Usaha</div>
    <div class="card-body">
        <?php if($err) echo "<div class='alert alert-danger'>$err</div>"; ?>
        <form method="POST" enctype="multipart/form-data">
            <div class="row">
                <div class="col-md-4 text-center mb-3">
                    <img src="<?= $fotoPreview ?>" class="img-thumbnail mb-2" style="height:150px">
                    <input type="file" name="foto" class="form-control form-control-sm">
                </div>
                <div class="col-md-8">
                    <div class="mb-3"><label>NIP</label><input type="text" name="nip" class="form-control" value="<?= htmlspecialchars($nip) ?>" required></div>
                    <div class="mb-3"><label>Nama Lengkap</label><input type="text" name="nama" class="form-control" value="<?= htmlspecialchars($nama) ?>" required></div>
                    
                    <div class="mb-3"><label>Email</label><input type="email" name="email" class="form-control" value="<?= htmlspecialchars($email) ?>"></div>
                    
                    <div class="mb-3"><label>Password</label><input type="password" name="password" class="form-control" placeholder="<?= $is_edit?'Kosongkan jika tidak ubah':'Default: sama dengan NIP' ?>"></div>
                    <div class="text-end"><a href="staff.php" class="btn btn-secondary">Batal</a> <button class="btn btn-primary">Simpan</button></div>
                </div>
            </div>
        </form>
    </div>
</div></div></div></div>
</body></html>