<?php
require "../config/koneksi.php";

// Ambil ID dari URL
$id = $_GET['id'];
$query = mysqli_query($conn, "SELECT reservasi_event.*, pelanggan.nama 
                              FROM reservasi_event 
                              JOIN pelanggan ON reservasi_event.id_pelanggan = pelanggan.id_pelanggan 
                              WHERE reservasi_event.id_event_res = '$id'");
$data = mysqli_fetch_assoc($query);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <title>Nota Reservasi Event</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: #13171c; color: white; padding: 50px; }
        .nota-card { background: #1c2128; padding: 30px; border-radius: 15px; max-width: 500px; margin: auto; border: 1px solid #f89d13; }
    </style>
</head>
<body>
    <div class="nota-card">
        <h3 class="text-warning text-center">Nota Reservasi Event</h3>
        <hr>
        <p><strong>Nama:</strong> <?= $data['nama']; ?></p>
        <p><strong>Jenis Event:</strong> <?= $data['jenis_event']; ?></p>
        <p><strong>Tanggal:</strong> <?= $data['tanggal_event']; ?></p>
        <p><strong>Jam:</strong> <?= $data['jam_event']; ?></p>
        <p><strong>Status:</strong> <span class="badge bg-warning text-dark"><?= $data['status_booking']; ?></span></p>
        <hr>
        <p class="text-center small text-muted">Silakan tunjukkan nota ini kepada admin Jo Cafe di tempat untuk konfirmasi.</p>
        <button onclick="window.print()" class="btn btn-outline-warning w-100">Cetak Nota</button>
        <a href="../index.php" class="btn btn-secondary w-100 mt-2">Kembali ke Beranda</a>
    </div>
</body>
</html>