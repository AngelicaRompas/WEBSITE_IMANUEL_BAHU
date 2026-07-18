<?php
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['admin_imanuel'])) {
    echo json_encode(["status" => "error", "message" => "Sesi Anda telah berakhir. Silakan login kembali."]);
    exit;
}

include '../../koneksi.php';

// ========================================================
// 1. PROSES EDIT / UPDATE DATA BPMJ & PENDETA EXISTING
// ========================================================
if (isset($_POST['simpan_edit_bpmj'])) {
    $jabatan = mysqli_real_escape_string($koneksi, $_POST['jabatan']);
    $nama    = mysqli_real_escape_string($koneksi, $_POST['nama_lengkap']); 
    
    $file_name  = $_FILES['foto_profil']['name'];
    $file_tmp   = $_FILES['foto_profil']['tmp_name'];
    $file_error = $_FILES['foto_profil']['error'];

    if ($file_error === 0) {
        $x = explode('.', $file_name);
        $ekstensi = strtolower(end($x));
        $nama_foto_baru = "profile_" . uniqid() . "." . $ekstensi;
        $folder_tujuan  = "../../assets/images/" . $nama_foto_baru;

        if (move_uploaded_file($file_tmp, $folder_tujuan)) {
            $query = "UPDATE struktur_organisasi SET nama='$nama', foto='$nama_foto_baru' WHERE jabatan='$jabatan' AND kategori='bpmj'";
        } else {
            echo json_encode(["status" => "error", "message" => "Gagal mengunggah foto ke direktori server."]);
            exit;
        }
    } else {
        $query = "UPDATE struktur_organisasi SET nama='$nama' WHERE jabatan='$jabatan' AND kategori='bpmj'";
    }

    if (mysqli_query($koneksi, $query)) {
        echo json_encode(["status" => "success", "message" => "Pembalasan posisi pelayan struktur berhasil disimpan!"]);
        exit;
    } else {
        echo json_encode(["status" => "error", "message" => "Gagal memperbarui database BPMJ: " . mysqli_error($koneksi)]);
        exit;
    }
}

// ========================================================
// 2. PROSES EDIT / UPDATE DATA PELSUS PER KOLOM EXISTING
// ========================================================
elseif (isset($_POST['simpan_edit_pelsus'])) {
    $kolom        = intval($_POST['nomor_kolom']);
    $nama_penatua = mysqli_real_escape_string($koneksi, $_POST['nama_penatua']);
    $nama_diaken  = mysqli_real_escape_string($koneksi, $_POST['nama_diaken']);

    $queryPnt = "UPDATE struktur_organisasi SET nama='$nama_penatua' WHERE kolom='$kolom' AND jabatan='Penatua'";
    $execPnt = mysqli_query($koneksi, $queryPnt);

    $queryDkn = "UPDATE struktur_organisasi SET nama='$nama_diaken' WHERE kolom='$kolom' AND jabatan='Diaken'";
    $execDkn = mysqli_query($koneksi, $queryDkn);

    if ($execPnt && $execDkn) {
        echo json_encode(["status" => "success", "message" => "Pembalasan posisi pelayan struktur berhasil disimpan!"]);
        exit;
    } else {
        echo json_encode(["status" => "error", "message" => "Gagal memperbarui database Pelsus Kolom: " . mysqli_error($koneksi)]);
        exit;
    }
}

// ========================================================
// 3. PROSES TAMBAH JABATAN BARU (TANPA NAMA & FOTO)
// ========================================================
elseif (isset($_POST['simpan_tambah_bpmj'])) {
    $jabatan_baru = mysqli_real_escape_string($koneksi, trim($_POST['jabatan_baru']));

    $cek = mysqli_query(
        $koneksi,
        "SELECT id FROM struktur_organisasi WHERE kategori='bpmj' AND jabatan='$jabatan_baru'"
    );

    if (mysqli_num_rows($cek) > 0) {
        echo json_encode(["status" => "error", "message" => "Jabatan tersebut sudah ada."]);
        exit;
    }

    $query = "INSERT INTO struktur_organisasi (kategori, jabatan, nama, foto) 
              VALUES ('bpmj', '$jabatan_baru', '', 'default-user.jpg')";

    if (mysqli_query($koneksi, $query)) {
        echo json_encode(["status" => "success", "message" => "Data pelayan atau kolom baru berhasil ditambahkan ke dalam struktur!"]);
        exit;
    } else {
        echo json_encode(["status" => "error", "message" => "Gagal menambah jabatan baru: " . mysqli_error($koneksi)]);
        exit;
    }
}

// ========================================================
// 4. FITUR BARU: PROSES TAMBAH EXPANSION KOLOM BARU (PELSUS)
// ========================================================
elseif (isset($_POST['simpan_tambah_kolom'])) {
    $kolom_baru   = intval($_POST['nomor_kolom_baru']);
    $nama_penatua = mysqli_real_escape_string($koneksi, $_POST['nama_penatua_awal']); 
    $nama_diaken  = mysqli_real_escape_string($koneksi, $_POST['nama_diaken_awal']); 

    $insertPnt = "INSERT INTO struktur_organisasi (kategori, jabatan, nama, kolom) VALUES ('pelsus', 'Penatua', '$nama_penatua', '$kolom_baru')";
    $execInsertPnt = mysqli_query($koneksi, $insertPnt);

    $insertDkn = "INSERT INTO struktur_organisasi (kategori, jabatan, nama, kolom) VALUES ('pelsus', 'Diaken', '$nama_diaken', '$kolom_baru')";
    $execInsertDkn = mysqli_query($koneksi, $insertDkn);

    if ($execInsertPnt && $execInsertDkn) {
        echo json_encode(["status" => "success", "message" => "Data pelayan atau kolom baru berhasil ditambahkan ke dalam struktur!"]);
        exit;
    } else {
        echo json_encode(["status" => "error", "message" => "Gagal mengekspansi wilayah kolom baru: " . mysqli_error($koneksi)]);
        exit;
    }
}

else {
    echo json_encode(["status" => "error", "message" => "Akses langsung terblokir."]);
    exit;
}
?>