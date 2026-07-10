<?php
// proses_keuangan_kolom.php
session_start();
if (!isset($_SESSION['admin_imanuel'])) {
    header("Location: ../login.php"); 
    exit;
}
include '../../koneksi.php';

if (isset($_POST['simpan_keuangan_kolom'])) {
    $tanggal = mysqli_real_escape_string($koneksi, $_POST['tanggal']);
    
    $kolom_arr    = $_POST['kolom_no'];
    $pers_arr     = $_POST['pers_kolom'];
    $pkb_arr      = $_POST['pkb'];
    $wki_arr      = $_POST['wki'];
    $pemuda_arr   = $_POST['pemuda'];
    $remaja_arr   = $_POST['remaja'];
    $asm_arr      = $_POST['asm'];
    $pdp_arr      = $_POST['pdp'];
    $pem_arr      = $_POST['pem'];
    $awal_bln_arr = $_POST['awal_bln']; // Tangkap Array Baru

    foreach ($kolom_arr as $i => $no_kolom) {
        $no_kolom  = intval($no_kolom);
        $pers      = !empty($pers_arr[$i]) ? floatval($pers_arr[$i]) : 0;
        $pkb       = !empty($pkb_arr[$i]) ? floatval($pkb_arr[$i]) : 0;
        $wki       = !empty($wki_arr[$i]) ? floatval($wki_arr[$i]) : 0;
        $pemuda    = !empty($pemuda_arr[$i]) ? floatval($pemuda_arr[$i]) : 0;
        $remaja    = !empty($remaja_arr[$i]) ? floatval($remaja_arr[$i]) : 0;
        $asm       = !empty($asm_arr[$i]) ? floatval($asm_arr[$i]) : 0;
        $pdp       = !empty($pdp_arr[$i]) ? floatval($pdp_arr[$i]) : 0;
        $pem       = !empty($pem_arr[$i]) ? floatval($pem_arr[$i]) : 0;
        $awal_bln  = !empty($awal_bln_arr[$i]) ? floatval($awal_bln_arr[$i]) : 0; // Bersyarat (Jika kosong = 0)
        
        // Jumlah total baris kalkulasi backend
        $jumlah_kolom = $pers + $pkb + $wki + $pemuda + $remaja + $asm + $pdp + $pem + $awal_bln;

        $cek = mysqli_query($koneksi, "SELECT id_kolom FROM keuangan_ibadah_kolom WHERE tanggal = '$tanggal' AND kolom = '$no_kolom'");
        
        if (mysqli_num_rows($cek) > 0) {
            $query = "UPDATE keuangan_ibadah_kolom SET 
                        pers_kolom = '$pers', pkb = '$pkb', wki = '$wki', 
                        pemuda = '$pemuda', remaja = '$remaja', asm = '$asm', 
                        pdp = '$pdp', pem = '$pem', awal_bln = '$awal_bln', jumlah = '$jumlah_kolom' 
                      WHERE tanggal = '$tanggal' AND kolom = '$no_kolom'";
        } else {
            $query = "INSERT INTO keuangan_ibadah_kolom (
                        tanggal, kolom, pers_kolom, pkb, wki, pemuda, remaja, asm, pdp, pem, awal_bln, jumlah
                      ) VALUES (
                        '$tanggal', '$no_kolom', '$pers', '$pkb', '$wki', '$pemuda', '$remaja', '$asm', '$pdp', '$pem', '$awal_bln', '$jumlah_kolom'
                      )";
        }
        mysqli_query($koneksi, $query);
    }

    header("Location: ../admin_dashboard.php?pesan=sukses_keuangan&tab=edit-keuangan&subtab=kolom&tgl_keuangan=$tanggal");
    exit;
} else {
    header("Location: ../admin_dashboard.php?tab=edit-keuangan&subtab=kolom");
    exit;
}
?>