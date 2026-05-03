<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jo Cafe - Gallery</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Playball&family=Poppins:wght@400;600&display=swap');

        body {
            background-color: #000000; /* Dasar hitam pekat */
            color: white;
            font-family: 'Poppins', sans-serif;
            margin: 0;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        .gallery-page-wrapper {
            flex: 1; /* Memastikan konten mendorong footer ke bawah */
            background: linear-gradient(rgba(0, 0, 0, 0.85), rgba(0, 0, 0, 0.85)), 
                        url('https://images.unsplash.com/photo-1554118811-1e0d58224f24?q=80&w=1920');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
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

        .gallery-section-subtitle {
            color: #f39c12;
            font-weight: 600;
            display: block;
        }

        .gallery-card {
            background: rgba(255, 255, 255, 0.08); 
            border-radius: 15px;
            padding: 12px;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            transition: all 0.3s ease;
            cursor: pointer;
        }

        /* EFEK KOTAK OREN SAAT HOVER */
        .gallery-card:hover {
            border: 4px solid #f39c12; 
            transform: scale(1.02);
        }

        .img-item {
            width: 100%;
            object-fit: cover;
            border-radius: 10px;
        }

        .row-1 .img-item { height: 200px; }
        .row-2 .img-item { height: 320px; }
        .row-3 .img-item { height: 380px; }

        .section-divider {
            border: 0;
            height: 1px;
            background: rgba(255,255,255,0.2);
            margin: 50px auto;
            width: 90%;
        }

        /* FOOTER BERSIH TANPA GAMBAR */
        footer {
            text-align: center;
            font-size: 12px;
            color: #f39c12;
            padding: 20px 0;
            background-color: #000000; /* Hitam Solid */
            border-top: 1px solid #333;
            width: 100%;
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
                <span class="gallery-section-subtitle">Jo Cafe</span>
            </div>
            <div class="flex-grow-1 ms-4" style="height: 1px; background-color: rgba(255,255,255,0.3);"></div>
        </div>

        <div class="row g-3 row-1">
            <div class="col-md-2 col-6"><div class="gallery-card"><img src="https://images.unsplash.com/photo-1559925393-8be0ec4767c8?w=400" class="img-item"></div></div>
            <div class="col-md-2 col-6"><div class="gallery-card"><img src="https://images.unsplash.com/photo-1517048676732-d65bc937f952?w=400" class="img-item"></div></div>
            <div class="col-md-2 col-6"><div class="gallery-card"><img src="https://images.unsplash.com/photo-1445116572660-236099ec97a0?w=400" class="img-item"></div></div>
            <div class="col-md-2 col-6"><div class="gallery-card"><img src="https://images.unsplash.com/photo-1521017432531-fbd92d768814?w=400" class="img-item"></div></div>
            <div class="col-md-2 col-6"><div class="gallery-card"><img src="https://images.unsplash.com/photo-1514933651103-005eec06c04b?w=800" class="img-item"></div></div>
        </div>

        <hr class="section-divider">

        <div class="row g-4 row-2">
            <div class="col-md-3 col-6"><div class="gallery-card"><img src="https://images.unsplash.com/photo-1495474472287-4d71bcdd2085?w=600" class="img-item"></div></div>
            <div class="col-md-3 col-6"><div class="gallery-card"><img src="https://images.unsplash.com/photo-1509042239860-f550ce710b93?w=600" class="img-item"></div></div>
            <div class="col-md-3 col-6"><div class="gallery-card"><img src="https://images.unsplash.com/photo-1511920170033-f8396924c348?w=600" class="img-item"></div></div>
            <div class="col-md-3 col-6"><div class="gallery-card"><img src="https://images.unsplash.com/photo-1552566626-52f8b828add9?w=600" class="img-item"></div></div>
        </div>

        <hr class="section-divider">

        <div class="row g-4 row-3">
            <div class="col-md-4"><div class="gallery-card"><img src="https://images.unsplash.com/photo-1521017432531-fbd92d768814?w=800" class="img-item"></div></div>
            <div class="col-md-4"><div class="gallery-card"><img src="https://images.unsplash.com/photo-1559925393-8be0ec4767c8?w=400" class="img-item"></div></div>
            <div class="col-md-4"><div class="gallery-card"><img src="https://images.unsplash.com/photo-1514933651103-005eec06c04b?w=800" class="img-item"></div></div>
        </div>
    </div>
</div>

<footer>
    Copyright © 2026 Jo Cafe Gallery. All Rights Reserved.
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>