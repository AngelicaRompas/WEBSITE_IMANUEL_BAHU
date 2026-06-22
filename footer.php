<!-- footer.php -->
<style>
    /* Animasi Khusus Footer */
    .footer-premium {
        background: linear-gradient(135deg, #6f42c1 0%, #4a2c85 100%);
        border-top: 4px solid #a17fe0;
    }
    .footer-title {
        font-family: 'Playfair Display', serif;
        color: #ffd700; /* Warna Emas Premium */
        letter-spacing: 1px;
    }
    .footer-link {
        color: rgba(255, 255, 255, 0.8);
        transition: all 0.3s ease;
        display: inline-block;
    }
    .footer-link:hover {
        color: #ffd700 !important;
        transform: translateX(5px); /* Efek geser kanan saat disentuh */
        text-decoration: none;
    }
    .social-icon-btn {
        transition: all 0.3s ease;
        display: inline-block;
        color: white;
    }
    .social-icon-btn:hover {
        transform: translateY(-5px) scale(1.1); /* Efek melompat dan membesar */
        color: #ffd700 !important;
    }
</style>

<footer class="footer-premium text-white pt-5 pb-4 mt-5">
    <div class="container text-center text-md-start">
        <div class="row text-center text-md-start">
            
            <!-- Kolom 1: Nama Gereja -->
            <div class="col-md-4 col-lg-4 col-xl-4 mx-auto mt-3">
                <h5 class="text-uppercase mb-4 fw-bolder footer-title">GMIM Imanuel Bahu</h5>
                <p class="small text-white-50" style="line-height: 1.8;">
                    Menjabarkan Trilogi Pembangunan Jemaat untuk mewujudkan jemaat yang berintegritas dan takut akan Tuhan.
                </p>
            </div>

            <!-- Kolom 2: Navigasi Cepat -->
            <div class="col-md-2 col-lg-2 col-xl-2 mx-auto mt-3">
                <h5 class="text-uppercase mb-4 fw-bold footer-title" style="font-size: 1.1rem;">Tautan</h5>
                <p><a href="index.php" class="text-decoration-none small footer-link">Beranda</a></p>
                <p><a href="event.php" class="text-decoration-none small footer-link">Event</a></p>
                <p><a href="warta-keuangan.php" class="text-decoration-none small footer-link">Keuangan</a></p>
                <p><a href="warta-jemaat.php" class="text-decoration-none small footer-link">Warta Jemaat</a></p>
            </div>

            <!-- Kolom 3: Kontak -->
            <div class="col-md-4 col-lg-3 col-xl-3 mx-auto mt-3">
                <h5 class="text-uppercase mb-4 fw-bold footer-title" style="font-size: 1.1rem;">Kontak</h5>
                <p class="small text-white-50"><i class="bi bi-geo-alt-fill me-2 text-warning"></i> Jln. Sam Ratulangi No. 59, Manado</p>
                <p class="small text-white-50"><i class="bi bi-envelope-fill me-2 text-warning"></i> info@gmimimanuelbahu.or.id</p>
                <p class="small text-white-50"><i class="bi bi-telephone-fill me-2 text-warning"></i> +62 852-XXXX-XXXX</p>
            </div>
        </div>

        <hr class="mb-4" style="opacity: 0.2;">

        <!-- Hak Cipta -->
        <div class="row align-items-center">
            <div class="col-md-7 col-lg-8">
                <p class="small text-white-50 mb-md-0"> &copy; <?php echo date('Y'); ?> All Rights Reserved by:
                    <a href="#" class="text-warning text-decoration-none ms-1">
                        <strong>Informatics Engineering Student</strong>
                    </a>
                </p>
            </div>
            
            <!-- Media Sosial -->
            <div class="col-md-5 col-lg-4">
                <div class="text-center text-md-end mt-3 mt-md-0">
                    <ul class="list-unstyled list-inline mb-0">
                        <li class="list-inline-item me-3">
                            <a href="https://www.facebook.com/share/1EXzKHEmR9/?mibextid=wwXIfr" target="_blank" rel="noopener noreferrer" class="social-icon-btn" style="font-size: 22px;">
                                <i class="bi bi-facebook"></i>
                            </a>
                        </li>
                        <li class="list-inline-item me-3">
                            <a href="https://www.instagram.com/multimedia_gib?igsh=MWRrZmExbGpmYWVlcQ==" target="_blank" rel="noopener noreferrer" class="social-icon-btn" style="font-size: 22px;">
                                <i class="bi bi-instagram"></i>
                            </a>
                        </li>
                        <li class="list-inline-item">
                            <a href="https://youtube.com/@gmimimanuelbahu9014?si=w2rrySlknzHq_fMc" target="_blank" rel="noopener noreferrer" class="social-icon-btn" style="font-size: 22px;">
                                <i class="bi bi-youtube"></i>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</footer>