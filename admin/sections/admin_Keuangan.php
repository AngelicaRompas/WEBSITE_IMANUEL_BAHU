<?php
// AMBIL SALDO TERAKHIR DARI DATABASE UNTUK ACUAN PERHITUNGAN
$qSaldo = mysqli_query($koneksi, "SELECT saldo_akhir FROM warta_keuangan ORDER BY tanggal DESC, id DESC LIMIT 1");
$dataSaldo = mysqli_fetch_assoc($qSaldo);
$saldoTerakhir = $dataSaldo['saldo_akhir'] ?? 0;
?>

<h4 class="section-header mb-4 text-primary fw-bold">
    <i class="bi bi-cash-coin me-2"></i>Manajemen Laporan Keuangan Kas Jemaat
</h4>

<div class="row mb-4">
    <div class="col-md-12">
        <div class="card border-0 shadow-sm text-white p-4 rounded-4 d-flex flex-row justify-content-between align-items-center" 
             style="background: linear-gradient(45deg, #0d6efd, #0dcaf0);">
            <div>
                <small class="text-white-50 text-uppercase fw-bold">Saldo Kas Keseluruhan</small>
                <h2 class="mb-0 fw-bolder">Rp <?php echo number_format($saldoTerakhir, 0, ',', '.'); ?></h2>
            </div>
            <i class="bi bi-wallet2 display-4 opacity-50"></i>
        </div>
    </div>
</div>

<div class="card border-0 shadow-sm rounded-4 p-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h5 class="fw-bold mb-0 text-dark"><i class="bi bi-list-ul me-2 text-primary"></i>Riwayat Pembukuan</h5>
        <div class="d-flex gap-2">
            <form method="GET" class="d-flex gap-2">
                <input type="hidden" name="tab" value="edit-keuangan">
                <select name="filter_bulan" class="form-select form-select-sm rounded-pill px-3" onchange="this.form.submit()">
                    <?php for($i=1; $i<=12; $i++): $m = str_pad($i, 2, '0', STR_PAD_LEFT); ?>
                        <option value="<?php echo $m; ?>" <?php echo (isset($_GET['filter_bulan']) && $_GET['filter_bulan'] == $m) ? 'selected' : ''; ?>>
                            <?php echo date('F', mktime(0,0,0,$i,1)); ?>
                        </option>
                    <?php endfor; ?>
                </select>
            </form>
            <button class="btn btn-sm btn-success rounded-pill px-3 shadow-sm" data-bs-toggle="modal" data-bs-target="#modalKeuangan" onclick="resetModal()">
                <i class="bi bi-plus-lg me-1"></i> Tambah Data
            </button>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="bg-light">
                <tr>
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
                // PENTING: Order by tanggal ASC dan id ASC agar saldo terhitung kronologis
                $sql = mysqli_query($koneksi, "SELECT * FROM warta_keuangan WHERE MONTH(tanggal) = '$bulan' ORDER BY tanggal ASC, id ASC");
                while($data = mysqli_fetch_assoc($sql)): 
                ?>
                <tr class="border-bottom">
                    <td class="ps-3 fw-bold text-muted"><?php echo date('d M Y', strtotime($data['tanggal'])); ?></td>
                    <td>
                        <span class="badge <?php echo ($data['kategori'] == 'Pengeluaran') ? 'bg-danger-subtle text-danger' : 'bg-success-subtle text-success'; ?> px-2 py-1 rounded-pill">
                            <?php echo htmlspecialchars($data['kategori'] ?? 'Tidak Diketahui'); ?>
                        </span>
                    </td>
                    <td><?php echo htmlspecialchars($data['keterangan']); ?></td>
                    <td class="text-end text-success fw-bold">Rp <?php echo number_format($data['total_pemasukan'],0,',','.'); ?></td>
                    <td class="text-end text-danger fw-bold">Rp <?php echo number_format($data['total_pengeluaran'],0,',','.'); ?></td>
                    <td class="text-end fw-bold text-primary">Rp <?php echo number_format($data['saldo_akhir'],0,',','.'); ?></td>
                    <td class="text-center">
                        <div class="btn-group">
                            <button class="btn btn-sm btn-light text-warning edit-keu border-0" data-id="<?php echo $data['id']; ?>"><i class="bi bi-pencil-square"></i></button>
                            <a href="proses/proses_keuangan.php?aksi=hapus&id=<?php echo $data['id']; ?>" class="btn btn-sm btn-light text-danger border-0" onclick="return confirm('Yakin hapus data ini?')"><i class="bi bi-trash"></i></a>
                        </div>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

<div class="modal fade" id="modalKeuangan" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content rounded-4 border-0 shadow">
            <form action="proses/proses_keuangan.php" method="POST">
                <input type="hidden" name="id" id="edit_id">
                <div class="modal-header border-0 p-4">
                    <h5 class="modal-title fw-bold">Form Laporan Keuangan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    
                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="fw-bold small">Tanggal Transaksi</label>
                            <input type="date" name="tanggal" id="edit_tanggal" class="form-control rounded-3" value="<?php echo date('Y-m-d'); ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label class="fw-bold small">Kategori Arus Kas</label>
                            <select name="kategori" id="edit_kategori" class="form-select rounded-3" required>
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
                                    <option value="Pengeluaran">Pengeluaran</option>
                                </optgroup>
                            </select>
                        </div>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="fw-bold small text-success">Jumlah Pemasukan (Rp)</label>
                            <input type="text" name="total_pemasukan" id="disp_pemasukan" class="form-control rounded-3" oninput="formatRupiahDanHitung()">
                        </div>
                        <div class="col-md-6">
                            <label class="fw-bold small text-danger">Jumlah Pengeluaran (Rp)</label>
                            <input type="text" name="total_pengeluaran" id="disp_pengeluaran" class="form-control rounded-3" oninput="formatRupiahDanHitung()">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="fw-bold small">Keterangan / Rincian</label>
                        <textarea name="keterangan" id="edit_keterangan" class="form-control rounded-3" rows="3" required></textarea>
                    </div>
                    
                    <input type="hidden" name="total_pemasukan" id="real_pemasukan">
                    <input type="hidden" name="total_pengeluaran" id="real_pengeluaran">
                </div>
                <div class="modal-footer border-0 p-4">
                    <button type="submit" name="simpan_keuangan" class="btn btn-primary px-4 rounded-pill">Simpan Data</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Fungsi Edit
document.querySelectorAll('.edit-keu').forEach(btn => {
    btn.addEventListener('click', function() {
        let id = this.getAttribute('data-id');
        fetch('proses/proses_keuangan.php?aksi=ambil_data&id=' + id)
        .then(res => res.json())
        .then(data => {
            document.getElementById('edit_id').value = data.id;
            let tgl = (data.tanggal && data.tanggal !== "0000-00-00") ? data.tanggal : '<?php echo date('Y-m-d'); ?>';
            document.getElementById('edit_tanggal').value = tgl;
            
            // Set Kategori pada dropdown
            if(data.kategori) {
                document.getElementById('edit_kategori').value = data.kategori;
            } else {
                document.getElementById('edit_kategori').value = '';
            }

            document.getElementById('disp_pemasukan').value = data.total_pemasukan.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
            document.getElementById('disp_pengeluaran').value = data.total_pengeluaran.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
            document.getElementById('edit_keterangan').value = data.keterangan;
            
            new bootstrap.Modal(document.getElementById('modalKeuangan')).show();
        });
    });
});

function resetModal() {
    document.getElementById('edit_id').value = '';
    document.getElementById('edit_kategori').value = '';
    document.getElementById('disp_pemasukan').value = '';
    document.getElementById('disp_pengeluaran').value = '';
    document.getElementById('edit_keterangan').value = '';
    document.getElementById('edit_tanggal').value = '<?php echo date('Y-m-d'); ?>';
}

function formatRupiahDanHitung() {
    let inpMasuk = document.getElementById('disp_pemasukan');
    let inpKeluar = document.getElementById('disp_pengeluaran');
    
    let rawMasuk = inpMasuk.value.replace(/\D/g, '');
    let rawKeluar = inpKeluar.value.replace(/\D/g, '');
    
    inpMasuk.value = rawMasuk.replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    inpKeluar.value = rawKeluar.replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    
    document.getElementById('real_pemasukan').value = rawMasuk;
    document.getElementById('real_pengeluaran').value = rawKeluar;
}
</script>