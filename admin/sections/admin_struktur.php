<?php
if (!isset($kolomBerikutnya)) {
    $kolomBerikutnya = 29; 
}
?>

<link rel="stylesheet" href="assets/css/style-adminStruktur.css">
<link rel="stylesheet" href="assets/css/style-admin-konten.css?v=<?php echo time(); ?>">

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
                    <div class="input-group"><span class="input-group-text input-group-text-custom">Pnt.</span>
                        <input type="text" name="nama_penatua" class="form-control form-control-custom" placeholder="Nama Penatua Baru" required>
                    </div>
                </div>
                <div class="mb-4">
                    <label class="form-label small fw-bold text-secondary">Nama Lengkap Diaken</label>
                    <div class="input-group"><span class="input-group-text input-group-text-custom">Dkn.</span>
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
</div>

<div class="row g-4 mt-1">
    <div class="col-lg-6">
        <div class="card card-custom p-4 shadow-sm h-100 border-0" style="background-color: #fcfaff;">
            <h6 class="fw-bold mb-2 text-dark">Tambah Posisi Jabatan Inti Baru</h6>
            <form class="ajax-form-struktur">
                <input type="hidden" name="jenis_update" value="tambah_bpmj">
                <input type="text" name="jabatan_baru" class="form-control mb-3" placeholder="Contoh: Anggota BPMJ" required>
                <button type="submit" name="simpan_tambah_bpmj" class="btn btn-outline-purple-admin w-100">Daftarkan Jabatan</button>
            </form>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="card card-custom p-4 shadow-sm h-100 border-0" style="background-color: #fdfdfd;">
            <h6 class="fw-bold mb-2 text-dark">Ekspansi Wilayah Kolom Baru</h6>
            <form class="ajax-form-struktur">
                <input type="hidden" name="jenis_update" value="tambah_kolom">
                <input type="hidden" name="nomor_kolom_baru" value="<?php echo $kolomBerikutnya; ?>">
                <div class="row g-2 mb-3">
                    <div class="col-6"><input type="text" name="nama_penatua_awal" class="form-control" placeholder="Nama Pnt" required></div>
                    <div class="col-6"><input type="text" name="nama_diaken_awal" class="form-control" placeholder="Nama Dkn" required></div>
                </div>
                <button type="submit" name="simpan_tambah_kolom" class="btn btn-outline-success w-100">Resmikan Kolom <?php echo $kolomBerikutnya; ?></button>
            </form>
        </div>
    </div>
</div>

<!-- FORM KOMISI (ACTION SUDAH DIPERBAIKI KE PROSES_STRUKTUR.PHP) -->
<div class="card card-custom p-4 mt-4 shadow-sm bg-white border-0">
    <h6 class="fw-bold mb-3 text-purple-premium"><i class="bi bi-person-plus-fill me-2"></i>Tambah Anggota Komisi Baru</h6>
    <form action="proses/proses_struktur.php" method="POST">
    <div class="row g-3">
        <div class="col-md-3"><input type="text" name="nama" class="form-control" placeholder="Nama Lengkap" required></div>
        <div class="col-md-3"><input type="text" name="jabatan" class="form-control" placeholder="Jabatan" required></div>
        <div class="col-md-3">
            <select name="kategori" class="form-select" required>
                <?php 
                $kategori_list = ["Penasihat BPMJ", "Komisi Pengawas Perbendaharaan", "Komisi Pelayanan Pria Kaum Bapa", "Komisi Pelayanan Wanita Kaum Ibu", "Komisi Pelayanan Pemuda", "Komisi Pelayanan Remaja", "Komisi Pelayanan Anak", "Komisi Kategorial Lansia", "Komisi Liturgi & Kesenian Kerja (Musik)", "Komisi Liturgi & Kesenian Kerja (Paduan Suara)", "Komisi Liturgi & Kesenian Kerja (Liturgi & Kantoria)", "Komisi Liturgi & Kesenian Kerja (Rebana, Banners & Tarian Kreatif)", "Komisi Pendidikan", "Komisi Pengembalaan", "Komisi Pelayanan Doa & Pekabaran Injil", "Komisi Kesehatan", "Komisi Pembangunan", "Komisi Rumah Tangga & Kerja Bakti", "Komisi Pemberdayaan Sumber Daya"];
                foreach($kategori_list as $kat): echo "<option value='$kat'>$kat</option>"; endforeach; 
                ?>
            </select>
        </div>
        <div class="col-md-3"><button type="submit" name="tambah_anggota_komisi" class="btn btn-success w-100">Simpan Anggota</button></div>
    </div>
</form>
</div>

<div class="card card-custom p-4 mt-4 shadow-sm bg-white border-0">
    <h6 class="fw-bold mb-3 text-purple-premium"><i class="bi bi-card-list me-2"></i>Daftar Anggota Komisi</h6>
    <div class="table-responsive">
        <table class="table table-hover">
            <thead><tr><th>Nama</th><th>Jabatan</th><th>Kategori</th><th class="text-center">Aksi</th></tr></thead>
            <tbody>
                <?php
                $anggota = mysqli_query($koneksi, "SELECT * FROM struktur_organisasi WHERE kategori NOT IN ('bpmj','pelsus') ORDER BY kategori");
                while($a = mysqli_fetch_assoc($anggota)):
                ?>
                <tr>
                    <td><?php echo htmlspecialchars($a['nama']); ?></td>
                    <td><?php echo htmlspecialchars($a['jabatan']); ?></td>
                    <td><?php echo htmlspecialchars($a['kategori']); ?></td>
                    <td class="text-center">
                        <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editAnggota<?php echo $a['id']; ?>"><i class="bi bi-pencil-square"></i></button>
                        <a href="proses/proses_struktur.php?hapus_komisi=1&id=<?php echo $a['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Hapus?')"><i class="bi bi-trash"></i></a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- MODAL EDIT (ACTION DIARAHKAN KE PROSES_STRUKTUR.PHP) -->
<?php
$anggota_modal = mysqli_query($koneksi, "SELECT * FROM struktur_organisasi WHERE kategori NOT IN ('bpmj','pelsus')");
while($a = mysqli_fetch_assoc($anggota_modal)):
?>
<div class="modal fade" id="editAnggota<?php echo $a['id']; ?>" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content card bg-white border-0 p-3 shadow" style="border-radius: 20px;">
            <form action="proses/proses_struktur.php" method="POST">
                <input type="hidden" name="id" value="<?php echo $a['id']; ?>">
                <div class="modal-header border-0"><h5 class="modal-title fw-bold">Edit Anggota</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
                <div class="modal-body">
                    <div class="mb-3"><label>Nama</label><input type="text" name="nama" class="form-control" value="<?php echo htmlspecialchars($a['nama']); ?>" required></div>
                    <div class="mb-3"><label>Jabatan</label><input type="text" name="jabatan" class="form-control" value="<?php echo htmlspecialchars($a['jabatan']); ?>" required></div>
                    <div class="mb-3"><label>Kategori</label>
                        <select name="kategori" class="form-select" required>
                            <?php 
                            foreach($kategori_list as $kat): ?>
                                <option value="<?php echo $kat; ?>" <?php echo ($a['kategori'] == $kat) ? 'selected' : ''; ?>><?php echo $kat; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="modal-footer"><button type="submit" name="edit_anggota_komisi" class="btn btn-warning w-100">Update Data</button></div>
            </form>
        </div>
    </div>
</div>
<?php endwhile; ?>

<script>
document.querySelectorAll('.ajax-form-struktur').forEach(function(form) {
    form.addEventListener('submit', function(e) {
        e.preventDefault(); 
        const currentForm = e.target;
        const formData = new FormData(currentForm);
        const submitBtn = currentForm.querySelector('button[type="submit"]');
        formData.append(submitBtn.getAttribute('name'), "1"); 

        fetch('proses/proses_struktur.php', { method: 'POST', body: formData })
        .then(response => response.json())
        .then(data => {
            document.getElementById('alertMessageAjax').innerText = data.message;
            document.getElementById('ajaxAlertContainer').classList.remove('d-none');
            window.scrollTo({ top: 0, behavior: 'smooth' });
        });
    });
});
function closeAjaxAlert() { document.getElementById('ajaxAlertContainer').classList.add('d-none'); }
</script>