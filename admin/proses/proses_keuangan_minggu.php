<?php
session_start();
if (!isset($_SESSION['admin_imanuel'])) {
    header("Location: ../../login.php"); 
    exit;
}
include '../../koneksi.php';

if (isset($_POST['simpan_keuangan_minggu'])) {
    $tanggal = mysqli_real_escape_string($koneksi, $_POST['tanggal']);
    
    $sesi_arr            = $_POST['sesi'];
    $pundi_1_arr         = $_POST['pundi_1'];
    $pundi_2_arr         = $_POST['pundi_2'];
    $saranaprasarana_arr = $_POST['saranaprasarana'];

    foreach ($sesi_arr as $i => $nama_sesi) {
        $nama_sesi = mysqli_real_escape_string($koneksi, $nama_sesi);
        $pundi_1   = !empty($pundi_1_arr[$i]) ? floatval($pundi_1_arr[$i]) : 0;
        $pundi_2   = !empty($pundi_2_arr[$i]) ? floatval($pundi_2_arr[$i]) : 0;
        $sapras    = !empty($saranaprasarana_arr[$i]) ? floatval($saranaprasarana_arr[$i]) : 0;
        
        $jumlah_sesi = $pundi_1 + $pundi_2 + $sapras;

        // PINTAR: Cek apakah kombinasi tanggal dan sesi tersebut sudah ada di tabel
        $cek_existing = mysqli_query($koneksi, "SELECT id_keuangan FROM keuangan_ibadah_minggu WHERE tanggal = '$tanggal' AND sesi_ibadah = '$nama_sesi'");
        
        if (mysqli_num_rows($cek_existing) > 0) {
            // Jika data sudah ada, langsung timpa / UPDATE data tersebut
            $query = "UPDATE keuangan_ibadah_minggu SET 
                        pundi_1 = '$pundi_1', 
                        pundi_2 = '$pundi_2', 
                        sarana_prasarana = '$sapras', 
                        jumlah_sesi = '$jumlah_sesi' 
                      WHERE tanggal = '$tanggal' AND sesi_ibadah = '$nama_sesi'";
        } else {
            // Jika data belum ada pada tanggal itu, buat baris data baru / INSERT
            $query = "INSERT INTO keuangan_ibadah_minggu (
                        tanggal, sesi_ibadah, pundi_1, pundi_2, sarana_prasarana, jumlah_sesi
                      ) VALUES (
                        '$tanggal', '$nama_sesi', '$pundi_1', '$pundi_2', '$sapras', '$jumlah_sesi'
                      )";
        }
        
        mysqli_query($koneksi, $query);
    }

    // Kembalikan ke halaman edit-keuangan dengan membawa tanggal yang sedang aktif agar form tidak reset ke hari ini
    header("Location: ../admin_dashboard.php?pesan=sukses_keuangan&tab=edit-keuangan&tgl_keuangan=$tanggal");
    exit;
} else {
    header("Location: ../admin_dashboard.php?tab=edit-keuangan");
    exit;
}
?>