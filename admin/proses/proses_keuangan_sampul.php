<?php
// proses_keuangan_sampul.php
session_start();
if (!isset($_SESSION['admin_imanuel'])) {
    header("Location: ../login.php"); 
    exit;
}
include '../../koneksi.php';

if (isset($_POST['simpan_sampul_massal'])) {
    $tanggal = mysqli_real_escape_string($koneksi, $_POST['tanggal']);
    
    // Daftar 5 kategori yang dilemparkan form
    $daftar_kategori = ['persepuluhan', 'hut_pribadi', 'pernikahan', 'lainnya', 'bulanan_keluarga'];

    $koneksi->begin_transaction();
    try {
        // 1. Bersihkan seluruh rekaman data lama pada tanggal ini di tabel sampul
        $stmt_del = $koneksi->prepare("DELETE FROM keuangan_sampul_massal WHERE tanggal = ?");
        $stmt_del->bind_param("s", $tanggal);
        $stmt_del->execute();
        $stmt_del->close();

        // 2. Siapkan perintah insert data kolektif
        $stmt_ins = $koneksi->prepare("INSERT INTO keuangan_sampul_massal (tanggal, kategori, kolom, keterangan, nominal) VALUES (?, ?, ?, ?, ?)");

        foreach ($daftar_kategori as $kat) {
            // Cek apakah data array kategori terkait terkirim
            if (isset($_POST[$kat . '_kolom'])) {
                $kolom_arr       = $_POST[$kat . '_kolom'];
                $keterangan_arr  = $_POST[$kat . '_keterangan'];
                $nominal_arr     = $_POST[$kat . '_nominal'];

                foreach ($kolom_arr as $i => $kolom) {
                    $no_kolom   = intval($kolom);
                    $keterangan = htmlspecialchars($keterangan_arr[$i]);
                    $nominal    = !empty($nominal_arr[$i]) ? floatval($nominal_arr[$i]) : 0;

                    // Simpan baris hanya jika nominal di atas 0 atau keterangan tidak kosong
                    if ($nominal > 0 || !empty($keterangan)) {
                        $stmt_ins->bind_param("ssisd", $tanggal, $kat, $no_kolom, $keterangan, $nominal);
                        $stmt_ins->execute();
                    }
                }
            }
        }
        
        $stmt_ins->close();
        $koneksi->commit();

        header("Location: ../admin_dashboard.php?pesan=sukses_keuangan&tab=edit-keuangan&subtab=sampul&tgl_keuangan=$tanggal");
        exit;
    } catch (Exception $e) {
        $koneksi->rollback();
        die("Gagal memproses kumpulan sampul: " . $e->getMessage());
    }
} else {
    header("Location: ../admin_dashboard.php?tab=edit-keuangan&subtab=sampul");
    exit;
}
?>