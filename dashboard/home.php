<?php
session_start();

// KONEKSI DATABASE
$conn = mysqli_connect("localhost", "root", "", "jocafee");
if (!$conn) { die("Koneksi Gagal: " . mysqli_connect_error()); }

// ==========================================
// --- FITUR TOMBOL SELESAI DITEKAN ---
// ==========================================
if (isset($_GET['selesai']) && isset($_GET['id_kamar'])) {
    $id_res = $_GET['selesai'];
    $id_kamar = $_GET['id_kamar'];

    // 1. Kosongkan meja biar bisa dipesan orang lain lagi
    mysqli_query($conn, "UPDATE room SET status = 'Tersedia' WHERE id_room = '$id_kamar'");

    // 2. Hapus data antrean dari tabel reservasi_room biar layar admin bersih
    mysqli_query($conn, "DELETE FROM reservasi_room WHERE id_reservasi_room = '$id_res'");

    // Refresh halaman biar tabel terupdate
    echo "<script>window.location='home.php';</script>";
    exit;
}
// ==========================================

// --- QUERY MENGHITUNG JUMLAH DATA ---
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
?>

<!DOCTYPE html>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Home Admin</title>
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
            padding: 40px; 
        }
        .stat-card {
            background-color: var(--bg-card);
            border: 1px solid var(--border-dark);
            border-radius: 15px;
            padding: 25px;
            text-align: center;
            transition: 0.3s;
            height: 100%; /* Biar tinggi kotaknya sejajar */
        }
        .stat-card:hover {
            border-color: var(--accent-gold);
            transform: translateY(-5px);
        }
        .stat-icon {
            font-size: 2rem;
            color: var(--accent-gold);
            margin-bottom: 10px;
        }
        
        /* CSS Balikin Tombol Action Lo */
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
    <h2 class="fw-bold mb-4">Dashboard Overview</h2>
    
    <div class="row g-4 mb-4">
        <div class="col-md-4">
            <div class="stat-card">
                <i class="fas fa-utensils stat-icon"></i>
                <h6 class="text-muted mt-2">Total Data Menu</h6>
                <h2 class="fw-bold"><?php echo $jml_menu; ?></h2>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-card">
                <i class="fas fa-images stat-icon"></i>
                <h6 class="text-muted mt-2">Total Foto Galeri</h6>
                <h2 class="fw-bold"><?php echo $jml_galeri; ?></h2>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-card">
                <i class="fas fa-newspaper stat-icon"></i>
                <h6 class="text-muted mt-2">Total Artikel Blog</h6>
                <h2 class="fw-bold"><?php echo $jml_blog; ?></h2>
            </div>
        </div>
    </div>

    <div class="row g-4 justify-content-center">
        <div class="col-md-4">
            <div class="stat-card">
                <i class="fas fa-star stat-icon"></i>
                <h6 class="text-muted mt-2">Testimoni</h6>
                <h2 class="fw-bold text-warning"><?php echo $jml_testimoni; ?></h2>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-card">
                <i class="fas fa-door-open stat-icon"></i>
                <h6 class="text-muted mt-2">Total Reservasi Room</h6>
                <h2 class="fw-bold text-info"><?php echo $jml_room; ?></h2>
            </div>
        </div>
    </div>

    <hr class="my-5" style="border-color: var(--border-dark);">

    <h4 class="fw-bold mb-3">Quick Actions (Presentasi)</h4>
    <div class="row g-4">
        <div class="col-md-6">
            <div class="stat-card text-start" style="height: auto;">
                <h5>Manajemen Reservasi</h5>
                <p class="small text-muted">Akses cepat ke form-form yang sudah dibuat.</p>
                <div class="row">
                    <div class="col-6">
                        <a href="reservasi_room.php" class="btn-action text-center"><i class="fas fa-door-open me-2"></i>Cek Room</a>
                    </div>
                    <div class="col-6">
                        <a href="reservasi_event.php" class="btn-action text-center"><i class="fas fa-star me-2"></i>Cek Event</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="stat-card text-start" style="height: auto;">
                <h5>Simulasi Pelanggan</h5>
                <p class="small text-muted">Mulai alur dari halaman depan pelanggan.</p>
                <a href="../index.php" target="_parent" class="btn-action text-center"><i class="fas fa-external-link-alt me-2"></i>Buka Halaman Utama</a>
            </div>
        </div>
    </div>

    <div class="stat-card mt-5 text-start" style="height: auto;">
        <h5 class="mb-4">Reservasi Masuk Terbaru</h5>
      <table class="table table-dark table-hover mb-0">
    <thead>
        <tr>
            <th>ID</th>
            <th>Nama</th>
            <th>Jenis</th>
            <th>Tanggal</th>
            <th>Status</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $sql_gabungan = "
            (SELECT id_reservasi_room AS id, id_pelanggan, tanggal_reservasi AS tgl, 'Room' AS jenis, bukti_pembayaran AS bukti, id_room AS item_id FROM reservasi_room)
            UNION
            (SELECT id_event_res AS id, id_pelanggan, tanggal_event AS tgl, 'Event' AS jenis, NULL AS bukti, 0 AS item_id FROM reservasi_event)
            ORDER BY tgl DESC LIMIT 5
        ";

        $query_terbaru = mysqli_query($conn, $sql_gabungan);

        if ($query_terbaru && mysqli_num_rows($query_terbaru) > 0) {
            while ($row = mysqli_fetch_assoc($query_terbaru)) {
                $badge_warna = ($row['jenis'] == 'Room') ? 'bg-warning text-dark' : 'bg-info text-dark';
                $status_txt = ($row['jenis'] == 'Room' && !empty($row['bukti'])) ? 'Confirmed' : 'Pending';
                $warna_status = ($status_txt == 'Confirmed') ? 'text-success' : 'text-warning';
        ?>
            <tr>
                <td>#<?php echo $row['id']; ?></td>
                <td>User ID: <?php echo $row['id_pelanggan']; ?></td>
                <td><span class="badge <?php echo $badge_warna; ?>"><?php echo $row['jenis']; ?></span></td>
                <td><?php echo $row['tgl']; ?></td>
                <td><span class="<?php echo $warna_status; ?>"><?php echo $status_txt; ?></span></td>
                <td>
                    <div class="d-flex justify-content-start">
                        
                        <?php if ($row['jenis'] == 'Room' && !empty($row['bukti'])) { ?>
                            <a href="../assets/img/bukti/<?php echo $row['bukti']; ?>" target="_blank" class="btn btn-sm btn-info fw-bold me-2 text-dark">
                                <i class="fas fa-receipt"></i> Cek Bukti
                            </a>
                        <?php } else { ?>
                            <button class="btn btn-sm btn-secondary fw-bold me-2" disabled title="Bayar di tempat / Belum bayar">
                                <i class="fas fa-receipt"></i> Cek Bukti
                            </button>
                        <?php } ?>
                        
                        <a href="home.php?selesai_<?php echo strtolower($row['jenis']); ?>=<?php echo $row['id']; ?>&id_kamar=<?php echo $row['item_id']; ?>" 
                           class="btn btn-sm btn-success fw-bold" onclick="return confirm('Selesaikan pesanan ini?')">
                           <i class="fas fa-check"></i> Selesai
                        </a>
                        
                    </div>
                </td>
            </tr>
        <?php 
            }
        } else {
            echo '<tr><td colspan="6" class="text-center py-4 text-muted">Belum ada reservasi masuk.</td></tr>';
        }
        ?>
    </tbody>
</table>
    </div>

</body>
</html>