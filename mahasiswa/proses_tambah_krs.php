<?php
require_once "../config/auth.php";
require_once "../config/database.php";

$auth = new Auth();
if (!$auth->isLoggedIn() || ($_SESSION['user']['role'] ?? '') !== 'mahasiswa') {
    header("Location: ../login.php"); exit;
}
$user_id = $_SESSION['user']['id'];

$berhasil = [];
$gagal = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['kelas_id'])) {
    $kelas_ids = $_POST['kelas_id'];
    $stmtMhs = $pdo->prepare("SELECT ips, semester FROM mahasiswa WHERE id = ?");
    $stmtMhs->execute([$user_id]);
    $mhs = $stmtMhs->fetch();
    
    $ips = $mhs['ips'];
    $semester_mhs = $mhs['semester'];
    
    $max_sks = 20; 
    if ($semester_mhs > 1) {
        if ($ips >= 3.00) $max_sks = 24;
        elseif ($ips >= 2.50) $max_sks = 21;
        elseif ($ips >= 2.00) $max_sks = 18;
        else $max_sks = 15;
    }

    $queryExisting = "
        SELECT k.id as kelas_id, k.hari, k.jam_mulai, k.jam_selesai, 
               mk.sks, mk.nama_mk
        FROM krs_awal ka 
        JOIN kelas k ON ka.kelas_id = k.id 
        JOIN mata_kuliah mk ON k.mata_kuliah_id = mk.id 
        WHERE ka.mahasiswa_id = ? AND ka.status != 'ditolak'
    ";
    $stmtEx = $pdo->prepare($queryExisting);
    $stmtEx->execute([$user_id]);
    $existingKRS = $stmtEx->fetchAll();

    $current_sks = 0;
    $jadwal_diambil = []; 
    foreach ($existingKRS as $ex) {
        $current_sks += $ex['sks'];
        $jadwal_diambil[] = [
            'hari' => $ex['hari'],
            'mulai' => $ex['jam_mulai'],
            'selesai' => $ex['jam_selesai'],
            'nama' => $ex['nama_mk']
        ];
    }

    $queryLulus = "
        SELECT mk.kode_mk, mk.sks 
        FROM krs_awal ka
        JOIN kelas k ON ka.kelas_id = k.id
        JOIN mata_kuliah mk ON k.mata_kuliah_id = mk.id
        WHERE ka.mahasiswa_id = ? AND ka.status = 'selesai'
    ";
    $stmtLulus = $pdo->prepare($queryLulus);
    $stmtLulus->execute([$user_id]);
    $mkLulus = $stmtLulus->fetchAll(PDO::FETCH_COLUMN, 0); 
    
    $stmtTotalLulus = $pdo->prepare("SELECT SUM(mk.sks) FROM krs_awal ka JOIN kelas k ON ka.kelas_id = k.id JOIN mata_kuliah mk ON k.mata_kuliah_id = mk.id WHERE ka.mahasiswa_id = ? AND ka.status = 'selesai'");
    $stmtTotalLulus->execute([$user_id]);
    $total_sks_lulus = $stmtTotalLulus->fetchColumn() ?: 0;
    
    try {
        $pdo->beginTransaction();

        foreach ($kelas_ids as $kid) {
            $stmtClass = $pdo->prepare("
                SELECT k.id, k.nama_kelas, k.kuota, k.terisi, k.hari, k.jam_mulai, k.jam_selesai,
                       mk.kode_mk, mk.nama_mk, mk.sks, mk.prasyarat
                FROM kelas k
                JOIN mata_kuliah mk ON k.mata_kuliah_id = mk.id
                WHERE k.id = ? FOR UPDATE
            ");
            $stmtClass->execute([$kid]);
            $kelas = $stmtClass->fetch();

            if (!$kelas) continue;

            $nama_mk = $kelas['nama_mk'];
            $kode_mk = $kelas['kode_mk'];
            $nm_kelas = $kelas['nama_kelas'];

            $isTaken = false;
            foreach($existingKRS as $exItem) {
                if($exItem['kelas_id'] == $kid) $isTaken = true;
            }
            if ($isTaken) continue; 

            if (($current_sks + $kelas['sks']) > $max_sks) {
                $gagal[] = [
                    'kode' => $kode_mk, 'matkul' => $nama_mk, 'kelas' => $nm_kelas,
                    'alasan' => "Melebihi batas SKS (Max $max_sks, Total akan menjadi " . ($current_sks + $kelas['sks']) . ")"
                ];
                continue;
            }

            $prasyarat_ok = true;
            $alasan_prasyarat = "";

            if (!empty($kelas['prasyarat'])) {
                $syaratArr = array_map('trim', explode(',', $kelas['prasyarat']));
                foreach ($syaratArr as $s) {
                    if (preg_match('/^(\d+)\s*(SKS|sks)$/i', $s, $matches)) {
                        $minSksReq = intval($matches[1]);
                        if ($total_sks_lulus < $minSksReq) {
                            $prasyarat_ok = false; 
                            $alasan_prasyarat = "Total SKS Lulus belum cukup (Butuh $minSksReq, Punya $total_sks_lulus)";
                        }
                    } else {
                        $isCoReq = (substr($s, -1) === '*');
                        $kodeSyarat = rtrim($s, '*');
                        
                        if ($isCoReq) {
                            $lulus = in_array($kodeSyarat, $mkLulus);
                            if (!$lulus) {
                                $prasyarat_ok = false; $alasan_prasyarat = "Belum lulus/ambil MK Prasyarat: $kodeSyarat";
                            }
                        } else {
                            if (!in_array($kodeSyarat, $mkLulus)) {
                                $prasyarat_ok = false; $alasan_prasyarat = "Belum lulus MK Prasyarat: $kodeSyarat";
                            }
                        }
                    }
                }
            }

            if (!$prasyarat_ok) {
                $gagal[] = ['kode' => $kode_mk, 'matkul' => $nama_mk, 'kelas' => $nm_kelas, 'alasan' => $alasan_prasyarat];
                continue;
            }

            if ($kelas['terisi'] >= $kelas['kuota']) {
                $gagal[] = ['kode' => $kode_mk, 'matkul' => $nama_mk, 'kelas' => $nm_kelas, 'alasan' => "Kuota Penuh ({$kelas['terisi']}/{$kelas['kuota']})"];
                continue;
            }

            $isBentrok = false;
            $bentrokWith = "";
            
            $startNew = strtotime($kelas['jam_mulai']);
            $endNew = strtotime($kelas['jam_selesai']);
            $hariNew = $kelas['hari'];

            foreach ($jadwal_diambil as $j) {
                if ($j['hari'] == $hariNew) {
                    $startExist = strtotime($j['mulai']);
                    $endExist = strtotime($j['selesai']);

                    if ($startNew < $endExist && $startExist < $endNew) {
                        $isBentrok = true;
                        $bentrokWith = $j['nama'] . " (" . substr($j['mulai'],0,5) . "-" . substr($j['selesai'],0,5) . ")";
                        break;
                    }
                }
            }

            if ($isBentrok) {
                $gagal[] = ['kode' => $kode_mk, 'matkul' => $nama_mk, 'kelas' => $nm_kelas, 'alasan' => "Jadwal Bentrok dengan $bentrokWith"];
                continue;
            }

            $pdo->prepare("INSERT INTO krs_awal (mahasiswa_id, kelas_id, status) VALUES (?, ?, 'draft')")->execute([$user_id, $kid]);
            $pdo->prepare("UPDATE kelas SET terisi = terisi + 1 WHERE id = ?")->execute([$kid]);
            
            $current_sks += $kelas['sks'];
            $jadwal_diambil[] = [
                'hari' => $kelas['hari'],
                'mulai' => $kelas['jam_mulai'],
                'selesai' => $kelas['jam_selesai'],
                'nama' => $nama_mk
            ];

            $berhasil[] = [
                'kode' => $kode_mk, 
                'matkul' => $nama_mk, 
                'kelas' => $nm_kelas, 
                'sks' => $kelas['sks'], 
                'ket' => 'Berhasil ditambahkan'
            ];
        }
        
        $pdo->commit();

    } catch (Exception $e) {
        $pdo->rollBack();
        die("Error: " . $e->getMessage());
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8"><title>Hasil KRS | Portal Akademik</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
<?php include "../header.php"; ?>
<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="content-box">
                <div class="msg-header mb-4 text-center">Laporan Pengambilan Mata Kuliah</div>
                
                <?php if (empty($berhasil) && empty($gagal)): ?>
                    <div class="alert alert-warning text-center">Tidak ada mata kuliah yang diproses.</div>
                <?php endif; ?>

                <?php if (!empty($berhasil)): ?>
                <div class="card mb-4 border-success">
                    <div class="card-header bg-success text-white fw-bold"><i class="fas fa-check-circle me-2"></i> Berhasil Diambil</div>
                    <div class="card-body p-0">
                        <table class="table table-sm mb-0 table-striped">
                            <?php foreach($berhasil as $b): ?>
                            <tr>
                                <td width="15%" class="ps-3 fw-bold"><?= $b['kode'] ?></td>
                                <td><?= $b['matkul'] ?> (<?= $b['kelas'] ?>)</td>
                                <td width="10%" class="text-center"><?= $b['sks'] ?> SKS</td>
                                <td width="20%" class="text-end pe-3 text-success small fw-bold">OK</td>
                            </tr>
                            <?php endforeach; ?>
                        </table>
                    </div>
                </div>
                <?php endif; ?>

                <?php if (!empty($gagal)): ?>
                <div class="card mb-4 border-danger">
                    <div class="card-header bg-danger text-white fw-bold"><i class="fas fa-times-circle me-2"></i> Gagal Diambil</div>
                    <div class="card-body p-0">
                        <table class="table table-sm mb-0 table-striped">
                            <?php foreach($gagal as $g): ?>
                            <tr>
                                <td width="15%" class="ps-3 fw-bold"><?= $g['kode'] ?></td>
                                <td><?= $g['matkul'] ?> (<?= $g['kelas'] ?>)</td>
                                <td class="text-end pe-3 text-danger small fw-bold"><?= $g['alasan'] ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </table>
                    </div>
                    <div class="card-footer bg-light text-muted small">
                        <em>* Jika gagal karena kuota penuh, Anda dapat mengajukan penambahan kelas di menu "Pengajuan Tambah Kelas".</em>
                    </div>
                </div>
                <?php endif; ?>

                <div class="text-center mt-4">
                    <a href="krs.php" class="btn btn-primary px-4"><i class="fas fa-check me-1"></i> Selesai & Lihat KRS</a>
                    <a href="tambah_krs.php" class="btn btn-outline-secondary px-4 ms-2"><i class="fas fa-plus me-1"></i> Ambil Lagi</a>
                </div>
            </div>
        </div>
    </div>
</div>
</body></html>