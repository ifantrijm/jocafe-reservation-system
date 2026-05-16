<?php
session_start();

// 1. KONEKSI DATABASE
include_once "../config/koneksi.php";

// 2. SATPAM SESSION KHUSUS ADMIN
if (!isset($_SESSION['role_staff']) || $_SESSION['role_staff'] !== 'manager') {
    header("Location: ../auth/login.php"); 
    exit;
}

// 3. ANTI-CACHE
header("Cache-Control: no-cache, no-store, must-revalidate"); 
header("Pragma: no-cache"); 
header("Expires: 0"); 

// ==========================================
// --- QUERY MENGHITUNG JUMLAH DATA OVERVIEW ---
// ==========================================
$query_menu = mysqli_query($conn, "SELECT * FROM menu");
$jml_menu = $query_menu ? mysqli_num_rows($query_menu) : 0;

$query_galeri = mysqli_query($conn, "SELECT * FROM gallery"); 
$jml_galeri = $query_galeri ? mysqli_num_rows($query_galeri) : 0;

$query_blog = mysqli_query($conn, "SELECT * FROM blog");
$jml_blog = $query_blog ? mysqli_num_rows($query_blog) : 0;

$query_testimoni = mysqli_query($conn, "SELECT * FROM testimoni");
$jml_testimoni = $query_testimoni ? mysqli_num_rows($query_testimoni) : 0;

$query_room = mysqli_query($conn, "SELECT * FROM reservasi_room");
$jml_room = $query_room ? mysqli_num_rows($query_room) : 0;


// ==========================================
// --- QUERY UNTUK GRAFIK AREA RESERVASI ---
// ==========================================
$query_chart_area = mysqli_query($conn, "
    SELECT r.nama_area, COUNT(rr.id_reservasi_room) as total_reservasi 
    FROM room r 
    JOIN reservasi_room rr ON r.id_room = rr.id_room 
    GROUP BY r.id_room
    ORDER BY total_reservasi DESC 
    LIMIT 3
");

$label_area = [];
$data_area = [];

if ($query_chart_area) {
    while ($row = mysqli_fetch_assoc($query_chart_area)) {
        $label_area[] = $row['nama_area'];
        $data_area[] = $row['total_reservasi'];
    }
}

// Convert data PHP ke JSON biar bisa dibaca JavaScript
$js_label_area = json_encode($label_area);
$js_data_area = json_encode($data_area);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Statistik Reservasi - Jo Cafe</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <style>
        :root {
            --bg-main: #0a0e17;       
            --bg-card: #111826;       
            --jo-orange: #f89b1c;     
            --jo-orange-hover: #e08915;
            --border-color: #1f2937;  
        }

        body {
            background-color: var(--bg-main);
            color: #ffffff;
            font-family: 'Poppins', sans-serif;
            padding: 60px 0;
            -webkit-font-smoothing: antialiased;
        }

        .header-title { font-weight: 800; font-size: 2.2rem; letter-spacing: -0.5px; margin-bottom: 0; }
        .header-subtitle { color: #8b95a5; font-size: 0.95rem; font-weight: 400; }

        .chart-card {
            background-color: var(--bg-card);
            border: 1px solid var(--border-color);
            border-radius: 12px;
            padding: 35px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.5);
            margin-bottom: 30px;
        }

        .btn-jo { background-color: var(--jo-orange); color: #ffffff; font-weight: 600; border: none; border-radius: 6px; padding: 10px 24px; font-size: 0.95rem; transition: all 0.2s; text-decoration: none; }
        .btn-jo:hover { background-color: var(--jo-orange-hover); color: #ffffff; }

        .btn-logout { background-color: transparent; color: #ef4444; font-weight: 600; border: 1px solid #ef4444; border-radius: 6px; padding: 10px 24px; font-size: 0.95rem; transition: all 0.2s; text-decoration: none; }
        .btn-logout:hover { background-color: #ef4444; color: #ffffff; }
    </style>
</head>
<body>

<div class="container" style="max-width: 1100px;">
    <div class="d-flex justify-content-between align-items-center mb-5">
        <div>
            <h1 class="header-title">Dashboard <span style="color: var(--jo-orange);">Overview</span></h1>
            <div class="header-subtitle mt-1">Laporan Ringkasan Sistem Jo Cafe</div>
        </div>

        <div class="d-flex gap-3 align-items-center">
            <a href="../auth/logout.php" class="btn-logout">Logout</a>
        </div>
    </div>

    <div class="chart-card">
        <h5 style="font-weight: 600; margin-bottom: 25px; color: #fff;">Grafik Ringkasan Data Master</h5>
        <div style="height: 350px; position: relative;">
            <canvas id="overviewChart"></canvas>
        </div>
    </div>

    <div class="chart-card mt-5">
        <h5 style="font-weight: 600; margin-bottom: 25px; color: #fff;">Grafik Reservasi per Area</h5>
        <div style="height: 350px; position: relative;">
            <canvas id="reservasiChart"></canvas>
        </div>
    </div>
</div>

<script>
    // ==========================================
    // 1. SETUP CHART OVERVIEW (Dinamis dari Database)
    // ==========================================
    const ctxOverview = document.getElementById('overviewChart').getContext('2d');
    
    let gradientOverview = ctxOverview.createLinearGradient(0, 0, 0, 400);
    gradientOverview.addColorStop(0, 'rgba(248, 155, 28, 0.9)'); 
    gradientOverview.addColorStop(1, 'rgba(248, 155, 28, 0.1)'); 

    new Chart(ctxOverview, {
        type: 'bar',
        data: {
            labels: ['Data Menu', 'Foto Galeri', 'Artikel Blog', 'Testimoni', 'Reservasi Room'],
            datasets: [{
                label: 'Jumlah Total',
                data: [
                    <?php echo $jml_menu; ?>, 
                    <?php echo $jml_galeri; ?>, 
                    <?php echo $jml_blog; ?>, 
                    <?php echo $jml_testimoni; ?>, 
                    <?php echo $jml_room; ?>
                ],
                backgroundColor: gradientOverview,
                borderColor: '#f89b1c',
                borderWidth: 2,
                borderRadius: 6,
                barPercentage: 0.5 
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: { backgroundColor: '#111826', titleColor: '#f89b1c', bodyColor: '#ffffff', borderColor: '#1f2937', borderWidth: 1, padding: 15, displayColors: false }
            },
            scales: {
                y: { beginAtZero: true, grid: { color: '#1f2937', drawBorder: false }, ticks: { color: '#8b95a5', font: { family: 'Poppins' }, stepSize: 1 } },
                x: { grid: { display: false }, ticks: { color: '#ffffff', font: { family: 'Poppins', weight: 500 } } }
            }
        }
    });

    // ==========================================
    // 2. SETUP CHART AREA ROOM (Dinamis dari Database)
    // ==========================================
    // Ngambil variabel JSON dari PHP langsung
    const areaLabels = <?php echo $js_label_area; ?>;
    const totalReservasi = <?php echo $js_data_area; ?>;

    const ctxReservasi = document.getElementById('reservasiChart').getContext('2d');
    
    let gradientArea = ctxReservasi.createLinearGradient(0, 0, 0, 400);
    gradientArea.addColorStop(0, 'rgba(46, 204, 113, 0.8)'); // Hijau Jo Cafe
    gradientArea.addColorStop(1, 'rgba(46, 204, 113, 0.1)'); 

    new Chart(ctxReservasi, {
        type: 'bar', 
        data: {
            labels: areaLabels,
            datasets: [{
                label: 'Jumlah Reservasi',
                data: totalReservasi,
                backgroundColor: gradientArea,
                borderColor: '#2ecc71',
                borderWidth: 2,
                borderRadius: 6, 
                barPercentage: 0.5 
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: '#111826', titleColor: '#2ecc71', bodyColor: '#ffffff', borderColor: '#1f2937', borderWidth: 1, padding: 15, displayColors: false,
                    callbacks: { label: function(context) { return context.parsed.y + ' Kali Dipesan'; } }
                }
            },
            scales: {
                y: { beginAtZero: true, grid: { color: '#1f2937', drawBorder: false }, ticks: { color: '#8b95a5', font: { family: 'Poppins' }, stepSize: 1 } },
                x: { grid: { display: false }, ticks: { color: '#ffffff', font: { family: 'Poppins', weight: 500 } } }
            }
        }
    });
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>