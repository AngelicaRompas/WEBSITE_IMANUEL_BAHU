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

<!-- Desain Navigasi Tab Digital & Aesthetic (FIXED: Sejajar Satu Baris & Responsive Mobile) -->
<ul class="nav nav-pills mb-4 p-2 bg-glass-digital rounded-4 gap-2 border border-white-50 shadow-sm overflow-x-auto flex-nowrap"
    id="pills-tab-keuangan"
    role="tablist"
    style="background: rgba(255,255,255,.45); backdrop-filter: blur(10px); scrollbar-width: none; -ms-overflow-style: none;">

    <li class="nav-item" role="presentation">
        <button
            class="nav-link tab-digital fw-bold px-4 py-2-5 rounded-3 d-flex align-items-center gap-2 text-nowrap <?= $subtab == 'minggu' ? 'active' : '' ?>"
            id="tab-minggu-btn"
            data-bs-toggle="pill"
            data-bs-target="#sub-keuangan-minggu"
            type="button"
            role="tab">
            <i class="bi bi-calendar3-event fs-5"></i>
            Ibadah Minggu
        </button>
    </li>

    <li class="nav-item" role="presentation">
        <button
            class="nav-link tab-digital fw-bold px-4 py-2-5 rounded-3 d-flex align-items-center gap-2 text-nowrap <?= $subtab == 'kolom' ? 'active' : '' ?>"
            id="tab-kolom-btn"
            data-bs-toggle="pill"
            data-bs-target="#sub-keuangan-kolom"
            type="button"
            role="tab"
            aria-controls="sub-keuangan-kolom"
            aria-selected="<?= $subtab == 'kolom' ? 'true' : 'false' ?>">
            <i class="bi bi-grid-3x3-gap-fill fs-5"></i>
            Penyetoran Kolom
        </button>
    </li>

    <li class="nav-item" role="presentation">
        <button
            class="nav-link tab-digital fw-bold px-4 py-2-5 rounded-3 d-flex align-items-center gap-2 text-nowrap <?= $subtab == 'bipra' ? 'active' : '' ?>"
            id="tab-bipra-btn"
            data-bs-toggle="pill"
            data-bs-target="#sub-keuangan-bipra"
            type="button"
            role="tab">
            <i class="bi bi-diagram-3-fill fs-5"></i>
            Penyetoran BIPRA
        </button>
    </li>

    <li class="nav-item" role="presentation">
        <button 
            class="nav-link tab-digital fw-bold px-4 py-2-5 rounded-3 d-flex align-items-center gap-2 text-nowrap <?= $subtab == 'sampul' ? 'active' : '' ?>" 
            id="tab-sampul-btn" 
            data-bs-toggle="pill" 
            data-bs-target="#sub-keuangan-sampul" 
            type="button" 
            role="tab">
            <i class="bi bi-envelope-paper-fill fs-5"></i> 
            Sampul - Sampul
        </button>
    </li>

    <li class="nav-item" role="presentation">
        <button 
            class="nav-link tab-digital fw-bold px-4 py-2-5 rounded-3 d-flex align-items-center gap-2 text-nowrap <?= $subtab == 'khusus' ? 'active' : '' ?>" 
            id="tab-khusus-btn" 
            data-bs-toggle="pill" 
            data-bs-target="#sub-keuangan-khusus" 
            type="button" 
            role="tab">
            <i class="bi bi-stars fs-5"></i> 
            Ibadah Khusus
        </button>
    </li>

    <li class="nav-item" role="presentation">
        <button 
            class="nav-link tab-digital fw-bold px-4 py-2-5 rounded-3 d-flex align-items-center gap-2 text-nowrap <?= $subtab == 'pengeluaran' ? 'active' : '' ?>" 
            id="tab-pengeluaran-btn" 
            data-bs-toggle="pill" 
            data-bs-target="#sub-keuangan-pengeluaran" 
            type="button" 
            role="tab">
            <i class="bi bi-cart-dash-fill fs-5"></i> 
            Pengeluaran
        </button>
    </li>
</ul>

<div class="tab-content" id="pills-tabContentKeuangan">

    <!-- KONTEN SUB-TAB 1: IBADAH MINGGU -->
    <div class="tab-pane fade <?= $subtab == 'minggu' ? 'show active' : '' ?>" id="sub-keuangan-minggu" role="tabpanel">
        <?php include 'admin_keuangan_minggu.php'; ?>
    </div>

    <!-- KONTEN SUB-TAB 2: PENYETORAN KOLOM -->
    <div class="tab-pane fade <?= $subtab == 'kolom' ? 'show active' : '' ?>" id="sub-keuangan-kolom" role="tabpanel">
        <?php include 'admin_keuangan_kolom.php'; ?>
    </div>

    <!-- KONTEN SUB-TAB 3: PENYETORAN BIPRA -->
    <div class="tab-pane fade <?= $subtab == 'bipra' ? 'show active' : '' ?>" id="sub-keuangan-bipra" role="tabpanel">
        <?php include 'admin_keuangan_bipra.php'; ?>
    </div>

    <!-- KONTEN SUB-TAB 4: SAMPUL - SAMPUL -->
    <div class="tab-pane fade <?= $subtab == 'sampul' ? 'show active' : '' ?>" id="sub-keuangan-sampul" role="tabpanel">
        <?php include 'admin_keuangan_sampul.php'; ?>
    </div>

    <!-- KONTEN SUB-TAB 5: IBADAH KHUSUS -->
    <div class="tab-pane fade <?= $subtab == 'khusus' ? 'show active' : '' ?>" id="sub-keuangan-khusus" role="tabpanel">
        <?php include 'admin_keuangan_khusus.php'; ?>
    </div>

    <!-- KONTEN SUB-TAB 6: PENGELUARAN -->
    <div class="tab-pane fade <?= $subtab == 'pengeluaran' ? 'show active' : '' ?>" id="sub-keuangan-pengeluaran" role="tabpanel">
        <?php include 'admin_keuangan_pengeluaran.php'; ?>
    </div>

</div>

<style>
/* Menyembunyikan scrollbar bawaan agar navigasi tampak bersih */
ul#pills-tab-keuangan::-webkit-scrollbar {
    display: none;
}

.tab-digital {
    color: #64748b !important;
    background: transparent !important;
    transition: .3s;
    border: 1px solid transparent;
}
.tab-digital:hover:not(.disabled) {
    background: rgba(147, 51, 234, .08) !important;
    color: #4b1a8a !important;
}
.tab-digital.active {
    background: linear-gradient(135deg, #4b1a8a, #2e0854) !important;
    color: #fff !important;
    box-shadow: 0 4px 12px rgba(75, 26, 138, .25);
}
.py-2-5 {
    padding-top: .65rem;
    padding-bottom: .65rem;
}
</style>