<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: index.php");
    exit;
}
include '../db.php';

// Proses simpan data ke database
if (isset($_POST['simpan'])) {
    $nama = $_POST['nama_sma'];
    $alamat = $_POST['alamat'];
    $kecamatan = $_POST['kecamatan'];
    $akreditasi = $_POST['akreditasi'];
    $latitude = $_POST['latitude'];
    $longitude = $_POST['longitude'];
    $npsn = $_POST['npsn'];
    $sk_pendirian = $_POST['sk_pendirian'];
    $tanggal_berdiri = $_POST['tanggal_berdiri'];
    $sertifikasi = $_POST['sertifikasi'];
    $kepala_sekolah = $_POST['kepala_sekolah'];
    $operator = $_POST['operator'];
    $no_telp = $_POST['no_telp'];
    $email = $_POST['email'];
    $website = $_POST['website'];

    // Upload foto (jika ada)
    $foto = "";
    if (!empty($_FILES['foto']['name'])) {
        $foto = basename($_FILES['foto']['name']);
        $target = "../img/" . $foto;

        // Validasi ekstensi file
        $ext = strtolower(pathinfo($foto, PATHINFO_EXTENSION));
        if (in_array($ext, ['jpg', 'jpeg', 'png'])) {
            move_uploaded_file($_FILES['foto']['tmp_name'], $target);
        } else {
            die("Format foto tidak diperbolehkan! Hanya JPG, JPEG, atau PNG.");
        }
    }

    // Simpan ke database
    $query = "INSERT INTO sma 
        (nama_sma, alamat, kecamatan, akreditasi, latitude, longitude, foto, 
         npsn, sk_pendirian, tanggal_berdiri, sertifikasi, kepala_sekolah, operator, 
         no_telp, email, website)
        VALUES 
        ('$nama', '$alamat', '$kecamatan', '$akreditasi', '$latitude', '$longitude', '$foto', 
         '$npsn', '$sk_pendirian', '$tanggal_berdiri', '$sertifikasi', '$kepala_sekolah', '$operator', 
         '$no_telp', '$email', '$website')";

    mysqli_query($koneksi, $query);
    header("Location: dashboard.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tambah Data SMA</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f8f9fa; font-family: 'Poppins', sans-serif; }
        .container { margin-top: 40px; background: white; padding: 25px; border-radius: 10px; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
        .btn { border-radius: 6px; }
    </style>
</head>
<body>
<div class="container">
    <h3 class="text-center text-primary mb-4">Tambah Data SMA</h3>
    <form method="POST" enctype="multipart/form-data">

        <div class="mb-3">
            <label>Nama SMA</label>
            <input type="text" name="nama_sma" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Alamat</label>
            <textarea name="alamat" class="form-control" required></textarea>
        </div>

        <div class="mb-3">
            <label>Kecamatan</label>
            <input type="text" name="kecamatan" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Akreditasi</label>
            <input type="text" name="akreditasi" class="form-control">
        </div>

        <div class="row">
            <div class="col-md-6 mb-3">
                <label>Latitude</label>
                <input type="text" name="latitude" class="form-control" placeholder="contoh: -0.932512" required>
            </div>
            <div class="col-md-6 mb-3">
                <label>Longitude</label>
                <input type="text" name="longitude" class="form-control" placeholder="contoh: 100.353845" required>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6 mb-3">
                <label>NPSN</label>
                <input type="text" name="npsn" class="form-control">
            </div>
            <div class="col-md-6 mb-3">
                <label>No. SK Pendirian</label>
                <input type="text" name="sk_pendirian" class="form-control">
            </div>
        </div>

        <div class="row">
            <div class="col-md-6 mb-3">
                <label>Tanggal Berdiri</label>
                <input type="date" name="tanggal_berdiri" class="form-control">
            </div>
            <div class="col-md-6 mb-3">
                <label>Sertifikasi</label>
                <input type="text" name="sertifikasi" class="form-control">
            </div>
        </div>

        <div class="row">
            <div class="col-md-6 mb-3">
                <label>Kepala Sekolah</label>
                <input type="text" name="kepala_sekolah" class="form-control">
            </div>
            <div class="col-md-6 mb-3">
                <label>Operator</label>
                <input type="text" name="operator" class="form-control">
            </div>
        </div>

        <div class="row">
            <div class="col-md-4 mb-3">
                <label>No. Telepon</label>
                <input type="text" name="no_telp" class="form-control">
            </div>
            <div class="col-md-4 mb-3">
                <label>Email</label>
                <input type="email" name="email" class="form-control">
            </div>
            <div class="col-md-4 mb-3">
                <label>Website</label>
                <input type="text" name="website" class="form-control" placeholder="contoh: https://smaexample.sch.id">
            </div>
        </div>

        <div class="mb-3">
            <label>Foto Sekolah</label>
            <input type="file" name="foto" class="form-control">
        </div>

        <button type="submit" name="simpan" class="btn btn-success">💾 Simpan</button>
        <a href="dashboard.php" class="btn btn-secondary">⬅ Kembali</a>
    </form>
</div>
</body>
</html>
