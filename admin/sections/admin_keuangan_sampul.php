<?php
// admin_keuangan_sampul.php
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
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-5 bg-white border-light-subtle">
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
                            <td class="px-2"><input type="number" name="<?php echo $kategori_db; ?>_kolom[]" class="form-control text-center input-digital-opsi text-purple-premium" value="<?php echo $data['kolom']; ?>" required></td>
                            <td class="px-2"><input type="text" name="<?php echo $kategori_db; ?>_keterangan[]" class="form-control text-start input-digital-opsi text-input-box" value="<?php echo $data['keterangan']; ?>" required></td>
                            <td class="px-2"><input type="number" name="<?php echo $kategori_db; ?>_nominal[]" class="form-control text-end input-digital-opsi nominal-input input-hitung-sampul" value="<?php echo $data['nominal']; ?>" min="0" required></td>
                            <td><button type="button" class="btn btn-sm btn-light-danger rounded-3 hapus-baris-sampul-btn"><i class="bi bi-trash3-fill"></i></button></td>
                        </tr>
                        <?php 
                            endforeach;
                        else:
                        ?>
                        <tr class="sampul-row border-bottom border-light-subtle">
                            <td class="px-2"><input type="number" name="<?php echo $kategori_db; ?>_kolom[]" class="form-control text-center input-digital-opsi text-purple-premium" required></td>
                            <td class="px-2"><input type="text" name="<?php echo $kategori_db; ?>_keterangan[]" class="form-control text-start input-digital-opsi text-input-box" required placeholder="Nama Lengkap / Keluarga"></td>
                            <td class="px-2"><input type="number" name="<?php echo $kategori_db; ?>_nominal[]" class="form-control text-end input-digital-opsi nominal-input input-hitung-sampul" value="0" min="0" required></td>
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
            <div class="mt-3 text-start">
                <button type="button" class="btn btn-outline-purple btn-sm py-2 px-3 rounded-3 fw-bold tambah-baris-sampul-btn" data-kategori="<?php echo $kategori_db; ?>">
                    <i class="bi bi-plus-circle-fill me-1"></i> Tambah Baris
                </button>
            </div>
        </div>
    </div>
    <?php
}
?>

<!-- HEADER UTAMA HARI/TANGGAL DAN FORM AKSI -->
<form action="proses/proses_keuangan_sampul.php" method="POST">
    <input type="hidden" name="tanggal" value="<?php echo $tanggal_pilih; ?>">

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

    <!-- PEMANGGILAN 5 FORM TABEL SESUAI PERINTAH -->
    <?php 
    renderFormSampul($koneksi, $tanggal_pilih, 'persepuluhan', '1. Sampul Persepuluhan', 'bi-envelope-fill'); 
    renderFormSampul($koneksi, $tanggal_pilih, 'hut_pribadi', '2. Sampul Syukur HUT Pribadi', 'bi-cake2-fill'); 
    renderFormSampul($koneksi, $tanggal_pilih, 'pernikahan', '3. Sampul Syukur Pernikahan', 'bi-heart-fill'); 
    renderFormSampul($koneksi, $tanggal_pilih, 'lainnya', '4. Persembahan & Sampul Syukur Lainnya', 'bi-gift-fill'); 
    renderFormSampul($koneksi, $tanggal_pilih, 'bulanan_keluarga', '5. Persembahan Bulanan Keluarga', 'bi-people-fill'); 
    ?>

    <!-- FOOTER AKUMULASI DARI 5 FORM TABEL -->
    <div class="card border-0 shadow-sm rounded-4 p-4 text-white mb-4" style="background: linear-gradient(135deg, #1e0b36, #090214);">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-center gap-3">
            <div>
                <h4 class="fw-bold mb-1">TOTAL GRAND AKUMULASI SAMPUL</h4>
                <small class="text-white-50">Gabungan nilai nominal dari kelima form tabel di atas pada periode ini</small>
            </div>
            <h2 class="fw-bolder text-warning font-monospace mb-0" id="total_ultimate_sampul">Rp 0</h2>
        </div>
    </div>

    <div class="mb-5 text-end">
        <button type="submit" name="simpan_sampul_massal" class="btn btn-purple-digital w-100 w-md-auto px-5 py-3 rounded-3 fw-bold border-0 shadow-sm fs-6">
            <i class="bi bi-cloud-arrow-up-fill me-2"></i> Sinkronisasi Seluruh Sampul
        </button>
    </div>
</form>

<style>
.p-3\.5 { padding: 0.9rem 1.2rem; }
.input-digital-opsi { border: 1px solid #e2e8f0 !important; background: #f8fafc !important; font-size: 0.85rem; padding: 0.4rem 0.5rem !important; border-radius: 6px; font-weight: 600; }
.input-digital-opsi:focus { background: #fff !important; border-color: #4b1a8a !important; box-shadow: 0 0 0 3px rgba(75, 26, 138, 0.15) !important; }
.text-input-box { font-weight: 500 !important; color: #334155; }
.nominal-input { font-family: var(--bs-font-monospace); color: #1e293b; text-align: right; }
.btn-outline-purple { color: #4b1a8a; border: 2px solid #4b1a8a; transition: all 0.2s; }
.btn-outline-purple:hover { background: #4b1a8a; color: #ffffff; }
.btn-light-danger { background: rgba(239, 68, 68, 0.06); color: #ef4444; border: none; padding: 0.35rem 0.55rem; }
.btn-light-danger:hover { background: #ef4444; color: #ffffff; }
.input-digital-opsi::-webkit-outer-spin-button, .input-digital-opsi::-webkit-inner-spin-button { -webkit-appearance: none; margin: 0; }
.input-digital-opsi[type=number] { -moz-appearance: textfield; }
</style>

<script>
document.addEventListener("DOMContentLoaded", function () {
    const masterTgl = document.getElementById("tanggal_sampul_master_input");

    function kalkulasiKolektifSampul() {
        let grandUltimate = 0;
        document.querySelectorAll(".table-sampul-induk").forEach(tabel => {
            let totalSub = 0;
            tabel.querySelectorAll(".nominal-input").forEach(input => {
                totalSub += parseFloat(input.value) || 0;
            });
            tabel.querySelector(".output-grand-sampul").innerText = "Rp " + totalSub.toLocaleString('id-ID');
            grandUltimate += totalSub;
        });
        document.getElementById("total_ultimate_sampul").innerText = "Rp " + grandUltimate.toLocaleString('id-ID');
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
                <td class="px-2"><input type="number" name="${kat}_kolom[]" class="form-control text-center input-digital-opsi text-purple-premium" required></td>
                <td class="px-2"><input type="text" name="${kat}_keterangan[]" class="form-control text-start input-digital-opsi text-input-box" required placeholder="Nama Lengkap / Keluarga"></td>
                <td class="px-2"><input type="number" name="${kat}_nominal[]" class="form-control text-end input-digital-opsi nominal-input input-hitung-sampul" value="0" min="0" required></td>
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