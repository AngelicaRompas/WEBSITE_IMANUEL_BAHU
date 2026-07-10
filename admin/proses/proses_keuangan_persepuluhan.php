<?php
// proses_keuangan_persepuluhan.php
session_start();
if (!isset($_SESSION['admin_imanuel'])) {
    header("Location: ../login.php"); 
    exit;
}
include '../../koneksi.php';

if (isset($_POST['simpan_persepuluhan'])) {
    $tanggal = mysqli_real_escape_string($koneksi, $_POST['tanggal']);
    
    $kolom_arr       = $_POST['kolom'];
    $keterangan_arr  = $_POST['keterangan'];
    $nominal_arr     = $_POST['nominal'];

    $koneksi->begin_transaction();
    try {
        // Karena jumlah baris dinamis, bersihkan data lama di tanggal ini terlebih dahulu
        $stmt_del = $koneksi->prepare("DELETE FROM keuangan_persepuluhan WHERE tanggal = ?");
        $stmt_del->bind_param("s", $tanggal);
        $stmt_del->execute();
        $stmt_del->close();

        // Masukkan massal baris data baru yang diinput admin
        $stmt_ins = $koneksi->prepare("INSERT INTO keuangan_persepuluhan (tanggal, kolom, keterangan, nominal) VALUES (?, ?, ?, ?)");
        
        foreach ($kolom_arr as $i => $kolom) {
            $no_kolom   = intval($kolom);
            $keterangan = htmlspecialchars($keterangan_arr[$i]);
            $nominal    = !empty($nominal_arr[$i]) ? floatval($nominal_arr[$i]) : 0;

            // Simpan baris hanya jika nominal di atas 0 atau keterangan terisi
            if ($nominal > 0 || !empty($keterangan)) {
                $stmt_ins->bind_param("sisd", $tanggal, $no_kolom, $keterangan, $nominal);
                $stmt_ins->execute();
            }
        }
        $stmt_ins->close();
        $koneksi->commit();

        header("Location: ../admin_dashboard.php?pesan=sukses_keuangan&tab=edit-keuangan&subtab=persepuluhan&tgl_keuangan=$tanggal");
        exit;
    } catch (Exception $e) {
        $koneksi->rollback();
        die("Gagal menyimpan data persepuluhan: " . $e->getMessage());
    }
} else {
    header("Location: ../admin_dashboard.php?tab=edit-keuangan&subtab=persepuluhan");
    exit;
}
?>