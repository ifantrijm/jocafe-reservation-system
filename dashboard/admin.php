<?php
session_start();

// 1. SATPAM SESSION KHUSUS ADMIN
if (!isset($_SESSION['role_staff']) || $_SESSION['role_staff'] !== 'admin') {
    header("Location: ../auth/login.php"); // Tendang ke folder auth
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
        }

        /* Sidebar Sederhana */
        .sidebar {
            width: 250px;
            height: 100vh;
            background-color: var(--bg-card);
            border-right: 1px solid var(--border-dark);
            padding: 20px;
            position: fixed;
        }

        .main-content {
            margin-left: 250px;
            padding: 40px;
            width: 100%;
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

        .stat-card {
            background-color: var(--bg-card);
            border: 1px solid var(--border-dark);
            border-radius: 15px;
            padding: 25px;
            text-align: center;
        }

        .btn-action {
            background-color: transparent;
            border: 1px solid var(--accent-gold);
            color: var(--accent-gold);
            padding: 10px 20px;
            border-radius: 8px;
            text-decoration: none;
            display: inline-block;
            transition: 0.3s;
            width: 100%;
            margin-bottom: 10px;
        }

        .btn-action:hover {
            background-color: var(--accent-gold);
            color: var(--bg-main);
        }
    </style>
</head>
<body>

    <div class="sidebar">
        <h4 class="fw-bold mb-5" style="color: var(--accent-gold);">JO CAFE <span class="text-white">ADMIN</span></h4>
        <a href="#" class="nav-link-custom active"><i class="fas fa-home me-2"></i> Dashboard</a>
        <a href="menu.html" class="nav-link-custom"><i class="fas fa-utensils me-2"></i> Kelola Menu</a>
        <a href="gallery.html" class="nav-link-custom"><i class="fas fa-images me-2"></i> Gallery</a>
        <a href="blog.html" class="nav-link-custom"><i class="fas fa-newspaper me-2"></i> Blog</a>
        <hr class="my-4" style="border-color: var(--border-dark);">
        <a href="../auth/logout.php" class="nav-link-custom text-danger"><i class="fas fa-sign-out-alt me-2"></i> Logout</a>
    </div>

    <div class="main-content">
        <h2 class="fw-bold mb-4">Dashboard Overview</h2>
        
        <div class="row g-4 mb-5">
            <div class="col-md-4">
                <div class="stat-card">
                    <h6 class="text-muted">Total Reservasi Room</h6>
                    <h2 class="fw-bold">12</h2>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stat-card">
                    <h6 class="text-muted">Total Reservasi Event</h6>
                    <h2 class="fw-bold">5</h2>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stat-card">
                    <h6 class="text-muted">Testimoni Baru</h6>
                    <h2 class="fw-bold text-warning">3</h2>
                </div>
            </div>
        </div>

        <h4 class="fw-bold mb-3">Quick Actions (Presentasi)</h4>
        <div class="row g-4">
            <div class="col-md-6">
                <div class="stat-card text-start">
                    <h5>Manajemen Reservasi</h5>
                    <p class="small text-muted">Akses cepat ke form-form yang sudah dibuat.</p>
                    <div class="row">
                        <div class="col-6">
                            <a href="reservasi_room.html" class="btn-action text-center"><i class="fas fa-door-open me-2"></i>Cek Room</a>
                        </div>
                        <div class="col-6">
                            <a href="reservasi_event.html" class="btn-action text-center"><i class="fas fa-star me-2"></i>Cek Event</a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="stat-card text-start">
                    <h5>Simulasi Pelanggan</h5>
                    <p class="small text-muted">Mulai alur dari halaman depan pelanggan.</p>
                    <a href="pilih_reservasi.html" class="btn-action text-center"><i class="fas fa-external-link-alt me-2"></i>Buka Halaman Selamat Datang</a>
                </div>
            </div>
        </div>

        <div class="stat-card mt-5 text-start">
            <h5 class="mb-4">Reservasi Masuk Terbaru</h5>
            <table class="table table-dark table-hover mb-0">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nama</th>
                        <th>Jenis</th>
                        <th>Tanggal</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>#001</td>
                        <td>Ifan</td>
                        <td><span class="badge bg-warning text-dark">Room</span></td>
                        <td>2026-04-28</td>
                        <td><span class="text-success">Confirmed</span></td>
                    </tr>
                    <tr>
                        <td>#002</td>
                        <td>Budi</td>
                        <td><span class="badge bg-info text-dark">Event</span></td>
                        <td>2026-05-01</td>
                        <td><span class="text-warning">Pending</span></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

</body>
</html>