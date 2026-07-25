<?php
session_start();

if (!isset($_SESSION['admin_imanuel'])) {
    header("Location: ../../login.php");
    exit;
}

include '../../koneksi.php';

/* =====================================================
   SIMPAN / UPDATE KEUANGAN IBADAH MINGGU
===================================================== */

if (isset($_POST['simpan_keuangan_minggu'])) {

    $tanggal = mysqli_real_escape_string($koneksi, $_POST['tgl_keuangan']);

    $sesi_arr            = $_POST['sesi'];
    $pundi_1_arr         = $_POST['pundi_1'];
    $pundi_2_arr         = $_POST['pundi_2'];
    $saranaprasarana_arr = $_POST['saranaprasarana'];

    $koneksi->begin_transaction();

    try {

        foreach ($sesi_arr as $i => $nama_sesi) {

            $nama_sesi = mysqli_real_escape_string($koneksi, $nama_sesi);

            $pundi_1 = !empty($pundi_1_arr[$i])
                ? floatval($pundi_1_arr[$i])
                : 0;

            $pundi_2 = !empty($pundi_2_arr[$i])
                ? floatval($pundi_2_arr[$i])
                : 0;

            $sapras = !empty($saranaprasarana_arr[$i])
                ? floatval($saranaprasarana_arr[$i])
                : 0;

            $jumlah_sesi = $pundi_1 + $pundi_2 + $sapras;

            /* =========================================
               CEK DATA SUDAH ADA / BELUM
            ========================================= */

            $cek_existing = mysqli_query(
                $koneksi,
                "SELECT id_keuangan
                 FROM keuangan_ibadah_minggu
                 WHERE tanggal='$tanggal'
                 AND sesi_ibadah='$nama_sesi'"
            );

            if (!$cek_existing) {
                throw new Exception(mysqli_error($koneksi));
            }

            /* =========================================
               UPDATE
            ========================================= */

            if (mysqli_num_rows($cek_existing) > 0) {

                $query = "
                    UPDATE keuangan_ibadah_minggu
                    SET
                        pundi_1='$pundi_1',
                        pundi_2='$pundi_2',
                        sarana_prasarana='$sapras',
                        jumlah_sesi='$jumlah_sesi'
                    WHERE
                        tanggal='$tanggal'
                    AND
                        sesi_ibadah='$nama_sesi'
                ";

            }

            /* =========================================
               INSERT
            ========================================= */

            else {

                $query = "
                    INSERT INTO keuangan_ibadah_minggu
                    (
                        tanggal,
                        sesi_ibadah,
                        pundi_1,
                        pundi_2,
                        sarana_prasarana,
                        jumlah_sesi
                    )
                    VALUES
                    (
                        '$tanggal',
                        '$nama_sesi',
                        '$pundi_1',
                        '$pundi_2',
                        '$sapras',
                        '$jumlah_sesi'
                    )
                ";

            }

            if (!mysqli_query($koneksi, $query)) {
                throw new Exception(mysqli_error($koneksi));
            }
        }

        /* =========================================
           SEMUA BERHASIL
        ========================================= */

        $koneksi->commit();

        header("Location: ../admin_dashboard.php?" . http_build_query([
            'tab'           => 'edit-keuangan',
            'subtab'        => 'minggu',
            'tgl_keuangan'  => $tanggal,
            'pesan'         => 'sukses_keuangan'
        ]));

        exit;

    } catch (Exception $e) {

        $koneksi->rollback();

        die("Terjadi kesalahan : " . $e->getMessage());

    }

}

/* =====================================================
   AKSES LANGSUNG TANPA POST
===================================================== */

header("Location: ../admin_dashboard.php?" . http_build_query([
    'tab'          => 'edit-keuangan',
    'subtab'       => 'minggu',
    'tgl_keuangan' => date('Y-m-d')
]));

exit;
?>