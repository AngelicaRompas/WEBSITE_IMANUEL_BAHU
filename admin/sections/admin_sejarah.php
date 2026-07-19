<?php
// admin_sejarah.php
?>

<link rel="stylesheet" href="assets/css/style-adminProfil.css">

<h4 class="section-header"><i class="bi bi-person-gear me-2 text-primary"></i>Pengaturan Menu Profil & Sejarah</h4>

<div id="alertPlaceholder"></div>

<!-- Narasi Sejarah -->
<div class="card card-custom p-4 mb-5">
    <h5 class="fw-bold mb-3 text-dark d-flex align-items-center" style="font-size: 1.1rem;">
        <i class="bi bi-journal-text me-2 text-purple"></i>Narasi Sejarah Pendirian Jemaat
    </h5>
    <form id="formSejarah">
        <input type="hidden" name="aksi" value="update_sejarah">
        <div class="mb-4">
            <textarea name="konten_sejarah" class="form-control form-control-custom" rows="9" required><?php echo htmlspecialchars($dataSejarah['konten'] ?? ''); ?></textarea>
        </div>
        <button type="submit" class="btn btn-purple-admin shadow-sm">
            <i class="bi bi-check-circle me-1"></i> Simpan Perubahan Narasi
        </button>
    </form>
</div>

<!-- Tabel Ketua -->
<div class="card card-custom p-4 mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h5 class="fw-bold text-dark m-0"><i class="bi bi-people me-2 text-purple"></i>Daftar Ketua Jemaat</h5>
        <button class="btn btn-purple-admin btn-sm" data-bs-toggle="modal" data-bs-target="#modalTambahKetua">
            <i class="bi bi-plus-lg me-1"></i> Tambah Ketua
        </button>
    </div>

    <div class="table-responsive border">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr><th>Foto</th><th>Nama</th><th>Tahun</th><th>Aksi</th></tr>
            </thead>
            <tbody>
                <?php
                $query = mysqli_query($koneksi, "SELECT * FROM ketua_jemaat ORDER BY tahun_mulai ASC");
                while ($k = mysqli_fetch_assoc($query)):
                ?>
                <tr>
                    <td><img src="../assets/images/<?php echo !empty($k['foto']) ? $k['foto'] : 'default.jpg'; ?>" width="50" class="rounded-circle"></td>
                    <td class="fw-bold"><?php echo htmlspecialchars($k['nama']); ?></td>
                    <td><?php echo htmlspecialchars($k['tahun_mulai']) . ' - ' . htmlspecialchars($k['tahun_selesai']); ?></td>
                    <td>
                        <button class="btn btn-sm btn-warning text-white" data-bs-toggle="modal" data-bs-target="#modalEditKetua<?php echo $k['id']; ?>">
                            <i class="bi bi-pencil-square"></i>
                        </button>
                        <a href="proses/proses_sejarah.php?aksi=hapus_ketua&id=<?php echo $k['id']; ?>" 
                           class="btn btn-sm btn-danger" onclick="return confirm('Yakin hapus data ini?')">
                            <i class="bi bi-trash"></i>
                        </a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- MODAL TAMBAH -->
<div class="modal fade" id="modalTambahKetua" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog"><form action="proses/proses_sejarah.php" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="aksi" value="save_ketua">
        <div class="modal-content"><div class="modal-body">
            <input type="text" name="nama" class="form-control mb-2" placeholder="Nama" required>
            <div class="row"><div class="col-6"><input type="text" name="tahun_mulai" class="form-control mb-2" placeholder="Mulai"></div>
            <div class="col-6"><input type="text" name="tahun_selesai" class="form-control mb-2" placeholder="Selesai"></div></div>
            <input type="file" name="foto" class="form-control">
        </div><div class="modal-footer"><button type="submit" class="btn btn-primary">Simpan</button></div></div>
    </form></div>
</div>

<!-- MODAL EDIT (PENTING: Harus ada loop agar ID sesuai) -->
<?php 
$queryEdit = mysqli_query($koneksi, "SELECT * FROM ketua_jemaat");
while ($row = mysqli_fetch_assoc($queryEdit)): 
?>
<div class="modal fade" id="modalEditKetua<?php echo $row['id']; ?>" tabindex="-1">
    <div class="modal-dialog"><form action="proses/proses_sejarah.php" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="aksi" value="save_ketua">
        <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
        <input type="hidden" name="foto_lama" value="<?php echo $row['foto']; ?>">
        <div class="modal-content"><div class="modal-body">
            <input type="text" name="nama" class="form-control mb-2" value="<?php echo htmlspecialchars($row['nama']); ?>">
            <div class="row"><div class="col-6"><input type="text" name="tahun_mulai" class="form-control mb-2" value="<?php echo $row['tahun_mulai']; ?>"></div>
            <div class="col-6"><input type="text" name="tahun_selesai" class="form-control mb-2" value="<?php echo $row['tahun_selesai']; ?>"></div></div>
            <input type="file" name="foto" class="form-control">
        </div><div class="modal-footer"><button type="submit" class="btn btn-warning text-white">Update</button></div></div>
    </form></div>
</div>
<?php endwhile; ?>

<script>
document.getElementById('formSejarah').addEventListener('submit', function(e) {
    e.preventDefault(); 
    
    const btn = this.querySelector('button[type="submit"]');
    const originalText = btn.innerHTML;
    btn.innerHTML = '<i class="bi bi-hourglass-split"></i> Menyimpan...';
    btn.disabled = true;

    let formData = new FormData(this);

    fetch('proses/proses_sejarah.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        btn.innerHTML = originalText;
        btn.disabled = false;

        if(data.status === 'success') {
            const placeholder = document.getElementById('alertPlaceholder');
            
            // Menggunakan struktur yang IDENTIK dengan alert.php Anda agar tampilannya sama persis
            placeholder.innerHTML = `
                <div id="dynamic-alert" class="alert alert-primary alert-dismissible fade show shadow border-0 py-3 mb-4" 
                     style="background: #eef2ff; color: #4338ca; border-left: 5px solid #4338ca !important;">
                    <i class="bi bi-stars me-2"></i> <strong>Pemberitahuan:</strong> Narasi sejarah berhasil diperbarui!
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>`;
            
            // Auto scroll agar alert terlihat
            window.scrollTo({ top: 0, behavior: 'smooth' });
        } else {
            alert("Gagal menyimpan data.");
        }
    })
    .catch(error => {
        btn.innerHTML = originalText;
        btn.disabled = false;
        console.error('Error:', error);
    });
});
</script>