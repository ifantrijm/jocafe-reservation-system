<?php
// Naik satu folder ke luar folder reservasi, baru masuk ke config
include "../config/koneksi.php"; 

// Ambil data dari tabel room
$query = mysqli_query($conn, "SELECT * FROM room");
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
        .card-room:hover { border-color: #f89b1c; transform: translateY(-5px); }
        .status-hijau { color: #2ecc71; border: 1px solid #2ecc71; background: rgba(46, 204, 113, 0.1); }
        .status-merah { color: #e74c3c; border: 1px solid #e74c3c; background: rgba(231, 76, 60, 0.1); }
        .img-room { height: 180px; object-fit: cover; border-radius: 15px 15px 0 0; }
        .btn-booking { background-color: #f89b1c; color: #0a0e17; font-weight: 700; border: none; text-decoration: none; display: block; width: 100%; text-align: center; border-radius: 8px; }
    </style>
</head>
<body>

<div class="container py-5">
    <div class="text-center mb-5">
        <h1 class="fw-bold">Pilih <span style="color: #f89b1c;">Area Meja</span></h1>
        <p class="text-secondary">Area Hijau = Tersedia | Area Merah = Penuh</p>
    </div>

    <div class="row g-4">
        <?php while($row = mysqli_fetch_assoc($query)): 
            $is_available = (strtolower($row['status']) == 'tersedia');
            $class_warna = $is_available ? 'status-hijau' : 'status-merah';
        ?>
        <div class="col-md-4">
            <div class="card card-room h-100 shadow">
                <!-- Path gambar harus keluar folder reservasi dulu -->
                <img src="../assets/img/room/<?= $row['gambar']; ?>" class="card-img-top img-room" alt="Room Image">
                <div class="card-body text-center">
                    <h4 class="fw-bold mb-2"><?= $row['nama_area']; ?></h4>
                    <p class="small text-secondary mb-3">Kapasitas: <?= $row['kapasitas']; ?> Orang</p>
                    <div class="badge <?= $class_warna; ?> py-2 px-3 mb-4 w-100">
                        <?= strtoupper($row['status']); ?>
                    </div>

                    <?php if ($is_available): ?>
                        <!-- Link ke room.php dalam satu folder yang sama -->
                        <a href="room.php?id_room=<?= $row['id_room']; ?>" class="btn btn-booking py-2">BOOKING SEKARANG</a>
                    <?php else: ?>
                        <button class="btn btn-outline-secondary w-100 py-2" disabled>SUDAH DIPESAN</button>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <?php endwhile; ?>
    </div>
</div>
</body>
</html>