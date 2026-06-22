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

<div class="digital-grid"></div>

<div class="aurora-container">
    <div class="aurora-blob blob-blue"></div>
    <div class="aurora-blob blob-soft"></div>
</div>

<?php include 'navbar.php'; ?>
<?php include 'koneksi.php'; ?>

<section class="page-header-premium text-center z-2">

<div class="container">

<div data-aos="fade-down">

<span class="badge rounded-pill px-3 py-2 small mb-3 fw-bold"
style="letter-spacing:2px;background-color:rgba(111,66,193,.1);color:#6f42c1;">

<i class="bi bi-book-half me-2"></i>RENUNGAN JEMAAT

</span>

</div>

<h1 class="main-title-aesthetic mb-3"
data-aos="fade-down"
data-aos-delay="100">

Renungan Jemaat

</h1>

<p class="text-muted fw-medium mb-0" data-aos="fade-up" data-aos-delay="200">
Renungan Harian Keluarga dan Renungan Tematik GMIM Imanuel Bahu.
</p>

</div>

</section>

<div class="container pb-5 mb-5 position-relative z-2">

<div class="row g-5">

<div class="col-lg-7">

<h4 class="fw-bold mb-4" data-aos="fade-right">
<i class="bi bi-archive-fill text-primary me-2"></i>Arsip RHK
</h4>

<div class="row g-4">

<?php
$query = mysqli_query($koneksi,"SELECT * FROM renungan_harian ORDER BY tanggal DESC");
$modals = [];

while($data = mysqli_fetch_assoc($query)):
$modals[] = $data;
?>

<div class="col-md-6" data-aos="fade-up">

<div class="rhk-card-glass h-100"
data-bs-toggle="modal"
data-bs-target="#mdl<?php echo $data['id']; ?>">

<div class="rhk-img-container">

<h2>RHK</h2>

<div class="rhk-badge">
BACA SEKARANG
</div>

</div>

<div class="p-4 text-center">

<h6 class="fw-bold mb-3">
<?php echo date('d M Y', strtotime($data['tanggal'])); ?>
</h6>

<span class="badge bg-primary bg-opacity-10 text-primary">
<?php echo htmlspecialchars($data['nas_alkitab']); ?>
</span>

</div>

</div>

</div>

<?php endwhile; ?>

</div>

</div>

<div class="col-lg-5">

<div class="search-glass-container renungan-sticky" data-aos="fade-left">

<div class="text-center mb-4">

<span class="badge rounded-pill bg-primary bg-opacity-10 text-primary px-3 py-2 mb-3">
<i class="bi bi-stars me-2"></i>RENUNGAN TEMATIK
</span>

<h4 class="fw-bold mb-2">
Temukan Penguatan Firman
</h4>

<p class="text-muted small mb-0">
Pilih topik pergumulan atau kebutuhan rohani Anda
</p>

</div>

<div class="d-flex flex-wrap gap-2 justify-content-center mb-4">

<span class="badge rounded-pill prompt-badge" onclick="cariRenungan('Khawatir')">Khawatir</span>
<span class="badge rounded-pill prompt-badge" onclick="cariRenungan('Kesepian')">Kesepian</span>
<span class="badge rounded-pill prompt-badge" onclick="cariRenungan('Keluarga')">Keluarga</span>
<span class="badge rounded-pill prompt-badge" onclick="cariRenungan('Kasih')">Kasih</span>
<span class="badge rounded-pill prompt-badge" onclick="cariRenungan('Pengampunan')">Pengampunan</span>
<span class="badge rounded-pill prompt-badge" onclick="cariRenungan('Sakit')">Kesembuhan</span>
<span class="badge rounded-pill prompt-badge" onclick="cariRenungan('Masa Depan')">Masa Depan</span>
<span class="badge rounded-pill prompt-badge" onclick="cariRenungan('Pekerjaan')">Pekerjaan</span>
<span class="badge rounded-pill prompt-badge" onclick="cariRenungan('Putus Asa')">Putus Asa</span>
<span class="badge rounded-pill prompt-badge" onclick="cariRenungan('Anak Muda')">Anak Muda</span>
<span class="badge rounded-pill prompt-badge" onclick="cariRenungan('Stress')">Stress</span>
<span class="badge rounded-pill prompt-badge" onclick="cariRenungan('Bersyukur')">Bersyukur</span>

</div>

<div id="aiRes" class="p-4 shadow-sm text-secondary">

<div class="text-center py-5">

<i class="bi bi-heart-pulse fs-1 text-primary mb-3 d-block"></i>

<h6 class="fw-bold text-dark">
Renungan Tematik Interaktif
</h6>

<p class="text-muted mb-0">
Klik salah satu topik di atas untuk mendapatkan renungan secara acak dan dinamis.
</p>

</div>

</div>

</div>

</div>

</div>

</div>

<?php foreach($modals as $data): ?>

<div class="modal fade" id="mdl<?php echo $data['id']; ?>" tabindex="-1">

<div class="modal-dialog modal-lg modal-dialog-centered">

<div class="modal-content p-4">

<h3 class="fw-bold mb-3">
<?php echo $data['judul']; ?>
</h3>

<p class="text-primary fw-bold">
<?php echo $data['nas_alkitab']; ?>
</p>

<div class="text-secondary">
<?php echo nl2br(htmlspecialchars($data['isi_renungan'])); ?>
</div>

<hr>

<p class="fst-italic text-warning mb-0">
"<?php echo $data['doa']; ?>"
</p>

</div>

</div>

</div>

<?php endforeach; ?>

<script>

function cariRenungan(keyword){

const resBox = document.getElementById('aiRes');

resBox.innerHTML =
'<div class="text-center py-5"><div class="spinner-border text-primary"></div><p class="mt-3 mb-0">Mencari renungan...</p></div>';

fetch('get-renungan.php',{
method:'POST',
headers:{'Content-Type':'application/x-www-form-urlencoded'},
body:'keyword=' + encodeURIComponent(keyword)
})

.then(response => response.text())

.then(data => {
resBox.innerHTML = data;
});

}

</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>

<script>
AOS.init({
once:true,
offset:80
});
</script>

</body>
</html>