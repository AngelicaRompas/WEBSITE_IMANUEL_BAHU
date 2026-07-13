<?php
// keuangan_rekapan.php - Logika Kompilasi Buku Kas Rekapan Publik Otomatis
if (session_status() === PHP_SESSION_NONE) { session_start(); }
include 'koneksi.php';

$bulan_pilih = isset($_GET['bulan']) ? mysqli_real_escape_string($koneksi, $_GET['bulan']) : date('Y-m');

// Pecah tahun dan bulan untuk algoritma perulangan hari kalender
list($tahun, $bulan) = explode('-', $bulan_pilih);
$jumlah_hari = cal_days_in_month(CAL_GREGORIAN, $bulan, $tahun);

$buku_kas_rekapan = [];

// Array nama bulan untuk konversi bulan_target digital
$nama_bulan_target_indo = [
    1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
    5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
    9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
];

// Label deskripsi dasar komisi kategorial jemaat
$label_bipra_base = [
    'PKB'    => 'Penyetoran BIPRA: Pria / Kaum Bapa (PKB)',
    'WKI'    => 'Penyetoran BIPRA: Wanita / Kaum Ibu (WKI)',
    'PEMUDA' => 'Penyetoran BIPRA: Pelayanan Pemuda',
    'REMAJA' => 'Penyetoran BIPRA: Pelayanan Remaja',
    'ASM'    => 'Penyetoran BIPRA: Anak Sekolah Minggu (ASM)',
    'LANSIA' => 'Penyetoran BIPRA: Jemaat Lansia',
    'KPDP'   => 'Penyetoran BIPRA: Kemitraan Pria & Diaken (KPDP)'
];

// Loop menelusuri hari demi hari dari tanggal 1 sampai akhir bulan pilihan
for ($hari = 1; $hari <= $jumlah_hari; $hari++) {
    $tanggal_loop = sprintf('%04d-%02d-%02d', $tahun, $bulan, $hari);

    // 1. Ambil & Jumlahkan Total Ibadah Minggu harian
    $q_minggu = mysqli_query($koneksi, "SELECT SUM(jumlah_sesi) as total FROM keuangan_ibadah_minggu WHERE tanggal = '$tanggal_loop'");
    $r_minggu = mysqli_fetch_assoc($q_minggu);
    if ($r_minggu['total'] > 0) {
        $buku_kas_rekapan[] = ['tanggal' => $tanggal_loop, 'keterangan' => 'Persembahan Ibadah Minggu', 'tipe' => 'pemasukan', 'nominal' => floatval($r_minggu['total'])];
    }

    // 2. Ambil & Jumlahkan Total Penyetoran Kolom harian
    $q_kolom = mysqli_query($koneksi, "SELECT SUM(jumlah) as total FROM keuangan_ibadah_kolom WHERE tanggal = '$tanggal_loop'");
    $r_kolom = mysqli_fetch_assoc($q_kolom);
    if ($r_kolom['total'] > 0) {
        $buku_kas_rekapan[] = ['tanggal' => $tanggal_loop, 'keterangan' => 'Penyetoran Kolom', 'tipe' => 'pemasukan', 'nominal' => floatval($r_kolom['total'])];
    }

    // 3. Ambil Data Penyetoran BIPRA Harian dengan Tambahan Keterangan Bulan Target
    $q_bipra = mysqli_query($koneksi, "SELECT komisi, nominal, bulan_target FROM keuangan_bipra WHERE tanggal = '$tanggal_loop'");
    while ($r_bipra = mysqli_fetch_assoc($q_bipra)) {
        if ($r_bipra['nominal'] > 0) {
            $base_text = $label_bipra_base[$r_bipra['komisi']] ?? 'Penyetoran BIPRA: ' . $r_bipra['komisi'];
            $bulan_text = $nama_bulan_target_indo[intval($r_bipra['bulan_target'])] ?? '';
            
            // Menggabungkan nama komisi dengan keterangan bulan target
            $label_lengkap = $base_text . ' (Bulan ' . $bulan_text . ')';
            
            $buku_kas_rekapan[] = [
                'type' => 'pemasukan',
                'tanggal' => $tanggal_loop,
                'keterangan' => $label_lengkap,
                'tipe' => 'pemasukan',
                'nominal' => floatval($r_bipra['nominal'])
            ];
        }
    }

    // 4. Ambil & Klasifikasikan Total Sampul Massal harian
    $kategori_sampul_list = [
        'persepuluhan' => 'Pemasukkan Sampul Persepuluhan',
        'hut_pribadi' => 'Pemasukkan Sampul HUT Pribadi',
        'pernikahan' => 'Pemasukkan Sampul Syukur Pernikahan',
        'lainnya' => 'Pemasukkan Persembahan Sampul Syukur Lainnya',
        'bulanan_keluarga' => 'Pemasukkan Persembahan Bulanan Keluarga'
    ];
    foreach ($kategori_sampul_list as $kat_db => $label_keterangan) {
        $q_sampul = mysqli_query($koneksi, "SELECT SUM(nominal) as total FROM keuangan_sampul_massal WHERE tanggal = '$tanggal_loop' AND kategori = '$kat_db'");
        $r_sampul = mysqli_fetch_assoc($q_sampul);
        if ($r_sampul['total'] > 0) {
            $buku_kas_rekapan[] = ['tanggal' => $tanggal_loop, 'keterangan' => $label_keterangan, 'tipe' => 'pemasukan', 'nominal' => floatval($r_sampul['total'])];
        }
    }

    // 5. Ambil Ibadah Khusus harian
    $q_khusus = mysqli_query($koneksi, "SELECT kegiatan, nominal FROM keuangan_ibadah_khusus WHERE tanggal = '$tanggal_loop'");
    while ($r_khusus = mysqli_fetch_assoc($q_khusus)) {
        if ($r_khusus['nominal'] > 0) {
            $buku_kas_rekapan[] = ['tanggal' => $tanggal_loop, 'keterangan' => 'Persembahan ' . $r_khusus['kegiatan'], 'tipe' => 'pemasukan', 'nominal' => floatval($r_khusus['nominal'])];
        }
    }

    // 6. Ambil Detail Pengeluaran harian
    $q_pengeluaran = mysqli_query($koneksi, "SELECT keterangan, nominal FROM keuangan_pengeluaran WHERE tanggal = '$tanggal_loop'");
    while ($r_pengeluaran = mysqli_fetch_assoc($q_pengeluaran)) {
        if ($r_pengeluaran['nominal'] > 0) {
            $buku_kas_rekapan[] = ['tanggal' => $tanggal_loop, 'keterangan' => 'Pengeluaran: ' . $r_pengeluaran['keterangan'], 'tipe' => 'pengeluaran', 'nominal' => floatval($r_pengeluaran['nominal'])];
        }
    }
}
?>

<!-- Kartu Konten Rekapan Publik -->
<div class="card border-0 shadow-sm rounded-4 p-4 bg-white">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 border-bottom pb-3 mb-4">
        <div>
            <h5 class="fw-bold text-dark mb-1">Buku Kas Rekapan Bulanan</h5>
            <small class="text-muted">Ikhtisar kronologis pergerakan kas masuk dan kas keluar jemaat</small>
        </div>
        
        <div class="d-flex align-items-center gap-2">
            <label for="filter_bulan_publik" class="form-label mb-0 small fw-bold text-secondary text-nowrap">Pilih Periode:</label>
            <input type="month" id="filter_bulan_publik" class="form-control font-monospace fw-bold text-dark bg-light" value="<?= $bulan_pilih; ?>" style="max-width: 180px;">
        </div>
    </div>

    <div class="table-responsive rounded-3 border border-light-subtle">
        <table class="table table-hover align-middle mb-0 text-center" style="min-width: 900px;">
            <thead class="bg-light text-secondary text-uppercase fw-bold" style="font-size: 0.75rem; letter-spacing: 0.5px;">
                <tr>
                    <th class="py-3" style="width: 5%;">No</th>
                    <th class="py-3" style="width: 12%;">Tanggal</th>
                    <th class="py-3 text-start ps-4" style="width: 43%;">Keterangan Transaksi</th>
                    <th class="py-3 text-end" style="width: 20%;">Nominal</th>
                    <th class="py-3 text-end pe-4" style="width: 20%;">Saldo Kas</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                if (!empty($buku_kas_rekapan)):
                    $no = 1;
                    $saldo_berjalan = 0;
                    $grand_pemasukan = 0;
                    $grand_pengeluaran = 0;

                    foreach ($buku_kas_rekapan as $kas):
                        $nom = $kas['nominal'];
                        
                        if ($kas['tipe'] === 'pemasukan') {
                            $saldo_berjalan += $nom;
                            $grand_pemasukan += $nom;
                            $warna_nominal = "text-success";
                            $tanda_baca = "+";
                        } else {
                            $saldo_berjalan -= $nom;
                            $grand_pengeluaran += $nom;
                            $warna_nominal = "text-danger";
                            $tanda_baca = "-";
                        }
                ?>
                <tr class="border-bottom border-light-subtle">
                    <td class="text-muted small"><?= $no++; ?></td>
                    <td class="font-monospace text-secondary small"><?= date('d-m-Y', strtotime($kas['tanggal'])); ?></td>
                    <td class="text-start ps-4 text-dark fw-medium" style="font-size: 0.9rem;"><?= $kas['keterangan']; ?></td>
                    <td class="text-end font-monospace fw-bold <?= $warna_nominal; ?>">
                        <?= $tanda_baca; ?> Rp <?= number_format($nom, 0, ',', '.'); ?>
                    </td>
                    <td class="text-end pe-4 font-monospace fw-bold text-dark" style="font-size: 0.95rem;">
                        Rp <?= number_format($saldo_berjalan, 0, ',', '.'); ?>
                    </td>
                </tr>
                <?php endforeach; ?>
                
                <tr class="fw-bold border-0 bg-light" style="background: #f8fafc;">
                    <td colspan="3" class="text-start ps-4 py-3 text-secondary fw-bolder" style="font-size: 0.8rem;">TOTAL KESELURUHAN BULANAN</td>
                    <td class="text-end font-monospace text-dark py-3" style="font-size: 0.85rem;">
                        <span class="text-success">+Rp <?= number_format($grand_pemasukan, 0, ',', '.'); ?></span><br>
                        <span class="text-danger">-Rp <?= number_format($grand_pengeluaran, 0, ',', '.'); ?></span>
                    </td>
                    <td class="text-end pe-4 py-3 font-monospace text-purple-premium fs-5 text-end" style="vertical-align: middle;">
                        Rp <?= number_format($saldo_berjalan, 0, ',', '.'); ?>
                    </td>
                </tr>
                <?php else: ?>
                <tr>
                    <td colspan="5" class="py-5 text-muted text-center small">
                        <i class="bi bi-folder-x fs-2 mb-2 d-block text-secondary"></i>
                        Tidak ditemukan adanya rekaman riwayat transaksi kas jemaat pada periode bulan ini.
                    </td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function() {
    const filterBulan = document.getElementById("filter_bulan_publik");
    if (filterBulan) {
        filterBulan.addEventListener("change", function() {
            const url = new URL(window.location);
            url.searchParams.set("subtab", "rekapan");
            url.searchParams.set("bulan", this.value);
            url.searchParams.delete("tab");
            window.location.search = url.search;
        });
    }
});
</script>