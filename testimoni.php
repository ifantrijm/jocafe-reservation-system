<?php
// 1. Sertakan file koneksi ke database (Pastikan variabel di koneksi.php adalah $conn)
include "config/koneksi.php"; 

// 2. Cek apakah tombol "Kirim" sudah diklik
if (isset($_POST['kirim'])) {
    
    // 3. Ambil data dari form dan amankan
    $nama    = mysqli_real_escape_string($conn, $_POST['nama']);
    $no_telp = mysqli_real_escape_string($conn, $_POST['no_telp']);
    $pesan   = mysqli_real_escape_string($conn, $_POST['pesan']);
    $rating  = $_POST['rating'];
    $tanggal = date('Y-m-d');

    // --- LOGIKA DAFTAR PELANGGAN OTOMATIS (Sama seperti di Reservasi Room) ---
    $cekPelanggan = mysqli_query($conn, "SELECT id_pelanggan FROM pelanggan WHERE telepon = '$no_telp'");
    
    if (mysqli_num_rows($cekPelanggan) > 0) {
        // Jika pelanggan lama, ambil ID-nya
        $data = mysqli_fetch_assoc($cekPelanggan);
        $id_pelanggan = $data['id_pelanggan'];
    } else {
        // Jika pelanggan baru, simpan ke tabel pelanggan dulu
        mysqli_query($conn, "INSERT INTO pelanggan (nama, telepon) VALUES ('$nama', '$no_telp')");
        $id_pelanggan = mysqli_insert_id($conn); // Ambil ID barunya
    }

    // 4. Buat query INSERT sesuai struktur DB terbaru (Menyertakan id_pelanggan)
    // status diset 'pending' sesuai default struktur tabel
    $sql = "INSERT INTO testimoni (id_pelanggan, nama, no_telp, pesan, rating, status, tanggal) 
            VALUES ('$id_pelanggan', '$nama', '$no_telp', '$pesan', '$rating', 'pending', '$tanggal')";

    // 5. Eksekusi query
    if (mysqli_query($conn, $sql)) {
        echo "<script>alert('Terima kasih! Testimoni berhasil disimpan dan menunggu moderasi.'); window.location='testimoni.php';</script>";
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Testimoni JO Cafe - Modern</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        /* CSS tetap menggunakan gaya modern rekan Pak Cik */
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Poppins', sans-serif;
            background: #0f172a;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            overflow-x: hidden;
            color: white;
        }
        .container {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(15px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            padding: 40px;
            border-radius: 30px;
            width: 450px;
            box-shadow: 0 25px 50px rgba(0,0,0,0.5);
            animation: fadeIn 1s ease-out;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        h2 { text-align: center; font-weight: 600; margin-bottom: 30px; letter-spacing: 1px; }
        .form-group { margin-bottom: 20px; position: relative; }
        input, textarea, select {
            width: 100%; padding: 15px;
            background: rgba(255, 255, 255, 0.1);
            border: 2px solid transparent;
            border-radius: 12px; color: white; outline: none;
            transition: 0.3s;
        }
        input:focus, textarea:focus {
            border-color: #f59e0b;
            background: rgba(255, 255, 255, 0.15);
            box-shadow: 0 0 15px rgba(245, 158, 11, 0.3);
        }
        button {
            width: 100%; padding: 15px; background: #f59e0b;
            border: none; border-radius: 12px; color: #0f172a;
            font-weight: 600; cursor: pointer; transition: 0.4s;
            text-transform: uppercase;
        }
        button:hover {
            background: #fbbf24; transform: scale(1.02);
            box-shadow: 0 10px 20px rgba(245, 158, 11, 0.4);
        }
        .list-section { margin-top: 30px; max-height: 200px; overflow-y: auto; padding-right: 10px; }
        .testi-card {
            background: rgba(255, 255, 255, 0.05);
            padding: 15px; border-radius: 10px; margin-bottom: 10px;
            border-left: 4px solid #f59e0b;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>☕ JO Cafe <span style="font-weight: 300;">Feedback</span></h2>
    
    <form id="testiForm" action="" method="POST">
        <div class="form-group">
            <input type="text" name="nama" placeholder="Nama Lengkap" required>
        </div>
        <div class="form-group">
            <input type="text" name="no_telp" placeholder="WhatsApp / No. Telp" required>
        </div>
        <div class="form-group">
            <textarea name="pesan" rows="3" placeholder="Bagaimana pengalaman Anda?" required></textarea>
        </div>
        <div class="form-group">
            <select name="rating" required>
                <option value="" disabled selected>Pilih Rating</option>
                <option value="5">⭐⭐⭐⭐⭐ Excellent</option>
                <option value="4">⭐⭐⭐⭐ Good</option>
                <option value="3">⭐⭐⭐ Average</option>
                <option value="2">⭐⭐ Poor</option>
                <option value="1">⭐ Very Poor</option>
            </select>
        </div>  
        <button type="submit" name="kirim">Kirim Sekarang ✨</button>
    </form>

    <div class="list-section">
        <?php
        // Menampilkan testimoni yang statusnya 'tampilkan' menggunakan variabel $conn
        $res = mysqli_query($conn, "SELECT * FROM testimoni WHERE status = 'tampilkan' ORDER BY id_testimoni DESC LIMIT 5");
        while($row = mysqli_fetch_assoc($res)) {
            $bintang = str_repeat("⭐", $row['rating']);
            echo "<div class='testi-card'>
                    <strong>{$row['nama']}</strong> <small>$bintang</small> <br>
                    <small>{$row['pesan']}</small>
                  </div>";
        }
        ?>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function(){
        $('#testiForm').submit(function(){
            $('button').text('Mengirim...');
        });
    });
</script>

</body>
</html>