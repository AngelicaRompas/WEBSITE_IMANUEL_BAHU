<?php
// admin_Keuangan.php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

global $koneksi;

// Ambil tanggal
$tanggal_pilih = isset($_GET['tgl_keuangan'])
    ? mysqli_real_escape_string($koneksi, $_GET['tgl_keuangan'])
    : date('Y-m-d');

// Subtab aktif
$subtab = $_GET['subtab'] ?? 'minggu';
?>

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

        <button
            class="nav-link tab-digital fw-bold px-4 py-2-5 rounded-3 d-flex align-items-center gap-2 <?= $subtab=='kolom' ? 'active' : '' ?>"
            id="tab-kolom-btn"
            data-bs-toggle="pill"
            data-bs-target="#sub-keuangan-kolom"
            type="button"
            role="tab">

            <i class="bi bi-grid-3x3-gap-fill fs-5"></i>

            Ibadah Kolom

        </button>

    </li>

    <li class="nav-item" role="presentation">

        <button
            class="nav-link tab-digital fw-bold px-4 py-2-5 rounded-3 d-flex align-items-center gap-2 disabled"
            type="button"
            style="opacity:.5;">

            <i class="bi bi-folder-plus fs-5"></i>

            Form Lanjutan

        </button>

    </li>

</ul>

<div class="tab-content" id="pills-tabContentKeuangan">

    <div
        class="tab-pane fade <?= $subtab=='minggu' ? 'show active' : '' ?>"
        id="sub-keuangan-minggu"
        role="tabpanel">

        <?php include 'admin_keuangan_minggu.php'; ?>

    </div>

    <div
        class="tab-pane fade <?= $subtab=='kolom' ? 'show active' : '' ?>"
        id="sub-keuangan-kolom"
        role="tabpanel">

        <div class="card card-custom p-5 border-0 shadow-sm rounded-4 text-center bg-white">

            <div class="py-5"
                 style="background:linear-gradient(135deg,rgba(147,51,234,.05),rgba(59,130,246,.05)); border-radius:16px;">

                <i class="bi bi-cpu text-purple-premium fs-1 mb-3 d-block"></i>

                <h5 class="text-dark fw-bold mb-2">

                    Form Keuangan Ibadah Kolom

                </h5>

                <p class="text-muted small px-4 mb-0">

                    Arsitektur tabel dan fungsi otomatisasi form sedang menunggu instruksi pemetaan kolom selanjutnya.

                </p>

            </div>

        </div>

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