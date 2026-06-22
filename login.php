<?php
session_start();
include 'koneksi.php';

if (isset($_POST['login'])) {
    $user = mysqli_real_escape_string($koneksi, $_POST['username']);
    $pass = $_POST['password'];

    $query = mysqli_query($koneksi, "SELECT * FROM users WHERE username='$user' AND password='$pass'");
    
    if (mysqli_num_rows($query) === 1) {
        $_SESSION['admin_imanuel'] = $user;
        header("Location: admin/admin_dashboard.php");
        exit;
    } else {
        $error = true;
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>DASHBOARD ADMIN GMIM IMANUEL BAHU</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        body { 
            background-color: #f8fafc; 
            min-height: 100vh; 
            display: flex; 
            align-items: center; 
            position: relative;
            overflow: hidden; 
        }

        /* Tekstur & Aurora dari Beranda */
        .digital-grid {
            position: fixed; top: 0; left: 0; width: 100vw; height: 100vh; z-index: -2;
            background-image: linear-gradient(rgba(13, 110, 253, 0.04) 1px, transparent 1px),
                              linear-gradient(90deg, rgba(13, 110, 253, 0.04) 1px, transparent 1px);
            background-size: 40px 40px; 
        }
        .aurora-blob {
            position: fixed; border-radius: 50%; filter: blur(100px); opacity: 0.28; z-index: -1;
        }
        .blob-blue { top: -10%; left: 10%; width: 40vw; height: 40vw; background: #6f42c1; }
        .blob-soft { bottom: -10%; right: -5%; width: 50vw; height: 50vw; background: #e0eafc; }

        /* Kartu Login Glassmorphism */
        .login-card { 
            width: 100%; max-width: 400px; margin: auto; 
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.8);
            border-radius: 20px; 
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
        }
    </style>
</head>
<body>

<div class="digital-grid"></div>
<div class="aurora-blob blob-blue"></div>
<div class="aurora-blob blob-soft"></div>

<div class="container">
    <div class="card login-card border-0">
        <div class="card-body p-5">
            <div class="text-center mb-3">
                <i class="bi bi-house-heart-fill text-primary" style="font-size: 3rem;"></i>
            </div>
            <h3 class="text-center fw-bold mb-4">DASHBOARD ADMIN GMIM IMANUEL BAHU</h3>
            
            <?php if(isset($error)) : ?>
                <div class="alert alert-danger text-center shadow-sm">Username atau Password <Samp></Samp>Salah!</div>
            <?php endif; ?>

            <form method="POST">
                <div class="mb-3">
                    <label class="form-label fw-bold small">Username</label>
                    <input type="text" name="username" class="form-control" placeholder="Masukkan username" required>
                </div>
                <div class="mb-4">
                    <label class="form-label fw-bold small">Password</label>
                    <input type="password" name="password" class="form-control" placeholder="Masukkan password" required>
                </div>
                <button type="submit" name="login" class="btn btn-primary w-100 py-2 rounded-pill fw-bold shadow-sm">Masuk</button>
            </form>
            <div class="text-center mt-4">
                <a href="index.php" class="text-decoration-none text-muted small">← Kembali ke Beranda</a>
            </div>
        </div>
    </div>
</div>

</body>
</html>