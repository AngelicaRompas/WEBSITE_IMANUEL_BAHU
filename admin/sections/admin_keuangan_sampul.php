<?php
// admin_keuangan_sampul.php - Manajemen Sampul Kolektif Per Form Tabel
$tanggal_pilih = isset($_GET['tgl_keuangan']) ? mysqli_real_escape_string($koneksi, $_GET['tgl_keuangan']) : date('Y-m-d');

// Fungsi Pintar Render Tabel Dinamis Berjemaat
function renderFormSampul($koneksi, $tanggal_pilih, $kategori_db, $judul_form, $icon) {
    $data_existing = [];
    $cek_data = mysqli_query($koneksi, "SELECT * FROM keuangan_sampul_massal WHERE tanggal = '$tanggal_pilih' AND kategori = '$kategori_db' ORDER BY id_persepuluhan ASC");
    if ($cek_data) {
        while ($row = mysqli_fetch_assoc($cek_data)) {
            $data_existing[] = $row;
        }
    }
    ?>
    <!-- Pembungkus Form Mandiri Per Tabel Kategori -->
    <form action="proses/proses_keuangan_sampul.php" method="POST" class="mb-5">
        <input type="hidden" name="tanggal" value="<?php echo $tanggal_pilih; ?>">
        <input type="hidden" name="kategori_target" value="<?php echo $kategori_db; ?>">

        <div class="card border-0 shadow-sm rounded-4 overflow-hidden bg-white border-light-subtle">
            <!-- Header Sub-Form -->
            <div class="p-3.5 d-flex flex-column flex-md-row align-items-start align-items-md-center justify-content-between text-white gap-2" style="background: linear-gradient(135deg, #371266, #1f043a);">
                <div class="d-flex align-items-center gap-2">
                    <div class="p-2 bg-white bg-opacity-10 rounded-3 text-warning"><i class="bi <?php echo $icon; ?> fs-5"></i></div>
                    <div>
                        <h6 class="fw-bold mb-0" style="letter-spacing: 0.5px;"><?php echo $judul_form; ?></h6>
                    </div>
                </div>
            </div>

            <!-- Tabel Data Dinamis -->
            <div class="p-3">
                <div class="table-responsive rounded-3 border border-light-subtle">
                    <table class="table table-hover align-middle mb-0 text-center custom-digital-table table-sampul-induk" data-kategori="<?php echo $kategori_db; ?>" style="min-width: 650px;">
                        <thead class="bg-light text-secondary text-uppercase fw-bold" style="font-size: 0.75rem; letter-spacing: 0.8px;">
                            <tr>
                                <th style="width: 15%;">Kolom</th>
                                <th class="text-start ps-3" style="width: 50%;">Keterangan Nama / Kel</th>
                                <th class="text-end pe-4" style="width: 25%;">Nominal</th>
                                <th style="width: 10%;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="wrapper-baris-sampul">
                            <?php 
                            if (!empty($data_existing)):
                                foreach ($data_existing as $data):
                            ?>
                            <tr class="sampul-row border-bottom border-light-subtle">
                                <td class="px-2"><input type="number" name="kolom[]" class="form-control text-center input-digital-opsi text-purple-premium" value="<?php echo !empty($data['kolom']) ? $data['kolom'] : ''; ?>"></td>
                                <td class="px-2"><input type="text" name="keterangan[]" class="form-control text-start input-digital-opsi text-input-box" value="<?php echo $data['keterangan']; ?>" required></td>
                                <td class="px-2"><input type="number" name="nominal[]" class="form-control text-end input-digital-opsi nominal-input input-hitung-sampul" value="<?php echo $data['nominal']; ?>" min="0" required></td>
                                <td><button type="button" class="btn btn-sm btn-light-danger rounded-3 hapus-baris-sampul-btn"><i class="bi bi-trash3-fill"></i></button></td>
                            </tr>
                            <?php 
                                endforeach;
                            else:
                            ?>
                            <tr class="sampul-row border-bottom border-light-subtle">
                                <td class="px-2"><input type="number" name="kolom[]" class="form-control text-center input-digital-opsi text-purple-premium"></td>
                                <td class="px-2"><input type="text" name="keterangan[]" class="form-control text-start input-digital-opsi text-input-box" required placeholder="Nama Lengkap / Keluarga"></td>
                                <td class="px-2"><input type="number" name="nominal[]" class="form-control text-end input-digital-opsi nominal-input input-hitung-sampul" value="0" min="0" required></td>
                                <td><button type="button" class="btn btn-sm btn-light-danger rounded-3 hapus-baris-sampul-btn"><i class="bi bi-trash3-fill"></i></button></td>
                            </tr>
                            <?php endif; ?>
                        </tbody>
                        <tfoot>
                            <tr class="fw-bold border-0 bg-light-subtle" style="background: #f8fafc;">
                                <td colspan="2" class="text-start ps-4 py-2.5 text-secondary fw-bold" style="font-size: 0.75rem;">TOTAL <?php echo strtoupper($judul_form); ?></td>
                                <td class="text-end pe-4 py-2.5 font-monospace text-purple-premium output-grand-sampul" style="font-size: 0.95rem;">Rp 0</td>
                                <td></td>
                        </tr>
                        </tfoot>
                    </table>
                </div>
                
                <!-- Aksi Tambah Baris dan Tombol Simpan Mandiri Per Tabel -->
                <div class="mt-3 d-flex flex-column flex-sm-row justify-content-between align-items-stretch align-items-sm-center gap-2">
                    <button type="button" class="btn btn-outline-purple btn-sm py-2 px-3 rounded-3 fw-bold tambah-baris-sampul-btn" data-kategori="<?php echo $kategori_db; ?>">
                        <i class="bi bi-plus-circle-fill me-1"></i> Tambah Baris
                    </button>
                    <button type="submit" name="simpan_sampul_single" class="btn btn-purple-digital btn-sm py-2 px-4 rounded-3 fw-bold border-0 shadow-sm">
                        <i class="bi bi-check-circle-fill me-1"></i> Simpan Laporan <?= str_replace(['1. ', '2. ', '3. ', '4. ', '5. '], '', $judul_form); ?>
                    </button>
                </div>
            </div>
        </div>
    </form>
    <?php
}
?>

<!-- HEADER UTAMA HARI/TANGGAL -->
<div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4 bg-white">
    <div class="p-3.5 d-flex align-items-center justify-content-between text-white" style="background: linear-gradient(135deg, #4b1a8a, #2e0854);">
        <div class="d-flex align-items-center gap-2">
            <i class="bi bi-envelope-paper-heart-fill fs-4 text-warning"></i>
            <h5 class="fw-bold mb-0">Manajemen Kolektif Sampul Jemaat</h5>
        </div>
        <div class="style-tgl-box" style="width: 220px;">
            <input type="date" id="tanggal_sampul_master_input" class="form-control bg-dark bg-opacity-20 border-0 text-white text-center fw-bold small" value="<?php echo $tanggal_pilih; ?>" style="color-scheme: dark;" required>
        </div>
    </div>
</div>

<!-- PEMANGGILAN FORM TABEL INDEPENDEN -->
<?php 
renderFormSampul($koneksi, $tanggal_pilih, 'persepuluhan', '1. Sampul Persepuluhan', 'bi-envelope-fill'); 
renderFormSampul($koneksi, $tanggal_pilih, 'hut_pribadi', '2. Sampul Syukur HUT Pribadi', 'bi-cake2-fill'); 
renderFormSampul($koneksi, $tanggal_pilih, 'pernikahan', '3. Sampul Syukur Pernikahan', 'bi-heart-fill'); 
renderFormSampul($koneksi, $tanggal_pilih, 'lainnya', '4. Persembahan & Sampul Syukur Lainnya', 'bi-gift-fill'); 
renderFormSampul($koneksi, $tanggal_pilih, 'bulanan_keluarga', '5. Persembahan Bulanan Keluarga', 'bi-people-fill'); 
?>

<style>
.p-3\.5 { padding: 0.9rem 1.2rem; }
.input-digital-opsi { border: 1px solid #e2e8f0 !important; background: #f8fafc !important; font-size: 0.85rem; padding: 0.4rem 0.5rem !important; border-radius: 6px; font-weight: 600; }
.input-digital-opsi:focus { background: #fff !important; border-color: #4b1a8a !important; box-shadow: 0 0 0 3px rgba(75, 26, 138, 0.15) !important; }
.text-input-box { font-weight: 500 !important; color: #334155; }
.nominal-input { font-family: var(--bs-font-monospace); color: #1e293b; text-align: right; }
.btn-outline-purple { color: #4b1a8a; border: 2px solid #4b1a8a; transition: all 0.2s; }
.btn-outline-purple:hover { background: #4b1a8a; color: #ffffff; }
.btn-purple-digital { background: #6f42c1; color: #fff; transition: all 0.2s; }
.btn-purple-digital:hover { background: #5a32a3; color: #fff; }
.btn-light-danger { background: rgba(239, 68, 68, 0.06); color: #ef4444; border: none; padding: 0.35rem 0.55rem; }
.btn-light-danger:hover { background: #ef4444; color: #ffffff; }
.input-digital-opsi::-webkit-outer-spin-button, .input-digital-opsi::-webkit-inner-spin-button { -webkit-appearance: none; margin: 0; }
.input-digital-opsi[type=number] { -moz-appearance: textfield; }
</style>

<script>
document.addEventListener("DOMContentLoaded", function () {
    const masterTgl = document.getElementById("tanggal_sampul_master_input");

    function kalkulasiKolektifSampul() {
        document.querySelectorAll(".table-sampul-induk").forEach(tabel => {
            let totalSub = 0;
            tabel.querySelectorAll(".nominal-input").forEach(input => {
                totalSub += parseFloat(input.value) || 0;
            });
            tabel.querySelector(".output-grand-sampul").innerText = "Rp " + totalSub.toLocaleString('id-ID');
        });
    }

    kalkulasiKolektifSampul();

    document.addEventListener("input", function(e) {
        if(e.target.classList.contains("nominal-input")) {
            kalkulasiKolektifSampul();
        }
    });

    document.addEventListener("click", function(e) {
        const addBtn = e.target.closest(".tambah-baris-sampul-btn");
        if(addBtn) {
            const kat = addBtn.getAttribute("data-kategori");
            const tbody = addBtn.closest(".card").querySelector(".wrapper-baris-sampul");
            const tr = document.createElement("tr");
            tr.className = "sampul-row border-bottom border-light-subtle";
            tr.innerHTML = `
                <td class="px-2"><input type="number" name="kolom[]" class="form-control text-center input-digital-opsi text-purple-premium"></td>
                <td class="px-2"><input type="text" name="keterangan[]" class="form-control text-start input-digital-opsi text-input-box" required placeholder="Nama Lengkap / Keluarga"></td>
                <td class="px-2"><input type="number" name="nominal[]" class="form-control text-end input-digital-opsi nominal-input input-hitung-sampul" value="0" min="0" required></td>
                <td><button type="button" class="btn btn-sm btn-light-danger rounded-3 hapus-baris-sampul-btn"><i class="bi bi-trash3-fill"></i></button></td>
            `;
            tbody.appendChild(tr);
        }

        const delBtn = e.target.closest(".hapus-baris-sampul-btn");
        if(delBtn) {
            const tbody = delBtn.closest("tbody");
            if(tbody.querySelectorAll(".sampul-row").length > 1) {
                delBtn.closest(".sampul-row").remove();
                kalkulasiKolektifSampul();
            } else {
                alert("Minimal harus menyisakan 1 baris input data.");
            }
        }
    });

    if (masterTgl) {
        masterTgl.addEventListener("change", function() {
            const url = new URL(window.location);
            url.searchParams.set("tab", "edit-keuangan");
            url.searchParams.set("subtab", "sampul");
            url.searchParams.set("tgl_keuangan", this.value);
            window.location.search = url.search;
        });
    }
});
</script>