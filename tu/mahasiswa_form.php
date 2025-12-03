<?php
require_once "../config/auth.php";
require_once "../config/database.php";

$auth = new Auth();
if (!$auth->isLoggedIn() || ($_SESSION['user']['role'] ?? '') !== 'tu') {
    header("Location: ../login.php");
    exit;
}

$id = $_GET['id'] ?? null;
$is_edit = !empty($id);
$msg = '';
$err = '';

$nim = ''; $nama = ''; $jk = ''; $prodi = ''; $semester = 1; $email = ''; $dpa_id = ''; $foto_db = '';

if ($is_edit) {
    $stmt = $pdo->prepare("SELECT * FROM mahasiswa WHERE id = ?");
    $stmt->execute([$id]);
    $data = $stmt->fetch();
    if ($data) {
        $nim = $data['nim'];
        $nama = $data['nama'];
        $jk = $data['jenis_kelamin'];
        $prodi = $data['prodi'];
        $semester = $data['semester'];
        $email = $data['email'];
        $dpa_id = $data['dpa_id'];
        $foto_db = $data['foto'];
    } else {
        header("Location: mahasiswa.php"); exit;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nim_in = trim($_POST['nim']);
    $nama_in = trim($_POST['nama']);
    $jk_in = $_POST['jenis_kelamin'] ?? null;
    $prodi_in = $_POST['prodi'];
    $sem_in = $_POST['semester'];
    $email_in = trim($_POST['email']);
    $dpa_in = $_POST['dpa_id'] ?: null;
    $password_in = $_POST['password'];

    if (!$nim_in || !$nama_in || !$prodi_in || !$jk_in) {
        $err = "NIM, Nama, Jenis Kelamin, dan Prodi wajib diisi.";
    } else {
        try {
            $foto_nama = $foto_db; 
            if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
                $file_tmp = $_FILES['foto']['tmp_name'];
                $file_name = $_FILES['foto']['name'];
                $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
                
                if (!in_array($file_ext, ['jpg', 'jpeg', 'png'])) throw new Exception("Format foto harus JPG/PNG.");
                if ($_FILES['foto']['size'] > 2 * 1024 * 1024) throw new Exception("Ukuran foto maksimal 2MB.");

                $new_name = $nim_in . '_' . time() . '.' . $file_ext;
                $upload_dir = "../assets/img/uploads/";
                if (!is_dir($upload_dir)) mkdir($upload_dir, 0777, true);

                if (move_uploaded_file($file_tmp, $upload_dir . $new_name)) {
                    if ($is_edit && $foto_db && file_exists($upload_dir . $foto_db)) unlink($upload_dir . $foto_db);
                    $foto_nama = $new_name;
                }
            }

            if ($is_edit) {
                $sql = "UPDATE mahasiswa SET nim=?, nama=?, jenis_kelamin=?, prodi=?, semester=?, email=?, dpa_id=?, foto=? WHERE id=?";
                $params = [$nim_in, $nama_in, $jk_in, $prodi_in, $sem_in, $email_in, $dpa_in, $foto_nama, $id];
                $stmtUpd = $pdo->prepare($sql);
                $stmtUpd->execute($params);

                if (!empty($password_in)) {
                    $hash = password_hash($password_in, PASSWORD_DEFAULT);
                    $pdo->prepare("UPDATE mahasiswa SET password=? WHERE id=?")->execute([$hash, $id]);
                }
                header("Location: mahasiswa.php?msg=updated"); exit;

            } else {
                $cek = $pdo->prepare("SELECT id FROM mahasiswa WHERE nim = ?");
                $cek->execute([$nim_in]);
                if ($cek->rowCount() > 0) {
                    $err = "NIM $nim_in sudah terdaftar.";
                } else {
                    $pass_plain = !empty($password_in) ? $password_in : $nim_in;
                    $hash = password_hash($pass_plain, PASSWORD_DEFAULT);
                    $sql = "INSERT INTO mahasiswa (nim, nama, jenis_kelamin, password, prodi, semester, email, dpa_id, foto) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
                    $stmtIns = $pdo->prepare($sql);
                    $stmtIns->execute([$nim_in, $nama_in, $jk_in, $hash, $prodi_in, $sem_in, $email_in, $dpa_in, $foto_nama]);
                    header("Location: mahasiswa.php?msg=created"); exit;
                }
            }
        } catch (Exception $e) { $err = "Error: " . $e->getMessage(); }
    }
}

$listDosen = $pdo->query("SELECT id, nidn, nama FROM dosen WHERE jabatan = 'Dosen' ORDER BY nama ASC")->fetchAll();

$listProdi = $pdo->query("SELECT * FROM program_studi ORDER BY nama_prodi ASC")->fetchAll();$fotoPath = "../assets/img/uploads/" . ($foto_db ?? 'default.png');
$fotoPreview = ($is_edit && file_exists($fotoPath) && !empty($foto_db)) ? $fotoPath : "https://via.placeholder.com/150x180.png?text=NO+IMG";
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title><?= $is_edit ? 'Edit' : 'Tambah' ?> Mahasiswa | TU</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
<?php include "../header.php"; ?>
<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><?= $is_edit ? 'Edit Data Mahasiswa' : 'Tambah Mahasiswa Baru' ?></h5>
                </div>
                <div class="card-body">
                    <?php if ($err): ?><div class="alert alert-danger"><?= $err ?></div><?php endif; ?>

                    <form method="POST" enctype="multipart/form-data">
                        <div class="row">
                            <div class="col-md-3 text-center mb-3">
                                <label class="form-label fw-bold">Foto Profil</label>
                                <div class="card p-1 mb-2 mx-auto" style="width: 150px;">
                                    <img src="<?= $fotoPreview ?>" alt="Preview" class="img-fluid" style="height: 180px; object-fit: cover;">
                                </div>
                                <input type="file" name="foto" class="form-control form-control-sm mt-2" accept="image/png, image/jpeg, image/jpg">
                            </div>

                            <div class="col-md-9">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">NIM <span class="text-danger">*</span></label>
                                        <input type="text" name="nim" class="form-control" value="<?= htmlspecialchars($nim) ?>" required <?= $is_edit ? 'readonly class="form-control bg-light"' : '' ?>>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                                        <input type="text" name="nama" class="form-control" value="<?= htmlspecialchars($nama) ?>" required>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Jenis Kelamin <span class="text-danger">*</span></label>
                                        <select name="jenis_kelamin" class="form-select" required>
                                            <option value="">-- Pilih --</option>
                                            <option value="L" <?= ($jk == 'L') ? 'selected' : '' ?>>Laki-laki</option>
                                            <option value="P" <?= ($jk == 'P') ? 'selected' : '' ?>>Perempuan</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Program Studi <span class="text-danger">*</span></label>
                                        <select name="prodi" class="form-select" required>
                                            <option value="">-- Pilih Prodi --</option>
                                            <?php foreach ($listProdi as $p): ?>
                                                <option value="<?= htmlspecialchars($p['nama_prodi']) ?>" <?= ($prodi == $p['nama_prodi']) ? 'selected' : '' ?>>
                                                    <?= htmlspecialchars($p['nama_prodi']) ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Semester</label>
                                        <input type="number" name="semester" class="form-control" min="1" max="14" value="<?= $semester ?>">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Email</label>
                                        <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($email) ?>">
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Dosen Wali (DPA)</label>
                                    <select name="dpa_id" class="form-select">
                                        <option value="">-- Pilih Dosen Wali (Non-Kaprodi) --</option>
                                        <?php foreach ($listDosen as $d): ?>
                                            <option value="<?= $d['id'] ?>" <?= ($dpa_id == $d['id']) ? 'selected' : '' ?>>
                                                <?= htmlspecialchars($d['nama']) ?> (<?= $d['nidn'] ?>)
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                    <div class="form-text">Dosen dengan jabatan Kaprodi tidak muncul di sini.</div>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Password</label>
                                    <input type="password" name="password" class="form-control" placeholder="<?= $is_edit ? 'Kosongkan jika tidak ubah password' : 'Default: Sama dengan NIM' ?>">
                                </div>

                                <div class="d-flex justify-content-end gap-2">
                                    <a href="mahasiswa.php" class="btn btn-secondary">Batal</a>
                                    <button type="submit" class="btn btn-primary">Simpan Data</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include "../footer.php"; ?>
</body>
</html>