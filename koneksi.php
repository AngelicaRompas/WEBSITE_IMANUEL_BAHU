<?php
// Konfigurasi Database
$host     = "localhost";
$username = "root";
$password = "";
$database = "web_imanuel_bahu";
$port     = 3307; // Sesuaikan dengan port MySQL Anda yang sedang berjalan

// Membuat koneksi ke database
$koneksi = mysqli_connect($host, $username, $password, $database, $port);

// Cek apakah koneksi berhasil
if (!$koneksi) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

// Jika berhasil (opsional: bisa dihapus jika sudah masuk tahap produksi)
// echo "Koneksi database berhasil!";
?>