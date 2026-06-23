<?php
?>

<link rel="stylesheet" href="assets/css/style-admin-konten.css?v=<?php echo time(); ?>">

<h4 class="section-header-jemaat mb-4 text-purple-premium">
    <i class="bi bi-people-fill me-2"></i>Pengaturan Analisis Data Jemaat & Statistik
</h4>

<form action="proses/proses_datajemaat.php" method="POST">
    <div class="card card-custom p-4 mb-4 shadow-sm bg-white border-0">
        <h5 class="fw-bold mb-3 text-dark d-flex align-items-center" style="font-size: 1.1rem;">
            <i class="bi bi-grid-3x3-gap-fill me-2 text-purple-premium"></i>Statistik Utama Jemaat
        </h5>
        <div class="row g-3">
            <div class="col-md-4">
                <label class="form-label small fw-bold text-secondary">Jumlah Total Kolom</label>
                <input type="number" name="jml_kolom" class="form-control form-control-custom" value="<?php echo $stats['Kolom']['jumlah'] ?? 0; ?>" required>
            </div>
            <div class="col-md-4">
                <label class="form-label small fw-bold text-secondary">Jumlah Total Keluarga</label>
                <input type="number" name="jml_keluarga" class="form-control form-control-custom" value="<?php echo $stats['Keluarga']['jumlah'] ?? 0; ?>" required>
            </div>
            <div class="col-md-4">
                <label class="form-label small fw-bold text-purple-premium">Jumlah Total Anggota Jemaat (Total Jiwa)</label>
                <input type="number" name="jml_anggota" class="form-control form-control-custom fw-bold border-purple-premium text-purple-premium" value="<?php echo $stats['Anggota']['jumlah'] ?? 0; ?>" required>
            </div>
        </div>
    </div>

    <div class="card card-custom p-4 shadow-sm bg-white border-0">
        <h5 class="fw-bold mb-1 text-dark d-flex align-items-center" style="font-size: 1.1rem;">
            <i class="bi bi-pie-chart-fill me-2 text-success"></i>Komposisi Elemen Grafik Kuantitatif
        </h5>
        <p class="text-muted small mb-4">*Cukup ketik jumlah jiwa riil saat ini. Nilai presentase (%) halaman pengunjung akan dikalkulasi otomatis oleh server.</p>
        
        <div class="row g-3">
            <div class="col-xl-3 col-md-6 border-end-custom">
                <h6 class="text-purple-premium border-bottom pb-2 fw-bold small"><i class="bi bi-gender-ambiguous me-1"></i>Rasio Jenis Kelamin</h6>
                <div class="mb-2">
                    <label class="small fw-bold text-muted">Jiwa Laki-laki</label>
                    <input type="number" name="jiwa_pria" class="form-control form-control-custom" value="<?php echo $stats['Laki-laki']['jumlah'] ?? 0; ?>" required>
                </div>
                <div class="mb-2">
                    <label class="small fw-bold text-muted">Jiwa Perempuan</label>
                    <input type="number" name="jiwa_wanita" class="form-control form-control-custom" value="<?php echo $stats['Perempuan']['jumlah'] ?? 0; ?>" required>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 border-end-custom">
                <h6 class="text-success border-bottom pb-2 fw-bold small"><i class="bi bi-water me-1"></i>Sakramen Baptis</h6>
                <div class="mb-2">
                    <label class="small fw-bold text-muted">Sudah Baptis (Jiwa)</label>
                    <input type="number" name="jiwa_baptis" class="form-control form-control-custom" value="<?php echo $stats['Sudah Baptis']['jumlah'] ?? 0; ?>" required>
                </div>
                <div class="mb-2">
                    <label class="small fw-bold text-muted">Belum Baptis (Jiwa)</label>
                    <input type="number" name="jiwa_belum_baptis" class="form-control form-control-custom" value="<?php echo $stats['Belum Baptis']['jumlah'] ?? 0; ?>" required>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 border-end-custom">
                <h6 class="text-info border-bottom pb-2 fw-bold small"><i class="bi bi-patch-check-fill me-1"></i>Peneguhan Sidi</h6>
                <div class="mb-2">
                    <label class="small fw-bold text-muted">Sudah Sidi (Jiwa)</label>
                    <input type="number" name="jiwa_sidi" class="form-control form-control-custom" value="<?php echo $stats['Sudah Sidi']['jumlah'] ?? 0; ?>" required>
                </div>
                <div class="mb-2">
                    <label class="small fw-bold text-muted">Belum Sidi (Jiwa)</label>
                    <input type="number" name="jiwa_belum_sidi" class="form-control form-control-custom" value="<?php echo $stats['Belum Sidi']['jumlah'] ?? 0; ?>" required>
                </div>
            </div>

            <div class="col-xl-3 col-md-6">
                <h6 class="text-warning border-bottom pb-2 fw-bold small"><i class="bi bi-diagram-3-fill me-1"></i>BIPRA & Lansia</h6>
                <div class="row g-2">
                    <div class="col-6"><label class="small fw-bold text-muted">P/KB</label><input type="number" name="jiwa_pkb" class="form-control form-control-custom" value="<?php echo $stats['P/KB']['jumlah'] ?? 0; ?>" required></div>
                    <div class="col-6"><label class="small fw-bold text-muted">W/KI</label><input type="number" name="jiwa_wki" class="form-control form-control-custom" value="<?php echo $stats['W/KI']['jumlah'] ?? 0; ?>" required></div>
                    <div class="col-6"><label class="small fw-bold text-muted">Pemuda</label><input type="number" name="jiwa_pemuda" class="form-control form-control-custom" value="<?php echo $stats['Pemuda']['jumlah'] ?? 0; ?>" required></div>
                    <div class="col-6"><label class="small fw-bold text-muted">Remaja</label><input type="number" name="jiwa_remaja" class="form-control form-control-custom" value="<?php echo $stats['Remaja']['jumlah'] ?? 0; ?>" required></div>
                    <div class="col-6"><label class="small fw-bold text-muted">ASM</label><input type="number" name="jiwa_asm" class="form-control form-control-custom" value="<?php echo $stats['ASM']['jumlah'] ?? 0; ?>" required></div>
                    <div class="col-6"><label class="small fw-bold text-muted">Lansia</label><input type="number" name="jiwa_lansia" class="form-control form-control-custom" value="<?php echo $stats['Lansia']['jumlah'] ?? 0; ?>" required></div>
                </div>
            </div>
        </div>

        <hr class="my-4">
        <div class="text-end">
            <button type="submit" name="update_data_jemaat" class="btn btn-purple-admin px-5 shadow-sm">
                <i class="bi bi-calculator me-1"></i> Simpan & Hitung Persentase
            </button>
        </div>
    </div>
</form> <div class="card card-custom p-4 mt-4 shadow-sm bg-white border-0">
    <h6 class="fw-bold mb-3 text-purple-premium" style="font-size: 1rem;"><i class="bi bi-tags-fill me-2"></i>Manajemen Kategori Komisi</h6>

    <form action="proses/proses_datajemaat.php" method="POST" class="row g-3 mb-4">
        <div class="col-md-9">
            <input type="text" name="nama_kategori" class="form-control form-control-custom" placeholder="Nama Kategori Baru" required>
        </div>
        <div class="col-md-3">
            <button type="submit" name="tambah_kategori" class="btn btn-purple-admin w-100 fw-bold">
                <i class="bi bi-plus-lg me-1"></i> Tambah Kategori
            </button>
        </div>
    </form>

    <div class="table-responsive">
        <table class="table table-hover align-middle table-custom-jemaat mb-0 bg-white">
            <thead class="table-light">
                <tr class="text-nowrap">
                    <th>Nama Kategori Komisi</th>
                    <th width="120" class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $kat = mysqli_query($koneksi, "SELECT * FROM kategori_komisi ORDER BY nama_kategori ASC");
                if (mysqli_num_rows($kat) > 0):
                    while($r = mysqli_fetch_assoc($kat)):
                ?>
                <tr>
                    <td class="fw-bold text-dark"><?php echo htmlspecialchars($r['nama_kategori']); ?></td>
                    <td class="text-center text-nowrap">
                        <button class="btn btn-sm btn-warning text-white rounded-3 px-2.5 me-1" data-bs-toggle="modal" data-bs-target="#editKategori<?php echo $r['id']; ?>"><i class="bi bi-pencil-square"></i></button>
                        <a href="proses/proses_datajemaat.php?hapus_kategori=1&id=<?php echo $r['id']; ?>" class="btn btn-danger btn-sm rounded-3 px-2.5" onclick="return confirm('Hapus kategori ini?')"><i class="bi bi-trash"></i></a>
                    </td>
                </tr>
                <?php 
                    endwhile; 
                else:
                ?>
                <tr><td colspan="2" class="text-center text-muted py-3">Belum ada kategori komisi.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>


<div class="card card-custom p-4 mt-4 shadow-sm bg-white border-0">
    <h6 class="fw-bold mb-3 text-purple-premium" style="font-size: 1rem;"><i class="bi bi-person-plus-fill me-2"></i>Tambah Anggota Komisi Baru</h6>

    <form action="proses/proses_datajemaat.php" method="POST">
        <div class="row g-3">
            <div class="col-md-3">
                <input type="text" name="nama" class="form-control form-control-custom" placeholder="Nama Lengkap" required>
            </div>
            <div class="col-md-3">
                <input type="text" name="jabatan" class="form-control form-control-custom" placeholder="Jabatan" required>
            </div>
            <div class="col-md-3">
                <select name="kategori" class="form-select form-select-custom" required>
                    <option value="">Pilih Kategori</option>
                    <?php
                    $katSelect = mysqli_query($koneksi, "SELECT * FROM kategori_komisi ORDER BY nama_kategori ASC");
                    while($r = mysqli_fetch_assoc($katSelect)):
                    ?>
                        <option value="<?php echo htmlspecialchars($r['nama_kategori']); ?>"><?php echo htmlspecialchars($r['nama_kategori']); ?></option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="col-md-3">
                <button type="submit" name="tambah_anggota_komisi" class="btn btn-success text-white w-100 fw-bold py-2.5 rounded-3 shadow-sm">
                    <i class="bi bi-plus-circle me-1"></i> Simpan Anggota
                </button>
            </div>
        </div>
    </form>
</div>


<div class="card card-custom p-4 mt-4 shadow-sm bg-white border-0">
    <h6 class="fw-bold mb-3 text-purple-premium" style="font-size: 1rem;"><i class="bi bi-card-list me-2"></i>Daftar Anggota Komisi</h6>

    <div class="table-responsive">
        <table class="table table-hover align-middle table-custom-jemaat mb-0 bg-white">
            <thead class="table-light">
                <tr class="text-nowrap">
                    <th>Nama</th>
                    <th>Jabatan</th>
                    <th>Kategori</th>
                    <th width="120" class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $anggota = mysqli_query($koneksi, "SELECT so.* FROM struktur_organisasi so INNER JOIN kategori_komisi kk ON so.kategori = kk.nama_kategori ORDER BY so.kategori, so.id");
                if (mysqli_num_rows($anggota) > 0):
                    while($a = mysqli_fetch_assoc($anggota)):
                ?>
                <tr>
                    <td class="fw-bold text-dark text-nowrap"><?php echo htmlspecialchars($a['nama']); ?></td>
                    <td class="text-secondary"><?php echo htmlspecialchars($a['jabatan']); ?></td>
                    <td><span class="badge badge-purple-soft py-1.5 px-3 rounded-pill"><?php echo htmlspecialchars($a['kategori']); ?></span></td>
                    <td class="text-center text-nowrap">
                        <button class="btn btn-warning btn-sm text-white rounded-3 px-2.5 me-1" data-bs-toggle="modal" data-bs-target="#editAnggota<?php echo $a['id']; ?>"><i class="bi bi-pencil-square"></i></button>
                        <a href="proses/proses_datajemaat.php?hapus_komisi=1&id=<?php echo $a['id']; ?>" class="btn btn-danger btn-sm rounded-3 px-2.5" onclick="return confirm('Hapus anggota komisi ini?')"><i class="bi bi-trash"></i></a>
                    </td>
                </tr>
                <?php 
                    endwhile; 
                else:
                ?>
                <tr><td colspan="4" class="text-center text-muted py-3">Belum ada data anggota komisi.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>


<?php
$kat_modal = mysqli_query($koneksi, "SELECT * FROM kategori_komisi");
while($r = mysqli_fetch_assoc($kat_modal)):
?>
<div class="modal fade" id="editKategori<?php echo $r['id']; ?>" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content card bg-white border-0 p-3 shadow" style="border-radius: 20px;">
            <form action="proses/proses_datajemaat.php" method="POST">
                <div class="modal-header border-0 pt-3 px-3">
                    <h5 class="modal-title fw-bold text-dark"><i class="bi bi-pencil-square me-2 text-warning"></i>Edit Kategori</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body px-3">
                    <input type="hidden" name="id" value="<?php echo $r['id']; ?>">
                    <input type="hidden" name="nama_lama" value="<?php echo htmlspecialchars($r['nama_kategori']); ?>">
                    <label class="form-label small fw-bold text-secondary mb-1">Nama Kategori</label>
                    <input type="text" name="nama_kategori" class="form-control form-control-custom" value="<?php echo htmlspecialchars($r['nama_kategori']); ?>" required>
                </div>
                <div class="modal-footer border-0 bg-light rounded-4 px-3 py-2 mt-3 d-flex justify-content-end gap-2">
                    <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill px-3" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" name="edit_kategori" class="btn btn-sm btn-warning text-white rounded-pill px-4 fw-bold shadow-sm">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php endwhile; ?>


<?php
$anggota_modal = mysqli_query($koneksi, "SELECT * FROM struktur_organisasi");
while($a = mysqli_fetch_assoc($anggota_modal)):
?>
<div class="modal fade" id="editAnggota<?php echo $a['id']; ?>" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content card bg-white border-0 p-3 shadow" style="border-radius: 20px;">
            <form action="proses/proses_datajemaat.php" method="POST">
                <div class="modal-header border-0 pt-3 px-3">
                    <h5 class="modal-title fw-bold text-dark"><i class="bi bi-pencil-square me-2 text-warning"></i>Edit Anggota Komisi</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body px-3">
                    <input type="hidden" name="id" value="<?php echo $a['id']; ?>">
                    <div class="mb-3">
                        <label class="form-label small fw-bold text-secondary mb-1">Nama</label>
                        <input type="text" name="nama" class="form-control form-control-custom" value="<?php echo htmlspecialchars($a['nama']); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold text-secondary mb-1">Jabatan</label>
                        <input type="text" name="jabatan" class="form-control form-control-custom" value="<?php echo htmlspecialchars($a['jabatan']); ?>" required>
                    </div>
                    <div class="mb-2">
                        <label class="form-label small fw-bold text-secondary mb-1">Kategori</label>
                        <select name="kategori" class="form-select form-select-custom" required>
                            <?php
                            $kategoriEdit = mysqli_query($koneksi, "SELECT * FROM kategori_komisi ORDER BY nama_kategori ASC");
                            while($k = mysqli_fetch_assoc($kategoriEdit)):
                            ?>
                                <option value="<?php echo htmlspecialchars($k['nama_kategori']); ?>" <?php echo ($a['kategori'] == $k['nama_kategori']) ? 'selected' : ''; ?>><?php echo htmlspecialchars($k['nama_kategori']); ?></option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                </div>
                <div class="modal-footer border-0 bg-light rounded-4 px-3 py-2 mt-3 d-flex justify-content-end gap-2">
                    <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill px-3" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" name="edit_anggota_komisi" class="btn btn-sm btn-warning text-white rounded-pill px-4 fw-bold shadow-sm">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php endwhile; ?>