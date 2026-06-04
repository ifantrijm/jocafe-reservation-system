<?php
require "../config/koneksi.php";

function compressImage($source, $destination, $quality) {
    $info = getimagesize($source);
    if ($info['mime'] == 'image/jpeg') $image = imagecreatefromjpeg($source);
    elseif ($info['mime'] == 'image/png') $image = imagecreatefrompng($source);
    
    $width = $info[0];
    $height = $info[1];
    $new_width = 800; 
    $new_height = ($height / $width) * $new_width;
    
    $tmp = imagecreatetruecolor($new_width, $new_height);
    imagecopyresampled($tmp, $image, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
    imagejpeg($tmp, $destination, $quality);
    
    imagedestroy($image);
    imagedestroy($tmp);
}

// ==========================================
// 1. SATPAM FLOW: Validasi Akses ID Room & Anti-Bypass URL
// ==========================================
if (!isset($_GET['id_room']) || empty($_GET['id_room'])) {
    echo "<script>alert('Silakan pilih meja yang tersedia terlebih dahulu!'); window.location='detail.php';</script>";
    exit;
}

$id_room_dipilih = mysqli_real_escape_string($conn, $_GET['id_room']);
$cek_meja = mysqli_query($conn, "SELECT * FROM room WHERE id_room = '$id_room_dipilih'");

if (mysqli_num_rows($cek_meja) == 0) {
    header("Location: detail.php");
    exit;
}
$data_room = mysqli_fetch_assoc($cek_meja); 

// --- ALGORITMA SATPAM ANTI-BYPASS URL ---
$hari_ini_satpam = date('Y-m-d'); 
$jam_buka_satpam = strtotime("10:00");
$jam_tutup_satpam = strtotime("22:00");
$minimal_booking_satpam = 3600; // 1 Jam

$q_jadwal_satpam = mysqli_query($conn, "SELECT jam_mulai, jam_selesai FROM reservasi_room WHERE id_room = '$id_room_dipilih' AND tanggal_reservasi = '$hari_ini_satpam' AND status_pesanan != 'Selesai' ORDER BY jam_mulai ASC");

$ada_celah_satpam = false;
$waktu_cek_satpam = $jam_buka_satpam;

if (mysqli_num_rows($q_jadwal_satpam) > 0) {
    while ($jadwal = mysqli_fetch_assoc($q_jadwal_satpam)) {
        $mulai_booking = strtotime($jadwal['jam_mulai']);
        $selesai_booking = strtotime($jadwal['jam_selesai']);

        if ($mulai_booking - $waktu_cek_satpam >= $minimal_booking_satpam) {
            $ada_celah_satpam = true;
            break; 
        }
        if ($selesai_booking > $waktu_cek_satpam) {
            $waktu_cek_satpam = $selesai_booking;
        }
    }
    if (!$ada_celah_satpam && ($jam_tutup_satpam - $waktu_cek_satpam >= $minimal_booking_satpam)) {
        $ada_celah_satpam = true;
    }
} else {
    $ada_celah_satpam = true; 
}

// JIKA HARI INI FULL DAN USER MAKSA KETIK URL MANUAL, TENDANG KELUAR!
if (!$ada_celah_satpam) {
    echo "<script>alert('Akses Ditolak! Celah keamanan terdeteksi. Meja ini sudah full dibooking untuk hari ini.'); window.location='detail.php';</script>";
    exit;
}

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
    $deskripsi   = mysqli_real_escape_string($conn, $_POST['jenis']);
    
    $jam_selesai_input = $_POST['jam_selesai'];

    // --- LOGIKA JAM SELESAI OTOMATIS & VALIDASI DURASI ---
    $time_mulai = strtotime($jam_mulai);

    if (empty($jam_selesai_input)) {
        $time_selesai = strtotime("+6 hours", $time_mulai);
        $jam_selesai = date('H:i', $time_selesai);
    } else {
        $jam_selesai = $jam_selesai_input;
        $time_selesai = strtotime($jam_selesai);
        $selisih_jam = ($time_selesai - $time_mulai) / 3600; 

        if ($selisih_jam <= 0) {
            echo "<script>alert('Jam selesai tidak boleh lebih awal atau sama dengan jam mulai!'); window.history.back();</script>";
            exit;
        } elseif ($selisih_jam > 6) {
            echo "<script>alert('Maaf, maksimal durasi reservasi adalah 6 jam!'); window.history.back();</script>";
            exit;
        }
    }

    // --- CEK BENTROK JADWAL (OVERLAP DETECTION) DI BACKEND ---
    $cek_bentrok = mysqli_query($conn, "
        SELECT * FROM reservasi_room 
        WHERE id_room = '$id_room' 
        AND tanggal_reservasi = '$tgl' 
        AND status_pesanan != 'Selesai'
        AND ('$jam_mulai' < jam_selesai AND '$jam_selesai' > jam_mulai)
    ");

    if(mysqli_num_rows($cek_bentrok) > 0) {
        echo "<script>alert('Mohon maaf, durasi yang Anda pilih menabrak jadwal yang sudah terisi. Silakan cek kembali!'); window.history.back();</script>";
        exit;
    }

    // --- PROSES UPLOAD & KOMPRESI BUKTI BAYAR ---
    $bukti_nama = $_FILES['bukti_pembayaran']['name'];
    $bukti_tmp  = $_FILES['bukti_pembayaran']['tmp_name'];
    $bukti_baru = time() . '_' . $bukti_nama;
    $target_path = "../assets/img/bukti/" . $bukti_baru;
    
    if (move_uploaded_file($bukti_tmp, $target_path)) {
        compressImage($target_path, $target_path, 60);
    }

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
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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

<?php include"../include/navbarroom.php" ?>

    <div class="container mb-5">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="form-card">
                    
                    <div class="mb-4 p-3 rounded" style="background: rgba(248, 157, 19, 0.1); border: 1px solid var(--accent-gold);">
                        <label class="form-label text-warning mb-1">Area / Meja Terpilih</label>
                        <input type="text" class="form-control border-0 bg-transparent fs-5 p-0" value="<?= $data_room['nama_area']; ?> (Kapasitas <?= $data_room['kapasitas']; ?> Orang)" readonly>
                    </div>

                    <div class="alert border-warning text-warning" >
                        <i class="fas fa-lightbulb text-warning me-2"></i> 
                        <small><strong>Pernah reservasi sebelumnya?</strong> Masukkan nomor WhatsApp Anda terlebih dahulu, maka Nama dan Email akan terisi otomatis!</small>
                    </div>

                    <form action="" method="POST" enctype="multipart/form-data" id="formReservasi">
                        <input type="hidden" name="id_room" id="id_room_input" value="<?= $data_room['id_room']; ?>"> 

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
                                <select name="jenis" id="jenis_reservasi" class="form-select" style="background-color: var(--bg-card);" required>
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
                                <input type="date" id="tanggal_reservasi" name="tanggal_reservasi" class="form-control" min="<?php echo date('Y-m-d'); ?>" required>
                            </div>
                        </div>

                        <div id="box_info_jadwal" class="mb-3 p-3 rounded" style="display:none; background-color: #111826; border: 1px dashed var(--accent-gold);">
                            <h6 class="text-warning mb-2"><i class="fas fa-calendar-check me-2"></i>Status Jadwal di Tanggal Ini:</h6>
                            <div id="list_jadwal" class="small text-white"></div>
                        </div>

                        <div class="row g-3 mb-1">
                            <div class="col-6">
                                <label class="form-label">Jam Mulai <span style="color: red;">*</span></label>
                                <input type="time" id="jam_mulai" name="jam_mulai" class="form-control" required>
                            </div>
                            <div class="col-6">
                                <label class="form-label">Jam Selesai (Opsional)</label>
                                <input type="time" id="jam_selesai" name="jam_selesai" class="form-control" placeholder="Otomatis 6 Jam">
                            </div>
                        </div>
                        <div class="mb-4">
                            <small class="text-info"><i class="fas fa-info-circle me-1"></i> Maksimal pemakaian 6 jam. Harap pilih jam yang belum terisi di atas.</small>
                        </div>

                        <h5 class="section-title">3. Konfirmasi Pembayaran <span style="color: red;">*</span></h5>
                        
                        <div class="alert alert-info mb-3" style="background-color: rgba(248, 157, 19, 0.1); border-color: var(--accent-gold); color: #fff;">
                            <i class="fas fa-money-bill-wave text-warning me-2"></i>
                            Biaya Reservasi: <strong>Rp 100.000</strong>
                        </div>

                        <div class="mb-4 box p-3">
                            <div class="row g-2">
                                <div class="col-12">
                                    <p class="text-center">Via Rekening </p>
                                    <button type="button" class="btn btn-gold w-100" id="btnRekening">
                                        BCA 0241464007 / ITA KRISTANTI
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label text-muted small">Upload Bukti Pembayaran (JPG/PNG)</label>
                           <input type="file" name="bukti_pembayaran" class="form-control" accept=".jpg, .jpeg, .png" required>
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
    let jadwalTerisi = [];

    // 1. AJAX Cek Jadwal Saat Tanggal Dipilih
    document.getElementById('tanggal_reservasi').addEventListener('change', function() {
        let tgl = this.value;
        let idRoom = document.getElementById('id_room_input').value;
        let boxInfo = document.getElementById('box_info_jadwal');
        let listJadwal = document.getElementById('list_jadwal');

        if(tgl) {
            boxInfo.style.display = 'block';
            listJadwal.innerHTML = '<span class="text-muted">Memeriksa jadwal...</span>';

            fetch(`cek_jadwal_api.php?id_room=${idRoom}&tgl=${tgl}`)
                .then(response => response.json())
                .then(data => {
                    jadwalTerisi = data; 
                    if(data.length > 0) {
                        let html = '<ul class="mb-0 ps-3">';
                        data.forEach(item => {
                            html += `<li class="text-danger fw-bold">Terbooking: Jam ${item.mulai} s/d ${item.selesai}</li>`;
                        });
                        html += '</ul>';
                        listJadwal.innerHTML = html;
                    } else {
                        listJadwal.innerHTML = '<span class="text-success fw-bold"><i class="fas fa-check-circle"></i> Meja kosong sepanjang hari. Silakan pilih jam bebas.</span>';
                    }
                })
                .catch(error => {
                    listJadwal.innerHTML = '<span class="text-danger">Gagal memuat jadwal.</span>';
                });
        }
    });

// 2. Validasi Cerdas saat Pelanggan Memilih Jam Mulai (SweetAlert)
    document.getElementById('jam_mulai').addEventListener('change', function() {
        let inputMulai = this.value;
        if(!inputMulai) return;

        let timeInput = new Date("2000-01-01T" + inputMulai);
        let isBooked = false;

        // Pastikan jadwal diurutkan dari jam paling pagi agar pengecekan berurutan
        jadwalTerisi.sort((a, b) => a.mulai.localeCompare(b.mulai));

        // Looping untuk mengecek dan "mendorong" jam jika nabrak jadwal beruntun
        for (let i = 0; i < jadwalTerisi.length; i++) {
            let slotMulai = new Date("2000-01-01T" + jadwalTerisi[i].mulai);
            let slotSelesai = new Date("2000-01-01T" + jadwalTerisi[i].selesai);

            // Jika input (atau hasil geseran sebelumnya) jatuh di dalam jadwal ini
            if (timeInput >= slotMulai && timeInput < slotSelesai) {
                isBooked = true;
                // Dorong jam input ke jam selesai jadwal ini, lalu lanjut cek jadwal berikutnya!
                timeInput = new Date(slotSelesai); 
            }
        }

        if (isBooked) {
            // Ambil jam hasil dorongan terakhir (format HH:mm)
            let jamSaran = timeInput.toTimeString().substring(0, 5);

            Swal.fire({
                icon: 'warning',
                title: 'Waktu Berurutan Terisi',
                text: `Mohon maaf, jam ${inputMulai} menabrak sesi booking. Sistem otomatis mencarikan jam kosong terdekat dan menggeser jadwal Anda ke jam ${jamSaran}.`,
                confirmButtonColor: '#f89d13',
                background: '#1c2128',
                color: '#fff'
            });
            
            this.value = jamSaran; 
            document.getElementById('jam_selesai').min = this.value; 
        }
    });

    // 3. Kunci Jam Selesai agar tidak lebih awal (SweetAlert)
    document.getElementById('jam_mulai').addEventListener('change', function() {
        let jamMulaiValue = this.value;
        let inputJamSelesai = document.getElementById('jam_selesai');
        
        inputJamSelesai.min = jamMulaiValue;
        
        if (inputJamSelesai.value && inputJamSelesai.value <= jamMulaiValue) {
            inputJamSelesai.value = ''; 
            Swal.fire({
                icon: 'info',
                title: 'Info Waktu',
                text: 'Jam selesai otomatis direset karena tidak boleh lebih awal dari jam mulai!',
                confirmButtonColor: '#f89d13',
                background: '#1c2128',
                color: '#fff'
            });
        }
    });

    // 4. Validasi Durasi Jam & Bentrok Durasi (SweetAlert)
    document.getElementById('formReservasi').addEventListener('submit', function(e) {
        let mulai = document.getElementById('jam_mulai').value;
        let selesai = document.getElementById('jam_selesai').value;
        
        if (mulai && selesai) {
            let timeMulai = new Date("2000-01-01T" + mulai);
            let timeSelesai = new Date("2000-01-01T" + selesai);
            let diffHours = (timeSelesai - timeMulai) / (1000 * 60 * 60);

            if (diffHours <= 0) {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Jam selesai harus lebih akhir dari jam mulai!',
                    confirmButtonColor: '#f89d13',
                    background: '#1c2128',
                    color: '#fff'
                });
                e.preventDefault(); 
                return;
            } else if (diffHours > 6) {
                Swal.fire({
                    icon: 'error',
                    title: 'Batas Waktu',
                    text: 'Maksimal waktu pemakaian ruangan adalah 6 jam!',
                    confirmButtonColor: '#f89d13',
                    background: '#1c2128',
                    color: '#fff'
                });
                e.preventDefault(); 
                return;
            }
            
            for (let i = 0; i < jadwalTerisi.length; i++) {
                let slotMulai = new Date("2000-01-01T" + jadwalTerisi[i].mulai);
                if (timeMulai < slotMulai && timeSelesai > slotMulai) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Jadwal Bentrok',
                        text: `Durasi Anda menabrak jadwal lain yang dimulai pada jam ${jadwalTerisi[i].mulai}. Silakan kurangi jam selesai Anda.`,
                        confirmButtonColor: '#f89d13',
                        background: '#1c2128',
                        color: '#fff'
                    });
                    e.preventDefault();
                    return;
                }
            }
        }
    });

    // 5. Popup Rekening Info
    document.getElementById('btnRekening').addEventListener('click', function() {
        Swal.fire({
            icon: 'info',
            title: 'Informasi Rekening',
            html: '<p class="text-white">BCA Jo Cafe:<br><strong class="fs-4 text-warning">0241464007</strong><br>a.n Jo Cafe Official / ITA KRISTANTI</p>',
            confirmButtonColor: '#f89d13',
            background: '#1c2128',
            color: '#fff'
        });
    });

    // 6. Auto-fill Pelanggan
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
                        inputEmail.style.borderColor = 'var(--accent-gold)';
                    }
                });
        }
    });
    </script>
</body>
</html>