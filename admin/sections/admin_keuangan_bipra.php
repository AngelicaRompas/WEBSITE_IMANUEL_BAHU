<?php
// admin/sections/admin_keuangan_bipra.php - Filter Tanggal Per Bulan Dinamis
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include '../koneksi.php';

// Ambil filter tahun pelaksanaan (Default: Tahun ini) dan kategori komisi
$tahun_pilih  = isset($_GET['tgl_keuangan']) ? date('Y', strtotime($_GET['tgl_keuangan'])) : date('Y');
$komisi_pilih = $_GET['komisi_pilih'] ?? 'PKB';

$nama_bulan_indo = [
    1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
    5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
    9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
];

// Ambil data nominal dan tanggal yang sudah diinput berdasarkan Tahun dan Komisi
$data_existing = [];
$q_bipra = mysqli_query($koneksi, "SELECT tanggal, bulan_target, nominal FROM keuangan_bipra WHERE YEAR(tanggal) = '$tahun_pilih' AND komisi = '$komisi_pilih'");
if ($q_bipra) {
    while ($row = mysqli_fetch_assoc($q_bipra)) {
        $data_existing[$row['bulan_target']] = [
            'tanggal' => $row['tanggal'],
            'nominal' => floatval($row['nominal'])
        ];
    }
}
?>

<div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4 bg-white mx-auto" style="max-width: 850px;">
    <!-- Header Elemen Panel Kontrol -->
    <div class="p-4 d-flex flex-column flex-md-row align-items-start align-items-md-center justify-content-between text-white gap-3" style="background: linear-gradient(135deg, #4b1a8a, #2e0854);">
        <div class="d-flex align-items-center gap-3">
            <div class="p-2 bg-white bg-opacity-10 rounded-3 border border-white-20">
                <i class="bi bi-diagram-3-fill text-warning fs-4"></i>
            </div>
            <div>
                <h5 class="fw-bold mb-0" style="letter-spacing: 0.5px; color: #ffffff;">Penyetoran BIPRA & Komisi</h5>
                <small style="color: rgba(255, 255, 255, 0.75);">Manajemen kas masuk dengan input tanggal dinamis per bulan</small>
            </div>
        </div>
        
        <!-- Filter Kontrol Sisi Kanan -->
        <div class="d-flex flex-wrap align-items-center gap-2 w-100 justify-content-md-end" style="max-width: 400px;">
            <div class="style-tgl-box flex-grow-1" style="max-width: 180px;">
                <label class="form-label fw-semibold mb-1" style="font-size: 0.72rem; color: rgba(255, 255, 255, 0.7); text-transform: uppercase;">Pilih Komisi</label>
                <select id="filter_komisi_bipra" class="form-select form-select-sm fw-bold bg-white text-dark border-0 rounded-3" style="cursor: pointer;">
                    <?php 
                    $daftar_komisi = ['PKB', 'WKI', 'PEMUDA', 'REMAJA', 'ASM', 'LANSIA', 'KPDP'];
                    foreach ($daftar_komisi as $k):
                    ?>
                        <option value="<?= $k; ?>" <?= $komisi_pilih === $k ? 'selected' : ''; ?>><?= $k; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="style-tgl-box flex-grow-1" style="max-width: 180px;">
                <label class="form-label fw-semibold mb-1" style="font-size: 0.72rem; color: rgba(255, 255, 255, 0.7); text-transform: uppercase;">Tahun Periode</label>
                <select id="filter_tahun_bipra" class="form-select form-select-sm fw-bold bg-white text-dark border-0 rounded-3" style="cursor: pointer;">
                    <?php 
                    $tahun_sekarang = intval(date('Y'));
                    for ($t = $tahun_sekarang - 2; $t <= $tahun_sekarang + 2; $t++):
                    ?>
                        <option value="<?= $t; ?>-01-01" <?= intval($tahun_pilih) === $t ? 'selected' : ''; ?>><?= $t; ?></option>
                    <?php endfor; ?>
                </select>
            </div>
        </div>
    </div>

    <!-- Konten Form Utama -->
    <div class="p-3 p-md-4">
        <form action="proses/proses_keuangan_bipra.php" method="POST">
            <input type="hidden" name="tahun_periode" value="<?= $tahun_pilih; ?>">
            <input type="hidden" name="komisi" value="<?= $komisi_pilih; ?>">

            <div class="table-responsive rounded-3 border border-light-subtle shadow-sm mb-4">
                <table class="table table-hover align-middle mb-0 text-center text-nowrap">
                    <thead class="bg-light text-secondary text-uppercase fw-bold" style="font-size: 0.75rem; letter-spacing: 0.8px;">
                        <tr>
                            <th class="py-3 text-start ps-4" style="width: 25%;">Bulan Laporan</th>
                            <th class="py-3" style="width: 35%;">Tanggal Setor</th>
                            <th class="py-3 text-end Back pe-4" style="width: 40%;">Nominal Setoran (Rp)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        for ($b = 1; $b <= 12; $b++): 
                            $val_nominal = $data_existing[$b]['nominal'] ?? 0;
                            // Default tanggal disesuaikan dengan baris bulan berjalan pada tahun pilihan jika belum ada data
                            $val_tanggal = $data_existing[$b]['tanggal'] ?? sprintf('%04d-%02d-%02d', $tahun_pilih, $b, date('d'));
                        ?>
                        <tr class="bipra-row border-bottom border-light-subtle">
                            <!-- Kolom 1: Nama Bulan -->
                            <td class="text-start ps-4 fw-bold text-dark py-2">
                                <span class="badge px-3 py-2 text-purple-premium rounded-3" style="background: rgba(147, 51, 234, 0.08); font-size: 0.85rem; min-width: 110px; display: inline-block; text-center;">
                                    <?= $nama_bulan_indo[$b]; ?>
                                </span>
                                <input type="hidden" name="bulan_target[]" value="<?= $b; ?>">
                            </td>
                            
                            <!-- Kolom 2: Input Tanggal Penyetoran (Dinamis Berdampingan) -->
                            <td>
                                <input type="date" name="tanggal_setor[]" class="form-control form-control-sm text-center fw-semibold text-dark bg-light border-light-subtle mx-auto" value="<?= $val_tanggal; ?>" style="max-width: 180px;" required>
                            </td>
                            
                            <!-- Kolom 3: Nominal Anggaran -->
                            <td class="pe-4">
                                <input type="number" name="nominal[]" class="form-control text-end table-input-digital input-bipra-hitung" value="<?= $val_nominal; ?>" min="0" placeholder="0" style="max-width: 220px; margin-left: auto;">
                            </td>
                        </tr>
                        <?php endfor; ?>
                        
                        <!-- Footer Total Akumulasi -->
                        <tr class="fw-bold border-0 bg-light-subtle" style="background: #f8fafc;">
                            <td colspan="2" class="text-start ps-4 py-3 text-secondary fw-bolder" style="font-size: 0.75rem;">TOTAL KESELURUHAN</td>
                            <td class="text-end pe-4 py-3 font-monospace text-purple-premium text-end" id="total-bipra-grand-all" style="font-size: 1.1rem; text-shadow: 0 0 10px rgba(124, 58, 237, 0.1);">Rp 0</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="text-end">
                <button type="submit" name="simpan_keuangan_bipra" class="btn btn-purple-digital w-100 w-md-auto px-4 py-2.5 rounded-3 fw-bold border-0 shadow-sm">
                    <i class="bi bi-cpu-fill me-2"></i> Sinkronisasi Laporan <?= $komisi_pilih; ?>
                </button>
            </div>
        </form>
    </div>
</div>

<style>
.table-input-digital { border: 1px solid #e2e8f0 !important; background: #f8fafc !important; text-align: right; font-family: var(--bs-font-monospace); font-weight: 600; color: #334155; font-size: 0.85rem; padding: 0.4rem 0.6rem !important; border-radius: 6px; transition: all 0.2s ease; }
.table-input-digital:focus { background: #ffffff !important; border-color: #4b1a8a !important; box-shadow: 0 0 0 3px rgba(75, 26, 138, 0.15) !important; }
.table-input-digital::-webkit-outer-spin-button, .table-input-digital::-webkit-inner-spin-button { -webkit-appearance: none; margin: 0; }
.table-input-digital[type=number] { -moz-appearance: textfield; }
.btn-purple-digital { background: #6f42c1; color: #fff; transition: all 0.2s; }
.btn-purple-digital:hover { background: #5a32a3; color: #fff; }
</style>

<script>
document.addEventListener("DOMContentLoaded", function () {
    const inputsBipra = document.querySelectorAll(".input-bipra-hitung");
    const filterTahun = document.getElementById("filter_tahun_bipra");
    const filterKomisi = document.getElementById("filter_komisi_bipra");

    function hitungGrandTotalBipra() {
        let total = 0;
        inputsBipra.forEach(input => {
            total += parseFloat(input.value) || 0;
        });
        document.getElementById("total-bipra-grand-all").innerText = "Rp " + total.toLocaleString('id-ID');
    }

    function reloadRouterBipra() {
        const url = new URL(window.location);
        url.searchParams.set("tab", "edit-keuangan");
        url.searchParams.set("subtab", "bipra");
        url.searchParams.set("tgl_keuangan", filterTahun.value);
        url.searchParams.set("komisi_pilih", filterKomisi.value);
        window.location.search = url.search;
    }

    hitungGrandTotalBipra();
    inputsBipra.forEach(box => { box.addEventListener("input", hitungGrandTotalBipra); });

    if (filterTahun) { filterTahun.addEventListener("change", reloadRouterBipra); }
    if (filterKomisi) { filterKomisi.addEventListener("change", reloadRouterBipra); }
});
</script>