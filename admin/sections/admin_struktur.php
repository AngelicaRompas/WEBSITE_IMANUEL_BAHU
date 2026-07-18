<?php
// Memastikan variabel pengaman deteksi urutan tertinggi kolom sudah terinisialisasi
if (!isset($kolomBerikutnya)) {
    $kolomBerikutnya = 29; // Nilai fallback aman jika file luar tidak melewatinya
}
?>

<link rel="stylesheet" href="assets/css/style-adminStruktur.css">

<!-- Wadah Alert Dinamis dengan Efek Animasi Fade (Kedipan) Bawaan Bootstrap -->
<div id="ajaxAlertContainer" class="mb-4 d-none">
    <div id="dynamic-alert-ajax" class="alert alert-dismissible fade show shadow border-0 py-3" 
         style="background: #eef2ff; color: #4338ca; border-left: 5px solid #4338ca !important; transition: opacity 0.15s linear;">
        <i id="alertIconAjax" class="bi bi-stars me-2"></i> 
        <strong>Pemberitahuan:</strong> <span id="alertMessageAjax"></span>
        <button type="button" class="btn-close" onclick="closeAjaxAlert()"></button>
    </div>
</div>

<h4 class="section-header-kurva section-header-struktur mb-4 text-purple-premium">
    <i class="bi bi-diagram-3-fill me-2"></i>Manajemen Komponen Struktur Organisasi
</h4>

<div class="row g-4">
    
    <!-- FORM 1: EDIT JALUR BPMJ & PENDETA -->
    <div class="col-lg-6">
        <div class="card card-custom p-4 shadow-sm h-100 border-0 bg-white">
            <h5 class="fw-bold mb-4 text-dark border-bottom pb-3 d-flex align-items-center" style="font-size: 1.1rem;">
                <i class="bi bi-pencil-square text-purple-premium me-2"></i>Edit Jajaran BPMJ & Pendeta
            </h5>
            
            <form class="ajax-form-struktur" enctype="multipart/form-data">
                <input type="hidden" name="jenis_update" value="edit_bpmj">
                
                <div class="mb-3">
                    <label class="form-label small fw-bold text-secondary">Pilih Jabatan Struktural</label>
                    <select name="jabatan" class="form-select form-select-custom" required>
                        <?php 
                        $q_bpmj = mysqli_query($koneksi, "SELECT jabatan FROM struktur_organisasi WHERE kategori='bpmj' ORDER BY id ASC");
                        while($b = mysqli_fetch_assoc($q_bpmj)):
                        ?>
                            <option value="<?php echo $b['jabatan']; ?>"><?php echo $b['jabatan']; ?></option>
                        <?php endwhile; ?>
                    </select>
                </div>
                
                <div class="mb-3">
                    <label class="form-label small fw-bold text-secondary">Nama Lengkap & Gelar Baru</label>
                    <input type="text" name="nama_lengkap" class="form-control form-control-custom" placeholder="Contoh: Pdt. John Doe, M.Th" required>
                </div>
                
                <div class="mb-4">
                    <label class="form-label small fw-bold text-secondary">Ganti Berkas Foto Profil</label>
                    <input type="file" name="foto_profil" class="form-control form-control-custom" accept="image/*">
                </div>
                
                <button type="submit" name="simpan_edit_bpmj" class="btn btn-purple-admin w-100 fw-bold rounded-3 py-2.5 shadow-sm d-flex align-items-center justify-content-center">
                    <span class="spinner-border spinner-border-sm me-2 d-none btn-spinner" role="status"></span>
                    <i class="bi bi-check-circle me-1 btn-icon"></i> <span class="btn-text">Perbarui Pelayan Inti</span>
                </button>
            </form>
        </div>
    </div>

    <!-- FORM 2: EDIT PENATUA & DIAKEN PER KOLOM -->
    <div class="col-lg-6">
        <div class="card card-custom p-4 shadow-sm h-100 border-0 bg-white">
            <h5 class="fw-bold mb-4 text-dark border-bottom pb-3 d-flex align-items-center" style="font-size: 1.1rem;">
                <i class="bi bi-pencil-square text-success me-2"></i>Edit Penatua & Diaken per Kolom
            </h5>
            
            <form class="ajax-form-struktur">
                <input type="hidden" name="jenis_update" value="edit_pelsus">
                
                <div class="mb-3">
                    <label class="form-label small fw-bold text-secondary">Pilih Target Kolom Pelayanan</label>
                    <select name="nomor_kolom" class="form-select form-select-custom" required>
                        <?php 
                        $jumlah_kolom_riil = ($kolomBerikutnya - 1 > 0) ? ($kolomBerikutnya - 1) : 28; 
                        for($i = 1; $i <= $jumlah_kolom_riil; $i++):
                        ?>
                            <option value="<?php echo $i; ?>">Kolom <?php echo $i; ?></option>
                        <?php endfor; ?>
                    </select>
                </div>
                
                <div class="mb-3">
                    <label class="form-label small fw-bold text-secondary">Nama Lengkap Penatua</label>
                    <div class="input-group">
                        <span class="input-group-text input-group-text-custom">Pnt.</span>
                        <input type="text" name="nama_penatua" class="form-control form-control-custom" placeholder="Nama Penatua Baru" required>
                    </div>
                </div>
                
                <div class="mb-4">
                    <label class="form-label small fw-bold text-secondary">Nama Lengkap Diaken</label>
                    <div class="input-group">
                        <span class="input-group-text input-group-text-custom">Dkn.</span>
                        <input type="text" name="nama_diaken" class="form-control form-control-custom" placeholder="Nama Diaken Baru" required>
                    </div>
                </div>
                
                <button type="submit" name="simpan_edit_pelsus" class="btn btn-success w-100 fw-bold rounded-3 py-2.5 shadow-sm text-white d-flex align-items-center justify-content-center">
                    <span class="spinner-border spinner-border-sm me-2 d-none btn-spinner" role="status"></span>
                    <i class="bi bi-check-circle me-1 btn-icon"></i> <span class="btn-text">Simpan Pelayan Kolom</span>
                </button>
            </form>
        </div>
    </div>

    <!-- FORM 3: TAMBAH POSISI JABATAN INTI BARU -->
    <div class="col-lg-6">
        <div class="card card-custom p-4 shadow-sm h-100 border-0" style="background-color: #fcfaff; border: 1px solid rgba(111, 66, 193, 0.08) !important;">
            <h6 class="fw-bold mb-2 text-dark d-flex align-items-center" style="font-size: 1rem;">
                <i class="bi bi-person-plus-fill text-purple-premium me-2"></i>Tambah Posisi Jabatan Inti Baru
            </h6>
            <p class="small text-muted mb-4">Gunakan form ini untuk menambah personil pelayan baru di luar struktur utama saat ini.</p>
            
            <form class="ajax-form-struktur">
                <input type="hidden" name="jenis_update" value="tambah_bpmj">

                <div class="mb-4">
                    <label class="form-label small fw-bold text-secondary">Nama Jabatan Baru</label>
                    <input type="text" name="jabatan_baru" class="form-control form-control-custom bg-white" placeholder="Contoh: Anggota BPMJ" required>
                </div>

                <button type="submit" name="simpan_tambah_bpmj" class="btn btn-outline-purple-admin rounded-pill w-100 fw-bold py-2.5 d-flex align-items-center justify-content-center">
                    <span class="spinner-border spinner-border-sm me-2 d-none btn-spinner" role="status"></span>
                    <i class="bi bi-plus-circle-fill me-2 btn-icon"></i> <span class="btn-text">Daftarkan Jabatan Baru</span>
                </button>
            </form>
        </div>
    </div>

    <!-- FORM 4: EKSPANSI WILAYAH KOLOM BARU -->
    <div class="col-lg-6">
        <div class="card card-custom p-4 shadow-sm h-100 border-0" style="background-color: #fdfdfd; border: 1px solid rgba(25, 135, 84, 0.08) !important;">
            <h6 class="fw-bold mb-2 text-dark d-flex align-items-center" style="font-size: 1rem;">
                <i class="bi bi-node-plus-fill text-success me-2"></i>Ekspansi Wilayah Kolom Baru
            </h6>
            <p class="small text-muted mb-4">Sistem mendeteksi tingkat urutan tertinggi. Klik resmikan untuk membuka gerbang koordinasi kolom baru.</p>
            
            <form class="ajax-form-struktur">
                <input type="hidden" name="jenis_update" value="tambah_kolom">
                <input type="hidden" name="nomor_kolom_baru" value="<?php echo $kolomBerikutnya; ?>">
                
                <div class="mb-3">
                    <label class="small text-secondary fw-bold mb-1 d-block">Nomor Wilayah Kolom yang Akan Dibuat:</label>
                    <input type="text" class="form-control form-control-custom bg-light fw-bold text-success fs-5 text-center py-2" value="KOLOM <?php echo $kolomBerikutnya; ?>" readonly>
                </div>
                
                <div class="row g-2 mb-4">
                    <div class="col-6">
                        <input type="text" name="nama_penatua_awal" class="form-control form-control-custom bg-white" placeholder="Nama Penatua Awal" required>
                    </div>
                    <div class="col-6">
                        <input type="text" name="nama_diaken_awal" class="form-control form-control-custom bg-white" placeholder="Nama Diaken Awal" required>
                    </div>
                </div>
                
                <button type="submit" name="simpan_tambah_kolom" class="btn btn-outline-success rounded-pill w-100 fw-bold py-2.5 d-flex align-items-center justify-content-center">
                    <span class="spinner-border spinner-border-sm me-2 d-none btn-spinner" role="status"></span>
                    <i class="bi bi-plus-circle-fill me-2 btn-icon"></i> <span class="btn-text">Resmikan Kolom <?php echo $kolomBerikutnya; ?></span>
                </button>
            </form>
        </div>
    </div>

</div>

<!-- JavaScript Interseptor AJAX Submit -->
<script>
document.querySelectorAll('.ajax-form-struktur').forEach(function(form) {
    form.addEventListener('submit', function(e) {
        e.preventDefault(); 

        const currentForm = e.target;
        const formData = new FormData(currentForm);
        
        const submitBtn = currentForm.querySelector('button[type="submit"]');
        const submitBtnName = submitBtn.getAttribute('name');
        formData.append(submitBtnName, "1"); 

        const spinner = submitBtn.querySelector('.btn-spinner');
        const icon = submitBtn.querySelector('.btn-icon');
        const alertWrapper = document.getElementById('ajaxAlertContainer');
        const alertBox = document.getElementById('dynamic-alert-ajax');
        const alertMsg = document.getElementById('alertMessageAjax');
        const alertIcon = document.getElementById('alertIconAjax');

        // Mengaktifkan status loading tombol
        submitBtn.disabled = true;
        if (spinner) spinner.classList.remove('d-none');
        if (icon) icon.classList.add('d-none');

        // RESET STATE ANIMASI: Sembunyikan dan bersihkan kelas animasi lama
        alertWrapper.classList.add('d-none');
        alertBox.classList.remove('show');

        fetch('proses/proses_struktur.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            submitBtn.disabled = false;
            if (spinner) spinner.classList.add('d-none');
            if (icon) icon.classList.remove('d-none');

            // Set isi pesan teks
            alertMsg.innerText = data.message;

            // Setel gaya visual warna sesuai status respons data
            if (data.status === 'success') {
                alertBox.style.setProperty('background', '#eef2ff', 'important');
                alertBox.style.setProperty('color', '#4338ca', 'important');
                alertBox.style.setProperty('border-left', '5px solid #4338ca', 'important');
                alertIcon.className = "bi bi-stars me-2";
                
                currentForm.querySelectorAll('input[type="text"]').forEach(input => input.value = "");
            } else {
                alertBox.style.setProperty('background', '#fffbeb', 'important');
                alertBox.style.setProperty('color', '#b45309', 'important');
                alertBox.style.setProperty('border-left', '5px solid #b45309', 'important');
                alertIcon.className = "bi bi-exclamation-triangle-fill me-2";
            }
            
            // PEMICU ANIMASI UTAMA: Lepas pembungkus display, lalu picu efek berkedip fade-show
            alertWrapper.classList.remove('d-none'); 
            setTimeout(() => {
                alertBox.classList.add('show');
            }, 50);
            
            // Gulung halaman ke atas agar user langsung melihat kedipan alertnya
            window.scrollTo({ top: 0, behavior: 'smooth' });
        })
        .catch(error => {
            submitBtn.disabled = false;
            if (spinner) spinner.classList.add('d-none');
            if (icon) icon.classList.remove('d-none');

            alertMsg.innerText = "Terjadi kendala saat memperbarui data server lokal.";
            
            // Pemicu Animasi saat Error Koneksi
            alertWrapper.classList.remove('d-none');
            setTimeout(() => {
                alertBox.classList.add('show');
            }, 50);
            
            alertBox.style.setProperty('background', '#fef2f2', 'important');
            alertBox.style.setProperty('color', '#b91c1c', 'important');
            alertBox.style.setProperty('border-left', '5px solid #b91c1c', 'important');
            alertIcon.className = "bi bi-exclamation-octagon-fill me-2";
            
            window.scrollTo({ top: 0, behavior: 'smooth' });
        });
    });
});

function closeAjaxAlert() {
    const alertBox = document.getElementById('dynamic-alert-ajax');
    alertBox.classList.remove('show');
    setTimeout(() => {
        document.getElementById('ajaxAlertContainer').classList.add('d-none');
    }, 150);
}

document.querySelectorAll('.nav-link-admin').forEach(link => {
    link.addEventListener('click', function() {
        closeAjaxAlert();
    });
});
</script>