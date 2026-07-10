<?php
// admin_keuangan_kolom.php
$tanggal_pilih = isset($_GET['tgl_keuangan']) ? mysqli_real_escape_string($koneksi, $_GET['tgl_keuangan']) : date('Y-m-d');

$data_existing = [];
$cek_data = mysqli_query($koneksi, "SELECT * FROM keuangan_ibadah_kolom WHERE tanggal = '$tanggal_pilih'");
if ($cek_data) {
    while ($row = mysqli_fetch_assoc($cek_data)) {
        $data_existing[$row['kolom']] = $row;
    }
}
?>

<div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4 bg-white">
    <!-- Header Form Estetik Selaras -->
    <div class="p-4 d-flex flex-column flex-md-row align-items-start align-items-md-center justify-content-between text-white gap-3" style="background: linear-gradient(135deg, #4b1a8a, #2e0854);">
        <div class="d-flex align-items-center gap-3">
            <div class="p-2 bg-white bg-opacity-10 rounded-3 border border-white-20">
                <i class="bi bi-grid-3x3-gap-fill text-warning fs-4"></i>
            </div>
            <div>
                <h5 class="fw-bold mb-0" style="letter-spacing: 0.5px; color: #ffffff;">Penyetoran Kolom</h5>
                <small style="color: rgba(255, 255, 255, 0.75);">Pencatatan kas digital untuk kolom 1 sampai 28</small>
            </div>
        </div>
        
        <!-- Input Tanggal Digital -->
        <div class="w-100 style-tgl-box" style="max-width: 250px;">
            <label class="form-label fw-semibold mb-1" style="font-size: 0.75rem; color: rgba(255, 255, 255, 0.75); text-transform: uppercase;">Periode Pelaksanaan</label>
            <div class="input-group input-group-sm rounded-3 overflow-hidden border border-white-20">
                <span class="input-group-text bg-white bg-opacity-10 border-0 text-white"><i class="bi bi-calendar-check"></i></span>
                <input type="date" id="tanggal_keuangan_kolom_tampilan" class="form-control bg-dark bg-opacity-20 border-0 text-white text-center fw-bold small" value="<?php echo $tanggal_pilih; ?>" style="color-scheme: dark;" required>
            </div>
        </div>
    </div>

    <!-- Konten Form Utama -->
    <div class="p-3 p-md-4">
        <form action="proses/proses_keuangan_kolom.php" method="POST">
            <input type="hidden" name="tanggal" value="<?php echo $tanggal_pilih; ?>">

            <div class="table-responsive rounded-3 border border-light-subtle">
                <!-- Ditambahkan lebar min-width agar pas dengan kolom awal bln -->
                <table class="table table-hover align-middle mb-0 text-center custom-digital-table" style="min-width: 1300px;">
                    <thead class="bg-light text-secondary text-uppercase fw-bold" style="font-size: 0.75rem; letter-spacing: 0.8px;">
                        <tr>
                            <th class="py-3 text-start ps-4" style="width: 7%;">Kolom</th>
                            <th class="py-3">Pers Kolom</th>
                            <th class="py-3">PKB</th>
                            <th class="py-3">WKI</th>
                            <th class="py-3">Pemuda</th>
                            <th class="py-3">Remaja</th>
                            <th class="py-3">ASM</th>
                            <th class="py-3">PDP</th>
                            <th class="py-3">PEM</th>
                            <th class="py-3">Awal Bln</th> <!-- Kolom Baru -->
                            <th class="py-3 text-end pe-4" style="width: 11%;">Jumlah</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        for ($k = 1; $k <= 28; $k++): 
                            $val_pers    = isset($data_existing[$k]) ? floatval($data_existing[$k]['pers_kolom']) : 0;
                            $val_pkb     = isset($data_existing[$k]) ? floatval($data_existing[$k]['pkb']) : 0;
                            $val_wki     = isset($data_existing[$k]) ? floatval($data_existing[$k]['wki']) : 0;
                            $val_pemuda  = isset($data_existing[$k]) ? floatval($data_existing[$k]['pemuda']) : 0;
                            $val_remaja  = isset($data_existing[$k]) ? floatval($data_existing[$k]['remaja']) : 0;
                            $val_asm     = isset($data_existing[$k]) ? floatval($data_existing[$k]['asm']) : 0;
                            $val_pdp     = isset($data_existing[$k]) ? floatval($data_existing[$k]['pdp']) : 0;
                            $val_pem     = isset($data_existing[$k]) ? floatval($data_existing[$k]['pem']) : 0;
                            $val_awal_bln= isset($data_existing[$k]) ? floatval($data_existing[$k]['awal_bln']) : 0; // Data Baru
                            
                            // Kalkulasi total baris termasuk awal bulan
                            $row_total = $val_pers + $val_pkb + $val_wki + $val_pemuda + $val_remaja + $val_asm + $val_pdp + $val_pem + $val_awal_bln;
                        ?>
                        <tr class="kolom-row border-bottom border-light-subtle" data-kolom="<?php echo $k; ?>">
                            <td class="text-start ps-4 fw-bold text-dark py-2">
                                <span class="badge px-2.5 py-2 text-purple-premium rounded-3" style="background: rgba(147, 51, 234, 0.08); font-size: 0.85rem;">
                                    Kolom <?php echo $k; ?>
                                </span>
                                <input type="hidden" name="kolom_no[]" value="<?php echo $k; ?>">
                            </td>
                            
                            <td><input type="number" name="pers_kolom[]" class="form-control table-input-digital input-kolom-hitung pers-input" value="<?php echo $val_pers; ?>" min="0"></td>
                            <td><input type="number" name="pkb[]" class="form-control table-input-digital input-kolom-hitung pkb-input" value="<?php echo $val_pkb; ?>" min="0"></td>
                            <td><input type="number" name="wki[]" class="form-control table-input-digital input-kolom-hitung wki-input" value="<?php echo $val_wki; ?>" min="0"></td>
                            <td><input type="number" name="pemuda[]" class="form-control table-input-digital input-kolom-hitung pemuda-input" value="<?php echo $val_pemuda; ?>" min="0"></td>
                            <td><input type="number" name="remaja[]" class="form-control table-input-digital input-kolom-hitung remaja-input" value="<?php echo $val_remaja; ?>" min="0"></td>
                            <td><input type="number" name="asm[]" class="form-control table-input-digital input-kolom-hitung asm-input" value="<?php echo $val_asm; ?>" min="0"></td>
                            <td><input type="number" name="pdp[]" class="form-control table-input-digital input-kolom-hitung pdp-input" value="<?php echo $val_pdp; ?>" min="0"></td>
                            <td><input type="number" name="pem[]" class="form-control table-input-digital input-kolom-hitung pem-input" value="<?php echo $val_pem; ?>" min="0"></td>
                            <td><input type="number" name="awal_bln[]" class="form-control table-input-digital input-kolom-hitung awal-bln-input" value="<?php echo $val_awal_bln; ?>" min="0"></td> <!-- Input Baru -->
                            
                            <td class="text-end pe-4 font-monospace fw-bold text-dark">
                                <input type="text" class="form-control text-end fw-bold text-dark border-0 p-0 bg-transparent row-total-output" value="<?php echo number_format($row_total, 0, ',', '.'); ?>" readonly style="outline: none; box-shadow: none;">
                            </td>
                        </tr>
                        <?php endfor; ?>
                        
                        <!-- FOOTER TOTAL AKHIR KESELURUHAN -->
                        <tr class="fw-bold border-0 bg-light-subtle" style="background: #f8fafc;">
                            <td class="text-start ps-4 py-3 text-secondary fw-bolder" style="font-size: 0.75rem;">TOTAL</td>
                            <td id="total-pers-all" class="font-monospace text-muted small text-end px-2">Rp 0</td>
                            <td id="total-pkb-all" class="font-monospace text-muted small text-end px-2">Rp 0</td>
                            <td id="total-wki-all" class="font-monospace text-muted small text-end px-2">Rp 0</td>
                            <td id="total-pemuda-all" class="font-monospace text-muted small text-end px-2">Rp 0</td>
                            <td id="total-remaja-all" class="font-monospace text-muted small text-end px-2">Rp 0</td>
                            <td id="total-asm-all" class="font-monospace text-muted small text-end px-2">Rp 0</td>
                            <td id="total-pdp-all" class="font-monospace text-muted small text-end px-2">Rp 0</td>
                            <td id="total-pem_all" class="font-monospace text-muted small text-end px-2">Rp 0</td>
                            <td id="total-awal-bln-all" class="font-monospace text-muted small text-end px-2">Rp 0</td> <!-- Total Footer Baru -->
                            <td class="text-end pe-4 py-3 font-monospace text-purple-premium text-end" id="total-kolom-grand-all" style="font-size: 1.05rem; text-shadow: 0 0 10px rgba(124, 58, 237, 0.1);">Rp 0</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="mt-4 text-end">
                <button type="submit" name="simpan_keuangan_kolom" class="btn btn-purple-digital w-100 w-md-auto px-4 py-2-5 rounded-3 fw-bold border-0 shadow-sm">
                    <i class="bi bi-cpu-fill me-2"></i> Sinkronisasi Laporan Kolom
                </button>
            </div>
        </form>
    </div>
</div>

<style>
.table-input-digital {
    border: 1px solid #e2e8f0 !important;
    background: #f8fafc !important;
    text-align: right;
    font-family: var(--bs-font-monospace);
    font-weight: 600;
    color: #334155;
    font-size: 0.85rem;
    padding: 0.35rem 0.5rem !important;
    border-radius: 6px;
    min-width: 95px;
    transition: all 0.2s ease;
}
.table-input-digital:focus {
    background: #ffffff !important;
    border-color: #4b1a8a !important;
    box-shadow: 0 0 0 3px rgba(75, 26, 138, 0.15) !important;
}

/* MENYEMBUNYIKAN FITUR PANAH NAIK TURUN (SPINNER) */
.table-input-digital::-webkit-outer-spin-button,
.table-input-digital::-webkit-inner-spin-button {
    -webkit-appearance: none;
    margin: 0;
}
.table-input-digital[type=number] {
    -moz-appearance: textfield;
}
</style>

<script>
document.addEventListener("DOMContentLoaded", function () {
    const inputsKolom = document.querySelectorAll(".input-kolom-hitung");
    const tglKolomInput = document.getElementById("tanggal_keuangan_kolom_tampilan");

    function hitungMajuKolom() {
        let gPers = 0, gPkb = 0, gWki = 0, gPemuda = 0, gRemaja = 0, gAsm = 0, gPdp = 0, gPem = 0, gAwalBln = 0, gGrand = 0;
        const rows = document.querySelectorAll(".kolom-row");

        rows.forEach(row => {
            const pers = parseFloat(row.querySelector(".pers-input").value) || 0;
            const pkb = parseFloat(row.querySelector(".pkb-input").value) || 0;
            const wki = parseFloat(row.querySelector(".wki-input").value) || 0;
            const pemuda = parseFloat(row.querySelector(".pemuda-input").value) || 0;
            const remaja = parseFloat(row.querySelector(".remaja-input").value) || 0;
            const asm = parseFloat(row.querySelector(".asm-input").value) || 0;
            const pdp = parseFloat(row.querySelector(".pdp-input").value) || 0;
            const pem = parseFloat(row.querySelector(".pem-input").value) || 0;
            const awalBln = parseFloat(row.querySelector(".awal-bln-input").value) || 0; // JS Ambil Data Baru

            const totalBaris = pers + pkb + wki + pemuda + remaja + asm + pdp + pem + awalBln;
            row.querySelector(".row-total-output").value = totalBaris.toLocaleString('id-ID');

            gPers += pers; gPkb += pkb; gWki += wki; gPemuda += pemuda;
            gRemaja += remaja; gAsm += asm; gPdp += pdp; gPem += pem; gAwalBln += awalBln;
            gGrand += totalBaris;
        });

        document.getElementById("total-pers-all").innerText = "Rp " + gPers.toLocaleString('id-ID');
        document.getElementById("total-pkb-all").innerText = "Rp " + gPkb.toLocaleString('id-ID');
        document.getElementById("total-wki-all").innerText = "Rp " + gWki.toLocaleString('id-ID');
        document.getElementById("total-pemuda-all").innerText = "Rp " + gPemuda.toLocaleString('id-ID');
        document.getElementById("total-remaja-all").innerText = "Rp " + gRemaja.toLocaleString('id-ID');
        document.getElementById("total-asm-all").innerText = "Rp " + gAsm.toLocaleString('id-ID');
        document.getElementById("total-pdp-all").innerText = "Rp " + gPdp.toLocaleString('id-ID');
        document.getElementById("total-pem_all").innerText = "Rp " + gPem.toLocaleString('id-ID');
        document.getElementById("total-awal-bln-all").innerText = "Rp " + gAwalBln.toLocaleString('id-ID'); // Footer Render Baru
        document.getElementById("total-kolom-grand-all").innerText = "Rp " + gGrand.toLocaleString('id-ID');
    }

    hitungMajuKolom();

    inputsKolom.forEach(inBox => {
        inBox.addEventListener("input", hitungMajuKolom);
    });

    if (tglKolomInput) {
        tglKolomInput.addEventListener("change", function() {
            const url = new URL(window.location);
            url.searchParams.set("tab", "edit-keuangan");
            url.searchParams.set("subtab", "kolom");
            url.searchParams.set("tgl_keuangan", this.value);
            window.location.search = url.search;
        });
    }
});
</script>