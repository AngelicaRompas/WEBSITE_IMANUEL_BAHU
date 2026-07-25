<?php
// admin_warta.php
?>

<link rel="stylesheet" href="assets/css/style-adminWarta.css">

<!-- Judul Menu Utama -->
<h4 class="section-header">
    <i class="bi bi-file-earmark-text-fill me-2 text-purple-premium"></i>Pengaturan Manajemen Warta Digital
</h4>

<!-- Kartu Utama Form Input -->
<div class="card card-custom p-4">
    <h5 class="fw-bold mb-4 text-dark border-bottom pb-2 d-flex align-items-center" style="font-size: 1.1rem;">
        <i class="bi bi-pencil-square me-2 text-purple-premium"></i>Input Data Warta & Jadwal Petugas
    </h5>
    
    <form action="proses/proses_warta.php" method="POST" enctype="multipart/form-data">
        
        <!-- Bagian 1: Informasi Umum -->
        <div class="row g-3 mb-4">
            <div class="col-md-6">
                <label class="form-label small fw-bold text-secondary">Tanggal Pelaksanaan Ibadah</label>
                <input type="date" name="tanggal" class="form-control form-control-custom" value="<?php echo date('Y-m-d'); ?>" required>
            </div>
            <div class="col-md-6">
                <label class="form-label small fw-bold text-secondary">Tema Mingguan</label>
                <input type="text" name="tema_mingguan" class="form-control form-control-custom" placeholder="Masukkan tema ibadah minggu" required>
            </div>
        </div>

        <!-- Bagian 2: Detail Petugas per Sesi (Looping Sesi I, II, III) -->
        <?php foreach (['I', 'II', 'III'] as $sesi): ?>
            <div class="p-4 mb-4 session-box-purple border border-start-0 border-end-0 border-top-0 border-bottom-0">
                <h6 class="fw-bold text-purple-premium mb-3 d-flex align-items-center" style="font-size: 0.95rem; letter-spacing: 0.3px;">
                    <i class="bi bi-calendar-event-fill me-2"></i>Ibadah Minggu Sesi <?php echo $sesi; ?>
                </h6>
                
                <div class="row g-3">
                    <!-- Baris 1 Konten Sesi -->
                    <div class="col-md-4">
                        <label class="form-label small fw-bold text-muted">Nama Khadim</label>
                        <input type="text" name="khadim_sesi_<?php echo $sesi; ?>" class="form-control form-control-custom" placeholder="Nama pengkhotbah">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label small fw-bold text-muted">Foto Khadim</label>
                        <input type="file" name="foto_sesi_<?php echo $sesi; ?>" class="form-control form-control-custom" accept="image/*">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label small fw-bold text-muted">Penerima Jemaat</label>
                        <input type="text" name="penerima_sesi_<?php echo $sesi; ?>" class="form-control form-control-custom" placeholder="Nama petugas penerima">
                    </div>
                    
                    <!-- Baris 2 Konten Sesi -->
                    <div class="col-md-6">
                        <label class="form-label small fw-bold text-muted">Doa & Pembacaan</label>
                        <input type="text" name="doa_sesi_<?php echo $sesi; ?>" class="form-control form-control-custom" placeholder="Petugas doa / pembacaan">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label small fw-bold text-muted">Puji-pujian</label>
                        <input type="text" name="puji_sesi_<?php echo $sesi; ?>" class="form-control form-control-custom" placeholder="Paduan suara / Kolom / Pelsis">
                    </div>

                    <!-- Baris 3 Konten Sesi (Fitur Baru) -->
                    <div class="col-md-6">
                        <label class="form-label small fw-bold text-muted">KPI</label>
                        <input type="text" name="kpi_sesi_<?php echo $sesi; ?>" class="form-control form-control-custom" placeholder="Nama KPI">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label small fw-bold text-muted">Doa Persembahan</label>
                        <input type="text" name="doapersembahan_sesi_<?php echo $sesi; ?>" class="form-control form-control-custom" placeholder="Petugas doa persembahan">
                    </div>
                </div>
            </div>
        <?php endforeach; ?>

        <!-- Bagian 3: Informasi Dokumen Tambahan -->
        <div class="row g-3 mb-4">
            <div class="col-md-6">
                <label class="form-label small fw-bold text-secondary">Nas Pembacaan Alkitab</label>
                <input type="text" name="pembacaan_alkitab" class="form-control form-control-custom" placeholder="Contoh: Efesus 2:1-10" required>
            </div>
            <div class="col-md-6">
                <label class="form-label small fw-bold text-secondary">File Lembar Warta (PDF)</label>
                <input type="file" name="file_pdf" class="form-control form-control-custom" accept="application/pdf" required>
            </div>
        </div>

        <!-- Tombol Aksi Eksekusi -->
        <div class="mt-4 pt-2">
            <button type="submit" name="upload_warta" class="btn btn-purple-admin shadow-sm d-inline-flex align-items-center justify-content-center">
                <i class="bi bi-cloud-arrow-up-fill me-2 fs-5"></i> Simpan Warta Digital
            </button>
        </div>
        
    </form>
</div>