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
    $tgl         = $_POST['tanggal_reservasi'];
    $jam_mulai   = $_POST['jam_mulai'];
    $jam_selesai = $_POST['jam_selesai'];
    $deskripsi   = mysqli_real_escape_string($conn, $_POST['jenis']);

    // --- VALIDASI BACKEND: Cek Durasi Maksimal 6 Jam ---
    $time_mulai = strtotime($jam_mulai);
    $time_selesai = strtotime($jam_selesai);
    $selisih_jam = ($time_selesai - $time_mulai) / 3600; // Ubah detik ke jam

    if ($selisih_jam <= 0) {
        echo "<script>alert('Jam selesai tidak boleh lebih awal atau sama dengan jam mulai!'); window.history.back();</script>";
        exit;
    } elseif ($selisih_jam > 6) {
        echo "<script>alert('Maaf, maksimal durasi reservasi adalah 6 jam!'); window.history.back();</script>";
        exit;
    }

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
    $query_res = "INSERT INTO reservasi_room (id_pelanggan, id_room, tanggal_reservasi, jam_mulai, jam_selesai, bukti_pembayaran, deskripsi) 
                  VALUES ('$id_pelanggan', '$id_room', '$tgl', '$jam_mulai', '$jam_selesai', '$bukti_baru', '$deskripsi')";

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
        :root { --bg-main: #13171c; --bg-card: #1c2128; --text-main: #ffffff; --text-muted: #a0aab5; --accent-gold: #f89d13; --border-dark: rgba(255, 255, 255, 0.1); }
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: var(--bg-main); color: var(--text-main); padding-top: 80px; }
        .navbar-custom { background-color: rgba(19, 23, 28, 0.95); border-bottom: 1px solid var(--border-dark); }
        .form-card { background-color: var(--bg-card); border: 1px solid var(--border-dark); border-radius: 16px; padding: 40px; }
        .form-control, .form-select { background-color: rgba(255, 255, 255, 0.05); border: 1px solid rgba(255, 255, 255, 0.1); color: white; }
        .btn-gold { background-color: var(--accent-gold); color: white; font-weight: 700; padding: 14px; border-radius: 8px; border:none; }
        .section-title { font-weight: 800; border-bottom: 2px solid var(--border-dark); padding-bottom: 10px; margin-bottom: 25px; color: var(--accent-gold); }
        .form-card input, .form-card select {border-color:var(--accent-gold);}
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
                    <form action="" method="POST" enctype="multipart/form-data" id="formReservasi">
                        
                        <div class="mb-4 p-3 rounded" style="background: rgba(248, 157, 19, 0.1); border: 1px solid var(--accent-gold);">
                            <label class="form-label text-warning mb-1">Area / Meja Terpilih</label>
                            <input type="text" class="form-control border-0 bg-transparent fs-5 p-0" value="<?= $data_room['nama_area']; ?> (Kapasitas <?= $data_room['kapasitas']; ?> Orang)" readonly>
                            <input type="hidden" name="id_room" value="<?= $data_room['id_room']; ?>"> 
                        </div>

                        
                        <div class="alert border-warning text-warning" >
                            <i class="fas fa-lightbulb text-warning me-2"></i> 
                            <small><strong>Pernah reservasi sebelumnya?</strong> Masukkan nomor WhatsApp Anda terlebih dahulu, maka Nama dan Email akan terisi otomatis!</small>
                        </div>

                        <h5 class="section-title">1. Data Pemesan</h5>

                        <div class="mb-3">
                            <label class="form-label">No. WhatsApp <span style="color: red;">*</span></label>
                            <input type="number" id="no_telp" name="telepon" class="form-control"  required>
                            <small id="status_pelanggan" class="text-success fw-bold mt-1" style="display: none;"></small>
                        </div>

                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <label class="form-label">Nama Lengkap <span style="color: red;">*</span></label>
                                <input type="text" id="nama_pendaftar" name="nama" class="form-control" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Email Aktif</label>
                                <input type="email" id="email_pendaftar" name="email" class="form-control" >
                            </div>
                        </div>

                        <h5 class="section-title">2. Detail Acara & Waktu </h5>
                        <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Jenis Reservasi <span style="color: red;">*</span></label>
                            <select 
                                name="jenis" 
                                id="jenis_reservasi" 
                                class="form-select"
                                style="background-color: var(--bg-card); border-color:var(--accent-gold);" 
                                required
                            >
                                <option value="">-- Pilih Jenis Reservasi --</option>
                                <option value="Dine In">Dine In / Makan Bersama</option>
                                <option value="Birthday">Perayaan Ulang Tahun</option>
                                <option value="Meeting">Meeting / Rapat</option>
                                <option value="Family Gathering">Family Gathering</option>
                                <option value="Private Event">Private Event</option>
                                <option value="Anniversary">Anniversary</option>
                                <option value="Komunitas">Gathering Komunitas</option>
                                <option value="Lainnya">Lainnya</option>
                            </select>
                        </div>
                            <div class="col-md-6">
                                <label class="form-label">Tanggal Kedatangan <span style="color: red;">*</span></label>
                                <input type="date" name="tanggal_reservasi" class="form-control" required>
                            </div>
                        </div>

                        <div class="row g-3 mb-1">
                            <div class="col-6">
                                <label class="form-label">Jam Mulai <span style="color: red;">*</span></label>
                                <input type="time" id="jam_mulai" name="jam_mulai" class="form-control" required>
                            </div>


                        <h5 class="section-title">3. Konfirmasi Pembayaran <span style="color: red;">*</span></h5>
                        <div class="mb-4 box p-3">
                            <div class="row g-2">
                                <div class="col-12">
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


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
    // Validasi saat form mau disubmit (Biar pelanggan nggak capek nunggu loading kalau salah)
    document.getElementById('formReservasi').addEventListener('submit', function(e) {
        let mulai = document.getElementById('jam_mulai').value;
        let selesai = document.getElementById('jam_selesai').value;

        if (mulai && selesai) {
            // Konversi ke objek Date dummy (hari yang sama)
            let timeMulai = new Date("2000-01-01T" + mulai);
            let timeSelesai = new Date("2000-01-01T" + selesai);

            let diffHours = (timeSelesai - timeMulai) / (1000 * 60 * 60);

            if (diffHours <= 0) {
                alert("Jam selesai harus lebih akhir dari jam mulai!");
                e.preventDefault(); // Batalkan pengiriman form
            } else if (diffHours > 6) {
                alert("Maksimal waktu pemakaian ruangan adalah 6 jam!");
                e.preventDefault(); // Batalkan pengiriman form
            }
        }
    });

    // Auto-fill pelanggan
    document.getElementById('no_telp').addEventListener('blur', function() {
        let noWa = this.value;
        let inputNama = document.getElementById('nama_pendaftar');
        let inputEmail = document.getElementById('email_pendaftar');
        let statusTeks = document.getElementById('status_pelanggan');

        if (noWa.trim() !== "") {
            fetch('cek_pelanggan.php?telp=' + noWa)
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'ketemu') {
                        inputNama.value = data.nama;
                        inputEmail.value = data.email;
                        statusTeks.innerHTML = '<i class="fas fa-check-circle"></i> Data pelanggan ditemukan! Terisi otomatis.';
                        statusTeks.style.display = 'block';
                        inputNama.style.borderColor = '#0dcaf0';
                        inputEmail.style.borderColor = '#0dcaf0';
                    } else {
                        statusTeks.style.display = 'none';
                        inputNama.style.borderColor = 'var(--accent-gold)';
                        inputEmail.style.borderColor = 'rgba(255, 255, 255, 0.1)';
                    }
                })
                .catch(error => console.error('Error fetching data:', error));
        }
    });
    </script>

</body>
</html>