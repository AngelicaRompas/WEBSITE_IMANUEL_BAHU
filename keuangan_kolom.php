<?php
// keuangan_kolom.php - Rincian Penyetoran Kas Kolom 1-28 Publik (Format Keterangan Minggu)
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include 'koneksi.php';

// Ambil parameter filter bulan dan nomor kolom (Default: Bulan ini & Kolom 1)
$bulan_pilih = isset($_GET['bulan']) ? mysqli_real_escape_string($koneksi, $_GET['bulan']) : date('Y-m');
$kolom_pilih = isset($_GET['kolom_no']) ? intval($_GET['kolom_no']) : 1;

list($tahun, $bulan) = explode('-', $bulan_pilih);

// Algoritma memetakan seluruh tanggal Hari Minggu pada bulan tersebut
$tanggal_minggu_list = [];
$jumlah_hari = cal_days_in_month(CAL_GREGORIAN, $bulan, $tahun);
for ($d = 1; $d <= $jumlah_hari; $d++) {
    $time = mktime(0, 0, 0, $bulan, $d, $tahun);
    if (date('N', $time) == 7) {
        $tanggal_minggu_list[] = date('Y-m-d', $time);
    }
}

// Array untuk konversi penulisan angka Romawi estetik
$angka_romawi = [1 => 'I', 2 => 'II', 3 => 'III', 4 => 'IV', 5 => 'V'];
?>

<div class="card border-0 shadow-sm rounded-4 p-4 bg-white">
    <!-- Header Control Panel Publik -->
    <div class="d-flex flex-column flex-xl-row justify-content-between align-items-xl-center gap-3 border-bottom pb-3 mb-4">
        <div>
            <h5 class="fw-bold text-dark mb-1">Rincian Penyetoran Kas Kolom</h5>
            <small class="text-muted">Pilih bulan pelaksanaan dan nomor kolom untuk melihat detail transparansi setoran pekanan</small>
        </div>
        
        <!-- Filter Digital Dropdown & Month Input -->
        <div class="d-flex flex-wrap align-items-center gap-3">
            <div class="d-flex align-items-center gap-2">
                <label for="filter_pilih_kolom" class="form-label mb-0 small fw-bold text-secondary text-nowrap">Kolom:</label>
                <select id="filter_pilih_kolom" class="form-select fw-bold text-dark bg-light rounded-3" style="max-width: 130px; border: 1px solid #e2e8f0;">
                    <?php for($i = 1; $i <= 28; $i++): ?>
                        <option value="<?= $i; ?>" <?= $kolom_pilih == $i ? 'selected' : ''; ?>>Kolom <?= $i; ?></option>
                    <?php endfor; ?>
                </select>
            </div>

            <div class="d-flex align-items-center gap-2">
                <label for="filter_bulan_kolom" class="form-label mb-0 small fw-bold text-secondary text-nowrap">Periode:</label>
                <input type="month" id="filter_bulan_kolom" class="form-control font-monospace fw-bold text-dark bg-light" value="<?= $bulan_pilih; ?>" style="max-width: 180px;">
            </div>
        </div>
    </div>

    <!-- TAMPILAN UTAMA TABEL SETORAN -->
    <div class="table-responsive rounded-3 border border-light-subtle shadow-sm">
        <table class="table table-hover align-middle mb-0 text-center text-nowrap" style="min-width: 1100px; font-size: 0.9rem;">
            <thead class="text-secondary text-uppercase fw-bold bg-light" style="font-size: 0.75rem; letter-spacing: 0.5px;">
                <tr>
                    <!-- FIXED: Judul kolom diubah dari Tanggal Setor menjadi Minggu Ke -->
                    <th class="py-3 text-start ps-4" style="width: 15%;">Minggu Ke</th>
                    <th class="py-3">Pers Kolom</th>
                    <th class="py-3">PKB</th>
                    <th class="py-3">WKI</th>
                    <th class="py-3">Pemuda</th>
                    <th class="py-3">Remaja</th>
                    <th class="py-3">ASM</th>
                    <th class="py-3">PDP</th>
                    <th class="py-3">PEM</th>
                    <th class="py-3">Awal Bln</th>
                    <th class="py-3 text-end pe-4" style="width: 14%;">Jumlah</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                if (!empty($tanggal_minggu_list)):
                    // Inisialisasi variabel akumulasi total akhir footer
                    $g_pers = 0; $g_pkb = 0; $g_wki = 0; $g_pemuda = 0; $g_remaja = 0;
                    $g_asm = 0; $g_pdp = 0; $g_pem = 0; $g_awal = 0; $g_grand = 0;
                    
                    // Penghitung indeks minggu berjalan
                    $index_pekan = 1;

                    foreach ($tanggal_minggu_list as $tgl_pekan):
                        // Tarik data kas kolom dari database
                        $query = mysqli_query($koneksi, "SELECT * FROM keuangan_ibadah_kolom WHERE tanggal = '$tgl_pekan' AND kolom = '$kolom_pilih'");
                        $data = mysqli_fetch_assoc($query);

                        $pers    = floatval($data['pers_kolom'] ?? 0);
                        $pkb     = floatval($data['pkb'] ?? 0);
                        $wki     = floatval($data['wki'] ?? 0);
                        $pemuda  = floatval($data['pemuda'] ?? 0);
                        $remaja  = floatval($data['remaja'] ?? 0);
                        $asm     = floatval($data['asm'] ?? 0);
                        $pdp     = floatval($data['pdp'] ?? 0);
                        $pem     = floatval($data['pem'] ?? 0);
                        $awal    = floatval($data['awal_bln'] ?? 0);

                        $total_baris = $pers + $pkb + $wki + $pemuda + $remaja + $asm + $pdp + $pem + $awal;

                        $g_pers += $pers; $g_pkb += $pkb; $g_wki += $wki; $g_pemuda += $pemuda; $g_remaja += $remaja;
                        $g_asm += $asm; $g_pdp += $pdp; $g_pem += $pem; $g_awal += $awal; $g_grand += $total_baris;
                        
                        // Konversi penanda indeks angka romawi
                        $label_romawi = $angka_romawi[$index_pekan] ?? $index_pekan;
                ?>
                <tr class="border-bottom border-light-subtle">
                    <!-- FIXED: Merender teks "Minggu Ke-I" secara kontras & menyematkan tanggal aslinya pada tooltip kecil -->
                    <td class="text-start ps-4 py-3 fw-semibold text-dark" style="font-size: 0.9rem;" title="Tanggal Setor: <?= date('d-m-Y', strtotime($tgl_pekan)); ?>">
                        Minggu Ke-<?= $label_romawi; ?>
                    </td>
                    <td class="font-monospace text-muted">Rp <?= number_format($pers, 0, ',', '.'); ?></td>
                    <td class="font-monospace text-muted">Rp <?= number_format($pkb, 0, ',', '.'); ?></td>
                    <td class="font-monospace text-muted">Rp <?= number_format($wki, 0, ',', '.'); ?></td>
                    <td class="font-monospace text-muted">Rp <?= number_format($pemuda, 0, ',', '.'); ?></td>
                    <td class="font-monospace text-muted">Rp <?= number_format($remaja, 0, ',', '.'); ?></td>
                    <td class="font-monospace text-muted">Rp <?= number_format($asm, 0, ',', '.'); ?></td>
                    <td class="font-monospace text-muted">Rp <?= number_format($pdp, 0, ',', '.'); ?></td>
                    <td class="font-monospace text-muted">Rp <?= number_format($pem, 0, ',', '.'); ?></td>
                    <td class="font-monospace text-muted">Rp <?= number_format($awal, 0, ',', '.'); ?></td>
                    <td class="text-end pe-4 font-monospace fw-bold text-dark">
                        Rp <?= number_format($total_baris, 0, ',', '.'); ?>
                    </td>
                </tr>
                <?php 
                        $index_pekan++;
                    endforeach; 
                ?>

                <!-- FOOTER GRAND TOTAL BULANAN PER KATEGORI -->
                <tr class="fw-bold bg-light" style="background: #f8fafc;">
                    <td class="text-start ps-4 py-3 text-secondary fw-bold" style="font-size: 0.75rem;">TOTAL BULANAN</td>
                    <td class="font-monospace text-dark">Rp <?= number_format($g_pers, 0, ',', '.'); ?></td>
                    <td class="font-monospace text-dark">Rp <?= number_format($g_pkb, 0, ',', '.'); ?></td>
                    <td class="font-monospace text-dark">Rp <?= number_format($g_wki, 0, ',', '.'); ?></td>
                    <td class="font-monospace text-dark">Rp <?= number_format($g_pemuda, 0, ',', '.'); ?></td>
                    <td class="font-monospace text-dark">Rp <?= number_format($g_remaja, 0, ',', '.'); ?></td>
                    <td class="font-monospace text-dark">Rp <?= number_format($g_asm, 0, ',', '.'); ?></td>
                    <td class="font-monospace text-dark">Rp <?= number_format($g_pdp, 0, ',', '.'); ?></td>
                    <td class="font-monospace text-dark">Rp <?= number_format($g_pem, 0, ',', '.'); ?></td>
                    <td class="font-monospace text-dark">Rp <?= number_format($g_awal, 0, ',', '.'); ?></td>
                    <td class="text-end pe-4 py-3 font-monospace text-purple-premium fs-6" style="color: #6f42c1 !important;">
                        Rp <?= number_format($g_grand, 0, ',', '.'); ?>
                    </td>
                </tr>
                <?php else: ?>
                <tr>
                    <td colspan="11" class="py-5 text-muted text-center small">
                        <i class="bi bi-calendar-x fs-2 mb-2 d-block text-secondary"></i>
                        Gagal memetakan kalender penyerahan kas kolom pada bulan terpilih.
                    </td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function() {
    const filterBulanKolom = document.getElementById("filter_bulan_kolom");
    const filterPilihKolom = document.getElementById("filter_pilih_kolom");

    function jalankanReloadRouter() {
        const url = new URL(window.location);
        url.searchParams.set("subtab", "kolom"); 
        url.searchParams.set("kolom_no", filterPilihKolom.value);
        url.searchParams.set("bulan", filterBulanKolom.value);
        url.searchParams.delete("tab"); 
        window.location.search = url.search;
    }

    if (filterBulanKolom) {
        filterBulanKolom.addEventListener("change", jalankanReloadRouter);
    }
    if (filterPilihKolom) {
        filterPilihKolom.addEventListener("change", jalankanReloadRouter);
    }
});
</script>