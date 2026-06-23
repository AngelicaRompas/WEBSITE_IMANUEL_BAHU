<?php
// admin_beranda.php
// File ini bisa langsung di-include ke dashboard utama Anda
?>

<style>
    /* ========================================================
       CSS INTERNAL - KHUSUS HALAMAN BERANDA ADMIN
       ======================================================== */
    .verse-card-admin {
        border-left: 10px solid #6f42c1;
    }

    .quote-icon-bg {
        font-size: 5rem; 
        color: #6f42c1;
    }

    .opacity-quote {
        opacity: 0.25;
    }

    .small-tracking {
        letter-spacing: 3px; 
        font-size: 0.8rem;
    }

    .verse-text-admin {
        font-family: 'Playfair Display', serif;
        font-size: clamp(1.2rem, 2.5vw, 1.5rem); /* Ukuran font fluid mengikuti lebar layar */
        line-height: 1.6;
    }

    /* Penyesuaian khusus untuk layar HP / Smartphone */
    @media (max-width: 575.98px) {
        .verse-card-admin {
            border-left: 6px solid #6f42c1; /* Menipiskan border kiri di HP agar pas */
        }
        
        .opacity-quote {
            opacity: 0.12; /* Diredupkan di HP agar teks di atasnya 100% terbaca tajam */
        }
        
        .quote-icon-bg {
            font-size: 3.5rem; /* Memperkecil ikon kutipan di sudut HP */
        }

        .small-tracking {
            letter-spacing: 1.5px;
            font-size: 0.75rem;
        }
    }
</style>

<div class="welcome-card p-4 p-md-5 mb-4">
    <h2 class="fw-bold mb-2">Selamat Datang, Admin!</h2>
    <p class="mb-0 opacity-75">
        Gunakan pusat kendali ini untuk memantau aktivitas dan memperbarui konten gereja secara cepat.
    </p>
</div>

<div class="row mt-4">
    <div class="col-12">
        <div class="dashboard-card verse-card-admin p-4 p-md-5 text-center position-relative overflow-hidden" 
             style="background: linear-gradient(135deg, #ffffff 0%, #f3effb 100%);">
            
            <div class="position-absolute top-0 end-0 p-2 p-md-3 opacity-quote">
                <i class="bi bi-quote quote-icon-bg"></i>
            </div>

            <div class="position-relative z-1">
                <h6 class="text-uppercase text-primary fw-bold mb-3 small-tracking">
                    Firman Tuhan untuk Pelayan
                </h6>
                <p class="verse-text-admin fst-italic text-dark mb-3">
                    "Karena kita ini buatan Allah, diciptakan dalam Kristus Yesus untuk melakukan pekerjaan baik, yang dipersiapkan Allah sebelumnya."
                </p>
                <div class="text-muted fw-bold small text-uppercase" style="letter-spacing: 1px;">
                    — Efesus 2:10
                </div>
            </div>
            
        </div>
    </div>
</div>