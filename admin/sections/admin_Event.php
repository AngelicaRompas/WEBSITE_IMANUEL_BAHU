<?php
// admin_event.php
// File ini dapat langsung dimasukkan atau di-include ke dalam dashboard utama admin Anda
?>

<link rel="stylesheet" href="assets/css/style-adminEvent.css">

<!-- Bagian Atas: Header Kontrol Utama -->
<div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-3 header-wrapper-flex">
    <div>
        <h4 class="fw-bold mb-0 section-title-admin">
            <i class="bi bi-calendar-event text-purple-premium me-2"></i>Manajemen Event
        </h4>
        <p class="text-muted small mb-0">Kelola agenda mendatang dan riwayat kegiatan jemaat.</p>
    </div>
    <button class="btn btn-purple-admin shadow-sm fw-bold rounded-pill px-4 py-2.5 d-inline-flex align-items-center justify-content-center" data-bs-toggle="modal" data-bs-target="#modalTambahEvent">
        <i class="bi bi-plus-lg me-1"></i> Tambah Event Baru
    </button>
</div>

<!-- Navigasi Saringan Tab Konten (Pills) -->
<ul class="nav nav-pills mb-4 bg-white p-1 rounded-pill d-inline-flex border nav-pills-purple-admin" id="pills-tab" role="tablist">
    <li class="nav-item">
        <button class="nav-link active rounded-pill px-4" data-bs-toggle="pill" data-bs-target="#pills-mendatang" type="button">Agenda Mendatang</button>
    </li>
    <li class="nav-item">
        <button class="nav-link rounded-pill px-4" data-bs-toggle="pill" data-bs-target="#pills-terlaksana" type="button">Event Terlaksana</button>
    </li>
</ul>

<!-- Konten Switch Tab -->
<div class="tab-content">
    
    <!-- Tab PANEL 1: Agenda Mendatang -->
    <div class="tab-pane fade show active" id="pills-mendatang" role="tabpanel">
        <div class="card card-custom border-0 shadow-sm overflow-hidden">
            <div class="table-responsive">
                <table class="table table-hover align-middle table-custom-event mb-0 bg-white">
                    <thead class="table-light">
                        <tr class="text-nowrap">
                            <th class="ps-4">Tanggal</th>
                            <th>Judul Kegiatan</th>
                            <th>Lokasi</th>
                            <th class="text-end pe-4">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php 
                    $q_mendatang = mysqli_query($koneksi, "SELECT * FROM events WHERE tanggal >= '$today' ORDER BY tanggal ASC");
                    if (mysqli_num_rows($q_mendatang) > 0):
                        while($evt = mysqli_fetch_assoc($q_mendatang)): 
                    ?>
                    <tr>
                        <td class="ps-4 text-nowrap">
                            <span class="badge badge-purple-soft py-2 px-3 rounded-3">
                                <i class="bi bi-calendar3 me-1"></i> <?php echo date('d M Y', strtotime($evt['tanggal'])); ?>
                            </span>
                        </td>
                        <td class="fw-bold text-dark"><?php echo htmlspecialchars($evt['judul']); ?></td>
                        <td class="text-secondary"><i class="bi bi-geo-alt-fill text-danger me-1"></i><?php echo htmlspecialchars($evt['lokasi']); ?></td>
                        <td class="text-end pe-4 text-nowrap">
                            <button class="btn btn-sm btn-outline-purple-action btn-circle-action" data-bs-toggle="modal" data-bs-target="#modalEditEvent<?php echo $evt['id']; ?>" title="Edit Event">
                                <i class="bi bi-pencil-fill"></i>
                            </button>
                            <a href="proses/proses_event.php?hapus_event=<?php echo $evt['id']; ?>" class="btn btn-sm btn-outline-danger btn-circle-action ms-1" onclick="return confirm('Yakin ingin menghapus agenda event ini?')" title="Hapus Event">
                                <i class="bi bi-trash-fill"></i>
                            </a>
                        </td>
                    </tr>
                    <?php 
                        endwhile; 
                    else:
                    ?>
                    <tr>
                        <td colspan="4" class="text-center py-4 text-muted">Belum ada agenda kegiatan mendatang.</td>
                    </tr>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Tab PANEL 2: Event Terlaksana -->
    <div class="tab-pane fade" id="pills-terlaksana" role="tabpanel">
        <div class="card card-custom border-0 shadow-sm overflow-hidden">
            <div class="table-responsive">
                <table class="table table-hover align-middle table-custom-event mb-0 bg-white">
                    <thead class="table-light">
                        <tr class="text-nowrap">
                            <th class="ps-4">Tanggal</th>
                            <th>Judul Kegiatan</th>
                            <th>Dokumentasi</th>
                            <th class="text-end pe-4">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php 
                    $q_lalu = mysqli_query($koneksi, "SELECT * FROM events WHERE tanggal < '$today' ORDER BY tanggal DESC");
                    if (mysqli_num_rows($q_lalu) > 0):
                        while($evt = mysqli_fetch_assoc($q_lalu)): 
                            $total_foto = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) as total FROM event_gallery WHERE event_id = '".$evt['id']."'"))['total'];
                    ?>
                    <tr>
                        <td class="ps-4 text-secondary text-nowrap"><?php echo date('d M Y', strtotime($evt['tanggal'])); ?></td>
                        <td class="fw-semibold text-dark"><?php echo htmlspecialchars($evt['judul']); ?></td>
                        
                        <!-- Kolom Dokumentasi Interaktif (Bisa di-klik untuk membuka Modal Galeri) -->
                        <td>
                            <span class="badge <?php echo $total_foto > 0 ? 'bg-success' : 'bg-warning'; ?> rounded-pill py-1.5 px-3 badge-clickable" 
                                  data-bs-toggle="modal" 
                                  data-bs-target="#modalGaleri<?php echo $evt['id']; ?>" 
                                  title="Klik untuk melihat seluruh dokumentasi foto">
                                <i class="bi bi-images me-1"></i> <?php echo $total_foto; ?> Foto
                            </span>
                        </td>
                        
                        <!-- Kolom Aksi Bersih tanpa tombol galeri terpisah -->
                        <td class="text-end pe-4 text-nowrap">
                            <button class="btn btn-sm btn-outline-purple-action btn-circle-action" data-bs-toggle="modal" data-bs-target="#modalEditEvent<?php echo $evt['id']; ?>" title="Edit Event">
                                <i class="bi bi-pencil-fill"></i>
                            </button>
                            <a href="proses/proses_event.php?hapus_event=<?php echo $evt['id']; ?>" class="btn btn-sm btn-outline-danger btn-circle-action ms-1" onclick="return confirm('Yakin ingin menghapus riwayat event ini?')" title="Hapus Event">
                                <i class="bi bi-trash-fill"></i>
                            </a>
                        </td>
                    </tr>
                    <?php 
                        endwhile; 
                    else:
                    ?>
                    <tr>
                        <td colspan="4" class="text-center py-4 text-muted">Belum ada riwayat kegiatan masa lalu.</td>
                    </tr>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal 1: Tambah Event Baru -->
<div class="modal fade" id="modalTambahEvent" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content modal-content-custom border-0 shadow">
            <form action="proses/proses_event.php" method="POST" enctype="multipart/form-data">
                <div class="modal-header border-0 pt-4 px-4 pb-2">
                    <h5 class="fw-bold text-dark mb-0"><i class="bi bi-calendar-plus me-2 text-purple-premium"></i>Tambah Event</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body px-4">
                    <div class="mb-3">
                        <label class="small fw-bold text-secondary mb-1">Judul</label>
                        <input type="text" name="judul" class="form-control form-control-custom" placeholder="Masukkan nama kegiatan" required>
                    </div>
                    <div class="row">
                        <div class="col-6 mb-3">
                            <label class="small fw-bold text-secondary mb-1">Tanggal</label>
                            <input type="date" name="tanggal" class="form-control form-control-custom" required>
                        </div>
                        <div class="col-6 mb-3">
                            <label class="small fw-bold text-secondary mb-1">Kategori</label>
                            <select name="kategori_acara" class="form-select form-select-custom">
                                <option>Jemaat</option>
                                <option>Pemuda</option>
                                <option>BIPRA</option>
                            </select>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="small fw-bold text-secondary mb-1">Lokasi</label>
                        <input type="text" name="lokasi" class="form-control form-control-custom" placeholder="Tempat pelaksanaan" required>
                    </div>
                    <div class="mb-3">
                        <label class="small fw-bold text-secondary mb-1">Deskripsi</label>
                        <textarea name="deskripsi" class="form-control form-control-custom" rows="3" placeholder="Keterangan singkat acara..."></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="small fw-bold text-secondary mb-1">Foto Cover (Opsional)</label>
                        <input type="file" name="poster" class="form-control form-control-custom" accept="image/*">
                    </div>
                    <div class="mb-2">
                        <label class="small fw-bold text-secondary mb-1">Dokumentasi (Multiple)</label>
                        <input type="file" name="galeri[]" class="form-control form-control-custom" accept="image/*" multiple>
                    </div>
                </div>
                <div class="modal-footer border-0 bg-light rounded-bottom-4 px-4 py-3 mt-3">
                    <button type="button" class="btn btn-outline-secondary rounded-pill px-4 btn-sm" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" name="simpan_event" class="btn btn-purple-admin rounded-pill px-4 btn-sm fw-bold shadow-sm">Simpan Event</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Looping Modals Edit & Galeri per Event -->
<?php
$q_all = mysqli_query($koneksi, "SELECT * FROM events");
while($row = mysqli_fetch_assoc($q_all)): ?>
    
    <!-- Modal 2: Edit Event -->
    <div class="modal fade" id="modalEditEvent<?php echo $row['id']; ?>" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content modal-content-custom border-0 shadow">
                <form action="proses/proses_event.php" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="id_event" value="<?php echo $row['id']; ?>">
                    <div class="modal-header border-0 pt-4 px-4 pb-2">
                        <h5 class="fw-bold text-dark mb-0"><i class="bi bi-pencil-square me-2 text-warning"></i>Edit Event</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body px-4">
                        <div class="mb-3"><label class="small fw-bold text-secondary mb-1">Judul</label><input type="text" name="judul" class="form-control form-control-custom" value="<?php echo htmlspecialchars($row['judul']); ?>" required></div>
                        <div class="mb-3"><label class="small fw-bold text-secondary mb-1">Tanggal</label><input type="date" name="tanggal" class="form-control form-control-custom" value="<?php echo $row['tanggal']; ?>" required></div>
                        <div class="mb-3"><label class="small fw-bold text-secondary mb-1">Lokasi</label><input type="text" name="lokasi" class="form-control form-control-custom" value="<?php echo htmlspecialchars($row['lokasi']); ?>" required></div>
                        <div class="mb-3"><label class="small fw-bold text-secondary mb-1">Deskripsi</label><textarea name="deskripsi" class="form-control form-control-custom" rows="3"><?php echo htmlspecialchars($row['deskripsi']); ?></textarea></div>
                        <div class="mb-3"><label class="small fw-bold text-secondary mb-1">Ganti Cover</label><input type="file" name="poster" class="form-control form-control-custom" accept="image/*"></div>
                        <div class="mb-2"><label class="small fw-bold text-secondary mb-1">Tambah Galeri</label><input type="file" name="galeri[]" class="form-control form-control-custom" accept="image/*" multiple></div>
                    </div>
                    <div class="modal-footer border-0 bg-light rounded-bottom-4 px-4 py-3 mt-3">
                        <button type="button" class="btn btn-outline-secondary rounded-pill px-4 btn-sm" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" name="edit_event" class="btn btn-purple-admin rounded-pill px-4 btn-sm fw-bold shadow-sm">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal 3: Galeri Foto Riwayat Kegiatan (FIXED PATH STYLE WITH ../) -->
    <div class="modal fade" id="modalGaleri<?php echo $row['id']; ?>" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content modal-content-custom border-0 shadow">
                <div class="modal-header border-0 pt-4 px-4 pb-2">
                    <h5 class="fw-bold text-dark mb-0"><i class="bi bi-images me-2 text-info"></i>Galeri: <?php echo htmlspecialchars($row['judul']); ?></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body px-4 pb-4">
                    <div class="row g-2">
                        <?php 
                        $galeri = mysqli_query($koneksi, "SELECT * FROM event_gallery WHERE event_id = '".$row['id']."'");
                        if (mysqli_num_rows($galeri) > 0):
                            while($g = mysqli_fetch_assoc($galeri)): 
                        ?>
                            <div class="col-6 col-md-4">
                                <!-- FIX: Jalur file ditambahkan ../ agar sukses mundur dari folder admin -->
                                <img src="../assets/gallery/<?php echo $g['foto_path']; ?>" class="img-fluid rounded border shadow-sm object-fit-cover" style="aspect-ratio: 4/3; width: 100%;">
                            </div>
                        <?php 
                            endwhile; 
                        else:
                        ?>
                            <div class="col-12 text-center py-3 text-muted small">Belum ada unggahan dokumentasi foto untuk kegiatan ini.</div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

<?php endwhile; ?>