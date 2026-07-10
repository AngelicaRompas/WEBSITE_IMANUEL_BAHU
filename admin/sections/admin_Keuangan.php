<?php
// admin_Keuangan.php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

global $koneksi;

// Ambil parameter tanggal dari URL string
$tanggal_pilih = isset($_GET['tgl_keuangan'])
    ? mysqli_real_escape_string($koneksi, $_GET['tgl_keuangan'])
    : date('Y-m-d');

// Subtab aktif pembagi menu
$subtab = $_GET['subtab'] ?? 'minggu';
?>

<!-- Desain Navigasi Tab Digital & Aesthetic -->
<ul class="nav nav-pills mb-4 p-2 bg-glass-digital rounded-4 gap-2 border border-white-50 shadow-sm"
    id="pills-tab-keuangan"
    role="tablist"
    style="background: rgba(255,255,255,.45); backdrop-filter: blur(10px);">

    <li class="nav-item" role="presentation">
        <button
            class="nav-link tab-digital fw-bold px-4 py-2-5 rounded-3 d-flex align-items-center gap-2 <?= $subtab=='minggu' ? 'active' : '' ?>"
            id="tab-minggu-btn"
            data-bs-toggle="pill"
            data-bs-target="#sub-keuangan-minggu"
            type="button"
            role="tab">
            <i class="bi bi-calendar3-event fs-5 text-purple-premium"></i>
            Ibadah Minggu
        </button>
    </li>

    <li class="nav-item" role="presentation">
        <!-- UBAH TEKS MENU: Menjadi Penyetoran Kolom & Sinkronisasi Aksesibilitas ARIA -->
        <button
            class="nav-link tab-digital fw-bold px-4 py-2-5 rounded-3 d-flex align-items-center gap-2 <?= $subtab=='kolom' ? 'active' : '' ?>"
            id="tab-kolom-btn"
            data-bs-toggle="pill"
            data-bs-target="#sub-keuangan-kolom"
            type="button"
            role="tab"
            aria-controls="sub-keuangan-kolom"
            aria-selected="<?= $subtab=='kolom' ? 'true' : 'false' ?>">
            <i class="bi bi-grid-3x3-gap-fill fs-5"></i>
            Penyetoran Kolom
        </button>
    </li>

    <li class="nav-item" role="presentation">
        <button class="nav-link tab-digital fw-bold px-4 py-2-5 rounded-3 d-flex align-items-center gap-2 disabled" type="button" style="opacity:.5;">
            <i class="bi bi-folder-plus fs-5"></i>
            Form Lanjutan
        </button>
    </li>
</ul>

<div class="tab-content" id="pills-tabContentKeuangan">

    <!-- KONTEN SUB-TAB 1: IBADAH MINGGU -->
    <div class="tab-pane fade <?= $subtab=='minggu' ? 'show active' : '' ?>" id="sub-keuangan-minggu" role="tabpanel">
        <?php include 'admin_keuangan_minggu.php'; ?>
    </div>

    <!-- KONTEN SUB-TAB 2: PENYETORAN KOLOM (FIXED: Menyambungkan file tabel baru Anda) -->
    <div class="tab-pane fade <?= $subtab=='kolom' ? 'show active' : '' ?>" id="sub-keuangan-kolom" role="tabpanel">
        <?php include 'admin_keuangan_kolom.php'; ?>
    </div>

</div>

<style>
.tab-digital{
    color:#64748b!important;
    background:transparent!important;
    transition:.3s;
    border:1px solid transparent;
}
.tab-digital:hover:not(.disabled){
    background:rgba(147,51,234,.08)!important;
    color:#4b1a8a!important;
}
.tab-digital.active{
    background:linear-gradient(135deg,#4b1a8a,#2e0854)!important;
    color:#fff!important;
    box-shadow:0 4px 12px rgba(75,26,138,.25);
}
.py-2-5{
    padding-top:.65rem;
    padding-bottom:.65rem;
}
</style>