<?php
// 1. KONEKSI DATABASE
include_once "../config/koneksi.php";

// 2. LOGIKA TAMBAH DATA AREA
if (isset($_POST['tambah'])) {
    $nama = mysqli_real_escape_string($conn, $_POST['nama_area']);
    $kapasitas = mysqli_real_escape_string($conn, $_POST['kapasitas']);
    
    $nama_file = ""; 
    
    // Proses Upload File Fisik
    if (isset($_FILES['gambar']['name']) && $_FILES['gambar']['name'] != '') {
        $nama_file = time() . "_" . basename($_FILES['gambar']['name']); 
        $tmp_file = $_FILES['gambar']['tmp_name'];
        
        // JALUR DIPERBAIKI: Tambah ../ 
        $direktori = "../assets/img/room/"; 
        
        // Pindahkan file dari memori sementara ke folder assets
        move_uploaded_file($tmp_file, $direktori . $nama_file);
    }
    
    // Insert ke database
    $query = "INSERT INTO room (id_admin, id_detail_reservasi, nama_area, kapasitas, gambar, status) 
              VALUES (NULL, NULL, '$nama', '$kapasitas', '$nama_file', 'Tersedia')";
              
    mysqli_query($conn, $query);
    // REDIRECT DIPERBAIKI
    header("Location: admin.php?page=room");
    exit;
}

// 3. LOGIKA UPDATE DATA AREA
if (isset($_POST['update'])) {
    $id = $_POST['id_room'];
    $nama = mysqli_real_escape_string($conn, $_POST['nama_area']);
    $kapasitas = mysqli_real_escape_string($conn, $_POST['kapasitas']);
    $status = $_POST['status'];
    $gambar_lama = $_POST['gambar_lama'];
    
    $nama_file = $gambar_lama; 
    
    // Kalau admin upload foto baru
    if (isset($_FILES['gambar_baru']['name']) && $_FILES['gambar_baru']['name'] != '') {
        $nama_file = time() . "_" . basename($_FILES['gambar_baru']['name']);
        $tmp_file = $_FILES['gambar_baru']['tmp_name'];
        // JALUR DIPERBAIKI: Tambah ../
        $direktori = "../assets/img/room/";
        
        move_uploaded_file($tmp_file, $direktori . $nama_file);
        
        // Hapus foto lama biar hemat storage
        if (!empty($gambar_lama) && file_exists($direktori . $gambar_lama)) {
            unlink($direktori . $gambar_lama);
        }
    }
    
    mysqli_query($conn, "UPDATE room SET nama_area='$nama', kapasitas='$kapasitas', status='$status', gambar='$nama_file' WHERE id_room='$id'");
    // REDIRECT DIPERBAIKI
    header("Location: admin.php?page=room");
    exit;
}

// 4. LOGIKA HAPUS DATA AREA
if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];
    
    // Hapus file fisik dulu
    $cek_foto = mysqli_query($conn, "SELECT gambar FROM room WHERE id_room = '$id'");
    $data_foto = mysqli_fetch_assoc($cek_foto);
    // JALUR DIPERBAIKI: Tambah ../
    if (!empty($data_foto['gambar']) && file_exists("../assets/img/room/" . $data_foto['gambar'])) {
        unlink("../assets/img/room/" . $data_foto['gambar']);
    }

    // Baru hapus data dari database
    mysqli_query($conn, "DELETE FROM room WHERE id_room = '$id'");
    // REDIRECT DIPERBAIKI
    header("Location: admin.php?page=room");
    exit;
}
?>

<style>
    .room-content { font-family: 'Plus Jakarta Sans', sans-serif; color: white; }
    .header-title { font-weight: 800; font-size: 2.2rem; margin-bottom: 0; }
    .management-card { background-color: #111826; border: 1px solid #1f2937; border-radius: 12px; padding: 35px; margin-top: 30px; }
    .table { color: white; }
    .table thead th { color: #f89b1c; border-bottom: 2px solid #1f2937; padding: 15px 10px; background-color: transparent; }
    .table tbody td { padding: 20px 10px; border-bottom: 1px solid #1f2937; vertical-align: middle; background-color: transparent; }
    
    .badge-status { padding: 6px 14px; border-radius: 6px; font-size: 0.8rem; font-weight: 600; }
    .bg-available { background-color: rgba(39, 174, 96, 0.15); color: #2ecc71; border: 1px solid rgba(39, 174, 96, 0.3); }
    .bg-booked { background-color: rgba(231, 76, 60, 0.15); color: #e74c3c; border: 1px solid rgba(231, 76, 60, 0.3); }

    .btn-jo { background-color: #f89b1c; color: #ffffff; font-weight: 600; border: none; border-radius: 6px; padding: 10px 24px; transition: all 0.2s; }
    .btn-jo:hover { background-color: #e08915; color: #ffffff; }

    .btn-action { font-weight: 500; font-size: 0.85rem; padding: 6px 12px; border-radius: 4px; text-decoration: none; border: none; cursor: pointer;}
    .btn-edit { color: #f89b1c; border: 1px solid rgba(248, 155, 28, 0.3); background: rgba(248, 155, 28, 0.05); }
    .btn-delete { color: #e74c3c; border: 1px solid rgba(231, 76, 60, 0.3); background: rgba(231, 76, 60, 0.05); margin-left: 8px; }

    .form-control, .form-select { background-color: #0f1520; border: 1px solid #1f2937; color: #fff; padding: 12px 15px; border-radius: 6px; }
    .form-control:focus, .form-select:focus { background-color: #0f1520; border-color: #f89b1c; color: #fff; box-shadow: none; }
    
    .form-control::file-selector-button {
        background-color: #f89b1c; color: black; border: none; padding: 8px 16px; border-radius: 4px; font-weight: 600; margin-right: 15px; cursor: pointer; transition: 0.2s;
    }
    .form-control::file-selector-button:hover { background-color: white; }

    .modal-content { background-color: #111826; border: 1px solid #1f2937; border-radius: 12px; color: white;}
    .modal-header { border-bottom: 1px solid #1f2937; }
    .modal-footer { border-top: 1px solid #1f2937; }
    .modal-title { font-weight: 700; color: #f89b1c; }
    .text-label { color: #8b95a5; font-size: 0.85rem; font-weight: 500; margin-bottom: 6px; display: block; }
</style>

<div class="container room-content" style="max-width: 1050px;">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h1 class="header-title">Management <span style="color: #f89b1c;">Room</span></h1>
            <div class="header-subtitle mt-1 text-muted">Sistem Informasi Pengelolaan Area & Meja Jo Cafe</div>
        </div>
        <button class="btn btn-jo" data-bs-toggle="modal" data-bs-target="#addModal">
            + Tambah Area
        </button>
    </div>

    <div class="management-card">
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th style="width: 15%;">FOTO</th>
                        <th style="width: 25%;">NAMA AREA</th>
                        <th class="text-center" style="width: 15%;">KAPASITAS</th>
                        <th class="text-center" style="width: 20%;">STATUS</th>
                        <th class="text-end" style="width: 25%;">AKSI</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $res = mysqli_query($conn, "SELECT * FROM room");
                    while ($row = mysqli_fetch_assoc($res)) : 
                        $is_avail = ($row['status'] == 'Tersedia');
                        $badge_class = $is_avail ? 'bg-available' : 'bg-booked';
                        
                        // JALUR FOTO DIPERBAIKI: Tambah ../ 
                        $foto = !empty($row['gambar']) ? "../assets/img/room/".$row['gambar'] : "https://via.placeholder.com/100x70?text=No+Image";
                    ?>
                    <tr>
                        <td>
                            <img src="<?= $foto; ?>" alt="Foto" style="width: 80px; height: 60px; object-fit: cover; border-radius: 6px; border: 1px solid #1f2937; background-color: #0f1520;">
                        </td>
                        <td>
                            <div style="font-weight: 600; font-size: 1.05rem; color:white;"><?= htmlspecialchars($row['nama_area']); ?></div>
                        </td>
                        <td class="text-center">
                            <span style="font-weight: 500; color:white" ><?= $row['kapasitas']; ?> Orang</span>
                        </td>
                        <td class="text-center">
                            <span class="badge-status <?= $badge_class; ?>"><?= $row['status']; ?></span>
                        </td>
                        <td class="text-end">
                            <button type="button" class="btn-action btn-edit" data-bs-toggle="modal" data-bs-target="#editModal<?= $row['id_room']; ?>">Edit</button>
                            <a href="admin.php?page=room&hapus=<?= $row['id_room']; ?>" class="btn-action btn-delete" onclick="return confirm('Hapus area ini?')">Hapus</a>
                        </td>
                    </tr>

                    <div class="modal fade" id="editModal<?= $row['id_room']; ?>" tabindex="-1">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <form action="admin.php?page=room" method="POST" enctype="multipart/form-data">
                                    <div class="modal-header p-4 pb-3">
                                        <h5 class="modal-title">Edit Data Area</h5>
                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body p-4">
                                        <input type="hidden" name="id_room" value="<?= $row['id_room']; ?>">
                                        <input type="hidden" name="gambar_lama" value="<?= $row['gambar']; ?>">
                                        
                                        <div class="mb-4">
                                            <label class="text-label">NAMA AREA</label>
                                            <input type="text" name="nama_area" value="<?= htmlspecialchars($row['nama_area']); ?>" class="form-control" required>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-6 mb-3">
                                                <label class="text-label">KAPASITAS</label>
                                                <input type="number" name="kapasitas" value="<?= $row['kapasitas']; ?>" class="form-control" required>
                                            </div>
                                            <div class="col-sm-6 mb-3">
                                                <label class="text-label">STATUS</label>
                                                <select name="status" class="form-select">
                                                    <option value="Tersedia" <?= ($is_avail) ? 'selected' : ''; ?>>Tersedia</option>
                                                    <option value="Dibooking" <?= (!$is_avail) ? 'selected' : ''; ?>>Dibooking</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <label class="text-label">GANTI FOTO AREA (Opsional)</label>
                                            <input type="file" name="gambar_baru" accept="image/*" class="form-control">
                                        </div>
                                    </div>
                                    <div class="modal-footer p-4 pt-3">
                                        <button type="submit" name="update" class="btn btn-jo w-100">Simpan Perubahan</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="modal fade" id="addModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form action="admin.php?page=room" method="POST" enctype="multipart/form-data">
                <div class="modal-header p-4 pb-3">
                    <h5 class="modal-title">Tambah Area Baru</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="mb-4">
                        <label class="text-label">NAMA AREA</label>
                        <input type="text" name="nama_area" class="form-control" placeholder="Contoh: Meja VIP 306" required>
                    </div>
                    <div class="mb-4">
                        <label class="text-label">KAPASITAS (ORANG)</label>
                        <input type="number" name="kapasitas" class="form-control" placeholder="Masukkan jumlah" required>
                    </div>
                    <div class="mb-3">
                        <label class="text-label">UPLOAD FOTO AREA</label>
                        <input type="file" name="gambar" accept="image/*" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer p-4 pt-3">
                    <button type="submit" name="tambah" class="btn btn-jo w-100">Tambahkan Area</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>