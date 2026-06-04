<?php
require "../config/koneksi.php";

// Ambil ID dari URL
$id = $_GET['id'];

// Query Gabungan (Join) untuk mengambil data Lengkap (Udah dibersihin dari detail_reservasi)
$sql = "SELECT rr.*, p.nama, p.telepon, r.nama_area 
        FROM reservasi_room rr
        JOIN pelanggan p ON rr.id_pelanggan = p.id_pelanggan
        JOIN room r ON rr.id_room = r.id_room
        WHERE rr.id_reservasi_room = '$id'";

$query = mysqli_query($conn, $sql);
$data = mysqli_fetch_assoc($query);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Nota Reservasi | Jo Cafe</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { background-color: #0a0e17; color: white; padding: 50px 0; }
        .nota-box { background: #1c2128; border: 2px dashed #f89d13; padding: 30px; border-radius: 15px; }
        .line { border-bottom: 1px solid rgba(255,255,255,0.1); margin: 15px 0; }
        .text-gold { color: #f89d13; }
    </style>
</head>
<body>
<div class="container" style="max-width: 600px;">
    <div class="nota-box shadow-lg">
        <div class="text-center mb-4">
            <h2 class="fw-bold text-gold">JO CAFE</h2>
            <p class="small text-muted">Nota Reservasi Digital</p>
        </div>

        <div class="row mb-2">
            <div class="col-6 text-muted small">Nomor Reservasi:</div>
            <div class="col-6 text-end fw-bold">#JO-<?= $data['id_reservasi_room']; ?></div>
        </div>
        <div class="row mb-2">
            <div class="col-6 text-muted small">Tanggal Pesan:</div>
            <div class="col-6 text-end"><?= $data['tanggal_reservasi']; ?></div>
        </div>

        <div class="line"></div>

        <h6 class="text-gold fw-bold mb-3">DETAIL PELANGGAN</h6>
        <p class="mb-1"><strong><?= $data['nama']; ?></strong></p>
        <p class="small text-muted mb-0"><?= $data['telepon']; ?></p>

        <div class="line"></div>

        <h6 class="text-gold fw-bold mb-3">DETAIL RESERVASI</h6>
        <div class="d-flex justify-content-between mb-2">
            <span>Area:</span>
            <span class="fw-bold"><?= $data['nama_area']; ?></span>
        </div>
        <div class="d-flex justify-content-between mb-2">
            <span>Waktu:</span>
        <span>
            <?= $data['jam_mulai']; ?> -
            <?= (!empty($data['jam_selesai']) && $data['jam_selesai'] != '00:00:00') ? $data['jam_selesai'] . ' WIB' : 'Selesai'; ?>
        </span>           
        </div>

        <div class="line"></div>

        <div class="alert alert-warning bg-transparent border-warning text-warning small">
            <i class="fas fa-info-circle me-2"></i>
            Silakan tunjukkan nota ini kepada staf kami saat kedatangan. Status reservasi Anda saat ini adalah <strong>Menunggu Konfirmasi Admin</strong>.
        </div>

        <div class="text-center mt-4 no-print">
            <button onclick="window.print()" class="btn btn-outline-light me-2">
                <i class="fas fa-print me-2"></i>Cetak Nota
            </button>
            <a href="detail.php" class="btn btn-warning fw-bold">Kembali ke Beranda</a>
        </div>
    </div>
    <p class="text-center text-muted small mt-4">© 2026 Jo Cafe Management System</p>
</div>
</body>
</html>