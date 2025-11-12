<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: index.php");
    exit;
}
include '../db.php';

// --- Validasi ID agar tidak kosong ---
if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("ID tidak ditemukan!");
}

$id = mysqli_real_escape_string($koneksi, $_GET['id']);
$query = mysqli_query($koneksi, "SELECT * FROM sma WHERE id = '$id'");
if (mysqli_num_rows($query) == 0) {
    die("Data tidak ditemukan!");
}
$data = mysqli_fetch_assoc($query);

// --- Proses update data ---
if (isset($_POST['update'])) {
    $nama       = mysqli_real_escape_string($koneksi, $_POST['nama_sma']);
    $alamat     = mysqli_real_escape_string($koneksi, $_POST['alamat']);
    $kecamatan  = mysqli_real_escape_string($koneksi, $_POST['kecamatan']);
    $akreditasi = mysqli_real_escape_string($koneksi, $_POST['akreditasi']);
    $latitude   = mysqli_real_escape_string($koneksi, $_POST['latitude']);
    $longitude  = mysqli_real_escape_string($koneksi, $_POST['longitude']);

    // Simpan nama file lama kalau tidak upload baru
    $foto = $data['foto'];

    // --- Jika ada upload foto baru ---
    if (!empty($_FILES['foto']['name'])) {
        $nama_file = basename($_FILES['foto']['name']);
        $ext = strtolower(pathinfo($nama_file, PATHINFO_EXTENSION));
        $allowed_ext = ['jpg', 'jpeg', 'png', 'gif'];

        if (in_array($ext, $allowed_ext)) {
            $nama_baru = "sma_" . time() . "." . $ext;
            $target = "../img/" . $nama_baru;

            // hapus foto lama (jika ada dan bukan default)
            if (!empty($foto) && file_exists("../img/" . $foto) && $foto != "default.jpg") {
                unlink("../img/" . $foto);
            }

            if (move_uploaded_file($_FILES['foto']['tmp_name'], $target)) {
                $foto = $nama_baru;
            } else {
                echo "<div class='alert alert-danger'>Gagal mengupload foto.</div>";
            }
        } else {
            echo "<div class='alert alert-warning'>Format foto tidak diperbolehkan! (Gunakan JPG, PNG, GIF)</div>";
        }
    }

    // --- Update data ke database ---
    $update = mysqli_query($koneksi, "
        UPDATE sma SET 
            nama_sma='$nama',
            alamat='$alamat',
            kecamatan='$kecamatan',
            akreditasi='$akreditasi',
            latitude='$latitude',
            longitude='$longitude',
            foto='$foto'
        WHERE id='$id'
    ");

    if ($update) {
        header("Location: dashboard.php?pesan=update_sukses");
        exit;
    } else {
        echo "<div class='alert alert-danger'>Terjadi kesalahan saat menyimpan data!</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Data SMA</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f8f9fa; font-family: 'Poppins', sans-serif; }
        .container { margin-top: 40px; background: white; padding: 25px; border-radius: 10px; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
        img { border-radius: 8px; margin-bottom: 10px; }
    </style>
</head>
<body>
<div class="container">
    <h3 class="text-center text-primary mb-4">Edit Data SMA</h3>

    <form method="POST" enctype="multipart/form-data">
        <div class="mb-3">
            <label>Nama SMA</label>
            <input type="text" name="nama_sma" class="form-control" value="<?= htmlspecialchars($data['nama_sma']) ?>" required>
        </div>
        <div class="mb-3">
            <label>Alamat</label>
            <textarea name="alamat" class="form-control" required><?= htmlspecialchars($data['alamat']) ?></textarea>
        </div>
        <div class="mb-3">
            <label>Kecamatan</label>
            <input type="text" name="kecamatan" class="form-control" value="<?= htmlspecialchars($data['kecamatan']) ?>" required>
        </div>
        <div class="mb-3">
            <label>Akreditasi</label>
            <input type="text" name="akreditasi" class="form-control" value="<?= htmlspecialchars($data['akreditasi']) ?>" required>
        </div>

        <div class="row">
            <div class="col-md-6 mb-3">
                <label>Latitude</label>
                <input type="text" name="latitude" class="form-control" value="<?= htmlspecialchars($data['latitude']) ?>" required>
            </div>
            <div class="col-md-6 mb-3">
                <label>Longitude</label>
                <input type="text" name="longitude" class="form-control" value="<?= htmlspecialchars($data['longitude']) ?>" required>
            </div>
        </div>

        <div class="mb-3">
            <label>Foto Sekolah Saat Ini</label><br>
            <img src="../img/<?= !empty($data['foto']) ? $data['foto'] : 'default.jpg' ?>" width="150" alt="Foto Sekolah">
            <input type="file" name="foto" class="form-control mt-2" accept=".jpg,.jpeg,.png,.gif">
        </div>

        <button type="submit" name="update" class="btn btn-primary">💾 Perbarui</button>
        <a href="dashboard.php" class="btn btn-secondary">⬅ Kembali</a>
    </form>
</div>
</body>
</html>
