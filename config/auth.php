<?php
// config/auth.php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

class Auth {
    private $pdo;

    public function __construct() {
        global $pdo; 
        require_once __DIR__ . "/database.php";
        
        if (!isset($pdo)) {
            throw new Exception("PDO tidak ditemukan. Periksa config/database.php");
        }
        $this->pdo = $pdo;
    }

    public function isLoggedIn() {
        return isset($_SESSION['user']);
    }

    private function verifyPassword($plain, $stored) {
        if (empty($stored)) return false;
        if (strpos($stored, '$2y$') === 0 || strpos($stored, '$2a$') === 0 || strpos($stored, '$argon2') === 0) {
            return password_verify($plain, $stored);
        }
        if (preg_match('/^[a-f0-9]{32}$/i', $stored)) {
            return md5($plain) === $stored;
        }
        return hash_equals($stored, $plain);
    }

    public function login($username, $password) {
        // 1) Cek Mahasiswa
        $stmt = $this->pdo->prepare("SELECT * FROM mahasiswa WHERE nim = :u LIMIT 1");
        $stmt->execute([':u' => $username]);
        $m = $stmt->fetch();
        if ($m) {
            if ($this->verifyPassword($password, $m['password'])) {
                $_SESSION['user'] = [
                    'id' => $m['id'],
                    'username' => $m['nim'],
                    'nama' => $m['nama'],
                    'role' => 'mahasiswa',
                    'prodi' => $m['prodi']
                ];
                return true;
            }
            return false;
        }

        // 2) Cek Dosen
        $stmt = $this->pdo->prepare("SELECT * FROM dosen WHERE nidn = :u LIMIT 1");
        $stmt->execute([':u' => $username]);
        $d = $stmt->fetch();
        if ($d) {
            if ($this->verifyPassword($password, $d['password'])) {
                $jab = strtolower(trim($d['jabatan'] ?? ''));
                
                if ($jab === 'kaprodi') {
                    $role = 'dosen_kaprodi';
                } else {
                    $stmtDpa = $this->pdo->prepare("SELECT id FROM mahasiswa WHERE dpa_id = ? LIMIT 1");
                    $stmtDpa->execute([$d['id']]);
                    if ($stmtDpa->rowCount() > 0) {
                        $role = 'dosen_dpa';
                    } else {
                        $role = 'dosen'; 
                    }
                }

                $_SESSION['user'] = [
                    'id' => $d['id'],
                    'username' => $d['nidn'],
                    'nama' => $d['nama'],
                    'jabatan' => $d['jabatan'],
                    'role' => $role
                ];
                return true;
            }
            return false;
        }

        // 3) Cek Tata Usaha
        $stmt = $this->pdo->prepare("SELECT * FROM tata_usaha WHERE nip = :u LIMIT 1");
        $stmt->execute([':u' => $username]);
        $tu = $stmt->fetch();
        if ($tu) {
            if ($this->verifyPassword($password, $tu['password'])) {
                $_SESSION['user'] = [
                    'id' => $tu['id'],
                    'username' => $tu['nip'],
                    'nama' => $tu['nama'],
                    'role' => 'tu'
                ];
                return true;
            }
            return false;
        }

        return false;
    }

    public function redirectBasedOnRole() {
        if (!$this->isLoggedIn()) {
            header("Location: login.php");
            exit;
        }

        $role = $_SESSION['user']['role'] ?? '';

        switch ($role) {
            case 'mahasiswa': header("Location: mahasiswa/dashboard.php"); break;
            case 'dosen_dpa': header("Location: dosen/dashboard.php"); break;
            case 'dosen_kaprodi': header("Location: dosen/kaprodi/dashboard.php"); break;
            case 'dosen': header("Location: dosen/dashboard.php"); break;
            case 'tu': header("Location: tu/dashboard.php"); break;
            default: header("Location: login.php"); break;
        }
        exit;
    }

    public function logout() {
        $_SESSION = [];
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params['path'], $params['domain'],
                $params['secure'], $params['httponly']
            );
        }
        session_destroy();
        header("Location: /KPRS/login.php"); 
        exit;
    }
}
?>