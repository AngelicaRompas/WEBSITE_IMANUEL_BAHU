<?php
session_start();

if (!isset($_SESSION['admin_imanuel'])) {
    header("Location: ../login.php");
    exit;
}

include '../koneksi.php';

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

<?php include 'partials/header.php'; ?>

<style>

/* =========================
   GLOBAL
========================= */

html{
    scroll-behavior:smooth;
}

body{
    background:#f6f3ff;
    overflow-x:hidden;
}

/* =========================
   TAB ANIMATION
========================= */

.tab-content{
    animation:fadeEffect .3s ease;
}

@keyframes fadeEffect{

    from{
        opacity:0;
        transform:translateY(8px);
    }

    to{
        opacity:1;
        transform:translateY(0);
    }
}

/* =========================
   MOBILE TOPBAR
========================= */

.admin-mobile-topbar{
    background:linear-gradient(90deg,#4c1d95,#7c3aed);
    position:sticky;
    top:0;
    z-index:1040;
    box-shadow:0 5px 20px rgba(0,0,0,.08);
}

/* =========================
   WELCOME CARD
========================= */

.welcome-card{
    background:linear-gradient(135deg,#6f42c1 0%,#4f46e5 100%);
    border-radius:30px;
    overflow:hidden;
    position:relative;
    color:#fff;
    box-shadow:0 20px 50px rgba(111,66,193,.25);
}

.welcome-card::before{
    content:'';
    position:absolute;
    width:320px;
    height:320px;
    background:rgba(255,255,255,.08);
    border-radius:50%;
    top:-140px;
    right:-120px;
}

.welcome-card::after{
    content:'';
    position:absolute;
    width:220px;
    height:220px;
    background:rgba(255,255,255,.05);
    border-radius:50%;
    bottom:-100px;
    left:-80px;
}

/* =========================
   DASHBOARD CARD
========================= */

.dashboard-card{
    border:none;
    border-radius:26px;
    overflow:hidden;
    background:rgba(255,255,255,.88);
    backdrop-filter:blur(18px);
    box-shadow:0 12px 35px rgba(0,0,0,.05);
    transition:.3s ease;
}

.dashboard-card:hover{
    transform:translateY(-6px);
    box-shadow:0 18px 40px rgba(111,66,193,.14);
}

.dashboard-icon{
    width:68px;
    height:68px;
    border-radius:20px;
    display:flex;
    align-items:center;
    justify-content:center;
    font-size:1.5rem;
}

/* =========================
   ICON COLORS
========================= */

.bg-purple-soft{
    background:rgba(111,66,193,.12);
    color:#6f42c1;
}

.bg-indigo-soft{
    background:rgba(79,70,229,.12);
    color:#4f46e5;
}

.bg-pink-soft{
    background:rgba(236,72,153,.12);
    color:#ec4899;
}

.bg-violet-soft{
    background:rgba(139,92,246,.12);
    color:#8b5cf6;
}

/* =========================
   RESPONSIVE
========================= */

@media (max-width:991px){

    .tab-content{
        padding-top:1rem;
    }
}

@media (max-width:576px){

    .welcome-card{
        padding:2rem !important;
        border-radius:24px;
    }

    .welcome-card h2{
        font-size:1.5rem;
    }

    .dashboard-card{
        border-radius:22px;
    }

    .dashboard-icon{
        width:58px;
        height:58px;
        font-size:1.2rem;
    }

    .dashboard-card h2{
        font-size:1.6rem;
    }
}

</style>

</head>

<body>

<!-- =========================
     MOBILE TOPBAR
========================= -->

<div class="d-lg-none admin-mobile-topbar px-3 py-3 d-flex align-items-center justify-content-between">

    <div class="fw-bold text-white">
        <i class="bi bi-shield-lock-fill me-2"></i>
        Admin Panel
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

    <div class="col-12 col-lg-10 p-4 p-md-5">

        <?php include 'partials/alert.php'; ?>

        <div class="tab-content" id="v-pills-tabContent">

            <!-- =========================
                 SECTION ADMIN
            ========================= -->

            <div class="tab-pane fade show active" id="beranda-admin" role="tabpanel">
                <?php include 'sections/admin_beranda.php'; ?>
            </div>

            <div class="tab-pane fade" id="edit-data-jemaat" role="tabpanel">
                <?php include 'sections/admin_DataJemaat.php'; ?>
            </div>

            <div class="tab-pane fade" id="edit-profil" role="tabpanel">
                <?php include 'sections/admin_Profil.php'; ?>
            </div>

            <div class="tab-pane fade" id="edit-warta" role="tabpanel">
                <?php include 'sections/admin_WartaJemaat.php'; ?>
            </div>

            <div class="tab-pane fade" id="edit-event" role="tabpanel">
                <?php include 'sections/admin_Event.php'; ?>
            </div>

            <div class="tab-pane fade" id="edit-keuangan" role="tabpanel">
                <?php include 'sections/admin_Keuangan.php'; ?>
            </div>

            <div class="tab-pane fade" id="edit-struktur" role="tabpanel">
                <?php include 'sections/admin_Struktur.php'; ?>
            </div>

            <div class="tab-pane fade" id="edit-renungan" role="tabpanel">
                <?php include 'sections/admin_Renungan.php'; ?>
            </div>

            <div class="tab-pane fade" id="edit-navigasi" role="tabpanel">
                <?php include 'sections/admin_Navigasi.php'; ?>
            </div>
        </div>
    </div>
</div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>

/* =========================
   ACTIVE TAB
========================= */

window.addEventListener('DOMContentLoaded', () => {

    const urlParams = new URLSearchParams(window.location.search);
    const tabId = urlParams.get('tab') || 'beranda-admin';
    const targetPane = document.getElementById(tabId);
    const targetBtn = document.querySelector(
        `[data-bs-target="#${tabId}"]`
    );

    if(targetPane && targetBtn){
        document.querySelectorAll('.tab-pane') .forEach(el => el.classList.remove('show','active'));
        document.querySelectorAll('.nav-link-admin') .forEach(el => el.classList.remove('active'));
        targetPane.classList.add('show','active');
        targetBtn.classList.add('active');
    }
});

/* =========================
   AUTO CLOSE MOBILE MENU
========================= */

document.querySelectorAll('.nav-link-admin').forEach(button => {

    button.addEventListener('click', () => {

        const target = button.getAttribute('data-bs-target');

        if(target){

            const tabId = target.replace('#','');
            const url = new URL(window.location);
            url.searchParams.set('tab', tabId);
            url.searchParams.delete('pesan');
            window.history.replaceState(
                {},
                '',
                url.toString()
            );
        }

        const sidebar =
        bootstrap.Offcanvas.getInstance(
            document.getElementById('mobileSidebar')
        );
        if(sidebar){
            sidebar.hide();
        }
    });

});

</script>
</body>
</html>