<?php
// admin_profil.php
?>

<link rel="stylesheet" href="assets/css/style-adminProfil.css">

<h4 class="section-header"><i class="bi bi-person-gear me-2 text-primary"></i>Pengaturan Menu Profil & Sejarah</h4>

<div class="card card-custom p-4 mb-5">
    <h5 class="fw-bold mb-3 text-dark d-flex align-items-center" style="font-size: 1.1rem;">
        <i class="bi bi-journal-text me-2 text-purple"></i>Narasi Sejarah Pendirian Jemaat
    </h5>
    <form action="proses/proses_profil.php" method="POST">
        <input type="hidden" name="aksi" value="update_sejarah">
        <div class="mb-4">
            <textarea name="konten_sejarah" class="form-control form-control-custom" rows="9" placeholder="Tuliskan sejarah lengkap jemaat di sini..."><?php echo htmlspecialchars($dataSejarah['konten'] ?? ''); ?></textarea>
        </div>
        <button type="submit" class="btn btn-purple-admin shadow-sm">
            <i class="bi bi-check-circle me-1"></i> Simpan Perubahan Narasi
        </button>
    </form>
</div>

<div class="card card-custom p-4 mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
        <h5 class="fw-bold text-dark m-0 d-flex align-items-center" style="font-size: 1.1rem;">
            <i class="bi bi-people me-2 text-purple"></i>Daftar Ketua Jemaat
        </h5>
        <button class="btn btn-purple-admin btn-sm rounded-pill px-3 py-2 d-flex align-items-center shadow-sm" data-bs-toggle="modal" data-bs-target="#modalTambahKetua">
            <i class="bi bi-plus-lg me-1"></i> Tambah Ketua
        </button>
    </div>

    <div class="table-responsive rounded-3 border">
        <table class="table table-hover align-middle table-custom-profile mb-0 bg-white">
            <thead class="table-light">
                <tr class="text-nowrap">
                    <th style="width: 80px;">Foto</th>
                    <th>Nama Pendeta / Ketua</th>
                    <th>Tahun Menjabat</th>
                    <th style="width: 120px;" class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $query = mysqli_query($koneksi, "SELECT * FROM ketua_jemaat ORDER BY tahun_mulai ASC");
                if (mysqli_num_rows($query) > 0):
                    while ($k = mysqli_fetch_assoc($query)):
                ?>
                <tr>
                    <td>
                        <img src="../assets/images/<?php echo !empty($k['foto']) ? $k['foto'] : 'default.jpg'; ?>" 
                             width="50" height="50" class="rounded-circle shadow-sm object-fit-cover border">
                    </td>
                    <td class="fw-bold text-dark"><?php echo htmlspecialchars($k['nama']); ?></td>
                    <td>
                        <span class="badge badge-periode">
                            <i class="bi bi-calendar3 me-1 small"></i> <?php echo htmlspecialchars($k['tahun_mulai']) . ' - ' . htmlspecialchars($k['tahun_selesai']); ?>
                        </span>
                    </td>
                    <td class="text-nowrap text-center">
                        <button class="btn btn-sm btn-warning rounded-pill px-2.5 shadow-sm me-1 text-white" data-bs-toggle="modal" data-bs-target="#modalEditKetua<?php echo $k['id']; ?>" title="Edit Data">
                            <i class="bi bi-pencil-square"></i>
                        </button>
                        <a href="proses/proses_profil.php?aksi=hapus_ketua&id=<?php echo $k['id']; ?>" 
                           class="btn btn-sm btn-danger rounded-pill px-2.5 shadow-sm" onclick="return confirm('Yakin ingin menghapus data Ketua Jemaat ini?')" title="Hapus Data">
                            <i class="bi bi-trash"></i>
                        </a>
                    </td>
                </tr>
                <?php 
                    endwhile; 
                else: 
                ?>
                <tr>
                    <td colspan="4" class="text-center py-4 text-muted">Belum ada data ketua jemaat yang dimasukkan.</td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<div class="modal fade" id="modalTambahKetua" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <form action="proses/proses_profil.php" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="aksi" value="save_ketua">
            <div class="modal-content modal-content-custom">
                <div class="modal-header modal-header-custom">
                    <h5 class="modal-title fw-bold"><i class="bi bi-person-plus me-2 text-purple"></i>Tambah Ketua Jemaat</h5>
                    <button type="button" class="btn-close" data-bs-shadow="none" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body modal-body-custom">
                    <div class="mb-3">
                        <label class="form-label small fw-bold text-secondary">Nama Lengkap & Gelar</label>
                        <input type="text" name="nama" class="form-control form-control-custom" placeholder="Contoh: Pdt. Nama, M.Th" required>
                    </div>
                    <div class="row">
                        <div class="col-6 mb-3">
                            <label class="form-label small fw-bold text-secondary">Tahun Mulai</label>
                            <input type="text" name="tahun_mulai" class="form-control form-control-custom" placeholder="Contoh: 2020" required>
                        </div>
                        <div class="col-6 mb-3">
                            <label class="form-label small fw-bold text-secondary">Tahun Selesai</label>
                            <input type="text" name="tahun_selesai" class="form-control form-control-custom" placeholder="Contoh: 2024 atau Sekarang" required>
                        </div>
                    </div>
                    <div class="mb-0">
                        <label class="form-label small fw-bold text-secondary">Foto Profil</label>
                        <input type="file" name="foto" class="form-control form-control-custom">
                    </div>
                </div>
                <div class="modal-footer modal-footer-custom bg-light rounded-bottom-4">
                    <button type="button" class="btn btn-outline-secondary rounded-pill px-4" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-purple-login btn-purple-admin shadow-sm">Simpan Ketua</button>
                </div>
            </div>
        </form>
    </div>
</div>

<?php
$queryEdit = mysqli_query($koneksi, "SELECT * FROM ketua_jemaat");
while ($row = mysqli_fetch_assoc($queryEdit)):
?>
<div class="modal fade" id="modalEditKetua<?php echo $row['id']; ?>" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <form action="proses/proses_profil.php" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="aksi" value="save_ketua">
            <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
            <input type="hidden" name="foto_lama" value="<?php echo $row['foto']; ?>">
            
            <div class="modal-content modal-content-custom">
                <div class="modal-header modal-header-custom">
                    <h5 class="modal-title fw-bold"><i class="bi bi-pencil-square me-2 text-warning"></i>Edit Data Ketua</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body modal-body-custom">
                    <div class="mb-3">
                        <label class="form-label small fw-bold text-secondary">Nama Lengkap & Gelar</label>
                        <input type="text" name="nama" class="form-control form-control-custom" value="<?php echo htmlspecialchars($row['nama']); ?>" required>
                    </div>
                    <div class="row">
                        <div class="col-6 mb-3">
                            <label class="form-label small fw-bold text-secondary">Tahun Mulai</label>
                            <input type="text" name="tahun_mulai" class="form-control form-control-custom" value="<?php echo htmlspecialchars($row['tahun_mulai']); ?>" required>
                        </div>
                        <div class="col-6 mb-3">
                            <label class="form-label small fw-bold text-secondary">Tahun Selesai</label>
                            <input type="text" name="tahun_selesai" class="form-control form-control-custom" value="<?php echo htmlspecialchars($row['tahun_selesai']); ?>" required>
                        </div>
                    </div>
                    <div class="mb-0">
                        <label class="form-label small fw-bold text-secondary">Ganti Foto Baru (Opsional)</label>
                        <input type="file" name="foto" class="form-control form-control-custom">
                        <div class="form-text text-muted small mt-1">Biarkan kosong jika tidak ingin mengubah foto saat ini.</div>
                    </div>
                </div>
                <div class="modal-footer modal-footer-custom bg-light rounded-bottom-4">
                    <button type="button" class="btn btn-outline-secondary rounded-pill px-4" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-warning text-white rounded-pill px-4 fw-bold shadow-sm">Update Data</button>
                </div>
            </div>
        </form>
    </div>
</div>
<?php endwhile; ?>