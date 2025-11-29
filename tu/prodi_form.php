<?php
// tu/prodi_form.php
require_once "../config/auth.php";
require_once "../config/database.php";

$auth = new Auth();
if (!$auth->isLoggedIn() || ($_SESSION['user']['role'] ?? '') !== 'tu') { header("Location: ../login.php"); exit; }

$id = $_GET['id'] ?? null;
$is_edit = !empty($id);
$kode = ''; $nama = ''; $kaprodi_id = ''; $err = '';

// Load Data Edit
if ($is_edit) {
    $stmt = $pdo->prepare("SELECT * FROM program_studi WHERE id = ?");
    $stmt->execute([$id]);
    $data = $stmt->fetch();
    if ($data) {
        $kode = $data['kode_prodi'];
        $nama = $data['nama_prodi'];
        $kaprodi_id = $data['kaprodi_id'];
    }
}

// Proses Simpan
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $kode = strtoupper(trim($_POST['kode']));
    $nama = trim($_POST['nama']);
    $kaprodi = !empty($_POST['kaprodi_id']) ? $_POST['kaprodi_id'] : null;

    if (empty($kode) || empty($nama)) {
        $err = "Kode dan Nama Prodi wajib diisi.";
    } else {
        try {
            if ($is_edit) {
                $sql = "UPDATE program_studi SET kode_prodi=?, nama_prodi=?, kaprodi_id=? WHERE id=?";
                $pdo->prepare($sql)->execute([$kode, $nama, $kaprodi, $id]);
            } else {
                // Cek Kode Unik
                $cek = $pdo->prepare("SELECT id FROM program_studi WHERE kode_prodi=?");
                $cek->execute([$kode]);
                if ($cek->rowCount() > 0) throw new Exception("Kode Prodi '$kode' sudah ada.");

                $sql = "INSERT INTO program_studi (kode_prodi, nama_prodi, kaprodi_id) VALUES (?,?,?)";
                $pdo->prepare($sql)->execute([$kode, $nama, $kaprodi]);
            }
            header("Location: prodi.php"); exit;
        } catch (Exception $e) { $err = "Error: " . $e->getMessage(); }
    }
}

// Ambil Daftar Dosen untuk Pilihan Kaprodi
// Kita ambil semua dosen, atau bisa difilter hanya yang jabatannya 'Kaprodi' jika konsisten
$listDosen = $pdo->query("SELECT id, nidn, nama FROM dosen ORDER BY nama ASC")->fetchAll();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8"><title>Form Prodi | TU</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
<?php include "header.php"; ?>
<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">Form Program Studi</div>
                <div class="card-body">
                    <?php if ($err) echo "<div class='alert alert-danger'>$err</div>"; ?>
                    
                    <form method="POST">
                        <div class="mb-3">
                            <label class="form-label">Kode Prodi</label>
                            <input type="text" name="kode" class="form-control" value="<?= htmlspecialchars($kode) ?>" placeholder="Contoh: TI, SI" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Nama Program Studi</label>
                            <input type="text" name="nama" class="form-control" value="<?= htmlspecialchars($nama) ?>" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Kepala Program Studi (Kaprodi)</label>
                            <select name="kaprodi_id" class="form-select">
                                <option value="">-- Pilih Dosen --</option>
                                <?php foreach($listDosen as $d): ?>
                                    <option value="<?= $d['id'] ?>" <?= ($kaprodi_id == $d['id']) ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($d['nama']) ?> (<?= $d['nidn'] ?>)
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <div class="form-text">Opsional. Pilih dosen yang menjabat sebagai Kaprodi.</div>
                        </div>
                        
                        <div class="text-end">
                            <a href="prodi.php" class="btn btn-secondary">Batal</a>
                            <button class="btn btn-primary">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
</body></html>