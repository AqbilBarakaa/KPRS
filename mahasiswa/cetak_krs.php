<?php
require_once "../config/auth.php";
require_once "../config/database.php";

$auth = new Auth();
if (!$auth->isLoggedIn() || ($_SESSION['user']['role'] ?? '') !== 'mahasiswa') {
    header("Location: ../login.php"); exit;
}

$user_id = $_SESSION['user']['id'];

$stmtMhs = $pdo->prepare("
    SELECT m.*, d.nama as nama_dpa, d.nidn as nidn_dpa, ps.nama_prodi 
    FROM mahasiswa m 
    LEFT JOIN dosen d ON m.dpa_id = d.id 
    LEFT JOIN program_studi ps ON m.prodi = ps.nama_prodi
    WHERE m.id = ?
");
$stmtMhs->execute([$user_id]);
$mhs = $stmtMhs->fetch();

$queryKRS = "
    SELECT ka.status as status_krs,
           k.nama_kelas, k.hari, k.jam_mulai, k.jam_selesai, k.ruangan,
           mk.kode_mk, mk.nama_mk, mk.sks, mk.semester
    FROM krs_awal ka
    JOIN kelas k ON ka.kelas_id = k.id
    JOIN mata_kuliah mk ON k.mata_kuliah_id = mk.id
    WHERE ka.mahasiswa_id = ?
    ORDER BY mk.semester ASC, mk.nama_mk ASC
";
$stmt = $pdo->prepare($queryKRS);
$stmt->execute([$user_id]);
$krsData = $stmt->fetchAll();

$totalSKS = 0;
foreach($krsData as $row) {
    $totalSKS += $row['sks'];
}

$bulanIndo = [
    1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April', 5 => 'Mei', 6 => 'Juni',
    7 => 'Juli', 8 => 'Agustus', 9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
];
$tglSekarang = date('d') . ' ' . $bulanIndo[(int)date('m')] . ' ' . date('Y');
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Cetak KRS - <?= htmlspecialchars($mhs['nim']) ?></title>
    <style>
        body {
            font-family: "Times New Roman", Times, serif;
            font-size: 12pt;
            margin: 0;
            padding: 20px;
            color: #000;
        }
        
        /* --- KOP SURAT --- */
        .header {
            border-bottom: 3px double #000;
            padding-bottom: 10px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
        }
        .logo {
            width: 80px;
            height: auto;
            position: absolute;
            left: 20px;
            top: 0;
        }
        .header-text {
            text-align: center;
            width: 100%;
        }
        .header-text h3 { margin: 0; font-size: 14pt; font-weight: normal; text-transform: uppercase; }
        .header-text h2 { margin: 5px 0; font-size: 16pt; font-weight: bold; text-transform: uppercase; }
        .header-text p { margin: 0; font-size: 10pt; font-style: italic; }

        /* Judul Halaman */
        .doc-title {
            text-align: center;
            font-size: 14pt;
            font-weight: bold;
            text-decoration: underline;
            margin: 20px 0;
            text-transform: uppercase;
        }

        /* Info Mahasiswa */
        .info-table { width: 100%; margin-bottom: 20px; font-size: 11pt; }
        .info-table td { padding: 3px; vertical-align: top; }
        .label { width: 120px; font-weight: bold; }
        .colon { width: 10px; }

        /* Tabel KRS */
        .krs-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
            font-size: 10pt; /* Font tabel sedikit diperkecil agar muat */
        }
        .krs-table th, .krs-table td {
            border: 1px solid #000;
            padding: 6px;
        }
        .krs-table th {
            background-color: #e0e0e0; /* Abu-abu muda untuk header */
            text-align: center;
            font-weight: bold;
            text-transform: uppercase;
        }
        .text-center { text-align: center; }
        .text-left { text-align: left; }
        
        /* Tanda Tangan */
        .signature-section {
            margin-top: 40px;
            width: 100%;
            display: table;
        }
        .sig-box {
            display: table-cell;
            width: 33%;
            text-align: center;
            vertical-align: top;
        }
        .sig-space { height: 70px; }
        .name-under { font-weight: bold; text-decoration: underline; }

        /* Tombol Cetak */
        .no-print {
            position: fixed; top: 20px; right: 20px;
            background: #007bff; color: white;
            padding: 10px 20px; border: none; border-radius: 5px;
            cursor: pointer; font-weight: bold; font-family: sans-serif;
        }
        .no-print:hover { background: #0056b3; }

        @media print {
            .no-print { display: none; }
            @page { size: A4; margin: 1.5cm; }
        }
    </style>
</head>
<body>

    <button class="no-print" onclick="window.print()">Cetak Dokumen</button>

    <div class="doc-title">KARTU RENCANA STUDI (KRS)</div>

    <table class="info-table">
        <tr>
            <td class="label">Nama</td><td class="colon">:</td>
            <td><?= strtoupper(htmlspecialchars($mhs['nama'])) ?></td>
            
            <td class="label">Tahun Ajaran</td><td class="colon">:</td>
            <td>2025/2026 Ganjil</td>
        </tr>
        <tr>
            <td class="label">NIM</td><td class="colon">:</td>
            <td><?= htmlspecialchars($mhs['nim']) ?></td>
            
            <td class="label">Semester</td><td class="colon">:</td>
            <td><?= htmlspecialchars($mhs['semester']) ?></td>
        </tr>
        <tr>
            <td class="label">Program Studi</td><td class="colon">:</td>
            <td><?= htmlspecialchars($mhs['prodi']) ?></td>
            
            <td class="label">Dosen Wali</td><td class="colon">:</td>
            <td><?= htmlspecialchars($mhs['nama_dpa']) ?></td>
        </tr>
    </table>

    <table class="krs-table">
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="12%">Kode MK</th>
                <th width="30%">Mata Kuliah</th>
                <th width="5%">SKS</th>
                <th width="8%">Kelas</th>
                <th width="10%">Hari</th>
                <th width="12%">Jam</th>
                <th width="8%">Ruang</th>
                <th width="10%">Ket</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($krsData)): ?>
                <tr>
                    <td colspan="9" class="text-center" style="padding: 20px;">
                        <em>-- Belum ada mata kuliah yang diambil --</em>
                    </td>
                </tr>
            <?php else: ?>
                <?php $no=1; foreach ($krsData as $row): ?>
                <tr>
                    <td class="text-center"><?= $no++ ?></td>
                    <td class="text-center"><?= htmlspecialchars($row['kode_mk']) ?></td>
                    <td class="text-left"><?= htmlspecialchars($row['nama_mk']) ?></td>
                    <td class="text-center"><?= $row['sks'] ?></td>
                    <td class="text-center"><?= htmlspecialchars($row['nama_kelas']) ?></td>
                    <td class="text-center"><?= $row['hari'] ?></td>
                    <td class="text-center"><?= substr($row['jam_mulai'],0,5) ?>-<?= substr($row['jam_selesai'],0,5) ?></td>
                    <td class="text-center"><?= htmlspecialchars($row['ruangan'] ?? '-') ?></td>
                    <td class="text-center">
                        <?= ($row['status_krs'] == 'terdaftar') ? 'Baru' : 'Valid' ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
        <tfoot>
            <tr>
                <th colspan="3" style="text-align: right; padding-right: 10px;">Total SKS</th>
                <th class="text-center"><?= $totalSKS ?></th>
                <th colspan="5"></th>
            </tr>
        </tfoot>
    </table>

    <div class="signature-section">
        <div class="sig-box">
            Menyetujui,<br>
            Dosen Wali
            <div class="sig-space"></div>
            <div class="name-under"><?= htmlspecialchars($mhs['nama_dpa'] ?? '..........................') ?></div>
            <div>NIP/NIDN. <?= htmlspecialchars($mhs['nidn_dpa'] ?? '................') ?></div>
        </div>
        
        <div class="sig-box"></div> <div class="sig-box">
            Bangkalan, <?= $tglSekarang ?><br>
            Mahasiswa
            <div class="sig-space"></div>
            <div class="name-under"><?= htmlspecialchars($mhs['nama']) ?></div>
            <div>NIM. <?= htmlspecialchars($mhs['nim']) ?></div>
        </div>
    </div>

</body>
</html>