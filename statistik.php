<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Statistik Reservasi - Jo Cafe</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <style>
        :root {
            /* Tema Warna Sama Persis dengan Halaman Room */
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

        /* HEADER SECTION */
        .header-title {
            font-weight: 800;
            font-size: 2.2rem;
            letter-spacing: -0.5px;
            margin-bottom: 0;
        }
        .header-subtitle {
            color: var(--text-muted);
            font-size: 0.95rem;
            font-weight: 400;
        }

        /* CARD CONTAINER */
        .chart-card {
            background-color: var(--bg-card);
            border: 1px solid var(--border-color);
            border-radius: 12px;
            padding: 35px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.5);
            margin-top: 30px;
        }

        .summary-card {
            background-color: var(--bg-card);
            border: 1px solid var(--border-color);
            border-radius: 12px;
            padding: 25px;
            text-align: center;
            height: 100%;
        }

        .summary-value {
            font-size: 2.5rem;
            font-weight: 800;
            color: var(--jo-orange);
            margin-bottom: 0;
            line-height: 1;
        }

        .summary-label {
            color: var(--text-muted);
            font-size: 0.9rem;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-top: 10px;
        }

        .btn-jo {
            background-color: var(--jo-orange);
            color: #ffffff;
            font-weight: 600;
            border: none;
            border-radius: 6px;
            padding: 10px 24px;
            font-size: 0.95rem;
            transition: all 0.2s;
            text-decoration: none;
        }
        .btn-jo:hover { background-color: var(--jo-orange-hover); color: #ffffff; }
    </style>
</head>
<body>

<div class="container" style="max-width: 1100px;">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="header-title">Statistik <span style="color: var(--jo-orange);">Reservasi</span></h1>
            <div class="header-subtitle mt-1">Laporan Frekuensi Pemesanan Area/Room Jo Cafe</div>
        </div>
        <a href="room.php" class="btn-jo">← Kembali ke Room</a>
    </div>

    <div class="row g-4 mb-2">
        <div class="col-md-4">
            <div class="summary-card">
                <div class="summary-value">225</div>
                <div class="summary-label">Total Reservasi</div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="summary-card">
                <div class="summary-value" style="color: #2ecc71;">Room 5</div>
                <div class="summary-label">Area Paling Laris</div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="summary-card">
                <div class="summary-value" style="color: #e74c3c;">Room 1</div>
                <div class="summary-label">Area Kurang Diminati</div>
            </div>
        </div>
    </div>

    <div class="chart-card">
        <h5 style="font-weight: 600; margin-bottom: 25px;">Grafik Reservasi per Area</h5>
        <div style="height: 400px; position: relative;">
            <canvas id="reservasiChart"></canvas>
        </div>
    </div>
</div>

<script>
    // 1. Data Dummy Sesuai Request
    const areaLabels = ['Room 1', 'Room 2', 'Room 3', 'Room 4', 'Room 5'];
    const totalReservasi = [20, 40, 50, 30, 85];

    // 2. Konfigurasi Chart
    const ctx = document.getElementById('reservasiChart').getContext('2d');
    
    // Membuat Gradient Warna Orange Jo Cafe untuk Bar Chart
    let gradientOrange = ctx.createLinearGradient(0, 0, 0, 400);
    gradientOrange.addColorStop(0, 'rgba(248, 155, 28, 0.9)'); // Orange Solid
    gradientOrange.addColorStop(1, 'rgba(248, 155, 28, 0.2)'); // Orange Transparan di bawah

    new Chart(ctx, {
        type: 'bar', // Tipe Diagram Batang
        data: {
            labels: areaLabels,
            datasets: [{
                label: 'Jumlah Reservasi',
                data: totalReservasi,
                backgroundColor: gradientOrange,
                borderColor: '#f89b1c',
                borderWidth: 2,
                borderRadius: 6, // Ujung batang membulat (modern)
                barPercentage: 0.6 // Lebar batang
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false // Menyembunyikan legend karena hanya 1 data
                },
                tooltip: {
                    backgroundColor: '#111826',
                    titleColor: '#f89b1c',
                    bodyColor: '#ffffff',
                    borderColor: '#1f2937',
                    borderWidth: 1,
                    padding: 15,
                    displayColors: false,
                    callbacks: {
                        label: function(context) {
                            return context.parsed.y + ' Kali Dipesan';
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: {
                        color: '#1f2937', // Garis grid gelap sesuai tema
                        drawBorder: false
                    },
                    ticks: {
                        color: '#8b95a5', // Warna angka Y axis
                        font: { family: 'Poppins' }
                    }
                },
                x: {
                    grid: {
                        display: false, // Menghilangkan garis vertikal agar clean
                        drawBorder: false
                    },
                    ticks: {
                        color: '#ffffff', // Warna teks X axis
                        font: { family: 'Poppins', weight: 500 }
                    }
                }
            }
        }
    });
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>