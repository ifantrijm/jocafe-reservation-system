<?php
// Pastikan path koneksi sudah benar
include "../config/koneksi.php"; 

// Mengambil data dari tabel gallery
$query = mysqli_query($conn, "SELECT * FROM gallery");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jo Cafe Event Moment</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { background-color: #0a0e17; color: white; font-family: 'Poppins', sans-serif; }
        
        /* Animasi Slide */
        .fade-in-slide {
            opacity: 0;
            transform: translateY(30px);
            animation: slideUp 0.8s ease forwards;
        }
        @keyframes slideUp {
            to { opacity: 1; transform: translateY(0); }
        }

        .gallery-img { 
            height: 250px; 
            object-fit: cover; 
            border-radius: 15px; 
            transition: 0.4s;
            width: 100%;
        }
        .gallery-item:hover .gallery-img { 
            transform: scale(1.05); 
            border: 2px solid #f89b1c; 
        }

        .btn-custom { font-weight: 600; padding: 12px 30px; border-radius: 8px; transition: 0.3s; }
        .btn-booking { background-color: #f89b1c; color: #0a0e17; border: none; }
        .btn-booking:hover { background-color: #e08910; color: white; }
        .btn-back { background-color: transparent; border: 1px solid #4b5563; color: white; }
        .btn-back:hover { background-color: #1f2937; border-color: #f89b1c; }
    </style>
</head>
<body>

<div class="container py-5">
    <div class="text-center mb-5 fade-in-slide">
        <h1 class="fw-bold">Jo Cafe <span style="color: #f89b1c;">Event Moment</span></h1>
        <p class="text-secondary mt-3">Abadikan setiap momen berharga acara anda bersama kami dengan pelayanan terbaik.</p>
    </div>

    <div class="row g-4 mb-5">
        <?php while($row = mysqli_fetch_assoc($query)): ?>
        <div class="col-md-4 gallery-item fade-in-slide">
            <div class="card bg-transparent border-0">
                <img src="../assets/img/gallery/<?= $row['gambar']; ?>" class="gallery-img shadow" alt="Event Photo">
                <div class="card-body text-center p-2">
                    <p class="text-secondary small"><?= $row['keterangan']; ?></p>
                </div>
            </div>
        </div>
        <?php endwhile; ?>
    </div>

    <div class="d-flex justify-content-center gap-3 fade-in-slide">
        <a href="../index.php" class="btn btn-back btn-custom">Kembali</a>
        <a href="event.php" class="btn btn-booking btn-custom">Booking Sekarang</a>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>