<?php
// Logika Tambah Artikel
if (isset($_POST['tambah_artikel'])) {
    $judul = mysqli_real_escape_string($koneksi, $_POST['judul']);
    $tanggal = $_POST['tanggal'];
    $konten = mysqli_real_escape_string($koneksi, $_POST['konten']);
    
    $gambar = '';
    if (isset($_FILES['gambar']) && $_FILES['gambar']['error'] === 0) {
        $nama_file = $_FILES['gambar']['name'];
        $tmp_file = $_FILES['gambar']['tmp_name'];
        $ekstensi = strtolower(pathinfo($nama_file, PATHINFO_EXTENSION));
        $ekstensi_boleh = ['jpg', 'jpeg', 'png', 'webp'];
        
        if (in_array($ekstensi, $ekstensi_boleh)) {
            $gambar = 'artikel_' . time() . '.' . $ekstensi;
            move_uploaded_file($tmp_file, '../assets/gallery/' . $gambar);
        }
    }

    $query_tambah = mysqli_query($koneksi, "INSERT INTO artikel_warta (judul, gambar, tanggal, konten) VALUES ('$judul', '$gambar', '$tanggal', '$konten')");
    
    if($query_tambah) {
        echo "<script>window.location='admin_dashboard.php?tab=admin-artikel&pesan=sukses_artikel';</script>";
    } else {
        echo "<script>window.location='admin_dashboard.php?tab=admin-artikel&pesan=gagal';</script>";
    }
}

// Logika Edit Artikel
if (isset($_POST['edit_artikel'])) {
    $id = (int)$_POST['id'];
    $judul = mysqli_real_escape_string($koneksi, $_POST['judul']);
    $tanggal = $_POST['tanggal'];
    $konten = mysqli_real_escape_string($koneksi, $_POST['konten']);
    
    // Cek apakah ada upload gambar baru
    if (isset($_FILES['gambar']) && $_FILES['gambar']['error'] === 0) {
        $nama_file = $_FILES['gambar']['name'];
        $tmp_file = $_FILES['gambar']['tmp_name'];
        $ekstensi = strtolower(pathinfo($nama_file, PATHINFO_EXTENSION));
        $ekstensi_boleh = ['jpg', 'jpeg', 'png', 'webp'];
        
        if (in_array($ekstensi, $ekstensi_boleh)) {
            // Hapus gambar lama
            $q_img = mysqli_query($koneksi, "SELECT gambar FROM artikel_warta WHERE id = $id");
            $d_img = mysqli_fetch_assoc($q_img);
            if (!empty($d_img['gambar']) && file_exists('../assets/gallery/' . $d_img['gambar'])) {
                unlink('../assets/gallery/' . $d_img['gambar']);
            }

            $gambar = 'artikel_' . time() . '.' . $ekstensi;
            move_uploaded_file($tmp_file, '../assets/gallery/' . $gambar);

            $query_update = mysqli_query($koneksi, "UPDATE artikel_warta SET judul='$judul', gambar='$gambar', tanggal='$tanggal', konten='$konten' WHERE id=$id");
        }
    } else {
        $query_update = mysqli_query($koneksi, "UPDATE artikel_warta SET judul='$judul', tanggal='$tanggal', konten='$konten' WHERE id=$id");
    }

    if($query_update) {
        echo "<script>window.location='admin_dashboard.php?tab=admin-artikel&pesan=sukses_edit_artikel';</script>";
    } else {
        echo "<script>window.location='admin_dashboard.php?tab=admin-artikel&pesan=gagal';</script>";
    }
}

// Logika Hapus Artikel
if (isset($_GET['hapus_artikel'])) {
    $id = (int)$_GET['hapus_artikel'];
    $q_img = mysqli_query($koneksi, "SELECT gambar FROM artikel_warta WHERE id = $id");
    $d_img = mysqli_fetch_assoc($q_img);
    if (!empty($d_img['gambar']) && file_exists('../assets/gallery/' . $d_img['gambar'])) {
        unlink('../assets/gallery/' . $d_img['gambar']);
    }
    
    $query_hapus = mysqli_query($koneksi, "DELETE FROM artikel_warta WHERE id = $id");
    if($query_hapus) {
        echo "<script>window.location='admin_dashboard.php?tab=admin-artikel&pesan=sukses_hapus_artikel';</script>";
    } else {
        echo "<script>window.location='admin_dashboard.php?tab=admin-artikel&pesan=gagal';</script>";
    }
}
?>

<!-- FORM TAMBAH ARTIKEL -->
<div class="card shadow-sm mb-4 border-0 rounded-4">
    <div class="card-body p-4">
        <h5 class="fw-bold mb-4 text-purple">
            <i class="bi bi-journal-plus me-2"></i> Tambah Informasi / Artikel Warta
        </h5>
        <form method="POST" enctype="multipart/form-data" class="row g-3">
            <div class="col-md-8">
                <label class="form-label fw-semibold text-muted small">Judul Artikel / Informasi</label>
                <input type="text" name="judul" class="form-control rounded-pill px-4 py-2 border-light shadow-sm" placeholder="Masukkan judul artikel..." required>
            </div>
            <div class="col-md-4">
                <label class="form-label fw-semibold text-muted small">Tanggal</label>
                <input type="date" name="tanggal" class="form-control rounded-pill px-4 py-2 border-light shadow-sm" value="<?= date('Y-m-d') ?>" required>
            </div>
            <div class="col-md-12">
                <label class="form-label fw-semibold text-muted small">Gambar Utama (Opsional)</label>
                <input type="file" name="gambar" class="form-control rounded-pill px-4 py-2 border-light shadow-sm">
            </div>
            <div class="col-md-12">
                <label class="form-label fw-semibold text-muted small">Isi Konten / Keterangan</label>
                <textarea name="konten" class="form-control rounded-4 p-4 border-light shadow-sm" rows="5" placeholder="Tulis isi informasi atau artikel di sini..." required></textarea>
            </div>
            <div class="col-md-12 text-end mt-4">
                <button type="submit" name="tambah_artikel" class="btn btn-purple rounded-pill px-5 py-2 fw-bold shadow-sm" style="background-color: #6f42c1; color: white;">
                    <i class="bi bi-save2 me-2"></i> Simpan Artikel
                </button>
            </div>
        </form>
    </div>
</div>

<!-- TABEL DAFTAR ARTIKEL -->
<div class="card shadow-sm border-0 rounded-4">
    <div class="card-body p-4">
        <h5 class="fw-bold mb-4 text-purple">
            <i class="bi bi-card-list me-2"></i> Daftar Informasi & Artikel Warta
        </h5>
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th class="py-3 ps-3" width="10%">Gambar</th>
                        <th class="py-3">Judul Artikel</th>
                        <th class="py-3" width="20%">Tanggal</th>
                        <th class="py-3 text-center" width="20%">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?>
                    <?php 
                    $data_artikel = mysqli_query($koneksi, "SELECT * FROM artikel_warta ORDER BY tanggal DESC");
                    if (mysqli_num_rows($data_artikel) > 0):
                        while($row = mysqli_fetch_assoc($data_artikel)): 
                    ?>
                    <tr>
                        <td class="ps-3">
                            <?php if(!empty($row['gambar'])): ?>
                                <img src="../assets/gallery/<?= $row['gambar'] ?>" class="rounded-3 shadow-sm border" style="width: 50px; height: 50px; object-fit: cover;">
                            <?php else: ?>
                                <span class="badge bg-secondary rounded-pill px-3 py-2">No Image</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <span class="fw-bold text-dark d-block"><?= htmlspecialchars($row['judul']) ?></span>
                            <small class="text-muted text-truncate d-inline-block" style="max-width: 300px;"><?= strip_tags($row['konten']) ?></small>
                        </td>
                        <td>
                            <span class="text-secondary small fw-semibold">
                                <i class="bi bi-calendar3 me-1 text-primary"></i> <?= date('d M Y', strtotime($row['tanggal'])) ?>
                            </span>
                        </td>
                        <td class="text-center">
                            <!-- Tombol Edit memicu Modal -->
                            <button type="button" class="btn btn-warning btn-sm rounded-pill px-3 py-1 shadow-sm fw-bold text-white mb-1" data-bs-toggle="modal" data-bs-target="#editModal<?= $row['id'] ?>">
                                <i class="bi bi-pencil-square me-1"></i> Edit
                            </button>
                            <a href="?tab=admin-artikel&hapus_artikel=<?= $row['id'] ?>" class="btn btn-danger btn-sm rounded-pill px-3 py-1 shadow-sm fw-bold mb-1" onclick="return confirm('Yakin ingin menghapus artikel ini?')">
                                <i class="bi bi-trash-fill me-1"></i> Hapus
                            </a>
                        </td>
                    </tr>

                    <!-- MODAL EDIT ARTIKEL -->
                    <div class="modal fade" id="editModal<?= $row['id'] ?>" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-lg modal-dialog-centered">
                            <div class="modal-content border-0 rounded-4 shadow-lg">
                                <div class="modal-header bg-purple text-white px-4 py-3" style="background: #6f42c1;">
                                    <h5 class="modal-title fw-bold"><i class="bi bi-pencil-square me-2"></i> Edit Artikel / Informasi</h5>
                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <form method="POST" enctype="multipart/form-data">
                                    <div class="modal-body p-4">
                                        <input type="hidden" name="id" value="<?= $row['id'] ?>">
                                        <div class="mb-3 text-start">
                                            <label class="form-label fw-semibold text-muted small">Judul Artikel / Informasi</label>
                                            <input type="text" name="judul" class="form-control rounded-pill px-4 py-2 border-light shadow-sm" value="<?= htmlspecialchars($row['judul']) ?>" required>
                                        </div>
                                        <div class="mb-3 text-start">
                                            <label class="form-label fw-semibold text-muted small">Tanggal</label>
                                            <input type="date" name="tanggal" class="form-control rounded-pill px-4 py-2 border-light shadow-sm" value="<?= $row['tanggal'] ?>" required>
                                        </div>
                                        <div class="mb-3 text-start">
                                            <label class="form-label fw-semibold text-muted small">Ganti Gambar Utama (Opsional)</label>
                                            <?php if(!empty($row['gambar'])): ?>
                                                <div class="mb-2">
                                                    <img src="../assets/gallery/<?= $row['gambar'] ?>" class="rounded-3 border" style="width: 80px; height: 80px; object-fit: cover;">
                                                </div>
                                            <?php endif; ?>
                                            <input type="file" name="gambar" class="form-control rounded-pill px-4 py-2 border-light shadow-sm">
                                        </div>
                                        <div class="mb-3 text-start">
                                            <label class="form-label fw-semibold text-muted small">Isi Konten / Keterangan</label>
                                            <textarea name="konten" class="form-control rounded-4 p-4 border-light shadow-sm" rows="5" required><?= htmlspecialchars($row['konten']) ?></textarea>
                                        </div>
                                    </div>
                                    <div class="modal-footer px-4 pb-4 border-0">
                                        <button type="button" class="btn btn-secondary rounded-pill px-4" data-bs-dismiss="modal">Batal</button>
                                        <button type="submit" name="edit_artikel" class="btn btn-purple rounded-pill px-5 fw-bold shadow-sm" style="background-color: #6f42c1; color: white;">Simpan Perubahan</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <?>
                    <?php 
                        endwhile; 
                    else:
                    ?>
                    <tr>
                        <td colspan="4" class="text-center text-muted py-4">
                            <i class="bi bi-info-circle fs-4 d-block mb-2"></i> Belum ada artikel atau informasi yang ditambahkan.
                        </td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>