<?php 
session_start();
include 'koneksi.php'; 

$kode_valid = ['GIB2026','JEMAAT2026', 'IMANUELBAHU', 'PELAYANAN', 'WARTAIMANUEL'];
$akses_warta = isset($_SESSION['akses_warta']) && $_SESSION['akses_warta'] === true;

// Logika Verifikasi Kode Referral
if (isset($_POST['btn_akses'])) {
    if (in_array(strtoupper(trim($_POST['kode_referral'])), $kode_valid)) {
        $_SESSION['akses_warta'] = true;
        header("Location: warta-jemaat.php"); 
        exit;
    } else { 
        $error = "Kode Akses Salah!"; 
    }
}

// Logika Keluar (Logout)
if (isset($_GET['logout'])) { 
    unset($_SESSION['akses_warta']); 
    header("Location: warta-jemaat.php"); 
    exit; 
}

// Logika Pengambilan Data Database
$detail_tgl = isset($_GET['detail']) ? mysqli_real_escape_string($koneksi, $_GET['detail']) : null;
$query = ($akses_warta) ? ($detail_tgl ? mysqli_query($koneksi, "SELECT * FROM warta_jemaat WHERE tanggal = '$detail_tgl'") : mysqli_query($koneksi, "SELECT DISTINCT tanggal FROM warta_jemaat ORDER BY tanggal DESC")) : null;

// Logika Ekstra: Ambil file PDF satu kali saja untuk diletakkan di header detail
$pdf_file = '';
if ($detail_tgl && $akses_warta) {
    $q_pdf = mysqli_query($koneksi, "SELECT file_pdf FROM warta_jemaat WHERE tanggal = '$detail_tgl' AND file_pdf != '' LIMIT 1");
    if ($q_pdf && mysqli_num_rows($q_pdf) > 0) {
        $r_pdf = mysqli_fetch_assoc($q_pdf);
        $pdf_file = $r_pdf['file_pdf'];
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Warta Jemaat - GMIM Imanuel Bahu</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,700;0,900;1,400;1,700;1,900&family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    
    <!-- Tautan CSS Eksternal (Gunakan versioning time agar anti-cache browser) -->
    <link rel="stylesheet" href="assets/css/style-beranda.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="assets/css/style_warta.css?v=<?php echo time(); ?>">
</head>
<body>

<!-- Elemen Background Estetik -->
<div class="digital-grid"></div>
<div class="aurora-container">
    <div class="aurora-blob blob-blue"></div>
    <div class="aurora-blob blob-soft"></div>
</div>

<?php include 'navbar.php'; ?>

<!-- Premium Header Area -->
<section class="page-header-premium text-center z-2">
    <div class="container">
        <!-- Badge Atas -->
        <div data-aos="fade-down" data-aos-duration="800">
            <span class="badge rounded-pill px-3 py-2 small mb-3 fw-bold tracking-widest" style="background-color: #f3effb; color: #6f42c1; letter-spacing: 2px;">
                <i class="bi bi-journal-bookmark-fill me-2"></i>INFORMASI DIGITAL
            </span>      
        </div>
        
        <!-- Judul Utama -->
        <h1 class="main-title-aesthetic mb-2" data-aos="fade-down" data-aos-duration="1000" data-aos-delay="100">
            Warta Jemaat
        </h1>
        
        <!-- Divider Estetis -->
        <div class="premium-divider" data-aos="zoom-in" data-aos-duration="800" data-aos-delay="200">
            <div class="line"></div>
            <div class="dot"></div>
            <div class="line"></div>
        </div>
        
        <!-- Sub Judul -->
        <p class="sub-title-aesthetic fw-medium mb-0" data-aos="fade-up" data-aos-duration="1000" data-aos-delay="300">
            Pusat Informasi Pelayanan dan Jadwal Ibadah <span class="church-highlight">GMIM Imanuel Bahu</span>
        </p>
    </div>
</section>

<div class="container pb-5 mb-5 position-relative z-2 mt-4">
    <?php if (!$akses_warta): ?>
        <!-- Tampilan Halaman Terkunci (Verifikasi Kode) -->
        <div class="row justify-content-center" data-aos="zoom-in" data-aos-delay="300">
            <div class="col-md-5 glass-card auth-box p-5 text-center mt-2">
                <i class="bi bi-shield-lock text-purple fs-1 mb-3 d-block"></i>
                <h5 class="fw-bold my-3">Area Terbatas Jemaat</h5>
                <p class="text-muted small mb-4">Warta Jemaat bersifat internal. Silakan masukkan kode akses jemaat yang dapat diperoleh melalui panduan website Imanuel Bahu atau melalui pelayan khusus.</p>
                <form method="POST">
                    <input type="text" name="kode_referral" class="form-control mb-3 text-center rounded-pill py-2" placeholder="Masukkan Kode Akses..." required>
                    <button type="submit" name="btn_akses" class="btn btn-purple w-100 py-2 fw-bold">Verifikasi Akses</button>
                </form>
                <?php if(isset($error)) echo "<p class='text-danger mt-3 mb-0 small fw-bold'>$error</p>"; ?>
            </div>
        </div>
        
    <?php else: ?>
        <!-- Navigasi Atas Menu Setelah Akses Terbuka -->
        <div class="d-flex justify-content-between align-items-center mb-4 mt-3" data-aos="fade-in">
            <!-- Menambahkan class 'text-white' dan 'shadow-sm' agar tulisan putih dan menonjol -->
            <h4 class="fw-bold m-0 text-white" style="text-shadow: 1px 1px 4px rgba(0,0,0,0.5);">
                <i class="bi bi-collection-play me-2"></i> <?php echo $detail_tgl ? 'Detail Warta Digital' : 'Galeri Warta Jemaat'; ?>
            </h4>
            
            <!-- Mengubah warna tombol menjadi putih dengan 'btn-outline-light' -->
            <a href="warta-jemaat.php?logout=1" class="btn btn-outline-light btn-sm rounded-pill px-4 fw-bold shadow-sm">
                <i class="bi bi-box-arrow-right me-1"></i> Tutup Akses
            </a>
        </div>

        <?php if ($detail_tgl): ?>
            <!-- ==============================================
                 TAMPILAN MODE DETAIL (PETUGAS IBADAH 3 SESI)
                 ============================================== -->
            
            <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
                <a href="warta-jemaat.php" class="btn btn-dark rounded-pill px-4 py-2 fw-semibold shadow-sm" data-aos="fade-right">
                    <i class="bi bi-arrow-left me-2"></i> Kembali ke Galeri
                </a>
                
                <?php if(!empty($pdf_file)): ?>
                    <!-- FIX: Menghapus awalan 'admin/' karena folder target berada di root luar -->
                    <a href="assets/document_warta/<?php echo htmlspecialchars($pdf_file); ?>" target="_blank" class="btn btn-purple rounded-pill px-4 py-2 fw-bold shadow-sm" data-aos="fade-left">
                        <i class="bi bi-cloud-arrow-down-fill me-2"></i> Buka / Unduh Dokumen Warta (PDF)
                    </a>
                <?php endif; ?>
            </div>
            
            <div class="row g-4">
                <?php while($row = mysqli_fetch_assoc($query)): ?>
                <div class="col-md-4" data-aos="fade-up">
                    <div class="glass-card p-4 h-100 text-center position-relative">
                        
                        <!-- Wadah Foto Khadim -->
                        <?php if(!empty($row['foto_khadim'])): ?>
                            <img src="admin/assets/images-khadim/<?php echo htmlspecialchars($row['foto_khadim']); ?>" class="khadim-img mb-4 mx-auto d-block" alt="Foto Khadim <?php echo htmlspecialchars($row['nama_khadim']); ?>">
                        <?php else: ?>
                            <div class="khadim-img d-flex align-items-center justify-content-center bg-light mx-auto mb-4">
                                <i class="bi bi-person-fill icon-placeholder"></i>
                            </div>
                        <?php endif; ?>
                        
                        <h5 class="fw-bold text-purple mb-3"><?php echo htmlspecialchars($row['sesi_ibadah']); ?></h5>
                        <hr class="divider-line">
                        
                        <!-- Rincian Tugas Khadim -->
                        <div class="text-start mt-3 data-petugas-block">
                            <p class="mb-2"><small class="text-muted d-block">Nama Khadim</small><strong><i class="bi bi-person-badge me-2 text-purple"></i> <?php echo htmlspecialchars($row['nama_khadim']); ?></strong></p>
                            <p class="mb-2"><small class="text-muted d-block">KPI</small><strong><i class="bi bi-diagram-3 me-2 text-purple"></i> <?php echo htmlspecialchars($row['kpi']); ?></strong></p>
                            <p class="mb-2"><small class="text-muted d-block">Penerima Jemaat</small><strong><i class="bi bi-people me-2 text-purple"></i> <?php echo htmlspecialchars($row['penerima_jemaat']); ?></strong></p>
                            <p class="mb-2"><small class="text-muted d-block">Doa & Pembacaan</small><strong><i class="bi bi-book-half me-2 text-purple"></i> <?php echo htmlspecialchars($row['doa_pembacaan']); ?></strong></p>
                            <p class="mb-2"><small class="text-muted d-block">Puji-pujian</small><strong><i class="bi bi-music-note-beamed me-2 text-purple"></i> <?php echo htmlspecialchars($row['puji_pujian']); ?></strong></p>
                            <p class="mb-3"><small class="text-muted d-block">Doa Persembahan</small><strong><i class="bi bi-cash-coin me-2 text-purple"></i> <?php echo htmlspecialchars($row['doa_persembahan']); ?></strong></p>
                        </div>
                        
                    </div>
                </div>
                <?php endwhile; ?>
            </div>
            
        <?php else: ?>
            <!-- ==============================================
                 TAMPILAN MODE DAFTAR UTAMA (GRID ARSIP GALERI)
                 ============================================== -->
            <div class="row g-4" data-aos="fade-up" data-aos-delay="200">
                <?php if(mysqli_num_rows($query) > 0): ?>
                    <?php while($row = mysqli_fetch_assoc($query)): ?>
                    <div class="col-12 col-sm-6 col-md-4 col-lg-3">
                        
                        <a href="warta-jemaat.php?detail=<?php echo $row['tanggal']; ?>" class="text-decoration-none card-gallery-link">
                            <div class="glass-card h-100 d-flex flex-column overflow-hidden warta-card-hover p-0">
                                
                                <div class="warta-cover-wrapper">
                                    <img src="assets/images/cover_warta_default.png" alt="Cover Majalah Warta Jemaat" class="w-100 h-100 object-fit-cover">
                                    <div class="warta-overlay">
                                        <span class="btn btn-light rounded-pill fw-bold text-purple px-4 shadow-sm">
                                            <i class="bi bi-book-half me-2"></i>Buka Warta
                                        </span>
                                    </div>
                                </div>
                                
                                <div class="p-4 text-center bg-white wrapper-bottom-card">
                                    <span class="badge bg-purple-soft text-purple mb-2 px-3 py-2 rounded-pill">
                                        <i class="bi bi-calendar-event me-1"></i> Edisi Mingguan
                                    </span>
                                    <h6 class="fw-bolder text-dark mb-0 fs-5 mt-2 title-date-warta">
                                        <?php echo date('d F Y', strtotime($row['tanggal'])); ?>
                                    </h6>
                                </div>

                            </div>
                        </a>
                        
                    </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <div class="col-12">
                        <div class="glass-card p-5 text-center">
                            <i class="bi bi-inbox fs-1 text-muted mb-3 d-block"></i>
                            <p class="text-muted fs-5 m-0">Belum ada arsip warta yang dipublikasikan.</p>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        <?php endif; ?>
        
    <?php endif; ?>
</div>

<?php include 'footer.php'; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script>
    AOS.init({ once: true, offset: 80 });
</script>
</body>
</html> 