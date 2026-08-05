<?php
session_start();
if (!isset($_SESSION['admin_imanuel'])) {
    header("Location: ../../login.php"); 
    exit;
}
include '../../koneksi.php';

if (isset($_POST['upload_warta'])) {
    // 1. Amankan Data Umum
    $tanggal          = mysqli_real_escape_string($koneksi, $_POST['tanggal']);
    $tema_mingguan    = mysqli_real_escape_string($koneksi, $_POST['tema_mingguan']);
    $pembacaan_alkitab = mysqli_real_escape_string($koneksi, $_POST['pembacaan_alkitab']);
    
    // 2. Upload File PDF Warta Utama
    $file_name  = $_FILES['file_pdf']['name'];
    $file_tmp   = $_FILES['file_pdf']['tmp_name'];
    $ekstensi   = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

    // 3. Upload File Tata Ibadah (Baru)
    $tata_name  = $_FILES['file_tata_ibadah']['name'];
    $tata_tmp   = $_FILES['file_tata_ibadah']['tmp_name'];
    $tata_ext   = strtolower(pathinfo($tata_name, PATHINFO_EXTENSION));

    // 4. Upload Cover Warta Utama (Baru - Landscape/Potrait)
    $cover_name = $_FILES['cover_warta']['name'];
    $cover_tmp  = $_FILES['cover_warta']['tmp_name'];
    $cover_ext  = strtolower(pathinfo($cover_name, PATHINFO_EXTENSION));
    
    // Validasi Ekstensi Dokumen
    if ($ekstensi == 'pdf' && in_array($tata_ext, ['pdf', 'doc', 'docx']) && in_array($cover_ext, ['jpg', 'jpeg', 'png', 'webp'])) {
        
        $nama_file_baru = "warta_" . $tanggal . "_" . uniqid() . ".pdf";
        $nama_tata_baru = "tata_ibadah_" . $tanggal . "_" . uniqid() . "." . $tata_ext;
        $nama_cover_baru = "cover_" . $tanggal . "_" . uniqid() . "." . $cover_ext;

        $dir_pdf = "../../assets/document_warta/";
        $dir_tata = "../../assets/document_tata_ibadah/";
        $dir_cover = "../../assets/images_cover/";
        
        // Buat folder jika belum ada
        if (!is_dir($dir_pdf)) { mkdir($dir_pdf, 0755, true); }
        if (!is_dir($dir_tata)) { mkdir($dir_tata, 0755, true); }
        if (!is_dir($dir_cover)) { mkdir($dir_cover, 0755, true); }
        
        $folder_pdf = $dir_pdf . $nama_file_baru;
        $folder_tata = $dir_tata . $nama_tata_baru;
        $folder_cover = $dir_cover . $nama_cover_baru;

        // Eksekusi pemindahan file fisik
        if (move_uploaded_file($file_tmp, $folder_pdf) && 
            move_uploaded_file($tata_tmp, $folder_tata) && 
            move_uploaded_file($cover_tmp, $folder_cover)) {
            
            // 5. Proses Insert untuk setiap Sesi (I, II, III)
            foreach (['I', 'II', 'III'] as $sesi) {
                // Amankan Input Data per Sesi
                $khadim         = mysqli_real_escape_string($koneksi, $_POST["khadim_sesi_$sesi"]);
                $penerima       = mysqli_real_escape_string($koneksi, $_POST["penerima_sesi_$sesi"]);
                $doa            = mysqli_real_escape_string($koneksi, $_POST["doa_sesi_$sesi"]);
                $puji           = mysqli_real_escape_string($koneksi, $_POST["puji_sesi_$sesi"]);
                $kpi            = mysqli_real_escape_string($koneksi, $_POST["kpi_sesi_$sesi"]);
                $doa_persemb    = mysqli_real_escape_string($koneksi, $_POST["doapersembahan_sesi_$sesi"]);
                
                // Penanganan Upload Foto Khadim (Format Landscape)
                $foto_khadim = "";
                if (!empty($_FILES["foto_sesi_$sesi"]['name'])) {
                    $f_name = $_FILES["foto_sesi_$sesi"]['name'];
                    $f_tmp  = $_FILES["foto_sesi_$sesi"]['tmp_name'];
                    $f_ext  = strtolower(pathinfo($f_name, PATHINFO_EXTENSION));
                    $foto_khadim = "foto_khadim_" . $sesi . "_" . uniqid() . "." . $f_ext;
                    
                    $dir_foto = "../../assets/images-khadim/";
                    if (!is_dir($dir_foto)) {
                        mkdir($dir_foto, 0755, true);
                    }
                    move_uploaded_file($f_tmp, $dir_foto . $foto_khadim);
                }
                
                // Masukkan ke database warta_jemaat
                // Catatan: Pastikan struktur tabel Anda sudah memiliki kolom: cover_warta dan file_tata_ibadah
                $query = "INSERT INTO warta_jemaat (
                            tanggal, tema_mingguan, pembacaan_alkitab, cover_warta,
                            sesi_ibadah, nama_khadim, foto_khadim, penerima_jemaat, 
                            doa_pembacaan, puji_pujian, kpi, doa_persembahan, file_pdf, file_tata_ibadah
                          ) VALUES (
                            '$tanggal', '$tema_mingguan', '$pembacaan_alkitab', '$nama_cover_baru',
                            'Ibadah Sesi $sesi', '$khadim', '$foto_khadim', '$penerima', 
                            '$doa', '$puji', '$kpi', '$doa_persemb', '$nama_file_baru', '$nama_tata_baru'
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