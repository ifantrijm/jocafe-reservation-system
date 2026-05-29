<?php
// 1. Koneksi ke database
include "../config/koneksi.php"; 

if (isset($_POST['ajukan_event'])) {
    
    // 2. Ambil data dari form
    $nama        = mysqli_real_escape_string($conn, $_POST['nama_pendaftar']);
    $email       = mysqli_real_escape_string($conn, $_POST['email']);
    $no_telp     = mysqli_real_escape_string($conn, $_POST['no_telp']);
    $jenis_event = mysqli_real_escape_string($conn, $_POST['jenis_event']);
    $tgl_event   = $_POST['tanggal_event'];
    $jam_event   = $_POST['jam_event'];
    
    // Cegah milih tanggal yang udah lewat (Validasi Backend)
    $hari_ini = date('Y-m-d');
    if ($tgl_event < $hari_ini) {
        echo "<script>
                alert('Maaf, Anda tidak bisa memilih tanggal yang sudah berlalu!');
                window.history.back();
              </script>";
        exit;
    }

    // --- TAHAP 1: DAFTAR PELANGGAN OTOMATIS ---
    $cekPelanggan = mysqli_query($conn, "SELECT id_pelanggan FROM pelanggan WHERE telepon = '$no_telp'");
    
    if (mysqli_num_rows($cekPelanggan) > 0) {
        $data = mysqli_fetch_assoc($cekPelanggan);
        $id_pelanggan = $data['id_pelanggan'];
    } else {
        // Simpan data pelanggan baru
        mysqli_query($conn, "INSERT INTO pelanggan (nama, email, telepon) VALUES ('$nama', '$email', '$no_telp')");
        $id_pelanggan = mysqli_insert_id($conn);
    }

// --- TAHAP 2: SIMPAN KE TABEL RESERVASI_EVENT ---
    // Ubah dari 'pending' menjadi 'on progres'
    $sql = "INSERT INTO reservasi_event (id_pelanggan, tanggal_event, jam_event, no_telp, jenis_event, status_booking) 
            VALUES ('$id_pelanggan', '$tgl_event', '$jam_event', '$no_telp', '$jenis_event', 'on progres')";

    if (mysqli_query($conn, $sql)) {
        $id_event = mysqli_insert_id($conn);
        echo "<script> window.location='nota_event.php?id=$id_event';</script>";
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
    <title>Reservasi Event | Jo Cafe</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root { --bg-main: #13171c; --bg-card: #1c2128; --text-main: #ffffff; --text-muted: #a0aab5; --accent-gold: #f89d13; --border-dark: rgba(255, 255, 255, 0.1); }
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: var(--bg-main); color: var(--text-main); padding-top: 80px; padding-bottom: 50px; }
        .form-card { background-color: var(--bg-card); border: 1px solid var(--border-dark); border-radius: 16px; padding: 40px; box-shadow: 0 15px 35px rgba(0,0,0,0.5); }
        .form-label { color: var(--text-muted); font-size: 0.9rem; font-weight: 600; }
        .form-control, .form-select { background-color: rgba(255, 255, 255, 0.05); border: 1px solid rgba(255, 255, 255, 0.1); color: white; border-radius: 8px; padding: 12px; }
        .btn-gold { background-color: var(--accent-gold); color: white; font-weight: 700; padding: 14px; border-radius: 8px; text-transform: uppercase; letter-spacing: 1px; border: none; transition: 0.3s; width: 100%; }
        .section-title { font-weight: 800; border-left: 4px solid var(--accent-gold); padding-left: 15px; margin-bottom: 25px; color: var(--text-main); }
        .info-box { background: rgba(248, 157, 19, 0.05); border: 1px dashed var(--accent-gold); border-radius: 10px; padding: 15px; margin-bottom: 25px; }
        .form-card input {border-color:var(--accent-gold);}
    </style>
</head>
<body>

<?php include"../include/navbar.php" ?>

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="text-center mb-5">
                    <h2 class="fw-bold">Reservasi Event Khusus</h2>
                    <p class="" style="color:var(--accent-gold);">Rencanakan momen spesialmu di Jo Cafe.</p>
                </div>

                <div class="form-card">
                    <form action="" method="POST">
                        <div class="info-box">
                            <p class="small mb-0 text-warning">
                                <i class="fas fa-info-circle me-2"></i> Untuk Pembayaran Dilakukan Di tempat.
                            </p>
                        </div>

                        
                        <div class="alert  border-warning text-warning" style="">
                            <i class="fas fa-lightbulb text-white me-2"></i> 
                            <small><strong>Pernah reservasi sebelumnya?</strong> Masukkan nomor WhatsApp Anda terlebih dahulu, maka Nama dan Email akan terisi otomatis!</small>
                        </div>
                        
                        <h5 class="section-title">Data Pendaftar</h5>
                        <div class="mb-3 ">
                            <label class="form-label">WhatsApp <span style="color: red;">*</span></label>
                            <input type="text" id="no_telp" name="no_telp" class="form-control" required>
                            <small id="status_pelanggan" class="text-success fw-bold mt-1" style="display: none;"></small>
                        </div>

                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <label class="form-label">Nama Lengkap / Instansi <span style="color: red;">*</span></label>
                                <input type="text" id="nama_pendaftar" name="nama_pendaftar" class="form-control" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Email Aktif</label>
                                <input type="email" id="email_pendaftar" name="email" class="form-control">
                            </div>
                        </div>

                        <h5 class="section-title">Detail Acara</h5>
                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Jenis Event <span style="color: red;">*</span></label>
                                <select name="jenis_event" class="form-select" style="background-color: var(--bg-card); border-color:var(--accent-gold);" required>
                                    <option value="Birthday">Ulang Tahun</option>
                                    <option value="Meeting">Rapat / Gathering</option>
                                    <option value="Prewedding">Pre-Wedding</option>
                                    <option value="Other">Lainnya</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Tanggal Pelaksanaan <span style="color: red;">*</span></label>
                                <input type="date" id="tanggal_event" name="tanggal_event" class="form-control" min="<?php echo date('Y-m-d'); ?>" required>
                                <div id="info_jadwal" class="mt-2 p-2 rounded" style="display: none; background: rgba(0,0,0,0.2);"></div>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label">Jam Pelaksanaan <span style="color: red;">*</span></label>
                            <input type="time" name="jam_event" class="form-control" required>
                        </div>

                        <button type="submit" name="ajukan_event" class="btn btn-gold">Ajukan Reservasi Event</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

<script>
document.getElementById('tanggal_event').addEventListener('change', function() {
    let tglPilihan = this.value;
    let infoBox = document.getElementById('info_jadwal');

    // Kosongkan info jika user hapus tanggal
    if(!tglPilihan) {
        infoBox.style.display = 'none';
        return;
    }

    // Ambil data antrean
    fetch('cek_jadwal.php?tgl=' + tglPilihan)
        .then(response => response.text())
        .then(jumlah => {
            infoBox.style.display = 'block';
            
            if (parseInt(jumlah) > 0) {
                infoBox.className = "mt-2 p-2 rounded small fw-bold text-warning border border-warning";
                infoBox.innerHTML = '<i class="fas fa-exclamation-triangle"></i> Sudah ada ' + jumlah + ' reservasi di tanggal ini.';
            } else {
                infoBox.className = "mt-2 p-2 rounded small fw-bold text-success border border-success";
                infoBox.innerHTML = '<i class="fas fa-check-circle"></i> Jadwal kosong. Cocok untuk eventmu!';
            }
        })
        .catch(error => console.error('Gagal memuat antrean:', error));
});
</script>

<script>
// Fitur Auto-Fill Data Pelanggan berdasarkan No WA
document.getElementById('no_telp').addEventListener('blur', function() {
    let noWa = this.value;
    let inputNama = document.getElementById('nama_pendaftar');
    let inputEmail = document.getElementById('email_pendaftar');
    let statusTeks = document.getElementById('status_pelanggan');

    // Hanya proses kalau nomor WA tidak kosong
    if (noWa.trim() !== "") {
        fetch('cek_pelanggan.php?telp=' + noWa)
            .then(response => response.json())
            .then(data => {
                if (data.status === 'ketemu') {
                    // Isi otomatis kolom nama dan email
                    inputNama.value = data.nama;
                    inputEmail.value = data.email;
                    
                    // Munculkan notif sukses
                    statusTeks.innerHTML = '<i class="fas fa-check-circle"></i> Data pelanggan ditemukan! Terisi otomatis.';
                    statusTeks.style.display = 'block';
                    
                    // Efek visual biar pelanggan sadar datanya berubah
                    inputNama.style.borderColor = '#0dcaf0';
                    inputEmail.style.borderColor = '#0dcaf0';
                } else {
                    // Kosongkan notif kalau nomor baru
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