<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail & Rating Reservasi - Jo Cafe</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        :root {
            --bg-main: #0a0e17;       
            --bg-card: #111826;       
            --jo-orange: #f89b1c;     
            --jo-orange-hover: #e08915;
            --text-main: #ffffff;
            --text-muted: #8b95a5;
            --border-color: #1f2937;  
        }

        body {
            background-color: var(--bg-main);
            color: var(--text-main);
            font-family: 'Poppins', sans-serif;
            padding: 60px 0;
            -webkit-font-smoothing: antialiased;
        }

        .header-title {
            font-weight: 800;
            font-size: 2.2rem;
            letter-spacing: -0.5px;
        }

        .header-subtitle {
            color: var(--text-muted);
            font-size: 0.95rem;
        }

        /* CARD REVIEW STYLE */
        .review-card {
            background-color: var(--bg-card);
            border: 1px solid var(--border-color);
            border-radius: 16px;
            padding: 30px;
            margin-bottom: 20px;
            transition: transform 0.3s ease, border-color 0.3s ease;
        }

        .review-card:hover {
            transform: translateY(-5px);
            border-color: var(--jo-orange);
        }

        .room-name {
            font-weight: 700;
            font-size: 1.25rem;
            margin-bottom: 5px;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        /* STATUS BADGE */
        .badge-status {
            padding: 4px 12px;
            border-radius: 4px;
            font-size: 0.75rem;
            font-weight: 600;
            letter-spacing: 0.5px;
            text-transform: uppercase;
        }
        .bg-available { background-color: rgba(39, 174, 96, 0.15); color: #2ecc71; border: 1px solid rgba(39, 174, 96, 0.3); }
        .bg-booked { background-color: rgba(231, 76, 60, 0.15); color: #e74c3c; border: 1px solid rgba(231, 76, 60, 0.3); }

        .star-rating {
            color: var(--jo-orange);
            font-size: 1.1rem;
            margin-bottom: 15px;
        }

        .star-number {
            color: var(--text-main);
            font-weight: 700;
            margin-left: 8px;
            font-size: 1.1rem;
        }

        .progress-custom {
            background-color: #1f2937;
            height: 8px;
            border-radius: 10px;
        }

        .progress-bar-custom {
            background-color: var(--jo-orange);
            border-radius: 10px;
        }

        .btn-jo {
            background-color: var(--jo-orange);
            color: #ffffff;
            font-weight: 600;
            border: none;
            border-radius: 6px;
            padding: 10px 24px;
            text-decoration: none;
            transition: 0.2s;
        }
        .btn-jo:hover { background-color: var(--jo-orange-hover); color: #ffffff; }

        .meta-info {
            font-size: 0.85rem;
            color: var(--text-muted);
            margin-top: 15px;
            display: flex;
            justify-content: space-between;
        }
    </style>
</head>
<body>

<div class="container" style="max-width: 900px;">
    <div class="d-flex justify-content-between align-items-center mb-5">
        <div>
            <h1 class="header-title">Detail <span style="color: var(--jo-orange);">Rating</span></h1>
            <div class="header-subtitle mt-1">Kepuasan Pelanggan & Ketersediaan Area Jo Cafe</div>
        </div>
        <a href="statistik.php" class="btn-jo">← Statistik</a>
    </div>

    <?php
    // Data Dummy: Room 2 & 4 status 'Dibooking', sisanya 'Tersedia'
    $rooms = [
        ['name' => 'Room 1', 'star' => 5.0, 'total_rev' => 120, 'desc' => 'Sangat Luar Biasa', 'status' => 'Tersedia'],
        ['name' => 'Room 2', 'star' => 4.8, 'total_rev' => 95, 'desc' => 'Sangat Memuaskan', 'status' => 'Dibooking'],
        ['name' => 'Room 3', 'star' => 4.7, 'total_rev' => 88, 'desc' => 'Nyaman & Estetik', 'status' => 'Tersedia'],
        ['name' => 'Room 4', 'star' => 4.8, 'total_rev' => 110, 'desc' => 'Pelayanan Cepat', 'status' => 'Dibooking'],
        ['name' => 'Room 5', 'star' => 5.0, 'total_rev' => 150, 'desc' => 'Favorit Pelanggan', 'status' => 'Tersedia']
    ];

    foreach ($rooms as $room) :
        // Logika warna status
        $status_class = ($room['status'] == 'Tersedia') ? 'bg-available' : 'bg-booked';
    ?>
        <div class="review-card shadow-lg">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="room-name">
                        <?= $room['name']; ?>
                        <span class="badge-status <?= $status_class; ?>">
                            <i class="fas fa-circle" style="font-size: 8px; margin-right: 4px; vertical-align: middle;"></i>
                            <?= $room['status']; ?>
                        </span>
                    </div>
                    <div class="star-rating">
                        <?php 
                        $full_stars = floor($room['star']);
                        $has_half = ($room['star'] - $full_stars) >= 0.5;
                        
                        for ($i = 1; $i <= 5; $i++) {
                            if ($i <= $full_stars) {
                                echo '<i class="fas fa-star"></i> ';
                            } elseif ($has_half) {
                                echo '<i class="fas fa-star-half-alt"></i> ';
                                $has_half = false;
                            } else {
                                echo '<i class="far fa-star"></i> ';
                            }
                        }
                        ?>
                        <span class="star-number"><?= number_format($room['star'], 1); ?></span>
                    </div>
                </div>
                <div class="text-end">
                    <span class="badge" style="background: rgba(248, 155, 28, 0.1); color: var(--jo-orange); border: 1px solid var(--jo-orange);">
                        Excellent Area
                    </span>
                </div>
            </div>
            
            <p style="color: var(--text-muted); font-size: 0.95rem; margin-bottom: 20px;">
                "<?= $room['desc']; ?>. Kebersihan dan fasilitas di area ini sangat terjaga sesuai standar Jo Cafe."
            </p>

            <div class="label-progress d-flex justify-content-between mb-2" style="font-size: 0.8rem; font-weight: 600;">
                <span>User Satisfaction Rate</span>
                <span><?= ($room['star'] / 5) * 100; ?>%</span>
            </div>
            <div class="progress progress-custom">
                <div class="progress-bar progress-bar-custom" style="width: <?= ($room['star'] / 5) * 100; ?>%"></div>
            </div>

            <div class="meta-info">
                <span><i class="far fa-comment-dots me-1"></i> <?= $room['total_rev']; ?> Reviews</span>
                <span><i class="far fa-calendar-alt me-1"></i> Terakhir diupdate hari ini</span>
            </div>
        </div>
    <?php endforeach; ?>

    <footer class="text-center mt-5 mb-4">
        <p style="color: var(--text-muted); font-size: 0.8rem;">
            © 2026 Jo Cafe Management System • Data Rating & Status Bersifat Real-time
        </p>
    </footer>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>