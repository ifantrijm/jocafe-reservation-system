<?php
include "../config/koneksi.php";

//  SATPAM FLOW: Cek apakah ada id_room di URL?
if (!isset($_GET['id_room']) || empty($_GET['id_room'])) {
    // kalau nggak ada ID, tendang balik ke halaman pemilihan meja (detail.php)
    echo "<script>alert('Silakan pilih meja yang tersedia terlebih dahulu!'); window.location='detail.php';</script>";
    exit;
}

//  Ambil ID dari URL
$id_room_dipilih = $_GET['id_room'];

//  Validasi Tambahan: Cek apakah ID tersebut benar-benar ada di database?
$cek_meja = mysqli_query($conn, "SELECT * FROM room WHERE id_room = '$id_room_dipilih'");

if (mysqli_num_rows($cek_meja) == 0) {
    // Kalau ID asal-asalan (nggak ada di DB), tendang juga!
    header("Location: detail.php");
    exit;
}

// Jika lolos semua satpam di atas, baru ambil datanya untuk ditampilkan di form
$info = mysqli_fetch_assoc($cek_meja);
$nama_area_tampil = $info['nama_area'];
$kapasitas_tampil = $info['kapasitas'];

// 1. Logika Ambil Data Room (Untuk menampilkan info room yang dipilih di form)
$id_room_dipilih = isset($_GET['id_room']) ? $_GET['id_room'] : 1;
$query_room = mysqli_query($conn, "SELECT * FROM room WHERE id_room = '$id_room_dipilih'");
$data_room = mysqli_fetch_assoc($query_room);


// 2. Logika Submit Form
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['proses_reservasi'])) {
    
    $nama        = mysqli_real_escape_string($conn, $_POST['nama']);
    $email       = mysqli_real_escape_string($conn, $_POST['email']);
    $telepon     = mysqli_real_escape_string($conn, $_POST['telepon']);
    $id_room     = $_POST['id_room'];
    $jenis       = $_POST['jenis']; // 'room' atau 'event'
    $tgl         = $_POST['tanggal_reservasi'];
    $jam_mulai   = $_POST['jam_mulai'];
    $jam_selesai = $_POST['jam_selesai'];
    $deskripsi   = mysqli_real_escape_string($conn, $_POST['deskripsi']);
    $nama_event  = isset($_POST['nama_event']) ? mysqli_real_escape_string($conn, $_POST['nama_event']) : '-';

    // Logika Upload Bukti Bayar
    $bukti_nama = $_FILES['bukti_pembayaran']['name'];
    $bukti_tmp  = $_FILES['bukti_pembayaran']['tmp_name'];
    $bukti_baru = time() . '_' . $bukti_nama;
    move_uploaded_file($bukti_tmp, "../assets/img/bukti/" . $bukti_baru);

    // --- TAHAP 1: INPUT KE PELANGGAN (ID didapat otomatis) ---
    $cek = mysqli_query($conn, "SELECT id_pelanggan FROM pelanggan WHERE telepon = '$telepon'");
    if (mysqli_num_rows($cek) > 0) {
        $dp = mysqli_fetch_assoc($cek);
        $id_pelanggan = $dp['id_pelanggan'];
    } else {
        mysqli_query($conn, "INSERT INTO pelanggan (nama, email, telepon) VALUES ('$nama', '$email', '$telepon')");
        $id_pelanggan = mysqli_insert_id($conn);
    }

    // --- TAHAP 2: INPUT KE DETAIL_RESERVASI ---
    mysqli_query($conn, "INSERT INTO detail_reservasi (id_pelanggan, id_room, nama_event, deskripsi) 
                         VALUES ('$id_pelanggan', '$id_room', '$nama_event', '$deskripsi')");
    $id_detail = mysqli_insert_id($conn);

    // --- TAHAP 3: INPUT KE RESERVASI_ROOM & UPDATE STATUS ---
    $query_res = "INSERT INTO reservasi_room (id_pelanggan, id_detail_reservasi, tanggal_reservasi, jam_mulai, jam_selesai, bukti_pembayaran) 
                  VALUES ('$id_pelanggan', '$id_detail', '$tgl', '$jam_mulai', '$jam_selesai', '$bukti_baru')";

if (mysqli_query($conn, $query_res)) {
        // Ambil ID reservasi yang baru saja masuk
        $id_baru = mysqli_insert_id($conn);
        
        // Update status room jadi Dipesan
        mysqli_query($conn, "UPDATE room SET status = 'Dipesan' WHERE id_room = '$id_room'");
        
        // Langsung arahkan ke halaman nota
        header("Location: nota.php?id=$id_baru");
        exit();
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <!-- CSS TETAP SAMA SEPERTI MILIK PAK CIK -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Isi Data Reservasi | Jo Cafe</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;600;700;800&display=swap" rel="stylesheet">
    <style>
        /* (Style milik Pak Cik dimasukkan di sini) */
        :root { --bg-main: #13171c; --bg-card: #1c2128; --text-main: #ffffff; --text-muted: #a0aab5; --accent-gold: #f89d13; --accent-gold-hover: #e08c0f; --border-dark: rgba(255, 255, 255, 0.1); }
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: var(--bg-main); color: var(--text-main); padding-top: 80px; }
        .navbar-custom { background-color: rgba(19, 23, 28, 0.95); border-bottom: 1px solid var(--border-dark); }
        .form-card { background-color: var(--bg-card); border: 1px solid var(--border-dark); border-radius: 16px; padding: 40px; }
        .form-control, .form-select { background-color: rgba(255, 255, 255, 0.05); border: 1px solid rgba(255, 255, 255, 0.1); color: white; }
        .btn-gold { background-color: var(--accent-gold); color: white; font-weight: 700; padding: 14px; border-radius: 8px; border:none; }
        .section-title { font-weight: 800; border-bottom: 2px solid var(--border-dark); padding-bottom: 10px; margin-bottom: 25px; color: var(--accent-gold); }
        .form-card input {border-color:var(--accent-gold);}
    </style>
</head>
<body>

    <nav class="navbar navbar-expand-lg navbar-dark navbar-custom fixed-top">
        <div class="container">
            <a class="navbar-brand fs-4" href="#">JO CAFE.</a>
            <a href="detail_reservasi.php" class="btn btn-outline-light btn-sm rounded-pill px-4">Kembali</a>
        </div>
    </nav>

    <div class="container mb-5">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="form-card">
                    <form action="" method="POST" enctype="multipart/form-data">
                        
                        <div class="mb-4 p-3 rounded" style="background: rgba(248, 157, 19, 0.1); border: 1px solid var(--accent-gold);">
                            <label class="form-label text-warning mb-1">Area / Meja Terpilih</label>
                            <!-- Mengambil Nama Meja dari DB -->
                            <input type="text" class="form-control border-0 bg-transparent fs-5 p-0" value="<?= $data_room['nama_area']; ?> (Kapasitas <?= $data_room['kapasitas']; ?> Orang)" readonly>
                            <input type="hidden" name="id_room" value="<?= $data_room['id_room']; ?>"> 
                        </div>

                        <h5 class="section-title">1. Data Pemesan</h5>
                        <div class="mb-3">
                            <label class="form-label">Nama Lengkap</label>
                            <input type="text" name="nama" class="form-control" required>
                        </div>
                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <label class="form-label">Email Aktif</label>
                                <input type="email" name="email" class="form-control" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">No. WhatsApp</label>
                                <input type="number" name="telepon" class="form-control" required>
                            </div>
                        </div>

                        <h5 class="section-title">2. Detail Acara & Waktu</h5>
                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Jenis Reservasi</label>
                                <select name="jenis" id="jenis_reservasi" class="form-select"  style="background-color: var(--bg-card); border-color:var(--accent-gold);" required>
                                    <option value="room">Nongkrong / Makan Biasa</option>
                                    <option value="event">Acara Spesial (Event)</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Tanggal Kedatangan</label>
                                <input type="date" name="tanggal_reservasi" class="form-control" required>
                            </div>
                        </div>

                        <div class="mb-3" id="input_nama_event" style="display: none;">
                            <label class="form-label">Nama Acara</label>
                            <input type="text" name="nama_event" id="field_event" class="form-control">
                        </div>

                        <div class="row g-3 mb-3">
                            <div class="col-6">
                                <label class="form-label">Jam Mulai</label>
                                <input type="time" name="jam_mulai" class="form-control" required>
                            </div>
                            <div class="col-6">
                                <label class="form-label">Jam Selesai</label>
                                <input type="time" name="jam_selesai" class="form-control" required>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label">Catatan Tambahan</label>
                            <textarea name="deskripsi" class="form-control"  style="border-color:var(--accent-gold);" rows="3"></textarea>
                        </div>

                        <h5 class="section-title">3. Konfirmasi Pembayaran</h5>
                        <div class="mb-4">
                            <input type="file" name="bukti_pembayaran" class="form-control" accept="image/*" required>
                        </div>

                        <button type="submit" name="proses_reservasi" class="btn btn-gold w-100 fs-5 mt-2">
                            Kirim Form Reservasi
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('jenis_reservasi').addEventListener('change', function() {
            var eventDiv = document.getElementById('input_nama_event');
            if (this.value === 'event') {
                eventDiv.style.display = 'block';
            } else {
                eventDiv.style.display = 'none';
            }
        });
    </script>
</body>
</html>