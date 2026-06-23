<?php
// get-renungan.php
include 'koneksi.php';

header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

$keyword = isset($_POST['keyword'])
    ? mysqli_real_escape_string($koneksi, $_POST['keyword'])
    : '';

// Mengunci pencarian berdasarkan keyword, diurutkan dari ID terkecil (ID 1) agar data utama yang keluar
$query = mysqli_query(
    $koneksi,
    "SELECT * FROM renungan_tematik
     WHERE keyword='$keyword'
     ORDER BY id ASC
     LIMIT 1"
);

if(mysqli_num_rows($query) > 0){
    $data = mysqli_fetch_assoc($query);
    // Div text-start dihilangkan agar w-100 dan word-break bekerja penuh pada pembungkus induk
    echo '
    <span class="badge bg-primary bg-opacity-10 text-primary mb-3 px-3 py-2">
        <i class="bi bi-bookmark-heart me-2"></i>'.htmlspecialchars($data['keyword']).'
    </span>

    <h5 class="fw-bold text-dark mb-2 w-100">
        '.htmlspecialchars($data['judul']).'
    </h5>

    <p class="text-primary fw-semibold mb-3 w-100">
        '.htmlspecialchars($data['ayat']).'
    </p>

    <div class="text-secondary mb-3 w-100" style="line-height:1.9; white-space: normal; word-break: break-word;">
        '.nl2br(htmlspecialchars($data['isi'])).'
    </div>

    <div class="small text-muted w-100 mt-2">
        <i class="bi bi-stars me-1"></i>
        Semoga Renungan Ini Menjadi Berkat, Tuhan Yesus Memberkati.
    </div>
    ';
}else{
    echo '
    <div class="text-center py-5 w-100">
        <i class="bi bi-journal-x fs-1 text-muted mb-3 d-block"></i>
        <h6 class="fw-bold">
            Renungan Belum Tersedia
        </h6>
        <p class="text-muted mb-0">
            Topik "'.htmlspecialchars($keyword).'" belum memiliki data renungan.
        </p>
    </div>
    ';
}
?>