<?php
// KONEKSI DATABASE
$conn = mysqli_connect("localhost", "root", "", "jocafee");
if (!$conn) { die("Koneksi Gagal: " . mysqli_connect_error()); }

/* =========================================================================
   PENGATURAN GAMBAR HALAMAN DEPAN (HOME)
   -------------------------------------------------------------------------
   Cara memilih gambar:
   Lihat ID gambar di menu Management Gallery (misal: ID 5), lalu ubah angka 
   0 di bawah ini menjadi 5. 
   Jika dibiarkan 0, maka akan memakai gambar bawaan (default).
   ========================================================================= */

// --- 1. GAMBAR RESERVASI EVENT ---
$img_event_banner1 = 12; // Banner Event Kiri
$img_event_banner2 = 13; // Banner Event Kanan
$img_event_card1   = 14; // Card Prewedding
$img_event_card2   = 15; // Card Yearbook
$img_event_card3   = 16; // Card Graduation

// --- 2. GAMBAR RESERVASI TEMPAT ---
$img_tempat_banner1 = 17; // Banner Tempat Kiri
$img_tempat_banner2 = 18; // Banner Tempat Kanan
$img_tempat_card1   = 19; // Card Ruang 1
$img_tempat_card2   = 20; // Card Ruang 2
$img_tempat_card3   = 21; // Card Ruang 3

// --- 3. GAMBAR BEST SELLER ---
$img_bestseller_1 = 22; // Makanan 1 (Pasta)
$img_bestseller_2 = 23; // Makanan 2 (Bulgogi)
$img_bestseller_3 = 24; // Makanan 3 (Tteokbokki)

/* ========================================================================= */

// Fungsi untuk memanggil gambar dari database berdasarkan ID
function get_gambar($conn, $id_gallery, $default_url) {
    if ($id_gallery == 0) return $default_url; // Jika diset 0, pakai default

    $query = mysqli_query($conn, "SELECT gambar FROM gallery WHERE id_gallery = '$id_gallery'");
    if ($query && mysqli_num_rows($query) > 0) {
        $data = mysqli_fetch_assoc($query);
        $path_file = "assets/img/gallery/" . $data['gambar'];
        
        // Cek apakah file fisik gambarnya benar-benar ada di folder
        if (!empty($data['gambar']) && file_exists($path_file)) {
            return $path_file;
        }
    }
    return $default_url; // Jika ID tidak ditemukan/file terhapus, pakai default
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Company Profile - Jo Cafe</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;800&family=Great+Vibes&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <style>
        :root {
            --bg-dark: #0a0e17; 
            --jo-orange: #f89b1c;
            --jo-orange-hover: #e08915;
            --text-light: #ffffff;
            --bg-card: #111826;
            --border-dark: rgba(255, 255, 255, 0.1);
        }

        html { scroll-behavior: smooth; }

        body {
            background-color: var(--bg-dark);
            color: var(--text-light);
            font-family: 'Poppins', sans-serif;
            overflow-x: hidden;
        }

        /* Navbar */
        .navbar-custom {
            background-color: var(--bg-dark);
            padding: 1px 1px;
            border-bottom: 1px solid #ffffff;
            transition: top 0.4s ease-in-out; 
        }
        .navbar-brand {
            color: var(--jo-orange);
            font-weight: 600;
            font-size: 1.5rem;
            line-height: 1.5;
            display: block;
            margin: 0;
        }
        .navbar-brand span {
            display: block;
            margin-top: -2px;
            line-height: 2.0;
        }

        .nav-link {
            color: var(--text-light) !important;
            font-size: 0.9rem;
            margin-left: 15px;
            text-transform: uppercase;
        }
        .nav-link:hover { color: var(--jo-orange) !important; }

        .dropdown-menu {
             background-color: var(--bg-dark);
        }
        .nav-item {
             background-color: var(--bg-dark);
        }

        /* Buttons */
        .btn-jo {
            background-color: var(--jo-orange);
            color: white;
            font-weight: 600;
            border-radius: 25px;
            padding: 10px 30px;
            border: 2px solid var(--jo-orange);
            transition: 0.3s;
            display: inline-block;
            text-decoration: none;
        }
        .btn-jo:hover { background-color: var(--jo-orange-hover); border-color: var(--jo-orange-hover); color: white; }
        
        .btn-outline-jo {
            background-color: transparent;
            color: var(--jo-orange);
            font-weight: 600;
            border-radius: 25px;
            padding: 10px 30px;
            border: 2px solid var(--jo-orange);
            transition: 0.3s;
            display: inline-block;
            text-decoration: none;
        }
        .btn-outline-jo:hover { background-color: var(--jo-orange); color: white; }

        /* Typography */
        .title-cursive { font-family: 'Great Vibes', cursive; color: var(--text-light); font-size: 3.5rem; margin-bottom: 20px; }
        .hero-title { font-size: 3.5rem; font-weight: 800; line-height: 1.2; }

        /* Logo Hero */
        .hero-logo-container {
            width: 450px; height: 450px; border: 2px solid white; border-radius: 50%;
            display: flex; align-items: center; justify-content: center; margin: 0 auto;
        }
        .hero-logo-container img { width: 90%; border-radius: 50%; animation: imgRotate 50s linear infinite; }
        @keyframes imgRotate { from { transform: rotate(0deg); } to { transform: rotate(360deg); } }

        /* Sections */
        .section-padding { padding: 80px 0; }

        /* Image Cards */
        .img-card { border-radius: 15px; overflow: hidden; position: relative; border: 1px solid var(--border-dark); }
        .img-card img { width: 100%; height: 250px; object-fit: cover; transition: transform 0.3s; }
        .img-card:hover img { transform: scale(1.05); }
        .img-card-title { text-align: center; margin-top: 10px; font-weight: 600; }

        /* Best Seller */
        .food-item { position: relative; text-align: center; }
        .food-item img { width: 200px; height: 200px; border-radius: 50%; object-fit: cover; border: 3px solid var(--jo-orange); }
        .food-title {
            font-family: 'Great Vibes', cursive; color: var(--jo-orange); font-size: 2rem;
            position: absolute; top: -20px; left: 50%; transform: translateX(-50%); width: 100%;
        }

        /* Card Dynamic (Menu & Blog) */
        .card-jo {
            background-color: var(--bg-card);
            border: 1px solid var(--border-dark);
            border-radius: 15px;
            overflow: hidden;
            transition: 0.3s ease;
        }
        .card-jo:hover {
            transform: translateY(-5px);
            border-color: var(--jo-orange);
        }
        .card-jo-img {
            width: 100%;
            height: 200px;
            object-fit: cover;
        }

        /* Custom Nav Pills untuk Menu */
        .nav-pills .nav-link-custom {
            color: white;
            border: 1px solid var(--border-dark);
            margin: 0 5px;
            border-radius: 20px;
            padding: 8px 25px;
            font-weight: 600;
            font-size: 0.9rem;
        }
        .nav-pills .nav-link-custom.active {
            background-color: var(--jo-orange);
            border-color: var(--jo-orange);
            color: white;
        }

        /* Card Testimoni */
        .testi-home-card {
            background-color: #111826; border: 1px solid #1f2937; border-radius: 15px;
            padding: 25px; height: 100%; transition: 0.3s; border-left: 4px solid var(--jo-orange);
        }
        .testi-home-card:hover { transform: translateY(-5px); box-shadow: 0 10px 20px rgba(248, 155, 28, 0.1); }


        /* Footer */
        /* Custom CSS khusus untuk Footer Jo Cafe */
    .footer-custom {
        background-color: var(--bg-dark); /* Tema hitam premium */
        color: #b5b5b5; 
        padding: 70px 0 30px 0;
        font-family: 'Segoe UI', Roboto, sans-serif;
    }
    .footer-custom h5, .footer-custom h6 {
        color: #ffffff;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 1px;
    }
    .footer-custom .text-warning-custom {
        color: #ffb300 !important; /* Warna kuning/emas elegan */
    }
    
    /* Efek hover untuk link */
    .footer-custom .footer-links a {
        color: #b5b5b5;
        transition: all 0.3s ease;
        display: inline-block;
    }
    .footer-custom .footer-links a:hover {
        color: #ffb300 !important;
        transform: translateX(4px); /* Efek geser kecil saat di-hover */
    }
    
    /* Tombol ikon media sosial */
    .footer-custom .social-icons a {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 38px;
        height: 38px;
        background-color: #222222;
        color: #ffffff;
        border-radius: 50%;
        margin-right: 10px;
        transition: background-color 0.3s ease, transform 0.3s ease;
    }
    .footer-custom .social-icons a:hover {
        background-color: #ffb300;
        color: #111111 !important;
        transform: translateY(-4px);
    }
    
    /* Daftar kontak detail */
    .footer-custom .contact-list li {
        margin-bottom: 14px;
        display: flex;
        align-items: flex-start;
        font-size: 0.9rem;
    }
    .footer-custom .contact-list i {
        color: #ffb300;
        margin-right: 12px;
        font-size: 1.1rem;
        margin-top: 1px;
    }
    
    /* Frame untuk Peta Lokasi */
    .footer-custom .map-container {
        border: 2px solid #222222;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 8px 24px rgba(0,0,0,0.6);
        transition: border-color 0.3s ease;
    }
    .footer-custom .map-container:hover {
        border-color: #ffb300;
    }
    .footer-custom .border-top-custom {
        border-top: 1px solid #222222 !important;
    }

    /* --- DESAIN BOX BEST SELLER BARU --- */
.bestseller-box {
    background-color: #111826;
    border: 1px solid rgba(255, 255, 255, 0.1);
    border-radius: 12px;
    overflow: hidden;
    transition: all 0.3s ease;
    position: relative;
    text-align: center;
}

.bestseller-box:hover {
    transform: translateY(-8px);
    border-color: #f89b1c;
    box-shadow: 0 10px 25px rgba(248, 155, 28, 0.15);
}

.bestseller-img-container {
    width: 100%;
    height: 220px;
    overflow: hidden;
    position: relative;
}

.bestseller-img-container img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.5s ease;
}

.bestseller-box:hover .bestseller-img-container img {
    transform: scale(1.1);
}

.bestseller-badge {
    position: absolute;
    top: 15px;
    right: 15px;
    background: #f89b1c;
    color: #0a0e17;
    font-size: 0.75rem;
    font-weight: 800;
    padding: 5px 12px;
    border-radius: 20px;
    z-index: 2;
    letter-spacing: 1px;
}

.bestseller-content {
    padding: 20px;
}

.bs-title {
    font-family: 'Poppins', sans-serif;
    font-weight: 700;
    font-size: 1.2rem;
    color: #ffffff;
    margin-bottom: 8px;
    text-transform: capitalize;
}

.bs-desc {
    font-size: 0.85rem;
    color: #a0aec0;
    margin-bottom: 15px;
    line-height: 1.5;
}

.bs-price {
    font-family: 'Poppins', sans-serif;
    font-weight: 800;
    color: #f89b1c;
    font-size: 1.15rem;
}
    </style>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
</head>
<body>

    <nav id="joNavbar" class="navbar navbar-expand-lg navbar-custom fixed-top">
        <div class="container">
            <a class="navbar-brand" href="home.php">Jo Cafe<br><span style="font-size: 0.8rem; color: #fff; font-weight: 400;">Authentic Coffee, Bar & Kitchen</span></a>
            <div class="collapse navbar-collapse justify-content-end">
                <ul class="navbar-nav align-items-center">
                    <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="#welcome">Tentang Kami</a></li>
                    <li class="nav-item"><a class="nav-link" href="#galeri">Galeri</a></li>
                    <li class="nav-item"><a class="nav-link" href="#blog">Blog</a></li>
                    <li class="nav-item"><a class="nav-link" href="#menu">Menu</a></li>
                    <li class="nav-item dropdown ">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Reservasi
                    </a>
                        <ul class="dropdown-menu">                            
                            <li class="nav-item"><a class="nav-link" href="reservasi/detail.php">Reservasi Room</a></li>
                            <li class="nav-item"><a class="nav-link" href="reservasi/detail_event.php">Reservasi Event</a></li>
                        </ul> 
                    </li>                                                       
                </ul>
            </div>
        </div>
    </nav>

    <section class="section-padding" style="margin-top: 80px;">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <h1 class="hero-title mb-4">Layanan<br>Reservasi Tempat</h1>
                    <div class="d-flex gap-3 mt-4">
                        <a href="#welcome" class="btn btn-outline-jo">Selengkapnya</a>
                        <a href="page_reservasi.php" class="btn btn-jo">Reservasi</a>
                    </div>
                </div>
                <div class="col-md-6 text-center">
                    <div class="hero-logo-container">
                        <img src="https://jocafe.jember.site/assets/img/jocafe.webp" alt="Logo Jo Cafe">
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="welcome" class="section-padding text-center" style="margin-top: 50px;">
        <div class="container">
            <h4 style="color: var(--jo-orange); font-weight: 600;">Selamat datang di Jo Cafe</h4>
            <p class="mx-auto" style="max-width: 700px; color: #ccc;">
                Selamat datang di rumah kami, Jo Cafe Authentic Coffee, Bar & Kitchen. Semoga seluruh menu dan tempat kami dapat mengembalikan semangat anda di tengah pengalaman yang menyenangkan.
            </p>
            <p class="mt-4 fw-bold">Silahkan pilih Jenis Reservasi Anda</p>
            <i class="fa-solid fa-chevron-down fs-3"></i>
        </div>
    </section>

<section class="section-padding text-center">
    <div class="container">
        <h2 class="title-cursive">Reservasi Event</h2>
        <div class="row justify-content-center gap-4">
            <?php 
            $q_event = mysqli_query($conn, "SELECT * FROM gallery WHERE kategori = 'event' ORDER BY id_gallery DESC LIMIT 2");
            while($ev = mysqli_fetch_assoc($q_event)): 
            ?>
            <div class="col-md-3">
                <div class="img-card">
                    <img src="assets/img/gallery/<?= $ev['gambar'] ?>" alt="<?= htmlspecialchars($ev['keterangan']) ?>">
                </div>
                <div class="img-card-title"><?= htmlspecialchars($ev['keterangan']) ?></div>
            </div>
            <?php endwhile; ?>
        </div>
    </div>
</section>

<section class="section-padding text-center">
    <div class="container">
        <h2 class="title-cursive">Reservasi Tempat</h2>
        <div class="row justify-content-center gap-4">
            <?php 
            $q_room = mysqli_query($conn, "SELECT * FROM gallery WHERE kategori = 'room' ORDER BY id_gallery DESC LIMIT 2");
            while($rm = mysqli_fetch_assoc($q_room)): 
            ?>
            <div class="col-md-3">
                <div class="img-card">
                    <img src="assets/img/gallery/<?= $rm['gambar'] ?>" alt="<?= htmlspecialchars($rm['keterangan']) ?>">
                </div>
                <div class="img-card-title"><?= htmlspecialchars($rm['keterangan']) ?></div>
            </div>
            <?php endwhile; ?>
        </div>
    </div>
</section>

<section class="section-padding text-center">
    <div class="container">
        <h2 class="title-cursive text-white mb-2">Our Best Seller</h2>
        <p class="mb-5" style="color: #f89b1c; letter-spacing: 1px;">MENU FAVORIT PILIHAN PELANGGAN</p>
        
        <div class="row justify-content-center gap-4">
            <?php 
            $q_best = mysqli_query($conn, "SELECT * FROM menu WHERE is_bestseller = 1 LIMIT 3");
            if(mysqli_num_rows($q_best) > 0):
                while($bs = mysqli_fetch_assoc($q_best)): 
            ?>
            <div class="col-md-3">
                <div class="bestseller-box">
                    <div class="bestseller-img-container">
                        <div class="bestseller-badge">
                            <i class="fa-solid fa-fire me-1"></i> TOP
                        </div>
                        <img src="assets/img/menu/<?= $bs['gambar'] ?>" alt="<?= htmlspecialchars($bs['nama_item']) ?>">
                    </div>
                    <div class="bestseller-content">
                        <h4 class="bs-title"><?= htmlspecialchars($bs['nama_item']) ?></h4>
                        <p class="bs-desc"><?= substr(htmlspecialchars($bs['deskripsi']), 0, 40) ?></p>
                    </div>
                </div>
            </div>
            <?php 
                endwhile;
            else:
                echo "<p class='text-white w-100'>Belum ada menu best seller yang dipilih.</p>";
            endif;
            ?>
        </div>
    </div>
</section>


    <section id="galeri" class="section-padding text-center">
        <div class="container">
            <h2 class="title-cursive">Galeri Jo</h2>
            <div class="row g-4 justify-content-center mt-4">
                <?php 
                $query_galeri = mysqli_query($conn, "SELECT * FROM gallery ORDER BY id_gallery DESC LIMIT 6");
                if (mysqli_num_rows($query_galeri) > 0) {
                    while($g = mysqli_fetch_assoc($query_galeri)) {
                ?>
                <div class="col-md-4">
                    <div class="img-card shadow">
                        <img src="assets/img/gallery/<?= $g['gambar']; ?>" alt="Atmosphere Jo Cafe">
                    </div>
                </div>
                <?php 
                    }
                } else {
                    echo "<p class=''>Belum ada foto galeri terbaru.</p>";
                }
                ?>
            </div>
        </div>
    </section>

    <section id="blog" class="section-padding text-center">
        <div class="container">
            <h2 class="title-cursive">Blog & Berita Jo</h2>
            <div class="row g-4 justify-content-center mt-4 text-start">
                <?php 
                $query_blog = mysqli_query($conn, "SELECT * FROM blog ORDER BY id_blog DESC LIMIT 3");
                if (mysqli_num_rows($query_blog) > 0) {
                    while($b = mysqli_fetch_assoc($query_blog)) {
                ?>
                <div class="col-md-4">
                    <div class="card-jo shadow h-100">
                        <img src="assets/img/blog/<?= $b['gambar']; ?>" class="card-jo-img" alt="<?= htmlspecialchars($b['judul']); ?>">
                        <div class="p-4">
                            <span class="text-warning small d-block mb-2">
                                <i class="far fa-calendar-alt me-2"></i><?= date('d M Y', strtotime($b['tanggal'])); ?>
                            </span>
                            <h4 class="fw-bold mb-3 text-white"><?= htmlspecialchars($b['judul']); ?></h4>
                            <p class=" small mb-4"><?= substr(htmlspecialchars($b['isi']), 0, 100); ?>...</p>
                            <a href="blog_detail.php?id=<?= $b['id_blog']; ?>" class="text-warning text-decoration-none fw-bold small">Baca Selengkapnya →</a>
                        </div>
                    </div>
                </div>
                <?php 
                    }
                } else {
                    echo "<p class=' text-center w-100'>Belum ada artikel terbit.</p>";
                }
                ?>
            </div>
        </div>
    </section>

    <section id="menu" class="section-padding text-center">
        <div class="container">
            <h2 class="title-cursive">Menu Jo Cafe</h2>
            
            <ul class="nav nav-pills justify-content-center my-4" id="pills-tab" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link nav-link-custom active" id="all-tab" data-bs-toggle="pill" data-bs-target="#menu-all" type="button" role="tab">ALL MENU</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link nav-link-custom" id="makanan-tab" data-bs-toggle="pill" data-bs-target="#menu-makanan" type="button" role="tab">FOOD</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link nav-link-custom" id="minuman-tab" data-bs-toggle="pill" data-bs-target="#menu-minuman" type="button" role="tab">DRINK</button>
                </li>
            </ul>

            <div class="tab-content text-start mt-5" id="pills-tabContent">
                <div class="tab-pane fade show active" id="menu-all" role="tabpanel">
                    <div class="row g-4">
                        <?php 
                        $q_all = mysqli_query($conn, "SELECT * FROM menu ORDER BY id_menu DESC");
                        while($m = mysqli_fetch_assoc($q_all)):
                        ?>
                        <div class="col-md-3">
                            <div class="card-jo h-100">
                                <img src="assets/img/menu/<?= $m['gambar']; ?>" class="card-jo-img" alt="<?= htmlspecialchars($m['nama_item']); ?>">
                                <div class="p-3">
                                    <h5 class="fw-bold mb-1"><?= htmlspecialchars($m['nama_item']); ?></h5>
                                    <p class="small  mb-3"><?= htmlspecialchars($m['deskripsi']); ?></p>
                                    <div class="text-warning fw-bold">Rp <?= number_format($m['harga'], 0, ',', '.'); ?></div>
                                </div>
                            </div>
                        </div>
                        <?php endwhile; ?>
                    </div>
                </div>

                <div class="tab-pane fade" id="menu-makanan" role="tabpanel">
                    <div class="row g-4">
                        <?php 
                        $q_makan = mysqli_query($conn, "SELECT * FROM menu WHERE kategori='makanan' ORDER BY id_menu DESC");
                        if(mysqli_num_rows($q_makan) == 0) echo "<p class=' text-center w-100'>Menu makanan belum tersedia.</p>";
                        while($m = mysqli_fetch_assoc($q_makan)):
                        ?>
                        <div class="col-md-3">
                            <div class="card-jo h-100">
                                <img src="assets/img/menu/<?= $m['gambar']; ?>" class="card-jo-img" alt="<?= htmlspecialchars($m['nama_item']); ?>">
                                <div class="p-3">
                                    <h5 class="fw-bold mb-1"><?= htmlspecialchars($m['nama_item']); ?></h5>
                                    <p class="small  mb-3"><?= htmlspecialchars($m['deskripsi']); ?></p>
                                    <div class="text-warning fw-bold">Rp <?= number_format($m['harga'], 0, ',', '.'); ?></div>
                                </div>
                            </div>
                        </div>
                        <?php endwhile; ?>
                    </div>
                </div>

                <div class="tab-pane fade" id="menu-minuman" role="tabpanel">
                    <div class="row g-4">
                        <?php 
                        $q_minum = mysqli_query($conn, "SELECT * FROM menu WHERE kategori='minuman' ORDER BY id_menu DESC");
                        if(mysqli_num_rows($q_minum) == 0) echo "<p class=' text-center w-100'>Menu minuman belum tersedia.</p>";
                        while($m = mysqli_fetch_assoc($q_minum)):
                        ?>
                        <div class="col-md-3">
                            <div class="card-jo h-100">
                                <img src="assets/img/menu/<?= $m['gambar']; ?>" class="card-jo-img" alt="<?= htmlspecialchars($m['nama_item']); ?>">
                                <div class="p-3">
                                    <h5 class="fw-bold mb-1"><?= htmlspecialchars($m['nama_item']); ?></h5>
                                    <p class="small text-muted mb-3"><?= htmlspecialchars($m['deskripsi']); ?></p>
                                    <div class="text-warning fw-bold">Rp <?= number_format($m['harga'], 0, ',', '.'); ?></div>
                                </div>
                            </div>
                        </div>
                        <?php endwhile; ?>
                    </div>
                </div>
            </div>
        </div>
    </section>


    <section class="section-padding text-center">
        <div class="container">
            <h2 class="title-cursive">Apa Kata Mereka</h2>
            <p class="text-muted mb-5" style="color: var(--jo-orange) !important;">Pengalaman pelanggan di Jo Cafe</p>
            
            <div class="row justify-content-center g-4">
                <?php 
                $query_testi = mysqli_query($conn, "SELECT * FROM testimoni WHERE status = 'tampilkan' ORDER BY id_testimoni DESC LIMIT 3");
                if(mysqli_num_rows($query_testi) > 0) {
                    while($row = mysqli_fetch_assoc($query_testi)) {
                        $bintang = str_repeat("⭐", $row['rating']);
                ?>
                <div class="col-md-4">
                    <div class="testi-home-card text-start">
                        <div class="mb-3"><?= $bintang ?></div>
                        <p class="small text-light fst-italic" style="line-height: 1.8;">"<?= htmlspecialchars($row['pesan']) ?>"</p>
                        <h6 class="text-warning mb-0 mt-4">- <?= htmlspecialchars($row['nama']) ?></h6>
                        <small class="text-muted" style="font-size: 0.75rem;"><?= date('d M Y', strtotime($row['tanggal'])) ?></small>
                    </div>
                </div>
                <?php 
                    }
                } else {
                    echo "<p class='text-muted'>Belum ada testimoni terbaru.</p>";
                }
                ?>
            </div>
            <div class="mt-5"><a href="testimoni.php" class="btn btn-outline-jo">Tulis Testimoni Anda</a></div>
        </div>
    </section>

<footer class="footer-custom">
    <div class="container">
        <div class="row g-4">
            
            <div class="col-lg-3 col-md-6">
                <div class="d-flex align-items-center mb-3">
                    <i class="bi bi-cup-hot text-warning-custom fs-3 me-2"></i>
                    <h5 class="mb-0 fw-bold tracking-wide text-white">Jo Cafe</h5>
                </div>
                <p class="small  mb-4" style="line-height: 1.7; text-align: justify;">
                    Tempat bersantai terbaik untuk menikmati kopi berkualitas tinggi, hidangan lezat, dan suasana yang hangat. Menemani setiap momen berhargamu sejak 2026.
                </p>
                <div class="social-icons">
                    <a href="#" title="Facebook"><i class="bi bi-facebook"></i></a>
                    <a href="#" title="Instagram"><i class="bi bi-instagram"></i></a>
                    <a href="#" title="TikTok"><i class="bi bi-tiktok"></i></a>
                    <a href="#" title="Twitter / X"><i class="bi bi-twitter-x"></i></a>
                </div>
            </div>

            <div class="col-lg-3 col-md-6">
                <h6 class="text-warning-custom mb-3">Akses & Reservasi</h6>
                <div class="row g-2 footer-links">
                    <div class="col-6">
                        <span class="text-white d-block small fw-bold mb-2">Link Cepat</span>
                        <ul class="list-unstyled small" style="line-height: 2.2;">
                            <li><a href="#" class="text-decoration-none">Home</a></li>
                            <li><a href="#welcome" class="text-decoration-none">Tentang</a></li>
                            <li><a href="#" class="text-decoration-none">Kontak</a></li>
                            <li><a href="auth/login.php" class="text-decoration-none">Dashboard</a></li>
                        </ul>
                    </div>
                    <div class="col-6">
                        <span class="text-white d-block small fw-bold mb-2">Reservasi Room</span>
                        <ul class="list-unstyled small" style="line-height: 2.2;">
                            <li><a href="page_reservasi.php?room=1" class="text-decoration-none">Room 1</a></li>
                            <li><a href="page_reservasi.php?room=2" class="text-decoration-none">Room 2</a></li>
                            <li><a href="page_reservasi.php?room=3" class="text-decoration-none">Room 3</a></li>
                            <li><a href="page_reservasi.php?room=4" class="text-decoration-none">Room 4</a></li>
                            <li><a href="page_reservasi.php?room=5" class="text-decoration-none">Room 5</a></li>
                            <li><a href="page_reservasi.php?room=6" class="text-decoration-none">Room 6</a></li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6">
                <h6 class="text-warning-custom mb-3">Hubungi Kami</h6>
                <ul class="list-unstyled contact-list ">
                    <li>
                        <i class="bi bi-geo-alt"></i>
                        <span>Jl. Merdeka No. 123, Kampus Tegalboto, Jember, Jawa Timur</span>
                    </li>
                    <li>
                        <i class="bi bi-telephone"></i>
                        <span>+62 812-3456-7890</span>
                    </li>
                    <li>
                        <i class="bi bi-envelope"></i>
                        <span>info@jocafe.com</span>
                    </li>
                    <li>
                        <i class="bi bi-clock"></i>
                        <span>Senin - Minggu<br><strong class="text-white">08:00 - 22:00 WIB</strong></span>
                    </li>
                </ul>
            </div>

            <div class="col-lg-3 col-md-6">
                <h6 class="text-warning-custom mb-3">Lokasi Kami</h6>
                <div class="map-container">
                        <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d987.3393057709314!2d113.70578904560988!3d-8.166718775186798!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2dd695162a241cc7%3A0x41f5d473ec70c910!2sJO%20CAFE%20AUTHENTIC%20COFFEE%20BAR%20%26%20KITCHEN!5e0!3m2!1sid!2sid!4v1778998704830!5m2!1sid!2sid" width="600" height="220" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                </div>
            </div>
            
        </div>

        <div class="text-center mt-5 pt-3 border-top-custom small ">
            © 2026 <span class="text-white fw-semibold">Jo Cafe</span>. Seluruh Hak Cipta Dilindungi.
        </div>
    </div>
</footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    let prevScrollpos = window.pageYOffset;
    window.onscroll = function() {
        let currentScrollPos = window.pageYOffset;
        if (prevScrollpos > currentScrollPos) {
            document.getElementById("joNavbar").style.top = "0";
        } else {
            document.getElementById("joNavbar").style.top = "-100px"; 
        }   
        prevScrollpos = currentScrollPos;
    }
    </script>
</body>
</html>