<?php
session_start();

class Auth {
    public function login($username, $password) {
        $database = new Database();
        $db = $database->getConnection();
        
        // Cek di tabel mahasiswa
        $query = "SELECT *, 'mahasiswa' as role FROM mahasiswa WHERE nim = :username";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':username', $username);
        $stmt->execute();
        
        if ($stmt->rowCount() == 1) {
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            if (password_verify($password, $user['password'])) {
                $this->setUserSession($user);
                return true;
            }
        }
        
        // Cek di tabel dosen
        $query = "SELECT *, 'dosen' as role FROM dosen WHERE nidn = :username";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':username', $username);
        $stmt->execute();
        
        if ($stmt->rowCount() == 1) {
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            if (password_verify($password, $user['password'])) {
                $this->setUserSession($user);
                return true;
            }
        }
        
        // Cek di tabel tata_usaha
        $query = "SELECT *, 'tata_usaha' as role FROM tata_usaha WHERE nip = :username";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':username', $username);
        $stmt->execute();
        
        if ($stmt->rowCount() == 1) {
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            if (password_verify($password, $user['password'])) {
                $this->setUserSession($user);
                return true;
            }
        }
        
        return false;
    }
    
    private function setUserSession($user) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'] ?? $user['nim'] ?? $user['nidn'] ?? $user['nip'];
        $_SESSION['nama'] = $user['nama'];
        $_SESSION['role'] = $user['role'];
        $_SESSION['logged_in'] = true;
        
        // Set additional data based on role
        if ($user['role'] == 'mahasiswa') {
            $_SESSION['nim'] = $user['nim'];
            $_SESSION['prodi'] = $user['prodi'];
            $_SESSION['dpa_id'] = $user['dpa_id'];
        } elseif ($user['role'] == 'dosen') {
            $_SESSION['nidn'] = $user['nidn'];
            $_SESSION['jabatan'] = $user['jabatan'];
            $_SESSION['prodi'] = $user['prodi'];
        } elseif ($user['role'] == 'tata_usaha') {
            $_SESSION['nip'] = $user['nip'];
        }
    }
    
    public function isLoggedIn() {
        return isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true;
    }
    
    public function redirectBasedOnRole() {
        if ($this->isLoggedIn()) {
            $role = $_SESSION['role'];
            switch ($role) {
                case 'mahasiswa':
                    header("Location: mahasiswa/dashboard.php");
                    break;
                case 'dosen':
                    // Check if dosen is Kaprodi
                    if ($_SESSION['jabatan'] == 'Kaprodi') {
                        header("Location: kaprodi/dashboard.php");
                    } else {
                        header("Location: dosen/dashboard.php");
                    }
                    break;
                case 'tata_usaha':
                    header("Location: tu/dashboard.php");
                    break;
                default:
                    header("Location: login.php");
            }
            exit;
        }
    }
    
    public function logout() {
        $_SESSION = array();
        session_destroy();
        header("Location: login.php");
        exit;
    }
    
    public function requireAuth() {
        if (!$this->isLoggedIn()) {
            header("Location: login.php");
            exit;
        }
    }
    
    public function requireRole($allowedRoles) {
        $this->requireAuth();
        if (!in_array($_SESSION['role'], $allowedRoles)) {
            header("Location: ../unauthorized.php");
            exit;
        }
    }
}
?>