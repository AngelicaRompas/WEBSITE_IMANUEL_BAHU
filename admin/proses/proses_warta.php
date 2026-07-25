<?php
session_start();
if (!isset($_SESSION['admin_imanuel'])) {
    header("Location: ../../login.php"); 
    exit;
}
include '../../koneksi.php';

if (isset($_POST['upload_warta'])) {
    // 1. Amankan Data Umum
    $tanggal           = mysqli_real_escape_string($koneksi, $_POST['tanggal']);
    $tema_mingguan     = mysqli_real_escape_string($koneksi, $_POST['tema_mingguan']);
    $pembacaan_alkitab = mysqli_real_escape_string($koneksi, $_POST['pembacaan_alkitab']);
    
    // 2. Upload File PDF Utama
    $file_name  = $_FILES['file_pdf']['name'];
    $file_tmp   = $_FILES['file_pdf']['tmp_name'];
    $ekstensi   = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
    
    if ($ekstensi == 'pdf') {
        $nama_file_baru = "warta_" . $tanggal . "_" . uniqid() . ".pdf";
        $dir_pdf = "../../assets/document_warta/";
        
        if (!is_dir($dir_pdf)) {
            mkdir($dir_pdf, 0755, true);
        }
        
        $folder_tujuan = $dir_pdf . $nama_file_baru;

        if (move_uploaded_file($file_tmp, $folder_tujuan)) {
            
            // 3. Proses Insert untuk setiap Sesi (I, II, III)
            foreach (['I', 'II', 'III'] as $sesi) {
                // Amankan Input Data per Sesi
                $khadim        = mysqli_real_escape_string($koneksi, $_POST["khadim_sesi_$sesi"]);
                $penerima      = mysqli_real_escape_string($koneksi, $_POST["penerima_sesi_$sesi"]);
                $doa           = mysqli_real_escape_string($koneksi, $_POST["doa_sesi_$sesi"]);
                $puji          = mysqli_real_escape_string($koneksi, $_POST["puji_sesi_$sesi"]);
                // Tangkap data baru KPI dan Doa Persembahan
                $kpi           = mysqli_real_escape_string($koneksi, $_POST["kpi_sesi_$sesi"]);
                $doa_persemb   = mysqli_real_escape_string($koneksi, $_POST["doapersembahan_sesi_$sesi"]);
                
                // Penanganan Upload Foto Khadim
                $foto_khadim = "";
                if (!empty($_FILES["foto_sesi_$sesi"]['name'])) {
                    $f_name = $_FILES["foto_sesi_$sesi"]['name'];
                    $f_tmp  = $_FILES["foto_sesi_$sesi"]['tmp_name'];
                    $f_ext  = strtolower(pathinfo($f_name, PATHINFO_EXTENSION));
                    $foto_khadim = "foto_" . $sesi . "_" . uniqid() . "." . $f_ext;
                    
                    $dir_foto = "../assets/images-khadim/";
                    if (!is_dir($dir_foto)) {
                        mkdir($dir_foto, 0755, true);
                    }
                    move_uploaded_file($f_tmp, $dir_foto . $foto_khadim);
                }
                
                // Masukkan ke database warta_jemaat termasuk kolom baru
                $query = "INSERT INTO warta_jemaat (
                            tanggal, tema_mingguan, pembacaan_alkitab, 
                            sesi_ibadah, nama_khadim, foto_khadim, penerima_jemaat, 
                            doa_pembacaan, puji_pujian, kpi, doa_persembahan, file_pdf
                          ) VALUES (
                            '$tanggal', '$tema_mingguan', '$pembacaan_alkitab',
                            'Ibadah Sesi $sesi', '$khadim', '$foto_khadim', '$penerima', 
                            '$doa', '$puji', '$kpi', '$doa_persemb', '$nama_file_baru'
                          )";
                
                mysqli_query($koneksi, $query);
            }

            header("Location: ../admin_dashboard.php?pesan=sukses_warta&tab=admin-warta");
            exit;
            
        } else {
            header("Location: ../admin_dashboard.php?pesan=error_upload&tab=admin-warta");
            exit;
        }
    } else {
        header("Location: ../admin_dashboard.php?pesan=error_ekstensi&tab=admin-warta");
        exit;
    }
} else {
    header("Location: ../admin_dashboard.php?tab=admin-warta");
    exit;
}
?>