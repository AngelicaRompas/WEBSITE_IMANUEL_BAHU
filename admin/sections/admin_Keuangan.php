<?php
// admin_keuangan.php
// File ini dapat langsung dimasukkan atau di-include ke dalam dashboard utama admin Anda

// AMBIL SALDO TERAKHIR DARI DATABASE UNTUK ACUAN PERHITUNGAN
$qSaldo = mysqli_query($koneksi, "SELECT saldo_akhir FROM warta_keuangan ORDER BY tanggal DESC, id DESC LIMIT 1");
$dataSaldo = mysqli_fetch_assoc($qSaldo);
$saldoTerakhir = $dataSaldo['saldo_akhir'] ?? 0;
?>

<link rel="stylesheet" href="assets/css/style-adminKeuangan.css">

<!-- Judul Kontrol Menu -->
<h4 class="section-header-keu mb-4 text-purple-premium">
    <i class="bi bi-cash-coin me-2"></i>Manajemen Laporan Keuangan Kas Jemaat
</h4>

<!-- Baris 1: Ringkasan Saldo Kas -->
<div class="row mb-4">
    <div class="col-md-12">
        <div class="card border-0 text-white p-4 balance-card-premium d-flex flex-row justify-content-between align-items-center">
            <div>
                <small class="text-white-50 text-uppercase fw-bold" style="letter-spacing: 1px;">Saldo Kas Keseluruhan</small>
                <h2 class="mb-0 fw-bolder mt-1">Rp <?php echo number_format($saldoTerakhir, 0, ',', '.'); ?></h2>
            </div>
            <div class="d-none d-sm-block">
                <i class="bi bi-wallet2 display-4 opacity-50"></i>
            </div>
        </div>
    </div>
</div>

<!-- Baris 2: Tabel Pembukuan Buku Kas Umum -->
<div class="card card-custom p-4 border-0 shadow-sm">
    <div class="d-flex justify-content-between align-items-center mb-4 header-wrapper-flex">
        <h5 class="fw-bold mb-0 text-dark d-flex align-items-center" style="font-size: 1.1rem;">
            <i class="bi bi-list-ul me-2 text-purple-premium"></i>Riwayat Pembukuan
        </h5>
        
        <div class="d-flex gap-2">
            <!-- Form Saringan Pencarian per Bulan -->
            <form method="GET" class="d-flex gap-2">
                <input type="hidden" name="tab" value="edit-keuangan">
                <select name="filter_bulan" class="form-select form-select-sm form-select-custom rounded-pill px-3" onchange="this.form.submit()">
                    <?php for($i=1; $i<=12; $i++): $m = str_pad($i, 2, '0', STR_PAD_LEFT); ?>
                        <option value="<?php echo $m; ?>" <?php echo (isset($_GET['filter_bulan']) && $_GET['filter_bulan'] == $m) ? 'selected' : ''; ?>>
                            <?php echo date('F', mktime(0,0,0,$i,1)); ?>
                        </option>
                    <?php endfor; ?>
                </select>
            </form>
            
            <button class="btn btn-sm btn-success rounded-pill px-3 py-2 fw-bold d-inline-flex align-items-center shadow-sm" data-bs-toggle="modal" data-bs-target="#modalKeuangan" onclick="resetModal()">
                <i class="bi bi-plus-lg me-1"></i> Tambah Data
            </button>
        </div>
    </div>

    <div class="table-responsive rounded-3 border">
        <table class="table table-hover align-middle table-custom-keu mb-0 bg-white">
            <thead>
                <tr class="text-nowrap">
                    <th class="ps-3">Tanggal</th>
                    <th>Kategori</th>
                    <th>Keterangan</th>
                    <th class="text-end">Masuk</th>
                    <th class="text-end">Keluar</th>
                    <th class="text-end">Saldo</th>
                    <th class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $bulan = $_GET['filter_bulan'] ?? date('m');
                $sql = mysqli_query($koneksi, "SELECT * FROM warta_keuangan WHERE MONTH(tanggal) = '$bulan' ORDER BY tanggal ASC, id ASC");
                if (mysqli_num_rows($sql) > 0):
                    while($data = mysqli_fetch_assoc($sql)): 
                ?>
                <tr>
                    <td class="ps-3 fw-bold text-secondary text-nowrap"><?php echo date('d M Y', strtotime($data['tanggal'])); ?></td>
                    <td>
                        <span class="badge <?php echo ($data['kategori'] == 'Pengeluaran') ? 'bg-danger-subtle text-danger' : 'bg-success-subtle text-success'; ?> px-2.5 py-1.5 rounded-pill small fw-semibold">
                            <?php echo htmlspecialchars($data['kategori'] ?? 'Tidak Diketahui'); ?>
                        </span>
                    </td>
                    <td class="text-dark"><?php echo htmlspecialchars($data['keterangan']); ?></td>
                    <td class="text-end text-success fw-bold text-nowrap">Rp <?php echo number_format($data['total_pemasukan'],0,',','.'); ?></td>
                    <td class="text-end text-danger fw-bold text-nowrap">Rp <?php echo number_format($data['total_pengeluaran'],0,',','.'); ?></td>
                    <td class="text-end fw-bold text-purple-premium text-nowrap">Rp <?php echo number_format($data['saldo_akhir'],0,',','.'); ?></td>
                    <td class="text-center text-nowrap">
                        <div class="btn-group shadow-sm rounded-3 overflow-hidden border">
                            <button class="btn btn-sm btn-light text-warning edit-keu border-0 px-2.5" data-id="<?php echo $data['id']; ?>" title="Edit Data"><i class="bi bi-pencil-square"></i></button>
                            <a href="proses/proses_keuangan.php?aksi=hapus&id=<?php echo $data['id']; ?>" class="btn btn-sm btn-light text-danger border-0 px-2.5" onclick="return confirm('Yakin ingin menghapus arsip pembukuan kas ini?')" title="Hapus Data"><i class="bi bi-trash"></i></a>
                        </div>
                    </td>
                </tr>
                <?php 
                    endwhile; 
                else:
                ?>
                <tr>
                    <td colspan="7" class="text-center py-4 text-muted">Belum ada data transaksi kas pembukuan pada bulan ini.</td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Modal Dialog: Form Input / Edit Kas Keuangan -->
<div class="modal fade" id="modalKeuangan" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content modal-content-custom border-0 shadow">
            <form action="proses/proses_keuangan.php" method="POST">
                <input type="hidden" name="id" id="edit_id">
                
                <div class="modal-header border-0 pt-4 px-4 pb-2">
                    <h5 class="modal-title fw-bold text-dark"><i class="bi bi-journal-plus me-2 text-purple-premium"></i>Form Laporan Keuangan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                
                <div class="modal-body px-4">
                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="fw-bold small text-secondary mb-1">Tanggal Transaksi</label>
                            <input type="date" name="tanggal" id="edit_tanggal" class="form-control form-control-custom" value="<?php echo date('Y-m-d'); ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label class="fw-bold small text-secondary mb-1">Kategori Arus Kas</label>
                            <select name="kategori" id="edit_kategori" class="form-select form-select-custom" required>
                                <option value="" disabled selected>-- Pilih Kategori --</option>
                                <optgroup label="Pemasukan">
                                    <option value="Umum">Umum (Pemasukan Lainnya)</option>
                                    <option value="Persembahan Ibadah Minggu">Persembahan Ibadah Minggu</option>
                                    <option value="Persembahan Ibadah Kolom / BIPRA">Persembahan Ibadah Kolom / BIPRA</option>
                                    <option value="Sampul Persepuluhan">Sampul Persepuluhan</option>
                                    <option value="Sampul Syukur Hut Pribadi">Sampul Syukur Hut Pribadi</option>
                                    <option value="Sampul Syukur Hut Pernikahan">Sampul Syukur Hut Pernikahan</option>
                                    <option value="Persembahan & Sampul Syukur Lainnya">Persembahan & Sampul Syukur Lainnya</option>
                                    <option value="Persembahan Bulanan Keluarga">Persembahan Bulanan Keluarga</option>
                                </optgroup>
                                <optgroup label="Pengeluaran">
                                    <option value="Pengeluaran">Pengeluaran Kas Keluar</option>
                                </optgroup>
                            </select>
                        </div>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="fw-bold small text-success mb-1">Jumlah Pemasukan (Rp)</label>
                            <!-- FIX: Menghapus atribut name agar nilai string titik tidak mengacaukan POST script database -->
                            <input type="text" id="disp_pemasukan" class="form-control form-control-custom fw-semibold text-success" placeholder="0" oninput="formatRupiahDanHitung()">
                        </div>
                        <div class="col-md-6">
                            <label class="fw-bold small text-danger mb-1">Jumlah Pengeluaran (Rp)</label>
                            <!-- FIX: Menghapus atribut name agar nilai string titik tidak mengacaukan POST script database -->
                            <input type="text" id="disp_pengeluaran" class="form-control form-control-custom fw-semibold text-danger" placeholder="0" oninput="formatRupiahDanHitung()">
                        </div>
                    </div>
                    
                    <div class="mb-2">
                        <label class="fw-bold small text-secondary mb-1">Keterangan / Rincian Pembukuan</label>
                        <textarea name="keterangan" id="edit_keterangan" class="form-control form-control-custom" rows="3" placeholder="Tulis rincian atau asal usul alokasi kas..." required></textarea>
                    </div>
                    
                    <!-- INPUT TERSEMBUNYI (PENTING UNTUK KIRIM ANGKA BERSIH KE DATABASE) -->
                    <input type="hidden" name="total_pemasukan" id="real_pemasukan" value="0">
                    <input type="hidden" name="total_pengeluaran" id="real_pengeluaran" value="0">
                </div>
                
                <div class="modal-footer border-0 bg-light rounded-bottom-4 px-4 py-3 mt-4">
                    <button type="button" class="btn btn-outline-secondary rounded-pill px-4 btn-sm" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" name="simpan_keuangan" class="btn btn-purple-admin rounded-pill px-4 btn-sm fw-bold shadow-sm">Simpan Data</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Logika Ajax Ambil Data untuk Edit Kas
document.querySelectorAll('.edit-keu').forEach(btn => {
    btn.addEventListener('click', function() {
        let id = this.getAttribute('data-id');
        fetch('proses/proses_keuangan.php?aksi=ambil_data&id=' + id)
        .then(res => res.json())
        .then(data => {
            document.getElementById('edit_id').value = data.id;
            let tgl = (data.tanggal && data.tanggal !== "0000-00-00") ? data.tanggal : '<?php echo date('Y-m-d'); ?>';
            document.getElementById('edit_tanggal').value = tgl;
            
            if(data.kategori) {
                document.getElementById('edit_kategori').value = data.kategori;
            } else {
                document.getElementById('edit_kategori').value = '';
            }

            // Set Nilai Display Terformat Titik Ribuan
            document.getElementById('disp_pemasukan').value = data.total_pemasukan.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
            document.getElementById('disp_pengeluaran').value = data.total_pengeluaran.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
            
            // FIX: Sinkronisasi nilai angka murni ke input hidden saat data dimuat agar tidak bernilai 0/kosong jika langsung disimpan
            document.getElementById('real_pemasukan').value = data.total_pemasukan;
            document.getElementById('real_pengeluaran').value = data.total_pengeluaran;
            
            document.getElementById('edit_keterangan').value = data.keterangan;
            
            new bootstrap.Modal(document.getElementById('modalKeuangan')).show();
        });
    });
});

// Fungsi Reset Form Modal saat Klik Tambah Data Baru
function resetModal() {
    document.getElementById('edit_id').value = '';
    document.getElementById('edit_kategori').value = '';
    document.getElementById('disp_pemasukan').value = '';
    document.getElementById('disp_pengeluaran').value = '';
    document.getElementById('real_pemasukan').value = '0';
    document.getElementById('real_pengeluaran').value = '0';
    document.getElementById('edit_keterangan').value = '';
    document.getElementById('edit_tanggal').value = '<?php echo date('Y-m-d'); ?>';
}

// Fungsi Otomatis Format Masking Rupiah & Ekstrak Angka Murni
function formatRupiahDanHitung() {
    let inpMasuk = document.getElementById('disp_pemasukan');
    let inpKeluar = document.getElementById('disp_pengeluaran');
    
    let rawMasuk = inpMasuk.value.replace(/\D/g, '');
    let rawKeluar = inpKeluar.value.replace(/\D/g, '');
    
    inpMasuk.value = rawMasuk.replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    inpKeluar.value = rawKeluar.replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    
    document.getElementById('real_pemasukan').value = rawMasuk ? rawMasuk : '0';
    document.getElementById('real_pengeluaran').value = rawKeluar ? rawKeluar : '0';
}
</script>