<?php
// keuangan_bipra.php - Rekap Transparansi Penyetoran BIPRA Publik Bulanan
if (session_status() === PHP_SESSION_NONE) { session_start(); }
include 'koneksi.php';

$bulan_pilih = $_GET['bulan'] ?? date('Y-m');
list($tahun, $bulan) = explode('-', $bulan_pilih);
$bulan_int = intval($bulan);

$daftar_komisi = ['PKB', 'WKI', 'PEMUDA', 'REMAJA', 'ASM', 'LANSIA', 'KPDP'];

// Label deskripsi lengkap komisi kategorial jemaat
$label_komisi_lengkap = [
    'PKB'    => 'Pria / Kaum Bapa (PKB)',
    'WKI'    => 'Wanita / Kaum Ibu (WKI)',
    'PEMUDA' => 'Komisi Pelayanan Pemuda',
    'REMAJA' => 'Komisi Pelayanan Remaja',
    'ASM'    => 'Anak Sekolah Minggu (ASM)',
    'LANSIA' => 'Kelompok Jemaat Lansia',
    'KPDP'   => 'Komisi Kemitraan Pria & Diaken (KPDP)'
];
?>

<div class="card border-0 shadow-sm rounded-4 p-4 bg-white">
    <!-- Header Modul Panel Publik -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 border-bottom pb-3 mb-4">
        <div>
            <h5 class="fw-bold text-dark mb-1">Penyetoran Kas Kategorial (BIPRA)</h5>
            <small class="text-muted">Ikhtisar keterbukaan laporan keuangan masuk dari masing-masing komisi kategorial jemaat</small>
        </div>
        
        <div class="d-flex align-items-center gap-2">
            <label for="filter_bulan_bipra_publik" class="form-label mb-0 small fw-bold text-secondary text-nowrap">Pilih Bulan Laporan:</label>
            <input type="month" id="filter_bulan_bipra_publik" class="form-control font-monospace fw-bold text-dark bg-light" value="<?= $bulan_pilih; ?>" style="max-width: 180px;">
        </div>
    </div>

    <!-- Interface Tabel Khusus BIPRA -->
    <div class="table-responsive rounded-3 border border-light-subtle shadow-sm">
        <table class="table table-hover align-middle mb-0 text-center text-nowrap" style="min-width: 600px; font-size: 0.9rem;">
            <thead class="bg-light text-secondary text-uppercase fw-bold" style="font-size: 0.75rem; letter-spacing: 0.5px;">
                <tr>
                    <th class="py-3 text-start ps-4" style="width: 55%;">Komisi Pelayanan Jemaat</th>
                    <th class="py-3 text-end pe-4" style="width: 45%;">Total Nominal Setoran</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $grand_total_bipra = 0;
                
                foreach ($daftar_komisi as $komisi):
                    // Query mengambil akumulasi setoran berdasarkan tahun dan bulan laporan target
                    $query = mysqli_query($koneksi, "SELECT SUM(nominal) as total FROM keuangan_bipra WHERE DATE_FORMAT(tanggal, '%Y') = '$tahun' AND bulan_target = '$bulan_int' AND komisi = '$komisi'");
                    $data = mysqli_fetch_assoc($query);
                    $nominal = !empty($data['total']) ? floatval($data['total']) : 0;
                    $grand_total_bipra += $nominal;
                ?>
                <tr class="border-bottom border-light-subtle">
                    <td class="text-start ps-4 py-3 text-dark fw-medium">
                        <i class="bi bi-patch-check-fill text-primary me-2" style="color: #6f42c1 !important;"></i>
                        <?= $label_komisi_lengkap[$komisi]; ?>
                    </td>
                    <td class="text-end pe-4 font-monospace fw-bold <?= $nominal > 0 ? 'text-dark' : 'text-muted-digital'; ?>">
                        <?= $nominal > 0 ? 'Rp ' . number_format($nominal, 0, ',', '.') : '-'; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
                
                <!-- Footer Total Bulanan -->
                <tr class="fw-bold bg-light" style="background: #f8fafc;">
                    <td class="text-start ps-4 py-3 text-secondary fw-bold" style="font-size: 0.75rem;">TOTAL KESELURUHAN SETORAN KOMISI</td>
                    <td class="text-end pe-4 py-3 font-monospace text-purple-premium fs-6" style="color: #6f42c1 !important;">Rp <?= number_format($grand_total_bipra, 0, ',', '.'); ?></td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<style>
.text-muted-digital { color: #cbd5e1; font-weight: 500 !important; }
</style>

<script>
document.addEventListener("DOMContentLoaded", function() {
    const filterBulanBipra = document.getElementById("filter_bulan_bipra_publik");

    if (filterBulanBipra) {
        filterBulanBipra.addEventListener("change", function() {
            const url = new URL(window.location);
            url.searchParams.set("subtab", "bipra"); 
            url.searchParams.set("bulan", this.value); 
            url.searchParams.delete("tab");           
            window.location.search = url.search;
        });
    }
});
</script>