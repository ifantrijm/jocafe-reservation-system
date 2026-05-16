<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Selamat Datang | Jo Cafe</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;600;700;800&display=swap" rel="stylesheet">

    <style>
        /* === TEMA DARK MODE & GOLD AKSEN (Sesuai Desain Halaman Sebelumnya) === */
        :root {
            --bg-main: #13171c; /* Latar belakang body gelap */
            --bg-card: #1c2128; /* Latar belakang kontainer kartu gelap */
            --text-main: #ffffff;
            --accent-gold: #f89d13; /* Warna emas/kuning aksen */
            --accent-gold-hover: #e08c0f;
            --border-dark: rgba(255, 255, 255, 0.1);
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: var(--bg-main);
            color: var(--text-main);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            margin: 0;
            overflow: hidden; /* Mencegah scrollbar saat MVP */
        }

        /* --- STYLING JUDUL --- */
        .main-title-container {
            text-align: center;
            margin-bottom: 2rem;
        }
        .main-title-1 {
            font-weight: 800;
            font-size: 2.8rem;
            margin-bottom: 0.2rem;
            letter-spacing: 2px;
            color: var(--text-main);
        }
        .main-title-2 {
            font-weight: 400;
            font-size: 1.8rem;
            color: var(--accent-gold);
            letter-spacing: 1px;
        }

        /* --- STYLING KARTU TENGAH --- */
        .card-container {
            background-color: var(--bg-card);
            border: 1px solid var(--border-dark);
            border-radius: 20px;
            padding: 3rem 2rem;
            box-shadow: 0 15px 35px rgba(0,0,0,0.6);
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 1.5rem;
            width: 90%;
            max-width: 500px;
        }

        /* --- STYLING LOGO PLACEHOLDER (Karena logo kustom tidak ada) --- */
        .logo-placeholder {
            width: 100px;
            height: 100px;
            background-color: var(--accent-gold);
            border-radius: 50%;
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 2.5rem;
            font-weight: 800;
            color: var(--bg-card);
            margin-bottom: 1rem;
            box-shadow: 0 0 20px rgba(248, 157, 19, 0.4);
        }

        /* --- STYLING TOMBOL BESAR --- */
        .custom-btn {
            background-color: transparent;
            color: var(--accent-gold);
            border: 2px solid var(--accent-gold);
            border-radius: 12px;
            padding: 1rem 2rem;
            font-size: 1.25rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none; /* Mencegah garis bawah tautan */
            display: block; /* Agar lebar tombol penuh jika butuh */
            width: 100%; /* Lebar tombol penuh */
            text-align: center;
        }

        .custom-btn:hover {
            background-color: var(--accent-gold);
            color: var(--bg-card);
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(248, 157, 19, 0.5);
        }

        /* Penyesuaian tombol MENU agar lebih kecil (seperti di desain) */
        .menu-btn {
            width: auto;
            padding: 0.75rem 1.5rem;
            font-size: 1rem;
            margin-top: 1rem;
            border-color: var(--text-muted);
            color: var(--text-muted);
        }
        .menu-btn:hover {
            background-color: var(--text-muted);
            color: var(--bg-card);
            border-color: var(--text-muted);
            box-shadow: 0 5px 10px rgba(160, 170, 181, 0.3);
        }
    </style>
</head>
<body>

    <div class="main-title-container">
        <h1 class="main-title-1">SELAMAT DATANG</h1>
        <h2 class="main-title-2">DI JO CAFE</h2>

        <div class="card-container mx-auto">
            <div class="logo-placeholder">
                <i class="fas fa-receipt me-1 fs-3"></i> JO
            </div>
            
            <a href="reservasi/detail.php" class="custom-btn">RESERVASI ROOM</a>
            <a href="reservasi/event.php" class="custom-btn">RESERVASI EVENT</a>
            
            <!-- <a href="menu.html" class="custom-btn menu-btn">MENU</a> -->
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>