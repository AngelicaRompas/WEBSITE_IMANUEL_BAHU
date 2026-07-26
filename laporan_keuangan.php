<?php
// laporan_keuangan.php - Halaman Publik Terintegrasi
session_start();
include 'koneksi.php';

// Logika Logout
if (isset($_GET['aksi']) && $_GET['aksi'] === 'keluar') {
    unset($_SESSION['akses_publik_imanuel']);
    header("Location: laporan_keuangan.php");
    exit;
}

$KODE_RAHASIA_VALID = ["GIB2026", "JEMAAT2026", "GIB MBD"]; 
if (isset($_POST['cek_kode'])) {
    $kode_input = trim($_POST['kode_akses']); 
    if (in_array($kode_input, $KODE_RAHASIA_VALID)) {
        $_SESSION['akses_publik_imanuel'] = true;
        header("Location: laporan_keuangan.php");
        exit;
    } else { $error = "Kode akses salah."; }
}

$subtab = $_GET['subtab'] ?? 'rekapan';
$bulan_pilih = $_GET['bulan'] ?? date('Y-m');

// LOGIKA AKUMULASI (Data sampai dengan bulan yang dipilih)
$filter_sql = !empty($bulan_pilih) ? "WHERE DATE_FORMAT(tanggal, '%Y-%m') <= '$bulan_pilih'" : "";

// Hitung Statistik
$query_pemasukan = mysqli_query($koneksi, "SELECT 
    (SELECT IFNULL(SUM(jumlah_sesi), 0) FROM keuangan_ibadah_minggu $filter_sql) +
    (SELECT IFNULL(SUM(jumlah), 0) FROM keuangan_ibadah_kolom $filter_sql) +
    (SELECT IFNULL(SUM(nominal), 0) FROM keuangan_bipra $filter_sql) +
    (SELECT IFNULL(SUM(nominal), 0) FROM keuangan_sampul_massal $filter_sql) +
    (SELECT IFNULL(SUM(nominal), 0) FROM keuangan_ibadah_khusus $filter_sql) 
    AS total_in");
$query_pengeluaran = mysqli_query($koneksi, "SELECT IFNULL(SUM(nominal), 0) as total_out FROM keuangan_pengeluaran $filter_sql");

$in_data = mysqli_fetch_assoc($query_pemasukan);
$out_data = mysqli_fetch_assoc($query_pengeluaran);
$total_in = $in_data['total_in'];
$total_out = $out_data['total_out'];
$saldo_akhir = $total_in - $total_out;
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Keuangan Publik - GMIM Imanuel Bahu</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,700;0,900;1,400;1,700;1,900&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style-beranda.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="assets/css/style_keuangan.css?v=<?php echo time(); ?>">
    <style>
        body { background-color: #f1f5f9; font-family: 'Plus Jakarta Sans', sans-serif; }
        .glass-auth-card { background: #ffffff; border-radius: 24px; box-shadow: 0 15px 35px rgba(0,0,0,0.05); border: 1px solid rgba(111, 66, 193, 0.1); max-width: 500px; width: 100%; margin: 0 auto; padding: 2.5rem; }
        .btn-purple-accent { background-color: #6f42c1; color: white; font-weight: 700; border-radius: 50px; }
        /* Style Statistik Original */
        .stat-card { background: white; padding: 20px; border-radius: 15px; box-shadow: 0 4px 15px rgba(0,0,0,0.08); }
        .stat-card p { color: #6c757d; font-size: 0.75rem; font-weight: 700; margin-bottom: 5px; }
        .stat-card h5 { color: #212529; font-weight: 800; margin: 0; }
        /* Navigasi Original */
        .nav-pills { background: rgba(255, 255, 255, 0.2); backdrop-filter: blur(10px); border-radius: 50px; padding: 5px; }
        .nav-link { color: white !important; font-weight: 600; border-radius: 50px !important; padding: 10px 20px; transition: 0.3s; }
        .nav-link.active { background-color: #6f42c1 !important; color: white !important; }
    </style>
</head>
<body>

<?php include 'navbar.php'; ?>

<div class="container my-5">
    <?php if (!isset($_SESSION['akses_publik_imanuel'])): ?>
        <div class="glass-auth-card text-center my-4">
            <h5 class="fw-bold text-dark mb-3">Area Terbatas Jemaat</h5>
            <form action="" method="POST">
                <input type="password" name="kode_akses" class="form-control text-center rounded-pill mb-3" placeholder="Masukkan Kode..." required>
                <button type="submit" name="cek_kode" class="btn btn-purple-accent w-100">Verifikasi</button>
            </form>
        </div>
    <?php else: ?>
        
        <!-- Filter -->
        <div class="d-flex justify-content-center mb-4">
            <form action="laporan_keuangan.php" method="GET" class="d-flex align-items-center gap-2">
                <input type="hidden" name="subtab" value="<?= $subtab; ?>">
                <label class="fw-bold text-secondary">Periode:</label>
                <input type="month" name="bulan" value="<?= $bulan_pilih; ?>" class="form-control rounded-pill" style="width: auto;">
                <button type="submit" class="btn btn-purple-accent rounded-pill">Tampilkan</button>
                <a href="laporan_keuangan.php?subtab=<?= $subtab; ?>" class="btn btn-outline-secondary rounded-pill">Reset</a>
            </form>
        </div>

        <!-- Statistik -->
        <div class="row g-3 mb-4">
            <div class="col-md-4"><div class="stat-card border-start border-success border-4"><p>TOTAL PEMASUKAN</p><h5>Rp <?= number_format($total_in, 0, ',', '.'); ?></h5></div></div>
            <div class="col-md-4"><div class="stat-card border-start border-danger border-4"><p>TOTAL PENGELUARAN</p><h5>Rp <?= number_format($total_out, 0, ',', '.'); ?></h5></div></div>
            <div class="col-md-4"><div class="stat-card border-start border-primary border-4"><p>SALDO SEMENTARA</p><h5>Rp <?= number_format($saldo_akhir, 0, ',', '.'); ?></h5></div></div>
        </div>

        <!-- Navigasi -->
        <ul class="nav nav-pills mb-4 overflow-x-auto flex-nowrap" id="pills-tab-publik">
            <li class="nav-item"><a class="nav-link <?= $subtab=='rekapan'?'active':'' ?>" href="?subtab=rekapan&bulan=<?= $bulan_pilih; ?>">Rekapan</a></li>
            <li class="nav-item"><a class="nav-link <?= $subtab=='minggu'?'active':'' ?>" href="?subtab=minggu&bulan=<?= $bulan_pilih; ?>">Ibadah Minggu</a></li>
            <li class="nav-item"><a class="nav-link <?= $subtab=='kolom'?'active':'' ?>" href="?subtab=kolom&bulan=<?= $bulan_pilih; ?>">Setoran Kolom</a></li>
            <li class="nav-item"><a class="nav-link <?= $subtab=='bipra'?'active':'' ?>" href="?subtab=bipra&bulan=<?= $bulan_pilih; ?>">Penyetoran BIPRA</a></li>
            <li class="nav-item"><a class="nav-link <?= $subtab=='sampul'?'active':'' ?>" href="?subtab=sampul&bulan=<?= $bulan_pilih; ?>">Penerimaan Sampul</a></li>
            <li class="nav-item"><a class="nav-link <?= $subtab=='khusus'?'active':'' ?>" href="?subtab=khusus&bulan=<?= $bulan_pilih; ?>">Ibadah Khusus</a></li>
            <li class="nav-item"><a class="nav-link <?= $subtab=='pengeluaran'?'active':'' ?>" href="?subtab=pengeluaran&bulan=<?= $bulan_pilih; ?>">Pengeluaran</a></li>
        </ul>

        <!-- Konten -->
        <div class="tab-content">
            <?php 
                $files = ['rekapan'=>'keuangan_rekapan.php', 'minggu'=>'keuangan_ibadahMinggu.php', 'kolom'=>'keuangan_kolom.php', 'bipra'=>'keuangan_bipra.php', 'sampul'=>'keuangan_sampul.php', 'khusus'=>'keuangan_ibadahKhusus.php', 'pengeluaran'=>'keuangan_pengeluaran.php'];
                include $files[$subtab] ?? 'keuangan_rekapan.php';
            ?>
        </div>
    <?php endif; ?>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>