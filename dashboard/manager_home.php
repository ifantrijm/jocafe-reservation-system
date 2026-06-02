<?php
// Gak perlu session_start lagi karena udah di-handle sama file induk
require "../config/koneksi.php";

// Query Data Overview
$query_menu = mysqli_query($conn, "SELECT * FROM menu"); $jml_menu = $query_menu ? mysqli_num_rows($query_menu) : 0;
$query_galeri = mysqli_query($conn, "SELECT * FROM gallery"); $jml_galeri = $query_galeri ? mysqli_num_rows($query_galeri) : 0;
$query_blog = mysqli_query($conn, "SELECT * FROM blog"); $jml_blog = $query_blog ? mysqli_num_rows($query_blog) : 0;
$query_testimoni = mysqli_query($conn, "SELECT * FROM testimoni"); $jml_testimoni = $query_testimoni ? mysqli_num_rows($query_testimoni) : 0;
$query_room = mysqli_query($conn, "SELECT * FROM reservasi_room"); $jml_room = $query_room ? mysqli_num_rows($query_room) : 0;

// Query Grafik Area Reservasi (Top 3)
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
$js_label_area = json_encode($label_area);
$js_data_area = json_encode($data_area);
?>

<style>
    .chart-card {
        background-color: #1c2128;
        border: 1px solid rgba(255,255,255,0.1);
        border-radius: 12px;
        padding: 35px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.5);
        margin-bottom: 30px;
    }
</style>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<div class="container-fluid" style="padding: 40px;">
    <div class="mb-5">
        <h2 class="fw-bold m-0 text-white">Dashboard <span style="color: #f89b1c;">Overview</span></h2>
        <div class="text-muted mt-1">Laporan Ringkasan Sistem Jo Cafe</div>
    </div>

    <div class="chart-card">
        <h5 style="font-weight: 600; margin-bottom: 25px; color: #fff;">Grafik Ringkasan Data Master</h5>
        <div style="height: 350px; position: relative;">
            <canvas id="overviewChart"></canvas>
        </div>
    </div>

    <div class="chart-card mt-5">
        <h5 style="font-weight: 600; margin-bottom: 25px; color: #fff;">Grafik Reservasi per Area (Top 3)</h5>
        <div style="height: 350px; position: relative;">
            <canvas id="reservasiChart"></canvas>
        </div>
    </div>
</div>

<script>
    // Konfigurasi Chart Overview
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
                data: [<?php echo $jml_menu; ?>, <?php echo $jml_galeri; ?>, <?php echo $jml_blog; ?>, <?php echo $jml_testimoni; ?>, <?php echo $jml_room; ?>],
                backgroundColor: gradientOverview, borderColor: '#f89b1c', borderWidth: 2, borderRadius: 6, barPercentage: 0.5 
            }]
        },
        options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { display: false } }, scales: { y: { beginAtZero: true, grid: { color: '#1f2937' }, ticks: { color: '#8b95a5', stepSize: 1 } }, x: { grid: { display: false }, ticks: { color: '#ffffff' } } } }
    });

    // Konfigurasi Chart Area
    const ctxReservasi = document.getElementById('reservasiChart').getContext('2d');
    let gradientArea = ctxReservasi.createLinearGradient(0, 0, 0, 400);
    gradientArea.addColorStop(0, 'rgba(46, 204, 113, 0.8)'); 
    gradientArea.addColorStop(1, 'rgba(46, 204, 113, 0.1)'); 

    new Chart(ctxReservasi, {
        type: 'bar', 
        data: {
            labels: <?php echo $js_label_area; ?>,
            datasets: [{
                label: 'Jumlah Reservasi',
                data: <?php echo $js_data_area; ?>,
                backgroundColor: gradientArea, borderColor: '#2ecc71', borderWidth: 2, borderRadius: 6, barPercentage: 0.5 
            }]
        },
        options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { display: false } }, scales: { y: { beginAtZero: true, grid: { color: '#1f2937' }, ticks: { color: '#8b95a5', stepSize: 1 } }, x: { grid: { display: false }, ticks: { color: '#ffffff' } } } }
    });
</script>