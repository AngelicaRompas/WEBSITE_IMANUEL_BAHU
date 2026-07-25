<?php
// admin_keuangan_minggu.php
$tanggal_pilih = isset($tanggal_pilih) ? $tanggal_pilih : date('Y-m-d');

$data_existing = [];
$cek_data = mysqli_query($koneksi, "SELECT * FROM keuangan_ibadah_minggu WHERE tanggal = '$tanggal_pilih'");
if ($cek_data) {
    while ($row = mysqli_fetch_assoc($cek_data)) {
        $data_existing[$row['sesi_ibadah']] = $row;
    }
}
?>

<div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4 bg-white">
    <!-- Header Form Estetik -->
    <div class="p-4 d-flex flex-column flex-md-row align-items-start align-items-md-center justify-content-between text-white gap-3" style="background: linear-gradient(135deg, #4b1a8a, #2e0854);">
        <div class="d-flex align-items-center gap-3">
            <div class="p-2 bg-white bg-opacity-10 rounded-3 border border-white-20">
                <i class="bi bi-wallet2 text-warning fs-4"></i>
            </div>
            <div>
                <h5 class="fw-bold mb-0" style="letter-spacing: 0.5px; color: #ffffff;">Penerimaan Ibadah Minggu</h5>
                <small style="color: rgba(255, 255, 255, 0.75);">Manajemen pencatatan pundi dan sarana prasarana digital</small>
            </div>
        </div>
        
        <!-- Input Tanggal Digital -->
        <div class="w-100 style-tgl-box" style="max-width:250px;">

    <label class="form-label fw-semibold mb-1"
           style="font-size:.75rem;
                  color:rgba(255,255,255,.75);
                  text-transform:uppercase;">
        Periode Pelaksanaan
    </label>

    <div class="input-group input-group-sm rounded-3 overflow-hidden border border-white-20">

        <span class="input-group-text bg-white bg-opacity-10 border-0 text-white">
            <i class="bi bi-calendar-check"></i>
        </span>

        <input
            type="date"
            id="tanggalTampilan"
            name="tanggal"
            class="form-control bg-dark bg-opacity-20 border-0 text-white text-center fw-bold small"
            value="<?php echo htmlspecialchars($tanggal_pilih); ?>"
            style="color-scheme: dark;"
            required>

    </div>

</div>

    <!-- Konten Form Utama -->
    <div class="p-3 p-md-4">
        <form action="proses/proses_keuangan_minggu.php" method="POST" autocomplete="off">
            <input type="hidden" name="tab" value="edit-keuangan">
            <input type="hidden" name="subtab" value="minggu">
            

            <div class="table-responsive rounded-3 border border-light-subtle">
                <table class="table table-hover align-middle mb-0 text-center custom-digital-table" style="min-width: 750px;">
                    <thead class="bg-light text-secondary text-uppercase fw-bold" style="font-size: 0.8rem; letter-spacing: 0.8px;">
                        <tr>
                            <th class="py-3 text-start ps-4" style="width: 16%;">Sesi Ibadah</th>
                            <th class="py-3" style="width: 21%;">Pundi I</th>
                            <th class="py-3" style="width: 21%;">Pundi II</th>
                            <th class="py-3" style="width: 21%;">Sarana Prasarana</th>
                            <th class="py-3 text-end pe-4" style="width: 21%;">Sub Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $sesi_list = ['I', 'II', 'III'];
                        foreach ($sesi_list as $sesi): 
                            $nama_sesi_db = "Sesi " . $sesi;
                            $val_pundi1 = isset($data_existing[$nama_sesi_db]) ? floatval($data_existing[$nama_sesi_db]['pundi_1']) : 0;
                            $val_pundi2 = isset($data_existing[$nama_sesi_db]) ? floatval($data_existing[$nama_sesi_db]['pundi_2']) : 0;
                            $val_sapras = isset($data_existing[$nama_sesi_db]) ? floatval($data_existing[$nama_sesi_db]['sarana_prasarana']) : 0;
                            $val_jumlah = $val_pundi1 + $val_pundi2 + $val_sapras;
                        ?>
                        <tr class="sesi-row border-bottom border-light-subtle" data-sesi="<?php echo $sesi; ?>">
                            <td class="text-start ps-4 fw-bold text-dark-subtle py-3.5">
                                <span class="badge px-3 py-2 text-primary rounded-pill" style="background: rgba(59, 130, 246, 0.08); font-size: 0.85rem;">
                                    Sesi <?php echo $sesi; ?>
                                </span>
                                <input type="hidden" name="sesi[]" value="<?php echo $nama_sesi_db; ?>">
                            </td>
                            
                            <td class="px-2">
                                <div class="digital-input-box">
                                    <span class="currency-label">Rp</span>
                                    <input type="number" name="pundi_1[]" class="form-control input-hitung pundi1-input" value="<?php echo $val_pundi1; ?>" min="0">
                                </div>
                            </td>
                            
                            <td class="px-2">
                                <div class="digital-input-box">
                                    <span class="currency-label">Rp</span>
                                    <input type="number" name="pundi_2[]" class="form-control input-hitung pundi2-input" value="<?php echo $val_pundi2; ?>" min="0">
                                </div>
                            </td>
                            
                            <td class="px-2">
                                <div class="digital-input-box">
                                    <span class="currency-label">Rp</span>
                                    <input type="number" name="saranaprasarana[]" class="form-control input-hitung sapras-input" value="<?php echo $val_sapras; ?>" min="0">
                                </div>
                            </td>
                            
                            <td class="text-end pe-4 font-monospace fw-bold text-dark" style="font-size: 0.95rem;">
                                <div class="input-group input-group-sm justify-content-end align-items-center">
                                    <span class="text-secondary small me-1">Rp</span>
                                    <input type="text" class="form-control text-end fw-bold text-dark border-0 p-0 bg-transparent jumlah-sesi-output" value="<?php echo number_format($val_jumlah, 0, ',', '.'); ?>" style="width: 120px;" readonly>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                        
                        <tr class="fw-bold border-0 bg-light" style="background: rgba(248, 250, 252, 0.85);">
                            <td class="text-start ps-4 py-4 text-secondary uppercase fw-bolder" style="font-size: 0.8rem; letter-spacing: 0.5px;">TOTAL REAL-TIME</td>
                            <td id="total-pundi1-all" class="font-monospace text-muted small">Rp 0</td>
                            <td id="total-pundi2-all" class="font-monospace text-muted small">Rp 0</td>
                            <td id="total-sapras-all" class="font-monospace text-muted small">Rp 0</td>
                            <td class="text-end pe-4 py-4 font-monospace text-purple-premium" id="total-grand-all" style="font-size: 1.15rem; text-shadow: 0 0 10px rgba(124, 58, 237, 0.1);">Rp 0</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="mt-4 text-end">
                <button type="submit" name="simpan_keuangan_minggu" class="btn btn-purple-digital w-100 w-md-auto px-4 py-2-5 rounded-3 fw-bold border-0 shadow-sm">
                    <i class="bi bi-cpu-fill me-2"></i> Sinkronisasi Laporan Keuangan
                </button>
            </div>
        </form>
    </div>
</div>

<style>
.py-3\.5 { padding-top: 0.85rem; padding-bottom: 0.85rem; }
.digital-input-box {
    position: relative;
    display: flex;
    align-items: center;
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    padding: 0 0.5rem;
    transition: all 0.25s ease;
}
.digital-input-box:focus-within {
    background: #ffffff;
    border-color: #4b1a8a;
    box-shadow: 0 0 0 3px rgba(75, 26, 138, 0.15);
}
.currency-label {
    font-size: 0.75rem;
    font-weight: 700;
    color: #94a3b8;
    user-select: none;
}
.digital-input-box input[type="number"] {
    border: none !important;
    background: transparent !important;
    box-shadow: none !important;
    text-align: right;
    font-family: var(--bs-font-monospace);
    font-weight: 600;
    color: #334155;
    padding: 0.45rem 0.5rem !important;
    font-size: 0.9rem;
    width: 100%;
}
.btn-purple-digital {
    background: linear-gradient(135deg, #4b1a8a, #2e0854);
    color: white;
    transition: all 0.3s ease;
}
.btn-purple-digital:hover {
    background: linear-gradient(135deg, #371266, #1f043a);
    box-shadow: 0 4px 15px rgba(75, 26, 138, 0.3);
    transform: translateY(-1px);
}
.custom-digital-table tbody tr {
    transition: background-color 0.2s ease;
}
.custom-digital-table tbody tr:hover {
    background-color: rgba(241, 245, 249, 0.4);
}
@media (max-width: 768px) {
    .style-tgl-box { max-width: 100% !important; }
}

/* Hilangkan spinner untuk Form Ibadah Minggu */
.digital-input-box input[type="number"]::-webkit-outer-spin-button,
.digital-input-box input[type="number"]::-webkit-inner-spin-button {
    -webkit-appearance: none;
    margin: 0;
}
.digital-input-box input[type="number"] {
    -moz-appearance: textfield;
}

</style>

<script>
document.addEventListener("DOMContentLoaded", function () {
    const inputs = document.querySelectorAll(".input-hitung");
    const tanggalTampilan = document.getElementById("tanggalTampilan");

    function hitungSemua() {
        let grandPundi1 = 0, grandPundi2 = 0, grandSapras = 0, grandTotalSemua = 0;
        const rows = document.querySelectorAll(".sesi-row");

        rows.forEach(row => {
            const pundi1 = parseFloat(row.querySelector(".pundi1-input").value) || 0;
            const pundi2 = parseFloat(row.querySelector(".pundi2-input").value) || 0;
            const sapras = parseFloat(row.querySelector(".sapras-input").value) || 0;

            const totalSesi = pundi1 + pundi2 + sapras;
            row.querySelector(".jumlah-sesi-output").value = totalSesi.toLocaleString('id-ID');

            grandPundi1 += pundi1;
            grandPundi2 += pundi2;
            grandSapras += sapras;
            grandTotalSemua += totalSesi;
        });

        document.getElementById("total-pundi1-all").innerText = "Rp " + grandPundi1.toLocaleString('id-ID');
        document.getElementById("total-pundi2-all").innerText = "Rp " + grandPundi2.toLocaleString('id-ID');
        document.getElementById("total-sapras-all").innerText = "Rp " + grandSapras.toLocaleString('id-ID');
        document.getElementById("total-grand-all").innerText = "Rp " + grandTotalSemua.toLocaleString('id-ID');
    }

    hitungSemua();

    inputs.forEach(input => {
        input.addEventListener("input", hitungSemua);
    });

    if (tanggalTampilan) {
    tanggalTampilan.addEventListener("change", function () {

        const url = new URL(window.location.href);

        url.searchParams.set("tab", "edit-keuangan");
        url.searchParams.set("subtab", "minggu");
        url.searchParams.set("tgl_keuangan", this.value);

        window.location.href = url.href;
    });
}

});
</script>