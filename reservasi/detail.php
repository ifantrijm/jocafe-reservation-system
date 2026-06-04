<?php
require "../config/koneksi.php"; 

$query = mysqli_query($conn, "SELECT * FROM room");
$hari_ini = date('Y-m-d'); 

// Tentukan Jam Operasional Cafe
$jam_buka = strtotime("10:00");
$jam_tutup = strtotime("22:00");
$minimal_booking = 3600; // Minimal durasi booking adalah 1 jam (3600 detik)
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Denah Room - Jo Cafe</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { background-color: #0a0e17; color: white; font-family: 'Poppins', sans-serif; }
        .card-room { background: #111826; border: 1px solid #1f2937; border-radius: 15px; transition: 0.3s; }
        .card-room:hover { border-color: #f89b1c; transform: translateY(-5px); box-shadow: 0 10px 20px rgba(248, 155, 28, 0.15) !important; }
        .img-room { height: 180px; object-fit: cover; border-radius: 15px 15px 0 0; }
        
        .btn-booking { background-color: #f89b1c; color: #0a0e17; font-weight: 700; border: none; display: block; width: 100%; text-align: center; border-radius: 8px; transition: 0.3s; }
        .btn-booking:hover { background-color: #d68212; color: white; }
        
        .status-sepi { color: #2ecc71; border: 1px solid #2ecc71; background: rgba(46, 204, 113, 0.1); }
        .status-info { color: #f89b1c; border: 1px solid #f89b1c; background: rgba(248, 155, 28, 0.1); }
        .status-ramai { color: #e74c3c; border: 1px solid #e74c3c; background: rgba(231, 76, 60, 0.1); }
        .status-penuh { color: #7f8c8d; border: 1px solid #7f8c8d; background: rgba(127, 140, 141, 0.1); }
    </style>
</head>
<body>

<?php include "../include/navbararea.php" ?>

<div class="container py-5 mt-4">
    <div class="text-center mb-5">
        <h1 class="fw-bold">Pilih <span style="color: #f89b1c;">Area Meja</span></h1>
        <p class="text-secondary">Pilih meja yang Anda inginkan dan cek slot waktu yang tersedia.</p>
    </div>

    <div class="row g-4">
        <?php 
        while($row = mysqli_fetch_assoc($query)): 
            $id_room = $row['id_room'];
            
            // Ambil semua jadwal hari ini, urutkan dari yang paling pagi
            $q_jadwal = mysqli_query($conn, "SELECT jam_mulai, jam_selesai FROM reservasi_room WHERE id_room = '$id_room' AND tanggal_reservasi = '$hari_ini' AND status_pesanan != 'Selesai' ORDER BY jam_mulai ASC");
            
            $total_hari_ini = mysqli_num_rows($q_jadwal);
            
            // ALGORITMA DETEKSI CELAH KOSONG (GAP DETECTION)
            $ada_celah = false;
            $waktu_pengecekan_sekarang = $jam_buka;

            if ($total_hari_ini > 0) {
                while ($jadwal = mysqli_fetch_assoc($q_jadwal)) {
                    $mulai_booking = strtotime($jadwal['jam_mulai']);
                    $selesai_booking = strtotime($jadwal['jam_selesai']);

                    // Jika selisih antara waktu mulai booking dengan ujung waktu pengecekan ada jarak (minimal 1 jam)
                    if ($mulai_booking - $waktu_pengecekan_sekarang >= $minimal_booking) {
                        $ada_celah = true;
                        break; // Ketemu celah, langsung berhenti mengecek
                    }
                    
                    // Geser waktu pengecekan ke jam selesai booking ini
                    if ($selesai_booking > $waktu_pengecekan_sekarang) {
                        $waktu_pengecekan_sekarang = $selesai_booking;
                    }
                }
                
                // Cek celah terakhir: dari booking terakhir sampai jam tutup cafe
                if (!$ada_celah && ($jam_tutup - $waktu_pengecekan_sekarang >= $minimal_booking)) {
                    $ada_celah = true;
                }
            } else {
                // Kalau belum ada yang booking sama sekali, otomatis ada celah
                $ada_celah = true; 
            }

            // PENENTUAN TAMPILAN BERDASARKAN HASIL ALGORITMA
            if (!$ada_celah) {
                // JIKA CELAH HABIS DARI PAGI SAMPAI MALAM
                $badge_class = "status-penuh";
                $icon = "fa-ban";
                $teks = "Jadwal Full Hari Ini";
                $btn_html = '<button class="btn btn-secondary py-2 w-100 fw-bold" disabled>SUDAH PENUH</button>';
            } else {
                // JIKA MASIH ADA CELAH KOSONG
                $btn_html = '<a href="room.php?id_room='.$row['id_room'].'" class="btn btn-booking py-2">BOOKING SEKARANG</a>';
                
                if ($total_hari_ini >= 3) {
                    $badge_class = "status-ramai";
                    $icon = "fa-fire";
                    $teks = "$total_hari_ini Sesi Terbooking Hari Ini";
                } elseif ($total_hari_ini > 0) {
                    $badge_class = "status-info";
                    $icon = "fa-calendar-check";
                    $teks = "Tersisa Beberapa Slot Waktu";
                } else {
                    $badge_class = "status-sepi";
                    $icon = "fa-check-circle";
                    $teks = "Masih Kosong Hari Ini";
                }
            }
        ?>
        <div class="col-md-4">
            <div class="card card-room h-100 shadow">
                <img src="<?= (!empty($row['gambar']) && file_exists("../assets/img/room/" . $row['gambar'])) ? '../assets/img/room/' . $row['gambar'] : '../assets/img/default-room.jpg'; ?>" class="card-img-top img-room" alt="Room Image">
                <div class="card-body text-center">
                    <h4 class="fw-bold mb-2 text-white"><?= $row['nama_area']; ?></h4>
                    <p class="small text-secondary mb-3">Kapasitas: <?= $row['kapasitas']; ?> Orang</p>
                    
                    <div class="badge <?= $badge_class ?> py-2 px-3 mb-4 w-100" style="font-size: 0.85rem;">
                        <i class="fas <?= $icon ?> me-1"></i> <?= $teks ?>
                    </div>

                    <?= $btn_html ?>
                </div>
            </div>
        </div>
        <?php endwhile; ?>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>