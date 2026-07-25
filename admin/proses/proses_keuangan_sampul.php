<?php
// admin/proses/proses_keuangan_sampul.php - Eksekusi Simpan Spesifik Per Kategori
session_start();
if (!isset($_SESSION['admin_imanuel'])) {
    header("Location: ../login.php"); 
    exit;
}
include '../../koneksi.php';

if (isset($_POST['simpan_sampul_single'])) {
    $tanggal          = mysqli_real_escape_string($koneksi, $_POST['tanggal']);
    $kategori_target  = mysqli_real_escape_string($koneksi, $_POST['kategori_target']);
    
    $kolom_arr        = $_POST['kolom'] ?? [];
    $keterangan_arr   = $_POST['keterangan'] ?? [];
    $nominal_arr      = $_POST['nominal'] ?? [];

    $koneksi->begin_transaction();
    try {
        // 1. Bersihkan HANYA data kategori terkait pada tanggal ini agar data kategori lain tetap aman
        $stmt_del = $koneksi->prepare("DELETE FROM keuangan_sampul_massal WHERE tanggal = ? AND kategori = ?");
        $stmt_del->bind_param("ss", $tanggal, $kategori_target);
        $stmt_del->execute();
        $stmt_del->close();

        // 2. Lakukan penyimpanan data baris demi baris yang dikirim
        $stmt_ins = $koneksi->prepare("INSERT INTO keuangan_sampul_massal (tanggal, kategori, kolom, keterangan, nominal) VALUES (?, ?, ?, ?, ?)");

        foreach ($keterangan_arr as $i => $ket) {
            $keterangan = htmlspecialchars($ket);
            $kolom_val  = $kolom_arr[$i];
            $no_kolom   = ($kolom_val !== '') ? intval($kolom_val) : 0;
            $nominal    = !empty($nominal_arr[$i]) ? floatval($nominal_arr[$i]) : 0;

            // Baris disimpan jika keterangan terisi atau nominalnya di atas 0
            if (!empty($keterangan) || $nominal > 0) {
                $stmt_ins->bind_param("ssisd", $tanggal, $kategori_target, $no_kolom, $keterangan, $nominal);
                $stmt_ins->execute();
            }
        }
        
        $stmt_ins->close();
        $koneksi->commit();

        header("Location: ../admin_dashboard.php?pesan=sukses_keuangan&tab=admin-keuangan&subtab=sampul&tgl_keuangan=$tanggal");
        exit;
    } catch (Exception $e) {
        $koneksi->rollback();
        die("Gagal memproses pengiriman sampul: " . $e->getMessage());
    }
} else {
    header("Location: ../admin_dashboard.php?tab=admin-keuangan&subtab=sampul");
    exit;
}
?>