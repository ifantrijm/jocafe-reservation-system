<?php
// Pastikan session sudah aktif dari admin.php untuk menangkap id_admin
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 1. KONEKSI DATABASE
include_once "../config/koneksi.php";

$url_kembali = "admin.php?page=TampilanTestimoni";
$id_admin = $_SESSION['id_admin'] ?? 'NULL';

// HAPUS TESTIMONI
if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];
    mysqli_query($conn, "DELETE FROM testimoni WHERE id_testimoni = '$id'");
    
    // REDIRECT FIX
    echo "<script>window.location.href='$url_kembali';</script>";
    exit;
}

// TAMPILKAN TESTIMONI
if (isset($_GET['tampilkan'])) {
    $id = $_GET['tampilkan'];
    // Update status dan catat admin yang menyetujui
    mysqli_query($conn, "UPDATE testimoni SET id_admin = $id_admin, status = 'tampilkan' WHERE id_testimoni = '$id'");
    
    // REDIRECT FIX
    echo "<script>window.location.href='$url_kembali';</script>";
    exit;
}

// SEMBUNYIKAN TESTIMONI (Ubah ke 'pending' sesuai ENUM database)
if (isset($_GET['sembunyikan'])) {
    $id = $_GET['sembunyikan'];
    // Update status kembali ke pending dan catat admin yang menyembunyikan
    mysqli_query($conn, "UPDATE testimoni SET id_admin = $id_admin, status = 'pending' WHERE id_testimoni = '$id'");
    
    // REDIRECT FIX
    echo "<script>window.location.href='$url_kembali';</script>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Testimoni - Jo Cafe</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;800&family=Great+Vibes&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <style>
        :root {
            --bg-dark: #0a0e17;
            --card-dark: #111826;
            --card-hover: #1c2431;
            --jo-orange: #f89b1c;
            --jo-orange-hover: #e08915;
            --text-light: #ffffff;
        }

        body{
            background-color: var(--bg-dark);
            color: white;
            font-family: 'Poppins', sans-serif;
        }

        .title-cursive{
            font-family: 'Great Vibes', cursive;
            font-size: 3.5rem;
            color: white;
        }

        /* =========================
           WRAPPER
        ========================= */

        .testimoni-wrapper{
            background: var(--card-dark);
            border: 1px solid #1f2937;
            border-radius: 20px;
            padding: 30px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.3);
        }

        .testi-total{
            background: rgba(248,155,28,0.1);
            border: 1px solid rgba(248,155,28,0.3);
            color: var(--jo-orange);
            padding: 10px 18px;
            border-radius: 12px;
            font-weight: 600;
        }

        /* =========================
           TABLE
        ========================= */

        .custom-table{
            margin: 0;
            border-collapse: separate;
            border-spacing: 0 12px;
        }

        .custom-table thead th{
            border: none !important;
            color: var(--jo-orange) !important;
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 1px;
            padding-bottom: 15px;
        }

        .custom-table tbody tr{
            background: #1c2128;
            transition: 0.3s;
        }

        .custom-table tbody tr:hover{
            transform: translateY(-3px);
            background: var(--card-hover);
            box-shadow: 0 8px 20px rgba(248,155,28,0.08);
        }

        .custom-table td{
            border: none !important;
            padding: 18px 15px;
            vertical-align: middle;
        }

        .custom-table tbody tr td:first-child{
            border-top-left-radius: 12px;
            border-bottom-left-radius: 12px;
        }

        .custom-table tbody tr td:last-child{
            border-top-right-radius: 12px;
            border-bottom-right-radius: 12px;
        }

        /* =========================
           BADGE & STATUS
        ========================= */

        .rating-badge{
            background: rgba(248,155,28,0.15);
            color: var(--jo-orange);
            padding: 8px 14px;
            border-radius: 30px;
            font-size: 13px;
            font-weight: 600;
            display: inline-block;
        }

        .status-badge {
            padding: 6px 12px;
            border-radius: 6px;
            font-size: 12px;
            font-weight: bold;
        }
        .status-pending { background: rgba(241, 196, 15, 0.15); color: #f1c40f; border: 1px solid #f1c40f; }
        .status-tampilkan { background: rgba(46, 204, 113, 0.15); color: #2ecc71; border: 1px solid #2ecc71; }

        /* =========================
           BUTTON
        ========================= */

        .btn-action {
            padding: 8px 12px;
            border-radius: 8px;
            text-decoration: none;
            font-size: 12px;
            font-weight: 600;
            transition: 0.3s;
            margin: 0 3px;
            display: inline-block;
        }

        .btn-hapus{ background: #ff4d4d; color: white; border: none; }
        .btn-hapus:hover{ background: #ff2c2c; color: white; }

        .btn-tampilkan{ background: #2ecc71; color: white; border: none; }
        .btn-tampilkan:hover{ background: #27ae60; color: white; }

        .btn-sembunyikan{ background: #f39c12; color: white; border: none; }
        .btn-sembunyikan:hover{ background: #d68910; color: white; }

        /* =========================
           RESPONSIVE
        ========================= */

        @media(max-width:768px){
            .title-cursive{ font-size: 2.5rem; }
            .testimoni-wrapper{ padding: 20px; }
        }
    </style>
</head>

<body>

<div class="container py-5">
    <div class="testimoni-wrapper">

        <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
            <div>
                <h1 class="title-cursive mb-0">Customer Testimoni</h1>
                <p style="color:#aaa; margin:0;">Semua ulasan pelanggan Jo Cafe</p>
            </div>
            <div class="testi-total">
                <?php
                $count = mysqli_query($conn, "SELECT id_testimoni FROM testimoni");
                ?>
                Total Testimoni: <strong><?php echo mysqli_num_rows($count); ?></strong>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-dark custom-table align-middle">
                <thead>
                    <tr>
                        <th>Nama</th>
                        <th>Rating</th>
                        <th>Pesan</th>
                        <th class="text-center">Status</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                $query_testimoni = mysqli_query($conn, "SELECT * FROM testimoni ORDER BY id_testimoni DESC");
                while($row = mysqli_fetch_assoc($query_testimoni)) {
                    $status_class = ($row['status'] == 'tampilkan') ? 'status-tampilkan' : 'status-pending';
                ?>
                    <tr>
                        <td>
                            <div class="fw-bold text-white">
                                <?= htmlspecialchars($row['nama']); ?>
                            </div>
                        </td>

                        <td>
                            <span class="rating-badge">
                                ⭐ <?= $row['rating']; ?>/5
                            </span>
                        </td>

                        <td style="max-width:350px;">
                            <span style="color:#ccc;">
                                <?= htmlspecialchars($row['pesan']); ?>
                            </span>
                        </td>

                        <td class="text-center">
                            <span class="status-badge <?= $status_class; ?>">
                                <?= strtoupper($row['status']); ?>
                            </span>
                        </td>

                        <td class="text-center">
                            <a href="<?= $url_kembali; ?>&hapus=<?php echo $row['id_testimoni']; ?>"
                               class="btn-action btn-hapus"
                               onclick="return confirm('Hapus testimoni ini secara permanen?')">
                               <i class="fas fa-trash"></i>
                            </a>

                            <?php if($row['status'] == 'pending'): ?>
                                <a href="<?= $url_kembali; ?>&tampilkan=<?php echo $row['id_testimoni']; ?>"
                                   class="btn-action btn-tampilkan"
                                   onclick="return confirm('Tampilkan testimoni ini di halaman utama?')">
                                   <i class="fas fa-eye"></i> Tampilkan
                                </a>
                            <?php else: ?>
                                <a href="<?= $url_kembali; ?>&sembunyikan=<?php echo $row['id_testimoni']; ?>"
                                   class="btn-action btn-sembunyikan"
                                   onclick="return confirm('Sembunyikan testimoni ini dari halaman utama?')">
                                   <i class="fas fa-eye-slash"></i> Sembunyikan
                                </a>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>