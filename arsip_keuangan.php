<?php 
include 'koneksi.php'; 

// Cek apakah dipanggil via modal
$is_modal = isset($_GET['is_modal']);

$bulan = isset($_GET['bulan']) ? (int)$_GET['bulan'] : date('m');
$tahun = isset($_GET['tahun']) ? (int)$_GET['tahun'] : date('Y');

// 1. Tangkap Parameter Kategori dari GET
$kategori_filter = isset($_GET['kategori']) ? mysqli_real_escape_string($koneksi, $_GET['kategori']) : 'all';

// Susun Query Berdasarkan Filter Periode dan Kategori
$whereClause = "WHERE MONTH(tanggal) = '$bulan' AND YEAR(tanggal) = '$tahun'";
if ($kategori_filter !== 'all' && !empty($kategori_filter)) {
    $whereClause .= " AND kategori = '$kategori_filter'";
}

$query = mysqli_query($koneksi, "SELECT * FROM warta_keuangan $whereClause ORDER BY tanggal ASC, id ASC");
$nama_bulan = date('F', mktime(0, 0, 0, $bulan, 1));
?>

<?php if(!$is_modal): ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Arsip Laporan Keuangan <?php echo $nama_bulan . ' ' . $tahun; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
    /* Styling Dasar */
    .table-glass-container { background: rgba(255, 255, 255, 0.85); backdrop-filter: blur(20px); border-radius: 20px; padding: 2rem; box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05); }
    .table-bordered th, .table-bordered td { border: 1px solid #dee2e6 !important; }
    
    /* PENGATURAN CETAK (PRINT) AGAR RAPI */
    @media print {
        body * { visibility: hidden; }
        .table-glass-container, .table-glass-container * { visibility: visible; }
        
        .table-glass-container { 
            position: absolute; 
            left: 0; 
            top: 0; 
            width: 100%; 
            padding: 0;
            background: #ffffff !important; 
        }

        .btn, .d-flex div { display: none !important; }

        .table { width: 100% !important; border-collapse: collapse !important; }
        .table th, .table td { border: 1px solid #000 !important; padding: 8px !important; }
        
        h3 { color: #000 !important; font-size: 18px !important; margin-bottom: 20px !important; }
    }
</style>
</head>
<body>
<?php include 'navbar.php'; ?>
<div class="container mt-5 pt-5">
<?php endif; ?>

    <div class="table-glass-container">
        <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
            <h3 class="fw-bold m-0"><i class="bi bi-archive me-2"></i>Arsip: <?php echo $nama_bulan . ' ' . $tahun; ?></h3>
            
            <div>
                <!-- Meneruskan parameter kategori ke link download PDF -->
                <a href="print_keuangan.php?bulan=<?php echo $bulan; ?>&tahun=<?php echo $tahun; ?>&kategori=<?php echo urlencode($kategori_filter); ?>"
                class="btn btn-dark rounded-pill px-4 py-2 fw-semibold shadow-sm"
                target="_blank">
                <i class="bi bi-file-earmark-pdf-fill me-2"></i>Download PDF</a>

                <?php if(!$is_modal): ?>
                    <a href="warta-keuangan.php" class="btn btn-outline-primary ms-2 rounded-pill"><i class="bi bi-arrow-left"></i> Kembali</a>
                <?php endif; ?>
            </div>
        </div>

        <div class="table-responsive rounded-4 overflow-hidden border">
            <table class="table table-hover table-striped table-bordered align-middle table-custom mb-0 bg-white">
                <thead class="table-light">
                    <tr class="text-center text-nowrap">
                        <th class="py-3">Tanggal</th>
                        <th class="py-3">Kategori</th>
                        <th class="py-3">Pemasukan</th>
                        <th class="py-3">Pengeluaran</th>
                        <th class="py-3">Saldo</th>
                        <th class="py-3">Keterangan</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(mysqli_num_rows($query) > 0): while($data = mysqli_fetch_assoc($query)): ?>
                    <tr>
                        <td class="text-center text-nowrap"><?php echo date('d/m/Y', strtotime($data['tanggal'])); ?></td>
                        <td class="text-center text-nowrap">
                            <!-- Label Badge Kategori -->
                            <span class="badge <?php echo ($data['kategori'] == 'Pengeluaran') ? 'bg-danger-subtle text-danger border border-danger-subtle' : 'bg-success-subtle text-success border border-success-subtle'; ?> rounded-pill px-3 py-1">
                                <?php echo htmlspecialchars($data['kategori'] ?? '-'); ?>
                            </span>
                        </td>
                        <td class="text-end text-success fw-bold text-nowrap">Rp <?php echo number_format($data['total_pemasukan'], 0, ',', '.'); ?></td>
                        <td class="text-end text-danger fw-bold text-nowrap">Rp <?php echo number_format($data['total_pengeluaran'], 0, ',', '.'); ?></td>
                        <td class="text-end fw-bold text-nowrap">Rp <?php echo number_format($data['saldo_akhir'], 0, ',', '.'); ?></td>
                        <td style="min-width: 200px;"><?php echo nl2br(htmlspecialchars($data['keterangan'])); ?></td>
                    </tr>
                    <?php endwhile; else: ?>
                    <tr>
                        <td colspan="6" class="text-center py-5 text-muted">
                            <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                            Tidak ada data keuangan untuk filter dan periode ini.
                        </td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

<?php if(!$is_modal): ?>
</div>
<?php include 'footer.php'; ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<?php endif; ?>