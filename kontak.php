<?php 
include 'koneksi.php'; 
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kontak - GMIM Imanuel Bahu</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,700;0,900;1,400;1,700;1,900&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <link rel="stylesheet" href="assets/css/style-beranda.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="assets/css/style-kontak.css?v=<?php echo time(); ?>">
    
</head>
<body>

<div class="digital-grid"></div>
<div class="aurora-container">
    <div class="aurora-blob blob-blue"></div>
    <div class="aurora-blob blob-soft"></div>
</div>

<?php include 'navbar.php'; ?>

<section class="page-header-premium text-center z-2">
    <div class="container">
        <div data-aos="fade-down" data-aos-duration="800">
            <span class="badge bg-primary bg-opacity-10 text-primary rounded-pill px-3 py-2 small mb-3 fw-bold tracking-widest" style="letter-spacing: 2px;">
                <i class="bi bi-chat-left-dots-fill me-2"></i>HUBUNGI KAMI
            </span>
        </div>
        <h1 class="main-title-aesthetic mb-3" data-aos="fade-down" data-aos-duration="1000" data-aos-delay="100">
            Kontak Pelayanan
        </h1>
        <p class="text-muted fw-medium mb-0" data-aos="fade-up" data-aos-duration="1000" data-aos-delay="200">
            Sampaikan Saran, Pertanyaan, atau Permohonan Pelayanan Melalui Form Di Bawah Ini.
        </p>
    </div>
</section>

<div class="container pb-5 mb-5 position-relative z-2">
    <div class="row g-4 justify-content-center">
        <div class="col-lg-4" data-aos="fade-right" data-aos-delay="400">
            <div class="glass-card-custom p-4 h-100">
                <h4 class="fw-bold text-dark mb-4">Informasi Kantor</h4>
                
                <div class="d-flex align-items-start mb-4">
                    <div class="icon-circle-purple me-3"><i class="bi bi-geo-alt-fill"></i></div>
                    <div>
                        <h6 class="fw-bold mb-1">Alamat</h6>
                        <p class="text-muted small mb-0">Jl. Wolter Monginsidi, Bahu, Kec. Malalayang, Kota Manado.</p>
                    </div>
                </div>

                <div class="d-flex align-items-start mb-4">
                    <div class="icon-circle-purple me-3"><i class="bi bi-envelope-at-fill"></i></div>
                    <div>
                        <h6 class="fw-bold mb-1">Email Resmi</h6>
                        <p class="text-muted small mb-0">admin@imanuelbahu.org</p>
                    </div>
                </div>

                <div class="d-flex align-items-start">
                    <div class="icon-circle-purple me-3"><i class="bi bi-clock-fill"></i></div>
                    <div>
                        <h6 class="fw-bold mb-1">Jam Pelayanan</h6>
                        <p class="text-muted small mb-0">Senin - Sabtu: 08:00 - 16:00 WITA</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-7" data-aos="fade-left" data-aos-delay="500">
            <div class="glass-card-custom p-4 p-md-5">
                <form action="proses_kontak.php" method="POST">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-dark">Nama Lengkap</label>
                            <input type="text" name="nama" class="form-control form-control-aesthetic" placeholder="Nama Anda" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-dark">Email Anda</label>
                            <input type="email" name="email" class="form-control form-control-aesthetic" placeholder="email@contoh.com" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label small fw-bold text-dark">Pesan / Permohonan Pelayanan</label>
                            <textarea name="pesan" class="form-control form-control-aesthetic" rows="5" placeholder="Tuliskan pesan Anda di sini..." required></textarea>
                        </div>
                        <div class="col-12 mt-4">
                            <button type="submit" class="btn btn-primary btn-lg rounded-pill px-5 fw-bold shadow-sm w-100 w-md-auto">
                                <i class="bi bi-send-fill me-2"></i>Kirim Pesan Sekarang
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script>
    AOS.init({ 
        once: true,
        duration: 1000,
        offset: 100
    });
</script>
</body>
</html>