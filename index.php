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
        .footer-custom { border-top: 2px solid #1f2937; padding: 40px 0 20px; margin-top: 50px; }
    </style>
</head>
<body>

    <nav id="joNavbar" class="navbar navbar-expand-lg navbar-custom fixed-top">
        <div class="container">
            <a class="navbar-brand" href="home.php">Jo Cafe<br><span style="font-size: 0.8rem; color: #fff; font-weight: 400;">Authentic Coffee, Bar & Kitchen</span></a>
            <div class="collapse navbar-collapse justify-content-end">
                <ul class="navbar-nav align-items-center">
                    <li class="nav-item"><a class="nav-link" href="home.php">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="#welcome">Tentang Kami</a></li>
                    <li class="nav-item"><a class="nav-link" href="#galeri">Galeri</a></li>
                    <li class="nav-item"><a class="nav-link" href="#blog">Blog</a></li>
                    <li class="nav-item"><a class="nav-link" href="#menu">Menu</a></li>
                    <li class="nav-item"><a class="nav-link" href="page_reservasi.php">Reservasi Room</a></li>
                    <li class="nav-item"><a class="nav-link" href="page_reservasi.php">Reservasi Event</a></li>
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
            <div class="row g-3 mb-4 justify-content-center">
                <div class="col-md-5">
                    <img src="<?= get_gambar($conn, $img_event_banner1, 'https://images.unsplash.com/photo-1511795409834-ef04bbd61622?auto=format&fit=crop&w=600&q=80') ?>" class="img-fluid rounded" style="height: 200px; object-fit: cover; width: 100%;">
                </div>
                <div class="col-md-5">
                    <img src="<?= get_gambar($conn, $img_event_banner2, 'https://images.unsplash.com/photo-1519741497674-611481863552?auto=format&fit=crop&w=600&q=80') ?>" class="img-fluid rounded" style="height: 200px; object-fit: cover; width: 100%;">
                </div>
            </div>
            <a href="page_reservasi.php" class="btn btn-jo mb-5">Reservasi</a>
            <div class="row justify-content-center gap-4">
                <div class="col-md-3">
                    <div class="img-card"><img src="<?= get_gambar($conn, $img_event_card1, 'https://images.unsplash.com/photo-1583939003579-730e3918a45a?auto=format&fit=crop&w=400&q=80') ?>" alt="Prewedding"></div>
                    <div class="img-card-title">Prewedding</div>
                </div>
                <div class="col-md-3">
                    <div class="img-card"><img src="<?= get_gambar($conn, $img_event_card2, 'https://images.unsplash.com/photo-1523580494863-6f3031224c94?auto=format&fit=crop&w=400&q=80') ?>" alt="Yearbook"></div>
                    <div class="img-card-title">Yearbook</div>
                </div>
                <div class="col-md-3">
                    <div class="img-card"><img src="<?= get_gambar($conn, $img_event_card3, 'https://images.unsplash.com/photo-1525683248386-4554f9a130f1?auto=format&fit=crop&w=400&q=80') ?>" alt="Graduation"></div>
                    <div class="img-card-title">Graduation</div>
                </div>
            </div>
        </div>
    </section>

    <section class="section-padding text-center">
        <div class="container">
            <h2 class="title-cursive">Reservasi Tempat</h2>
            <div class="row g-3 mb-4 justify-content-center">
                <div class="col-md-5">
                    <img src="<?= get_gambar($conn, $img_tempat_banner1, 'https://images.unsplash.com/photo-1554118811-1e0d58224f24?auto=format&fit=crop&w=600&q=80') ?>" class="img-fluid rounded" style="height: 200px; object-fit: cover; width: 100%;">
                </div>
                <div class="col-md-5">
                    <img src="<?= get_gambar($conn, $img_tempat_banner2, 'https://images.unsplash.com/photo-1559339352-11d035aa65de?auto=format&fit=crop&w=600&q=80') ?>" class="img-fluid rounded" style="height: 200px; object-fit: cover; width: 100%;">
                </div>
            </div>
            <a href="page_reservasi.php" class="btn btn-jo mb-5">Reservasi</a>
            <div class="row justify-content-center gap-4">
                <div class="col-md-3">
                    <div class="img-card"><img src="<?= get_gambar($conn, $img_tempat_card1, 'https://images.unsplash.com/photo-1497366216548-37526070297c?auto=format&fit=crop&w=400&q=80') ?>" alt="Ruang 1"></div>
                    <div class="img-card-title">RUANG 1</div>
                </div>
                <div class="col-md-3">
                    <div class="img-card"><img src="<?= get_gambar($conn, $img_tempat_card2, 'https://images.unsplash.com/photo-1517248135467-4c7edcad34c4?auto=format&fit=crop&w=400&q=80') ?>" alt="Ruang 2"></div>
                    <div class="img-card-title">RUANG 2</div>
                </div>
                <div class="col-md-3">
                    <div class="img-card"><img src="<?= get_gambar($conn, $img_tempat_card3, 'https://images.unsplash.com/photo-1522771731478-44fb949b294e?auto=format&fit=crop&w=400&q=80') ?>" alt="Ruang 3"></div>
                    <div class="img-card-title">RUANG 3</div>
                </div>
            </div>
        </div>
    </section>

    <section class="section-padding text-center">
        <div class="container">
            <h2 class="title-cursive">Best Seller</h2>
            <div class="row justify-content-center mt-5 gap-4">
                <div class="col-md-3">
                    <div class="food-item">
                        <div class="food-title">Pasta Penne</div>
                        <img src="<?= get_gambar($conn, $img_bestseller_1, 'https://images.unsplash.com/photo-1551183053-bf91a1d81141?auto=format&fit=crop&w=300&q=80') ?>" alt="Pasta Penne">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="food-item">
                        <div class="food-title" style="font-size: 2.5rem; top: -30px;">Beef Bulgogi</div>
                        <img src="<?= get_gambar($conn, $img_bestseller_2, 'https://images.unsplash.com/photo-1544025162-831770e28151?auto=format&fit=crop&w=300&q=80') ?>" alt="Beef Bulgogi" style="width: 230px; height: 230px;">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="food-item">
                        <div class="food-title">Tteokbokki Cheese Sauce</div>
                        <img src="<?= get_gambar($conn, $img_bestseller_3, 'https://images.unsplash.com/photo-1582260655866-e0fbc4fb3158?auto=format&fit=crop&w=300&q=80') ?>" alt="Tteokbokki">
                    </div>
                </div>
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
                    echo "<p class='text-muted'>Belum ada foto galeri terbaru.</p>";
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
                            <p class="text-muted small mb-4"><?= substr(htmlspecialchars($b['isi']), 0, 100); ?>...</p>
                            <a href="blog_detail.php?id=<?= $b['id_blog']; ?>" class="text-warning text-decoration-none fw-bold small">Baca Selengkapnya →</a>
                        </div>
                    </div>
                </div>
                <?php 
                    }
                } else {
                    echo "<p class='text-muted text-center w-100'>Belum ada artikel terbit.</p>";
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
                                    <p class="small text-muted mb-3"><?= htmlspecialchars($m['deskripsi']); ?></p>
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
                        if(mysqli_num_rows($q_makan) == 0) echo "<p class='text-muted text-center w-100'>Menu makanan belum tersedia.</p>";
                        while($m = mysqli_fetch_assoc($q_makan)):
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

                <div class="tab-pane fade" id="menu-minuman" role="tabpanel">
                    <div class="row g-4">
                        <?php 
                        $q_minum = mysqli_query($conn, "SELECT * FROM menu WHERE kategori='minuman' ORDER BY id_menu DESC");
                        if(mysqli_num_rows($q_minum) == 0) echo "<p class='text-muted text-center w-100'>Menu minuman belum tersedia.</p>";
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
            <div class="row">
                <div class="col-md-3">
                    <h6 class="text-warning">Link Cepat</h6>
                    <ul class="list-unstyled small" style="line-height: 2;">
                        <li><a href="#" class="text-decoration-none text-light">Home</a></li>
                        <li><a href="#welcome" class="text-decoration-none text-light">Tentang</a></li>
                        <li><a href="#" class="text-decoration-none text-light">Kontak</a></li>
                        <li><a href="auth/login.php" class="text-decoration-none text-light">Dashboard</a></li>
                    </ul>
                </div>
                <div class="col-md-3">
                    <h6 class="text-warning">Reservation</h6>
                    <ul class="list-unstyled small" style="line-height: 2;">
                        <li><a href="page_reservasi.php" class="text-decoration-none text-light">Room 1</a></li>
                        <li><a href="page_reservasi.php" class="text-decoration-none text-light">Room 2</a></li>
                        <li><a href="page_reservasi.php" class="text-decoration-none text-light">Room 3</a></li>
                    </ul>
                </div>
                <div class="col-md-6 text-end">
                    <img src="https://via.placeholder.com/400x150/ffffff/000000/?text=Peta+Lokasi+Jo+Cafe" class="img-fluid rounded" alt="Maps">
                </div>
            </div>
            <div class="text-center mt-4 pt-3 border-top border-secondary small">
                &copy; 2026 Jo Cafe.
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