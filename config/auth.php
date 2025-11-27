<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

class Auth {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function isLoggedIn() {
        return isset($_SESSION['user']);
    }

    public function login($username, $password) {

        // 1️⃣ Cek Mahasiswa (NIM)
        $stmt = $this->pdo->prepare("SELECT * FROM mahasiswa WHERE nim = ?");
        $stmt->execute([$username]);
        $m = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($m && $m['password'] == $password) {
            $_SESSION['user'] = [
                "id" => $m['id'],
                "username" => $m['nim'],
                "nama" => $m['nama'],
                "role" => "mahasiswa"
            ];
            return true;
        }

        // 2️⃣ Cek Dosen (NIDN)
        $stmt = $this->pdo->prepare("SELECT * FROM dosen WHERE nidn = ?");
        $stmt->execute([$username]);
        $d = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($d && $d['password'] == $password) {

            // Tentukan role berdasarkan jabatan
            $jab = strtolower($d['jabatan']);
            if ($jab == "dpa") {
                $role = "dosen_dpa";
            } else if ($jab == "kaprodi") {
                $role = "dosen_kaprodi";
            } else {
                $role = "dosen";
            }

            $_SESSION['user'] = [
                "id" => $d['id'],
                "username" => $d['nidn'],
                "nama" => $d['nama'],
                "role" => $role
            ];
            return true;
        }

        // 3️⃣ Cek Tata Usaha (NIP)
        $stmt = $this->pdo->prepare("SELECT * FROM tata_usaha WHERE nip = ?");
        $stmt->execute([$username]);
        $tu = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($tu && $tu['password'] == $password) {
            $_SESSION['user'] = [
                "id" => $tu['id'],
                "username" => $tu['nip'],
                "nama" => $tu['nama'],
                "role" => "tu"
            ];
            return true;
        }

        return false;
    }

    public function redirectBasedOnRole() {
        $role = $_SESSION['user']['role'] ?? '';

        switch ($role) {

            case 'mahasiswa':
                header("Location: mahasiswa/dashboard.php");
                exit;

            case 'dosen_dpa':
                header("Location: dosen/dpa/dashboard.php");
                exit;

            case 'dosen_kaprodi':
                header("Location: dosen/kaprodi/dashboard.php");
                exit;

            case 'dosen':
                header("Location: dosen/dashboard.php");
                exit;

            case 'tu':
                header("Location: tu/dashboard.php");
                exit;
        }

        header("Location: login.php");
        exit;
    }

    public function logout() {
        session_destroy();
        header("Location: login.php");
    }
}
