<?php
include '../../koneksi.php';

$subtab = $_GET['subtab'] ?? '';
$data = [];

switch ($subtab) {
    case 'minggu':
        $tanggal = mysqli_real_escape_string($koneksi, $_GET['tanggal']);
        $res = mysqli_query($koneksi, "SELECT * FROM keuangan_ibadah_minggu WHERE tanggal = '$tanggal'");
        while ($row = mysqli_fetch_assoc($res)) { $data[$row['sesi_ibadah']] = $row; }
        break;

    case 'kolom':
        $tanggal = mysqli_real_escape_string($koneksi, $_GET['tanggal']);
        $res = mysqli_query($koneksi, "SELECT * FROM keuangan_ibadah_kolom WHERE tanggal = '$tanggal'");
        while ($row = mysqli_fetch_assoc($res)) { $data[$row['kolom']] = $row; }
        break;

    case 'bipra':
        $tahun = mysqli_real_escape_string($koneksi, $_GET['tahun']);
        $komisi = mysqli_real_escape_string($koneksi, $_GET['komisi']);
        $res = mysqli_query($koneksi, "SELECT * FROM keuangan_bipra WHERE YEAR(tanggal) = '$tahun' AND komisi = '$komisi'");
        while ($row = mysqli_fetch_assoc($res)) { $data[$row['bulan_target']] = $row; }
        break;

    case 'khusus':
        $tanggal = mysqli_real_escape_string($koneksi, $_GET['tanggal']);
        $res = mysqli_query($koneksi, "SELECT * FROM keuangan_ibadah_khusus WHERE tanggal = '$tanggal'");
        while ($row = mysqli_fetch_assoc($res)) { $data[] = $row; }
        break;

    case 'pengeluaran':
        $tanggal = mysqli_real_escape_string($koneksi, $_GET['tanggal']);
        $res = mysqli_query($koneksi, "SELECT * FROM keuangan_pengeluaran WHERE tanggal = '$tanggal'");
        while ($row = mysqli_fetch_assoc($res)) { $data[] = $row; }
        break;

    case 'sampul':
        $tanggal = mysqli_real_escape_string($koneksi, $_GET['tanggal']);
        $kategori = mysqli_real_escape_string($koneksi, $_GET['kategori']);
        $res = mysqli_query($koneksi, "SELECT * FROM keuangan_sampul_massal WHERE tanggal = '$tanggal' AND kategori = '$kategori'");
        while ($row = mysqli_fetch_assoc($res)) { $data[] = $row; }
        break;
}

echo json_encode($data);
?>