<nav class="navbar navbar-expand-lg fixed-top navbar-custom">
    <div class="container">
        <!-- Logo dengan nuansa Gold Tradisional -->
        <a class="navbar-brand d-flex flex-column" href="../index.php">
            <span class="brand-name">Jo Cafe</span>
            <span class="brand-tagline">Authentic Coffee, Bar & Kitchen</span>
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <i class="fas fa-bars text-white"></i>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto text-uppercase fw-bold">
                <li class="nav-item">
                    <a class="nav-link px-3" href="../index.php">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link px-3" href="../tentang.php">Tentang Kami</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link px-3" href="../reservasi/detail.php">Reservasi Room</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link px-3 btn-event-gold" href="../reservasi/event.php">Reservasi Event</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<style>
    /* Gabungan Modern (Glassmorphism) & Tradisional (Dark Wood/Gold) */
    .navbar-custom {
        background: rgba(10, 14, 23, 0.9); /* Gelap modern */
        backdrop-filter: blur(10px);
        border-bottom: 2px solid #f89d13; /* Aksen emas tradisional */
        padding: 15px 0;
        transition: 0.4s;
    }

    .brand-name {
        color: #f89d13; /* Warna Orange-Gold khas Jo Cafe */
        font-weight: 800;
        font-size: 1.8rem;
        line-height: 1;
        letter-spacing: -1px;
    }

    .brand-tagline {
        color: #ffffff;
        font-size: 0.7rem;
        font-weight: 400;
        letter-spacing: 1px;
        text-transform: uppercase;
    }

    .nav-link {
        color: #ffffff !important;
        font-size: 0.85rem;
        letter-spacing: 1px;
        position: relative;
        transition: 0.3s;
    }

    /* Efek Hover Tradisional - Garis Bawah Emas */
    .nav-link::after {
        content: '';
        position: absolute;
        width: 0;
        height: 2px;
        bottom: 5px;
        left: 15px;
        background-color: #f89d13;
        transition: 0.3s;
    }

    .nav-link:hover::after {
        width: 70%;
    }

    .nav-link:hover {
        color: #f89d13 !important;
    }

    /* Tombol Khusus Event dengan Border Emas */
    .btn-event-gold {
        border: 1px solid #f89d13;
        border-radius: 5px;
        margin-left: 10px;
        color: #f89d13 !important;
    }

    .btn-event-gold:hover {
        background: #f89d13;
        color: #0a0e17 !important;
    }

    /* Styling Toggler untuk Mobile */
    .navbar-toggler {
        border: none;
        outline: none !important;
    }
</style>