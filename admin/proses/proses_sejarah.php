<?php
session_start();
include '../../koneksi.php';

if (!isset($_SESSION['admin_imanuel'])) {
    header("Location: ../login.php");
    exit;
}

$aksi = $_POST['aksi'] ?? $_GET['aksi'] ?? '';

// 1. PROSES UPDATE SEJARAH (AJAX)
if ($aksi == 'update_sejarah') {
    $konten = $_POST['konten_sejarah'] ?? '';
    $stmt = $koneksi->prepare("UPDATE profil SET konten = ? WHERE jenis = 'sejarah'");
    $stmt->bind_param("s", $konten);
    echo json_encode(['status' => $stmt->execute() ? 'success' : 'error']);
    exit;
}

// 2. PROSES SAVE KETUA (Standar dengan Redirect)
elseif ($aksi == 'save_ketua') {
    $id = $_POST['id'] ?? null;
    $nama = $_POST['nama'] ?? '';
    $mulai = $_POST['tahun_mulai'] ?? '';
    $selesai = $_POST['tahun_selesai'] ?? '';
    $foto = $_POST['foto_lama'] ?? '';

    if (!empty($_FILES['foto']['name'])) {
        $nama_file = time() . '_' . basename($_FILES['foto']['name']);
        if (move_uploaded_file($_FILES['foto']['tmp_name'], "../../assets/images/" . $nama_file)) {
            if (!empty($_POST['foto_lama']) && file_exists("../../assets/images/" . $_POST['foto_lama'])) {
                unlink("../../assets/images/" . $_POST['foto_lama']);
            }
            $foto = $nama_file;
        }
    }

    if ($id) {
        $stmt = $koneksi->prepare("UPDATE ketua_jemaat SET nama=?, tahun_mulai=?, tahun_selesai=?, foto=? WHERE id=?");
        $stmt->bind_param("ssssi", $nama, $mulai, $selesai, $foto, $id);
    } else {
        $stmt = $koneksi->prepare("INSERT INTO ketua_jemaat (nama, tahun_mulai, tahun_selesai, foto) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $nama, $mulai, $selesai, $foto);
    }
    
    if ($stmt->execute()) {
        header("Location: ../admin_dashboard.php?tab=admin-sejarah&pesan=sukses_ketua");
    } else {
        header("Location: ../admin_dashboard.php?tab=admin-sejarah&pesan=error");
    }
    exit;
}

// 3. PROSES HAPUS KETUA (Standar dengan Redirect)
elseif ($aksi == 'hapus_ketua') {
    $id = $_GET['id'] ?? 0;
    $res = mysqli_query($koneksi, "SELECT foto FROM ketua_jemaat WHERE id='$id'");
    if ($d = mysqli_fetch_assoc($res)) {
        if (!empty($d['foto']) && file_exists("../../assets/images/" . $d['foto'])) {
            unlink("../../assets/images/" . $d['foto']);
        }
    }
    
    if (mysqli_query($koneksi, "DELETE FROM ketua_jemaat WHERE id='$id'")) {
        header("Location: ../admin_dashboard.php?tab=admin-sejarah&pesan=hapus_sukses");
    } else {
        header("Location: ../admin_dashboard.php?tab=admin-sejarah&pesan=error");
    }
    exit;
}
?>