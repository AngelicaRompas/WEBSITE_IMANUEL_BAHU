<?php 
session_start();
include 'koneksi.php'; 

// Deteksi nama file ini secara otomatis (apapun nama file yang Anda simpan)
$halaman_ini = basename($_SERVER['PHP_SELF']);

// 1. Logika Verifikasi Kode Referral Keuangan
$kode_valid = ['JEMAAT2026', 'IMANUELBAHU', 'PELAYANAN'];
$akses_keuangan = isset($_SESSION['akses_keuangan']) && $_SESSION['akses_keuangan'] === true;

if (isset($_POST['btn_akses'])) {
    if (in_array(strtoupper(trim($_POST['kode_referral'])), $kode_valid)) {
        $_SESSION['akses_keuangan'] = true;
        // Redirect otomatis ke file ini
        header("Location: " . $halaman_ini); 
        exit;
    } else { 
        $error = "Kode referral salah!"; 
    }
}

// Logika Keluar (Logout)
if (isset($_GET['logout'])) { 
    unset($_SESSION['akses_keuangan']); 
    // Redirect otomatis ke file ini saat logout
    header("Location: " . $halaman_ini); 
    exit; 
}

// 2. Query Data HANYA dijalankan jika akses diberikan (Untuk menghemat resource server)
if ($akses_keuangan) {
    // Logika Header: Total Keseluruhan
    $queryTotal = mysqli_query($koneksi, "SELECT SUM(total_pemasukan) as total_masuk, SUM(total_pengeluaran) as total_keluar FROM warta_keuangan");
    $totalData = mysqli_fetch_assoc($queryTotal);

    // Logika Filter Bulan Berjalan
    $bulan_ini = date('m');
    $tahun_ini = date('Y');
    $query = mysqli_query($koneksi, "SELECT * FROM warta_keuangan WHERE MONTH(tanggal) = '$bulan_ini' AND YEAR(tanggal) = '$tahun_ini' ORDER BY tanggal ASC");

    // Ambil Saldo Kas Abadi Terbaru
    $querySaldo = mysqli_query($koneksi, "SELECT saldo_akhir FROM warta_keuangan ORDER BY id DESC LIMIT 1");
    $dataSaldo = mysqli_fetch_assoc($querySaldo);
    
    // Ambil Data Transaksi Paling Terakhir
    $queryTop = mysqli_query($koneksi, "SELECT * FROM warta_keuangan ORDER BY id DESC LIMIT 1");
    $dataTop = mysqli_fetch_assoc($queryTop);
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Keuangan Jemaat - GMIM Imanuel Bahu</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,700;0,900;1,400;1,700;1,900&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <link rel="stylesheet" href="assets/css/style-beranda.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="assets/css/style_keuangan.css">
</head>
<body>

<div class="digital-grid"></div>
<div class="aurora-container">
    <div class="aurora-blob blob-blue"></div>
    <div class="aurora-blob blob-soft"></div>
</div>

<?php include 'navbar.php'; ?>

<section class="page-header-premium text-center z-2">
    <div class="container">
        <div data-aos="fade-down" data-aos-duration="800">
            <span class="badge rounded-pill px-3 py-2 small mb-3 fw-bold tracking-widest" 
            style="letter-spacing: 2px; background-color: rgba(111, 66, 193, 0.1); color: #6f42c1;">
            <i class="bi bi-wallet2 me-2"></i>AKUNTABILITAS
            </span>      
        </div>
        <h1 class="main-title-aesthetic mb-3" data-aos="fade-down" data-aos-duration="1000" data-aos-delay="100">
            Transparansi Keuangan
        </h1>
        <p class="text-muted fw-medium mb-0" data-aos="fade-up" data-aos-duration="1000" data-aos-delay="200">
            Laporan Kas Masuk, Keluar, dan Saldo Kumulatif Jemaat GMIM Imanuel Bahu
        </p>
    </div>
</section>

<div class="container pb-5 mb-5 position-relative z-2">
    
    <?php if (!$akses_keuangan): ?>
        <div class="row justify-content-center" data-aos="zoom-in" data-aos-delay="300">
            <div class="col-md-5 glass-card p-5 text-center mt-4">
                <i class="bi bi-shield-lock text-purple fs-1 mb-3 d-block"></i>
                <h5 class="fw-bold my-3">Area Keuangan Terbatas</h5>
                <p class="text-muted small mb-4">Laporan keuangan bersifat internal. Silakan masukkan kode akses jemaat untuk melanjutkan.</p>
                <form method="POST">
                    <input type="text" name="kode_referral" class="form-control mb-3 text-center rounded-pill py-2" placeholder="Masukkan Kode Akses..." required>
                    <button type="submit" name="btn_akses" class="btn btn-purple w-100 py-2 fw-bold">Verifikasi Akses</button>
                </form>
                <?php if(isset($error)) echo "<p class='text-danger mt-3 mb-0 small fw-bold'>$error</p>"; ?>
            </div>
        </div>
        
    <?php else: ?>
        <div class="d-flex justify-content-end mb-4" data-aos="fade-in">
            <a href="?logout=1" class="btn btn-outline-danger btn-sm rounded-pill px-4 fw-bold">
                <i class="bi bi-box-arrow-right me-1"></i> Tutup Akses Keuangan
            </a>
        </div>

        <div class="row g-4 mb-5">
            <div class="col-md-4" data-aos="fade-up" data-aos-delay="300">
                <div class="saldo-card-glass p-4 border-start border-success border-4 h-100">
                    <small class="text-muted fw-bold text-uppercase tracking-wider">Total Pemasukan Terakhir</small>
                    <h3 class="fw-bolder text-success mt-2 mb-3">Rp <?php echo number_format($totalData['total_masuk'] ?? 0, 0, ',', '.'); ?></h3>
                    <small class="text-muted small fw-medium"><i class="bi bi-calendar-check me-1"></i> Per tanggal <?php echo isset($dataTop['tanggal']) ? date('d/m/Y', strtotime($dataTop['tanggal'])) : '-'; ?></small>
                </div>
            </div>
            <div class="col-md-4" data-aos="fade-up" data-aos-delay="400">
                <div class="saldo-card-glass p-4 border-start border-danger border-4 h-100">
                    <small class="text-muted fw-bold text-uppercase tracking-wider">Total Pengeluaran Terakhir</small>
                    <h3 class="fw-bolder text-danger mt-2 mb-3">Rp <?php echo number_format($totalData['total_keluar'] ?? 0, 0, ',', '.'); ?></h3>
                    <small class="text-muted small fw-medium"><i class="bi bi-cart-dash me-1"></i> Alokasi operasional & pelayanan</small>
                </div>
            </div>
            <div class="col-md-4" data-aos="fade-up" data-aos-delay="500">
                <div class="saldo-card-glass saldo-primary-glass p-4 h-100">
                    <small class="text-white-50 fw-bold text-uppercase tracking-wider">Saldo Kas Abadi Saat Ini</small>
                    <h3 class="fw-bolder text-white mt-2 mb-3">Rp <?php echo number_format($dataSaldo['saldo_akhir'] ?? 0, 0, ',', '.'); ?></h3>
                    <small class="text-white-50 small fw-medium"><i class="bi bi-shield-check me-1"></i> Kas Terkonsolidasi (Otomatis)</small>
                </div>
            </div>
        </div>

        <div class="table-glass-container mb-5" data-aos="zoom-in" data-aos-delay="600">
            <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
                <h5 class="fw-bold text-dark m-0">
                    <i class="bi bi-journal-album text-primary me-2"></i>
                    Buku Besar Arsip Anggaran
                </h5>
                <a href="print_keuangan.php?bulan=<?php echo $bulan_ini; ?>&tahun=<?php echo $tahun_ini; ?>" target="_blank" class="btn btn-dark rounded-pill px-4 py-2 fw-semibold shadow-sm">
                    <i class="bi bi-file-earmark-pdf-fill me-2"></i>Download PDF
                </a>
            </div>

            <div class="table-responsive rounded-4 overflow-hidden">
                <table class="table table-hover table-striped table-bordered align-middle mb-0">
                    <thead class="table-light">
                        <tr class="text-center">
                            <th>No</th><th>Tanggal Ibadah</th><th>Pemasukan</th><th>Pengeluaran</th><th>Saldo</th><th>Keterangan</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(mysqli_num_rows($query) > 0): $no=1; while($data = mysqli_fetch_assoc($query)): ?>
                        <tr>
                            <td class="text-center"><?php echo $no++; ?></td>
                            <td class="text-center"><?php echo date('d/m/Y', strtotime($data['tanggal'])); ?></td>
                            <td class="text-end text-success fw-bold">Rp <?php echo number_format($data['total_pemasukan'], 0, ',', '.'); ?></td>
                            <td class="text-end text-danger fw-bold">Rp <?php echo number_format($data['total_pengeluaran'], 0, ',', '.'); ?></td>
                            <td class="text-end fw-bold">Rp <?php echo number_format($data['saldo_akhir'], 0, ',', '.'); ?></td>
                            <td><?php echo nl2br(htmlspecialchars($data['keterangan'])); ?></td>
                        </tr>
                        <?php endwhile; else: ?>
                        <tr><td colspan="6" class="text-center py-4 text-muted">Belum ada data warta keuangan untuk bulan ini.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="table-glass-container" data-aos="zoom-in" data-aos-delay="200">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h5 class="fw-bold text-dark m-0"><i class="bi bi-calendar-event text-primary me-2"></i>Riwayat Laporan Tahunan</h5>
                <form method="GET" class="w-25">
                    <select name="tahun" class="form-select form-select-sm" onchange="this.form.submit()">
                        <?php 
                        $tahun_skrg = date('Y');
                        for($y=$tahun_skrg; $y>=2020; $y--) {
                            $selected = (isset($_GET['tahun']) && $_GET['tahun'] == $y) ? 'selected' : '';
                            echo "<option value='$y' $selected>$y</option>";
                        }
                        ?>
                    </select>
                </form>
            </div>
            <div class="row g-2">
                <?php 
                $tahun_pilih = $_GET['tahun'] ?? $tahun_skrg;
                for($m=1; $m<=12; $m++): 
                    $bln_nama = date('M', mktime(0,0,0,$m,1));
                    $qCek = mysqli_query($koneksi, "SELECT COUNT(*) as jml FROM warta_keuangan WHERE MONTH(tanggal) = '$m' AND YEAR(tanggal) = '$tahun_pilih'");
                    $cek = mysqli_fetch_assoc($qCek);
                    $hasData = ($cek['jml'] > 0);
                ?>
                <div class="col-6 col-md-3 col-lg-2">
                    <div class="month-card <?php echo $hasData ? 'active' : ''; ?>">
                        <span><?php echo $bln_nama; ?></span>
                        <?php if($hasData): ?>
                            <a href="javascript:void(0)" class="text-white" onclick="loadArsip(<?php echo $m; ?>, <?php echo $tahun_pilih; ?>)">
                                <i class="bi bi-eye-fill"></i>
                            </a>
                        <?php else: ?>
                            <i class="bi bi-dash text-muted"></i>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endfor; ?>
            </div>
        </div>
        
    <?php endif; ?>
</div>

<div class="modal fade" id="arsipModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Detail Arsip Keuangan</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body" id="modalContent"></div>
    </div>
  </div>
</div>

<?php include 'footer.php'; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script>
    AOS.init({ once: true, offset: 80 });
    function loadArsip(bulan, tahun) {
        fetch('arsip_keuangan.php?is_modal=1&bulan=' + bulan + '&tahun=' + tahun)
        .then(response => response.text())
        .then(data => {
            document.getElementById('modalContent').innerHTML = data;
            new bootstrap.Modal(document.getElementById('arsipModal')).show();
        });
    }
</script>
</body>
</html>