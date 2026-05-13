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
            --bg-dark: #0a0e17; /* Sesuai tema sebelumnya */
            --jo-orange: #f89b1c;
            --jo-orange-hover: #e08915;
            --text-light: #ffffff;
        }

        html {
            scroll-behavior: smooth; /* Efek scroll halus */
        }

        body {
            background-color: var(--bg-dark);
            color: var(--text-light);
            font-family: 'Poppins', sans-serif;
            overflow-x: hidden;
        }

        /* Navbar */
        .navbar-custom {
            background-color: var(--bg-dark);
            padding: 15px 50px;
            border-bottom: 1px solid #1f2937;
            transition: top 0.4s ease-in-out; /* Animasi naik turun */
}
        .navbar-brand {
            color: var(--jo-orange);
            font-weight: 700;
            font-size: 1.5rem;
        }
        .nav-link {
            color: var(--text-light) !important;
            font-size: 0.9rem;
            margin-left: 15px;
            text-transform: uppercase;
        }
        .nav-link:hover {
            color: var(--jo-orange) !important;
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
        }
        .btn-jo:hover {
            background-color: var(--jo-orange-hover);
            border-color: var(--jo-orange-hover);
            color: white;
        }
        .btn-outline-jo {
            background-color: transparent;
            color: var(--jo-orange);
            font-weight: 600;
            border-radius: 25px;
            padding: 10px 30px;
            border: 2px solid var(--jo-orange);
            transition: 0.3s;
        }
        .btn-outline-jo:hover {
            background-color: var(--jo-orange);
            color: white;
        }

        /* Typography */
        .title-cursive {
            font-family: 'Great Vibes', cursive;
            color: var(--text-light);
            font-size: 3.5rem;
            margin-bottom: 20px;
        }
        
        .hero-title {
            font-size: 3.5rem;
            font-weight: 800;
            line-height: 1.2;
        }

        /* Logo Hero */
        .hero-logo-container {
            width: 350px;
            height: 350px;
            border: 2px solid white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto;
        }
        .hero-logo-container img {
            width: 90%;
            border-radius: 50%;
        }

        /* Sections */
        .section-padding {
            padding: 80px 0;
        }

        /* Image Cards */
        .img-card {
            border-radius: 15px;
            overflow: hidden;
            position: relative;
        }
        .img-card img {
            width: 100%;
            height: 250px;
            object-fit: cover;
            transition: transform 0.3s;
        }
        .img-card:hover img {
            transform: scale(1.05);
        }
        .img-card-title {
            text-align: center;
            margin-top: 10px;
            font-weight: 600;
        }

        /* Best Seller */
        .food-item {
            position: relative;
            text-align: center;
        }
        .food-item img {
            width: 200px;
            height: 200px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid var(--jo-orange);
        }
        .food-title {
            font-family: 'Great Vibes', cursive;
            color: var(--jo-orange);
            font-size: 2rem;
            position: absolute;
            top: -20px;
            left: 50%;
            transform: translateX(-50%);
            width: 100%;
        }

        /* Footer */
        .footer-custom {
            border-top: 1px solid #1f2937;
            padding: 40px 0 20px;
            margin-top: 50px;
        }
    </style>
</head>
<body>

    <nav id="joNavbar" class="navbar navbar-expand-lg navbar-custom fixed-top">
        <div class="container">
            <a class="navbar-brand" href="#">Jo Cafe<br><span style="font-size: 0.6rem; color: #fff; font-weight: 400;">Authentic Coffee, Bar & Kitchen</span></a>
            <div class="collapse navbar-collapse justify-content-end">
                <ul class="navbar-nav">
                    <li class="nav-item"><a class="nav-link" href="home.php">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="about.php">Tentang Kami</a></li>
                    <li class="nav-item"><a class="nav-link" href="room.php">Reservasi Room</a></li>
                    <li class="nav-item"><a class="nav-link" href="event.php">Reservasi Event</a></li>
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
                        <a href="index.php" class="btn btn-jo">Reservasi</a>
                    </div>
                </div>
                <div class="col-md-6 text-center">
                    <div class="hero-logo-container">
                        <img src="https://via.placeholder.com/350/000000/FFFFFF/?text=LOGO+JO+CAFE" alt="Logo Jo Cafe">
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Selamat datang di Jo Cafe -->
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
                    <img src="https://images.unsplash.com/photo-1511795409834-ef04bbd61622?auto=format&fit=crop&w=600&q=80" class="img-fluid rounded" style="height: 200px; object-fit: cover; width: 100%;">
                </div>
                <div class="col-md-5">
                    <img src="https://images.unsplash.com/photo-1519741497674-611481863552?auto=format&fit=crop&w=600&q=80" class="img-fluid rounded" style="height: 200px; object-fit: cover; width: 100%;">
                </div>
            </div>

            <a href="index.php" class="btn btn-jo mb-5">Reservasi</a>

            <div class="row justify-content-center gap-4">
                <div class="col-md-3">
                    <div class="img-card">
                        <img src="https://images.unsplash.com/photo-1583939003579-730e3918a45a?auto=format&fit=crop&w=400&q=80" alt="Prewedding">
                    </div>
                    <div class="img-card-title">Prewedding</div>
                </div>
                <div class="col-md-3">
                    <div class="img-card">
                        <img src="https://images.unsplash.com/photo-1523580494863-6f3031224c94?auto=format&fit=crop&w=400&q=80" alt="Yearbook">
                    </div>
                    <div class="img-card-title">Yearbook</div>
                </div>
                <div class="col-md-3">
                    <div class="img-card">
                        <img src="https://images.unsplash.com/photo-1525683248386-4554f9a130f1?auto=format&fit=crop&w=400&q=80" alt="Graduation">
                    </div>
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
                    <img src="https://images.unsplash.com/photo-1554118811-1e0d58224f24?auto=format&fit=crop&w=600&q=80" class="img-fluid rounded" style="height: 200px; object-fit: cover; width: 100%;">
                </div>
                <div class="col-md-5">
                    <img src="https://images.unsplash.com/photo-1559339352-11d035aa65de?auto=format&fit=crop&w=600&q=80" class="img-fluid rounded" style="height: 200px; object-fit: cover; width: 100%;">
                </div>
            </div>

            <a href="index.php" class="btn btn-jo mb-5">Reservasi</a>

            <div class="row justify-content-center gap-4">
                <div class="col-md-3">
                    <div class="img-card">
                        <img src="https://images.unsplash.com/photo-1497366216548-37526070297c?auto=format&fit=crop&w=400&q=80" alt="Ruang 1">
                    </div>
                    <div class="img-card-title">RUANG 1</div>
                </div>
                <div class="col-md-3">
                    <div class="img-card">
                        <img src="https://images.unsplash.com/photo-1517248135467-4c7edcad34c4?auto=format&fit=crop&w=400&q=80" alt="Ruang 2">
                    </div>
                    <div class="img-card-title">RUANG 2</div>
                </div>
                <div class="col-md-3">
                    <div class="img-card">
                        <img src="https://images.unsplash.com/photo-1522771731478-44fb949b294e?auto=format&fit=crop&w=400&q=80" alt="Ruang 3">
                    </div>
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
                        <img src="https://images.unsplash.com/photo-1551183053-bf91a1d81141?auto=format&fit=crop&w=300&q=80" alt="Pasta Penne">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="food-item">
                        <div class="food-title" style="font-size: 2.5rem; top: -30px;">Beef Bulgogi</div>
                        <img src="https://images.unsplash.com/photo-1544025162-831770e28151?auto=format&fit=crop&w=300&q=80" alt="Beef Bulgogi" style="width: 230px; height: 230px;">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="food-item">
                        <div class="food-title">Tteokbokki Cheese Sauce</div>
                        <img src="https://images.unsplash.com/photo-1582260655866-e0fbc4fb3158?auto=format&fit=crop&w=300&q=80" alt="Tteokbokki">
                    </div>
                </div>
            </div>
        </div>
    </section>

    <footer class="footer-custom">
        <div class="container">
            <div class="row">
                <div class="col-md-3">
                    <h6 class="text-warning">Link Cepat</h6>
                    <ul class="list-unstyled small" style="line-height: 2;">
                        <li><a href="#" class="text-decoration-none text-light">Home</a></li>
                        <li><a href="#" class="text-decoration-none text-light">Tentang</a></li>
                        <li><a href="#" class="text-decoration-none text-light">Kontak</a></li>
                        <li><a href="#" class="text-decoration-none text-light">Dashboard</a></li>
                    </ul>
                </div>
                <div class="col-md-3">
                    <h6 class="text-warning">Reservation</h6>
                    <ul class="list-unstyled small" style="line-height: 2;">
                        <li><a href="#" class="text-decoration-none text-light">Room 1</a></li>
                        <li><a href="#" class="text-decoration-none text-light">Room 2</a></li>
                        <li><a href="#" class="text-decoration-none text-light">Room 3</a></li>
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
    // Simpan posisi scroll terakhir
    let prevScrollpos = window.pageYOffset;

    window.onscroll = function() {
        let currentScrollPos = window.pageYOffset;

        if (prevScrollpos > currentScrollPos) {
            // Jika scroll ke ATAS, munculkan navbar (posisi top 0)
            document.getElementById("joNavbar").style.top = "0";
        } else {
            // Jika scroll ke BAWAH, sembunyikan navbar ke atas (minus tinggi navbar)
            document.getElementById("joNavbar").style.top = "-100px"; 
        }

        // Perbarui posisi scroll terakhir
        prevScrollpos = currentScrollPos;
    }
</script>
</body>
</html>