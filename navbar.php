<?php 
// Memastikan session dimulai untuk logika Login Admin
if (session_status() === PHP_SESSION_NONE) {
    session_start();
} 

// Logika Adaptif Jalur Folder Publik (Mencegah Pecah Path)
$is_admin_folder = strpos($_SERVER['SCRIPT_NAME'], '/admin/') !== false;
$root_prefix = $is_admin_folder ? '../' : '';

/* =========================================================
   FIX JALUR ABSOLUT: Menghindari Error Tautan Ganda / XAMPP
   ========================================================= */
if ($_SERVER['HTTP_HOST'] == 'localhost:8081' || $_SERVER['HTTP_HOST'] == 'localhost') {
    $base_url = '/WEBSITE_IMANUEL/';
} else {
    $base_url = '/';
}
?>

<style>
/* CSS Utama (Desktop & Mobile) */
.animate {
    animation-duration: 0.3s;
    animation-fill-mode: both;
}

@keyframes slideIn {
    0% { transform: translateY(1rem); opacity: 0; }
    100% { transform: translateY(0rem); opacity: 1; }
}

.slideIn { animation-name: slideIn; }

/* =========================================================
   PENTING: Khusus untuk Tampilan Mobile (Max 991px)
   ========================================================= */
@media (max-width: 991px) {
    /* 1. Pengaturan Container agar item tetap sejajar 1 baris */
    .navbar .container {
        display: flex;
        flex-wrap: nowrap !important;
        justify-content: space-between;
        align-items: center;
        padding-left: 10px;
        padding-right: 10px;
    }

    /* 2. Logo dan Brand */
    .navbar-brand { 
        display: flex; 
        align-items: center; 
        flex-grow: 1; 
        min-width: 0; 
        margin-right: 10px !important;
    }
    
    .navbar-brand img { 
        width: 40px !important; 
        height: 40px !important; 
        margin-right: 8px !important; 
        flex-shrink: 0;
    }

    .brand-text { 
        font-size: 0.85rem !important; 
        white-space: nowrap; 
        overflow: hidden; 
        text-overflow: ellipsis; 
    }

    /* 3. Tombol Toggle (Hamburger) */
    .navbar-toggler {
        flex-shrink: 0;
        margin-left: auto;
        order: 2;
        padding: 5px 10px;
        border: none;
    }

    /* 4. KONTEN MENU POP-UP */
    .navbar-collapse {
        position: absolute;
        top: 100%;
        right: 15px;
        background: rgba(255, 255, 255, 0.98);
        width: 250px;
        border-radius: 15px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
        padding: 20px;
        z-index: 1050;
        border: 1px solid rgba(0, 0, 0, 0.05);
        opacity: 0;
        visibility: hidden;
        transition: all 0.3s ease-in-out;
        margin-top: 10px;
    }

    .navbar-collapse.show {
        opacity: 1;
        visibility: visible;
        transform: translateY(5px);
    }

    /* 5. List Menu Mobile */
    .navbar-nav {
        display: flex;
        flex-direction: column;
        align-items: flex-start !important;
        width: 100%;
        margin: 0 !important;
    }

    .navbar-nav .nav-item { width: 100%; margin-bottom: 5px; }

    .navbar-nav .nav-link {
        color: #6f42c1 !important;
        font-weight: 600 !important;
        padding: 10px 15px !important;
        border-radius: 8px;
        text-align: left;
        transition: background 0.2s;
    }

    .navbar-nav .nav-link:hover { background-color: rgba(111, 66, 193, 0.1); }

    /* 6. Dropdown Mobile */
    .dropdown-menu {
        position: static !important;
        float: none;
        width: 100%;
        margin-top: 5px;
        border: none !important;
        background: rgba(111, 66, 193, 0.05) !important;
        padding-left: 15px !important;
    }

    .dropdown-item { color: #555 !important; padding: 8px 15px !important; }

    /* 7. Tombol Admin/Login */
    .navbar-nav .nav-item.ms-lg-3 {
        width: 100%;
        margin-top: 15px !important;
        margin-left: 0 !important;
        border-top: 1px solid rgba(0, 0, 0, 0.1);
        padding-top: 15px;
    }

    .navbar-nav .btn { 
        width: 100% !important; 
        justify-content: center !important; 
    }
} /* FIX: Menutup tag @media mobile dengan benar */
</style>

<nav class="navbar navbar-expand-lg navbar-dark sticky-top shadow-sm" style="background-color: #6f42c1;">
    <div class="container">
        <a class="navbar-brand d-flex align-items-center fw-bold" href="<?php echo $root_prefix; ?>index.php">
            <img src="<?php echo $root_prefix; ?>assets/images/logo_gereja_imanuel.png" alt="Logo GMIM" width="55" height="55" class="me-3">
            <div class="brand-container">
                <span class="brand-text d-block" style="letter-spacing: 1px;">GMIM IMANUEL BAHU</span>
            </div>
        </a>

        <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto align-items-center">
                
                <li class="nav-item"><a class="nav-link px-3 fw-bold text-white" href="<?php echo $root_prefix; ?>index.php">Beranda</a></li>
                
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle px-3 fw-bold text-white" href="#" role="button" data-bs-toggle="dropdown">Profil</a>
                    <ul class="dropdown-menu shadow border-0 animate slideIn">
                        <li><a class="dropdown-item fw-semibold" href="<?php echo $root_prefix; ?>visi-misi.php">Visi Misi</a></li>
                        <li><a class="dropdown-item fw-semibold" href="<?php echo $root_prefix; ?>sejarah.php">Sejarah Gereja</a></li>
                        <li><a class="dropdown-item fw-semibold" href="<?php echo $root_prefix; ?>data-jemaat.php">Data Jemaat</a></li>
                    </ul>
                </li>

                <li class="nav-item"><a class="nav-link px-3 fw-bold text-white" href="<?php echo $root_prefix; ?>event.php">Event</a></li>
                
                <li class="nav-item"><a class="nav-link px-3 fw-bold text-white" href="<?php echo $root_prefix; ?>warta-jemaat.php">Warta Jemaat</a></li>

                <li class="nav-item"><a class="nav-link px-3 fw-bold text-white" href="<?php echo $root_prefix; ?>warta-keuangan.php">Keuangan</a></li>

                <li class="nav-item"><a class="nav-link px-3 fw-bold text-white" href="<?php echo $root_prefix; ?>renungan.php">Renungan</a></li>

                <li class="nav-item"><a class="nav-link px-3 fw-bold text-white" href="<?php echo $root_prefix; ?>kontak.php">Kontak</a></li>
                
                <li class="nav-item ms-lg-3 mt-3 mt-lg-0">
                    <?php if (isset($_SESSION['admin_imanuel'])): ?>
                        <div class="dropdown d-inline">
                            <a class="btn btn-light rounded-pill px-4 fw-bold text-primary dropdown-toggle shadow-sm" href="#" role="button" data-bs-toggle="dropdown">
                                <i class="bi bi-person-check-fill me-1"></i> Admin
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end shadow border-0 mt-2">
                                <li><a class="dropdown-item fw-semibold" href="<?php echo $base_url; ?>admin/admin_dashboard.php">Dashboard</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item text-danger fw-bold" href="#" onclick="jalankanLogout(event)">Logout</a></li>
                            </ul>
                        </div>
                    <?php else: ?>
                        <a class="btn btn-outline-light rounded-pill px-4 fw-bold shadow-sm" href="<?php echo $base_url; ?>login.php">Login Admin</a>
                    <?php endif; ?>
                </li>
                
            </ul>
        </div>
    </div>
</nav>

<script>
function jalankanLogout(event) {
    event.preventDefault();
    
    // Mengambil domain & port saat ini (misal: localhost:8081 atau imanuelbahu.org)
    const host = window.location.host;
    
    if (host.includes('localhost')) {
        // Jika di localhost, arahkan mutlak ke folder projek XAMPP
        window.location.href = window.location.protocol + '//' + host + '/WEBSITE_IMANUEL/admin/logout.php';
    } else {
        // Jika di hosting live, langsung tembak ke folder admin domain utama
        window.location.href = window.location.protocol + '//' + host + '/admin/logout.php';
    }
}
</script>