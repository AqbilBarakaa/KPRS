<?php
require_once 'config/database.php';
require_once 'config/auth.php';

$auth = new Auth();

// Redirect if already logged in
if ($auth->isLoggedIn()) {
    $auth->redirectBasedOnRole();
}

$error = '';

if ($_POST) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    
    if ($auth->login($username, $password)) {
        $auth->redirectBasedOnRole();
    } else {
        $error = "Username atau password salah!";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sistem KPRS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            height: 100vh;
            display: flex;
            align-items: center;
        }
        .login-container {
            background: white;
            border-radius: 10px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.3);
            overflow: hidden;
        }
        .login-header {
            background: #343a40;
            color: white;
            padding: 20px;
            text-align: center;
        }
        .login-form {
            padding: 30px;
        }
        .form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        }
        .btn-login {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            color: white;
            padding: 10px 30px;
        }
        .btn-login:hover {
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-5">
                <div class="login-container">
                    <div class="login-header">
                        <h3><i class="fas fa-graduation-cap"></i> SISTEM KPRS</h3>
                        <p class="mb-0">Universitas Trunojoyo Madura</p>
                    </div>
                    
                    <div class="login-form">
                        <?php if ($error): ?>
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <?php echo $error; ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        <?php endif; ?>
                        
                        <form method="POST" action="">
                            <div class="mb-3">
                                <label for="username" class="form-label">Username</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-user"></i></span>
                                    <input type="text" class="form-control" id="username" name="username" 
                                           placeholder="Masukkan NIM/NIDN/NIP" required autofocus>
                                </div>
                                <div class="form-text">
                                    <small>
                                        <strong>Mahasiswa:</strong> NIM &nbsp;|&nbsp; 
                                        <strong>Dosen:</strong> NIDN &nbsp;|&nbsp; 
                                        <strong>TU:</strong> NIP
                                    </small>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label for="password" class="form-label">Password</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                    <input type="password" class="form-control" id="password" name="password" 
                                           placeholder="Masukkan password" required>
                                </div>
                            </div>
                            
                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-login btn-lg">
                                    <i class="fas fa-sign-in-alt"></i> LOGIN
                                </button>
                            </div>
                        </form>
                        
                        <div class="mt-4 text-center">
                            <div class="row">
                                <div class="col-4">
                                    <div class="border rounded p-2 bg-light">
                                        <small class="text-muted d-block">Mahasiswa</small>
                                        <strong>NIM</strong>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="border rounded p-2 bg-light">
                                        <small class="text-muted d-block">Dosen/DPA</small>
                                        <strong>NIDN</strong>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="border rounded p-2 bg-light">
                                        <small class="text-muted d-block">Tata Usaha</small>
                                        <strong>NIP</strong>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="text-center mt-3">
                    <small class="text-white">
                        &copy; 2024 Sistem KPRS - Teknik Informatika UTM
                    </small>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://kit.fontawesome.com/your-fontawesome-kit.js"></script>
</body>
</html>