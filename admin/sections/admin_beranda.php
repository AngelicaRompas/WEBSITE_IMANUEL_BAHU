<?php
// admin_beranda.php
// File ini dipanggil (include) ke dalam dashboard utama Anda
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
        font-size: clamp(1.2rem, 2.5vw, 1.5rem);
        line-height: 1.6;
    }

    /* Penyesuaian khusus untuk layar HP / Smartphone */
    @media (max-width: 575.98px) {
        .verse-card-admin {
            border-left: 6px solid #6f42c1;
        }
        
        .opacity-quote {
            opacity: 0.12;
        }
        
        .quote-icon-bg {
            font-size: 3.5rem;
        }

        .small-tracking {
            letter-spacing: 1.5px;
            font-size: 0.75rem;
        }
    }
</style>

<!-- Pembungkus Grid Utama: Mengunci lebar agar responsive dan tidak melar ke kanan -->
<div class="row g-4">

    <!-- 1. KARTU UCAPAN SELAMAT DATANG (WELCOME CARD) -->
    <div class="col-12">
        <div class="welcome-card p-4 p-md-5">
            <h2 class="fw-bold mb-2">Selamat Datang, Admin!</h2>
            <p class="mb-0 opacity-75">
                Gunakan pusat kendali ini untuk memantau aktivitas dan memperbarui konten gereja secara cepat.
            </p>
        </div>
    </div>

    <!-- 2. KARTU AYAT FIRMAN TUHAN -->
    <div class="col-12 mt-2">
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