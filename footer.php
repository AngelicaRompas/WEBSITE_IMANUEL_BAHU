<!-- footer.php -->
<style>
    /* Desain Footer Premium */
    .footer-premium {
        background: linear-gradient(135deg, #2b1654 0%, #1a0d33 100%);
        border-top: 1px solid rgba(255, 255, 255, 0.1);
        position: relative;
        overflow: hidden;
    }
    
    /* Dekorasi Pencahayaan Latar Belakang (Aurora Efek) */
    .footer-premium::before {
        content: '';
        position: absolute;
        top: -100px;
        right: -100px;
        width: 300px;
        height: 300px;
        background: radial-gradient(circle, rgba(111, 66, 193, 0.2) 0%, rgba(0,0,0,0) 70%);
        pointer-events: none;
    }

    .footer-title {
        font-family: 'Plus Jakarta Sans', serif;
        font-weight: 800;
        letter-spacing: 0.5px;
        color: #ffffff;
    }
    
    .footer-subtitle-gold {
        font-family: 'Playfair Display', serif;
        color: #ffd700; /* Emas Mewah */
        font-style: italic;
    }

    /* Efek Kustom Peta Mini Adaptif & Klik Tautan */
    .map-link-wrapper {
        display: block;
        text-decoration: none;
        width: 100%;
    }

    .map-container-mini {
        border-radius: 16px;
        overflow: hidden;
        border: 2px solid rgba(255, 255, 255, 0.1);
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.2);
        width: 100%;
        transition: all 0.3s ease;
        position: relative;
    }
    
    /* Mengunci agar klik pada iframe diteruskan ke tag anchor pembungkus */
    .map-container-mini iframe {
        pointer-events: none; 
    }
    
    .map-link-wrapper:hover .map-container-mini {
        transform: scale(1.03);
        border-color: #ffd700;
        box-shadow: 0 8px 24px rgba(255, 215, 0, 0.15);
    }

    /* Gaya Media Sosial Modern */
    .social-circle-btn {
        width: 42px;
        height: 42px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        background: rgba(255, 255, 255, 0.05);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 50%;
        color: white;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        text-decoration: none;
    }
    
    .social-circle-btn:hover {
        background: #ffd700;
        color: #1a0d33 !important;
        transform: translateY(-5px);
        box-shadow: 0 5px 15px rgba(255, 215, 0, 0.3);
    }
</style>

<footer class="footer-premium text-white pt-5 pb-4 mt-5">
    <div class="container text-center text-md-start">
        <div class="row g-4 text-center text-md-start">
            
            <!-- Kolom 1: Nama & Visi Gereja -->
            <div class="col-md-5 col-lg-5 mx-auto mt-3">
                <h5 class="text-uppercase mb-2 footer-title fs-5">GMIM Imanuel Bahu</h5>
                <h6 class="footer-subtitle-gold mb-3 small">Sola Fide, Sola Gratia, Sola Scriptura</h6>
                <p class="small text-white-50 lh-lg pe-lg-4" style="text-align: justify;">
                    Menjabarkan Trilogi Pembangunan Jemaat untuk mewujudkan pelayanan yang berintegritas, inklusif, dan takut akan Tuhan di era transformasi digital.
                </p>
                
                <!-- Jadwal Pelayanan Tambahan -->
                <div class="d-flex align-items-center mt-4 justify-content-center justify-content-md-start text-white-50 small">
                    <div class="bg-white bg-opacity-10 rounded-3 p-2 me-3 text-center" style="width: 50px;">
                        <i class="bi bi-clock-history text-warning fs-5"></i>
                    </div>
                    <div>
                        <span class="fw-bold d-block text-white" style="font-size: 0.85rem;">Sekretariat Jemaat:</span>
                        <span style="font-size: 0.8rem;">Senin - Sabtu (09:00 - 16:00 WITA)</span>
                    </div>
                </div>
            </div>

            <!-- Kolom 2: Kontak Detail -->
            <div class="col-md-3 col-lg-3 mx-auto mt-3">
                <h5 class="text-uppercase mb-4 fw-bold footer-title" style="font-size: 0.95rem; color: rgba(255,255,255,0.4); letter-spacing: 1px;">Hubungi Kami</h5>
                
                <div class="d-flex align-items-start mb-3 justify-content-center justify-content-md-start">
                    <i class="bi bi-geo-alt-fill text-warning me-3 mt-1"></i>
                    <p class="small text-white-50 mb-0">Jl. Wolter Monginsidi, Bahu, Kec. Malalayang, Kota Manado, Sulawesi Utara</p>
                </div>
                
                <div class="d-flex align-items-center mb-3 justify-content-center justify-content-md-start">
                    <i class="bi bi-envelope-fill text-warning me-3"></i>
                    <p class="small text-white-50 mb-0">gerejagmimimanuelbahu@gmail.com</p>
                </div>
                
                <div class="d-flex align-items-center justify-content-center justify-content-md-start">
                    <i class="bi bi-telephone-fill text-warning me-3"></i>
                    <p class="small text-white-50 mb-0">0811-4316-533</p>
                </div>
            </div>

            <!-- Kolom 3: Peta Lokasi Terhubung Langsung ke Link Google Maps Resmi Anda -->
            <div class="col-md-4 col-lg-4 mx-auto mt-3">
                <h5 class="text-uppercase mb-3 fw-bold footer-title" style="font-size: 0.95rem; color: rgba(255,255,255,0.4); letter-spacing: 1px;">Lokasi Rumah Gereja</h5>
                
                <!-- Pembungkus Tautan yang Menghubungkan Fitur Peta Langsung ke Link Google Maps Anda -->
                <a href="https://maps.app.goo.gl/zcMnLb9Muot1TZwX8?g_st=ic" target="_blank" rel="noopener noreferrer" class="map-link-wrapper" title="Klik untuk membuka rute di Google Maps">
                    <div class="map-container-mini">
                        <iframe 
                            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3988.8926402422525!2d124.8226468739678!3d1.4485536615823126!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x328774f76231d683%3A0x6e2613ba6b67705d!2sGMIM%20Imanuel%20Bahu!5e0!3m2!1sid!2sid!4v1719144400000!5m2!1sid!2sid" 
                            width="100%" 
                            height="145" 
                            style="border:0; vertical-align: middle;" 
                            allowfullscreen="" 
                            loading="lazy" 
                            referrerpolicy="no-referrer-when-downgrade">
                        </iframe>
                    </div>
                </a>
            </div>
        </div>

        <hr class="my-4" style="opacity: 0.1;">

        <!-- Baris Bawah: Hak Cipta & Sosial Media Bulat -->
        <div class="row align-items-center g-3">
            <div class="col-md-7 text-center text-md-start">
                <p class="small text-white-50 mb-0">
                    &copy; 2026 GMIM Imanuel Bahu. All Rights Reserved. Designed by:
                    <a href="#" class="text-warning text-decoration-none fw-bold ms-1">
                        Informatics Engineering Student
                    </a>
                </p>
            </div>
            
            <div class="col-md-5 text-center text-md-end">
                <div class="d-flex gap-2 justify-content-center justify-content-md-end">
                    <a href="https://www.facebook.com/share/1EXzKHEmR9/?mibextid=wwXIfr" target="_blank" rel="noopener noreferrer" class="social-circle-btn" title="Facebook Resmi">
                        <i class="bi bi-facebook"></i>
                    </a>
                    <a href="https://www.instagram.com/multimedia_gib?igsh=MWRrZmExbGpmYWVlcQ==" target="_blank" rel="noopener noreferrer" class="social-circle-btn" title="Instagram Multimedia">
                        <i class="bi bi-instagram"></i>
                    </a>
                    <a href="https://youtube.com/@gmimimanuelbahu9014?si=w2rrySlknzHq_fMc" target="_blank" rel="noopener noreferrer" class="social-circle-btn" title="YouTube Streaming">
                        <i class="bi bi-youtube"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
</footer>