<?php
require_once "../config/auth.php";
require_once "../config/database.php";

$auth = new Auth();
if (!$auth->isLoggedIn() || ($_SESSION['user']['role'] ?? '') !== 'tu') { header("Location: ../login.php"); exit; }

$id = $_GET['id'] ?? null;
$is_edit = !empty($id);

$kode = ''; $nama = ''; $sks = 2; $sem = 1; $prodi_id = ''; $prasyarat = ''; $sifat = 'Wajib'; 
$min_lulus = 'C'; 
$err='';

if ($is_edit) {
    $r = $pdo->prepare("SELECT * FROM mata_kuliah WHERE id=?"); $r->execute([$id]); $d=$r->fetch();
    if($d) { 
        $kode=$d['kode_mk']; $nama=$d['nama_mk']; $sks=$d['sks']; 
        $sem=$d['semester']; $prodi_id=$d['prodi_id']; 
        $prasyarat=$d['prasyarat']; $sifat=$d['sifat']; 
        $min_lulus = $d['minimal_kelulusan'];
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $kode = strtoupper(trim($_POST['kode'])); 
    $nama = trim($_POST['nama']);
    $sks = (int) $_POST['sks'];
    $sem = (int) $_POST['semester'];
    $prodi = $_POST['prodi_id'];
    $syarat = trim($_POST['prasyarat']); 
    $sifat = $_POST['sifat'];
    $min_lulus = $_POST['min_lulus']; 

    $prefixIF = substr($kode, 0, 4);
    $prefixUNG = substr($kode, 0, 3);

    if (empty($kode) || $kode === '0') {
        $err = "Kode MK tidak boleh kosong.";
    } elseif ($prefixIF !== 'IF22' && $prefixUNG !== 'UNG') {
        $err = "Kode MK harus diawali dengan <b>IF22</b> atau <b>UNG</b>.";
    } elseif (empty($nama)) {
        $err = "Nama Mata Kuliah tidak valid.";
    } elseif ($sks < 1 || $sks > 4) { 
        $err = "Jumlah SKS harus antara 1 sampai 4.";
    } else {
        $valid_prasyarat = true;
        $invalid_tokens = [];
        if (!empty($syarat)) {
            $tokens = array_map('trim', explode(',', $syarat));
            foreach ($tokens as $token) {
                if (preg_match('/^\d+\s*(SKS|sks)$/', $token)) continue; 
                $clean_code = strtoupper(rtrim($token, '*'));
                if ($clean_code == $kode) { $valid_prasyarat = false; $invalid_tokens[] = "Diri Sendiri"; continue; }
                $stmtCek = $pdo->prepare("SELECT id FROM mata_kuliah WHERE kode_mk = ?");
                $stmtCek->execute([$clean_code]);
                if ($stmtCek->rowCount() == 0) { $valid_prasyarat = false; $invalid_tokens[] = $token; }
            }
        }

        if (!$valid_prasyarat) {
            $err = "Prasyarat salah: <b>" . implode(", ", $invalid_tokens) . "</b>.";
        } else {
            try {
                if ($is_edit) {
                    $pdo->prepare("UPDATE mata_kuliah SET kode_mk=?, nama_mk=?, sks=?, semester=?, prodi_id=?, prasyarat=?, sifat=?, minimal_kelulusan=? WHERE id=?")
                        ->execute([$kode, $nama, $sks, $sem, $prodi, $syarat, $sifat, $min_lulus, $id]);
                } else {
                    $cek = $pdo->prepare("SELECT id FROM mata_kuliah WHERE kode_mk = ?");
                    $cek->execute([$kode]);
                    if ($cek->rowCount() > 0) throw new Exception("Kode MK sudah ada.");
                    
                    $pdo->prepare("INSERT INTO mata_kuliah (kode_mk, nama_mk, sks, semester, prodi_id, prasyarat, sifat, minimal_kelulusan) VALUES (?,?,?,?,?,?,?,?)")
                        ->execute([$kode, $nama, $sks, $sem, $prodi, $syarat, $sifat, $min_lulus]);
                }
                header("Location: matakuliah.php"); exit;
            } catch(Exception $e) { $err = "Gagal: " . $e->getMessage(); }
        }
    }
}
$listProdi = $pdo->query("SELECT * FROM program_studi")->fetchAll();
?>
<!DOCTYPE html>
<html lang="id">
<head><meta charset="utf-8"><title>Form MK</title><link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet"><link rel="stylesheet" href="../assets/css/style.css"></head>
<body>
<?php include "../header.php"; ?>
<div class="container mt-4"><div class="row justify-content-center"><div class="col-md-8"><div class="card shadow">
    <div class="card-header bg-primary text-white">Form Mata Kuliah</div>
    <div class="card-body">
        <?php if($err) echo "<div class='alert alert-danger'>$err</div>"; ?>
        <form method="POST">
            <div class="row mb-3">
                <div class="col-md-3"><label>Kode MK</label><input type="text" name="kode" class="form-control" value="<?= $kode ?>" placeholder="IF22xx" required></div>
                <div class="col-md-9"><label>Nama MK</label><input type="text" name="nama" class="form-control" value="<?= $nama ?>" required></div>
            </div>
            <div class="row mb-3">
                <div class="col-md-3"><label>Sifat</label>
                    <select name="sifat" class="form-select">
                        <option value="Wajib" <?= $sifat=='Wajib'?'selected':'' ?>>Wajib</option>
                        <option value="Pilihan" <?= $sifat=='Pilihan'?'selected':'' ?>>Pilihan</option>
                    </select>
                </div>
                
                <div class="col-md-2">
                    <label>SKS</label>
                    <input type="number" name="sks" class="form-control" value="<?= $sks ?>" min="1" max="4" required>
                </div>

                <div class="col-md-2"><label>Sem</label>
                    <select name="semester" class="form-select" required>
                        <?php for($i=1; $i<=8; $i++): ?>
                            <option value="<?= $i ?>" <?= $sem==$i ? 'selected' : '' ?>><?= $i ?></option>
                        <?php endfor; ?>
                    </select>
                </div>
                
                <div class="col-md-2">
                    <label>Min. Lulus</label>
                    <select name="min_lulus" class="form-select">
                        <?php foreach(['A','AB','B','BC','C','D','E'] as $grade): ?>
                            <option value="<?= $grade ?>" <?= $min_lulus==$grade?'selected':'' ?>><?= $grade ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="col-md-3"><label>Prodi</label>
                    <select name="prodi_id" class="form-select">
                        <?php foreach($listProdi as $p): ?><option value="<?= $p['id'] ?>" <?= $prodi_id==$p['id']?'selected':'' ?>><?= $p['nama_prodi'] ?></option><?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div class="mb-3">
                <label>Prasyarat</label>
                <input type="text" name="prasyarat" class="form-control" value="<?= htmlspecialchars($prasyarat) ?>" placeholder="Contoh: IF2214, 50 SKS, IF2226*">
            </div>
            <div class="text-end"><a href="matakuliah.php" class="btn btn-secondary">Batal</a> <button class="btn btn-primary">Simpan</button></div>
        </form>
    </div>
</div></div></div></div>
<?php include "../footer.php"; ?>
</body></html>