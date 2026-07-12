<?php
// laporan_keuangan.php - Halaman Publik Terintegrasi Terkoreksi Penuh
session_start();
include 'koneksi.php';

// Logika Logout Terintegrasi
if (isset($_GET['aksi']) && $_GET['aksi'] === 'keluar') {
    unset($_SESSION['akses_publik_imanuel']);
    header("Location: laporan_keuangan.php");
    exit;
}

// Konfigurasi Kunci Akses Publik 
$KODE_RAHASIA_VALID = "imanuelbahu2026"; 

// Logika Validasi Kode Rahasia
if (isset($_POST['cek_kode'])) {
    $kode_input = $_POST['kode_akses'];
    if ($kode_input === $KODE_RAHASIA_VALID) {
        $_SESSION['akses_publik_imanuel'] = true;
        header("Location: laporan_keuangan.php");
        exit;
    } else {
        $error = "Kode akses yang Anda masukkan salah. Silakan periksa kembali.";
    }
}

// Ambil parameter filter utama
$subtab = $_GET['subtab'] ?? 'rekapan';
$bulan_pilih = $_GET['bulan'] ?? date('Y-m');
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Keuangan Publik - GMIM Imanuel Bahu</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,700;0,900;1,400;1,700;1,900&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style-beranda.css?v=<?php echo time(); ?>">
    
    <style>
        body { background-color: #f1f5f9; font-family: 'Plus Jakarta Sans', sans-serif; }
        .hero-title-style { font-family: 'Playfair Display', serif; color: #1e0b36; font-weight: 800; }
        .badge-info-digital { background-color: #f3effb; color: #6f42c1; font-weight: 700; letter-spacing: 1px; font-size: 0.75rem; }
        .glass-auth-card { background: #ffffff; border-radius: 24px; box-shadow: 0 15px 35px rgba(0,0,0,0.05); border: 1px solid rgba(111, 66, 193, 0.1); max-width: 500px; width: 100%; margin: 0 auto; padding: 2.5rem; }
        .btn-purple-accent { background-color: #6f42c1; color: white; font-weight: 700; border-radius: 50px; transition: all 0.3s; }
        .btn-purple-accent:hover { background-color: #5a32a3; color: white; transform: translateY(-1px); }
        .btn-close-access { border: 2px solid #dc3545; color: #dc3545; font-weight: 700; border-radius: 50px; background: transparent; transition: 0.3s; font-size: 0.85rem; }
        .btn-close-access:hover { background: #dc3545; color: white; }
        .bg-glass-publik { background: rgba(255, 255, 255, 0.8); backdrop-filter: blur(10px); border: 1px solid rgba(255, 255, 255, 0.5); }
        .tab-publik { color: #64748b !important; background: transparent !important; transition: .3s; font-weight: 700; font-size: 0.9rem; border: 1px solid transparent; text-decoration: none; display: inline-block; }
        .tab-publik:hover { background: rgba(111, 66, 193, 0.06) !important; color: #6f42c1 !important; }
        .tab-publik.active { background: #6f42c1 !important; color: #fff !important; box-shadow: 0 4px 12px rgba(111, 66, 193, 0.25); }
    </style>
</head>
<body>

<div class="digital-grid"></div>
<div class="aurora-container">
    <div class="aurora-blob blob-blue"></div>
    <div class="aurora-blob blob-soft"></div>
</div>

<?php include 'navbar.php'; ?>

<section class="py-5 text-center position-relative" style="z-index: 2;">
    <div class="container">
        <span class="badge badge-info-digital rounded-pill px-3 py-2 text-uppercase mb-3 shadow-sm">
            <i class="bi bi-info-circle-fill me-1"></i> Informasi Digital
        </span>
        <h1 class="hero-title-style mb-2" style="font-size: 3.5rem;">Laporan Keuangan</h1>
        <p class="text-muted small mx-auto" style="max-width: 600px;">Pusat Transparansi Kas, Persembahan Pundi, dan Akuntabilitas Pelayanan Jemaat GMIM Imanuel Bahu</p>
    </div>
</section>

<div class="container position-relative mb-5" style="z-index: 3;">

    <?php if (!isset($_SESSION['akses_publik_imanuel'])): ?>
        
        <div class="glass-auth-card text-center my-4">
            <div class="mb-3">
                <i class="bi bi-shield-lock text-primary" style="font-size: 2.5rem; color: #6f42c1 !important;"></i>
            </div>
            <h5 class="fw-bold text-dark mb-2">Area Terbatas Jemaat</h5>
            <p class="text-muted small mb-4 px-2">
                Laporan Keuangan bersifat internal. Silakan masukkan kode akses jemaat yang dapat diperoleh melalui panduan website Imanuel Bahu atau melalui pelayan khusus.
            </p>
            
            <?php if(isset($error)): ?>
                <div class="alert alert-danger border-0 small py-2 rounded-3 mb-3"><?= $error; ?></div>
            <?php endif; ?>

            <form action="" method="POST">
                <div class="mb-3">
                    <input type="password" name="kode_akses" class="form-control text-center rounded-pill py-2.5 border-light-subtle shadow-sm small" placeholder="Masukkan Kode Akses..." required autocomplete="off">
                </div>
                <button type="submit" name="cek_kode" class="btn btn-purple-accent w-100 py-2.5 shadow-sm">
                    Verifikasi Akses
                </button>
            </form>
        </div>

    <?php else: ?>

        <div class="d-flex justify-content-end mb-4">
            <a href="laporan_keuangan.php?aksi=keluar" class="btn btn-sm btn-close-access px-4 py-2 d-flex align-items-center gap-2 shadow-sm">
                <i class="bi bi-unlock-fill"></i> Tutup Akses
            </a>
        </div>

        <!-- FIXED: Mengubah button navigasi menjadi tag <a> untuk reload file PHP subtab secara bersih -->
        <ul class="nav nav-pills p-2 bg-glass-publik rounded-4 gap-1 border border-white shadow-sm mb-4 overflow-x-auto flex-nowrap" id="pills-tab-publik">
            <li class="nav-item">
                <a class="nav-link tab-publik px-4 py-2.5 rounded-3 text-nowrap <?= $subtab=='rekapan'?'active':'' ?>" href="laporan_keuangan.php?subtab=rekapan&bulan=<?= $bulan_pilih; ?>"><i class="bi bi-journal-text me-2"></i>Rekapan</a>
            </li>
            <li class="nav-item">
                <a class="nav-link tab-publik px-4 py-2.5 rounded-3 text-nowrap <?= $subtab=='minggu'?'active':'' ?>" href="laporan_keuangan.php?subtab=minggu&bulan=<?= $bulan_pilih; ?>"><i class="bi bi-calendar3-event me-2"></i>Ibadah Minggu</a>
            </li>
            <li class="nav-item">
                <a class="nav-link tab-publik px-4 py-2.5 rounded-3 text-nowrap <?= $subtab=='kolom'?'active':'' ?>" href="laporan_keuangan.php?subtab=kolom&bulan=<?= $bulan_pilih; ?>"><i class="bi bi-grid-3x3-gap-fill me-2"></i>Setoran Kolom</a>
            </li>
            <li class="nav-item">
                <a class="nav-link tab-publik px-4 py-2.5 rounded-3 text-nowrap <?= $subtab=='sampul'?'active':'' ?>" href="laporan_keuangan.php?subtab=sampul&bulan=<?= $bulan_pilih; ?>"><i class="bi bi-envelope-paper-heart-fill me-2"></i>Penerimaan Sampul</a>
            </li>
            <li class="nav-item">
                <a class="nav-link tab-publik px-4 py-2.5 rounded-3 text-nowrap <?= $subtab=='khusus'?'active':'' ?>" href="laporan_keuangan.php?subtab=khusus&bulan=<?= $bulan_pilih; ?>"><i class="bi bi-stars me-2"></i>Ibadah Khusus</a>
            </li>
            <li class="nav-item">
                <a class="nav-link tab-publik px-4 py-2.5 rounded-3 text-nowrap <?= $subtab=='pengeluaran'?'active':'' ?>" href="laporan_keuangan.php?subtab=pengeluaran&bulan=<?= $bulan_pilih; ?>"><i class="bi bi-cart-dash-fill me-2"></i>Pengeluaran</a>
            </li>
        </ul>

        <!-- Wadah Konten Render Sub-Tab Statis Sesuai Parameter URL -->
        <div class="tab-content" id="pills-tabContentPublik">
            <div class="tab-pane fade show active" role="tabpanel">
                <?php 
                if ($subtab == 'rekapan') {
                    include 'keuangan_rekapan.php';
                } elseif ($subtab == 'minggu') {
                    include 'keuangan_ibadahMinggu.php';
                } elseif ($subtab == 'kolom') {
                    include 'keuangan_kolom.php';
                } elseif ($subtab == 'sampul') {
                    include 'keuangan_sampul.php';
                } elseif ($subtab == 'khusus') {
                    include 'keuangan_ibadahKhusus.php';
                } elseif ($subtab == 'pengeluaran') {
                    include 'keuangan_pengeluaran.php';
                }
                ?>
            </div>
        </div>

    <?php endif; ?>

</div>

<?php include 'footer.php'; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>