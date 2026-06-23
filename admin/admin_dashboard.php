<?php
session_start();

// FIX KEAMANAN 1: Proteksi Session Hijacking dengan regenerasi ID session secara aman
if (!isset($_SESSION['admin_imanuel'])) {
    header("Location: ../login.php");
    exit;
} else {
    // Memperbarui kunci ID session setiap kali halaman dimuat ulang
    session_regenerate_id(true);
}

include '../koneksi.php';

// Menetapkan waktu zona lokal (opsional untuk akurasi data)
date_default_timezone_set('Asia/Makassar'); 
$today = date('Y-m-d');

/* =========================
   DATA STATISTIK
========================= */
$queryStat = mysqli_query(
    $koneksi,
    "SELECT label, jumlah, persentase FROM statistik"
);

$stats = [];
if($queryStat){
    while($row = mysqli_fetch_assoc($queryStat)){
        $stats[$row['label']] = $row;
    }
}

/* =========================
   DATA BERANDA ADMIN
========================= */
$totalEvent = mysqli_fetch_assoc(
    mysqli_query($koneksi, "SELECT COUNT(*) as total FROM events")
);

$totalRenungan = mysqli_fetch_assoc(
    mysqli_query($koneksi, "SELECT COUNT(*) as total FROM renungan_harian")
);

$totalStruktur = mysqli_fetch_assoc(
    mysqli_query($koneksi, "SELECT COUNT(*) as total FROM struktur_organisasi")
);

$totalNavigasi = mysqli_fetch_assoc(
    mysqli_query($koneksi, "SELECT COUNT(*) as total FROM navigasi")
);

/* =========================
   DATA TAMBAHAN
========================= */
$dataSejarah = mysqli_fetch_assoc(
    mysqli_query($koneksi, "SELECT konten FROM profil WHERE jenis='sejarah'")
);

$dataSaldo = mysqli_fetch_assoc(
    mysqli_query($koneksi, "SELECT saldo_akhir FROM warta_keuangan ORDER BY id DESC LIMIT 1")
);

$saldoSebelumnya = $dataSaldo['saldo_akhir'] ?? 0;

$kolomBerikutnya = (
    mysqli_fetch_assoc(
        mysqli_query(
            $koneksi,
            "SELECT MAX(kolom) as max_kolom 
             FROM struktur_organisasi 
             WHERE kategori='pelsus'"
        )
    )['max_kolom'] ?? 28
) + 1;
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel Admin GMIM Imanuel Bahu</title>

    <?php include 'partials/header.php'; ?>

    <link rel="stylesheet" href="assets/css/style_admin.css">
    <link rel="stylesheet" href="assets/css/style_sidebar.css">
</head>

<body>

<!-- =========================
     MOBILE TOPBAR
========================= -->
<div class="d-lg-none admin-mobile-topbar px-3 py-3 d-flex align-items-center justify-content-between">
    <div class="fw-bold text-white">
        <i class="bi bi-shield-lock-fill me-2"></i>Dashboard Admin
    </div>
    <button class="btn btn-light rounded-pill px-3"
            type="button"
            data-bs-toggle="offcanvas"
            data-bs-target="#mobileSidebar">
        <i class="bi bi-list fs-5"></i>
    </button>
</div>

<div class="container-fluid">
    <div class="row">

        <?php include 'partials/sidebar.php'; ?>

        <!-- FIX VISUAL KEDUA: Menambahkan max-width inline untuk mencegah kebocoran grid pembungkus ke kanan -->
        <div class="col-12 col-lg-10 p-4 p-md-5 admin-main-box" style="margin-left: auto; max-width: calc(100% - 260px); width: calc(100% - 260px); overflow-x: hidden; box-sizing: border-box;">

            <?php include 'partials/alert.php'; ?>

            <div class="tab-content" id="v-pills-tabContent">

                <!-- ========================================================
                     SECTION VIEW INCLUDE - PASTIKAN FILE BERADA DI FOLDER SECTIONS
                     ======================================================== -->

                <div class="tab-pane fade show active" id="beranda-admin" role="tabpanel">
                    <?php include 'sections/admin_beranda.php'; ?>
                </div>

                <div class="tab-pane fade" id="edit-data-jemaat" role="tabpanel">
                    <?php include 'sections/admin_datajemaat.php'; ?>
                </div>

                <div class="tab-pane fade" id="edit-profil" role="tabpanel">
                    <?php include 'sections/admin_profil.php'; ?>
                </div>

                <div class="tab-pane fade" id="edit-warta" role="tabpanel">
                    <?php include 'sections/admin_wartajemaat.php'; ?>
                </div>

                <div class="tab-pane fade" id="edit-event" role="tabpanel">
                    <?php include 'sections/admin_event.php'; ?>
                </div>

                <div class="tab-pane fade" id="edit-keuangan" role="tabpanel">
                    <?php include 'sections/admin_keuangan.php'; ?>
                </div>

                <div class="tab-pane fade" id="edit-struktur" role="tabpanel">
                    <?php include 'sections/admin_struktur.php'; ?>
                </div>

                <div class="tab-pane fade" id="edit-renungan" role="tabpanel">
                    <?php include 'sections/admin_renungan.php'; ?>
                </div>

                <div class="tab-pane fade" id="edit-navigasi" role="tabpanel">
                    <?php include 'sections/admin_navigasi.php'; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
/* =========================
   ACTIVE TAB HANDLING
========================= */
window.addEventListener('DOMContentLoaded', () => {
    const urlParams = new URLSearchParams(window.location.search);
    const tabId = urlParams.get('tab') || 'beranda-admin';
    const targetPane = document.getElementById(tabId);
    const targetBtn = document.querySelector(`[data-bs-target="#${tabId}"]`);

    if(targetPane && targetBtn){
        document.querySelectorAll('.tab-pane').forEach(el => el.classList.remove('show','active'));
        document.querySelectorAll('.nav-link-admin').forEach(el => el.classList.remove('active'));
        targetPane.classList.add('show','active');
        targetBtn.classList.add('active');
    }
});

/* =========================
   AUTO CLOSE MOBILE MENU & HISTORY ROUTING
========================= */
document.querySelectorAll('.nav-link-admin').forEach(button => {
    button.addEventListener('click', () => {
        const target = button.getAttribute('data-bs-target');

        if(target){
            const tabId = target.replace('#','');
            const url = new URL(window.location);
            url.searchParams.set('tab', tabId);
            url.searchParams.delete('pesan');
            window.history.replaceState({}, '', url.toString());
        }

        const sidebarElement = document.getElementById('mobileSidebar');
        if(sidebarElement) {
            const sidebar = bootstrap.Offcanvas.getInstance(sidebarElement);
            if(sidebar){
                sidebar.hide();
            }
        }
    });
});
</script>
</body>
</html>