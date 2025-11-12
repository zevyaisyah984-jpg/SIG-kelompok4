<?php
include 'db.php';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Cari SMA Negeri di Kota Padang</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

    <style>
        body {
            background-color: #e9f7ff;
            font-family: 'Poppins', sans-serif;
        }
        #map {
            height: 550px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .btn-detail {
            background-color: #0d6efd !important;
            color: #fff !important;
            padding: 5px 10px;
            border-radius: 5px;
            text-decoration: none;
            font-weight: 500;
        }
        .btn-detail:hover {
            background-color: #084298 !important;
        }
    </style>

    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
</head>
<body>

<div class="container mt-4">
    <h3 class="text-center text-primary mb-4">Pencarian SMA Negeri di Kota Padang</h3>

    <div class="row">
        <!-- Peta -->
        <div class="col-md-8 mb-4">
            <div id="map"></div>
        </div>

        <!-- Form pencarian -->
        <div class="col-md-4 mb-4">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5 class="card-title mb-3">Pilihan Pencarian</h5>
                    <form method="GET" action="">
                        <div class="mb-3">
                            <label class="form-label">Pilih Kecamatan:</label>
                            <select name="kecamatan" class="form-select">
                                <option value="">-- Semua Kecamatan --</option>
                                <?php
                                $res = mysqli_query($koneksi, "SELECT DISTINCT kecamatan FROM sma ORDER BY kecamatan ASC");
                                while ($row = mysqli_fetch_assoc($res)) {
                                    $selected = (isset($_GET['kecamatan']) && $_GET['kecamatan'] == $row['kecamatan']) ? "selected" : "";
                                    echo "<option value='{$row['kecamatan']}' $selected>{$row['kecamatan']}</option>";
                                }
                                ?>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Nama SMA:</label>
                            <select name="nama_sma" class="form-select">
                                <option value="">-- Semua SMA --</option>
                                <?php
                                $res2 = mysqli_query($koneksi, "SELECT nama_sma FROM sma ORDER BY nama_sma ASC");
                                while ($row = mysqli_fetch_assoc($res2)) {
                                    $selected = (isset($_GET['nama_sma']) && $_GET['nama_sma'] == $row['nama_sma']) ? "selected" : "";
                                    echo "<option value='{$row['nama_sma']}' $selected>{$row['nama_sma']}</option>";
                                }
                                ?>
                            </select>
                        </div>

                        <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-4">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-search"></i> Cari
                            </button>
                            <a href="cari.php" class="btn btn-secondary">
                                <i class="fas fa-redo"></i> Reset
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Inisialisasi Peta -->
<script>
    var map = L.map('map').setView([-0.95, 100.35], 12);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
        attribution: '© OpenStreetMap contributors'
    }).addTo(map);
</script>

<?php
// ====== FILTER & MARKER ======
$where = [];
if (!empty($_GET['kecamatan'])) {
    $kec = mysqli_real_escape_string($koneksi, $_GET['kecamatan']);
    $where[] = "kecamatan = '$kec'";
}
if (!empty($_GET['nama_sma'])) {
    $nama = mysqli_real_escape_string($koneksi, $_GET['nama_sma']);
    $where[] = "nama_sma = '$nama'";
}

$filter = count($where) ? "WHERE " . implode(" AND ", $where) : "";
$query = mysqli_query($koneksi, "SELECT * FROM sma $filter");

if (mysqli_num_rows($query) > 0) {
    while ($data = mysqli_fetch_assoc($query)) {
        $id = $data['id'];
        $nama = addslashes($data['nama_sma']);
        $alamat = addslashes($data['alamat']);
        $foto = (!empty($data['foto'])) ? $data['foto'] : 'default.jpg';
        $lat = $data['latitude'];
        $lng = $data['longitude'];

        // Ganti path ini agar sesuai dengan folder kamu
        // Kalau gambar di folder "image/", maka gunakan "image/"
        echo "
        <script>
            var marker = L.marker([$lat, $lng]).addTo(map);
            marker.bindPopup(`
                <b>$nama</b><br>
                $alamat<br><br>
                <img src='image/$foto' width='180' height='120' style='border-radius:8px; object-fit:cover;'><br>
                <a href='detail.php?id=$id' class='btn btn-primary btn-sm mt-2'>Lihat Detail</a>
            `);
        </script>
        ";
    }
} else {
    echo "<script>alert('Data tidak ditemukan!');</script>";
}
?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
