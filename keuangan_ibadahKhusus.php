<?php
// keuangan_ibadahKhusus.php - Rincian Penerimaan Ibadah Khusus Publik
if (session_status() === PHP_SESSION_NONE) { session_start(); }
include 'koneksi.php';

// Ambil parameter filter bulan
$bulan_pilih = $_GET['bulan'] ?? date('Y-m');
?>

<div class="card border-0 shadow-sm rounded-4 p-4 bg-white">
    <!-- Header Kontrol Panel -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 border-bottom pb-3 mb-4">
        <div>
            <h5 class="fw-bold text-dark mb-1">Buku Penerimaan Ibadah Khusus</h5>
            <small class="text-muted">Daftar perolehan persembahan dari ibadah-ibadah khusus sepanjang periode bulan berjalan</small>
        </div>
        <div class="d-flex align-items-center gap-2">
            <label for="filter_bulan_khusus" class="form-label mb-0 small fw-bold text-secondary text-nowrap">Periode:</label>
            <input type="month" id="filter_bulan_khusus" class="form-control font-monospace fw-bold text-dark bg-light" value="<?= $bulan_pilih; ?>" style="max-width: 180px;">
        </div>
    </div>

    <!-- TABEL DATA IBADAH KHUSUS -->
    <div class="table-responsive rounded-3 border border-light-subtle shadow-sm">
        <table class="table table-hover align-middle mb-0 text-center text-nowrap" style="min-width: 700px; font-size: 0.9rem;">
            <thead class="bg-light text-secondary text-uppercase fw-bold" style="font-size: 0.75rem; letter-spacing: 0.5px;">
                <tr>
                    <th class="py-3" style="width: 8%;">No</th>
                    <th class="py-3" style="width: 17%;">Tanggal</th>
                    <th class="py-3 text-start ps-4" style="width: 55%;">Keterangan Kegiatan Ibadah</th>
                    <th class="py-3 text-end pe-4" style="width: 20%;">Nominal Persembahan</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                // Ambil data dari tabel keuangan_ibadah_khusus berdasarkan filter bulan pilihan
                $query_khusus = mysqli_query($koneksi, "SELECT * FROM keuangan_ibadah_khusus WHERE DATE_FORMAT(tanggal, '%Y-%m') = '$bulan_pilih' ORDER BY tanggal ASC");
                
                if (mysqli_num_rows($query_khusus) > 0):
                    $no = 1;
                    $grand_total_khusus = 0;
                    while ($row = mysqli_fetch_assoc($query_khusus)):
                        $nominal = floatval($row['nominal']);
                        $grand_total_khusus += $nominal;
                ?>
                <tr class="border-bottom border-light-subtle">
                    <td class="text-muted small"><?= $no++; ?></td>
                    <td class="font-monospace text-secondary" style="font-size: 0.85rem;"><?= date('d-m-Y', strtotime($row['tanggal'])); ?></td>
                    <!-- Menampilkan nama kegiatan ibadah khusus secara dinamis -->
                    <td class="text-start ps-4 text-dark fw-medium">Persembahan <?= htmlspecialchars($row['kegiatan']); ?></td>
                    <td class="text-end pe-4 font-monospace fw-bold text-success">
                        + Rp <?= number_format($nominal, 0, ',', '.'); ?>
                    </td>
                </tr>
                <?php endwhile; ?>
                
                <!-- FOOTER TOTAL AKHIR -->
                <tr class="fw-bold bg-light" style="background: #f8fafc;">
                    <td colspan="3" class="text-start ps-4 py-3 text-secondary fw-bold" style="font-size: 0.75rem;">TOTAL PENERIMAAN BULANAN</td>
                    <td class="text-end pe-4 py-3 font-monospace text-purple-premium fs-6" style="color: #6f42c1 !important;">
                        Rp <?= number_format($grand_total_khusus, 0, ',', '.'); ?>
                    </td>
                </tr>
                <?php else: ?>
                <tr>
                    <td colspan="4" class="py-5 text-muted text-center small">
                        <i class="bi bi-stars fs-2 mb-2 d-block text-secondary"></i>
                        Tidak ditemukan adanya pelaksanaan kegiatan ibadah khusus pada periode bulan ini.
                    </td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function() {
    const filterBulanKhusus = document.getElementById("filter_bulan_khusus");
    if (filterBulanKhusus) {
        filterBulanKhusus.addEventListener("change", function() {
            const url = new URL(window.location);
            url.searchParams.set("subtab", "khusus"); // Tetap stay di subtab khusus publik
            url.searchParams.set("bulan", this.value); // Ambil data bulan baru
            url.searchParams.delete("tab");           // Bersihkan instruksi sisa router admin
            window.location.search = url.search;
        });
    }
});
</script>