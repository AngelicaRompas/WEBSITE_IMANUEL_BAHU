<?php 
include 'navbar.php';
include 'koneksi.php'; 

// 1. Ambil SEMUA data statistik sekali saja
$queryAll = mysqli_query($koneksi, "SELECT label, jumlah, kategori FROM statistik");
$stats = [];
// Kita kelompokkan data per kategori agar fungsi lebih mudah bekerja
$dataPerKategori = []; 

while($row = mysqli_fetch_assoc($queryAll)) {
    $stats[$row['label']] = $row['jumlah'];
    $dataPerKategori[$row['kategori']][] = $row; 
}

$totalAnggota = isset($stats['Anggota']) && $stats['Anggota'] > 0 ? $stats['Anggota'] : 1;

// 2. Fungsi hitung tanpa query ulang
function getDataChart($kategori, $dataPerKategori, $total) {
    $labels = []; $data = [];
    
    // Pastikan kategori ada di dalam array
    if(isset($dataPerKategori[$kategori])) {
        foreach($dataPerKategori[$kategori] as $item) {
            $labels[] = $item['label'];
            // Pastikan data yang diambil benar-benar angka jiwa
            $data[] = (int)$item['jumlah']; 
        }
    }
    
    // Jika data kosong, kirim angka 0 agar tidak ambil data lain
    if(empty($data)) { return [[], [0, 0]]; }
    
    return [$labels, $data];
}

// 3. Panggil fungsi dengan argumen yang benar (tanpa $koneksi)
list($labelsGender, $dataGender) = getDataChart('gender', $dataPerKategori, $totalAnggota);
list($labelsBaptis, $dataBaptis) = getDataChart('baptis', $dataPerKategori, $totalAnggota);
list($labelsSidi, $dataSidi) = getDataChart('sidi', $dataPerKategori, $totalAnggota);
list($labelsBipra, $dataBipra) = getDataChart('bipra', $dataPerKategori, $totalAnggota);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Statistik & Struktur Jemaat - GMIM Imanuel Bahu</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style-beranda.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="assets/css/data-jemaat.css?v=<?php echo time(); ?>">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>

<section class="page-header-premium text-center position-relative z-3">
    <div class="container">
        <!-- Badge Atas -->
        <div data-aos="fade-down" data-aos-duration="800">
            <span class="badge rounded-pill px-3 py-2 small mb-3 fw-bold tracking-widest" style="background-color: #f3effb; color: #6f42c1; letter-spacing: 2px;">
                <i class="bi bi-bar-chart-fill me-2"></i>DATA & STATISTIK GEREJA
            </span>
        </div>
        
        <!-- Judul Utama -->
        <h1 class="main-title-aesthetic mb-2" data-aos="fade-down" data-aos-duration="1000" data-aos-delay="100">
            Statistik & Struktur Jemaat
        </h1>
        
        <!-- Garis Dekoratif (Divider) -->
        <div class="premium-divider" data-aos="zoom-in" data-aos-duration="800" data-aos-delay="200">
            <div class="line"></div>
            <div class="dot"></div>
            <div class="line"></div>
        </div>
        
        <!-- Sub Judul -->
        <p class="sub-title-aesthetic fw-medium mb-0" data-aos="fade-up" data-aos-duration="1000" data-aos-delay="300">
            Sinkronisasi Terakhir: <?php echo date('d F Y, H:i'); ?>
        </p>
    </div>
</section>

<div class="container container-stats px-4">
    
    <div class="row g-4 mb-4" data-aos="fade-up" data-aos-delay="400">
        <div class="col-md-4">
            <div class="card stat-card p-4">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <h6 class="text-muted fw-bold small text-uppercase tracking-wider mb-1">Total Kolom</h6>
                        <h2 class="fw-extrabold mb-0" style="color: #1e293b !important; text-shadow: none !important;"><?php echo number_format($stats['Kolom'] ?? 0, 0, ',', '.'); ?></h2>
                    </div>
                    <div class="icon-box bg-blue-light"><i class="bi bi-grid-3x3-gap-fill"></i></div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card stat-card p-4">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <h6 class="text-muted fw-bold small text-uppercase tracking-wider mb-1">Total Keluarga</h6>
                        <h2 class="fw-extrabold mb-0" style="color: #1e293b !important; text-shadow: none !important;"><?php echo number_format($stats['Keluarga'] ?? 0, 0, ',', '.'); ?></h2>
                    </div>
                    <div class="icon-box bg-green-light"><i class="bi bi-house-door-fill"></i></div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card stat-card p-4">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <h6 class="text-muted fw-bold small text-uppercase tracking-wider mb-1">Anggota Jemaat</h6>
                        <h2 class="fw-extrabold mb-0" style="color: #1e293b !important; text-shadow: none !important;"><?php echo number_format($stats['Anggota'] ?? 0, 0, ',', '.'); ?></h2>
                    </div>
                    <div class="icon-box bg-orange-light"><i class="bi bi-people-fill"></i></div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3 mb-5">
        <div class="col-sm-6 col-lg-3">
            <div class="card stat-card h-100">
                <div class="card-header-digital"><i class="bi bi-gender-ambiguous me-2 text-primary"></i>Komposisi Jenis Kelamin</div>
                <div class="card-body d-flex align-items-center justify-content-center">
                    <div class="chart-container"><canvas id="chartGender"></canvas></div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-lg-3">
            <div class="card stat-card h-100">
                <div class="card-header-digital"><i class="bi bi-water me-2 text-success"></i>Presentasi Status Baptis</div>
                <div class="card-body d-flex align-items-center justify-content-center">
                    <div class="chart-container"><canvas id="chartBaptis"></canvas></div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-lg-3">
            <div class="card stat-card h-100">
                <div class="card-header-digital"><i class="bi bi-patch-check me-2 text-info"></i>Presentasi Status Sidi</div>
                <div class="card-body d-flex align-items-center justify-content-center">
                    <div class="chart-container"><canvas id="chartSidi"></canvas></div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-lg-3">
            <div class="card stat-card h-100">
                <div class="card-header-digital"><i class="bi bi-diagram-3 me-2 text-warning"></i>Proporsi Kategorial (BIPRA)</div>
                <div class="card-body d-flex align-items-center justify-content-center">
                    <div class="chart-container"><canvas id="chartBipra"></canvas></div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4 pb-5">
        <div class="col-12">
            <div class="text-center mb-4">
                <h3 class="fw-bold text-white mb-1" style="text-shadow: 2px 2px 8px rgba(0,0,0,0.7);"><i class="bi bi-diagram-3-fill text-primary me-2"></i>Struktur Organisasi Jemaat</h3>
                <p class="text-white small mb-0" style="text-shadow: 1px 1px 4px rgba(0,0,0,0.7);">Susunan pelayan tata laksana administrasi jemaat aktif</p>
            </div>
            
            <div class="text-center mb-5">
                <ul class="nav nav-pills nav-pills-custom" id="pills-tab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="pills-bpmj-tab" data-bs-toggle="pill" data-bs-target="#pills-bpmj" type="button" role="tab">BPMJ & Pendeta</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="pills-pelsus-tab" data-bs-toggle="pill" data-bs-target="#pills-pelsus" type="button" role="tab">Pelayan Khusus Kolom</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="pills-komisi-tab" data-bs-toggle="pill" data-bs-target="#pills-komisi" type="button" role="tab">Komisi Gereja</button>
                    </li>
                </ul>
            </div>

            <div class="tab-content" id="pills-tabContent">
                <div class="tab-pane fade show active" id="pills-bpmj" role="tabpanel">

    <?php
    $getKetua = mysqli_query($koneksi, "SELECT * FROM struktur_organisasi WHERE jabatan='Ketua BPMJ' AND kategori='bpmj' LIMIT 1");
    $ketua = mysqli_fetch_assoc($getKetua);
    ?>

    <!-- KETUA BPMJ -->
    <div class="row g-4 justify-content-center text-center mb-5">
        <div class="col-sm-6 col-md-5 col-lg-3">

            <div class="card bpmj-card ketua-card">

                <img
                    src="<?php
                    if (!empty($ketua['foto']) && file_exists('assets/images/' . $ketua['foto'])) {
                        echo 'assets/images/' . $ketua['foto'];
                    } else {
                        echo 'assets/images/default-user.jpg';
                    }
                    ?>"
                    class="bpmj-photo"
                    alt="Ketua BPMJ"
                    data-bs-toggle="modal"
                    data-bs-target="#fotoModal"
                    onclick="showPhoto(this.src)"
                >

                <div class="bpmj-info text-center">
                    <div class="bpmj-name">
                        <?php echo $ketua ? htmlspecialchars($ketua['nama']) : 'Belum Diatur'; ?>
                    </div>

                    <div class="bpmj-position">
                        Ketua BPMJ
                    </div>
                </div>

            </div>

        </div>
    </div>

    <!-- PELAYAN INTI BPMJ -->
<div class="row g-4 justify-content-center text-center mb-5">

    <?php
    $getPerangkat = mysqli_query(
        $koneksi,
        "SELECT * FROM struktur_organisasi
         WHERE kategori='bpmj'
         AND (
                jabatan='Wakil Ketua'
                OR jabatan='Sekretaris'
                OR jabatan='Bendahara'
             )
         ORDER BY id ASC"
    );

    while($p = mysqli_fetch_assoc($getPerangkat)):
    ?>

    <div class="col-6 col-sm-6 col-md-4 col-lg-3 col-xl-2">

        <div class="card bpmj-card h-100">

            <img
                src="<?php
                if (!empty($p['foto']) && file_exists('assets/images/' . $p['foto'])) {
                    echo 'assets/images/' . $p['foto'];
                } else {
                    echo 'assets/images/default-user.jpg';
                }
                ?>"
                class="bpmj-photo"
                alt="<?php echo htmlspecialchars($p['nama']); ?>"
                data-bs-toggle="modal"
                data-bs-target="#fotoModal"
                onclick="showPhoto(this.src)"
            >

            <div class="bpmj-info text-center">

                <div class="bpmj-name">
                    <?php echo htmlspecialchars($p['nama']); ?>
                </div>

                <div class="bpmj-position">
                    <?php echo htmlspecialchars($p['jabatan']); ?>
                </div>

            </div>

        </div>

    </div>

    <?php endwhile; ?>

</div>


<!-- JUDUL ANGGOTA BPMJ -->
<div class="text-center mt-5 mb-4">
    <h5 class="fw-bold text-secondary">
        Anggota BPMJ
    </h5>
</div>


<!-- ANGGOTA BPMJ -->
<div class="row g-4 justify-content-center text-center mb-5">

    <?php
    $getAnggota = mysqli_query(
    $koneksi,
    "SELECT *
     FROM struktur_organisasi
     WHERE kategori='bpmj'
     AND jabatan NOT IN (
        'Ketua BPMJ',
        'Wakil Ketua',
        'Sekretaris',
        'Bendahara'
     )
     AND jabatan NOT LIKE '%Pendeta%'
     AND jabatan NOT LIKE '%Pdt%'
     ORDER BY id ASC"
     );

    while($a = mysqli_fetch_assoc($getAnggota)):
    ?>

    <div class="col-6 col-sm-6 col-md-4 col-lg-3 col-xl-2">

        <div class="card bpmj-card h-100">

            <img
                src="<?php
                if (!empty($a['foto']) && file_exists('assets/images/' . $a['foto'])) {
                    echo 'assets/images/' . $a['foto'];
                } else {
                    echo 'assets/images/default-user.jpg';
                }
                ?>"
                class="bpmj-photo"
                alt="<?php echo htmlspecialchars($a['nama']); ?>"
                data-bs-toggle="modal"
                data-bs-target="#fotoModal"
                onclick="showPhoto(this.src)"
            >

            <div class="bpmj-info text-center">

                <div class="bpmj-name">
                    <?php echo htmlspecialchars($a['nama']); ?>
                </div>

                <div class="bpmj-position">
                    <?php echo htmlspecialchars($a['jabatan']); ?>
                </div>

            </div>

        </div>

    </div>

    <?php endwhile; ?>

</div>

    <!-- PENDETA -->
    <div class="row g-4 justify-content-center text-center">

        <?php
        $getPendeta = mysqli_query(
            $koneksi,
            "SELECT * FROM struktur_organisasi
             WHERE kategori='bpmj'
             AND (jabatan LIKE '%Pendeta%' OR jabatan LIKE '%Pdt%')
             AND jabatan != 'Ketua BPMJ'
             ORDER BY id ASC"
        );

        while($pd = mysqli_fetch_assoc($getPendeta)):
        ?>

        <div class="col-6 col-sm-6 col-md-4 col-lg-3 col-xl-2">

            <div class="card bpmj-card h-100">

                <img
                    src="<?php
                    if (!empty($pd['foto']) && file_exists('assets/images/' . $pd['foto'])) {
                        echo 'assets/images/' . $pd['foto'];
                    } else {
                        echo 'assets/images/default-user.jpg';
                    }
                    ?>"
                    class="bpmj-photo"
                    alt="<?php echo htmlspecialchars($pd['nama']); ?>"
                    data-bs-toggle="modal"
                    data-bs-target="#fotoModal"
                    onclick="showPhoto(this.src)"
                >

                <div class="bpmj-info text-center">

                    <div class="bpmj-name">
                        <?php echo htmlspecialchars($pd['nama']); ?>
                    </div>

                    <div class="bpmj-position">
                        <?php echo htmlspecialchars($pd['jabatan']); ?>
                    </div>

                </div>

            </div>

        </div>

        <?php endwhile; ?>

    </div>

</div>

                <div class="tab-pane fade" id="pills-pelsus" role="tabpanel">
                    <div class="card stat-card p-4">
                        <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-3">
                            <h5 class="fw-bold mb-0 text-dark" style="font-size: 1.1rem;"><i class="bi bi-people-fill me-2 text-primary"></i>Daftar Pelayan Khusus Jemaat</h5>
                            <div class="input-group" style="max-width: 280px;">
                                <span class="input-group-text bg-light border-end-0"><i class="bi bi-search text-muted"></i></span>
                                <input type="text" id="cariKolom" class="form-control bg-light border-start-0 text-sm" placeholder="Cari nomor wilayah kolom...">
                            </div>
                        </div>
                        
                        <div class="table-responsive">
                            <table class="table table-hover align-middle border-0 mb-0" id="tabelPelsus">
                                <thead class="table-light text-secondary text-uppercase" style="font-size: 0.75rem; letter-spacing: 0.5px;">
                                    <tr>
                                        <th style="width: 20%; padding: 15px;">Kolom ID</th>
                                        <th style="padding: 15px;">Pelayan Struktural (Penatua)</th>
                                        <th style="padding: 15px;">Pelayan Diakonia (Diaken)</th>
                                    </tr>
                                </thead>
                                <tbody class="text-dark" style="font-size: 0.9rem;">
                                    <?php 
                                    $getJumlahKolomAktif = mysqli_query($koneksi, "SELECT DISTINCT kolom FROM struktur_organisasi WHERE kategori='pelsus' ORDER BY kolom ASC");
                                    while($kRow = mysqli_fetch_assoc($getJumlahKolomAktif)):
                                        $i = $kRow['kolom']; 

                                        $getPnt = mysqli_query($koneksi, "SELECT nama FROM struktur_organisasi WHERE kategori='pelsus' AND kolom='$i' AND jabatan='Penatua' LIMIT 1");
                                        $pnt = mysqli_fetch_assoc($getPnt);
                                        
                                        $getDkn = mysqli_query($koneksi, "SELECT nama FROM struktur_organisasi WHERE kategori='pelsus' AND kolom='$i' AND jabatan='Diaken' LIMIT 1");
                                        $dkn = mysqli_fetch_assoc($getDkn);
                                    ?>
                                    <tr>
                                        <td class="fw-bold text-primary" style="padding: 15px;">Kolom <?php echo $i; ?></td>
                                        <td style="padding: 15px;">
                                            <div class="d-flex align-items-center gap-2">
                                                <i class="bi bi-patch-check-fill text-success fs-5"></i>
                                                <span class="fw-semibold">Pnt. <?php echo !empty($pnt['nama']) ? htmlspecialchars($pnt['nama']) : '[Belum Diinput]'; ?></span>
                                            </div>
                                        </td>
                                        <td style="padding: 15px;">
                                            <div class="d-flex align-items-center gap-2">
                                                <i class="bi bi-patch-check text-info fs-5"></i>
                                                <span class="fw-semibold">Dkn. <?php echo !empty($dkn['nama']) ? htmlspecialchars($dkn['nama']) : '[Belum Diinput]'; ?></span>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php endwhile; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="tab-pane fade" id="pills-komisi" role="tabpanel">
    <div class="row g-4">

        <?php
        // 1. Definisikan array urutan kategori yang Anda inginkan
        $urutan_kategori = [
            "Penasihat BPMJ",
            "Komisi Pengawas Perbendaharaan",
            "Komisi Pelayanan Pria Kaum Bapa",
            "Komisi Pelayanan Wanita Kaum Ibu",
            "Komisi Pelayanan Pemuda",
            "Komisi Pelayanan Remaja",
            "Komisi Pelayanan Anak",
            "Komisi Kategorial Lansia",
            "Komisi Pendidikan",
            "Komisi Pengembalaan",
            "Komisi Pelayanan Doa & Pekabaran Injil",
            "Komisi Pembangunan",
            "Komisi Rumah Tangga & Kerja Bakti",
            "Komisi Liturgi & Kesenian Kerja",
            "Komisi Kesehatan",
            "Komisi Pemberdayaan Sumber Daya"
        ];
        
        // 2. Loop melalui array tersebut, bukan dari tabel kategori_komisi
        foreach($urutan_kategori as $namaKategori):
        ?>
        
        <div class="col-md-4 mb-4">
            <div class="card stat-card p-4 h-100 shadow-sm border-0"
                 style="background: rgba(255,255,255,0.9);">
        
                <h5 class="fw-bold text-primary mb-4 text-center border-bottom pb-2">
                    <?php echo htmlspecialchars($namaKategori); ?>
                </h5>
        
                <div class="d-flex flex-column gap-2">
        
                    <?php
                    // Query tetap mengambil data berdasarkan nama kategori yang sedang di-loop
                    $q = mysqli_query(
                        $koneksi,
                        "SELECT * 
                         FROM struktur_organisasi
                         WHERE kategori='$namaKategori'
                         ORDER BY id ASC"
                    );
        
                    if(mysqli_num_rows($q) > 0):
                        while($r = mysqli_fetch_assoc($q)):
                    ?>
        
                    <div class="p-2 border-bottom border-light">
                        <span class="fw-bold d-block">
                            <?php echo htmlspecialchars($r['nama']); ?>
                        </span>
                        <span class="text-muted" style="font-size:0.85rem;">
                            <?php echo htmlspecialchars($r['jabatan']); ?>
                        </span>
                    </div>
        
                    <?php
                        endwhile;
                    else:
                    ?>
        
                    <p class="text-muted text-center small">
                        Data belum tersedia.
                    </p>
        
                    <?php endif; ?>
        
                </div>
            </div>
        </div>
        
        <?php endforeach; ?>

    </div>
</div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="fotoModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content bg-transparent border-0">

            <div class="text-end mb-2">
                <button
                    type="button"
                    class="btn btn-light rounded-circle"
                    data-bs-dismiss="modal">
                    ✕
                </button>
            </div>

            <img
                id="popupImage"
                class="img-fluid rounded shadow-lg"
                alt="Preview Foto">
        </div>
    </div>
</div>

<script>

    Chart.defaults.font.family = "'Plus Jakarta Sans', -apple-system, sans-serif";
    Chart.defaults.plugins.legend.display = false; 
    Chart.defaults.plugins.tooltip.enabled = true; 

    // OPSI TOOLTIP KUSTOM
    const opsiTooltipKustom = {
        padding: 12,
        cornerRadius: 10,
        callbacks: {
            label: function(context) {
                return context.label + ': ' + context.raw + ' Jiwa';
            }
        }
    };

    // 1. CHART GENDER (Hanya deklarasikan SEKALI)
    new Chart(document.getElementById('chartGender'), {
        type: 'doughnut',
        data: {
            labels: <?php echo json_encode($labelsGender); ?>,
            datasets: [{
                data: <?php echo json_encode($dataGender); ?>, 
                backgroundColor: ['#2563eb', '#f59e0b'],
                borderWidth: 0, hoverOffset: 6
            }]
        },
        options: { 
            maintainAspectRatio: false, 
            cutout: '75%',
            plugins: { tooltip: opsiTooltipKustom } 
        }
    });

    // 2. CHART BAPTIS
    new Chart(document.getElementById('chartBaptis'), {
        type: 'doughnut',
        data: {
            labels: <?php echo json_encode($labelsBaptis); ?>,
            datasets: [{
                data: <?php echo json_encode($dataBaptis); ?>,
                backgroundColor: ['#16a34a', '#cbd5e1'],
                borderWidth: 0, hoverOffset: 6
            }]
        },
        options: { 
            maintainAspectRatio: false, 
            cutout: '75%',
            plugins: { tooltip: opsiTooltipKustom }
        }
    });

    // 3. CHART SIDI
    new Chart(document.getElementById('chartSidi'), {
        type: 'doughnut',
        data: {
            labels: <?php echo json_encode($labelsSidi); ?>,
            datasets: [{
                data: <?php echo json_encode($dataSidi); ?>,
                backgroundColor: ['#0dcaf0', '#6c757d'],
                borderWidth: 0, hoverOffset: 6
            }]
        },
        options: { 
            maintainAspectRatio: false, 
            cutout: '75%',
            plugins: { tooltip: opsiTooltipKustom }
        }
    });

    // 4. CHART BIPRA
    new Chart(document.getElementById('chartBipra'), {
        type: 'polarArea',
        data: {
            labels: <?php echo json_encode($labelsBipra); ?>,
            datasets: [{
                data: <?php echo json_encode($dataBipra); ?>,
                backgroundColor: ['rgba(0, 86, 179, 0.85)', 'rgba(25, 135, 84, 0.85)', 'rgba(214, 51, 132, 0.85)', 'rgba(102, 16, 242, 0.85)', 'rgba(255, 193, 7, 0.85)', 'rgba(108, 117, 125, 0.85)'],
                borderWidth: 1
            }]
        },
        options: { 
            maintainAspectRatio: false,
            plugins: { tooltip: opsiTooltipKustom }
        }
    });
    
    // 5. Live Search Table Kolom Pelsus
    const cariKolom = document.getElementById('cariKolom');

    if(cariKolom){
        cariKolom.addEventListener('keyup', function(){

            let filter = this.value.toUpperCase();
            let rows = document.getElementById('tabelPelsus').getElementsByTagName('tr');

            for(let i = 1; i < rows.length; i++){

                let td = rows[i].getElementsByTagName('td')[0];

                if(td){

                    let text = td.textContent || td.innerText;

                    rows[i].style.display =
                        text.toUpperCase().indexOf(filter) > -1 ? "" : "none";
                }
            }
        });
    }
</script>
<script>
function showPhoto(src){
    document.getElementById('popupImage').src = src;
}
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script>AOS.init({ once: true, offset: 50 });</script>

</body>
</html>