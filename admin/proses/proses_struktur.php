<?php
session_start();

if (!isset($_SESSION['admin_imanuel'])) {
    die("Akses ditolak.");
}

include '../../koneksi.php';

// A. PROSES AJAX (BPMJ, Pelsus, Tambah Jabatan, Ekspansi Kolom)
if (isset($_POST['simpan_edit_bpmj']) || isset($_POST['simpan_edit_pelsus']) || isset($_POST['simpan_tambah_bpmj']) || isset($_POST['simpan_tambah_kolom'])) {
    
    header('Content-Type: application/json');

    if (isset($_POST['simpan_edit_bpmj'])) {
        $jabatan = mysqli_real_escape_string($koneksi, $_POST['jabatan']);
        $nama    = mysqli_real_escape_string($koneksi, $_POST['nama_lengkap']); 
        
        if ($_FILES['foto_profil']['error'] === 0) {
            $ext = strtolower(end(explode('.', $_FILES['foto_profil']['name'])));
            $nama_foto_baru = "profile_" . uniqid() . "." . $ext;
            if (move_uploaded_file($_FILES['foto_profil']['tmp_name'], "../../assets/images/" . $nama_foto_baru)) {
                $query = "UPDATE struktur_organisasi SET nama='$nama', foto='$nama_foto_baru' WHERE jabatan='$jabatan' AND kategori='bpmj'";
            }
        } else {
            $query = "UPDATE struktur_organisasi SET nama='$nama' WHERE jabatan='$jabatan' AND kategori='bpmj'";
        }
        echo mysqli_query($koneksi, $query) ? json_encode(["status" => "success", "message" => "Data BPMJ diperbarui."]) : json_encode(["status" => "error", "message" => mysqli_error($koneksi)]);
    }

    elseif (isset($_POST['simpan_edit_pelsus'])) {
        $kolom = intval($_POST['nomor_kolom']);
        $pnt = mysqli_real_escape_string($koneksi, $_POST['nama_penatua']);
        $dkn = mysqli_real_escape_string($koneksi, $_POST['nama_diaken']);
        mysqli_query($koneksi, "UPDATE struktur_organisasi SET nama='$pnt' WHERE kolom='$kolom' AND jabatan='Penatua'");
        mysqli_query($koneksi, "UPDATE struktur_organisasi SET nama='$dkn' WHERE kolom='$kolom' AND jabatan='Diaken'");
        echo json_encode(["status" => "success", "message" => "Data Pelsus diperbarui."]);
    }

    elseif (isset($_POST['simpan_tambah_bpmj'])) {
        $j = mysqli_real_escape_string($koneksi, trim($_POST['jabatan_baru']));
        mysqli_query($koneksi, "INSERT INTO struktur_organisasi (kategori, jabatan, nama, foto) VALUES ('bpmj', '$j', '', 'default-user.jpg')");
        echo json_encode(["status" => "success", "message" => "Jabatan baru ditambahkan."]);
    }

    elseif (isset($_POST['simpan_tambah_kolom'])) {
        $k = intval($_POST['nomor_kolom_baru']);
        $p = mysqli_real_escape_string($koneksi, $_POST['nama_penatua_awal']);
        $d = mysqli_real_escape_string($koneksi, $_POST['nama_diaken_awal']);
        mysqli_query($koneksi, "INSERT INTO struktur_organisasi (kategori, jabatan, nama, kolom) VALUES ('pelsus', 'Penatua', '$p', '$k')");
        mysqli_query($koneksi, "INSERT INTO struktur_organisasi (kategori, jabatan, nama, kolom) VALUES ('pelsus', 'Diaken', '$d', '$k')");
        echo json_encode(["status" => "success", "message" => "Kolom baru berhasil ditambahkan."]);
    }
    exit;
}

// B. PROSES KONVENSIONAL (Anggota Komisi - Redirect dengan Alert)
elseif (isset($_POST['tambah_anggota_komisi'])) {
    $stmt = $koneksi->prepare("INSERT INTO struktur_organisasi (nama, jabatan, kategori) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $_POST['nama'], $_POST['jabatan'], $_POST['kategori']);
    $stmt->execute();
    header("Location: ../admin_dashboard.php?tab=admin-struktur&pesan=sukses_tambah");
    exit;
}

elseif (isset($_POST['edit_anggota_komisi'])) {
    $stmt = $koneksi->prepare("UPDATE struktur_organisasi SET nama=?, jabatan=?, kategori=? WHERE id=?");
    $stmt->bind_param("sssi", $_POST['nama'], $_POST['jabatan'], $_POST['kategori'], $_POST['id']);
    $stmt->execute();
    header("Location: ../admin_dashboard.php?tab=admin-struktur&pesan=sukses_edit");
    exit;
}

elseif (isset($_GET['hapus_komisi'])) {
    $stmt = $koneksi->prepare("DELETE FROM struktur_organisasi WHERE id = ?");
    $stmt->bind_param("i", $_GET['id']);
    $stmt->execute();
    header("Location: ../admin_dashboard.php?tab=admin-struktur&pesan=sukses_hapus");
    exit;
}

else {
    header("Location: ../admin_dashboard.php?tab=admin-struktur");
    exit;
}
?>