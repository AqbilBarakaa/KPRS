<?php
// config/database.php
$host = "localhost";
$db   = "kprs";
$user = "root";
$pass = "";
$charset = "utf8mb4";

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (PDOException $e) {
    die("Koneksi database gagal: " . $e->getMessage());
}

if (!function_exists('kirimNotifikasi')) {
    function kirimNotifikasi($pdo, $user_id, $user_type, $judul, $pesan, $tipe = 'info', $link = '#', $sender_id = null, $sender_type = null) {
        $stmt = $pdo->prepare("INSERT INTO notifikasi (user_id, user_type, judul, pesan, tipe, link, sender_id, sender_type, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW())");
        $stmt->execute([$user_id, $user_type, $judul, $pesan, $tipe, $link, $sender_id, $sender_type]);
    }
}
?>