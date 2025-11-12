<?php
include 'db.php';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Peta Persebaran SMA Negeri di Kota Padang</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f8f9fa;
            margin: 0;
            padding: 0;
            height: 100vh; 
            overflow: hidden; 
        }

        /* Header Bar */
        .header-bar-custom {
            background-color: #0d6efd;
            color: white;
            padding: 10px 15px;
            text-align: center;
            box-shadow: 0 2px 5px rgba(0,0,0,0.2);
            height: 50px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .header-bar-custom .h5-small {
            font-size: 1.1rem;
            font-weight: bold;
            margin-bottom: 0;
        }

        /* Footer Bar */
        .footer-bar-custom {
            height: 60px;
            background-color: #f8f9fa;
            padding: 8px 0;
            text-align: center;
            width: 100%;
            box-shadow: 0 -2px 5px rgba(0,0,0,0.05);
        }

        /* Peta */
        #map {
            height: calc(100% - 50px - 60px);
            width: 100%;
        }

        /* Gambar popup */
        .popup-img {
            width: 180px;
            height: 120px;
            object-fit: cover;
            border-radius: 8px;
            margin-top: 5px;
            border: 1px solid #ccc;
        }

        /* Tombol popup */
        .btn-detail {
            background-color: #0d6efd;
            color: #fff;
            padding: 5px 10px;
            border-radius: 6px;
            font-size: 0.85rem;
            text-decoration: none;
            font-weight: 500;
        }
        .btn-detail:hover {
            background-color: #084298;
            color: #fff;
        }
    </style>
</head>
<body>
    
    <header class="header-bar-custom">
        <h2 class="h5-small"><i class="fas fa-map-marked-alt me-2"></i> Peta Persebaran SMA Negeri di Kota Padang</h2>
    </header>
    
    <div id="map"></div>
    
    <div class="footer-bar-custom">
        <a href="index.php" class="btn btn-primary btn-md shadow-sm">
            <i class="fas fa-home me-2"></i> Kembali ke Halaman Utama
        </a>
    </div>

    <script>
        // Inisialisasi peta
        var map = L.map('map').setView([-0.95, 100.35], 12);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '© OpenStreetMap contributors'
        }).addTo(map);
    </script>

    <?php
    // Ambil semua data SMA dari database
    $query = mysqli_query($koneksi, "SELECT * FROM sma");

    while ($data = mysqli_fetch_assoc($query)) {
        $id = $data['id'];
        $nama = addslashes($data['nama_sma']);
        $alamat = addslashes($data['alamat']);
        $foto = !empty($data['foto']) ? $data['foto'] : 'default.jpg';
        $lat = $data['latitude'];
        $lng = $data['longitude'];

        // Path folder gambar diperbaiki
        $path_gambar = "image/" . $foto;

        echo "
        <script>
            var marker = L.marker([$lat, $lng]).addTo(map);
            marker.bindPopup(`
                <b class='text-primary'>$nama</b><br>
                <small>$alamat</small><br>
                <img src='$path_gambar' class='popup-img'><br>
                <a href='detail.php?id=$id' class='btn-detail mt-2 d-inline-block'>Lihat Detail</a>
            `);
        </script>
        ";
    }
    ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
