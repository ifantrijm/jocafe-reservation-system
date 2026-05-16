<?php
include "../config/koneksi.php";

// ==========================================
// 1. SATPAM FLOW: Validasi Akses ID Room
// ==========================================
if (!isset($_GET['id_room']) || empty($_GET['id_room'])) {
    echo "<script>alert('Silakan pilih meja yang tersedia terlebih dahulu!'); window.location='detail.php';</script>";
    exit;
}

$id_room_dipilih = $_GET['id_room'];
$cek_meja = mysqli_query($conn, "SELECT * FROM room WHERE id_room = '$id_room_dipilih'");

if (mysqli_num_rows($cek_meja) == 0) {
    header("Location: detail.php");
    exit;
}

$info = mysqli_fetch_assoc($cek_meja);
$data_room = $info; // Konsistensi variabel untuk form

// ==========================================
// 2. LOGIKA SUBMIT FORM (PROSES SIMPAN)
// ==========================================
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['proses_reservasi'])) {
    
    $nama        = mysqli_real_escape_string($conn, $_POST['nama']);
    $email       = mysqli_real_escape_string($conn, $_POST['email']);
    $telepon     = mysqli_real_escape_string($conn, $_POST['telepon']);
    $id_room     = $_POST['id_room'];
    $jenis       = $_POST['jenis']; 
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

    // --- TAHAP 1: INPUT KE PELANGGAN ---
    $cek = mysqli_query($conn, "SELECT id_pelanggan FROM pelanggan WHERE telepon = '$telepon'");
    if (mysqli_num_rows($cek) > 0) {
        $dp = mysqli_fetch_assoc($cek);
        $id_pelanggan = $dp['id_pelanggan'];
    } else {
        mysqli_query($conn, "INSERT INTO pelanggan (nama, email, telepon) VALUES ('$nama', '$email', '$telepon')");
        $id_pelanggan = mysqli_insert_id($conn);
    }

    // --- TAHAP 2: INPUT KE RESERVASI_ROOM ---
    $query_res = "INSERT INTO reservasi_room (id_pelanggan, id_room, tanggal_reservasi, jam_mulai, jam_selesai, bukti_pembayaran, nama_event, deskripsi) 
                  VALUES ('$id_pelanggan', '$id_room', '$tgl', '$jam_mulai', '$jam_selesai', '$bukti_baru', '$nama_event', '$deskripsi')";

    if (mysqli_query($conn, $query_res)) {
        $id_baru = mysqli_insert_id($conn);
        
        // Update status room jadi Dipesan (Otomatis Merah)
        mysqli_query($conn, "UPDATE room SET status = 'Dipesan' WHERE id_room = '$id_room'");
        
        header("Location: nota.php?id=$id_baru");
        exit();
    } else {
        echo "<script>alert('Error: " . mysqli_error($conn) . "');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Isi Data Reservasi | Jo Cafe</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root { --bg-main: #13171c; --bg-card: #1c2128; --text-main: #ffffff; --text-muted: #a0aab5; --accent-gold: #f89d13; --accent-gold-hover: #e08c0f; --border-dark: rgba(255, 255, 255, 0.1); }
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: var(--bg-main); color: var(--text-main); padding-top: 80px; }
        .navbar-custom { background-color: rgba(19, 23, 28, 0.95); border-bottom: 1px solid var(--border-dark); }
        .form-card { background-color: var(--bg-card); border: 1px solid var(--border-dark); border-radius: 16px; padding: 40px; }
        .form-control, .form-select { background-color: rgba(255, 255, 255, 0.05); border: 1px solid rgba(255, 255, 255, 0.1); color: white; }
        .btn-gold { background-color: var(--accent-gold); color: white; font-weight: 700; padding: 14px; border-radius: 8px; border:none; }
        .section-title { font-weight: 800; border-bottom: 2px solid var(--border-dark); padding-bottom: 10px; margin-bottom: 25px; color: var(--accent-gold); }
        .form-card input {border-color:var(--accent-gold);}
        .box {border: 1px solid; border-color:var(--accent-gold);}
    </style>
</head>
<body>

    <nav class="navbar navbar-expand-lg navbar-dark navbar-custom fixed-top">
        <div class="container">
            <a class="navbar-brand fs-4" href="#">JO CAFE.</a>
            <a href="detail.php" class="btn btn-outline-light btn-sm rounded-pill px-4">Kembali</a>
        </div>
    </nav>

    <div class="container mb-5">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="form-card">
                    <form action="" method="POST" enctype="multipart/form-data">
                        
                        <div class="mb-4 p-3 rounded" style="background: rgba(248, 157, 19, 0.1); border: 1px solid var(--accent-gold);">
                            <label class="form-label text-warning mb-1">Area / Meja Terpilih</label>
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
                                <select name="jenis" id="jenis_reservasi" class="form-select" style="background-color: var(--bg-card); border-color:var(--accent-gold);" required>
                                    <option value="Makan-Makan">Makan-Makan</option>
                                    <option value="ulang tahun">Ulang Tahun</option>
                                    <option value="Rapat">Rapat</option>
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
                            <textarea name="deskripsi" class="form-control" style="border-color:var(--accent-gold);" rows="3"></textarea>
                        </div>

                        <h5 class="section-title">3. Konfirmasi Pembayaran</h5>
                        <div class="mb-4 box p-3">
                            <div class="row g-2">
                                <div class="col-6">
                                    <p class="text-center">Via QRIS</p>
                                    <button type="button" class="btn btn-gold w-100" data-bs-toggle="modal" data-bs-target="#modalQRIS">
                                        <i class="fas fa-qrcode me-2"></i>QRIS
                                    </button>
                                </div>
                                <div class="col-6">
                                    <p class="text-center">Via Rekening </p>
                                    <button type="button" class="btn btn-gold w-100" onclick="alert('BCA Jo Cafe: 0241464007 \na.n Jo Cafe Official')">
                                        BCA 0241464007 / ITA KRISTANTI
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label text-muted small">Upload Bukti Pembayaran (JPG/PNG)</label>
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

    <div class="modal fade" id="modalQRIS" tabindex="-1" aria-labelledby="modalQRISLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content" style="background-color: #1c2128; border: 1px solid #f89d13; border-radius: 16px;">
                <div class="modal-header border-0">
                    <h5 class="modal-title text-white fw-bold" id="modalQRISLabel">
                        <i class="fas fa-qrcode me-2 text-warning"></i>Pembayaran QRIS
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center pb-5">
                    <p class="text-muted small mb-4">Silakan scan kode QRIS di bawah ini untuk DP Reservasi.</p>
                    <div class="p-3 bg-white d-inline-block rounded-3 mb-4">
                        <img src="../assets/img/dll/img.jpeg" class="img-fluid" style="max-width: 250px;" alt="QRIS Jo Cafe">
                    </div>
                    <div class=" mx-3">
                        <h6 class="text-white mb-1">Jo Cafe Official</h6>
                        <span class="text-warning small">NMID: ID1234567890</span>
                    </div>
                    <button type="button" class="btn btn-outline-light w-100 mt-3" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        document.getElementById('jenis_reservasi').addEventListener('change', function() {
            var eventDiv = document.getElementById('input_nama_event');
            if (this.value === 'ulang tahun' || this.value === 'Rapat') {
                eventDiv.style.display = 'block';
            } else {
                eventDiv.style.display = 'none';
            }
        });
    </script>
</body>
</html>