<div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-2">
    <div>
        <h4 class="fw-bold mb-0 text-dark"><i class="bi bi-calendar-event text-primary me-2"></i>Manajemen Event</h4>
        <p class="text-muted small mb-0">Kelola agenda mendatang dan riwayat kegiatan jemaat.</p>
    </div>
    <button class="btn btn-primary shadow-sm fw-bold rounded-pill px-4" data-bs-toggle="modal" data-bs-target="#modalTambahEvent">
        <i class="bi bi-plus-lg me-1"></i> Tambah Event Baru
    </button>
</div>

<ul class="nav nav-pills mb-4 bg-white p-1 rounded-pill d-inline-flex border" id="pills-tab" role="tablist">
    <li class="nav-item"><button class="nav-link active rounded-pill px-4" data-bs-toggle="pill" data-bs-target="#pills-mendatang" type="button">Agenda Mendatang</button></li>
    <li class="nav-item"><button class="nav-link rounded-pill px-4" data-bs-toggle="pill" data-bs-target="#pills-terlaksana" type="button">Event Terlaksana</button></li>
</ul>

<div class="tab-content">
    <div class="tab-pane fade show active" id="pills-mendatang" role="tabpanel">
        <div class="card card-custom border-0 shadow-sm">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light"><tr><th class="ps-4">Tanggal</th><th>Judul</th><th>Lokasi</th><th class="text-end pe-4">Aksi</th></tr></thead>
                    <tbody>
                    <?php 
                    // Menggunakan $today yang sudah ada dari admin_dashboard.php
                    $q_mendatang = mysqli_query($koneksi, "SELECT * FROM events WHERE tanggal >= '$today' ORDER BY tanggal ASC");
                    while($evt = mysqli_fetch_assoc($q_mendatang)): ?>
                    <tr>
                        <td class="ps-4"><span class="badge bg-primary bg-opacity-10 text-primary"><?php echo date('d M Y', strtotime($evt['tanggal'])); ?></span></td>
                        <td><?php echo htmlspecialchars($evt['judul']); ?></td>
                        <td><i class="bi bi-geo-alt-fill text-danger me-1"></i><?php echo htmlspecialchars($evt['lokasi']); ?></td>
                        <td class="text-end pe-4">
                            <button class="btn btn-sm btn-outline-primary rounded-circle" data-bs-toggle="modal" data-bs-target="#modalEditEvent<?php echo $evt['id']; ?>"><i class="bi bi-pencil-fill"></i></button>
                            <a href="proses/proses_event.php?hapus_event=<?php echo $evt['id']; ?>" class="btn btn-sm btn-outline-danger rounded-circle ms-1" onclick="return confirm('Yakin hapus?')"><i class="bi bi-trash-fill"></i></a>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="tab-pane fade" id="pills-terlaksana" role="tabpanel">
        <div class="card card-custom border-0 shadow-sm">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light"><tr><th class="ps-4">Tanggal</th><th>Judul</th><th>Dokumentasi</th><th class="text-end pe-4">Aksi</th></tr></thead>
                    <tbody>
                    <?php 
                    $q_lalu = mysqli_query($koneksi, "SELECT * FROM events WHERE tanggal < '$today' ORDER BY tanggal DESC");
                    while($evt = mysqli_fetch_assoc($q_lalu)): 
                        $total_foto = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) as total FROM event_gallery WHERE event_id = '".$evt['id']."'"))['total'];
                    ?>
                    <tr>
                        <td class="ps-4"><?php echo date('d M Y', strtotime($evt['tanggal'])); ?></td>
                        <td><?php echo htmlspecialchars($evt['judul']); ?></td>
                        <td><span class="badge <?php echo $total_foto > 0 ? 'bg-success' : 'bg-warning'; ?> rounded-pill"><i class="bi bi-images me-1"></i> <?php echo $total_foto; ?> Foto</span></td>
                        <td class="text-end pe-4">
                            <button class="btn btn-sm btn-outline-info rounded-circle" data-bs-toggle="modal" data-bs-target="#modalGaleri<?php echo $evt['id']; ?>"><i class="bi bi-images"></i></button>
                            <button class="btn btn-sm btn-outline-primary rounded-circle ms-1" data-bs-toggle="modal" data-bs-target="#modalEditEvent<?php echo $evt['id']; ?>"><i class="bi bi-pencil-fill"></i></button>
                            <a href="proses/proses_event.php?hapus_event=<?php echo $evt['id']; ?>" class="btn btn-sm btn-outline-danger rounded-circle ms-1" onclick="return confirm('Yakin hapus?')"><i class="bi bi-trash-fill"></i></a>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalTambahEvent" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <form action="proses/proses_event.php" method="POST" enctype="multipart/form-data">
                <div class="modal-header border-0"><h5 class="fw-bold">Tambah Event</h5></div>
                <div class="modal-body">
                    <div class="mb-3"><label class="small fw-bold">Judul</label><input type="text" name="judul" class="form-control" required></div>
                    <div class="row">
                        <div class="col-6 mb-3"><label class="small fw-bold">Tanggal</label><input type="date" name="tanggal" class="form-control" required></div>
                        <div class="col-6 mb-3"><label class="small fw-bold">Kategori</label><select name="kategori_acara" class="form-select"><option>Jemaat</option><option>Pemuda</option><option>BIPRA</option></select></div>
                    </div>
                    <div class="mb-3"><label class="small fw-bold">Lokasi</label><input type="text" name="lokasi" class="form-control" required></div>
                    <div class="mb-3"><label class="small fw-bold">Deskripsi</label><textarea name="deskripsi" class="form-control" rows="3"></textarea></div>
                    <div class="mb-3"><label class="small fw-bold">Foto Cover (Opsional)</label><input type="file" name="poster" class="form-control" accept="image/*"></div>
                    <div class="mb-3"><label class="small fw-bold">Dokumentasi (Multiple)</label><input type="file" name="galeri[]" class="form-control" accept="image/*" multiple></div>
                </div>
                <div class="modal-footer border-0"><button type="submit" name="simpan_event" class="btn btn-primary">Simpan</button></div>
            </form>
        </div>
    </div>
</div>

<?php
$q_all = mysqli_query($koneksi, "SELECT * FROM events");
while($row = mysqli_fetch_assoc($q_all)): ?>
    <div class="modal fade" id="modalEditEvent<?php echo $row['id']; ?>" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow">
                <form action="proses/proses_event.php" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="id_event" value="<?php echo $row['id']; ?>">
                    <div class="modal-header border-0"><h5 class="fw-bold">Edit Event</h5></div>
                    <div class="modal-body">
                        <div class="mb-3"><label>Judul</label><input type="text" name="judul" class="form-control" value="<?php echo htmlspecialchars($row['judul']); ?>" required></div>
                        <div class="mb-3"><label>Tanggal</label><input type="date" name="tanggal" class="form-control" value="<?php echo $row['tanggal']; ?>" required></div>
                        <div class="mb-3"><label>Lokasi</label><input type="text" name="lokasi" class="form-control" value="<?php echo htmlspecialchars($row['lokasi']); ?>" required></div>
                        <div class="mb-3"><label>Deskripsi</label><textarea name="deskripsi" class="form-control"><?php echo htmlspecialchars($row['deskripsi']); ?></textarea></div>
                        <div class="mb-3"><label>Ganti Cover</label><input type="file" name="poster" class="form-control" accept="image/*"></div>
                        <div class="mb-3"><label>Tambah Galeri</label><input type="file" name="galeri[]" class="form-control" accept="image/*" multiple></div>
                    </div>
                    <div class="modal-footer border-0"><button type="submit" name="edit_event" class="btn btn-primary">Simpan Perubahan</button></div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal fade" id="modalGaleri<?php echo $row['id']; ?>" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content border-0 shadow">
                <div class="modal-header border-0"><h5 class="fw-bold">Galeri: <?php echo htmlspecialchars($row['judul']); ?></h5></div>
                <div class="modal-body">
                    <div class="row g-2">
                        <?php 
                        $galeri = mysqli_query($koneksi, "SELECT * FROM event_gallery WHERE event_id = '".$row['id']."'");
                        while($g = mysqli_fetch_assoc($galeri)): ?>
                            <div class="col-4"><img src="assets/gallery/<?php echo $g['foto_path']; ?>" class="img-fluid rounded border"></div>
                        <?php endwhile; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php endwhile; ?>