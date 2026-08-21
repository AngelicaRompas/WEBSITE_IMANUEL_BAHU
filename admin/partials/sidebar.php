<!-- SIDEBAR DESKTOP -->
<div class="d-none d-lg-block sidebar shadow-sm admin-sidebar">

    <!-- 1. HEADER LOGO (Tetap diam di atas) -->
    <div class="p-4 text-center border-bottom border-light border-opacity-25 flex-shrink-0">
        <div class="admin-logo mx-auto mb-3">
            <i class="bi bi-shield-lock-fill"></i>
        </div>
        <h5 class="fw-bold text-white mb-1">
            Dashboard Admin
        </h5>
        <small class="text-light opacity-75">
            GMIM Imanuel Bahu
        </small>
    </div>

    <!-- 2. AREA MENU TENGAH (Bisa di-scroll secara independen) -->
    <div class="admin-sidebar-scrollable">
        <div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist" aria-orientation="vertical">
            <button class="nav-link nav-link-admin active text-start py-3"
                    data-bs-toggle="pill"
                    data-bs-target="#admin-beranda"
                    type="button" role="tab">
                <i class="bi bi-house-door-fill me-2"></i>
                Beranda
            </button>

            <button class="nav-link nav-link-admin text-start py-3"
                    data-bs-toggle="pill"
                    data-bs-target="#admin-artikel"
                    type="button" role="tab">
                <i class="bi bi-newspaper me-2"></i>
                Edit Artikel
            </button>

            <button class="nav-link nav-link-admin text-start py-3 d-flex justify-content-between align-items-center" 
                    type="button" 
                    data-bs-toggle="collapse" 
                    data-bs-target="#menu-profil" 
                    aria-expanded="false">
                <span><i class="bi bi-database-gear me-2"></i> Edit Profil</span>
                <i class="bi bi-chevron-down"></i>
            </button>

            <div class="collapse ps-3 mb-2" id="menu-profil">
                <button class="nav-link nav-link-admin text-start py-2" data-bs-toggle="pill" data-bs-target="#admin-datajemaat" type="button" role="tab">
                    <i class="bi bi-people-fill me-2"></i> Edit Data Jemaat
                </button>
                <button class="nav-link nav-link-admin text-start py-2" data-bs-toggle="pill" data-bs-target="#admin-sejarah" type="button" role="tab">
                    <i class="bi bi-person-gear me-2"></i> Edit Sejarah
                </button>
                <button class="nav-link nav-link-admin text-start py-2" data-bs-toggle="pill" data-bs-target="#admin-struktur" type="button" role="tab">
                    <i class="bi bi-diagram-3-fill me-2"></i> Edit Struktur
                </button>
            </div>

            <button class="nav-link nav-link-admin text-start py-3"
                    data-bs-toggle="pill"
                    data-bs-target="#admin-warta"
                    type="button" role="tab">
                <i class="bi bi-file-earmark-text-fill me-2"></i>
                Edit Warta Jemaat
            </button>

            <button class="nav-link nav-link-admin text-start py-3"
                    data-bs-toggle="pill"
                    data-bs-target="#admin-event"
                    type="button" role="tab">
                <i class="bi bi-calendar-event me-2"></i>
                Edit Event
            </button>

            <button class="nav-link nav-link-admin text-start py-3"
                    data-bs-toggle="pill"
                    data-bs-target="#admin-keuangan"
                    type="button" role="tab">
                <i class="bi bi-cash-coin me-2"></i>
                Edit Keuangan
            </button>

            <button class="nav-link nav-link-admin text-start py-3"
                    data-bs-toggle="pill"
                    data-bs-target="#admin-renungan"
                    type="button" role="tab">
                <i class="bi bi-journal-bookmark-fill me-2"></i>
                Edit Renungan
            </button>

            <button class="nav-link nav-link-admin text-start py-3"
                    data-bs-toggle="pill"
                    data-bs-target="#admin-kehadiran"
                    type="button" role="tab">
                <i class="bi bi-people-fill me-2"></i>
                Edit Kehadiran
            </button>
        </div>
    </div>

    <!-- 3. FOOTER / TOMBOL KELUAR (Tetap diam di bawah) -->
    <div class="p-4 border-top border-light border-opacity-25 flex-shrink-0">
        <a href="../logout.php"
           class="btn btn-light text-danger fw-semibold rounded-pill w-100 py-2">
            <i class="bi bi-box-arrow-left me-2"></i>
            Keluar
        </a>
    </div>

</div>

<!-- SIDEBAR MOBILE (OFFCANVAS) -->
<div class="offcanvas offcanvas-start admin-offcanvas d-lg-none" tabindex="-1" id="mobileSidebar">
    <div class="offcanvas-header border-bottom border-light border-opacity-25">
        <h5 class="offcanvas-title text-white fw-bold mb-0">
            <i class="bi bi-shield-lock-fill me-2"></i> Admin Panel
        </h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas"></button>
    </div>
    
    <div class="offcanvas-body p-4">
        <div class="nav flex-column nav-pills" id="v-pills-tab-mobile" role="tablist" aria-orientation="vertical">
            <button class="nav-link nav-link-admin active text-start py-3" data-bs-toggle="pill" data-bs-target="#admin-beranda" data-bs-dismiss="offcanvas" type="button" role="tab">
                <i class="bi bi-house-door-fill me-2"></i> Beranda
            </button>

            <button class="nav-link nav-link-admin text-start py-3" data-bs-toggle="pill" data-bs-target="#admin-artikel" data-bs-dismiss="offcanvas" type="button" role="tab">
                <i class="bi bi-newspaper me-2"></i> Edit Artikel
            </button>

            <button class="nav-link nav-link-admin text-start py-3 d-flex justify-content-between align-items-center" 
                    type="button" data-bs-toggle="collapse" data-bs-target="#menu-edit-profil-mobile" aria-expanded="false">
                <span><i class="bi bi-database-gear me-2"></i> Edit Profil</span>
                <i class="bi bi-chevron-down"></i>
            </button>

            <div class="collapse ps-3 mb-2" id="menu-edit-profil-mobile">
                <button class="nav-link nav-link-admin text-start py-2" data-bs-toggle="pill" data-bs-target="#admin-datajemaat" data-bs-dismiss="offcanvas" type="button" role="tab">
                    <i class="bi bi-people-fill me-2"></i> Edit Data Jemaat
                </button>
                <button class="nav-link nav-link-admin text-start py-2" data-bs-toggle="pill" data-bs-target="#admin-sejarah" data-bs-dismiss="offcanvas" type="button" role="tab">
                    <i class="bi bi-person-gear me-2"></i> Edit Sejarah
                </button>
                <button class="nav-link nav-link-admin text-start py-2" data-bs-toggle="pill" data-bs-target="#admin-struktur" data-bs-dismiss="offcanvas" type="button" role="tab">
                    <i class="bi bi-diagram-3-fill me-2"></i> Edit Struktur
                </button>
            </div>

            <button class="nav-link nav-link-admin text-start py-3" data-bs-toggle="pill" data-bs-target="#admin-warta" data-bs-dismiss="offcanvas" type="button" role="tab">
                <i class="bi bi-file-earmark-text-fill me-2"></i> Edit Warta Jemaat
            </button>
            <button class="nav-link nav-link-admin text-start py-3" data-bs-toggle="pill" data-bs-target="#admin-event" data-bs-dismiss="offcanvas" type="button" role="tab">
                <i class="bi bi-calendar-event me-2"></i> Edit Event
            </button>
            <button class="nav-link nav-link-admin text-start py-3" data-bs-toggle="pill" data-bs-target="#admin-keuangan" data-bs-dismiss="offcanvas" type="button" role="tab">
                <i class="bi bi-cash-coin me-2"></i> Edit Keuangan
            </button>
            <button class="nav-link nav-link-admin text-start py-3" data-bs-toggle="pill" data-bs-target="#admin-renungan" data-bs-dismiss="offcanvas" type="button" role="tab">
                <i class="bi bi-journal-bookmark-fill me-2"></i> Edit Renungan
            </button>
            <button class="nav-link nav-link-admin text-start py-3" data-bs-toggle="pill" data-bs-target="#admin-kehadiran" data-bs-dismiss="offcanvas" type="button" role="tab">
                <i class="bi bi-people-fill me-2"></i> Edit Kehadiran
            </button>
        </div>
        
        <div class="mt-5 pt-4 border-top border-light border-opacity-25">
            <a href="../logout.php" class="btn btn-light text-danger fw-semibold rounded-pill w-100 py-2">
                <i class="bi bi-box-arrow-left me-2"></i> Keluar
            </a>
        </div>
    </div>
</div>