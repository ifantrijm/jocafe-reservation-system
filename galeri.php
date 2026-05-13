<?php
// 1. KONEKSI DATABASE
$conn = mysqli_connect("localhost", "root", "", "jocafee");
if (!$conn) { die("Koneksi Gagal: " . mysqli_connect_error()); }

// 2. AMBIL SEMUA DATA GAMBAR
$query = mysqli_query($conn, "SELECT * FROM gallery ORDER BY id_gallery DESC");
$all_images = [];
while($row = mysqli_fetch_assoc($query)) {
    $all_images[] = $row;
}

$total_images = count($all_images);
$current_index = 0;
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jo Cafe - Gallery Grid</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Playball&family=Poppins:wght@400;600&display=swap');

        body {
            background-color: #000000;
            color: white;
            font-family: 'Poppins', sans-serif;
            margin: 0;
        }

        .gallery-page-wrapper {
            background: linear-gradient(rgba(0, 0, 0, 0.85), rgba(0, 0, 0, 0.85)), 
                        url('https://images.unsplash.com/photo-1554118811-1e0d58224f24?q=80&w=1920');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            min-height: 100vh;
            padding-bottom: 50px;
        }

        .main-header {
            background-color: rgba(13, 16, 21, 0.95);
            padding: 15px 0;
            border-bottom: 1px solid #ffffff;
            margin-bottom: 40px;
        }

        .gallery-section-title {
            font-family: 'Playball', cursive;
            font-size: 3.5rem;
            line-height: 1;
        }

        .gallery-card {
            background: rgba(255, 255, 255, 0.08); 
            border-radius: 15px;
            padding: 10px;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            transition: all 0.3s ease;
        }

        .gallery-card:hover {
            border: 4px solid #f39c12; 
            transform: scale(1.02);
        }

        .img-item {
            width: 100%;
            object-fit: cover;
            border-radius: 10px;
        }

        /* Tinggi gambar berdasarkan ukuran kotak */
        .size-small { height: 180px; }
        .size-medium { height: 280px; }
        .size-large { height: 350px; }

        footer {
            text-align: center;
            font-size: 12px;
            color: #f39c12;
            padding: 20px 0;
            background-color: #000000;
            border-top: 1px solid #333;
        }
    </style>
</head>
<body>

<div class="gallery-page-wrapper">
    <header class="main-header">
        <div class="container">
            <h1 class="m-0">Jo Cafe</h1>
            <p class="m-0 small" style="color: #f39c12; font-weight: 600;">Authentic Coffee Bar & Kitchen</p>
        </div>
    </header>

    <div class="container">
        <div class="d-flex align-items-center mb-5">
            <div>
                <h2 class="gallery-section-title">Gallery</h2>
                <p class="m-0" style="color: #f39c12; font-weight: 600; letter-spacing: 2px;">MOMENTS AT JO CAFE</p>
            </div>
            <div class="flex-grow-1 ms-4" style="height: 1px; background-color: rgba(255,255,255,0.3);"></div>
        </div>

        <?php 
        // LOOPING POLA: 6, 4, 3
        while ($current_index < $total_images): 
        ?>

            <div class="row g-3 mb-4">
                <?php 
                for ($j = 0; $j < 6 && $current_index < $total_images; $j++): 
                    $img = $all_images[$current_index++];
                ?>
                <div class="col-md-2 col-6">
                    <div class="gallery-card">
                        <img src="assets/img/gallery/<?= $img['gambar']; ?>" class="img-item size-small">
                    </div>
                </div>
                <?php endfor; ?>
            </div>

            <?php if ($current_index < $total_images): ?>
            <div class="row g-4 mb-4">
                <?php 
                for ($j = 0; $j < 4 && $current_index < $total_images; $j++): 
                    $img = $all_images[$current_index++];
                ?>
                <div class="col-md-3 col-6">
                    <div class="gallery-card">
                        <img src="assets/img/gallery/<?= $img['gambar']; ?>" class="img-item size-medium">
                    </div>
                </div>
                <?php endfor; ?>
            </div>
            <?php endif; ?>

            <?php if ($current_index < $total_images): ?>
            <div class="row g-4 mb-4">
                <?php 
                for ($j = 0; $j < 3 && $current_index < $total_images; $j++): 
                    $img = $all_images[$current_index++];
                ?>
                <div class="col-md-4 col-12">
                    <div class="gallery-card">
                        <img src="assets/img/gallery/<?= $img['gambar']; ?>" class="img-item size-large">
                    </div>
                </div>
                <?php endfor; ?>
            </div>
            <?php endif; ?>

        <?php endwhile; ?>

        <?php if ($total_images == 0): ?>
            <div class="text-center py-5">
                <p style="color: #666;">Belum ada foto yang diupload.</p>
            </div>
        <?php endif; ?>

    </div>
</div>

<footer>
    Copyright © 2026 Jo Cafe Gallery. All Rights Reserved.
</footer>

</body>
</html>