<?php
// KONEKSI DATABASE
$conn = mysqli_connect("localhost", "root", "", "jocafee");
if (!$conn) {
    die("Koneksi Gagal: " . mysqli_connect_error());
}

// HAPUS TESTIMONI
if (isset($_GET['hapus'])) {

    $id = $_GET['hapus'];

    mysqli_query($conn, "DELETE FROM testimoni WHERE id_testimoni = '$id'");

    header("Location: admin.php?page=TampilanTestimoni");
    exit;
}

if (isset($_GET['tampilkan'])) {

    $id = $_GET['tampilkan'];

    mysqli_query($conn, "UPDATE testimoni SET status = 'tampilkan' WHERE id_testimoni = '$id'");

    header("Location: admin.php?page=TampilanTestimoni");
    exit;
}

if (isset($_GET['sembunyikan'])) {

    $id = $_GET['sembunyikan'];

    mysqli_query($conn, "UPDATE testimoni SET status = 'sembunyikan' WHERE id_testimoni = '$id'");

    header("Location: admin.php?page=TampilanTestimoni");
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
           BADGE
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

        /* =========================
           BUTTON
        ========================= */

        .btn-hapus{
            background: #ff4d4d;
            color: white;
            padding: 8px 14px;
            border-radius: 10px;
            text-decoration: none;
            font-size: 13px;
            font-weight: 600;
            transition: 0.3s;
        }

        .btn-hapus:hover{
            background: #ff2c2c;
            color: white;
        }

        /* =========================
           RESPONSIVE
        ========================= */

        @media(max-width:768px){

            .title-cursive{
                font-size: 2.5rem;
            }

            .testimoni-wrapper{
                padding: 20px;
            }

        }

    </style>
</head>

<body>

<div class="container py-5">

    <div class="testimoni-wrapper">

        <!-- HEADER -->
        <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">

            <div>
                <h1 class="title-cursive mb-0">
                    Customer Testimoni
                </h1>

                <p style="color:#aaa; margin:0;">
                    Semua ulasan pelanggan Jo Cafe
                </p>
            </div>

            <div class="testi-total">

                <?php
                $count = mysqli_query($conn, "SELECT id_testimoni FROM testimoni");
                ?>

                Total Testimoni:
                <strong><?php echo mysqli_num_rows($count); ?></strong>

            </div>

        </div>

        <!-- TABLE -->
        <div class="table-responsive">

            <table class="table table-dark custom-table align-middle">

                <thead>
                    <tr>
                        <th>Nama</th>
                        <th>Rating</th>
                        <th>Pesan</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>

                <tbody>

                <?php
                $query_testimoni = mysqli_query($conn, "SELECT * FROM testimoni ORDER BY id_testimoni DESC");

                while($row = mysqli_fetch_assoc($query_testimoni)) {
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

                        <td style="max-width:400px;">
                            <span style="color:#ccc;">
                                <?= htmlspecialchars($row['pesan']); ?>
                            </span>
                        </td>

                        <td class="text-center">

                            <a href="admin.php?page=TampilanTestimoni&hapus=<?php echo $row['id_testimoni']; ?>"
                               class="btn-hapus"
                               onclick="return confirm('Hapus testimoni ini?')">

                               <i class="fas fa-trash"></i> Hapus

                            </a>

                            <a href="admin.php?page=TampilanTestimoni&tampilkan=<?php echo $row['id_testimoni']; ?>"
                               class="btn-tampilkan"
                               onclick="return confirm('Tampilkan testimoni ini?')">

                               <i class="fas fa-eye"></i> Tampilkan

                            </a>

                            <a href="admin.php?page=TampilanTestimoni&sembunyikan=<?php echo $row['id_testimoni']; ?>"
                               class="btn-sembunyikan"
                               onclick="return confirm('Sembunyikan testimoni ini?')">

                               <i class="fas fa-eye-slash"></i> Sembunyikan

                            </a>

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