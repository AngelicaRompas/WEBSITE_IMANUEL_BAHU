<?php
// admin_keuangan_persepuluhan.php
$tanggal_pilih = isset($_GET['tgl_keuangan']) ? mysqli_real_escape_string($koneksi, $_GET['tgl_keuangan']) : date('Y-m-d');

// Ambil data yang sudah tersimpan pada tanggal terpilih
$data_existing = [];
$cek_data = mysqli_query($koneksi, "SELECT * FROM keuangan_persepuluhan WHERE tanggal = '$tanggal_pilih' ORDER BY id_persepuluhan ASC");
if ($cek_data) {
    while ($row = mysqli_fetch_assoc($cek_data)) {
        $data_existing[] = $row;
    }
}
?>

<div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4 bg-white">
    <!-- Header Form Estetik -->
    <div class="p-4 d-flex flex-column flex-md-row align-items-start align-items-md-center justify-content-between text-white gap-3" style="background: linear-gradient(135deg, #4b1a8a, #2e0854);">
        <div class="d-flex align-items-center gap-3">
            <div class="p-2 bg-white bg-opacity-10 rounded-3 border border-white-20">
                <i class="bi bi-envelope-paper-fill text-warning fs-4"></i>
            </div>
            <div>
                <h5 class="fw-bold mb-0" style="letter-spacing: 0.5px; color: #ffffff;">Sampul Persepuluhan</h5>
                <small style="color: rgba(255, 255, 255, 0.75);">Pencatatan daftar masuk persepuluhan jemaat</small>
            </div>
        </div>
        
        <!-- Input Tanggal Digital -->
        <div class="w-100 style-tgl-box" style="max-width: 250px;">
            <label class="form-label fw-semibold mb-1" style="font-size: 0.75rem; color: rgba(255, 255, 255, 0.75); text-transform: uppercase;">Periode Pelaksanaan</label>
            <div class="input-group input-group-sm rounded-3 overflow-hidden border border-white-20">
                <span class="input-group-text bg-white bg-opacity-10 border-0 text-white"><i class="bi bi-calendar-check"></i></span>
                <input type="date" id="tanggal_persepuluhan_tampilan" class="form-control bg-dark bg-opacity-20 border-0 text-white text-center fw-bold small" value="<?php echo $tanggal_pilih; ?>" style="color-scheme: dark;" required>
            </div>
        </div>
    </div>

    <!-- Konten Form Utama -->
    <div class="p-3 p-md-4">
        <form action="proses/proses_keuangan_persepuluhan.php" method="POST">
            <input type="hidden" name="tanggal" value="<?php echo $tanggal_pilih; ?>">

            <div class="table-responsive rounded-3 border border-light-subtle">
                <table class="table table-hover align-middle mb-0 text-center custom-digital-table" id="tabel_persepuluhan" style="min-width: 700px;">
                    <thead class="bg-light text-secondary text-uppercase fw-bold" style="font-size: 0.8rem; letter-spacing: 0.8px;">
                        <tr>
                            <th class="py-3" style="width: 15%;">Kolom</th>
                            <th class="py-3 text-start ps-3" style="width: 50%;">Keterangan Nama / Kel</th>
                            <th class="py-3 text-end pe-4" style="width: 25%;">Nominal</th>
                            <th class="py-3" style="width: 10%;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="wrapper_baris_data">
                        <?php 
                        if (!empty($data_existing)):
                            foreach ($data_existing as $index => $data):
                        ?>
                        <tr class="persepuluhan-row border-bottom border-light-subtle">
                            <td class="px-2">
                                <input type="number" name="kolom[]" class="form-control text-center input-digital-opsi" value="<?php echo $data['kolom']; ?>" required>
                            </td>
                            <td class="px-2">
                                <input type="text" name="keterangan[]" class="form-control text-start input-digital-opsi text-input-box" value="<?php echo $data['keterangan']; ?>" required placeholder="Contoh: Kel. Pnt. Rompas">
                            </td>
                            <td class="px-2">
                                <input type="number" name="nominal[]" class="form-control text-end input-digital-opsi nominal-input input-hitung-persepuluhan" value="<?php echo $data['nominal']; ?>" min="0" required>
                            </td>
                            <td>
                                <button type="button" class="btn btn-sm btn-light-danger rounded-3 hapus-baris-btn"><i class="bi bi-trash3-fill"></i></button>
                            </td>
                        </tr>
                        <?php 
                            endforeach;
                        else:
                        ?>
                        <!-- Baris Default Awal Jika Data Kosong -->
                        <tr class="persepuluhan-row border-bottom border-light-subtle">
                            <td class="px-2"><input type="number" name="kolom[]" class="form-control text-center input-digital-opsi" required></td>
                            <td class="px-2"><input type="text" name="keterangan[]" class="form-control text-start input-digital-opsi text-input-box" required placeholder="Nama Lengkap / Keluarga"></td>
                            <td class="px-2"><input type="number" name="nominal[]" class="form-control text-end input-digital-opsi nominal-input input-hitung-persepuluhan" value="0" min="0" required></td>
                            <td><button type="button" class="btn btn-sm btn-light-danger rounded-3 hapus-baris-btn"><i class="bi bi-trash3-fill"></i></button></td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                    <tfoot>
                        <!-- TOTAL AKUMULASI OTOMATIS -->
                        <tr class="fw-bold border-0 bg-light-subtle" style="background: #f8fafc;">
                            <td colspan="2" class="text-start ps-4 py-3 text-secondary fw-bolder" style="font-size: 0.8rem;">TOTAL KESELURUHAN REAL-TIME</td>
                            <td class="text-end pe-4 py-3 font-monospace text-purple-premium fs-5" id="total-grand-persepuluhan">Rp 0</td>
                            <td></td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            <!-- Tombol Aksi Responsive Grid -->
            <div class="mt-4 d-flex flex-column flex-md-row justify-content-between align-items-center gap-3">
                <button type="button" id="tambah_baris_btn" class="btn btn-outline-purple w-100 w-md-auto px-4 py-2 rounded-3 fw-bold">
                    <i class="bi bi-plus-circle-fill me-2"></i> Tambah Baris Data
                </button>
                <button type="submit" name="simpan_persepuluhan" class="btn btn-purple-digital w-100 w-md-auto px-4 py-2.5 rounded-3 fw-bold border-0 shadow-sm">
                    <i class="bi bi-cpu-fill me-2"></i> Sinkronisasi Laporan Sampul
                </button>
            </div>
        </form>
    </div>
</div>

<style>
.input-digital-opsi {
    border: 1px solid #e2e8f0 !important;
    background: #f8fafc !important;
    font-size: 0.9rem;
    padding: 0.45rem 0.6rem !important;
    border-radius: 8px;
    transition: all 0.2s ease;
    font-weight: 600;
}
.input-digital-opsi:focus {
    background: #ffffff !important;
    border-color: #4b1a8a !important;
    box-shadow: 0 0 0 3px rgba(75, 26, 138, 0.15) !important;
}
.text-input-box { font-weight: 500 !important; color: #334155; }
.nominal-input { font-family: var(--bs-font-monospace); color: #1e293b; text-align: right; }
.btn-outline-purple { color: #4b1a8a; border: 2px solid #4b1a8a; transition: all 0.25s; }
.btn-outline-purple:hover { background: #4b1a8a; color: #ffffff; }
.btn-light-danger { background: rgba(239, 68, 68, 0.08); color: #ef4444; border: none; padding: 0.45rem 0.65rem; transition: all 0.2s; }
.btn-light-danger:hover { background: #ef4444; color: #ffffff; }

/* SPINNER REMOVER */
.input-digital-opsi::-webkit-outer-spin-button,
.input-digital-opsi::-webkit-inner-spin-button { -webkit-appearance: none; margin: 0; }
.input-digital-opsi[type=number] { -moz-appearance: textfield; }
</style>

<script>
document.addEventListener("DOMContentLoaded", function () {
    const wrapper = document.getElementById("wrapper_baris_data");
    const tambahBtn = document.getElementById("tambah_baris_btn");
    const tglInput = document.getElementById("tanggal_persepuluhan_tampilan");

    function hitungTotalPersepuluhan() {
        let total = 0;
        document.querySelectorAll(".nominal-input").forEach(input => {
            total += parseFloat(input.value) || 0;
        });
        document.getElementById("total-grand-persepuluhan").innerText = "Rp " + total.toLocaleString('id-ID');
    }

    // Hitung saat load pertama kali
    hitungTotalPersepuluhan();

    // Event Delegation untuk hitung input dinamis
    wrapper.addEventListener("input", function(e) {
        if(e.target.classList.contains("nominal-input")) {
            hitungTotalPersepuluhan();
        }
    });

    // Tambah Baris Baru
    tambahBtn.addEventListener("click", function() {
        const barisBaru = document.createElement("tr");
        barisBaru.className = "persepuluhan-row border-bottom border-light-subtle";
        barisBaru.innerHTML = `
            <td class="px-2"><input type="number" name="kolom[]" class="form-control text-center input-digital-opsi" required></td>
            <td class="px-2"><input type="text" name="keterangan[]" class="form-control text-start input-digital-opsi text-input-box" required placeholder="Nama Lengkap / Keluarga"></td>
            <td class="px-2"><input type="number" name="nominal[]" class="form-control text-end input-digital-opsi nominal-input input-hitung-persepuluhan" value="0" min="0" required></td>
            <td><button type="button" class="btn btn-sm btn-light-danger rounded-3 hapus-baris-btn"><i class="bi bi-trash3-fill"></i></button></td>
        `;
        wrapper.appendChild(barisBaru);
    });

    // Hapus Baris Data
    wrapper.addEventListener("click", function(e) {
        const btnHapus = e.target.closest(".hapus-baris-btn");
        if(btnHapus) {
            const baris = btnHapus.closest(".persepuluhan-row");
            if(document.querySelectorAll(".persepuluhan-row").length > 1) {
                baris.remove();
                hitungTotalPersepuluhan();
            } else {
                alert("Minimal harus menyisakan 1 baris input data.");
            }
        }
    });

    // Jalur Sinkronisasi Tanggal Router Utama
    if (tglInput) {
        tglInput.addEventListener("change", function() {
            const url = new URL(window.location);
            url.searchParams.set("tab", "edit-keuangan");
            url.searchParams.set("subtab", "persepuluhan");
            url.searchParams.set("tgl_keuangan", this.value);
            window.location.search = url.search;
        });
    }
});
</script>