<?php
// admin_renungan.php
// File ini dapat langsung dimasukkan atau di-include ke dalam dashboard utama admin Anda
?>

<link rel="stylesheet" href="assets/css/style-adminRenungan.css">

<!-- Judul Kontrol Utama Menu -->
<h4 class="section-header-renungan mb-4 text-purple-premium">
    <i class="bi bi-book-half me-2"></i>Manajemen Renungan Harian Jemaat
</h4>

<!-- Kartu Utama Form Pengisian Renungan -->
<div class="card card-custom p-4 shadow-sm border-0 bg-white">
    <h5 class="fw-bold mb-4 text-dark border-bottom pb-2 d-flex align-items-center" style="font-size: 1.1rem;">
        <i class="bi bi-plus-circle me-2 text-purple-premium"></i>Input Teks Renungan Harian Baru
    </h5>
    
    <form action="proses/proses_renungan.php" method="POST">
        
        <!-- Baris 1: Pengaturan Waktu & Sumber Firman -->
        <div class="row g-3 mb-3">
            <div class="col-md-6">
                <label class="form-label small fw-bold text-secondary">Tanggal Renungan</label>
                <input type="date" name="tanggal" class="form-control form-control-custom" value="<?php echo date('Y-m-d'); ?>" required>
            </div>
            <div class="col-md-6">
                <label class="form-label small fw-bold text-secondary">Nas Pembacaan Alkitab</label>
                <input type="text" name="nas_alkitab" class="form-control form-control-custom" placeholder="Contoh: Efesus 6:10-18" required>
            </div>
        </div>

        <!-- Baris 2: Topik Utama -->
        <div class="mb-3">
            <label class="form-label small fw-bold text-secondary">Judul Renungan</label>
            <input type="text" name="judul" class="form-control form-control-custom" placeholder="Masukkan judul tema renungan" required>
        </div>

        <!-- Baris 3: Inti Ulasan Kotbah / Renungan -->
        <div class="mb-3">
            <label class="form-label small fw-bold text-secondary">Isi Lengkap Renungan Firman Tuhan</label>
            <textarea name="isi_renungan" class="form-control form-control-custom" rows="8" placeholder="Tuliskan rincian khotbah pembinaan, poin perenungan, dan aplikasi kehidupan praktis jemaat..." required></textarea>
        </div>
        
        <!-- Baris 4: Pokok Doa Aplikasi -->
        <div class="mb-4">
            <label class="form-label small fw-bold text-secondary">Teks Doa Penutup</label>
            <textarea name="doa" class="form-control form-control-custom" rows="3" placeholder="Tuliskan pokok doa penutup renungan..." required></textarea>
        </div>

        <!-- Tombol Kirim / Eksekusi Form -->
        <div class="pt-2">
            <button type="submit" name="simpan_renungan" class="btn btn-purple-admin shadow-sm d-inline-flex align-items-center justify-content-center">
                <i class="bi bi-journal-check me-2 fs-5"></i> Publikasikan Renungan Hari Ini
            </button>
        </div>
        
    </form>
</div>