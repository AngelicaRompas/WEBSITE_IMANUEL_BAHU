<?php
// admin_DataJemaat.php
?>

<link rel="stylesheet" href="assets/css/style-admin-konten.css?v=<?php echo time(); ?>">

<h4 class="section-header-jemaat mb-4 text-purple-premium">
    <i class="bi bi-people-fill me-2"></i>Pengaturan Analisis Data Jemaat & Statistik
</h4>

<!-- Tempat munculnya alert -->
<div id="alertPlaceholder"></div>

<!-- Form dengan ID formDataJemaat untuk AJAX -->
<form id="formDataJemaat">
    <!-- BLOK 1: STATISTIK UTAMA JEMAAT -->
    <div class="card card-custom p-4 mb-4 shadow-sm bg-white border-0">
        <h5 class="fw-bold mb-3 text-dark d-flex align-items-center" style="font-size: 1.1rem;">
            <i class="bi bi-grid-3x3-gap-fill me-2 text-purple-premium"></i>Statistik Utama Jemaat
        </h5>
        <div class="row g-3">
            <div class="col-md-4">
                <label class="form-label small fw-bold text-secondary">Jumlah Total Kolom</label>
                <input type="number" name="jml_kolom" class="form-control form-control-custom" value="<?php echo $stats['Kolom']['jumlah'] ?? 0; ?>" min="0" step="1" required>
            </div>
            <div class="col-md-4">
                <label class="form-label small fw-bold text-secondary">Jumlah Total Keluarga</label>
                <input type="number" name="jml_keluarga" class="form-control form-control-custom" value="<?php echo $stats['Keluarga']['jumlah'] ?? 0; ?>" min="0" step="1" required>
            </div>
            <div class="col-md-4">
                <label class="form-label small fw-bold text-purple-premium">Jumlah Total Anggota Jemaat (Total Jiwa)</label>
                <input type="number" name="jml_anggota" class="form-control form-control-custom fw-bold border-purple-premium text-purple-premium" value="<?php echo $stats['Anggota']['jumlah'] ?? 0; ?>" min="1" step="1" required>
            </div>
        </div>
    </div>

    <!-- BLOK 2: DATA DETAIL GRAFIK KUANTITATIF -->
    <div class="card card-custom p-4 shadow-sm bg-white border-0">
        <h5 class="fw-bold mb-1 text-dark d-flex align-items-center" style="font-size: 1.1rem;">
            <i class="bi bi-pie-chart-fill me-2 text-success"></i>Komposisi Elemen Grafik Kuantitatif
        </h5>
        <p class="text-muted small mb-4">*Cukup ketik jumlah jiwa riil saat ini. Nilai persentase (%) akan dikalkulasi otomatis oleh sistem.</p>
        
        <div class="row g-3">
            <div class="col-xl-3 col-md-6 border-end-custom">
                <h6 class="text-purple-premium border-bottom pb-2 fw-bold small"><i class="bi bi-gender-ambiguous me-1"></i>Rasio Jenis Kelamin</h6>
                <div class="mb-2"><label class="small fw-bold text-muted">Jiwa Laki-laki</label><input type="number" name="jiwa_pria" class="form-control form-control-custom" value="<?php echo $stats['Laki-laki']['jumlah'] ?? 0; ?>" min="0" step="1" required></div>
                <div class="mb-2"><label class="small fw-bold text-muted">Jiwa Perempuan</label><input type="number" name="jiwa_wanita" class="form-control form-control-custom" value="<?php echo $stats['Perempuan']['jumlah'] ?? 0; ?>" min="0" step="1" required></div>
            </div>

            <div class="col-xl-3 col-md-6 border-end-custom">
                <h6 class="text-success border-bottom pb-2 fw-bold small"><i class="bi bi-water me-1"></i>Sakramen Baptis</h6>
                <div class="mb-2"><label class="small fw-bold text-muted">Sudah Baptis (Jiwa)</label><input type="number" name="jiwa_baptis" class="form-control form-control-custom" value="<?php echo $stats['Sudah Baptis']['jumlah'] ?? 0; ?>" min="0" step="1" required></div>
                <div class="mb-2"><label class="small fw-bold text-muted">Belum Baptis (Jiwa)</label><input type="number" name="jiwa_belum_baptis" class="form-control form-control-custom" value="<?php echo $stats['Belum Baptis']['jumlah'] ?? 0; ?>" min="0" step="1" required></div>
            </div>

            <div class="col-xl-3 col-md-6 border-end-custom">
                <h6 class="text-info border-bottom pb-2 fw-bold small"><i class="bi bi-patch-check-fill me-1"></i>Peneguhan Sidi</h6>
                <div class="mb-2"><label class="small fw-bold text-muted">Sudah Sidi (Jiwa)</label><input type="number" name="jiwa_sidi" class="form-control form-control-custom" value="<?php echo $stats['Sudah Sidi']['jumlah'] ?? 0; ?>" min="0" step="1" required></div>
                <div class="mb-2"><label class="small fw-bold text-muted">Belum Sidi (Jiwa)</label><input type="number" name="jiwa_belum_sidi" class="form-control form-control-custom" value="<?php echo $stats['Belum Sidi']['jumlah'] ?? 0; ?>" min="0" step="1" required></div>
            </div>

            <div class="col-xl-3 col-md-6">
                <h6 class="text-warning border-bottom pb-2 fw-bold small"><i class="bi bi-diagram-3-fill me-1"></i>BIPRA & Lansia</h6>
                <div class="row g-2">
                    <div class="col-6"><label class="small fw-bold text-muted">P/KB</label><input type="number" name="jiwa_pkb" class="form-control form-control-custom" value="<?php echo $stats['P/KB']['jumlah'] ?? 0; ?>" min="0" step="1" required></div>
                    <div class="col-6"><label class="small fw-bold text-muted">W/KI</label><input type="number" name="jiwa_wki" class="form-control form-control-custom" value="<?php echo $stats['W/KI']['jumlah'] ?? 0; ?>" min="0" step="1" required></div>
                    <div class="col-6"><label class="small fw-bold text-muted">Pemuda</label><input type="number" name="jiwa_pemuda" class="form-control form-control-custom" value="<?php echo $stats['Pemuda']['jumlah'] ?? 0; ?>" min="0" step="1" required></div>
                    <div class="col-6"><label class="small fw-bold text-muted">Remaja</label><input type="number" name="jiwa_remaja" class="form-control form-control-custom" value="<?php echo $stats['Remaja']['jumlah'] ?? 0; ?>" min="0" step="1" required></div>
                    <div class="col-6"><label class="small fw-bold text-muted">ASM</label><input type="number" name="jiwa_asm" class="form-control form-control-custom" value="<?php echo $stats['ASM']['jumlah'] ?? 0; ?>" min="0" step="1" required></div>
                    <div class="col-6"><label class="small fw-bold text-muted">Lansia</label><input type="number" name="jiwa_lansia" class="form-control form-control-custom" value="<?php echo $stats['Lansia']['jumlah'] ?? 0; ?>" min="0" step="1" required></div>
                </div>
            </div>
        </div>

        <hr class="my-4">
        <div class="text-end">
            <button type="submit" class="btn btn-purple-admin px-5 shadow-sm">
                <i class="bi bi-calculator me-1"></i> Simpan & Hitung Persentase
            </button>
        </div>
    </div>
</form>

<style>
    @keyframes blink { 0% { opacity: 1; } 50% { opacity: 0.5; } 100% { opacity: 1; } }
    .blink-alert { animation: blink 1s linear 2; }
    
    /* Style sederhana untuk status loading */
    .btn-loading { pointer-events: none; opacity: 0.7; }
</style>

<script>
document.getElementById('formDataJemaat').addEventListener('submit', function(e) {
    e.preventDefault(); 
    
    const submitBtn = this.querySelector('button[type="submit"]');
    const originalBtnText = submitBtn.innerHTML;
    
    // Tampilkan status loading pada tombol
    submitBtn.classList.add('btn-loading');
    submitBtn.innerHTML = '<i class="bi bi-hourglass-split me-1"></i> Menyimpan...';

    let formData = new FormData(this);
    formData.append('update_data_jemaat', 'true');

    fetch('proses/proses_datajemaat.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        // Kembalikan tombol ke kondisi semula
        submitBtn.classList.remove('btn-loading');
        submitBtn.innerHTML = originalBtnText;

        if(data.status === 'success') {
            const placeholder = document.getElementById('alertPlaceholder');
            
            // Menampilkan alert yang stay dan blink
            placeholder.innerHTML = `
                <div id="dynamic-alert" class="alert alert-primary alert-dismissible fade show shadow border-0 py-3 mb-4 blink-alert" 
                     style="background: #eef2ff; color: #4338ca; border-left: 5px solid #4338ca !important;">
                    <i class="bi bi-stars me-2"></i> <strong>Pemberitahuan:</strong> Data Jemaat berhasil diperbarui!
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>`;
        }
    })
    .catch(error => {
        submitBtn.classList.remove('btn-loading');
        submitBtn.innerHTML = originalBtnText;
        alert('Terjadi kesalahan, silakan coba lagi.');
        console.error('Error:', error);
    });
});
</script>