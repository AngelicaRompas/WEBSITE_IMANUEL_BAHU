<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Navigasi Gedung Interaktif - GMIM Imanuel Bahu</title>
    
    <!-- CSS Dependencies -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/pannellum@2.5.6/build/pannellum.css">
    
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #f8fafc; }
        .tour-container { width: 100%; height: 100vh; position: relative; }
        #panorama-viewer { width: 100%; height: 100%; }
        
        .tour-info-card {
            position: absolute; top: 80px; left: 20px; z-index: 1000;
            background: rgba(255, 255, 255, 0.9);
            border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            max-width: 300px; padding: 15px;
        }

        .custom-hotspot {
            width: 40px; height: 40px;
            background: rgba(255, 255, 255, 0.7);
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            color: #0d6efd; cursor: pointer;
            transition: all 0.3s ease;
            border: 2px solid white;
        }

        .custom-hotspot:hover { background: #0d6efd; color: white; transform: scale(1.2); }
    </style>
</head>
<body>

<!-- Navbar tetap disertakan -->
<?php include 'navbar.php'; ?>

<div class="tour-container">
    <div class="tour-info-card">
        <h6 class="fw-bold text-primary mb-1"><i class="bi bi-compass-fill me-1"></i> Penjelajahan 360°</h6>
        <p class="small text-muted mb-2">Geser layar untuk melihat sekeliling.</p>
        <div class="badge bg-dark text-white p-2" id="roomLabel">Lokasi: Loading...</div>
    </div>
    <div id="panorama-viewer"></div>
</div>

<script src="https://cdn.jsdelivr.net/npm/pannellum@2.5.6/build/pannellum.js"></script>
<script>
    function hotspotCreator(hotSpotDiv, args) {
        hotSpotDiv.classList.add('custom-hotspot');
        hotSpotDiv.innerHTML = '<i class="bi bi-arrow-up-circle-fill"></i>';
    }

    const viewer = pannellum.viewer('panorama-viewer', {
        "default": {
            "firstScene": "halamanDepan",
            "author": "GMIM Imanuel Bahu",
            "autoLoad": true,
            "sceneFadeDuration": 1000
        },
        "scenes": {
            "halamanDepan": {
                "title": "Halaman Depan",
                "type": "equirectangular",
                "panorama": "assets/virtual-tour/halaman-depan.JPG",
                "hotSpots": [
                    { "pitch": 5.11, "yaw": -39.09, "type": "scene", "text": "Masuk Pintu Utama", "sceneId": "LobbyGereja", "createTooltipFunc": hotspotCreator },
                    { "pitch": 0.47, "yaw": 13.42, "type": "scene", "text": "Aula Lantai 1", "sceneId": "aulaLantai1", "createTooltipFunc": hotspotCreator }
                ]
            },
            "LobbyGereja": {
                "title": "Lobby Gereja",
                "type": "equirectangular",
                "panorama": "assets/virtual-tour/Lobby-Gereja.JPG",
                "hotSpots": [
                    { "pitch": 9.84, "yaw": 0.58, "type": "scene", "text": "Naik Lantai 2", "sceneId": "tanggaLantai1", "createTooltipFunc": hotspotCreator },
                    { "pitch": 6.59, "yaw": 26.62, "type": "scene", "text": "Aula Lantai 1", "sceneId": "aulaLantai1", "createTooltipFunc": hotspotCreator },
                    { "pitch": -10.21, "yaw": 141.70, "type": "scene", "text": "Keluar", "sceneId": "exitLobby", "createTooltipFunc": hotspotCreator }
                ]
            },
            "exitLobby": {
                "title": "Luar Lobby",
                "type": "equirectangular",
                "panorama": "assets/virtual-tour/luar-lobby.JPG",
                "hotSpots": [
                    { "pitch": 1.64, "yaw": -1.91, "type": "scene", "text": "Masuk Kembali", "sceneId": "LobbyGereja", "createTooltipFunc": hotspotCreator }
                ]
            },
            "aulaLantai1": {
                "title": "Aula Lantai 1",
                "type": "equirectangular",
                "panorama": "assets/virtual-tour/aula-lantai1.JPG",
                "hotSpots": [
                    { "pitch": 3.48, "yaw": -53.22, "type": "scene", "text": "Ke Lobby", "sceneId": "LobbyGereja", "createTooltipFunc": hotspotCreator },
                    { "pitch": -10.86, "yaw": -176.95, "type": "scene", "text": "Pintu Keluar", "sceneId": "exitAula", "createTooltipFunc": hotspotCreator }
                ]
            },
            "exitAula": {
                "title": "Luar Aula",
                "type": "equirectangular",
                "panorama": "assets/virtual-tour/luar-lobby.JPG",
                "hotSpots": [
                    { "pitch": 1.64, "yaw": -1.91, "type": "scene", "text": "Masuk ke Aula", "sceneId": "aulaLantai1", "createTooltipFunc": hotspotCreator }
                ]
            },
            "tanggaLantai1": {
                "title": "Tangga Lantai 1",
                "type": "equirectangular",
                "panorama": "assets/virtual-tour/tangga-lantai1.JPG",
                "hotSpots": [
                    { "pitch": -0.47, "yaw": -3.09, "type": "scene", "text": "Naik ke Lantai 2", "sceneId": "tanggaLantai2", "createTooltipFunc": hotspotCreator }
                ]
            },
            "tanggaLantai2": {
                "title": "Tangga Lantai 2",
                "type": "equirectangular",
                "panorama": "assets/virtual-tour/naik-lantai2.JPG",
                "hotSpots": [
                    { "pitch": 6.06, "yaw": 0.08, "type": "scene", "text": "Ke Lantai 2", "sceneId": "Lantai-Dua", "createTooltipFunc": hotspotCreator }
                ]
            },
            "Lantai-Dua": {
                "title": "Lantai 2",
                "type": "equirectangular",
                "panorama": "assets/virtual-tour/Lantai-Dua.JPG",
                "hotSpots": [
                    { "pitch": -1.20, "yaw": -126.45, "type": "scene", "text": "Aula Lantai 2", "sceneId": "aulaLantai2", "createTooltipFunc": hotspotCreator },
                    { "pitch": -14.81, "yaw": 121.85, "type": "scene", "text": "Naik Lantai 3", "sceneId": "naikLantai3", "createTooltipFunc": hotspotCreator }
                ]
            },
            "aulaLantai2": {
                "title": "Aula Lantai 2",
                "type": "equirectangular",
                "panorama": "assets/virtual-tour/aula-lantai2.JPG",
                "hotSpots": [
                    { "pitch": 4.62, "yaw": -10.03, "type": "scene", "text": "Aula Dalam", "sceneId": "aula-dua", "createTooltipFunc": hotspotCreator },
                    { "pitch": -2.44, "yaw": -109.97, "type": "scene", "text": "Ke Tangga 3", "sceneId": "naikLantai3", "createTooltipFunc": hotspotCreator }
                ]
            },
            "aula-dua": {
                "title": "Aula Dalam Lantai 2",
                "type": "equirectangular",
                "panorama": "assets/virtual-tour/aula-dua.jpg",
                "hotSpots": [
                    { "pitch": 10.18, "yaw": 3.77, "type": "scene", "text": "Kembali", "sceneId": "aulaLantai2", "createTooltipFunc": hotspotCreator }
                ]
            },
            "naikLantai3": {
                "title": "Akses Lantai 3",
                "type": "equirectangular",
                "panorama": "assets/virtual-tour/naik-lantai3.JPG",
                "hotSpots": [
                    { "pitch": 4.52, "yaw": -1.57, "type": "scene", "text": "Menuju Tangga 3", "sceneId": "Tangga-Lantai-Tiga", "createTooltipFunc": hotspotCreator }
                ]
            },
            "Tangga-Lantai-Tiga": {
                "title": "Tangga Lantai 3",
                "type": "equirectangular",
                "panorama": "assets/virtual-tour/Tangga-Lantai-Tiga.JPG",
                "hotSpots": [
                    { "pitch": 4.44, "yaw": -0.39, "type": "scene", "text": "Tangga Gereja", "sceneId": "tangga-gereja", "createTooltipFunc": hotspotCreator }
                ]
            },
            "tangga-gereja": {
                "title": "Tangga Utama Gereja",
                "type": "equirectangular",
                "panorama": "assets/virtual-tour/tangga-gereja.JPG",
                "hotSpots": [
                    { "pitch": 1.24, "yaw": -2.56, "type": "scene", "text": "Masuk Ruang Utama", "sceneId": "gereja", "createTooltipFunc": hotspotCreator }
                ]
            },
            "gereja": {
                "title": "Pintu Masuk Gereja",
                "type": "equirectangular",
                "panorama": "assets/virtual-tour/gereja.JPG",
                "hotSpots": [
                    { "pitch": -1.19, "yaw": 58.99, "type": "scene", "text": "Sisi Utama", "sceneId": "utama-gereja", "createTooltipFunc": hotspotCreator },
                    { "pitch": 3.86, "yaw": -56.50, "type": "scene", "text": "Sisi Samping", "sceneId": "samping-gereja", "createTooltipFunc": hotspotCreator }
                ]
            },
            "utama-gereja": {
                "title": "Sisi Utama Gereja",
                "type": "equirectangular",
                "panorama": "assets/virtual-tour/utama-gereja.JPG",
                "hotSpots": [
                    { "pitch": 0, "yaw": 0, "type": "scene", "text": "Belakang", "sceneId": "belakang-gereja", "createTooltipFunc": hotspotCreator },
                    { "pitch": 0.54, "yaw": 93.25, "type": "scene", "text": "Sisi Samping", "sceneId": "samping-gereja", "createTooltipFunc": hotspotCreator }
                ]
            },
            "samping-gereja": {
                "title": "Sisi Samping Gereja",
                "type": "equirectangular",
                "panorama": "assets/virtual-tour/samping-gereja.JPG",
                "hotSpots": [
                    { "pitch": 0, "yaw": 0, "type": "scene", "text": "Kembali", "sceneId": "utama-gereja", "createTooltipFunc": hotspotCreator }
                ]
            },
            "belakang-gereja": {
                "title": "Belakang Gereja",
                "type": "equirectangular",
                "panorama": "assets/virtual-tour/belakang-gereja.JPG",
                "hotSpots": [
                    { "pitch": 0, "yaw": 0, "type": "scene", "text": "Kembali", "sceneId": "utama-gereja", "createTooltipFunc": hotspotCreator }
                ]
            }
        }
    });

    viewer.on('scenechange', function(sceneId) {
        const label = document.getElementById('roomLabel');
        const locations = {
            'halamanDepan': 'Halaman Depan',
            'LobbyGereja': 'Lobby Gereja',
            'exitLobby': 'Luar Lobby',
            'aulaLantai1': 'Aula Lantai Satu',
            'exitAula': 'Luar Aula',
            'tanggaLantai1': 'Tangga Lantai Satu',
            'tanggaLantai2': 'Tangga Lantai Dua',
            'Lantai-Dua': 'Lantai Dua',
            'aulaLantai2': 'Selasar Aula Lantai Dua',
            'aula-dua': 'Aula Lantai Dua',
            'naikLantai3': 'Akses Lantai Tiga',
            'Tangga-Lantai-Tiga': 'Tangga Lantai Tiga',
            'tangga-gereja': 'Tangga Utama Gereja',
            'gereja': 'Pintu Masuk Gereja',
            'utama-gereja': 'Sisi Utama Gereja',
            'samping-gereja': 'Sisi Samping Gereja',
            'belakang-gereja': 'Belakang Konfessional Gereja'
        };
        label.innerText = "Lokasi: " + (locations[sceneId] || 'Unknown');
    });
</script>
</body>
</html>