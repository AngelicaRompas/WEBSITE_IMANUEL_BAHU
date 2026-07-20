<?php
session_start();
include 'koneksi.php';

// Proses Login
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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin GMIM Imanuel Bahu - Login</title>
    <!-- Bootstrap & CSS Assets -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style-login.css?v=<?php echo time(); ?>">
</head>
<body>

<!-- Card Login (Diposisikan di tengah oleh CSS body) -->
<div class="login-card shadow-sm">
    <div class="card-body p-4 p-md-5">
        
        <!-- Bagian Atas: Logo -->
        <div class="text-center mb-4">
            <div class="login-logo-container">
                <img src="assets/images/logo_gereja_imanuel.png" alt="Logo GMIM" class="login-logo" 
                     onerror="this.style.display='none'; document.getElementById('logo-fallback').style.display='block';">
                <i id="logo-fallback" class="bi bi-church text-primary" style="font-size: 2.2rem; display: none;"></i>
            </div>
        </div>
        
        <h3 class="text-center fw-bold mb-4 login-title">DASHBOARD ADMIN<br>GMIM IMANUEL BAHU</h3>
        
        <?php if(isset($error)) : ?>
            <div class="alert alert-danger text-center shadow-sm py-2 small fw-semibold rounded-3 mb-3">
                <i class="bi bi-exclamation-triangle-fill me-2"></i>Username atau Password Salah!
            </div>
        <?php endif; ?>

        <!-- Form Login -->
        <form method="POST">
            <div class="mb-3">
                <label class="form-label fw-bold small text-secondary">Username</label>
                <input type="text" name="username" class="form-control form-control-login" placeholder="Masukkan username" autocomplete="off" required>
            </div>
            <div class="mb-4">
                <label class="form-label fw-bold small text-secondary">Password</label>
                <input type="password" name="password" class="form-control form-control-login" placeholder="Masukkan password" required>
            </div>
            <button type="submit" name="login" class="btn btn-purple-login w-100 py-2 rounded-pill fw-bold shadow-sm">
                <i class="bi bi-box-arrow-in-right me-2"></i>Masuk Aplikasi
            </button>
        </form>
        
        <!-- Link Kembali -->
        <div class="text-center mt-4">
            <a href="index.php" class="text-decoration-none text-muted small link-back">
                <i class="bi bi-arrow-left me-1"></i> Kembali ke Beranda
            </a>
        </div>
        
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>