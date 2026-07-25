<?php
if (session_status() === PHP_SESSION_NONE) session_start();
include '../koneksi.php';
$tanggal_pilih = isset($_GET['tgl_keuangan']) ? mysqli_real_escape_string($koneksi, $_GET['tgl_keuangan']) : date('Y-m-d');

function renderFormSampul($koneksi, $tanggal_pilih, $kategori_db, $judul_form, $icon) {
    $data_existing = [];
    $cek_data = mysqli_query($koneksi, "SELECT * FROM keuangan_sampul_massal WHERE tanggal = '$tanggal_pilih' AND kategori = '$kategori_db' ORDER BY id_persepuluhan ASC");
    if ($cek_data) {
        while ($row = mysqli_fetch_assoc($cek_data)) { $data_existing[] = $row; }
    }
    ?>
    <form action="proses/proses_keuangan_sampul.php" method="POST" class="mb-5">
        <input type="hidden" name="tab" value="admin-keuangan">
        <input type="hidden" name="subtab" value="sampul">
        <input type="hidden" name="tanggal" value="<?php echo $tanggal_pilih; ?>">
        <input type="hidden" name="kategori_target" value="<?php echo $kategori_db; ?>">

        <div class="card border-0 shadow-sm rounded-4 overflow-hidden bg-white border-light-subtle">
            <div class="p-3 d-flex align-items-center text-white gap-2" style="background: linear-gradient(135deg, #371266, #1f043a);">
                <div class="p-2 bg-white bg-opacity-10 rounded-3 text-warning"><i class="bi <?php echo $icon; ?> fs-5"></i></div>
                <h6 class="fw-bold mb-0" style="letter-spacing: 0.5px;"><?php echo $judul_form; ?></h6>
            </div>
            <div class="table-responsive">
                <table class="table align-middle mb-0 text-center table-sampul-induk" data-kategori="<?php echo $kategori_db; ?>">
                    <thead class="bg-light text-secondary text-uppercase fw-bold" style="font-size: 0.70rem; letter-spacing: 0.8px;">
                        <tr>
                            <th style="width: 120px;">Kolom</th>
                            <th class="text-start">Keterangan Nama / Kel</th>
                            <th style="width: 200px;">Nominal</th>
                            <th style="width: 60px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="wrapper-baris-sampul">
                        <?php 
                        $rows = !empty($data_existing) ? $data_existing : [null];
                        foreach ($rows as $data):
                        ?>
                        <tr class="sampul-row">
                            <!-- Input Kolom diberi class text-center -->
                            <td class="p-2"><input type="number" name="kolom[]" class="form-control input-digital-opsi text-center" value="<?php echo $data['kolom'] ?? ''; ?>"></td>
                            <td class="p-2"><input type="text" name="keterangan[]" class="form-control input-digital-opsi" value="<?php echo $data['keterangan'] ?? ''; ?>" required placeholder="Masukkan keterangan..."></td>
                            <td class="p-2"><input type="number" name="nominal[]" class="form-control input-digital-opsi nominal-input input-hitung-sampul" value="<?php echo $data['nominal'] ?? 0; ?>" min="0" required></td>
                            <td class="p-2"><button type="button" class="btn btn-sm btn-light-danger rounded-3 hapus-baris-sampul-btn"><i class="bi bi-trash3-fill"></i></button></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                    <tfoot class="bg-light">
                        <tr>
                            <td colspan="2" class="text-end pe-4 fw-bold text-secondary" style="font-size: 0.75rem;">TOTAL <?php echo strtoupper($judul_form); ?></td>
                            <td class="text-end pe-3 fw-bold font-monospace text-purple-premium output-grand-sampul">Rp 0</td>
                            <td></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
            <div class="p-3 d-flex justify-content-between border-top">
                <button type="button" class="btn btn-outline-purple btn-sm py-2 px-3 rounded-3 fw-bold tambah-baris-sampul-btn" data-kategori="<?php echo $kategori_db; ?>"><i class="bi bi-plus-circle-fill me-1"></i> Tambah Baris</button>
                <button type="submit" name="simpan_sampul_single" class="btn btn-purple-digital btn-sm py-2 px-4 rounded-3 fw-bold border-0"><i class="bi bi-check-circle-fill me-1"></i> Simpan Laporan</button>
            </div>
        </div>
    </form>
    <?php
}
?>

<div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4 bg-white">
    <div class="p-3 d-flex align-items-center justify-content-between text-white" style="background: linear-gradient(135deg, #4b1a8a, #2e0854);">
        <h5 class="fw-bold mb-0"><i class="bi bi-envelope-paper-heart-fill me-2 text-warning"></i>Manajemen Kolektif Sampul Jemaat</h5>
        <input type="date" id="tanggal_sampul_master_input" class="form-control w-auto bg-dark bg-opacity-20 border-0 text-white text-center fw-bold small" value="<?php echo $tanggal_pilih; ?>">
    </div>
</div>

<?php 
renderFormSampul($koneksi, $tanggal_pilih, 'persepuluhan', '1. Sampul Persepuluhan', 'bi-envelope-fill'); 
renderFormSampul($koneksi, $tanggal_pilih, 'hut_pribadi', '2. Sampul Syukur HUT Pribadi', 'bi-cake2-fill'); 
renderFormSampul($koneksi, $tanggal_pilih, 'pernikahan', '3. Sampul Syukur Pernikahan', 'bi-heart-fill'); 
renderFormSampul($koneksi, $tanggal_pilih, 'lainnya', '4. Persembahan & Sampul Syukur Lainnya', 'bi-gift-fill'); 
renderFormSampul($koneksi, $tanggal_pilih, 'bulanan_keluarga', '5. Persembahan Bulanan Keluarga', 'bi-people-fill'); 
?>

<style>
.input-digital-opsi { border: 1px solid #cbd5e1 !important; background: #fff !important; font-size: 0.85rem; padding: 0.5rem !important; border-radius: 6px; width: 100% !important; box-sizing: border-box; transition: 0.2s; }
.input-digital-opsi:focus { border-color: #4b1a8a !important; outline: none; box-shadow: 0 0 0 3px rgba(75, 26, 138, 0.1); }
.nominal-input { font-family: var(--bs-font-monospace); text-align: right; }
.btn-outline-purple { color: #4b1a8a; border: 2px solid #4b1a8a; }
.btn-outline-purple:hover { background: #4b1a8a; color: #fff; }
.btn-purple-digital { background: #6f42c1; color: #fff; }
.btn-light-danger { background: #fee2e2; color: #ef4444; border: none; }
.btn-light-danger:hover { background: #ef4444; color: #fff; }
</style>

<script>
document.addEventListener("DOMContentLoaded", function () {
    function kalkulasiKolektifSampul() {
        document.querySelectorAll(".table-sampul-induk").forEach(tabel => {
            let totalSub = 0;
            tabel.querySelectorAll(".nominal-input").forEach(input => { totalSub += parseFloat(input.value) || 0; });
            tabel.querySelector(".output-grand-sampul").innerText = "Rp " + totalSub.toLocaleString('id-ID');
        });
    }
    kalkulasiKolektifSampul();
    document.addEventListener("input", e => { if(e.target.classList.contains("nominal-input")) kalkulasiKolektifSampul(); });
    document.addEventListener("click", e => {
        if(e.target.closest(".tambah-baris-sampul-btn")) {
            const tbody = e.target.closest(".card").querySelector(".wrapper-baris-sampul");
            // Menambahkan text-center pada kolom input yang baru
            tbody.insertAdjacentHTML('beforeend', '<tr class="sampul-row"><td class="p-2"><input type="number" name="kolom[]" class="form-control input-digital-opsi text-center"></td><td class="p-2"><input type="text" name="keterangan[]" class="form-control input-digital-opsi" required placeholder="Nama Lengkap / Keluarga"></td><td class="p-2"><input type="number" name="nominal[]" class="form-control input-digital-opsi nominal-input input-hitung-sampul" value="0" required></td><td class="p-2"><button type="button" class="btn btn-sm btn-light-danger rounded-3 hapus-baris-sampul-btn"><i class="bi bi-trash3-fill"></i></button></td></tr>');
        }
        if(e.target.closest(".hapus-baris-sampul-btn")) {
            if(document.querySelectorAll(".sampul-row").length > 1) { e.target.closest(".sampul-row").remove(); kalkulasiKolektifSampul(); }
            else alert("Minimal harus ada 1 baris.");
        }
    });
    document.getElementById("tanggal_sampul_master_input").addEventListener("change", function() {
        const url = new URL(window.location);
        url.searchParams.set("tab", "admin-keuangan");
        url.searchParams.set("subtab", "sampul");
        url.searchParams.set("tgl_keuangan", this.value);
        window.location.search = url.search;
    });
});
</script>