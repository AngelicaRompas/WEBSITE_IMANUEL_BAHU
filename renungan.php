<?php ob_start(); ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Arsip Renungan Jemaat - GMIM Imanuel Bahu</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700;900&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style-beranda.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="assets/css/style-renungan.css?v=<?php echo time(); ?>">
</head>
<body>

<?php include 'navbar.php'; include 'koneksi.php'; ?>

<!-- HEADER ESTETIS -->
<section class="page-header-premium text-center z-2">
    <div class="container">
        <div data-aos="fade-down" data-aos-duration="800">
            <span class="badge rounded-pill px-3 py-2 small mb-3 fw-bold tracking-widest" style="background-color: #f3effb; color: #6f42c1; letter-spacing: 2px;">
                <i class="bi bi-book-half me-2"></i>RENUNGAN TEMATIK
            </span>      
        </div>
        <h1 class="main-title-aesthetic mb-2" data-aos="fade-down" data-aos-duration="1000" data-aos-delay="100">
            Renungan Jemaat
        </h1>
        <div class="premium-divider" data-aos="zoom-in" data-aos-duration="800" data-aos-delay="200">
            <div class="line"></div>
            <div class="dot"></div>
            <div class="line"></div>
        </div>
        <p class="sub-title-aesthetic fw-medium mb-0" data-aos="fade-up" data-aos-duration="1000" data-aos-delay="300">
            Renungan Harian Keluarga dan Renungan Tematik GMIM Imanuel Bahu
        </p>
    </div>
</section>

<div class="container pb-5 mb-5 position-relative z-2">
    <div class="row g-5">
        <div class="col-lg-7">
            <h4 class="fw-bold mb-4 text-white" data-aos="fade-right" style="text-shadow: 1px 1px 4px rgba(0,0,0,0.5);">
                <i class="bi bi-archive-fill me-2"></i>Arsip RHK
            </h4>
            <div class="row g-4">
                <?php
                $query = mysqli_query($koneksi,"SELECT * FROM renungan_harian ORDER BY tanggal DESC");
                $modals = [];
                while($data = mysqli_fetch_assoc($query)): $modals[] = $data; ?>
                <div class="col-md-6" data-aos="fade-up">
                    <div class="card border-0 shadow-sm p-4 rounded-4 h-100 d-flex flex-column align-items-center justify-content-center text-center" 
                        style="background: #ffffff !important; border: 1px solid rgba(0,0,0,0.05);">
                        <h4 class="fw-bold text-dark mb-0">RHK</h4>
                        <p class="text-muted small mb-3"><?php echo date('d M Y', strtotime($data['tanggal'])); ?></p>
                        
                        <!-- Tombol Ungu yang Estetis -->
                        <button class="btn btn-primary rounded-pill px-4 fw-bold shadow-sm" 
                                style="background-color: #6f42c1 !important; border-color: #6f42c1 !important;"
                                data-bs-toggle="modal" 
                                data-bs-target="#mdl<?php echo $data['id']; ?>">
                            Baca Renungan
                        </button>
                    </div>
                </div>
                <?php endwhile; ?>
            </div>
        </div>

        <div class="col-lg-5">
            <div class="search-glass-container renungan-sticky p-4 rounded-4" data-aos="fade-left" style="background: rgba(255,255,255,0.9);">
                <div class="text-center mb-4">
                    <span class="badge rounded-pill bg-primary bg-opacity-10 text-primary px-3 py-2 mb-3">
                        <i class="bi bi-stars me-2"></i>RENUNGAN TEMATIK
                    </span>
                    <h4 class="fw-bold mb-2">Temukan Penguatan Firman</h4>
                    <p class="text-muted small mb-0">Pilih topik pergumulan atau kebutuhan rohani Anda</p>
                </div>
                <!-- Daftar Topik -->
                <div class="d-flex flex-wrap gap-2 justify-content-center mb-4">
                    <?php foreach(['Khawatir','Kesepian','Keluarga','Kasih','Pengampunan','Sakit','Masa Depan','Pekerjaan','Putus Asa','Anak Muda','Stress','Bersyukur'] as $topik): ?>
                    <span class="badge rounded-pill prompt-badge" style="cursor:pointer; background:#e2e8f0; color:#475569;" onclick="cariRenungan('<?php echo $topik; ?>')"><?php echo $topik; ?></span>
                    <?php endforeach; ?>
                </div>
                <div id="aiRes" class="p-3 shadow-sm rounded-3 bg-light text-secondary">
                    <div class="text-center py-4">
                        <i class="bi bi-heart-pulse fs-2 text-primary mb-2 d-block"></i>
                        <h6 class="fw-bold text-dark">Renungan Tematik</h6>
                        <p class="text-muted small mb-0">Klik topik untuk memulai.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal dan Script tetap sama -->
<?php foreach($modals as $data): ?>
<div class="modal fade" id="mdl<?php echo $data['id']; ?>" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content p-4 rounded-4 border-0">
            <h3 class="fw-bold mb-3"><?php echo $data['judul']; ?></h3>
            <p class="text-primary fw-bold"><?php echo $data['nas_alkitab']; ?></p>
            <div class="text-secondary"><?php echo nl2br(htmlspecialchars($data['isi_renungan'])); ?></div>
            <hr><p class="fst-italic text-warning mb-0">"<?php echo $data['doa']; ?>"</p>
        </div>
    </div>
</div>
<?php endforeach; ?>

<script>
    function cariRenungan(keyword){
        const resBox = document.getElementById('aiRes');
        resBox.innerHTML = '<div class="text-center py-5"><div class="spinner-border text-primary"></div></div>';
        fetch('get-renungan.php',{method:'POST',headers:{'Content-Type':'application/x-www-form-urlencoded'},body:'keyword=' + encodeURIComponent(keyword)})
        .then(response => response.text())
        .then(data => { resBox.innerHTML = data; });
    }
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script>AOS.init({once:true, offset:80});</script>
</body>
</html>