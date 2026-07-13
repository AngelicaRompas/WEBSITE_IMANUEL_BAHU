<?php
// admin/proses/proses_keuangan_bipra.php
session_start();
if (!isset($_SESSION['admin_imanuel'])) {
    header("Location: ../login.php"); 
    exit;
}
include '../../koneksi.php';

if (isset($_POST['simpan_keuangan_bipra'])) {
    $tahun_periode = mysqli_real_escape_string($koneksi, $_POST['tahun_periode']);
    $komisi        = mysqli_real_escape_string($koneksi, $_POST['komisi']);
    
    $bulan_target_arr = $_POST['bulan_target'] ?? [];
    $tanggal_setor_arr = $_POST['tanggal_setor'] ?? [];
    $nominal_arr      = $_POST['nominal'] ?? [];

    foreach ($bulan_target_arr as $i => $bulan_target) {
        $bulan_target = intval($bulan_target);
        $tanggal_line = mysqli_real_escape_string($koneksi, $tanggal_setor_arr[$i]);
        $nominal      = !empty($nominal_arr[$i]) ? floatval($nominal_arr[$i]) : 0;

        // Cek data existing untuk kombinasi komisi dan bulan laporan target di tahun terkait
        $cek = mysqli_query($koneksi, "SELECT id_bipra FROM keuangan_bipra WHERE YEAR(tanggal) = '$tahun_periode' AND komisi = '$komisi' AND bulan_target = '$bulan_target'");
        
        if (mysqli_num_rows($cek) > 0) {
            // Update nominal dan perbarui tanggal setoran sesuai input dinamis baris terkait
            $query = "UPDATE keuangan_bipra SET tanggal = '$tanggal_line', nominal = '$nominal' WHERE YEAR(tanggal) = '$tahun_periode' AND komisi = '$komisi' AND bulan_target = '$bulan_target'";
        } else {
            // Masukkan data baru lengkap dengan tanggal baris terkait
            $query = "INSERT INTO keuangan_bipra (tanggal, komisi, bulan_target, nominal) VALUES ('$tanggal_line', '$komisi', '$bulan_target', '$nominal')";
        }
        mysqli_query($koneksi, $query);
    }

    // Kembalikan redirect ke parameter tahun filter agar data yang baru disimpan langsung termuat stabil
    header("Location: ../admin_dashboard.php?pesan=sukses_keuangan&tab=edit-keuangan&subtab=bipra&tgl_keuangan=" . $tahun_periode . "-01-01&komisi_pilih=$komisi");
    exit;
} else {
    header("Location: ../admin_dashboard.php?tab=edit-keuangan&subtab=bipra");
    exit;
}
?>