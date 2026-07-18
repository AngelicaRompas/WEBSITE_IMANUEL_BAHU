<?php
session_start();
if (!isset($_SESSION['admin_imanuel'])) { 
    header("Location: ../login.php"); exit; 
}
include '../../koneksi.php';

// Logika tunggal untuk Statistik Jemaat
if (isset($_POST['update_data_jemaat'])) {
    $total_anggota = intval($_POST['jml_anggota']) > 0 ? intval($_POST['jml_anggota']) : 1;
    $data_to_update = [
        'Kolom' => $_POST['jml_kolom'], 'Keluarga' => $_POST['jml_keluarga'], 'Anggota' => $_POST['jml_anggota'],
        'Laki-laki' => $_POST['jiwa_pria'], 'Perempuan' => $_POST['jiwa_wanita'], 'Sudah Baptis' => $_POST['jiwa_baptis'],
        'Belum Baptis' => $_POST['jiwa_belum_baptis'], 'Sudah Sidi' => $_POST['jiwa_sidi'], 'Belum Sidi' => $_POST['jiwa_belum_sidi'],
        'P/KB' => $_POST['jiwa_pkb'], 'W/KI' => $_POST['jiwa_wki'], 'Pemuda' => $_POST['jiwa_pemuda'],
        'Remaja' => $_POST['jiwa_remaja'], 'ASM' => $_POST['jiwa_asm'], 'Lansia' => $_POST['jiwa_lansia']
    ];

    foreach ($data_to_update as $label => $jumlah) {
        $val = intval($jumlah);
        $persen = in_array($label, ['Kolom', 'Keluarga', 'Anggota']) ? 0 : round(($val / $total_anggota) * 100, 1);
        $stmt = $koneksi->prepare("UPDATE statistik SET jumlah = ?, persentase = ? WHERE label = ?");
        $stmt->bind_param("ids", $val, $persen, $label);
        $stmt->execute();
    }
    header("Location: ../admin_dashboard.php?pesan=sukses&tab=edit-data-jemaat");
    exit;
}

// Fallback
header("Location: ../admin_dashboard.php?tab=edit-data-jemaat");
exit;
?>