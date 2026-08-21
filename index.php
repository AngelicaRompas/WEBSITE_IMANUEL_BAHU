<?php ob_start(); ?>

<?php
include 'koneksi.php';  
// Ambil 3 event terlaksana terbaru khusus untuk berita kegiatan pelayanan
$queryNews = mysqli_query($koneksi, "SELECT * FROM events WHERE DATE(tanggal) < CURDATE() AND tanggal != '0000-00-00' ORDER BY tanggal DESC LIMIT 3");

// Ambil data artikel warta untuk slider Informasi Jemaat (bisa menampung banyak artikel)
$query_artikel_publik = mysqli_query($koneksi, "SELECT * FROM artikel_warta ORDER BY tanggal DESC LIMIT 6");

// Ambil data warta terbaru untuk ditampilkan pada bagian Jadwal Ibadah Minggu (sisi kanan)
$query_warta_terbaru = mysqli_query($koneksi, "SELECT tanggal, cover_warta FROM warta_jemaat ORDER BY tanggal DESC LIMIT 1");
$warta_terbaru = ($query_warta_terbaru && mysqli_num_rows($query_warta_terbaru) > 0) ? mysqli_fetch_assoc($query_warta_terbaru) : null;
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

<?php  
include 'navbar.php';  
?>

<!-- 1. HERO SECTION (TULISAN SELAMAT DATANG & NAVIGASI 360 DI PALING ATAS) -->
<section class="hero-section py-5">
    <div class="container text-center position-relative">
        <div data-aos="fade-down" data-aos-duration="1000">
            <h1 class="hero-title fw-bolder mb-5 mt-3">
                Selamat Datang di Portal Pelayanan <br>
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

<!-- 2. SECTION UTAMA: INFORMASI JEMAAT (DENGAN PANAH SLIDER) & JADWAL IBADAH -->
<section class="py-5">
    <div class="container">
        <div class="row g-4 align-items-stretch">
            
            <!-- KIRI: INFORMASI JEMAAT (SLIDER HORIZONTAL DENGAN TOMBOL PANAH) -->
            <div class="col-lg-8 position-relative d-flex flex-column" data-aos="fade-right">
                <div class="d-flex align-items-center justify-content-between mb-4">
                    <h3 class="fw-bold text-white m-0"><i class="bi bi-newspaper me-2" style="color: #a78bfa !important;"></i> Informasi Jemaat</h3>
                    
                    <!-- Tombol Panah Navigasi Kiri / Kanan -->
                    <div class="d-flex gap-2">
                        <button type="button" class="btn btn-light rounded-circle shadow-sm d-flex align-items-center justify-content-center" onclick="scrollInformasi('left')" style="width: 40px; height: 40px; background: rgba(255,255,255,0.9); border: none;">
                            <i class="bi bi-chevron-left text-dark fw-bold"></i>
                        </button>
                        <button type="button" class="btn btn-light rounded-circle shadow-sm d-flex align-items-center justify-content-center" onclick="scrollInformasi('right')" style="width: 40px; height: 40px; background: rgba(255,255,255,0.9); border: none;">
                            <i class="bi bi-chevron-right text-dark fw-bold"></i>
                        </button>
                    </div>
                </div>

                <!-- Wadah Scroll Horizontal (Slider) -->
                <div id="informasiSlider" class="d-flex gap-3 overflow-x-auto pb-3" style="scroll-behavior: smooth; scrollbar-width: none; -ms-overflow-style: none;">
                    <?php 
                    $array_artikel = [];
                    if(mysqli_num_rows($query_artikel_publik) > 0) {
                        while($art = mysqli_fetch_assoc($query_artikel_publik)) {
                            $array_artikel[] = $art;
                        }
                    }
                    
                    if(count($array_artikel) > 0):
                        foreach($array_artikel as $artikel): 
                    ?>
                    <div class="flex-shrink-0" style="width: 100%; max-width: 420px;">
                        <div class="glass-card p-3 p-md-4 border-0 shadow-sm rounded-4 d-flex flex-column h-100 text-decoration-none" style="cursor: pointer;" data-bs-toggle="modal" data-bs-target="#modalArtikel<?php echo $artikel['id']; ?>">
                            <?php if(!empty($artikel['gambar'])): ?>
                                <div class="w-100 mb-3 overflow-hidden rounded-3" style="height: 180px;">
                                    <img src="assets/gallery/<?php echo htmlspecialchars($artikel['gambar']); ?>" class="w-100 h-100 object-fit-cover shadow-sm">
                                </div>
                            <?php endif; ?>
                            <div class="flex-grow-1 text-start d-flex flex-column">
                                <div class="text-muted small mb-1">
                                    <i class="bi bi-calendar3 me-1 text-primary"></i> <?php echo date('d M Y', strtotime($artikel['tanggal'])); ?>
                                </div>
                                <h5 class="fw-bold text-dark mb-2"><?php echo htmlspecialchars($artikel['judul']); ?></h5>
                                <p class="text-muted small mb-0 line-clamp-2"><?php echo strip_tags($artikel['konten']); ?></p>
                            </div>
                        </div>
                    </div>
                    <?php 
                        endforeach; 
                    else: 
                    ?>
                        <div class="w-100">
                            <div class="glass-card p-4 text-center rounded-4 text-muted w-100">
                                <p class="mb-0">Belum ada informasi jemaat atau artikel terbaru.</p>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- KANAN: JADWAL IBADAH MINGGU -->
            <div class="col-lg-4 d-flex flex-column" data-aos="fade-left">
                <div class="d-flex align-items-center justify-content-between mb-4">
                    <h3 class="fw-bold text-white m-0"><i class="bi bi-calendar-week me-2" style="color: #a78bfa !important;"></i> Jadwal Ibadah</h3>
                </div>

                <div class="glass-card p-4 h-100 text-center border-0 d-flex flex-column align-items-center shadow-sm rounded-4">
                    <?php if($warta_terbaru && !empty($warta_terbaru['cover_warta'])): ?>
                        <div class="w-100 rounded-4 overflow-hidden mb-3 position-relative shadow-sm border">
                            <img src="assets/images_cover/<?php echo htmlspecialchars($warta_terbaru['cover_warta']); ?>" alt="Cover Warta Terbaru" class="w-100 d-block rounded-4 warta-cover-img" style="height: auto; object-fit: contain;">
                        </div>
                        <a href="warta-jemaat.php?detail=<?php echo $warta_terbaru['tanggal']; ?>" class="btn btn-purple rounded-pill w-100 fw-bold py-2 shadow-sm mt-auto" style="background-color: #6f42c1; color: white;">
                            <i class="bi bi-book-half me-2"></i> Buka Warta Minggu Ini
                        </a>
                    <?php else: ?>
                        <div class="p-5 text-center my-auto">
                            <p class="text-muted mb-0">Belum ada cover warta mingguan yang diunggah.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

        </div>
    </div>
</section>

<!-- ==========================================================================
     KUMPULAN MODAL DETAIL ARTIKEL (Diletakkan di luar looping)
     ========================================================================== -->
<?php if(count($array_artikel) > 0): ?>
    <?php foreach($array_artikel as $artikel): ?>
    <div class="modal fade" id="modalArtikel<?php echo $artikel['id']; ?>" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
                <div class="p-4 text-white d-flex justify-content-between align-items-center" style="background: linear-gradient(135deg, #6f42c1 0%, #4a2b85 100%);">
                    <h4 class="fw-bold mb-0"><i class="bi bi-newspaper me-2"></i>Detail Informasi Jemaat</h4>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4 bg-light text-start">
                    <?php if(!empty($artikel['gambar'])): ?>
                        <div class="mb-4 text-center bg-dark p-2 rounded-4 shadow-sm border overflow-hidden d-flex align-items-center justify-content-center" style="min-height: 250px; max-height: 450px;">
                            <img src="assets/gallery/<?php echo htmlspecialchars($artikel['gambar']); ?>" class="img-fluid rounded-3 w-100 h-100" style="object-fit: contain; max-height: 430px;">
                        </div>
                    <?php endif; ?>
                    <h4 class="fw-bold text-dark mb-2"><?php echo htmlspecialchars($artikel['judul']); ?></h4>
                    <div class="d-flex align-items-center text-primary mb-3">
                        <i class="bi bi-calendar3 me-2"></i> 
                        <span class="small fw-bold"><?php echo date('d F Y', strtotime($artikel['tanggal'])); ?></span>
                    </div>
                    <div class="bg-white p-4 rounded-4 shadow-sm border border-light">
                        <p class="text-dark mb-0" style="line-height: 1.8; white-space: pre-line;"><?php echo htmlspecialchars($artikel['konten']); ?></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
<?php endif; ?>

<!-- 3. STRUKTUR PELAYANAN -->
<section class="py-5">
    <div class="container-fluid px-2 px-md-5">
        <h2 class="fw-bold mb-5 text-dark text-center" data-aos="fade-up">Struktur Pelayanan</h2>
        <div class="row g-3 justify-content-center">  
            <?php
            $profil_items = [
                ["foto" => "pdt.png", "title" => "Pendeta", "desc" => "Pendeta Pelayanan GMIM Imanuel Bahu", "link" => "data-jemaat.php#pills-bpmj"],
                ["foto" => "pelsus.png", "title" => "Pelayan Khusus", "desc" => "Penatua & Diaken Kolom", "link" => "data-jemaat.php#pills-pelsus"],
                ["foto" => "bpmj.png", "title" => "Badan Pekerja Majelis Jemaat", "desc" => "Badan Pekerja Majelis Jemaat Periode Pelayanan 2022 - 2026", "link" => "data-jemaat.php#pills-bpmj"]
            ];
            foreach($profil_items as $item):
                $path_foto = "assets/images/" . $item['foto'];
            ?>
            <div class="col-lg-4 col-md-6" data-aos="fade-up">
                <div class="glass-card h-100 border-0 d-flex flex-column shadow-sm" style="border-radius: 20px; overflow: hidden;">
                    
                    <div class="img-container-wrapper" 
                         onclick="openModal('<?php echo $path_foto; ?>')"
                         style="width: 100%; aspect-ratio: 16 / 9; overflow: hidden; background: #f8f9fa; cursor: pointer; display: flex; align-items: center; justify-content: center;">
                        
                        <?php if(file_exists($path_foto)): ?>
                            <img src="<?php echo $path_foto; ?>" 
                                 alt="<?php echo $item['title']; ?>" 
                                 class="zoom-img img-responsive-custom"
                                 style="width: 100%; height: 100%; transition: transform 0.5s ease; object-fit: cover;">
                        <?php else: ?>
                            <div class="d-flex align-items-center justify-content-center h-100 text-muted small">Foto tidak ditemukan</div>
                        <?php endif; ?>
                    </div>
                    
                    <div class="card-body p-3 p-md-4 text-center d-flex flex-column flex-grow-1">
                        <h5 class="fw-bold text-dark mb-1"><?php echo $item['title']; ?></h5>
                        <p class="text-muted small mb-4 flex-grow-1"><?php echo $item['desc']; ?></p>
                        <a href="<?php echo $item['link']; ?>" 
                           class="btn btn-outline-primary rounded-pill px-4 py-2 fw-bold w-100">
                            Lihat Profil
                        </a>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- 4. TAHAPAN PEMILIHAN PELSUS -->
<section class="py-5">
    <div class="container">
        <div class="text-center mb-5" data-aos="fade-up">
            <h2 class="fw-bold text-white mb-2" style="letter-spacing: 1px;">TAHAPAN PEMILIHAN PELAYAN KHUSUS</h2>
            <div class="mx-auto" style="width: 60px; height: 2px; background: rgba(255,255,255,0.5);"></div>
        </div>

        <div class="row g-4 justify-content-center">
            <?php
            $foto_tambahan = ["tahapan1.jpeg", "tahapan2.jpeg", "tahapan3.jpeg", "tahapan4.jpeg"];
            foreach($foto_tambahan as $foto):
            ?>
            <div class="col-lg-3 col-md-6 col-12" data-aos="zoom-in" data-aos-delay="150">
                <div class="digital-card-wrapper" 
                     style="background: rgba(255, 255, 255, 0.05); backdrop-filter: blur(10px); border: 1px solid rgba(255, 255, 255, 0.2); border-radius: 25px; padding: 8px; transition: all 0.4s ease; cursor: pointer;"
                     onclick="openModal('assets/images/<?php echo $foto; ?>')">
                    <div class="overflow-hidden" style="border-radius: 18px;">
                        <img src="assets/images/<?php echo $foto; ?>" class="w-100" style="aspect-ratio: 1/1; object-fit: cover; transition: transform 0.6s ease;">
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

<!-- 5. BERITA KEGIATAN PELAYANAN -->
<section class="py-5" id="berita-kegiatan">
    <div class="container">
        <h2 class="fw-bold mb-5 text-center text-dark" data-aos="fade-up">Berita Kegiatan Pelayanan</h2>
        
        <div class="row g-4 justify-content-center">
            <?php  
            if(mysqli_num_rows($queryNews) > 0):
                while($row = mysqli_fetch_assoc($queryNews)):  
            ?>
            <div class="col-lg-4 col-md-6" data-aos="fade-up">
                <div class="glass-card h-100 shadow-sm border-0 d-flex flex-column" style="border-radius: 20px; overflow: hidden;">
                    <div style="width: 100%; aspect-ratio: 16 / 9; overflow: hidden; background: #000;">
                        <img src="assets/gallery/<?php echo htmlspecialchars($row['poster']); ?>" class="w-100 h-100 object-fit-cover zoom-img" alt="<?php echo htmlspecialchars($row['judul']); ?>">
                    </div>
                    <div class="card-body p-3 p-md-4 d-flex flex-column">
                        <span class="badge bg-primary mb-2 align-self-start shadow-sm"><?php echo date('d M Y', strtotime($row['tanggal'])); ?></span>
                        <h6 class="fw-bold text-dark mb-3 line-clamp-2"><?php echo htmlspecialchars($row['judul']); ?></h6>
                        <button class="btn btn-outline-primary w-100 rounded-pill py-2 mt-auto fw-bold" data-bs-toggle="modal" data-bs-target="#modalNews<?php echo $row['id']; ?>">
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

<!-- 6. LAYANAN INFORMASI DIGITAL -->
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
                    <a href="laporan_keuangan.php" class="btn btn-sm btn-outline-primary rounded-pill px-3 fw-bold mt-auto">Lihat Laporan</a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- 7. AYAT HARIAN -->
<section class="reflection-section py-5 mb-5">
    <div class="container text-center">
        <div class="glass-card p-5 mx-auto" style="max-width: 800px;" data-aos="fade-up" data-aos-duration="1000">
            <?php 
            include 'data_ayat.php'; 
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

    // Fungsi untuk menggeser slider informasi jemaat ke kiri atau kanan
    function scrollInformasi(direction) {
        const slider = document.getElementById('informasiSlider');
        const scrollAmount = 400; 
        if (direction === 'left') {
            slider.scrollBy({ left: -scrollAmount, behavior: 'smooth' });
        } else {
            slider.scrollBy({ left: scrollAmount, behavior: 'smooth' });
        }
    }
</script>
</body>
</html>