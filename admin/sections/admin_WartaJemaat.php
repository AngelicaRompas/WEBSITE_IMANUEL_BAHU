<h4 class="section-header"><i class="bi bi-file-earmark-text-fill me-2 text-primary"></i>Pengaturan Manajemen Warta Digital</h4>

<div class="card card-custom p-4">
    <h5 class="fw-bold mb-4 text-dark border-bottom pb-2">Input Data Warta & Jadwal Petugas</h5>
    <form action="proses/proses_warta.php" method="POST" enctype="multipart/form-data">
        
        <!-- Informasi Umum -->
        <div class="row g-3 mb-4">
            <div class="col-md-6">
                <label class="form-label small fw-bold">Tanggal Pelaksanaan Ibadah</label>
                <input type="date" name="tanggal" class="form-control" value="<?php echo date('Y-m-d'); ?>" required>
            </div>
            <div class="col-md-6">
                <label class="form-label small fw-bold">Tema Mingguan</label>
                <input type="text" name="tema_mingguan" class="form-control" placeholder="Masukkan tema ibadah minggu" required>
            </div>
        </div>

        <!-- Detail Petugas per Sesi -->
       <?php foreach (['I', 'II', 'III'] as $sesi): ?>
            <div class="bg-light p-3 rounded mb-4 border-start border-primary border-4">
                <h6 class="fw-bold text-primary mb-3"><i class="bi bi-calendar-event me-2"></i>Ibadah Minggu Sesi <?php echo $sesi; ?></h6>
                <div class="row g-3">
                    <!-- Baris 1 -->
                    <div class="col-md-4">
                        <label class="form-label small fw-bold">Nama Khadim</label>
                        <input type="text" name="khadim_sesi_<?php echo $sesi; ?>" class="form-control">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label small fw-bold">Foto Khadim</label>
                        <input type="file" name="foto_sesi_<?php echo $sesi; ?>" class="form-control" accept="image/*">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label small fw-bold">Penerima Jemaat</label>
                        <input type="text" name="penerima_sesi_<?php echo $sesi; ?>" class="form-control">
                    </div>
                    <!-- Baris 2 -->
                    <div class="col-md-6">
                        <label class="form-label small fw-bold">Doa & Pembacaan</label>
                        <input type="text" name="doa_sesi_<?php echo $sesi; ?>" class="form-control">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label small fw-bold">Puji-pujian</label>
                        <input type="text" name="puji_sesi_<?php echo $sesi; ?>" class="form-control">
                    </div>
                </div>
            </div>
            <?php endforeach; ?>

        <!-- Informasi Umum Tambahan -->
        <div class="row g-3 mb-4">
            <div class="col-md-6">
                <label class="form-label small fw-bold">Nas Pembacaan Alkitab</label>
                <input type="text" name="pembacaan_alkitab" class="form-control" required>
            </div>
            <div class="col-md-6">
                <label class="form-label small fw-bold">File Warta (PDF)</label>
                <input type="file" name="file_pdf" class="form-control" accept="application/pdf" required>
            </div>
        </div>

        <button type="submit" name="upload_warta" class="btn btn-primary btn-pill px-5 shadow-sm">
            <i class="bi bi-cloud-arrow-up-fill me-2"></i>Simpan Warta Digital
        </button>
    </form>
</div>