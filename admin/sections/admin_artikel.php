<?php
// Logika Tambah Artikel
if (isset($_POST['tambah_artikel'])) {
    $judul = mysqli_real_escape_string($koneksi, $_POST['judul']);
    $tanggal = $_POST['tanggal'];
    $konten = mysqli_real_escape_string($koneksi, $_POST['konten']);
    
    // Upload Gambar
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

    mysqli_query($koneksi, "INSERT INTO artikel_warta (judul, gambar, tanggal, konten) VALUES ('$judul', '$gambar', '$tanggal', '$konten')");
    echo "<script>window.location='admin_dashboard.php?tab=admin-artikel';</script>";
}

// Logika Hapus Artikel
if (isset($_GET['hapus_artikel'])) {
    $id = (int)$_GET['hapus_artikel'];
    // Hapus file gambar fisik jika ada
    $q_img = mysqli_query($koneksi, "SELECT gambar FROM artikel_warta WHERE id = $id");
    $d_img = mysqli_fetch_assoc($q_img);
    if (!empty($d_img['gambar']) && file_exists('../assets/gallery/' . $d_img['gambar'])) {
        unlink('../assets/gallery/' . $d_img['gambar']);
    }
    
    mysqli_query($koneksi, "DELETE FROM artikel_warta WHERE id = $id");
    echo "<script>window.location='admin_dashboard.php?tab=admin-artikel';</script>";
}
?>

<div class="card shadow-sm mb-4 border-0 rounded-4">
    <div class="card-body p-4">
        <h5 class="fw-bold mb-3 text-purple"><i class="bi bi-journal-plus me-2"></i> Tambah Informasi / Artikel Warta</h5>
        <form method="POST" enctype="multipart/form-data" class="row g-3">
            <div class="col-md-8">
                <label class="form-label fw-semibold">Judul Artikel / Informasi</label>
                <input type="text" name="judul" class="form-control rounded-pill px-3" required>
            </div>
            <div class="col-md-4">
                <label class="form-label fw-semibold">Tanggal</label>
                <input type="date" name="tanggal" class="form-control rounded-pill px-3" value="<?= date('Y-m-d') ?>" required>
            </div>
            <div class="col-md-12">
                <label class="form-label fw-semibold">Gambar Utama</label>
                <input type="file" name="gambar" class="form-control rounded-pill px-3">
            </div>
            <div class="col-md-12">
                <label class="form-label fw-semibold">Isi Konten / Keterangan</label>
                <textarea name="konten" class="form-control rounded-4 p-3" rows="4" required></textarea>
            </div>
            <div class="col-md-12 text-end">
                <button type="submit" name="tambah_artikel" class="btn btn-purple rounded-pill px-5 fw-bold shadow-sm">Simpan Artikel</button>
            </div>
        </form>
    </div>
</div>

<div class="card shadow-sm border-0 rounded-4">
    <div class="card-body p-4">
        <h5 class="fw-bold mb-3 text-purple"><i class="bi bi-card-list me-2"></i> Daftar Informasi & Artikel Warta</h5>
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th width="10%">Gambar</th>
                        <th>Judul</th>
                        <th width="15%">Tanggal</th>
                        <th width="10%">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $data_artikel = mysqli_query($koneksi, "SELECT * FROM artikel_warta ORDER BY tanggal DESC");
                    while($row = mysqli_fetch_assoc($data_artikel)): ?>
                    <tr>
                        <td>
                            <?php if(!empty($row['gambar'])): ?>
                                <img src="../assets/gallery/<?= $row['gambar'] ?>" class="rounded-3 shadow-sm" style="width: 50px; height: 50px; object-fit: cover;">
                            <?php else: ?>
                                <span class="badge bg-secondary">No Image</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <span class="fw-bold text-dark"><?= htmlspecialchars($row['judul']) ?></span>
                        </td>
                        <td><?= date('d M Y', strtotime($row['tanggal'])) ?></td>
                        <td>
                            <a href="?tab=admin-artikel&hapus_artikel=<?= $row['id'] ?>" class="btn btn-danger btn-sm rounded-pill px-3" onclick="return confirm('Yakin ingin menghapus artikel ini?')">Hapus</a>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>