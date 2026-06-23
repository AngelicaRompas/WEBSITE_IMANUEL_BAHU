<?php
include 'koneksi.php';

date_default_timezone_set('Asia/Makassar');

$bulan_ini = $_GET['bulan'] ?? date('m');
$tahun_ini = $_GET['tahun'] ?? date('Y');

// 1. Tangkap Parameter Kategori
$kategori_filter = isset($_GET['kategori']) ? mysqli_real_escape_string($koneksi, $_GET['kategori']) : 'all';

// Susun Query Berdasarkan Filter
$whereClause = "WHERE MONTH(tanggal) = '$bulan_ini' AND YEAR(tanggal) = '$tahun_ini'";
if ($kategori_filter !== 'all' && !empty($kategori_filter)) {
    $whereClause .= " AND kategori = '$kategori_filter'";
}

// 2. Query Utama dengan Filter
$query = mysqli_query($koneksi,"
    SELECT * FROM warta_keuangan
    $whereClause
    ORDER BY tanggal ASC, id ASC
");

// 3. Query Total dengan Filter (Agar summary card sesuai kategori yang dicetak)
$queryTotal = mysqli_query($koneksi,"
    SELECT
    SUM(total_pemasukan) as total_masuk,
    SUM(total_pengeluaran) as total_keluar
    FROM warta_keuangan
    $whereClause
");

$totalData = mysqli_fetch_assoc($queryTotal);

$querySaldo = mysqli_query($koneksi,"
    SELECT saldo_akhir
    FROM warta_keuangan
    ORDER BY tanggal DESC, id DESC
    LIMIT 1
");

$dataSaldo = mysqli_fetch_assoc($querySaldo);

$nama_bulan = date('F Y', strtotime($tahun_ini . '-' . $bulan_ini . '-01'));
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Laporan Keuangan Jemaat</title>

<style>

@page {
    size: A4 landscape;
    margin: 22mm 15mm;
}

body{
    font-family: Arial, Helvetica, sans-serif;
    color:#1e293b;
    background:white;
    margin:0;
    padding:0;
}

.header{
    display:flex;
    justify-content:space-between;
    align-items:center;
    border-bottom:3px solid #0f172a;
    padding-bottom:15px;
    margin-bottom:30px;
}

.logo{
    width:80px;
    height:80px;
    object-fit:contain;
}

.header-text{
    flex:1;
    text-align:center;
}

.header-text h1{
    margin:0;
    font-size:28px;
    color:#0f172a;
}

.header-text h2{
    margin:8px 0 0;
    font-size:18px;
    font-weight:600;
    color:#475569;
}

.header-text h3 {
    margin: 5px 0 0;
    font-size: 14px;
    font-weight: normal;
    color: #64748b;
}

.info-cetak{
    text-align:right;
    font-size:12px;
    color:#64748b;
}

.summary{
    display:flex;
    gap:20px;
    margin-bottom:25px;
}

.card{
    flex:1;
    border-radius:14px;
    padding:18px;
    color:white;
}

.card h4{
    margin:0 0 10px;
    font-size:14px;
    font-weight:600;
}

.card p{
    margin:0;
    font-size:24px;
    font-weight:bold;
}

.masuk{
    background:linear-gradient(135deg,#16a34a,#22c55e);
}

.keluar{
    background:linear-gradient(135deg,#dc2626,#ef4444);
}

.saldo{
    background:linear-gradient(135deg,#2563eb,#3b82f6);
}

.table-container{
    border-radius:16px;
    overflow:hidden;
    border:1px solid #cbd5e1;
}

table{
    width:100%;
    border-collapse:collapse;
}

thead{
    background:#0f172a;
    color:white;
}

thead th{
    padding:14px;
    font-size:13px;
    text-transform:uppercase;
    letter-spacing:0.5px;
}

tbody td{
    padding:12px;
    border-bottom:1px solid #e2e8f0;
    font-size:13px;
}

tbody tr:nth-child(even){
    background:#f8fafc;
}

.text-center{
    text-align:center;
}

.text-end{
    text-align:right;
}

.text-success{
    color:#16a34a;
    font-weight:bold;
}

.text-danger{
    color:#dc2626;
    font-weight:bold;
}

.badge-kategori {
    background-color: #f1f5f9;
    color: #475569;
    padding: 4px 8px;
    border-radius: 6px;
    font-size: 11px;
    border: 1px solid #e2e8f0;
    display: inline-block;
}

.footer{
    margin-top:50px;
    display:flex;
    justify-content:space-between;
}

.signature{
    text-align:center;
    width:250px;
}

.signature-space{
    height:80px;
}

.note{
    margin-top:25px;
    font-size:12px;
    color:#64748b;
    font-style:italic;
}

.print-button{
    position:fixed;
    top:20px;
    right:20px;
    background:#0f172a;
    color:white;
    border:none;
    padding:12px 18px;
    border-radius:10px;
    cursor:pointer;
    font-size:14px;
    z-index:9999;
}

@media print{

    .print-button{
        display:none;
    }

    body{
        -webkit-print-color-adjust: exact;
        print-color-adjust: exact;
    }
}

</style>
</head>

<body>

<button class="print-button" onclick="window.print()">
    Download / Print PDF
</button>

<div class="header">

    <div>
        <img src="assets/img/logo-gmim.png" class="logo" onerror="this.style.display='none'">
    </div>

    <div class="header-text">
        <h1>LAPORAN KEUANGAN JEMAAT</h1>
        <h2>GMIM Imanuel Bahu</h2>
        <h2><?php echo strtoupper($nama_bulan); ?></h2>
        <h3>Kategori: <?php echo ($kategori_filter === 'all') ? 'Semua Kategori (Rekapan)' : htmlspecialchars($kategori_filter); ?></h3>
    </div>

    <div class="info-cetak">
        Dicetak:<br>
        <?php echo date('d M Y H:i'); ?>
    </div>

</div>

<div class="summary">

    <div class="card masuk">
        <h4>Total Pemasukan</h4>
        <p>
            Rp <?php echo number_format($totalData['total_masuk'] ?? 0,0,',','.'); ?>
        </p>
    </div>

    <div class="card keluar">
        <h4>Total Pengeluaran</h4>
        <p>
            Rp <?php echo number_format($totalData['total_keluar'] ?? 0,0,',','.'); ?>
        </p>
    </div>

    <div class="card saldo">
        <h4>Saldo Kas Abadi</h4>
        <p>
            Rp <?php echo number_format($dataSaldo['saldo_akhir'] ?? 0,0,',','.'); ?>
        </p>
    </div>

</div>

<div class="table-container">

<table>

<thead>
<tr>
    <th width="5%">No</th>
    <th width="10%">Tanggal</th>
    <th width="15%">Kategori</th>
    <th width="15%">Pemasukan</th>
    <th width="15%">Pengeluaran</th>
    <th width="15%">Saldo</th>
    <th>Keterangan</th>
</tr>
</thead>

<tbody>

<?php
if(mysqli_num_rows($query) > 0):
$no = 1;
while($data = mysqli_fetch_assoc($query)):
?>

<tr>

<td class="text-center">
    <?php echo $no++; ?>
</td>

<td class="text-center">
    <?php echo date('d/m/Y', strtotime($data['tanggal'])); ?>
</td>

<td class="text-center">
    <span class="badge-kategori"><?php echo htmlspecialchars($data['kategori'] ?? '-'); ?></span>
</td>

<td class="text-end text-success">
    Rp <?php echo number_format($data['total_pemasukan'],0,',','.'); ?>
</td>

<td class="text-end text-danger">
    Rp <?php echo number_format($data['total_pengeluaran'],0,',','.'); ?>
</td>

<td class="text-end">
    <strong>
    Rp <?php echo number_format($data['saldo_akhir'],0,',','.'); ?>
    </strong>
</td>

<td>
    <?php echo nl2br(htmlspecialchars($data['keterangan'])); ?>
</td>

</tr>

<?php
endwhile;
else:
?>

<tr>
<td colspan="7" class="text-center" style="padding: 30px;">
    Tidak ada data keuangan untuk filter yang dipilih.
</td>
</tr>

<?php endif; ?>

</tbody>

</table>

</div>

<div class="note">
Laporan keuangan ini dihasilkan secara otomatis oleh Sistem Informasi Pelayanan Jemaat GMIM Imanuel Bahu.
</div>

<div class="footer">

<div class="signature">
    Mengetahui,
    <div class="signature-space"></div>
    <strong>Ketua BPMJ</strong>
</div>

<div class="signature">
    Bendahara Jemaat,
    <div class="signature-space"></div>
    <strong>Bendahara</strong>
</div>

</div>

<script>
window.onload = function(){
    setTimeout(() => {
        window.print();
    }, 700);
};
</script>

</body>
</html>