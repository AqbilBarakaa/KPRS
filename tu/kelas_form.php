<?php
require_once "../config/auth.php";
require_once "../config/database.php";
$auth = new Auth();
if (!$auth->isLoggedIn() || ($_SESSION['user']['role'] ?? '') !== 'tu') { header("Location: ../login.php"); exit; }

$id = $_GET['id'] ?? null; $is_edit = !empty($id);
$mk_id=''; $nm_kls=''; $dos_id=''; $hari=''; $j_mulai=''; $j_selesai=''; $kuota=40; 
$ruangan=''; 
$err='';

if ($is_edit) {
    $d = $pdo->prepare("SELECT * FROM kelas WHERE id=?"); $d->execute([$id]); $r=$d->fetch();
    if($r){ 
        $mk_id=$r['mata_kuliah_id']; $nm_kls=$r['nama_kelas']; $dos_id=$r['dosen_pengampu_id']; 
        $hari=$r['hari']; $j_mulai=$r['jam_mulai']; $j_selesai=$r['jam_selesai']; 
        $kuota=$r['kuota']; $ruangan=$r['ruangan']; 
    }
}

if($_SERVER['REQUEST_METHOD']=='POST'){
    $mk=$_POST['mk']; $nm=$_POST['nm']; $dos=$_POST['dos']; $hr=$_POST['hr']; 
    $jm=$_POST['jm']; $js=$_POST['js']; $k=$_POST['k']; $rng=$_POST['rng']; 

    try {
        if($is_edit) {
            $pdo->prepare("UPDATE kelas SET mata_kuliah_id=?, nama_kelas=?, dosen_pengampu_id=?, hari=?, jam_mulai=?, jam_selesai=?, kuota=?, ruangan=? WHERE id=?")
                ->execute([$mk,$nm,$dos,$hr,$jm,$js,$k,$rng,$id]);
        } else {
            $pdo->prepare("INSERT INTO kelas (mata_kuliah_id, nama_kelas, dosen_pengampu_id, hari, jam_mulai, jam_selesai, kuota, ruangan) VALUES (?,?,?,?,?,?,?,?)")
                ->execute([$mk,$nm,$dos,$hr,$jm,$js,$k,$rng]);
        }
        header("Location: kelas.php"); exit;
    } catch(Exception $e){ $err=$e->getMessage(); }
}

$listMK = $pdo->query("SELECT * FROM mata_kuliah ORDER BY nama_mk")->fetchAll();
$listDosen = $pdo->query("SELECT * FROM dosen WHERE jabatan='Dosen' ORDER BY nama")->fetchAll();
$listHari = ['Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'];
?>
<!DOCTYPE html>
<html lang="id">
<head><meta charset="utf-8"><title>Form Kelas</title><link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet"><link rel="stylesheet" href="../assets/css/style.css"></head>
<body>
<?php include "../header.php"; ?>
<div class="container mt-4"><div class="row justify-content-center"><div class="col-md-8"><div class="card shadow">
    <div class="card-header bg-primary text-white">Form Jadwal Kelas</div>
    <div class="card-body">
        <?php if($err) echo "<div class='alert alert-danger'>$err</div>"; ?>
        <form method="POST">
            <div class="mb-3"><label>Mata Kuliah</label>
                <select name="mk" class="form-select" required>
                    <option value="">- Pilih Mata Kuliah -</option>
                    <?php foreach($listMK as $m): ?><option value="<?= $m['id'] ?>" <?= $mk_id==$m['id']?'selected':'' ?>><?= $m['nama_mk'] ?> (<?= $m['kode_mk'] ?>)</option><?php endforeach; ?>
                </select>
            </div>
            
            <div class="row mb-3">
                <div class="col-md-4"><label>Nama Kelas</label><input type="text" name="nm" class="form-control" value="<?= $nm_kls ?>" placeholder="A, B, C..." required></div>
                <div class="col-md-4"><label>Ruangan</label><input type="text" name="rng" class="form-control" value="<?= $ruangan ?>" placeholder="R. 201" required></div>
                <div class="col-md-4"><label>Kuota</label><input type="number" name="k" class="form-control" value="<?= $kuota ?>" required></div>
            </div>

            <div class="mb-3"><label>Dosen Pengajar</label>
                <select name="dos" class="form-select" required>
                    <option value="">- Pilih Dosen -</option>
                    <?php foreach($listDosen as $d): ?><option value="<?= $d['id'] ?>" <?= $dos_id==$d['id']?'selected':'' ?>><?= $d['nama'] ?></option><?php endforeach; ?>
                </select>
            </div>

            <div class="row mb-3">
                <div class="col-md-4"><label>Hari</label>
                    <select name="hr" class="form-select" required>
                        <?php foreach($listHari as $h): ?><option value="<?= $h ?>" <?= $hari==$h?'selected':'' ?>><?= $h ?></option><?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-4"><label>Jam Mulai</label><input type="time" name="jm" class="form-control" value="<?= $j_mulai ?>" required></div>
                <div class="col-md-4"><label>Jam Selesai</label><input type="time" name="js" class="form-control" value="<?= $j_selesai ?>" required></div>
            </div>
            <div class="text-end"><a href="kelas.php" class="btn btn-secondary">Batal</a> <button class="btn btn-primary">Simpan</button></div>
        </form>
    </div>
</div></div></div></div>
<?php include "../footer.php"; ?>
</body>
</html>