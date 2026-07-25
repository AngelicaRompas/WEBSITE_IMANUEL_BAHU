<?php
// admin/proses/proses_keuangan.php
session_start();
if (!isset($_SESSION['admin_imanuel'])) {
    header("Location: ../login.php"); 
    exit;
}
include '../../koneksi.php';

// Fungsi sinkronisasi saldo akhir
function sinkronisasi_saldo_total($koneksi) {
    $query = mysqli_query($koneksi, "SELECT id, total_pemasukan, total_pengeluaran FROM warta_keuangan ORDER BY tanggal ASC, id ASC");
    
    $saldo_berjalan = 0;
    while ($row = mysqli_fetch_assoc($query)) {
        $in  = (int)$row['total_pemasukan'];
        $out = (int)$row['total_pengeluaran'];
        $saldo_berjalan += ($in - $out);
        
        $upd = $koneksi->prepare("UPDATE warta_keuangan SET saldo_akhir = ? WHERE id = ?");
        $upd->bind_param("ii", $saldo_berjalan, $row['id']);
        $upd->execute();
        $upd->close();
    }
}

// PROSES TAMBAH & EDIT
if (isset($_POST['simpan_keuangan'])) {
    $id = isset($_POST['id']) ? intval($_POST['id']) : 0;
    $tanggal = mysqli_real_escape_string($koneksi, $_POST['tanggal']);
    $kategori = mysqli_real_escape_string($koneksi, $_POST['kategori']);
    $keterangan = htmlspecialchars($_POST['keterangan']);
    
    // Validasi input angka agar tidak error jika kosong
    $in = !empty($_POST['total_pemasukan']) ? intval(str_replace(['.', ','], '', $_POST['total_pemasukan'])) : 0;
    $out = !empty($_POST['total_pengeluaran']) ? intval(str_replace(['.', ','], '', $_POST['total_pengeluaran'])) : 0;

    $koneksi->begin_transaction();
    try {
        if ($id > 0) {
            $stmt = $koneksi->prepare("UPDATE warta_keuangan SET tanggal=?, kategori=?, total_pemasukan=?, total_pengeluaran=?, keterangan=? WHERE id=?");
            $stmt->bind_param("ssiisi", $tanggal, $kategori, $in, $out, $keterangan, $id);
            $stmt->execute();
            $stmt->close();
        } else {
            $stmt = $koneksi->prepare("INSERT INTO warta_keuangan (tanggal, kategori, total_pemasukan, total_pengeluaran, keterangan) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param("ssiis", $tanggal, $kategori, $in, $out, $keterangan);
            $stmt->execute();
            $stmt->close();
        }

        sinkronisasi_saldo_total($koneksi);
        $koneksi->commit();
        
        // Redirect kembali ke tab utama keuangan (admin-keuangan)
        header("Location: ../admin_dashboard.php?pesan=sukses_keuangan&tab=admin-keuangan&subtab=minggu");
    } catch (Exception $e) {
        $koneksi->rollback();
        die("Error: " . $e->getMessage());
    }
    exit;
}

// PROSES HAPUS
if (isset($_GET['aksi']) && $_GET['aksi'] == 'hapus' && isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $koneksi->begin_transaction();
    try {
        $stmt = $koneksi->prepare("DELETE FROM warta_keuangan WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->close();
        
        sinkronisasi_saldo_total($koneksi);
        $koneksi->commit();
        
        // Redirect kembali ke tab utama keuangan
        header("Location: ../admin_dashboard.php?pesan=hapus_sukses_keuangan&tab=admin-keuangan&subtab=minggu");
    } catch (Exception $e) {
        $koneksi->rollback();
        die("Error: " . $e->getMessage());
    }
    exit;
}

// PROSES AMBIL DATA (JSON)
if (isset($_GET['aksi']) && $_GET['aksi'] == 'ambil_data' && isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $query = mysqli_query($koneksi, "SELECT * FROM warta_keuangan WHERE id = '$id'");
    header('Content-Type: application/json');
    echo json_encode(mysqli_fetch_assoc($query));
    exit;
}
?>