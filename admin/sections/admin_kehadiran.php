<?php
session_start();
include 'koneksi.php';

// Cek apakah admin sudah login (Sesuaikan dengan session login Anda)
// if (!isset($_SESSION['admin_login'])) { header("Location: login.php"); exit; }

// --- LOGIKA PROSES ---
// Tambah Data
if (isset($_POST['tambah'])) {
    $tgl = $_POST['tanggal'];
    $sesi = mysqli_real_escape_string($koneksi, $_POST['sesi']);
    $jml = (int)$_POST['jumlah'];
    mysqli_query($koneksi, "INSERT INTO kehadiran_jemaat (tanggal, sesi_ibadah, jumlah_hadir) VALUES ('$tgl', '$sesi', $jml)");
}

// Hapus Data
if (isset($_GET['hapus'])) {
    $id = (int)$_GET['hapus'];
    mysqli_query($koneksi, "DELETE FROM kehadiran_jemaat WHERE id = $id");
    header("Location: admin_kehadiran.php");
}

// Ambil Data untuk Tabel
$data = mysqli_query($koneksi, "SELECT * FROM kehadiran_jemaat ORDER BY tanggal DESC");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Admin - Statistik Kehadiran</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container py-5">
    <h2 class="mb-4">Manajemen Statistik Kehadiran</h2>

    <!-- Form Input -->
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <h5 class="card-title mb-3">Input Kehadiran Baru</h5>
            <form method="POST" class="row g-3">
                <div class="col-md-3">
                    <input type="date" name="tanggal" class="form-control" required>
                </div>
                <div class="col-md-4">
                    <select name="sesi" class="form-select" required>
                        <option value="Ibadah Subuh">Ibadah Subuh</option>
                        <option value="Ibadah Pagi">Ibadah Pagi</option>
                        <option value="Ibadah Malam">Ibadah Malam</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <input type="number" name="jumlah" class="form-control" placeholder="Jumlah Hadir" required>
                </div>
                <div class="col-md-2">
                    <button type="submit" name="tambah" class="btn btn-primary w-100">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Tabel Data -->
    <div class="card shadow-sm">
        <div class="card-body">
            <table class="table table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>Tanggal</th>
                        <th>Sesi Ibadah</th>
                        <th>Jumlah</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($row = mysqli_fetch_assoc($data)): ?>
                    <tr>
                        <td><?= date('d M Y', strtotime($row['tanggal'])) ?></td>
                        <td><?= $row['sesi_ibadah'] ?></td>
                        <td><?= $row['jumlah_hadir'] ?></td>
                        <td>
                            <a href="?hapus=<?= $row['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin hapus?')">Hapus</a>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

</body>
</html>