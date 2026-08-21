<?php 
$pesan = $_GET['pesan'] ?? '';
$alerts = [
    'sukses_jemaat'          => ['primary', 'Data Jemaat berhasil diperbarui!'],
    'sukses_event'           => ['primary', 'Data Event/Agenda berhasil disimpan!'],
    'sukses_hapus_event'     => ['info', 'Data Event telah dihapus.'],
    'sukses_sejarah'         => ['primary', 'Narasi sejarah berhasil diperbarui!'],
    'sukses_ketua'           => ['primary', 'Data Ketua Jemaat berhasil disimpan!'],
    'hapus_sukses'           => ['info', 'Data Ketua Jemaat berhasil dihapus.'],
    'sukses_warta'           => ['primary', 'Dokumen Warta Jemaat berhasil diunggah!'],
    'sukses_keuangan'        => ['primary', 'Laporan pembukuan kas masuk & keluar keuangan jemaat berhasil dipublikasikan!'],
    'hapus_sukses_keuangan'  => ['info', 'Data laporan keuangan berhasil dihapus.'],
    'sukses_struktur'        => ['success', 'Pembaruan posisi pelayan struktur berhasil disimpan!'],
    'sukses_tambah_struktur' => ['success', 'Data pelayan atau kolom baru berhasil ditambahkan ke dalam struktur!'],
    'sukses_renungan'        => ['success', 'Renungan baru berhasil dipublikasikan!'],
    'sukses_tambah'          => ['success', 'Data anggota komisi berhasil ditambahkan!'],
    'sukses_edit'            => ['success', 'Data anggota komisi berhasil diperbarui!'],
    'sukses_hapus'           => ['info', 'Data anggota komisi berhasil dihapus.'],
    'sukses_kategori'      => ['success', 'Kategori komisi baru berhasil ditambahkan!'],
    'sukses_hapus_kategori' => ['info', 'Kategori komisi berhasil dihapus.'],
    'sukses_edit_kategori' => ['success', 'Kategori komisi berhasil diperbarui!'],
    'kategori_sudah_ada'   => ['warning', 'Kategori komisi tersebut sudah ada.'],
    'kategori_kosong'      => ['warning', 'Nama kategori tidak boleh kosong.'],
    'kategori_dipakai'     => ['danger', 'Kategori tidak dapat dihapus karena masih digunakan oleh anggota komisi.'],
    'sukses_artikel' => ['primary', 'Artikel atau informasi warta berhasil disimpan!'],
    'sukses_hapus_artikel' => ['info', 'Artikel atau informasi warta berhasil dihapus.'],
    ];

if (array_key_exists($pesan, $alerts)): 
    $type = $alerts[$pesan][0];
    $msg = $alerts[$pesan][1];
?>
    <div id="dynamic-alert" class="alert alert-<?php echo $type; ?> alert-dismissible fade show shadow border-0 py-3 mb-4" 
         style="background: #eef2ff; color: #4338ca; border-left: 5px solid #4338ca !important;">
        <i class="bi bi-stars me-2"></i> <strong>Pemberitahuan:</strong> <?php echo $msg; ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>

    <script>
    document.querySelectorAll('.nav-link-admin').forEach(link => {
        link.addEventListener('click', function() {
            const alertEl = document.getElementById('dynamic-alert');
            if (alertEl) {
                alertEl.style.display = 'none';
                const url = new URL(window.location);
                url.searchParams.delete('pesan');
                window.history.replaceState({}, document.title, url.toString());
            }
        });
    });
    </script>
<?php endif; ?>