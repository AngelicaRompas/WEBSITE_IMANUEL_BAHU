<?php ob_start(); ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Visi & Misi - GMIM Imanuel Bahu</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,700;0,900;1,400;1,700;1,900&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <link rel="stylesheet" href="assets/css/style-beranda.css?v=<?php echo time(); ?>">
    
    <style>
        /* PERBAIKAN STYLING HEADER AGAR PREMIUM DAN ELEGAN */
        .page-header-premium {
            padding-top: 6rem;
            padding-bottom: 3rem;
            position: relative;
        }
        
        .main-title-aesthetic {
            font-family: 'Playfair Display', serif;
            font-size: clamp(3rem, 6vw, 4.5rem);
            font-weight: 900;
            color: #000000;
            letter-spacing: -1px;
            line-height: 1;
        }
        
        .sub-title-aesthetic {
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: clamp(1rem, 2vw, 1.25rem);
            font-weight: 600;
            color: #6f42c1;
            letter-spacing: 0.5px;
        }

        .church-highlight {
            color: #6f42c1;
            font-weight: 800;
            position: relative;
            display: inline-block;
        }

        /* Ornamen Garis Minimalis di Bawah Judul */
        .premium-divider {
            display: flex;
            align-items: center;
            justify-content: center;
            margin-top: 1.5rem;
            margin-bottom: 1.5rem;
        }
        .premium-divider .line {
            height: 1px;
            width: 80px;
            background: linear-gradient(90deg, transparent, #6f42c1, transparent);
        }
        .premium-divider .dot {
            width: 6px;
            height: 6px;
            background-color: #6f42c1;
            border-radius: 50%;
            margin: 0 10px;
            box-shadow: 0 0 8px #6f42c1;
        }

        /* Styling List Misi */
        .mission-list {
            list-style: none;
            padding-left: 0;
        }
        .mission-list li {
            position: relative;
            padding-left: 2.5rem;
            margin-bottom: 1.5rem;
            font-weight: 500;
            color: #334155;
            font-size: 1.05rem;
            transition: transform 0.2s ease;
        }
        .mission-list li:hover {
            transform: translateX(3px);
        }
        .mission-list li i {
            position: absolute;
            left: 0;
            top: 2px;
            color: #6f42c1;
            font-size: 1.3rem;
        }
        
        .visi-text-callout {
            font-family: 'Playfair Display', serif;
            font-size: clamp(1.8rem, 3vw, 2.4rem);
            line-height: 1.4;
            color: #0f172a;
        }

        @media (min-width: 992px) {
            .divider-vertical {
                border-right: 1px dashed rgba(111, 66, 193, 0.25);
                padding-right: 3.5rem;
            }
            .mission-content {
                padding-left: 3.5rem;
            }
        }
    </style>
</head>
<body>

<div class="digital-grid"></div>
<div class="aurora-container">
    <div class="aurora-blob blob-blue"></div>
    <div class="aurora-blob blob-soft"></div>
</div>

<?php 
include 'koneksi.php'; 
include 'navbar.php'; 
?>

<section class="page-header-premium text-center z-2">
    <div class="container">
        <div data-aos="fade-down" data-aos-duration="800">
            <span class="badge rounded-pill px-3 py-2 small mb-3 fw-bold tracking-widest" style="background-color: #f3effb; color: #6f42c1; letter-spacing: 2px;">
                <i class="bi bi-shield-shaded me-2"></i>LANDASAN FILOSOFIS GEREJA
            </span>
        </div>
        
        <h1 class="main-title-aesthetic mb-2" data-aos="fade-down" data-aos-duration="1000" data-aos-delay="100">
            Visi & Misi
        </h1>
        
        <div class="premium-divider" data-aos="zoom-in" data-aos-duration="800" data-aos-delay="200">
            <div class="line"></div>
            <div class="dot"></div>
            <div class="line"></div>
        </div>
        
        <p class="sub-title-aesthetic fw-medium mb-0" data-aos="fade-up" data-aos-duration="1000" data-aos-delay="300">
            Landasan Komitmen Pelayanan Jemaat <span class="church-highlight">GMIM Imanuel Bahu</span>
        </p>
    </div>
</section>

<section class="pb-5 mb-5 position-relative z-2">
    <div class="container">
        <div class="glass-card p-4 p-md-5 mx-auto shadow-lg" style="max-width: 1000px;" data-aos="zoom-in" data-aos-duration="1000">
            <div class="row g-5 align-items-center">
                
                <div class="col-lg-6 text-center divider-vertical">
                    <div class="d-inline-block p-3 rounded-circle mb-4" style="background-color: #f3effb; color: #6f42c1;">
                        <i class="bi bi-eye-fill" style="font-size: 2.5rem;"></i>
                    </div>
                    <h6 class="fw-bold tracking-wider mb-3 text-uppercase" style="letter-spacing: 2px; color: #6f42c1;" !important;">Panggilan Visi</h6>
                    <h2 class="visi-text-callout fw-bold fst-italic">"GMIM yang Kudus, Am dan Rasuli"</h2>
                </div>

                <div class="col-lg-6 mission-content">
                    <div class="text-center text-lg-start mb-4">
                        <h6 class="fw-bold tracking-wider mb-2 text-uppercase" style="letter-spacing: 2px; color: #6f42c1;" !important;">Implementasi Misi</h6>
                        <p class="text-muted small">Langkah operasional jemaat dalam bersekutu dan melayani:</p>
                    </div>
                    
                    <ul class="mission-list">
                        <li data-aos="fade-up" data-aos-delay="200">
                            <i class="bi bi-check-circle-fill"></i>
                            Peningkatan Karakter & Spiritualitas Jemaat
                        </li>
                        <li data-aos="fade-up" data-aos-delay="300">
                            <i class="bi bi-check-circle-fill"></i>
                            Pelayanan Misi Holistik yang Membawa Damai
                        </li>
                        <li data-aos="fade-up" data-aos-delay="400">
                            <i class="bi bi-check-circle-fill"></i>
                            Membangun Keesaan Gereja Skala Global
                        </li>
                        <li data-aos="fade-up" data-aos-delay="500">
                            <i class="bi bi-check-circle-fill"></i>
                            Penguatan Kapasitas Kelembagaan Sinodal
                        </li>
                    </ul>
                </div>

            </div>
        </div>
    </div>
</section>

<?php include 'footer.php'; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script>
    AOS.init({ once: true, offset: 80 });
</script>
</body>
</html>