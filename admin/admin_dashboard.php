<?php
session_start();

if (!isset($_SESSION['admin_imanuel'])) {
    header("Location: ../login.php");
    exit;
}

include '../koneksi.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$today = date('Y-m-d');

/* =========================
   DATA STATISTIK
========================= */
$queryStat = mysqli_query(
    $koneksi,
    "SELECT label,jumlah,persentase FROM statistik"
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
    mysqli_query(
        $koneksi,
        "SELECT COUNT(*) as total FROM events"
    )
);

$totalRenungan = mysqli_fetch_assoc(
    mysqli_query(
        $koneksi,
        "SELECT COUNT(*) as total FROM renungan_harian"
    )
);

$totalStruktur = mysqli_fetch_assoc(
    mysqli_query(
        $koneksi,
        "SELECT COUNT(*) as total FROM struktur_organisasi"
    )
);

$totalNavigasi = mysqli_fetch_assoc(
    mysqli_query(
        $koneksi,
        "SELECT COUNT(*) as total FROM navigasi"
    )
);

/* =========================
   DATA TAMBAHAN
========================= */
$dataSejarah = mysqli_fetch_assoc(
    mysqli_query(
        $koneksi,
        "SELECT konten FROM profil WHERE jenis='sejarah'"
    )
);

$dataSaldo = mysqli_fetch_assoc(
    mysqli_query(
        $koneksi,
        "SELECT saldo_akhir FROM warta_keuangan ORDER BY id DESC LIMIT 1"
    )
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
    <title>Dashboard Admin - GMIM Imanuel Bahu</title>
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
        <i class="bi bi-shield-lock-fill me-2"></i>
        Dashboard Admin
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

        <div class="col-12 col-lg-10 p-4 p-md-5 admin-main-box">

            <?php include 'partials/alert.php'; ?>

            <div class="tab-content" id="v-pills-tabContent">

                <!-- =========================
                     SECTION ADMIN (SINKRON DENGAN SIDEBAR)
                ========================= -->
                <div class="tab-pane fade show active" id="admin-beranda" role="tabpanel">
                    <?php include 'sections/admin_beranda.php'; ?>
                </div>

                <div class="tab-pane fade" id="admin-datajemaat" role="tabpanel">
                    <?php include 'sections/admin_DataJemaat.php'; ?>
                </div>

                <div class="tab-pane fade" id="admin-profil" role="tabpanel">
                    <?php include 'sections/admin_Profil.php'; ?>
                </div>

                <div class="tab-pane fade" id="admin-struktur" role="tabpanel">
                    <?php include 'sections/admin_struktur.php'; ?>
                </div>

                <div class="tab-pane fade" id="admin-warta" role="tabpanel">
                    <?php include 'sections/admin_WartaJemaat.php'; ?>
                </div>

                <div class="tab-pane fade" id="admin-event" role="tabpanel">
                    <?php include 'sections/admin_Event.php'; ?>
                </div>

                <div class="tab-pane fade" id="admin-keuangan" role="tabpanel">
                    <?php include 'sections/admin_Keuangan.php'; ?>
                </div>

                <div class="tab-pane fade" id="admin-renungan" role="tabpanel">
                    <?php include 'sections/admin_Renungan.php'; ?>
                </div>

            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
/* =========================
   ACTIVE TAB ROUTER
========================= */
window.addEventListener('DOMContentLoaded', () => {
    const urlParams = new URLSearchParams(window.location.search);
    const tabId = urlParams.get('tab') || 'admin-beranda';
    const subtab = urlParams.get('subtab');

    const targetPane = document.getElementById(tabId);
    const targetBtn = document.querySelector(`[data-bs-target="#${tabId}"], [href="#${tabId}"]`);

    if (targetPane) {
        document.querySelectorAll('#v-pills-tabContent > .tab-pane')
            .forEach(el => el.classList.remove('show', 'active'));

        document.querySelectorAll('.nav-link-admin')
            .forEach(el => el.classList.remove('active'));

        targetPane.classList.add('show', 'active');

        if (targetBtn) {
            targetBtn.classList.add('active');

            // Buka collapse parent jika menu berada di dalam submenu
            const parentCollapse = targetBtn.closest('.collapse');
            if (parentCollapse) {
                bootstrap.Collapse.getOrCreateInstance(parentCollapse, { toggle: false }).show();
                const toggleBtn = document.querySelector(`[data-bs-target="#${parentCollapse.id}"]`);
                if (toggleBtn) {
                    toggleBtn.classList.add('active');
                }
            }
        }
    }

    // Khusus subtab keuangan
    if (tabId === "admin-keuangan") {
        let targetSubtab = subtab || "minggu";
        const subtabButton = document.querySelector(`[data-bs-target="#sub-keuangan-${targetSubtab}"]`);
        if (subtabButton) {
            bootstrap.Tab.getOrCreateInstance(subtabButton).show();
        }
    }
});

/* =========================
   SIMPAN TAB AKTIF KTIKA DIKLIK
========================= */
document.querySelectorAll('.nav-link-admin').forEach(button => {
    button.addEventListener('click', () => {
        const target = button.getAttribute('data-bs-target') || button.getAttribute('href');
        if (!target) return;

        const tabId = target.replace('#', '');
        const url = new URL(window.location);

        url.searchParams.set('tab', tabId);
        url.searchParams.delete('pesan');

        // Bersihkan parameter khusus keuangan jika pindah ke menu lain
        if (tabId !== 'admin-keuangan') {
            url.searchParams.delete('tgl_keuangan');
            url.searchParams.delete('subtab');
        }

        window.history.replaceState({}, '', url);

        // Tutup otomatis sidebar mobile setelah klik menu
        const sidebarElement = document.getElementById('mobileSidebar');
        if (sidebarElement) {
            bootstrap.Offcanvas.getOrCreateInstance(sidebarElement).hide();
        }
    });
});

/* =========================
   SIMPAN SUBTAB KEUANGAN
========================= */
document.addEventListener("shown.bs.tab", function(e){
    const target = e.target.getAttribute("data-bs-target");
    if(!target) return;

    if(target === "#sub-keuangan-minggu" || target === "#sub-keuangan-kolom"){
        const url = new URL(window.location);
        url.searchParams.set("subtab", target === "#sub-keuangan-minggu" ? "minggu" : "kolom");
        window.history.replaceState({}, "", url);
    }
});
</script>
</body>
</html>