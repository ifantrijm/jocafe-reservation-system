<?php
// 1. KONEKSI DATABASE (Gunakan koneksi terpusat)
include_once "../config/koneksi.php";

// ==========================================
// --- FITUR TOMBOL SELESAI DITEKAN (SATUAN) ---
// ==========================================
// Logika untuk menyelesaikan pesanan ROOM
if (isset($_GET['selesai_room']) && isset($_GET['id_kamar'])) {
    $id_res = $_GET['selesai_room'];
    $id_kamar = $_GET['id_kamar'];

    // 1. Kosongkan meja biar bisa dipesan orang lain lagi
    mysqli_query($conn, "UPDATE room SET status = 'Tersedia' WHERE id_room = '$id_kamar'");

    // 2. SOFT DELETE: Jangan hapus data, cukup update status jadi 'Selesai'
    mysqli_query($conn, "UPDATE reservasi_room SET status_pesanan = 'Selesai' WHERE id_reservasi_room = '$id_res'");

    // Refresh halaman
    echo "<script>window.location='admin.php?page=home';</script>";
    exit;
}

// Logika untuk menyelesaikan pesanan EVENT
if (isset($_GET['selesai_event'])) {
    $id_event = $_GET['selesai_event'];

    // SOFT DELETE: Update status jadi 'selesai' (huruf kecil sesuai ENUM baru)
    mysqli_query($conn, "UPDATE reservasi_event SET status_booking = 'selesai' WHERE id_event_res = '$id_event'");

    // Refresh halaman
    echo "<script>window.location='admin.php?page=home';</script>";
    exit;
}

// ==========================================
// --- LOGIKA BULK ACTION (AKSI MASSAL) ---
// ==========================================
// BULK ACTION UNTUK ROOM
if (isset($_POST['bulk_selesai_room'])) {
    if (!empty($_POST['id_rooms'])) {
        $id_rooms = array_map('intval', $_POST['id_rooms']);
        $ids_string = implode(',', $id_rooms);

        // 1. Cari tahu id_room (meja) mana saja yang dipakai, lalu kosongkan
        $get_kamar = mysqli_query($conn, "SELECT id_room FROM reservasi_room WHERE id_reservasi_room IN ($ids_string)");
        $kamar_arr = [];
        while($k = mysqli_fetch_assoc($get_kamar)) {
            $kamar_arr[] = $k['id_room'];
        }
        if (count($kamar_arr) > 0) {
            $kamar_str = implode(',', $kamar_arr);
            mysqli_query($conn, "UPDATE room SET status = 'Tersedia' WHERE id_room IN ($kamar_str)");
        }

        // 2. Soft delete reservasinya
        mysqli_query($conn, "UPDATE reservasi_room SET status_pesanan = 'Selesai' WHERE id_reservasi_room IN ($ids_string)");
        
        echo "<script>alert('Berhasil! Data Room terpilih telah diselesaikan.'); window.location='admin.php?page=home';</script>";
    } else {
        echo "<script>alert('Pilih minimal satu data Room dulu!'); window.history.back();</script>";
    }
    exit;
}

// BULK ACTION UNTUK EVENT
if (isset($_POST['bulk_selesai_event'])) {
    if (!empty($_POST['id_events'])) {
        $id_events = array_map('intval', $_POST['id_events']);
        $ids_string = implode(',', $id_events);
        
        mysqli_query($conn, "UPDATE reservasi_event SET status_booking = 'selesai' WHERE id_event_res IN ($ids_string)");
        
        echo "<script>alert('Berhasil! Data Event terpilih telah diselesaikan.'); window.location='admin.php?page=home';</script>";
    } else {
        echo "<script>alert('Pilih minimal satu data Event dulu!'); window.history.back();</script>";
    }
    exit;
}
// ==========================================

// --- QUERY MENGHITUNG JUMLAH DATA ---
$query_menu = mysqli_query($conn, "SELECT * FROM menu");
$jml_menu = $query_menu ? mysqli_num_rows($query_menu) : 0;

$query_galeri = mysqli_query($conn, "SELECT * FROM gallery");
$jml_galeri = $query_galeri ? mysqli_num_rows($query_galeri) : 0;

$query_blog = mysqli_query($conn, "SELECT * FROM blog");
$jml_blog = $query_blog ? mysqli_num_rows($query_blog) : 0;

$query_testimoni = mysqli_query($conn, "SELECT * FROM testimoni");
$jml_testimoni = $query_testimoni ? mysqli_num_rows($query_testimoni) : 0;

$query_room = mysqli_query($conn, "SELECT * FROM reservasi_room");
$jml_room = $query_room ? mysqli_num_rows($query_room) : 0;

$query_event = mysqli_query($conn, "SELECT * FROM reservasi_event");
$jml_event = $query_event ? mysqli_num_rows($query_event) : 0;
?>

<style>
    .home-content { font-family: 'Plus Jakarta Sans', sans-serif; color: white; padding: 20px;}
    .stat-card {
        background-color: #1c2128;
        border: 1px solid rgba(255, 255, 255, 0.05);
        border-radius: 15px;
        padding: 25px;
        text-align: center;
        transition: all 0.3s ease;
        height: 100%;
    }
    .stat-card:hover {
        border-color: #f89d13;
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.3);
    }
    .stat-icon {
        font-size: 2rem;
        color: #f89d13;
        margin-bottom: 10px;
    }
    .btn-action-home {
        background-color: transparent;
        border: 1px solid #f89d13;
        color: #f89d13;
        padding: 10px 20px;
        border-radius: 8px;
        text-decoration: none;
        display: inline-block;
        transition: 0.3s;
        width: 100%;
        margin-bottom: 10px;
    }
    .btn-action-home:hover {
        background-color: #f89d13;
        color: #13171c;
    }
    .table { border-collapse: separate !important; border-spacing: 0; }
    .table thead th { background: #0f1520 !important; padding: 15px !important; }
</style>

<div class="home-content">
    <h2 class="fw-bold mb-4">Dashboard Overview</h2>
    
    <div class="row g-4 mb-4">
        <div class="col-md-4">
            <div class="stat-card">
                <i class="fas fa-utensils stat-icon"></i>
                <h6 class=" mt-2">Total Data Menu</h6>
                <h2 class="fw-bold"><?php echo $jml_menu; ?></h2>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-card">
                <i class="fas fa-images stat-icon"></i>
                <h6 class=" mt-2">Total Foto Galeri</h6>
                <h2 class="fw-bold"><?php echo $jml_galeri; ?></h2>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-card">
                <i class="fas fa-newspaper stat-icon"></i>
                <h6 class=" mt-2">Total Artikel Blog</h6>
                <h2 class="fw-bold"><?php echo $jml_blog; ?></h2>
            </div>
        </div>
    </div>

    <div class="row g-4 justify-content-center">
        <div class="col-md-4">
            <div class="stat-card">
                <i class="fas fa-star stat-icon"></i>
                <h6 class=" mt-2">Testimoni</h6>
                <h2 class="fw-bold "><?php echo $jml_testimoni; ?></h2>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-card">
                <i class="fas fa-door-open stat-icon"></i>
                <h6 class=" mt-2">Total Reservasi Room</h6>
                <h2 class="fw-bold "><?php echo $jml_room; ?></h2>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-card">
                <i class="fas fa-door-open stat-icon"></i>
                <h6 class=" mt-2">Total Reservasi Event</h6>
                <h2 class="fw-bold "><?php echo $jml_event; ?></h2>
            </div>
        </div>
    </div>

    <hr class="my-5" style="border-color: rgba(255,255,255,0.1);">

    <h4 class="fw-bold mb-3">Quick Actions</h4>
    <div class="row g-4 mb-5">
        <div class="col-md-6">
            <div class="stat-card text-start" style="height: auto;">
                <h5>Manajemen Reservasi</h5>
                <p class="small ">Akses cepat ke form-form yang sudah dibuat.</p>
                <div class="row">
                    <div class="col-6">
                        <a href="admin.php?page=room1" class="btn-action-home text-center"><i class="fas fa-door-open me-2"></i>Cek Room</a>
                    </div>
                    <div class="col-6">
                        <a href="admin.php?page=event" class="btn-action-home text-center"><i class="fas fa-star me-2"></i>Cek Event</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="stat-card text-start" style="height: auto;">
                <h5>Simulasi Pelanggan</h5>
                <p class="small ">Mulai alur dari halaman depan pelanggan.</p>
                <a href="../index.php" target="_blank" class="btn-action-home text-center"><i class="fas fa-external-link-alt me-2"></i>Buka Halaman Utama</a>
            </div>
        </div>
    </div>

    <h4 class="fw-bold mb-3 mt-5 text-warning">Reservasi Room Berjalan</h4>
    <form action="" method="POST">
        <div class="table-responsive">
            <table class="table table-dark table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th style="width: 50px;">
                            <input class="form-check-input border-warning" type="checkbox" id="cekSemuaRoom">
                        </th>
                        <th>ID</th>
                        <th>Pemesan</th>
                        <th>Kontak (WA)</th>
                        <th>Area</th>
                        <th>Kapasitas</th>
                        <th>Tanggal</th>
                        <!-- <th>Status</th> -->
                        <th>Bukti</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $q_room = mysqli_query($conn, "
                        SELECT r_res.*, p.nama, p.telepon, room.nama_area, room.kapasitas 
                        FROM reservasi_room r_res 
                        JOIN pelanggan p ON r_res.id_pelanggan = p.id_pelanggan 
                        JOIN room ON r_res.id_room = room.id_room 
                        WHERE r_res.status_pesanan != 'Selesai'
                        ORDER BY r_res.tanggal_reservasi ASC LIMIT 5
                    ");
                    
                    if(mysqli_num_rows($q_room) > 0) {
                        while ($row = mysqli_fetch_assoc($q_room)) {
                            $no_wa = $row['telepon'];
                            if (substr($no_wa, 0, 1) == '0') { $no_wa = '62' . substr($no_wa, 1); }
                    ?>
                    <tr>
                        <td>
                            <input class="form-check-input cek-room border-secondary" type="checkbox" name="id_rooms[]" value="<?php echo $row['id_reservasi_room']; ?>">
                        </td>
                        <td>#<?php echo $row['id_reservasi_room']; ?></td>
                        <td class="fw-bold"><?php echo htmlspecialchars($row['nama']); ?></td>
                        <td><?php echo $row['telepon']; ?></td>
                        <td><?php echo $row['nama_area']; ?></td>
                        <td><?php echo $row['kapasitas']; ?> org</td>
                        <td><?php echo date('d M Y', strtotime($row['tanggal_reservasi'])); ?></td>
                        <!-- <td>
                            <span class="badge <?php echo ($row['status_pesanan'] == 'Confirmed') ? 'bg-primary' : 'bg-warning text-dark'; ?>">
                                <?php echo $row['status_pesanan']; ?>
                            </span>
                        </td> -->
                        <td>
                            <?php if(!empty($row['bukti_pembayaran'])): ?>
                                <a href="../assets/img/bukti/<?php echo $row['bukti_pembayaran']; ?>" target="_blank" class="btn btn-sm btn-info text-dark fw-bold">
                                    <i class="fas fa-receipt"></i> Cek
                                </a>
                            <?php else: ?>
                                <span class=" small text-muted">Belum ada</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <div class="d-flex gap-2">
                                <a href="https://wa.me/<?php echo $no_wa; ?>" target="_blank" class="btn btn-sm btn-success fw-bold">
                                    <i class="fab fa-whatsapp"></i> WA
                                </a>
                                <a href="admin.php?page=home&selesai_room=<?php echo $row['id_reservasi_room']; ?>&id_kamar=<?php echo $row['id_room']; ?>" 
                                   class="btn btn-sm btn-primary fw-bold" onclick="return confirm('Selesaikan pesanan ini? Data akan masuk ke Master Data.')">
                                   <i class="fas fa-check"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                    <?php } } else { echo '<tr><td colspan="10" class="text-center text-muted py-4">Tidak ada reservasi room berjalan.</td></tr>'; } ?>
                </tbody>
            </table>
        </div>
        
        <div class="d-flex justify-content-between align-items-center mt-3 mb-5">
            <button type="submit" name="bulk_selesai_room" class="btn btn-primary fw-bold" onclick="return confirm('Yakin ingin menyelesaikan semua data Room yang dipilih?')">
                <i class="fas fa-check-double me-2"></i> Selesaikan Terpilih
            </button>
            <a href="admin.php?page=room1" class="btn btn-outline-info text-white">
                <i class="fas fa-database me-2"></i>Cek Histori Master Data Room
            </a>
        </div>
    </form>


    <h4 class="fw-bold mb-3 mt-5 text-warning">Reservasi Event Berjalan</h4>
    <form action="" method="POST">
        <div class="table-responsive">
            <table class="table table-dark table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th style="width: 50px;">
                            <input class="form-check-input border-warning" type="checkbox" id="cekSemuaEvent">
                        </th>
                        <th>ID</th>
                        <th>Pemesan</th>
                        <th>Kontak (WA)</th>
                        <th>Jenis</th>
                        <th>Tanggal & Waktu</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $q_event = mysqli_query($conn, "
                        SELECT e.*, p.nama, p.telepon 
                        FROM reservasi_event e 
                        JOIN pelanggan p ON e.id_pelanggan = p.id_pelanggan 
                        WHERE e.status_booking != 'selesai'
                        ORDER BY e.tanggal_event ASC LIMIT 5
                    ");
                    
                    if(mysqli_num_rows($q_event) > 0) {
                        while ($row = mysqli_fetch_assoc($q_event)) {
                            $no_wa_event = $row['telepon'];
                            if (substr($no_wa_event, 0, 1) == '0') { $no_wa_event = '62' . substr($no_wa_event, 1); }
                    ?>
                    <tr>
                        <td>
                            <input class="form-check-input cek-event border-secondary" type="checkbox" name="id_events[]" value="<?php echo $row['id_event_res']; ?>">
                        </td>
                        <td>#<?php echo $row['id_event_res']; ?></td>
                        <td class="fw-bold"><?php echo htmlspecialchars($row['nama']); ?></td>
                        <td><?php echo $row['telepon']; ?></td>
                        <td><?php echo $row['jenis_event']; ?></td>
                        <td>
                            <?php echo date('d M Y', strtotime($row['tanggal_event'])); ?> <br>
                            <small class="text-warning"><?php echo $row['jam_event']; ?></small>
                        </td>
                        <td>
                            <?php 
                            $warna_badge = 'bg-secondary';
                            if ($row['status_booking'] == 'confirmed') { 
                                $warna_badge = 'bg-success'; 
                            } else if ($row['status_booking'] == 'on progres') { 
                                $warna_badge = 'bg-info text-dark';
                            } else {
                                $warna_badge = 'bg-warning text-dark';
                            }
                            ?>
                            <span class="badge <?php echo $warna_badge; ?> text-uppercase">
                                <?php echo $row['status_booking']; ?>
                            </span>
                        </td>
                        <td>
                            <div class="d-flex gap-2">
                                <a href="https://wa.me/<?php echo $no_wa_event; ?>" target="_blank" class="btn btn-sm btn-success fw-bold">
                                    <i class="fab fa-whatsapp"></i> WA
                                </a>
                                <a href="admin.php?page=home&selesai_event=<?php echo $row['id_event_res']; ?>" 
                                   class="btn btn-sm btn-primary fw-bold" onclick="return confirm('Selesaikan event ini? Data akan masuk ke Master Data.')">
                                   <i class="fas fa-check"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                    <?php } } else { echo '<tr><td colspan="8" class="text-center text-muted py-4">Tidak ada reservasi event berjalan.</td></tr>'; } ?>
                </tbody>
            </table>
        </div>
        
        <div class="d-flex justify-content-between align-items-center mt-3 mb-5">
            <button type="submit" name="bulk_selesai_event" class="btn btn-primary fw-bold" onclick="return confirm('Yakin ingin menyelesaikan semua data Event yang dipilih?')">
                <i class="fas fa-check-double me-2"></i> Selesaikan Terpilih
            </button>
            <a href="admin.php?page=event" class="btn btn-outline-warning text-white">
                <i class="fas fa-database me-2"></i>Cek Histori Master Data Event
            </a>
        </div>
    </form>

</div>

<script>
    // Logika Select All untuk Room
    const cekSemuaRoom = document.getElementById('cekSemuaRoom');
    const cekSatuanRoom = document.querySelectorAll('.cek-room');
    if(cekSemuaRoom) {
        cekSemuaRoom.addEventListener('change', function() {
            cekSatuanRoom.forEach(cb => cb.checked = this.checked);
        });
    }

    // Logika Select All untuk Event
    const cekSemuaEvent = document.getElementById('cekSemuaEvent');
    const cekSatuanEvent = document.querySelectorAll('.cek-event');
    if(cekSemuaEvent) {
        cekSemuaEvent.addEventListener('change', function() {
            cekSatuanEvent.forEach(cb => cb.checked = this.checked);
        });
    }
</script>