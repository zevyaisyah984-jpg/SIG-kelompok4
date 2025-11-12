<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: index.php");
    exit;
}
include '../db.php';

// Ambil ID dari URL
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Ambil data dulu untuk hapus file foto (jika ada)
    $result = mysqli_query($koneksi, "SELECT foto FROM sma WHERE id='$id'");
    $data = mysqli_fetch_assoc($result);

    if (!empty($data['foto']) && file_exists("../img/" . $data['foto'])) {
        unlink("../img/" . $data['foto']); // Hapus file foto dari folder
    }

    // Hapus data dari database
    mysqli_query($koneksi, "DELETE FROM sma WHERE id='$id'");
}

header("Location: dashboard.php");
exit;
?>
