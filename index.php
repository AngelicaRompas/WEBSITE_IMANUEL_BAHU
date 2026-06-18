<?php ob_start(); ?>

<?php
include 'koneksi.php';  
// Ambil 3 event terlaksana terbaru
$queryNews = mysqli_query($koneksi, "SELECT * FROM events WHERE DATE(tanggal) < CURDATE() AND tanggal != '0000-00-00' ORDER BY tanggal DESC LIMIT 3");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GMIM Imanuel Bahu - Beranda</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,700;0,900;1,400;1,700;1,900&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style-beranda.css?v=<?php echo time(); ?>">
    
</head>
<body>

<div class="digital-grid"></div>
<div class="aurora-container">
    <div class="aurora-blob blob-blue"></div>
    <div class="aurora-blob blob-soft"></div>
</div>

<?php 
include 'navbar.php'; 
?>

<section class="hero-section py-5">
    <div class="container text-center position-relative">
        <div data-aos="fade-down" data-aos-duration="1000">
            <h1 class="hero-title fw-bolder mb-5 mt-3">
                Selamat Datang di <br>
                <span class="church-name-aesthetic fst-italic" style="font-size: 4.5rem;">"GMIM Imanuel Bahu"</span>
            </h1>
        </div>

        <div class="glass-card p-4 mx-auto" style="max-width: 320px;" data-aos="zoom-in" data-aos-duration="1200" data-aos-delay="200">
            <div class="mb-3">
                <div class="icon-pulse">
                    <i class="bi bi-camera-fill" style="font-size: 1.8rem;"></i>
                </div>
            </div>
            <h5 class="fw-bold mb-4 text-dark">Navigasi Gedung 360°</h5>
            <a href="navigasi.php" class="btn btn-primary rounded-pill w-100 fw-bold shadow-sm" style="font-size: 0.95rem;">
                <i class="bi bi-compass-fill me-2"></i> Mulai Penjelajahan
            </a>
        </div>
    </div>
</section>

<section class="py-5">
    <div class="container-fluid px-5">
        <h2 class="fw-bold mb-5 text-dark text-center" data-aos="fade-up">Struktur Pelayanan</h2>
        <div class="row g-4 justify-content-center"> 
            <?php
            $profil_items = [
                ["foto" => "pendeta.png", "title" => "Pendeta", "desc" => "Pelayan Firman & Sakramen", "link" => "data-jemaat.php#pills-bpmj"],
                ["foto" => "pelsus.jpg", "title" => "Pelayan Khusus", "desc" => "Penatua & Diaken Kolom", "link" => "data-jemaat.php#pills-pelsus"],
                ["foto" => "bpmj.jpg", "title" => "Badan Pekerja", "desc" => "Majelis Jemaat", "link" => "data-jemaat.php#pills-bpmj"]
            ];
            foreach($profil_items as $item):
                $path_foto = "assets/images/" . $item['foto'];
            ?>
            <div class="col-lg-4 col-md-6" data-aos="fade-up">
                <div class="glass-card h-100 border-0 d-flex flex-column shadow-sm" style="overflow: hidden; border-radius: 20px;">
                    
                    <div style="width: 100%; height: 320px; overflow: hidden; background: #f8f9fa; cursor: pointer;" 
                         onclick="openModal('<?php echo $path_foto; ?>')">
                        <?php if(file_exists($path_foto)): ?>
                            <img src="<?php echo $path_foto; ?>" 
                                 alt="<?php echo $item['title']; ?>" 
                                 class="zoom-img"
                                 style="width: 100%; height: 100%; object-fit: cover; transition: transform 0.5s ease;">
                        <?php else: ?>
                            <div class="d-flex align-items-center justify-content-center h-100 text-muted small">Foto tidak ditemukan</div>
                        <?php endif; ?>
                    </div>
                    
                    <div class="p-4 text-center d-flex flex-column flex-grow-1">
                        <h5 class="fw-bold text-dark mb-1"><?php echo $item['title']; ?></h5>
                        <p class="text-muted small mb-4"><?php echo $item['desc']; ?></p>
                        <a href="<?php echo $item['link']; ?>" 
                           class="btn btn-sm rounded-pill px-4 py-2 fw-bold mt-auto" 
                           style="border: 2px solid #6f42c1; color: #6f42c1; background: transparent; transition: 0.3s;"
                           onmouseover="this.style.backgroundColor='#6f42c1'; this.style.color='white';"
                           onmouseout="this.style.backgroundColor='transparent'; this.style.color='#6f42c1';">
                           Lihat Profil
                        </a>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<div class="modal fade" id="fotoModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content bg-transparent border-0">
      <div class="modal-body p-0">
        <img id="modalImg" src="" class="img-fluid w-100 rounded-4">
      </div>
    </div>
  </div>
</div>


<section class="py-5" id="berita-kegiatan">
    <div class="container">
        <h2 class="fw-bold mb-5 text-center text-dark" data-aos="fade-up">Berita Kegiatan</h2>
        
        <div class="row g-4 justify-content-center">
            <?php 
            if(mysqli_num_rows($queryNews) > 0):
                while($row = mysqli_fetch_assoc($queryNews)): 
            ?>
            <div class="col-lg-4 col-md-6" data-aos="fade-up">
                <div class="glass-card h-100 shadow-sm border-0 d-flex flex-column" style="border-radius: 20px; overflow: hidden;">
                    <div style="height: 200px; overflow: hidden;">
                        <img src="assets/gallery/<?php echo htmlspecialchars($row['poster']); ?>" 
                             class="w-100 h-100 object-fit-cover zoom-img" 
                             alt="<?php echo htmlspecialchars($row['judul']); ?>">
                    </div>
                    <div class="card-body p-4 d-flex flex-column">
                        <span class="badge bg-primary mb-2 align-self-start shadow-sm"><?php echo date('d M Y', strtotime($row['tanggal'])); ?></span>
                        <h6 class="fw-bold text-dark mb-3 line-clamp-2"><?php echo htmlspecialchars($row['judul']); ?></h6>
                        <button class="btn btn-outline-primary w-100 rounded-pill py-2 mt-auto fw-bold" 
                                data-bs-toggle="modal" 
                                data-bs-target="#modalNews<?php echo $row['id']; ?>">
                            Lihat Detail
                        </button>
                    </div>
                </div>
            </div>
            <?php 
                endwhile; 
            else: 
            ?>
                <div class="col-12 text-center py-4">
                    <p class="text-muted">Belum ada berita kegiatan terbaru.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>

<?php 
mysqli_data_seek($queryNews, 0); 
while($row = mysqli_fetch_assoc($queryNews)): ?>
<div class="modal fade" id="modalNews<?php echo $row['id']; ?>" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
            <div class="p-4 text-white" style="background: linear-gradient(135deg, #6f42c1 0%, #4a2b85 100%);">
                <h4 class="fw-bold mb-0"><i class="bi bi-newspaper me-2"></i>Detail Kegiatan</h4>
            </div>
            
            <div class="modal-body p-4 bg-light">
                <div class="row">
                    <div class="col-lg-5 mb-3">
                        <div class="bg-white p-2 rounded-4 shadow-sm border">
                            <img src="assets/gallery/<?php echo htmlspecialchars($row['poster']); ?>" class="img-fluid rounded-3 w-100">
                        </div>
                    </div>
                    <div class="col-lg-7">
                        <h5 class="fw-bold text-dark mb-2"><?php echo htmlspecialchars($row['judul']); ?></h5>
                        <div class="d-flex align-items-center text-primary mb-3">
                            <i class="bi bi-calendar3 me-2"></i> 
                            <span class="small fw-bold"><?php echo date('d F Y', strtotime($row['tanggal'])); ?></span>
                        </div>
                        <div class="bg-white p-3 rounded-4 shadow-sm border border-light">
                            <p class="text-muted small mb-0" style="line-height: 1.7;"><?php echo nl2br(htmlspecialchars($row['deskripsi'])); ?></p>
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
                            <a href="#" data-bs-toggle="modal" data-bs-target="#modalFotoNews<?php echo $g['id']; ?>" class="d-block border rounded-3 overflow-hidden shadow-sm hover-zoom">
                                <img src="assets/gallery/<?php echo $g['foto_path']; ?>" class="w-100 h-100" style="object-fit:cover; aspect-ratio:1/1;">
                            </a>
                        </div>
                    <?php endwhile; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php 
$galeri = mysqli_query($koneksi, "SELECT * FROM event_gallery WHERE event_id = '".$row['id']."'");
while($g = mysqli_fetch_assoc($galeri)): ?>
    <div class="modal fade" id="modalFotoNews<?php echo $g['id']; ?>" tabindex="-1" aria-hidden="true">
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
<?php endwhile; ?>
<?php endwhile; ?>

<section class="schedule-section py-5">
    <div class="container text-center py-4">
        <h2 class="fw-bold mb-2 text-dark" data-aos="fade-up">Jadwal Ibadah Minggu</h2>
        <p class="text-muted mb-5" data-aos="fade-up" data-aos-delay="100">Mari bertumbuh bersama dalam persekutuan jemaat Imanuel Bahu</p>
        
        <div class="row g-4 justify-content-center">
            <div class="col-md-4" data-aos="fade-up" data-aos-delay="150">
                <div class="glass-card p-4 h-100 text-center border-0">
                    <div class="d-inline-block p-3 rounded-circle mb-3" style="background-color: #f3effb; color: #6f42c1;">
                        <i class="bi bi-sunrise-fill" style="font-size: 2rem;"></i>
                    </div>
                    <h5 class="fw-bold text-dark">Ibadah Subuh</h5>
                    <h3 class="fw-bolder mb-2" style="color: #6f42c1;">05:30 WITA</h3>
                    <p class="small text-muted mb-0">Gedung Gereja Utama</p>
                </div>
            </div>
            <div class="col-md-4" data-aos="fade-up" data-aos-delay="250">
                <div class="glass-card p-4 h-100 text-center border-0" style="background: rgba(111, 66, 193, 0.05); border: 1px solid rgba(111, 66, 193, 0.2);">
                    <div class="d-inline-block bg-primary text-white p-3 rounded-circle mb-3 shadow-sm">
                        <i class="bi bi-sun-fill" style="font-size: 2rem;"></i>
                    </div>
                    <h5 class="fw-bold text-dark">Ibadah Pagi</h5>
                    <h3 class="fw-bolder mb-2" style="color: #6f42c1;">09:00 WITA</h3>
                    <p class="small text-muted mb-0">Gedung Gereja Utama</p>
                </div>
            </div>
            <div class="col-md-4" data-aos="fade-up" data-aos-delay="350">
                <div class="glass-card p-4 h-100 text-center border-0">
                    <div class="d-inline-block bg-dark bg-opacity-10 p-3 rounded-circle text-dark mb-3">
                        <i class="bi bi-moon-stars-fill" style="font-size: 2rem;"></i>
                    </div>
                    <h5 class="fw-bold text-dark">Ibadah Malam</h5>
                    <h3 class="fw-bolder mb-2" style="color: #6f42c1;">18:00 WITA</h3>
                    <p class="small text-muted mb-0">Gedung Gereja Utama</p>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="info-section py-5">
    <div class="container text-center py-4">
        <h2 class="fw-bold mb-5 text-dark" data-aos="fade-up">Layanan Informasi Digital</h2>
        <div class="row g-4 justify-content-center text-start">
            <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="100">
                <div class="glass-card p-4 h-100 border-0 d-flex flex-column">
                    <div class="text-primary mb-3"><i class="bi bi-calendar3-event" style="font-size: 2.3rem;"></i></div>
                    <h5 class="fw-bold text-dark mb-4">Agenda Terdekat</h5>
                    <?php
                    $query_event = @mysqli_query($koneksi, "SELECT * FROM events WHERE tanggal >= CURDATE() ORDER BY tanggal ASC LIMIT 1");
                    $event_terdekat = $query_event ? mysqli_fetch_assoc($query_event) : null;
                    if($event_terdekat): 
                        $timestamp = strtotime($event_terdekat['tanggal']);
                        $bulan_singkat = date('M', $timestamp);
                        $tanggal_angka = date('d', $timestamp);
                    ?>
                        <div class="d-flex align-items-center bg-white bg-opacity-75 p-2 rounded-4 mb-4 border border-primary border-opacity-25 shadow-sm">
                            <div class="event-calendar-badge me-3 border border-primary border-opacity-10">
                                <div class="event-calendar-month" style="background-color: #6f42c1; color: white;"><?php echo $bulan_singkat; ?></div>
                                <div class="event-calendar-date" style="color: #6f42c1;"><?php echo $tanggal_angka; ?></div>
                            </div>
                            <div class="overflow-hidden">
                                <div class="fw-bold text-dark mb-1 text-truncate"><?php echo htmlspecialchars($event_terdekat['judul']); ?></div>
                                <div class="small text-muted text-truncate"><i class="bi bi-geo-alt-fill text-danger me-1"></i> <?php echo htmlspecialchars($event_terdekat['lokasi']); ?></div>
                            </div>
                        </div>
                    <?php else: ?>
                        <div class="bg-light bg-opacity-50 p-3 rounded-4 mb-4 border border-secondary border-opacity-25 text-center">
                            <p class="text-muted small mb-0"><i class="bi bi-info-circle me-1"></i> Belum ada agenda.</p>
                        </div>
                    <?php endif; ?>
                    <a href="event.php" class="btn btn-sm btn-outline-primary rounded-pill px-3 fw-bold mt-auto">Lihat Agenda</a>
                </div>
            </div>
            <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="200">
                <div class="glass-card p-4 h-100 border-0 d-flex flex-column">
                    <div class="text-info mb-3" style="color: #6f42c1 !important;"><i class="bi bi-file-earmark-text" style="font-size: 2.3rem;"></i></div>
                    <h5 class="fw-bold text-dark mb-3">Warta Minggu Berjalan</h5>
                    <p class="text-muted small mb-3">Akses warta jemaat resmi.</p>
                    <a href="warta-jemaat.php" class="btn btn-sm btn-outline-primary rounded-pill px-3 fw-bold mt-auto">Buka Warta</a>
                </div>
            </div>
            <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="300">
                <div class="glass-card p-4 h-100 border-0 d-flex flex-column">
                    <div class="text-success mb-3" style="color: #6f42c1 !important;"><i class="bi bi-wallet2" style="font-size: 2.3rem;"></i></div>
                    <h5 class="fw-bold text-dark mb-3">Transparansi Keuangan</h5>
                    <p class="text-muted small mb-3">Laporan kas dan persembahan jemaat.</p>
                    <a href="warta-keuangan.php" class="btn btn-sm btn-outline-primary rounded-pill px-3 fw-bold mt-auto">Lihat Laporan</a>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="reflection-section py-5 mb-5">
    <div class="container text-center">
        <div class="glass-card p-5 mx-auto" style="max-width: 800px;" data-aos="fade-up" data-aos-duration="1000">
            <?php 
            // Hubungkan dengan file bank ayat
            include 'data_ayat.php'; 
            
            // Menggunakan array_rand agar ayat berganti setiap kali halaman di-refresh
            $index_terpilih = array_rand($bank_ayat);
            $ayat_hari_ini = $bank_ayat[$index_terpilih];
            ?>
            <div class="text-primary mb-3 opacity-50">
                <i class="bi bi-quote" style="font-size: 2.5rem;"></i>
            </div>
            <p class="verse-text px-md-4 mb-4" style="font-size: 1.2rem; line-height: 1.6;">
                "<?php echo htmlspecialchars($ayat_hari_ini['teks']); ?>"
            </p>
            <span class="text-primary fw-bold small text-uppercase tracking-wider" style="letter-spacing: 1.5px;">
                — <?php echo htmlspecialchars($ayat_hari_ini['kitab']); ?>
            </span>
        </div>
    </div>
</section>

<?php include 'footer.php'; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script>
    AOS.init({ once: true, offset: 80 });

    function openModal(src) {
        document.getElementById('modalImg').src = src;
        new bootstrap.Modal(document.getElementById('fotoModal')).show();
    }
</script>
</body>
</html>