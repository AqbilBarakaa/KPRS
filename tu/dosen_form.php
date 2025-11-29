<?php
// tu/dosen_form.php
require_once "../config/auth.php";
require_once "../config/database.php";

$auth = new Auth();
if (!$auth->isLoggedIn() || ($_SESSION['user']['role'] ?? '') !== 'tu') {
    header("Location: ../login.php"); exit;
}

$id = $_GET['id'] ?? null;
$is_edit = !empty($id);
$nidn = ''; $nama = ''; $email = ''; $jabatan = 'Dosen'; $prodi = ''; $foto_db = ''; $err = '';

if ($is_edit) {
    $stmt = $pdo->prepare("SELECT * FROM dosen WHERE id = ?");
    $stmt->execute([$id]);
    $data = $stmt->fetch();
    if ($data) {
        $nidn = $data['nidn']; $nama = $data['nama']; $email = $data['email']; 
        $jabatan = $data['jabatan']; $prodi = $data['prodi']; $foto_db = $data['foto'];
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nidn = $_POST['nidn'];
    $nama = $_POST['nama'];
    $email = $_POST['email'];
    $jabatan = $_POST['jabatan'];
    $prodi = $_POST['prodi']; // Nilai ini berisi Nama Prodi (String)
    $password = $_POST['password'];

    if (!$nidn || !$nama) {
        $err = "NIDN dan Nama wajib diisi.";
    } else {
        try {
            // --- UPLOAD FOTO ---
            $foto_nama = $foto_db; 
            if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
                $file_tmp = $_FILES['foto']['tmp_name'];
                $file_name = $_FILES['foto']['name'];
                $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
                
                if (!in_array($file_ext, ['jpg', 'jpeg', 'png'])) throw new Exception("Format foto harus JPG/PNG.");
                if ($_FILES['foto']['size'] > 2 * 1024 * 1024) throw new Exception("Ukuran foto maksimal 2MB.");

                $new_name = "DOSEN_" . $nidn . "_" . time() . "." . $file_ext;
                $upload_dir = "../assets/img/uploads/";
                if (!is_dir($upload_dir)) mkdir($upload_dir, 0777, true);

                if (move_uploaded_file($file_tmp, $upload_dir . $new_name)) {
                    if ($is_edit && $foto_db && file_exists($upload_dir . $foto_db)) unlink($upload_dir . $foto_db);
                    $foto_nama = $new_name;
                }
            }

            if ($is_edit) {
                // Update
                $sql = "UPDATE dosen SET nidn=?, nama=?, email=?, jabatan=?, prodi=?, foto=? WHERE id=?";
                $pdo->prepare($sql)->execute([$nidn, $nama, $email, $jabatan, $prodi, $foto_nama, $id]);
                
                if (!empty($password)) {
                    $hash = password_hash($password, PASSWORD_DEFAULT);
                    $pdo->prepare("UPDATE dosen SET password=? WHERE id=?")->execute([$hash, $id]);
                }
            } else {
                // Insert
                $cek = $pdo->prepare("SELECT id FROM dosen WHERE nidn=?");
                $cek->execute([$nidn]);
                if ($cek->rowCount() > 0) {
                    $err = "NIDN $nidn sudah terdaftar.";
                } else {
                    $pass = password_hash($password ?: $nidn, PASSWORD_DEFAULT);
                    $sql = "INSERT INTO dosen (nidn, nama, password, email, jabatan, prodi, foto) VALUES (?,?,?,?,?,?,?)";
                    $pdo->prepare($sql)->execute([$nidn, $nama, $pass, $email, $jabatan, $prodi, $foto_nama]);
                }
            }
            if (!$err) { header("Location: dosen.php"); exit; }
        } catch (Exception $e) { $err = "Error: " . $e->getMessage(); }
    }
}

// Preview Foto
$fotoPath = "../assets/img/uploads/" . ($foto_db ?? 'default.png');
$fotoPreview = ($is_edit && file_exists($fotoPath) && !empty($foto_db)) ? $fotoPath : "https://via.placeholder.com/150x180.png?text=FOTO";

// --- AMBIL DATA PRODI UNTUK DROPDOWN ---
$listProdi = $pdo->query("SELECT * FROM program_studi ORDER BY nama_prodi ASC")->fetchAll();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Form Dosen | TU</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
<?php include "header.php"; ?>
<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">Form Data Dosen</div>
                <div class="card-body">
                    <?php if ($err) echo "<div class='alert alert-danger'>$err</div>"; ?>
                    <form method="POST" enctype="multipart/form-data">
                        <div class="row">
                            <div class="col-md-4 text-center mb-3">
                                <label class="form-label fw-bold">Foto Profil</label>
                                <div class="card p-1 mb-2 mx-auto" style="width: 150px;">
                                    <img src="<?= $fotoPreview ?>" alt="Preview" class="img-fluid" style="height: 180px; object-fit: cover;">
                                </div>
                                <input type="file" name="foto" class="form-control form-control-sm mt-2" accept="image/png, image/jpeg, image/jpg">
                                <div class="form-text text-muted small">Format: JPG/PNG, Max 2MB</div>
                            </div>

                            <div class="col-md-8">
                                <div class="mb-3"><label>NIDN</label><input type="text" name="nidn" class="form-control" value="<?= htmlspecialchars($nidn) ?>" required></div>
                                <div class="mb-3"><label>Nama Lengkap</label><input type="text" name="nama" class="form-control" value="<?= htmlspecialchars($nama) ?>" required></div>
                                <div class="mb-3"><label>Email</label><input type="email" name="email" class="form-control" value="<?= htmlspecialchars($email) ?>"></div>
                                <div class="row mb-3">
                                    
                                    <div class="col-md-6"><label>Prodi</label>
                                        <select name="prodi" class="form-select" required>
                                            <option value="">-- Pilih Prodi --</option>
                                            <?php foreach ($listProdi as $p): ?>
                                                <option value="<?= htmlspecialchars($p['nama_prodi']) ?>" <?= ($prodi == $p['nama_prodi']) ? 'selected' : '' ?>>
                                                    <?= htmlspecialchars($p['nama_prodi']) ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>

                                    <div class="col-md-6"><label>Jabatan</label>
                                        <select name="jabatan" class="form-select">
                                            <option value="Dosen" <?= $jabatan=='Dosen'?'selected':'' ?>>Dosen Biasa</option>
                                            <option value="Kaprodi" <?= $jabatan=='Kaprodi'?'selected':'' ?>>Kaprodi</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="mb-3"><label>Password</label><input type="password" name="password" class="form-control" placeholder="<?= $is_edit?'Kosongkan jika tidak ubah':'Default: sama dengan NIDN' ?>"></div>
                                
                                <div class="text-end">
                                    <a href="dosen.php" class="btn btn-secondary">Batal</a>
                                    <button class="btn btn-primary">Simpan</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>