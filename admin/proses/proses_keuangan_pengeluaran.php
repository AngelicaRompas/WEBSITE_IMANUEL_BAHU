<?php
// proses_keuangan_pengeluaran.php
session_start();
if (!isset($_SESSION['admin_imanuel'])) {
    header("Location: ../login.php"); 
    exit;
}
include '../../koneksi.php';

if (isset($_POST['simpan_pengeluaran'])) {
    $tanggal = mysqli_real_escape_string($koneksi, $_POST['tanggal']);
    
    $keterangan_arr = $_POST['keterangan'] ?? [];
    $nominal_arr    = $_POST['nominal'] ?? [];

    $koneksi->begin_transaction();
    try {
        // Hapus data lama pada tanggal terkait agar tidak terjadi duplikasi item
        $stmt_del = $koneksi->prepare("DELETE FROM keuangan_pengeluaran WHERE tanggal = ?");
        $stmt_del->bind_param("s", $tanggal);
        $stmt_del->execute();
        $stmt_del->close();

        // Siapkan perintah simpan massal baris pengeluaran baru
        $stmt_ins = $koneksi->prepare("INSERT INTO keuangan_pengeluaran (tanggal, keterangan, nominal) VALUES (?, ?, ?)");
        
        foreach ($keterangan_arr as $i => $keterangan) {
            $ket_belanja = htmlspecialchars($keterangan);
            $nominal     = !empty($nominal_arr[$i]) ? floatval($nominal_arr[$i]) : 0;

            // Baris disimpan jika pos keterangan terisi atau nilai uang di atas 0
            if (!empty($ket_belanja) || $nominal > 0) {
                $stmt_ins->bind_param("ssd", $tanggal, $ket_belanja, $nominal);
                $stmt_ins->execute();
            }
        }
        $stmt_ins->close();
        $koneksi->commit();

        header("Location: ../admin_dashboard.php?pesan=sukses_keuangan&tab=admin-keuangan&subtab=pengeluaran&tgl_keuangan=$tanggal");
        exit;
    } catch (Exception $e) {
        $koneksi->rollback();
        die("Gagal menyimpan data pengeluaran kas: " . $e->getMessage());
    }
} else {
    header("Location: ../admin_dashboard.php?tab=admin-keuangan&subtab=pengeluaran");
    exit;
}
?>