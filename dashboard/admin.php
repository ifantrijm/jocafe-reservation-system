<?php
session_start();

// 1. SATPAM SESSION KHUSUS ADMIN
if (!isset($_SESSION['role_staff']) || $_SESSION['role_staff'] !== 'admin') {
    header("Location: ../auth/login.php");
    exit;
}

// 2. ANTI-CACHE
header("Cache-Control: no-cache, no-store, must-revalidate"); 
header("Pragma: no-cache"); 
header("Expires: 0"); 
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard | Jo Cafe</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;600;700;800&display=swap" rel="stylesheet">

    <style>
        :root {
            --bg-main: #13171c; 
            --bg-card: #1c2128; 
            --text-main: #ffffff;
            --accent-gold: #f89d13; 
            --border-dark: rgba(255, 255, 255, 0.1);
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: var(--bg-main);
            color: var(--text-main);
            margin: 0;
            display: flex;
            overflow: hidden; /* Biar body utama gak bisa di-scroll, scrollnya di dalem iframe aja */
        }

        .sidebar {
            width: 250px;
            height: 100vh;
            background-color: var(--bg-card);
            border-right: 1px solid var(--border-dark);
            padding: 20px;
            position: fixed;
        }

        /* Padding content diilangin biar iframenya full screen */
        .main-content {
            margin-left: 250px;
            width: calc(100% - 250px);
            height: 100vh; 
            padding: 0; 
        }

        /* Desain layarnya */
        iframe {
            width: 100%;
            height: 100%;
            border: none;
        }

        .nav-link-custom {
            color: var(--text-main);
            padding: 12px 15px;
            border-radius: 8px;
            display: block;
            text-decoration: none;
            margin-bottom: 10px;
            transition: 0.3s;
        }

        .nav-link-custom:hover, .nav-link-custom.active {
            background-color: var(--accent-gold);
            color: var(--bg-main);
            font-weight: 700;
        }
    </style>
</head>
<body>

<div class="sidebar">
    <h4 class="fw-bold mb-5" style="color: var(--accent-gold);">JO CAFE <span class="text-white">ADMIN</span></h4>
    
    <a href="/jocafe-reservation-system/dashboard/home.php" target="layar_konten" class="nav-link-custom active"><i class="fas fa-home me-2"></i> Dashboard</a>
    
    <a href="/jocafe-reservation-system/menu.php" target="layar_konten" class="nav-link-custom"><i class="fas fa-utensils me-2"></i> Kelola Menu</a>
    
    <a href="/jocafe-reservation-system/form_galeri.php" target="layar_konten" class="nav-link-custom"><i class="fas fa-images me-2"></i> Gallery</a>
    
    <a href="/jocafe-reservation-system/blog.php" target="layar_konten" class="nav-link-custom"><i class="fas fa-newspaper me-2"></i> Blog</a>

    <a href="/jocafe-reservation-system/testimoni.php" target="layar_konten" class="nav-link-custom"><i class="fas fa-comments me-2"></i> Testimoni</a>

    <a href="/jocafe-reservation-system/room.php" target="layar_konten" class="nav-link-custom"><i class="fas fa-door-open me-2"></i> Room</a>
    
    <hr class="my-4" style="border-color: var(--border-dark);">
    
    <a href="/jocafe-reservation-system/auth/logout.php" class="nav-link-custom text-danger"><i class="fas fa-sign-out-alt me-2"></i> Logout</a>
</div>

    <div class="main-content">
        <iframe name="layar_konten" src="home.php"></iframe>
    </div>

    <script>
        const links = document.querySelectorAll('.nav-link-custom');
        links.forEach(link => {
            link.addEventListener('click', function() {
                if(!this.classList.contains('text-danger')) {
                    links.forEach(l => l.classList.remove('active'));
                    this.classList.add('active');
                }
            });
        });
    </script>
</body>
</html>