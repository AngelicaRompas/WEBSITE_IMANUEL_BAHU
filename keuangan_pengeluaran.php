<?php
// keuangan_pengeluaran.php - Rincian Data Pengeluaran Kas Publik
if (session_status() === PHP_SESSION_NONE) { session_start(); }
include 'koneksi.php';

// Ambil parameter filter bulan
$bulan_pilih = $_GET['bulan'] ?? date('Y-m');
?>

<div class="card border-0 shadow-sm rounded-4 p-4 bg-white">
    <!-- Header Kontrol Panel -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 border-bottom pb-3 mb-4">
        <div>
            <h5 class="fw-bold text-dark mb-1">Buku Pengeluaran Kas Jemaat</h5>
            <small class="text-muted">Daftar rincian alokasi dan realisasi pengeluaran dana pelayanan sepanjang periode bulan berjalan</small>
        </div>
        <div class="d-flex align-items-center gap-2">
            <label for="filter_bulan_pengeluaran" class="form-label mb-0 small fw-bold text-secondary text-nowrap">Periode:</label>
            <input type="month" id="filter_bulan_pengeluaran" class="form-control font-monospace fw-bold text-dark bg-light" value="<?= $bulan_pilih; ?>" style="max-width: 180px;">
        </div>
    </div>

    <!-- TABEL DATA PENGELUARAN -->
    <div class="table-responsive rounded-3 border border-light-subtle shadow-sm">
        <table class="table table-hover align-middle mb-0 text-center text-nowrap" style="min-width: 700px; font-size: 0.9rem;">
            <thead class="bg-light text-secondary text-uppercase fw-bold" style="font-size: 0.75rem; letter-spacing: 0.5px;">
                <tr>
                    <th class="py-3" style="width: 8%;">No</th>
                    <th class="py-3" style="width: 17%;">Tanggal Keluar</th>
                    <th class="py-3 text-start ps-4" style="width: 55%;">Keterangan Alokasi Pengeluaran</th>
                    <th class="py-3 text-end pe-4" style="width: 20%;">Nominal Dana</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                // Ambil data pengeluaran kas berdasarkan filter bulan pilihan
                // Diurutkan berdasarkan tanggal untuk memastikan kerapian laporan (ORDER BY tanggal ASC) tanpa memanggil kolom ID yang rawan bentrok
                $query_pengeluaran = mysqli_query($koneksi, "SELECT * FROM keuangan_pengeluaran WHERE DATE_FORMAT(tanggal, '%Y-%m') = '$bulan_pilih' ORDER BY tanggal ASC");
                
                if (mysqli_num_rows($query_pengeluaran) > 0):
                    $no = 1;
                    $grand_total_pengeluaran = 0;
                    while ($row = mysqli_fetch_assoc($query_pengeluaran)):
                        $nominal = floatval($row['nominal']);
                        $grand_total_pengeluaran += $nominal;
                        
                        // Menangani nama kolom keterangan dinamis dari database (adaptif: 'keterangan' atau 'keperluan')
                        $keterangan_tampil = $row['keterangan'] ?? ($row['keperluan'] ?? '-');
                ?>
                <tr class="border-bottom border-light-subtle">
                    <td class="text-muted small"><?= $no++; ?></td>
                    <td class="font-monospace text-secondary" style="font-size: 0.85rem;"><?= date('d-m-Y', strtotime($row['tanggal'])); ?></td>
                    <td class="text-start ps-4 text-dark fw-medium"><?= htmlspecialchars($keterangan_tampil); ?></td>
                    <td class="text-end pe-4 font-monospace fw-bold text-danger">
                        - Rp <?= number_format($nominal, 0, ',', '.'); ?>
                    </td>
                </tr>
                <?php endwhile; ?>
                
                <!-- FOOTER TOTAL AKHIR PENGELUARAN -->
                <tr class="fw-bold bg-light" style="background: #f8fafc;">
                    <td colspan="3" class="text-start ps-4 py-3 text-secondary fw-bold" style="font-size: 0.75rem;">TOTAL PENGELUARAN BULANAN</td>
                    <td class="text-end pe-4 py-3 font-monospace text-purple-premium fs-6" style="color: #6f42c1 !important;">
                        Rp <?= number_format($grand_total_pengeluaran, 0, ',', '.'); ?>
                    </td>
                </tr>
                <?php else: ?>
                <tr>
                    <td colspan="4" class="py-5 text-muted text-center small">
                        <i class="bi bi-cart-dash-fill fs-2 mb-2 d-block text-secondary"></i>
                        Tidak ditemukan adanya rekaman data pengeluaran kas pada periode bulan ini.
                    </td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function() {
    const filterBulanPengeluaran = document.getElementById("filter_bulan_pengeluaran");
    if (filterBulanPengeluaran) {
        filterBulanPengeluaran.addEventListener("change", function() {
            const url = new URL(window.location);
            url.searchParams.set("subtab", "pengeluaran"); // Mengunci router tetap di subtab pengeluaran publik
            url.searchParams.set("bulan", this.value);     // Membawa parameter nilai bulan baru
            url.searchParams.delete("tab");               // Menghapus sisa router admin agar tidak bentrok atau terlempar
            window.location.search = url.search;
        });
    }
});
</script>