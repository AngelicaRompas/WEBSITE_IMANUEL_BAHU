<?php
session_start();
if (!isset($_SESSION['admin_imanuel'])) { die(json_encode(["message" => "Akses ditolak."])); }
include '../../koneksi.php';
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['simpan_edit_bpmj'])) {
        $jabatan = mysqli_real_escape_string($koneksi, $_POST['jabatan']);
        $nama = mysqli_real_escape_string($koneksi, $_POST['nama_lengkap']);
        if (!empty($_FILES['foto_profil']['name'])) {
            $nama_foto = "profile_" . uniqid() . "." . pathinfo($_FILES['foto_profil']['name'], PATHINFO_EXTENSION);
            move_uploaded_file($_FILES['foto_profil']['tmp_name'], "../../assets/images/" . $nama_foto);
            mysqli_query($koneksi, "UPDATE struktur_organisasi SET nama='$nama', foto='$nama_foto' WHERE jabatan='$jabatan' AND kategori='bpmj'");
        } else {
            mysqli_query($koneksi, "UPDATE struktur_organisasi SET nama='$nama' WHERE jabatan='$jabatan' AND kategori='bpmj'");
        }
        echo json_encode(["message" => "Data BPMJ diperbarui."]);
    }
    elseif (isset($_POST['simpan_edit_pelsus'])) {
        $kolom = intval($_POST['nomor_kolom']);
        $pnt = mysqli_real_escape_string($koneksi, $_POST['nama_penatua']);
        $dkn = mysqli_real_escape_string($koneksi, $_POST['nama_diaken']);
        mysqli_query($koneksi, "UPDATE struktur_organisasi SET nama='$pnt' WHERE kolom='$kolom' AND jabatan='Penatua'");
        mysqli_query($koneksi, "UPDATE struktur_organisasi SET nama='$dkn' WHERE kolom='$kolom' AND jabatan='Diaken'");
        echo json_encode(["message" => "Data Pelayan Kolom $kolom diperbarui."]);
    }
    elseif (isset($_POST['simpan_tambah_bpmj'])) {
        $j = mysqli_real_escape_string($koneksi, $_POST['jabatan_baru']);
        mysqli_query($koneksi, "INSERT INTO struktur_organisasi (kategori, jabatan, nama, foto) VALUES ('bpmj', '$j', '', 'default-user.jpg')");
        echo json_encode(["message" => "Jabatan baru ditambahkan."]);
    }
    elseif (isset($_POST['simpan_tambah_kolom'])) {
        $k = intval($_POST['nomor_kolom_baru']);
        $p = mysqli_real_escape_string($koneksi, $_POST['nama_penatua_awal']);
        $d = mysqli_real_escape_string($koneksi, $_POST['nama_diaken_awal']);
        mysqli_query($koneksi, "INSERT INTO struktur_organisasi (kategori, jabatan, nama, kolom) VALUES ('pelsus', 'Penatua', '$p', '$k')");
        mysqli_query($koneksi, "INSERT INTO struktur_organisasi (kategori, jabatan, nama, kolom) VALUES ('pelsus', 'Diaken', '$d', '$k')");
        echo json_encode(["message" => "Kolom $k berhasil diresmikan."]);
    }
    elseif (isset($_POST['tambah_anggota_komisi'])) {
        $stmt = $koneksi->prepare("INSERT INTO struktur_organisasi (nama, jabatan, kategori) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $_POST['nama'], $_POST['jabatan'], $_POST['kategori']);
        $stmt->execute();
        echo json_encode(["message" => "Anggota komisi berhasil ditambah."]);
    }
    elseif (isset($_POST['edit_anggota_komisi'])) {
        $stmt = $koneksi->prepare("UPDATE struktur_organisasi SET nama=?, jabatan=?, kategori=? WHERE id=?");
        $stmt->bind_param("sssi", $_POST['nama'], $_POST['jabatan'], $_POST['kategori'], $_POST['id']);
        $stmt->execute();
        echo json_encode(["message" => "Data komisi diperbarui."]);
    }
}
elseif (isset($_GET['hapus_komisi'])) {
    mysqli_query($koneksi, "DELETE FROM struktur_organisasi WHERE id='".intval($_GET['id'])."'");
    header("Location: ../admin_dashboard.php?tab=admin-struktur");
}
?>