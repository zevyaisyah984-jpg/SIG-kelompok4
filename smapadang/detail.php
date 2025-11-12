<?php
include 'db.php';

// --- Logika PHP untuk Mengambil Data ---
if (!isset($_GET['id']) || empty($_GET['id'])) die("ID sekolah tidak ditemukan.");
$id = mysqli_real_escape_string($koneksi, $_GET['id']);
$query = mysqli_query($koneksi, "SELECT * FROM sma WHERE id = '$id'");
$data = mysqli_fetch_assoc($query);
if (!$data) die("Data sekolah tidak ditemukan!");

// Mengamankan data untuk ditampilkan di HTML
$nama_sma = htmlspecialchars(strtoupper($data['nama_sma']));
$lat_clean = $data['latitude'] ?? 0;
$lon_clean = $data['longitude'] ?? 0;

// Link Google Maps
$google_map_url = "https://www.google.com/maps/search/?api=1&query={$lat_clean},{$lon_clean}";
$direction_url = "https://www.google.com/maps/dir/?api=1&destination={$lat_clean},{$lon_clean}";
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Profil <?= $nama_sma ?></title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>
    <link rel="stylesheet" href="https://api.mapbox.com/mapbox.js/plugins/leaflet-fullscreen/v1.0.1/leaflet.fullscreen.css"/>

    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script src="https://api.mapbox.com/mapbox.js/plugins/leaflet-fullscreen/v1.0.1/Leaflet.fullscreen.min.js"></script>

    <style>
        body {
            background-color: #f4f7f9;
            font-family: 'Poppins', sans-serif;
        }
        #map {
            height: 400px;
            border-radius: 10px;
            margin-top: 15px;
            box-shadow: 0 0 10px rgba(0,0,0,0.15);
        }
        .card-toc a {
            text-decoration: none;
            color: #0d6efd;
        }
        .card-toc a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

<div class="container my-5 p-4 bg-white rounded shadow-lg">
    
    <h1 class="text-primary text-center fw-bold mb-1">
        <i class="fas fa-school me-2"></i> Profil <?= $nama_sma ?>
    </h1>
    <p class="text-muted text-center mb-4">
        Diperbarui pada tanggal <?= date("d F Y") ?>
    </p>

    <img src="image/<?= !empty($data['foto']) ? $data['foto'] : 'default.jpg' ?>" 
         alt="Foto Sekolah" 
         class="img-fluid rounded mx-auto d-block mb-4 border border-light shadow-sm" 
         style="max-height: 400px; object-fit: cover;">

    <div class="alert alert-info border-0 rounded-3" role="alert">
        <p class="mb-0">
            <?= $nama_sma ?> merupakan salah satu sekolah jenjang SMA berstatus Negeri 
            yang berada di wilayah Kec. <?= htmlspecialchars($data['kecamatan']) ?>, Kota Padang. Sekolah ini 
            memiliki akreditasi <b><?= htmlspecialchars($data['akreditasi']) ?></b> dan terdaftar di bawah naungan 
            <b>Kementerian Pendidikan dan Kebudayaan</b>. Kepala sekolah saat ini adalah 
            <b><?= htmlspecialchars($data['kepala_sekolah'] ?? '-') ?></b>, dan operator sekolah adalah 
            <b><?= htmlspecialchars($data['operator'] ?? '-') ?></b>.
        </p>
    </div>

    <!-- Daftar Isi -->
    <div class="card shadow-sm mb-5 card-toc">
        <div class="card-header bg-light fw-bold">
            <i class="fas fa-list me-2"></i> Daftar Isi
        </div>
        <div class="card-body">
            <ul class="list-unstyled row g-2">
                <li class="col-md-4"><i class="fas fa-angle-right me-1"></i> <a href="#akreditasi">Akreditasi & Sertifikasi</a></li>
                <li class="col-md-4"><i class="fas fa-angle-right me-1"></i> <a href="#alamat">Alamat & Peta</a></li>
                <li class="col-md-4"><i class="fas fa-angle-right me-1"></i> <a href="#kontak">Kontak Sekolah</a></li>
                <li class="col-md-4"><i class="fas fa-angle-right me-1"></i> <a href="#website">Website</a></li>
                <li class="col-md-4"><i class="fas fa-angle-right me-1"></i> <a href="#informasi">Informasi Lengkap</a></li>
                <li class="col-md-4"><i class="fas fa-angle-right me-1"></i> <a href="#ulasan">Form Ulasan</a></li>
            </ul>
        </div>
    </div>
    
    <!-- Akreditasi -->
    <div id="akreditasi" class="card shadow-sm mb-4">
        <div class="card-header bg-primary text-white fw-bold">
            <i class="fas fa-certificate me-2"></i> Akreditasi & Sertifikasi
        </div>
        <div class="card-body">
            <p>Sekolah ini telah terakreditasi <b><?= htmlspecialchars($data['akreditasi']) ?></b>. 
            <?= !empty($data['sertifikasi']) ? "Selain itu, sekolah ini juga telah tersertifikasi <b>" . htmlspecialchars($data['sertifikasi']) . "</b>." : "Sertifikasi tambahan belum tersedia." ?></p>
        </div>
    </div>

    <!-- Alamat & Peta -->
    <div id="alamat" class="card shadow-sm mb-4">
        <div class="card-header bg-primary text-white fw-bold">
            <i class="fas fa-map-marker-alt me-2"></i> Alamat Sekolah
        </div>
        <div class="card-body">
            <p class="lead"><?= htmlspecialchars($data['alamat']) ?><br>Kecamatan <?= htmlspecialchars($data['kecamatan']) ?>, Kota Padang.</p>
            <div id="map"></div>
            <p class="mt-3 text-muted">
                <b>Latitude:</b> <?= $lat_clean ?> | <b>Longitude:</b> <?= $lon_clean ?>
            </p>
            <div class="d-flex gap-2">
                <a href="<?= $google_map_url ?>" target="_blank" class="btn btn-outline-primary btn-sm">
                    <i class="fas fa-map-marked-alt"></i> Lihat di Google Maps
                </a>
                <a href="<?= $direction_url ?>" target="_blank" class="btn btn-outline-success btn-sm">
                    <i class="fas fa-route"></i> Petunjuk Arah
                </a>
            </div>
        </div>
    </div>

    <!-- Kontak -->
    <div id="kontak" class="card shadow-sm mb-4">
        <div class="card-header bg-primary text-white fw-bold">
            <i class="fas fa-phone-alt me-2"></i> Kontak Sekolah
        </div>
        <div class="card-body">
            <p class="mb-1"><b>Telepon:</b> <?= htmlspecialchars($data['no_telp'] ?? '-') ?></p>
            <p class="mb-0"><b>Email:</b> <?= htmlspecialchars($data['email'] ?? '-') ?></p>
        </div>
    </div>

    <!-- Website -->
    <div id="website" class="card shadow-sm mb-4">
        <div class="card-header bg-primary text-white fw-bold">
            <i class="fas fa-globe me-2"></i> Website Sekolah
        </div>
        <div class="card-body">
            <?php if (!empty($data['website'])): ?>
                <p>Kunjungi website resmi: 
                    <a href="http://<?= htmlspecialchars($data['website']) ?>" target="_blank" class="text-decoration-underline"><?= htmlspecialchars($data['website']) ?></a>
                </p>
            <?php else: ?>
                <p class="text-muted">Website belum tersedia.</p>
            <?php endif; ?>
        </div>
    </div>

    <!-- Informasi Lengkap -->
    <div id="informasi" class="card shadow-sm mb-4">
        <div class="card-header bg-primary text-white fw-bold">
            <i class="fas fa-info-circle me-2"></i> Informasi Lengkap Sekolah
        </div>
        <div class="card-body p-0">
            <table class="table table-striped table-bordered mb-0">
                <tbody>
                    <tr><th class="bg-light w-25">NPSN</th><td><?= htmlspecialchars($data['npsn'] ?? '-') ?></td></tr>
                    <tr><th class="bg-light">Nama Sekolah</th><td><?= $nama_sma ?></td></tr>
                    <tr><th class="bg-light">Tanggal Berdiri</th><td><?= !empty($data['tgl_berdiri']) && $data['tgl_berdiri'] != '0000-00-00' ? date('d F Y', strtotime($data['tgl_berdiri'])) : '-' ?></td></tr>
                    <tr><th class="bg-light">No. SK Pendirian</th><td><?= htmlspecialchars($data['sk_pendirian'] ?? '-') ?></td></tr>
                    <tr><th class="bg-light">Akreditasi</th><td><?= htmlspecialchars($data['akreditasi'] ?? '-') ?></td></tr>
                    <tr><th class="bg-light">Sertifikasi</th><td><?= htmlspecialchars($data['sertifikasi'] ?? '-') ?></td></tr>
                    <tr><th class="bg-light">Kepala Sekolah</th><td><?= htmlspecialchars($data['kepala_sekolah'] ?? '-') ?></td></tr>
                    <tr><th class="bg-light">Operator</th><td><?= htmlspecialchars($data['operator'] ?? '-') ?></td></tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Ulasan -->
    <div id="ulasan" class="card shadow-sm mb-5">
        <div class="card-header bg-primary text-white fw-bold">
            <i class="fas fa-comment me-2"></i> Kirim Ulasan untuk <?= $nama_sma ?>
        </div>
        <div class="card-body">
            <form action="proses_ulasan.php" method="POST">
                <div class="mb-3">
                    <label for="nama_anda" class="form-label">Nama Anda</label>
                    <input type="text" class="form-control" id="nama_anda" name="nama_anda" placeholder="Masukkan nama Anda" required>
                </div>
                <div class="mb-3">
                    <label for="email_anda" class="form-label">Email Anda</label>
                    <input type="email" class="form-control" id="email_anda" name="email_anda" placeholder="Masukkan email Anda">
                </div>
                <div class="mb-3">
                    <label for="ulasan_anda" class="form-label">Ulasan Anda</label>
                    <textarea class="form-control" id="ulasan_anda" name="ulasan_anda" rows="4" placeholder="Tuliskan kesan atau pengalaman Anda di sekolah ini" required></textarea>
                </div>
                <button type="submit" class="btn btn-success">
                    <i class="fas fa-paper-plane"></i> Kirim Ulasan
                </button>
            </form>
        </div>
    </div>

    <div class="text-center my-4">
        <a href="cari.php" class="btn btn-secondary btn-lg me-2">
            <i class="fas fa-arrow-left me-2"></i> Kembali ke Pencarian
        </a>
        <a href="index.php" class="btn btn-outline-primary btn-lg">
            <i class="fas fa-home me-2"></i> Halaman Utama
        </a>
    </div>

</div>

<!-- ==== PERBAIKAN PETA ==== -->
<script>
document.addEventListener("DOMContentLoaded", function() {
    var lat = parseFloat("<?= $lat_clean ?>");
    var lon = parseFloat("<?= $lon_clean ?>");

    if (isNaN(lat) || isNaN(lon) || lat === 0 || lon === 0) {
        document.getElementById("map").innerHTML = `
            <div class="alert alert-warning text-center mt-3">
                ⚠️ Koordinat GPS sekolah belum tersedia.
            </div>`;
        return;
    }

    var map = L.map('map', { fullscreenControl: true }).setView([lat, lon], 15);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
        attribution: '© <a href="https://www.openstreetmap.org/">OpenStreetMap</a>'
    }).addTo(map);

    var popupContent = `
        <b><?= addslashes($data['nama_sma']) ?></b><br>
        <?= addslashes($data['alamat']) ?>
    `;
    L.marker([lat, lon]).addTo(map).bindPopup(popupContent).openPopup();
});
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
