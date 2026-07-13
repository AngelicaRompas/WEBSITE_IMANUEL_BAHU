<?php
// keuangan_sampul.php - Laporan Detail Penerimaan Sampul Publik dengan Filter Pekan (Warna Dinamis Bersih)
if (session_status() === PHP_SESSION_NONE) { session_start(); }
include 'koneksi.php';

// Ambil parameter filter utama
$bulan_pilih  = $_GET['bulan'] ?? date('Y-m');
$sub_sampul   = $_GET['sub_sampul'] ?? 'all';
$minggu_pilih = isset($_GET['minggu_no']) ? intval($_GET['minggu_no']) : 1;

list($tahun, $bulan) = explode('-', $bulan_pilih);
$jumlah_hari = cal_days_in_month(CAL_GREGORIAN, $bulan, $tahun);

// Cari semua tanggal hari Minggu pada bulan terpilih
$tanggal_minggu_list = [];
for ($d = 1; $d <= $jumlah_hari; $d++) {
    $time = mktime(0, 0, 0, $bulan, $d, $tahun);
    if (date('N', $time) == 7) {
        $tanggal_minggu_list[] = date('Y-m-d', $time);
    }
}

// Validasi jika user memilih Minggu ke-5 padahal bulan tersebut hanya punya 4 hari Minggu
if ($minggu_pilih > count($tanggal_minggu_list)) {
    $minggu_pilih = 1;
}

$angka_romawi = [1 => 'I', 2 => 'II', 3 => 'III', 4 => 'IV', 5 => 'V'];

// Pemetaan Kategori Database untuk tampilan label
$kategori_map = [
    'persepuluhan'     => 'Sampul Persepuluhan',
    'hut_pribadi'      => 'Sampul Syukur HUT Pribadi',
    'pernikahan'       => 'Sampul Syukur Pernikahan',
    'lainnya'          => 'Persembahan & Sampul Syukur Lainnya',
    'bulanan_keluarga' => 'Persembahan Bulanan Keluarga'
];

// Pemetaan Warna Khusus Setiap Jenis Sampul
$warna_map = [
    'persepuluhan'     => ['teks' => '#0d6efd', 'bg_badge' => 'rgba(13, 110, 253, 0.08)', 'border' => '#0d6efd'],
    'hut_pribadi'      => ['teks' => '#dc3545', 'bg_badge' => 'rgba(220, 53, 69, 0.08)', 'border' => '#dc3545'],
    'pernikahan'       => ['teks' => '#e21181', 'bg_badge' => 'rgba(226, 17, 129, 0.08)', 'border' => '#e21181'],
    'lainnya'          => ['teks' => '#198754', 'bg_badge' => 'rgba(25, 135, 84, 0.08)', 'border' => '#198754'],
    'bulanan_keluarga' => ['teks' => '#6f42c1', 'bg_badge' => 'rgba(111, 66, 193, 0.08)', 'border' => '#6f42c1']
];
?>

<div class="card border-0 shadow-sm rounded-4 p-4 bg-white">
    <!-- Header Modul Panel Kontrol -->
    <div class="d-flex flex-column flex-xl-row justify-content-between align-items-xl-center gap-3 border-bottom pb-3 mb-4">
        <div>
            <h5 class="fw-bold text-dark mb-1">Penerimaan Sampul Jemaat</h5>
            <small class="text-muted">Rincian transparansi penyerahan persembahan sampul syukur keluarga & jemaat</small>
        </div>
        
        <!-- Wadah Grouping Filter Atas -->
        <div class="d-flex flex-wrap align-items-center gap-3">
            <!-- Dropdown Filter Minggu hanya muncul jika berada di Tab "Semua Sampul (Pekanan)" -->
            <?php if($sub_sampul === 'all' && !empty($tanggal_minggu_list)): ?>
            <div class="d-flex align-items-center gap-2">
                <label for="filter_pekan_sampul" class="form-label mb-0 small fw-bold text-secondary text-nowrap">Minggu:</label>
                <select id="filter_pekan_sampul" class="form-select fw-bold text-dark bg-light rounded-3" style="max-width: 150px; border: 1px solid #e2e8f0;">
                    <?php foreach($tanggal_minggu_list as $index => $tgl): ?>
                        <option value="<?= $index + 1; ?>" <?= $minggu_pilih == ($index + 1) ? 'selected' : ''; ?>>
                            Minggu Ke-<?= $angka_romawi[$index + 1] ?? ($index + 1); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <?php endif; ?>

            <div class="d-flex align-items-center gap-2">
                <label for="filter_bulan_sampul" class="form-label mb-0 small fw-bold text-secondary text-nowrap">Periode:</label>
                <input type="month" id="filter_bulan_sampul" class="form-control font-monospace fw-bold text-dark bg-light" value="<?= $bulan_pilih; ?>" style="max-width: 180px;">
            </div>
        </div>
    </div>

    <!-- Sub-Navigasi Menu Jenis Sampul -->
    <ul class="nav nav-pills gap-1 mb-4 pb-2 border-bottom overflow-x-auto flex-nowrap" id="sub-pills-sampul" style="font-size: 0.85rem;">
        <li class="nav-item">
            <button class="nav-link rounded-3 fw-bold text-nowrap sub-tab-link <?= $sub_sampul=='all'?'active':'' ?>" data-sub="all">Semua Sampul</button>
        </li>
        <?php foreach($kategori_map as $key => $label): ?>
        <li class="nav-item">
            <button class="nav-link rounded-3 fw-bold text-nowrap sub-tab-link <?= $sub_sampul==$key?'active':'' ?>" data-sub="<?= $key; ?>"><?= $label; ?></button>
        </li>
        <?php endforeach; ?>
    </ul>

    <!-- RENDER TABEL DATA MASUK -->
    <div class="table-responsive rounded-3 border border-light-subtle shadow-sm">
        
        <?php if($sub_sampul === 'all'): ?>
            <!-- ======================= INTERFACE TAB ALL (WARNA DINAMIS) ======================= -->
            <table class="table table-hover align-middle mb-0 text-center text-nowrap" style="min-width: 600px; font-size: 0.9rem;">
                <thead class="bg-light text-secondary text-uppercase fw-bold" style="font-size: 0.75rem; letter-spacing: 0.5px;">
                    <tr>
                        <th class="py-3 text-start ps-4" style="width: 15%;">Kolom</th>
                        <th class="py-3 text-start ps-4" style="width: 35%;">Keterangan Jenis Sampul</th>
                        <th class="py-3 text-start ps-4" style="width: 35%;">Keterangan Nama / Keluarga</th>
                        <th class="py-3 text-end pe-4" style="width: 15%;">Nominal</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    if(!empty($tanggal_minggu_list)):
                        $grand_total_all = 0;
                        $tanggal_target = $tanggal_minggu_list[$minggu_pilih - 1];

                        $q_all = mysqli_query($koneksi, "SELECT * FROM keuangan_sampul_massal WHERE tanggal = '$tanggal_target' ORDER BY kolom ASC, id_persepuluhan ASC");
                        
                        if(mysqli_num_rows($q_all) > 0):
                            while($r_all = mysqli_fetch_assoc($q_all)):
                                $grand_total_all += floatval($r_all['nominal']);

                                $kolom_tampil = (!empty($r_all['kolom']) && $r_all['kolom'] > 0) ? 'Kolom ' . $r_all['kolom'] : '-';
                                $keterangan_jemaat = !empty($r_all['keterangan']) ? $r_all['keterangan'] : '-';
                                
                                $style_warna = $warna_map[$r_all['kategori']] ?? ['teks' => '#64748b', 'bg_badge' => '#f1f5f9', 'border' => '#cbd5e1'];
                    ?>
                    <tr class="border-bottom border-light-subtle">
                        <td class="text-start ps-4 py-3 font-monospace text-secondary"><?= $kolom_tampil; ?></td>
                        <td class="text-start ps-4">
                            <span class="badge px-3 py-2 fw-bold rounded-3" style="color: <?= $style_warna['teks']; ?>; background-color: <?= $style_warna['bg_badge']; ?>; border: 1px solid <?= $style_warna['border']; ?>; font-size: 0.82rem; letter-spacing: 0.3px;">
                                <?= $kategori_map[$r_all['kategori']] ?? $r_all['kategori']; ?>
                            </span>
                        </td>
                        <td class="text-start ps-4 text-dark fw-medium small"><?= htmlspecialchars($keterangan_jemaat); ?></td>
                        <td class="text-end pe-4 font-monospace fw-bold text-dark">Rp <?= number_format($r_all['nominal'], 0, ',', '.'); ?></td>
                    </tr>
                    <?php 
                            endwhile;
                        else:
                    ?>
                    <tr>
                        <td colspan="4" class="py-5 text-muted text-center small">
                            <i class="bi bi-envelope-open fs-2 mb-2 d-block text-secondary"></i>
                            Belum ada rekaman setoran persembahan sampul pada Minggu Ke-<?= $angka_romawi[$minggu_pilih]; ?> periode ini.
                        </td>
                    </tr>
                    <?php
                        endif;
                    ?>
                    <tr class="fw-bold bg-light" style="background: #f8fafc;">
                        <td colspan="3" class="text-start ps-4 py-3 text-secondary fw-bold" style="font-size: 0.75rem;">TOTAL PENGUMPULAN PEKANAN (MINGGU KE-<?= $angka_romawi[$minggu_pilih]; ?>)</td>
                        <td class="text-end pe-4 py-3 font-monospace text-purple-premium fs-6" style="color: #6f42c1 !important;">Rp <?= number_format($grand_total_all, 0, ',', '.'); ?></td>
                    </tr>
                    <?php else: ?>
                    <tr><td colspan="4" class="py-5 text-muted">Gagal memetakan penanggalan bulan ini.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>

        <?php else: ?>
            <!-- ======================= INTERFACE TAB KATEGORI SPESIFIK ======================= -->
            <table class="table table-hover align-middle mb-0 text-center text-nowrap" style="min-width: 700px; font-size: 0.9rem;">
                <thead class="bg-light text-secondary text-uppercase fw-bold" style="font-size: 0.75rem; letter-spacing: 0.5px;">
                    <tr>
                        <th class="py-3 text-start ps-4" style="width: 20%;">Tanggal Masuk</th>
                        <th class="py-3" style="width: 15%;">Kolom</th>
                        <th class="py-3 text-start ps-4" style="width: 45%;">Keterangan Pembawa / Detail</th>
                        <th class="py-3 text-end pe-4" style="width: 20%;">Nominal</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $q_spesifik = mysqli_query($koneksi, "SELECT * FROM keuangan_sampul_massal WHERE DATE_FORMAT(tanggal, '%Y-%m') = '$bulan_pilih' AND kategori = '$sub_sampul' ORDER BY tanggal ASC, kolom ASC");
                    
                    if(mysqli_num_rows($q_spesifik) > 0):
                        $grand_total_spesifik = 0;
                        while($r_spes = mysqli_fetch_assoc($q_spesifik)):
                            $grand_total_spesifik += floatval($r_spes['nominal']);

                            $kolom_spes_tampil = (!empty($r_spes['kolom']) && $r_spes['kolom'] > 0) ? 'Kolom ' . $r_spes['kolom'] : '-';
                            $keterangan_tampil = !empty($r_spes['keterangan']) ? $r_spes['keterangan'] : '-';
                    ?>
                    <tr class="border-bottom border-light-subtle">
                        <td class="text-start ps-4 py-3 font-monospace text-secondary" style="font-size: 0.85rem;"><?= date('d-m-Y', strtotime($r_spes['tanggal'])); ?></td>
                        <td class="font-monospace text-secondary"><?= $kolom_spes_tampil; ?></td>
                        <td class="text-start ps-4 text-dark fw-medium"><?= htmlspecialchars($keterangan_tampil); ?></td>
                        <td class="text-end pe-4 font-monospace fw-bold text-dark">Rp <?= number_format($r_spes['nominal'], 0, ',', '.'); ?></td>
                    </tr>
                    <?php endwhile; ?>
                    <tr class="fw-bold bg-light" style="background: #f8fafc;">
                        <td colspan="3" class="text-start ps-4 py-3 text-secondary fw-bold" style="font-size: 0.75rem;">TOTAL PENERIMAAN <?= strtoupper($kategori_map[$sub_sampul]); ?></td>
                        <td class="text-end pe-4 py-3 font-monospace text-purple-premium fs-6" style="color: #6f42c1 !important;">Rp <?= number_format($grand_total_spesifik, 0, ',', '.'); ?></td>
                    </tr>
                    <?php else: ?>
                    <tr>
                        <td colspan="4" class="py-5 text-muted text-center small">
                            <i class="bi bi-envelope-open fs-2 mb-2 d-block text-secondary"></i>
                            Tidak ada data penerimaan <?= $kategori_map[$sub_sampul]; ?> pada bulan ini.
                        </td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        <?php endif; ?>

    </div>
</div>

<style>
.sub-tab-link { color: #64748b; background: transparent; border: 1px solid transparent; transition: 0.2s; padding: 0.45rem 1rem; }
.sub-tab-link:hover { color: #6f42c1; background: rgba(111, 66, 193, 0.05); }
.sub-tab-link.active { background: rgba(111, 66, 193, 0.1) !important; color: #6f42c1 !important; border-color: rgba(111, 66, 193, 0.2) !important; }
</style>

<script>
document.addEventListener("DOMContentLoaded", function() {
    const filterBulanSampul = document.getElementById("filter_bulan_sampul");
    const filterPekanSampul = document.getElementById("filter_pekan_sampul");
    const subTabButtons     = document.querySelectorAll(".sub-tab-link");

    function jalankanReloadRouter(subTarget, mingguNo) {
        const url = new URL(window.location);
        url.searchParams.set("subtab", "sampul");
        url.searchParams.set("sub_sampul", subTarget);
        url.searchParams.set("bulan", filterBulanSampul.value);
        if(mingguNo) {
            url.searchParams.set("minggu_no", mingguNo);
        } else {
            url.searchParams.delete("minggu_no");
        }
        url.searchParams.delete("tab");
        window.location.search = url.search;
    }

    if(filterBulanSampul) {
        filterBulanSampul.addEventListener("change", function() {
            const mNo = filterPekanSampul ? filterPekanSampul.value : 1;
            jalankanReloadRouter("<?= $sub_sampul; ?>", mNo);
        });
    }

    if(filterPekanSampul) {
        filterPekanSampul.addEventListener("change", function() {
            jalankanReloadRouter("all", this.value);
        });
    }

    subTabButtons.forEach(btn => {
        btn.addEventListener("click", function() {
            const subTarget = this.getAttribute("data-sub");
            const mNo = (subTarget === 'all' && filterPekanSampul) ? filterPekanSampul.value : 1;
            jalankanReloadRouter(subTarget, mNo);
        });
    });
});
</script>