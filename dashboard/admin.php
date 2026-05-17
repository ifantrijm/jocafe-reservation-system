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
            font-family: 'Plus Jakarta Sans', sans-serif !important;
            background-color: var(--bg-main);
            color: var(--text-main);
            margin: 0;
            display: flex;
            /* overflow: hidden; DIHAPUS BIAR BISA SCROLL */
        }

        .sidebar {
            width: 250px;
            height: 100vh;
            background-color: var(--bg-card);
            border-right: 1px solid var(--border-dark);
            padding: 20px;
            position: fixed;
        }

        /* BAGIAN INI YANG DIBENERIN BIAR BISA SCROLL */
        .main-content {
            margin-left: 250px;
            width: calc(100% - 250px);
            height: 100vh; 
            padding: 0;
            overflow-y: auto; /* INI KUNCINYA */
            overflow-x: hidden;
            padding-bottom: 50px; /* Jarak aman di bawah */
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
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;800&family=Great+Vibes&display=swap" rel="stylesheet">

</head>
<body>

<div class="sidebar">
    <h4 class="fw-bold mb-4" style="color: var(--accent-gold);">JO CAFE <span class="text-white">ADMIN</span></h4>
    
    <div class="d-flex align-items-center mb-4 pb-3" style="border-bottom: 1px solid var(--border-dark);">
        <div class="rounded-circle d-flex justify-content-center align-items-center me-3" style="width: 40px; height: 40px; background-color: rgba(248, 157, 19, 0.2); color: var(--accent-gold); font-size: 1.2rem;">
            <i class="fas fa-user-tie"></i>
        </div>
        <div style="line-height: 1.2;">
            <div class="fw-bold text-white text-capitalize" style="font-size: 0.95rem;">
                <?php echo isset($_SESSION['username']) ? htmlspecialchars($_SESSION['username']) : 'Admin'; ?>
            </div>
            <small class="title-cursive" style="font-size: 0.75rem; color: #aab3be;">Administrator</small>
        </div>
    </div>
    <a href="admin.php?page=home" class="nav-link-custom"><i class="fas fa-home me-2"></i> Dashboard</a>
    
    <a href="admin.php?page=menu" class="nav-link-custom"><i class="fas fa-utensils me-2"></i> Kelola Menu</a>
    
    <a href="admin.php?page=galeri" class="nav-link-custom"><i class="fas fa-images me-2"></i> Gallery</a>
    
    <a href="admin.php?page=blog" class="nav-link-custom"><i class="fas fa-newspaper me-2"></i> Blog</a>

    <a href="admin.php?page=room" class="nav-link-custom"><i class="fas fa-door-open me-2"></i> Room</a>

    <a href="admin.php?page=TampilanTestimoni" class="nav-link-custom"><i class="fas fa-comments me-2"></i> Tampilan Testimoni</a>
    
    <hr class="my-4" style="border-color: var(--border-dark);">
    
    <a href="../auth/logout.php" class="nav-link-custom text-danger"><i class="fas fa-sign-out-alt me-2"></i> Logout</a>
</div>

    <div class="main-content">
        <?php 
    // 1. Menangkap parameter halaman dari URL. Jika baru login (kosong), arahkan ke 'home'
    $halaman = isset($_GET['page']) ? $_GET['page'] : 'home';

    // 2. Memanggil file yang sesuai dengan menu yang diklik
    switch ($halaman) {
        case 'home':
            include "../dashboard/home.php"; 
            break;
        case 'menu':
            include "../menu.php";
            break;
        case 'galeri':
            include "../form_galeri.php";
            break;
        case 'blog':
            include "../blog.php";
            break;
        case 'room':
            include "../room.php";
            break;
        case 'TampilanTestimoni':
            include "../TampilanTestimoni.php";
            break;
        default:
            echo "<div class='container mt-5'>
                    <h3 class='text-white'>Halaman tidak ditemukan! (Error 404)</h3>
                    <p class='text-muted'>Silakan periksa kembali tautan atau nama file.</p>
                  </div>";
            break;
    }
    ?>
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