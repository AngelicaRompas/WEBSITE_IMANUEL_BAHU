<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sejarah - GMIM Imanuel Bahu</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,700;0,900;1,400;1,700;1,900&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style-beranda.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="assets/css/style-sejarah.css?v=<?php echo time(); ?>">
</head>
<body>

<div class="digital-grid"></div>
<div class="aurora-container">
    <div class="aurora-blob blob-blue"></div>
    <div class="aurora-blob blob-soft"></div>
</div>

<?php 
include 'navbar.php'; 
include 'koneksi.php'; 
$queryKetua = mysqli_query($koneksi, "SELECT * FROM ketua_jemaat ORDER BY tahun_mulai DESC");
?>

<section class="page-header-premium text-center z-2">
    <div class="container">
        <h1 class="main-title-aesthetic mb-3">Sejarah Gereja</h1>
    </div>
</section>

<div class="container pb-5 mb-5 position-relative z-2">
    <div class="row g-4 mb-5">
        <div class="col-lg-6 mb-4" data-aos="fade-up">
            <div class="glass-card">
                <img src="assets/images/GEDUNG LAMA BARU.jpg" class="img-fluid rounded-4 mb-4" style="width: 100%; height: 300px; object-fit: cover;">
                <h5 class="fw-bold" style="color: #6f42c1;">Gedung Gereja Lama & Gedung Gereja Saat Ini</h5>
                <p class="text-muted small">Pusat Peribadatan Jemaat Yang Berlokasi di Jl. Wolter Monginsidi, Bahu, Kec. Malalayang, Kota Manado, Sulawesi Utara.</p>
            </div>
        </div>
        
        <div class="col-lg-6" data-aos="fade-up" data-aos-delay="100">
            <?php 
            $data = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT konten FROM profil WHERE jenis='sejarah'"));
            ?>
            <div class="glass-card">
                <h4 class="fw-bold mb-3"><i class="bi bi-book-half me-2" style="color: #6f42c1;"></i>Ringkasan Berdirinya</h4>
                <div class="text-muted" style="line-height: 1.8; text-align: justify;">
                    <?php echo nl2br($data['konten'] ?? 'Data belum tersedia.'); ?>
                </div>
            </div>
        </div>
    </div>

    <div class="text-center mt-5 pt-4" data-aos="fade-up">
        <div class="header-box mb-5">DAFTAR KETUA JEMAAT</div>
        <div class="row row-cols-1 row-cols-md-3 row-cols-lg-4 g-4">
            <?php 
            $queryGrid = mysqli_query($koneksi, "SELECT * FROM ketua_jemaat ORDER BY tahun_mulai DESC");
            while($k = mysqli_fetch_assoc($queryGrid)): 
                $foto = (!empty($k['foto']) && file_exists("assets/images/" . $k['foto'])) ? "assets/images/" . $k['foto'] : "assets/images/default.jpg";
            ?>
            <div class="col">
                <div class="glass-card text-center p-3">
                    <img src="<?php echo $foto; ?>" class="ketua-img mb-3" data-bs-toggle="modal" data-bs-target="#modalFoto<?php echo $k['id']; ?>" alt="Foto">
                    <h6 class="fw-bold mb-1 text-dark"><?php echo htmlspecialchars($k['nama']); ?></h6>
                    <small class="text-primary fw-bold"><i class="bi bi-calendar-check me-1"></i><?php echo $k['tahun_mulai'] . ' - ' . $k['tahun_selesai']; ?></small>
                </div>
            </div>
            <?php endwhile; ?>
        </div>
    </div>
</div> <?php 
$queryModal = mysqli_query($koneksi, "SELECT * FROM ketua_jemaat");
while($k = mysqli_fetch_assoc($queryModal)): 
    $foto = (!empty($k['foto']) && file_exists("assets/images/" . $k['foto'])) ? "assets/images/" . $k['foto'] : "assets/images/default.jpg";
?>
<div class="modal fade" id="modalFoto<?php echo $k['id']; ?>" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 border-0 shadow-lg">
            <div class="modal-header border-0 pb-0">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center pt-0">
                <img src="<?php echo $foto; ?>" class="img-fluid rounded-3 mb-3" style="max-height: 450px; width: auto;" alt="Foto">
                <h5 class="fw-bold text-dark"><?php echo htmlspecialchars($k['nama']); ?></h5>
            </div>
        </div>
    </div>
</div>
<?php endwhile; ?>

<?php include 'footer.php'; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script>AOS.init({ duration: 800, once: true });</script>
</body>
</html>