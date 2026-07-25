<?php
session_start();

if (!isset($_SESSION['admin_imanuel'])) {
    header("Location: ../login.php");
    exit;
}

include '../../koneksi.php';

if (isset($_POST['simpan_renungan'])) {
    // Amankan input dari karakter berbahaya
    $tanggal      = mysqli_real_escape_string($koneksi, $_POST['tanggal']);
    $tema         = mysqli_real_escape_string($koneksi, $_POST['judul']);
    $pembacaan    = mysqli_real_escape_string($koneksi, $_POST['nas_alkitab']);
    $renungan     = mysqli_real_escape_string($koneksi, $_POST['isi_renungan']);
    $doa          = mysqli_real_escape_string($koneksi, $_POST['doa']);

    // Query simpan ke database
    $sql = "INSERT INTO renungan_harian (tanggal, judul, nas_alkitab, isi_renungan, doa) 
            VALUES ('$tanggal', '$tema', '$pembacaan', '$renungan', '$doa')";

    if (mysqli_query($koneksi, $sql)) {
        header("Location: ../admin_dashboard.php?pesan=sukses_renungan&tab=admin-renungan");
        exit;
    } else {
        die("Error Database: " . mysqli_error($koneksi));
    }
} 
else {

    header("Location: ../admin_dashboard.php?tab=admin-renungan");
    exit;
}
?>