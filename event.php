<?php ob_start(); include 'koneksi.php'; 
date_default_timezone_set('Asia/Makassar');
$today = date('Y-m-d');

$queryNext = mysqli_query($koneksi, "SELECT * FROM events WHERE tanggal >= '$today' AND tanggal != '0000-00-00' AND tanggal IS NOT NULL ORDER BY tanggal ASC");
$queryPast = mysqli_query($koneksi, "SELECT * FROM events WHERE DATE(tanggal) < '$today' AND tanggal != '0000-00-00' AND tanggal IS NOT NULL ORDER BY tanggal DESC");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Acara Kita - GMIM Imanuel Bahu</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style-beranda.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="assets/css/event.css">
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
            <span class="badge rounded-pill px-3 py-2 small mb-3 fw-bold tracking-widest" style="letter-spacing: 2px; background-color: rgba(111, 66, 193, 0.1); color: #6f42c1;">
                <i class="bi bi-calendar-event me-2"></i>AGENDA JEMAAT
            </span>    
        </div>
        <h1 class="main-title-aesthetic mb-3" data-aos="fade-down" data-aos-duration="1000" data-aos-delay="100">Acara Kita</h1>
    </div>
</section>

<div class="container pb-5 mb-5 position-relative z-2">
    <div class="d-flex justify-content-center mb-5" data-aos="zoom-in" data-aos-delay="300">
        <ul class="nav nav-pills" id="pills-tab" role="tablist">
            <li class="nav-item"><button class="nav-link active" data-bs-toggle="pill" data-bs-target="#upcoming" type="button"><i class="bi bi-clock-history me-2"></i>Akan Datang</button></li>
            <li class="nav-item"><button class="nav-link" data-bs-toggle="pill" data-bs-target="#past" type="button"><i class="bi bi-check-all me-2"></i>Terlaksana</button></li>
        </ul>
    </div>

    <div class="tab-content" id="pills-tabContent">
        <div class="tab-pane fade show active" id="upcoming" role="tabpanel">
            <div class="row g-4">
                <?php while($row = mysqli_fetch_assoc($queryNext)): ?>
                <div class="col-lg-4 col-md-6" data-aos="fade-up">
                    <div class="event-card-glass h-100 d-flex flex-column">
                        <img src="assets/gallery/<?php echo htmlspecialchars($row['poster']); ?>" class="w-100 img-event" onerror="this.src='https://via.placeholder.com/400x200'">
                        <div class="card-body p-4">
                            <h5><?php echo htmlspecialchars($row['judul']); ?></h5>
                            <p class="small text-muted"><i class="bi bi-calendar-event me-2"></i><?php echo date('d F Y', strtotime($row['tanggal'])); ?></p>
                            <button class="btn btn-primary rounded-pill w-100" data-bs-toggle="modal" data-bs-target="#modalNext<?php echo $row['id']; ?>">Lihat Detail</button>
                        </div>
                    </div>
                </div>
                <?php endwhile; ?>
            </div>
        </div>

        <div class="tab-pane fade" id="past" role="tabpanel">
            <div class="row g-4">
                <?php while($row = mysqli_fetch_assoc($queryPast)): ?>
                <div class="col-lg-4 col-md-6" data-aos="fade-up">
                    <div class="event-card-archived h-100 shadow-sm">
                        <img src="assets/gallery/<?php echo htmlspecialchars($row['poster']); ?>" class="img-event-archived" onerror="this.src='https://via.placeholder.com/400x200'">
                        <div class="card-body p-3">
                            <h5 class="fw-bold text-dark mt-2 mb-3"><?php echo htmlspecialchars($row['judul']); ?></h5>
                            <button class="btn btn-doc w-100 py-2" data-bs-toggle="modal" data-bs-target="#modalPast<?php echo $row['id']; ?>">Lihat Dokumentasi</button>
                        </div>
                    </div>
                </div>
                <?php endwhile; ?>
            </div>
        </div>
    </div>
</div>

<?php 
// 1. Modal untuk Mendatang
mysqli_data_seek($queryNext, 0); 
while($row = mysqli_fetch_assoc($queryNext)): ?>
<div class="modal fade" id="modalNext<?php echo $row['id']; ?>" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
            <div class="position-relative">
                <img src="assets/gallery/<?php echo htmlspecialchars($row['poster']); ?>" class="w-100" style="height: 200px; object-fit: cover;" onerror="this.src='https://via.placeholder.com/400x200'">
                <div class="position-absolute bottom-0 start-0 w-100 p-3" style="background: linear-gradient(transparent, rgba(0,0,0,0.6));">
                    <h5 class="text-white fw-bold mb-0"><?php echo htmlspecialchars($row['judul']); ?></h5>
                </div>
            </div>

            <div class="modal-body p-4 bg-light">
                <div class="d-flex align-items-center mb-3 text-primary bg-white p-2 rounded-3 shadow-sm border-start border-4 border-primary">
                    <i class="bi bi-calendar-event fs-4 me-3"></i>
                    <div>
                        <small class="text-uppercase text-muted fw-bold d-block" style="font-size: 0.65rem; letter-spacing: 1px;">Tanggal Pelaksanaan</small>
                        <span class="fw-bold text-dark"><?php echo date('d F Y', strtotime($row['tanggal'])); ?></span>
                    </div>
                </div>
                
                <div class="bg-white p-3 rounded-3 shadow-sm">
                    <small class="text-uppercase text-muted fw-bold d-block mb-2" style="font-size: 0.65rem; letter-spacing: 1px;">Deskripsi Acara</small>
                    <p class="text-muted small mb-0" style="line-height: 1.6;">
                        <?php echo nl2br(htmlspecialchars($row['deskripsi'])); ?>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
<?php endwhile;

// 2. Modal untuk Terlaksana
mysqli_data_seek($queryPast, 0); 
while($row = mysqli_fetch_assoc($queryPast)): ?>
<div class="modal fade" id="modalPast<?php echo $row['id']; ?>" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
            <div class="p-4 text-white" style="background: linear-gradient(135deg, #6f42c1 0%, #4a2b85 100%);">
                <h4 class="fw-bold mb-0"><i class="bi bi-check-circle-fill me-2"></i><?php echo htmlspecialchars($row['judul']); ?></h4>
            </div>

            <div class="modal-body p-4 bg-light">
                <div class="row">
                    <div class="col-lg-5 mb-3">
                        <div class="bg-white p-2 rounded-4 shadow-sm border">
                            <img src="assets/gallery/<?php echo htmlspecialchars($row['poster']); ?>" class="img-fluid rounded-3 w-100">
                        </div>
                    </div>
                    
                    <div class="col-lg-7">
                        <div class="bg-white p-3 rounded-4 shadow-sm mb-3 border-start border-4 border-primary">
                            <small class="text-uppercase text-muted fw-bold d-block" style="font-size: 0.65rem; letter-spacing: 1px;">Tanggal Pelaksanaan</small>
                            <span class="fw-bold text-dark"><i class="bi bi-calendar3 me-2 text-primary"></i><?php echo date('d F Y', strtotime($row['tanggal'])); ?></span>
                        </div>
                        
                        <div class="bg-white p-3 rounded-4 shadow-sm">
                            <small class="text-uppercase text-muted fw-bold d-block mb-2" style="font-size: 0.65rem; letter-spacing: 1px;">Deskripsi Acara</small>
                            <p class="text-muted small mb-0" style="line-height: 1.6;"><?php echo nl2br(htmlspecialchars($row['deskripsi'])); ?></p>
                        </div>
                    </div>
                </div>

                <div class="d-flex align-items-center mt-4 mb-3">
                    <h6 class="fw-bold mb-0 text-dark text-uppercase" style="font-size: 0.8rem; letter-spacing: 1px;">
                        <i class="bi bi-images me-2 text-primary"></i>Dokumentasi Foto
                    </h6>
                    <div class="flex-grow-1 border-top ms-3 opacity-25"></div>
                </div>

                <div class="row g-2">
                    <?php 
                    $galeri = mysqli_query($koneksi, "SELECT * FROM event_gallery WHERE event_id = '".$row['id']."'");
                    while($g = mysqli_fetch_assoc($galeri)): ?>
                        <div class="col-3">
                            <a href="#" data-bs-toggle="modal" data-bs-target="#modalFoto<?php echo $g['id']; ?>" class="d-block border rounded-3 overflow-hidden shadow-sm hover-zoom">
                                <img src="assets/gallery/<?php echo $g['foto_path']; ?>" class="w-100 h-100" style="object-fit:cover; aspect-ratio:1/1;">
                            </a>
                        </div>
                    <?php endwhile; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php endwhile; ?>

<?php 
// 3. Modal Lightbox (Pop-up Foto agar tetap di dalam website)
mysqli_data_seek($queryPast, 0); 
while($row = mysqli_fetch_assoc($queryPast)): 
    $galeri = mysqli_query($koneksi, "SELECT * FROM event_gallery WHERE event_id = '".$row['id']."'");
    while($g = mysqli_fetch_assoc($galeri)): ?>
    <div class="modal fade" id="modalFoto<?php echo $g['id']; ?>" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content border-0 bg-transparent">
                <div class="modal-body p-0 text-center">
                    <img src="assets/gallery/<?php echo $g['foto_path']; ?>" class="img-fluid rounded-3 shadow-lg border border-white border-2">
                    <button type="button" class="btn btn-dark rounded-pill mt-3 shadow" data-bs-dismiss="modal">
                        <i class="bi bi-x-lg me-1"></i> Tutup
                    </button>
                </div>
            </div>
        </div>
    </div>
<?php endwhile; endwhile; ?>

<?php include 'footer.php'; ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script>AOS.init({ once: true, offset: 50 });</script>
</body>
</html>