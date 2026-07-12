<?php
// publik_minggu.php - Menampilkan Rincian Laporan Ibadah Minggu Publik
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include 'koneksi.php';

$bulan_pilih = isset($_GET['bulan']) ? mysqli_real_escape_string($koneksi, $_GET['bulan']) : date('Y-m');
list($tahun, $bulan) = explode('-', $bulan_pilih);

// 1. Algoritma mencari semua tanggal Hari Minggu pada bulan & tahun terpilih
$tanggal_minggu_list = [];
$jumlah_hari = cal_days_in_month(CAL_GREGORIAN, $bulan, $tahun);

for ($d = 1; $d <= $jumlah_hari; $d++) {
    $time = mktime(0, 0, 0, $bulan, $d, $tahun);
    if (date('N', $time) == 7) { // 7 berarti Hari Minggu dalam standar ISO-8601
        $tanggal_minggu_list[] = date('Y-m-d', $time);
    }
}
?>

<div class="card border-0 shadow-sm rounded-4 p-4 bg-white">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 border-bottom pb-3 mb-4">
        <div>
            <h5 class="fw-bold text-dark mb-1">Rincian Persembahan Ibadah Minggu</h5>
            <small class="text-muted">Daftar perolehan pundi per sesi ibadah (Subuh, Pagi, Malam) setiap pekan</small>
        </div>
        
        <!-- Filter Bulan Digital -->
        <div class="d-flex align-items-center gap-2">
            <label for="filter_bulan_minggu" class="form-label mb-0 small fw-bold text-secondary text-nowrap">Pilih Periode:</label>
            <input type="month" id="filter_bulan_minggu" class="form-control font-monospace fw-bold text-dark bg-light" value="<?= $bulan_pilih; ?>" style="max-width: 180px;">
        </div>
    </div>

    <?php 
    if (!empty($tanggal_minggu_list)):
        $pekan = 1;
        foreach ($tanggal_minggu_list as $tgl_minggu):
            // Ambil data dari tabel admin keuangan ibadah minggu untuk tanggal ini
            $query = mysqli_query($koneksi, "SELECT * FROM keuangan_ibadah_minggu WHERE tanggal = '$tgl_minggu'");
            $data = mysqli_fetch_assoc($query);

            // Set nilai default 0 jika admin belum menginput data di tanggal tersebut
            $pundi1_subuh = floatval($data['pundi1_subuh'] ?? 0);
            $pundi1_pagi  = floatval($data['pundi1_pagi'] ?? 0);
            $pundi1_malam = floatval($data['pundi1_malam'] ?? 0);

            $pundi2_subuh = floatval($data['pundi2_subuh'] ?? 0);
            $pundi2_pagi  = floatval($data['pundi2_pagi'] ?? 0);
            $pundi2_malam = floatval($data['pundi2_malam'] ?? 0);

            $pundi3_subuh = floatval($data['pundi3_subuh'] ?? 0);
            $pundi3_pagi  = floatval($data['pundi3_pagi'] ?? 0);
            $pundi3_malam = floatval($data['pundi3_malam'] ?? 0);

            // Total per sesi kolom vertikal
            $total_subuh = $pundi1_subuh + $pundi2_subuh + $pundi3_subuh;
            $total_pagi  = $pundi1_pagi + $pundi2_pagi + $pundi3_pagi;
            $total_malam = $pundi1_malam + $pundi2_malam + $pundi3_malam;

            // Total per jenis pundi baris horizontal
            $total_pundi1 = $pundi1_subuh + $pundi1_pagi + $pundi1_malam;
            $total_pundi2 = $pundi2_subuh + $pundi2_pagi + $pundi2_malam;
            $total_pundi3 = $pundi3_subuh + $pundi3_pagi + $pundi3_malam;

            // Grand total akumulasi pekanan
            $grand_pekan = $total_subuh + $total_pagi + $total_malam;
    ?>
        <!-- Blok Card Per Pekan Hari Minggu -->
        <div class="mb-5 border border-light-subtle rounded-4 overflow-hidden shadow-sm">
            <div class="p-3 text-white d-flex justify-content-between align-items-center flex-wrap gap-2" style="background: linear-gradient(135deg, #371266, #1f043a);">
                <span class="fw-bold fs-6"><i class="bi bi-calendar-event-fill text-warning me-2"></i>Minggu ke-<?= $pekan++; ?></span>
                <span class="badge bg-white font-monospace text-dark px-3 py-2 fw-bold rounded-3 shadow-sm" style="color: #371266 !important;">
                    <i class="bi bi-calendar3 me-1 text-primary" style="color: #6f42c1 !important;"></i>
                    <?= date('d M Y', strtotime($tgl_minggu)); ?>
                </span>
            </div>

            <div class="p-3 bg-white">
                <div class="table-responsive rounded-3">
                    <table class="table table-bordered table-hover align-middle mb-0 text-center text-nowrap" style="min-width: 600px;">
                        <thead class="bg-light text-secondary text-uppercase fw-bold" style="font-size: 0.75rem; letter-spacing: 0.5px;">
                            <tr>
                                <th class="py-2.5 text-start ps-3" style="width: 25%;">Kategori Pundi</th>
                                <th class="py-2.5" style="width: 18%;">Ibadah Subuh</th>
                                <th class="py-2.5" style="width: 18%;">Ibadah Pagi</th>
                                <th class="py-2.5" style="width: 18%;">Ibadah Malam</th>
                                <th class="py-2.5 text-end pe-3" style="width: 21%;">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="text-start ps-3 fw-medium text-dark">Pundi I (Pelayanan Jemaat)</td>
                                <td class="font-monospace text-muted">Rp <?= number_format($pundi1_subuh, 0, ',', '.'); ?></td>
                                <td class="font-monospace text-muted">Rp <?= number_format($pundi1_pagi, 0, ',', '.'); ?></td>
                                <td class="font-monospace text-muted">Rp <?= number_format($pundi1_malam, 0, ',', '.'); ?></td>
                                <td class="text-end pe-3 font-monospace fw-bold text-dark">Rp <?= number_format($total_pundi1, 0, ',', '.'); ?></td>
                            </tr>
                            <tr>
                                <td class="text-start ps-3 fw-medium text-dark">Pundi II (Pembangunan)</td>
                                <td class="font-monospace text-muted">Rp <?= number_format($pundi2_subuh, 0, ',', '.'); ?></td>
                                <td class="font-monospace text-muted">Rp <?= number_format($pundi2_pagi, 0, ',', '.'); ?></td>
                                <td class="font-monospace text-muted">Rp <?= number_format($pundi2_malam, 0, ',', '.'); ?></td>
                                <td class="text-end pe-3 font-monospace fw-bold text-dark">Rp <?= number_format($total_pundi2, 0, ',', '.'); ?></td>
                            </tr>
                            <tr class="border-bottom border-secondary-subtle">
                                <td class="text-start ps-3 fw-medium text-dark">Pundi III (Wilayah/Sinode)</td>
                                <td class="font-monospace text-muted">Rp <?= number_format($pundi3_subuh, 0, ',', '.'); ?></td>
                                <td class="font-monospace text-muted">Rp <?= number_format($pundi3_pagi, 0, ',', '.'); ?></td>
                                <td class="font-monospace text-muted">Rp <?= number_format($pundi3_malam, 0, ',', '.'); ?></td>
                                <td class="text-end pe-3 font-monospace fw-bold text-dark">Rp <?= number_format($total_pundi3, 0, ',', '.'); ?></td>
                            </tr>
                            <!-- FOOTER SUB-TOTAL PER KOLOM SESI -->
                            <tr class="fw-bold bg-light-subtle" style="background: #f8fafc;">
                                <td class="text-start ps-3 py-2.5 text-secondary fw-bold" style="font-size: 0.8rem;">TOTAL PER SESI</td>
                                <td class="font-monospace text-dark">Rp <?= number_format($total_subuh, 0, ',', '.'); ?></td>
                                <td class="font-monospace text-dark">Rp <?= number_format($total_pagi, 0, ',', '.'); ?></td>
                                <td class="font-monospace text-dark">Rp <?= number_format($total_malam, 0, ',', '.'); ?></td>
                                <td class="text-end pe-3 font-monospace text-purple-premium" style="font-size: 0.95rem;">
                                    Rp <?= number_format($grand_pekan, 0, ',', '.'); ?>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    <?php 
        endforeach;
    else: 
    ?>
        <div class="py-5 text-muted text-center small">
            <i class="bi bi-calendar-x fs-2 mb-2 d-block text-secondary"></i>
            Gagal memetakan kalender Hari Minggu pada periode bulan terpilih.
        </div>
    <?php endif; ?>
</div>

<script>
document.addEventListener("DOMContentLoaded", function() {
    const filterBulanMinggu = document.getElementById("filter_bulan_minggu");
    if (filterBulanMinggu) {
        filterBulanMinggu.addEventListener("change", function() {
            const url = new URL(window.location);
            url.searchParams.set("subtab", "minggu"); // Tetap stay di subtab minggu publik
            url.searchParams.set("bulan", this.value); // Set nilai bulan baru
            url.searchParams.delete("tab");           // Bersihkan sisa instruksi admin router
            window.location.search = url.search;
        });
    }
});
</script>