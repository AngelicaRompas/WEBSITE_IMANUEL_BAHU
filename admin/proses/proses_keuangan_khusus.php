<?php
// proses_keuangan_khusus.php
session_start();
if (!isset($_SESSION['admin_imanuel'])) {
    header("Location: ../login.php"); 
    exit;
}
include '../../koneksi.php';

if (isset($_POST['simpan_khusus'])) {
    $tanggal = mysqli_real_escape_string($koneksi, $_POST['tanggal']);
    
    $kegiatan_arr = $_POST['kegiatan'];
    $nominal_arr  = $_POST['nominal'];

    $koneksi->begin_transaction();
    try {
        // Hapus rekaman data lama pada tanggal terpilih agar tidak duplikat
        $stmt_del = $koneksi->prepare("DELETE FROM keuangan_ibadah_khusus WHERE tanggal = ?");
        $stmt_del->bind_param("s", $tanggal);
        $stmt_del->execute();
        $stmt_del->close();

        // Siapkan perintah insert data baru massal
        $stmt_ins = $koneksi->prepare("INSERT INTO keuangan_ibadah_khusus (tanggal, kegiatan, nominal) VALUES (?, ?, ?)");
        
        foreach ($kegiatan_arr as $i => $kegiatan) {
            $nama_kegiatan = htmlspecialchars($kegiatan);
            $nominal       = !empty($nominal_arr[$i]) ? floatval($nominal_arr[$i]) : 0;

            // Simpan baris hanya jika nama kegiatan terisi atau nominal di atas 0
            if (!empty($nama_kegiatan) || $nominal > 0) {
                $stmt_ins->bind_param("ssd", $tanggal, $nama_kegiatan, $nominal);
                $stmt_ins->execute();
            }
        }
        $stmt_ins->close();
        $koneksi->commit();

        // Kembalikan ke halaman dashboard subtab khusus dengan aman
        header("Location: ../admin_dashboard.php?pesan=sukses_keuangan&tab=edit-keuangan&subtab=khusus&tgl_keuangan=$tanggal");
        exit;
    } catch (Exception $e) {
        $koneksi->rollback();
        die("Gagal menyimpan data ibadah khusus: " . $e->getMessage());
    }
} else {
    header("Location: ../admin_dashboard.php?tab=edit-keuangan&subtab=khusus");
    exit;
}
?>