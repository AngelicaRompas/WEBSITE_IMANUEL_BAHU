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

                <div class="tab-pane fade" id="admin-sejarah" role="tabpanel">
                    <?php include 'sections/admin_sejarah.php'; ?>
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
/* ==========================================================
   ACTIVE TAB ROUTER
========================================================== */
window.addEventListener("DOMContentLoaded", function () {

    const url = new URL(window.location.href);

    let tabId = url.searchParams.get("tab") || "admin-beranda";
    let subtab = url.searchParams.get("subtab") || "minggu";

    const targetPane = document.getElementById(tabId);

    if (!targetPane) {
        console.warn("Tab tidak ditemukan :", tabId);
        return;
    }

    // Nonaktifkan semua tab utama
    document.querySelectorAll("#v-pills-tabContent > .tab-pane")
        .forEach(pane => {
            pane.classList.remove("show", "active");
        });

    document.querySelectorAll(".nav-link-admin")
        .forEach(btn => btn.classList.remove("active"));

    // Aktifkan tab tujuan
    targetPane.classList.add("show", "active");

    // Cari tombol sidebar
    const targetBtn = document.querySelector(
        `[data-bs-target="#${tabId}"], a[href="#${tabId}"]`
    );

    if (targetBtn) {

        targetBtn.classList.add("active");

        // Jika berada di submenu
        const parentCollapse = targetBtn.closest(".collapse");

        if (parentCollapse) {

            bootstrap.Collapse
                .getOrCreateInstance(parentCollapse, {
                    toggle: false
                })
                .show();

            const parentButton = document.querySelector(
                `[data-bs-target="#${parentCollapse.id}"]`
            );

            if (parentButton) {
                parentButton.classList.add("active");
            }

        }

    }

    /* ======================================================
       SUBTAB KEUANGAN
    ====================================================== */

    if (tabId === "admin-keuangan") {

        const map = {
            minggu: "#sub-keuangan-minggu",
            kolom: "#sub-keuangan-kolom",
            bipra: "#sub-keuangan-bipra",
            sampul: "#sub-keuangan-sampul",
            khusus: "#sub-keuangan-khusus",
            pengeluaran: "#sub-keuangan-pengeluaran"
        };

        const selector = map[subtab];

        if (selector) {

            const subButton = document.querySelector(
                `[data-bs-target="${selector}"]`
            );

            if (subButton) {
                bootstrap.Tab.getOrCreateInstance(subButton).show();
            }

        }

    }

});


/* ==========================================================
   SIMPAN TAB AKTIF
========================================================== */

document.querySelectorAll(".nav-link-admin").forEach(button => {

    button.addEventListener("click", function () {

        const target =
            this.getAttribute("data-bs-target") ||
            this.getAttribute("href");

        if (!target) return;

        const tabId = target.replace("#", "");

        const url = new URL(window.location.href);

        url.searchParams.set("tab", tabId);

        url.searchParams.delete("pesan");

        if (tabId !== "admin-keuangan") {

            url.searchParams.delete("subtab");
            url.searchParams.delete("tgl_keuangan");

        }

        history.replaceState({}, "", url);

        const sidebar = document.getElementById("mobileSidebar");

        if (sidebar) {

            bootstrap.Offcanvas
                .getOrCreateInstance(sidebar)
                .hide();

        }

    });

});


/* ==========================================================
   SIMPAN SUBTAB KEUANGAN
========================================================== */

document.addEventListener("shown.bs.tab", function (e) {

    const target = e.target.getAttribute("data-bs-target");

    if (!target) return;

    const map = {
        "#sub-keuangan-minggu": "minggu",
        "#sub-keuangan-kolom": "kolom",
        "#sub-keuangan-bipra": "bipra",
        "#sub-keuangan-sampul": "sampul",
        "#sub-keuangan-khusus": "khusus",
        "#sub-keuangan-pengeluaran": "pengeluaran"
    };

    if (map[target]) {

        const url = new URL(window.location.href);

        url.searchParams.set("tab", "admin-keuangan");
        url.searchParams.set("subtab", map[target]);

        history.replaceState({}, "", url);

    }

});
</script>
</body>
</html>