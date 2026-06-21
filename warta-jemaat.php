<?php 
session_start();
include 'koneksi.php'; 

$kode_valid = ['JEMAAT2026', 'IMANUELBAHU', 'PELAYANAN'];
$akses_warta = isset($_SESSION['akses_warta']) && $_SESSION['akses_warta'] === true;

// Logika Verifikasi Kode Referral
if (isset($_POST['btn_akses'])) {
    if (in_array(strtoupper(trim($_POST['kode_referral'])), $kode_valid)) {
        $_SESSION['akses_warta'] = true;
        header("Location: warta-jemaat.php"); 
        exit;
    } else { 
        $error = "Kode referral salah!"; 
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
    
    <!-- Tautan CSS Eksternal -->
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

<!-- Premium Header (Diperbaiki Spasi dan Ketebalan Teksnya) -->
<section class="page-header-premium text-center z-2 mt-5 pt-5">
    <div class="container mt-3">
        <div data-aos="fade-down" data-aos-duration="800">
            <span class="badge rounded-pill px-3 py-2 small mb-3 fw-bold tracking-widest" 
            style="letter-spacing: 2px; background-color: rgba(111, 66, 193, 0.1); color: #6f42c1;">
            <i class="bi bi-journal-bookmark-fill me-2"></i>INFORMASI DIGITAL
            </span>      
        </div>
        <!-- Menambahkan fw-bolder dan Inline Font-Family agar judul dijamin tebal -->
        <h1 class="main-title-aesthetic fw-bolder mb-3 text-dark" data-aos="fade-down" data-aos-duration="1000" data-aos-delay="100" style="font-family: 'Plus Jakarta Sans', sans-serif;">
            Warta Jemaat
        </h1>
        <p class="text-muted fw-medium mb-0" data-aos="fade-up" data-aos-duration="1000" data-aos-delay="200">
            Pusat Informasi Pelayanan dan Jadwal Ibadah GMIM Imanuel Bahu
        </p>
    </div>
</section>

<div class="container pb-5 mb-5 position-relative z-2 mt-4">
    <?php if (!$akses_warta): ?>
        <!-- Tampilan Halaman Terkunci -->
        <div class="row justify-content-center" data-aos="zoom-in" data-aos-delay="300">
            <div class="col-md-5 glass-card p-5 text-center mt-2">
                <i class="bi bi-shield-lock text-purple fs-1 mb-3 d-block"></i>
                <h5 class="fw-bold my-3">Area Jemaat Terbatas</h5>
                <p class="text-muted small mb-4">Silakan masukkan kode akses untuk melihat dokumen warta jemaat.</p>
                <form method="POST">
                    <input type="text" name="kode_referral" class="form-control mb-3 text-center rounded-pill py-2" placeholder="Masukkan Kode Akses..." required>
                    <button type="submit" name="btn_akses" class="btn btn-purple w-100 py-2 fw-bold">Verifikasi Akses</button>
                </form>
                <?php if(isset($error)) echo "<p class='text-danger mt-3 mb-0 small fw-bold'>$error</p>"; ?>
            </div>
        </div>
        
    <?php else: ?>
        <!-- Tampilan Header Jika Akses Diberikan -->
        <div class="d-flex justify-content-between align-items-center mb-4 mt-3" data-aos="fade-in">
            <h4 class="fw-bold m-0"><i class="bi bi-collection-play text-primary me-2"></i> <?php echo $detail_tgl ? 'Detail Warta Digital' : 'Arsip Warta Jemaat'; ?></h4>
            <a href="warta-jemaat.php?logout=1" class="btn btn-outline-danger btn-sm rounded-pill px-4 fw-bold shadow-sm">
                <i class="bi bi-box-arrow-right me-1"></i> Tutup Akses
            </a>
        </div>

        <?php if ($detail_tgl): ?>
            <!-- Tampilan Mode Detail -->
            <a href="warta-jemaat.php" class="btn btn-dark rounded-pill px-4 py-2 fw-semibold shadow-sm mb-4 d-inline-block" data-aos="fade-right">
                <i class="bi bi-arrow-left me-2"></i> Kembali ke Daftar
            </a>
            <div class="row g-4">
                <?php while($row = mysqli_fetch_assoc($query)): ?>
                <div class="col-md-4" data-aos="fade-up">
                    <div class="glass-card p-4 h-100 text-center position-relative">
                        
                        <!-- Menampilkan Gambar Khadim -->
                        <?php if(!empty($row['foto_khadim'])): ?>
                            <img src="admin/assets/images-khadim/<?php echo htmlspecialchars($row['foto_khadim']); ?>" class="khadim-img mb-4 mx-auto d-block" alt="Foto Khadim <?php echo htmlspecialchars($row['nama_khadim']); ?>">
                        <?php else: ?>
                            <div class="khadim-img d-flex align-items-center justify-content-center bg-light mx-auto mb-4">
                                <i class="bi bi-person-fill icon-placeholder"></i>
                            </div>
                        <?php endif; ?>
                        
                        <h5 class="fw-bold text-purple mb-3"><?php echo htmlspecialchars($row['sesi_ibadah']); ?></h5>
                        <hr style="opacity: 0.1;">
                        
                        <div class="text-start mt-3">
                            <p class="mb-2"><small class="text-muted d-block">Nama Khadim</small><strong><i class="bi bi-person-badge me-2 text-purple"></i> <?php echo htmlspecialchars($row['nama_khadim']); ?></strong></p>
                            <p class="mb-2"><small class="text-muted d-block">Penerima Jemaat</small><strong><i class="bi bi-people me-2 text-purple"></i> <?php echo htmlspecialchars($row['penerima_jemaat']); ?></strong></p>
                            <p class="mb-2"><small class="text-muted d-block">Doa & Pembacaan</small><strong><i class="bi bi-book-half me-2 text-purple"></i> <?php echo htmlspecialchars($row['doa_pembacaan']); ?></strong></p>
                            <p class="mb-3"><small class="text-muted d-block">Puji-pujian</small><strong><i class="bi bi-music-note-beamed me-2 text-purple"></i> <?php echo htmlspecialchars($row['puji_pujian']); ?></strong></p>
                        </div>
                        
                        <!-- Link Unduh PDF -->
                        <?php if(!empty($row['file_pdf'])): ?>
                            <div class="mt-4 pt-3 border-top" style="border-color: rgba(0,0,0,0.05) !important;">
                                <a href="admin/assets/document_warta/<?php echo htmlspecialchars($row['file_pdf']); ?>" target="_blank" class="btn btn-sm btn-purple w-100 py-2 fw-bold shadow-sm">
                                    <i class="bi bi-cloud-arrow-down-fill me-2"></i> Unduh PDF Warta
                                </a>
                            </div>
                        <?php endif; ?>
                        
                    </div>
                </div>
                <?php endwhile; ?>
            </div>
            
        <?php else: ?>
            <!-- Tampilan Mode Daftar Tanggal -->
            <div class="glass-card p-4 shadow-sm" data-aos="fade-up" data-aos-delay="200">
                <?php if(mysqli_num_rows($query) > 0): ?>
                    <div class="list-group list-group-flush">
                        <?php while($row = mysqli_fetch_assoc($query)): ?>
                        <div class="list-group-item d-flex justify-content-between align-items-center p-3 mb-2 border-0 rounded" style="background-color: transparent;">
                            <span class="fw-bold fs-5 text-dark"><i class="bi bi-calendar-event-fill text-purple me-3"></i> <?php echo date('l, d F Y', strtotime($row['tanggal'])); ?></span>
                            <a href="warta-jemaat.php?detail=<?php echo $row['tanggal']; ?>" class="btn btn-purple rounded-pill px-4 py-2 fw-bold shadow-sm">Buka Digital</a>
                        </div>
                        <?php endwhile; ?>
                    </div>
                <?php else: ?>
                    <p class="text-center text-muted my-4">Belum ada arsip warta yang dipublikasikan.</p>
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