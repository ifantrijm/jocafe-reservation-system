<?php
// Pastikan session sudah berjalan
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 1. KONEKSI DATABASE
require "../config/koneksi.php";

// FUNGSI KOMPRESI & RESIZE GAMBAR
function compressImage($source, $destination, $quality) {
    $info = getimagesize($source);
    if ($info['mime'] == 'image/jpeg') $image = imagecreatefromjpeg($source);
    elseif ($info['mime'] == 'image/png') $image = imagecreatefrompng($source);
    elseif ($info['mime'] == 'image/webp') $image = imagecreatefromwebp($source);
    else return false;

    // Resize (Lebar maks 800px agar tampilan room tetap proporsional)
    $width = $info[0];
    $height = $info[1];
    $new_width = 800; 
    $new_height = ($height / $width) * $new_width;
    
    $tmp = imagecreatetruecolor($new_width, $new_height);
    imagecopyresampled($tmp, $image, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
    
    imagejpeg($tmp, $destination, $quality);
    imagedestroy($image);
    imagedestroy($tmp);
    return $destination;
}

// 2. LOGIKA TAMBAH DATA AREA
if (isset($_POST['tambah'])) {
    $nama = mysqli_real_escape_string($conn, $_POST['nama_area']);
    $kapasitas = mysqli_real_escape_string($conn, $_POST['kapasitas']);
    $id_admin = $_SESSION['id_admin'] ?? 'NULL';
    $nama_file = ""; 
    
    if (isset($_FILES['gambar']['name']) && $_FILES['gambar']['name'] != '') {
        $nama_file = time() . "_" . basename($_FILES['gambar']['name']); 
        $direktori = "../assets/img/room/"; 
        $path = $direktori . $nama_file;
        
        if (!is_dir($direktori)) { mkdir($direktori, 0777, true); }
        
        if (move_uploaded_file($_FILES['gambar']['tmp_name'], $path)) {
            compressImage($path, $path, 70); // Kompres kualitas 70%
        }
    }
    
    mysqli_query($conn, "INSERT INTO room (id_admin, nama_area, kapasitas, gambar, status) VALUES ($id_admin, '$nama', '$kapasitas', '$nama_file', 'Tersedia')");
    
    echo "<script>window.location.href='admin.php?page=room';</script>";
    exit;
}

// 3. LOGIKA UPDATE DATA AREA
if (isset($_POST['update'])) {
    $id = $_POST['id_room'];
    $nama = mysqli_real_escape_string($conn, $_POST['nama_area']);
    $kapasitas = mysqli_real_escape_string($conn, $_POST['kapasitas']);
    $status = $_POST['status'];
    $gambar_lama = $_POST['gambar_lama'];
    
    $id_admin = $_SESSION['id_admin'] ?? 'NULL';
    $nama_file = $gambar_lama; 
    
    if (isset($_FILES['gambar_baru']['name']) && $_FILES['gambar_baru']['name'] != '') {
        $nama_file = time() . "_" . basename($_FILES['gambar_baru']['name']);
        $direktori = "../assets/img/room/";
        $path = $direktori . $nama_file;
        
        if (move_uploaded_file($_FILES['gambar_baru']['tmp_name'], $path)) {
            compressImage($path, $path, 70); // Kompres kualitas 70%
            
            if (!empty($gambar_lama) && file_exists($direktori . $gambar_lama)) {
                unlink($direktori . $gambar_lama);
            }
        }
    }
    
    mysqli_query($conn, "UPDATE room SET id_admin=$id_admin, nama_area='$nama', kapasitas='$kapasitas', status='$status', gambar='$nama_file' WHERE id_room='$id'");
    
    echo "<script>window.location.href='admin.php?page=room';</script>";
    exit;
}

// 4. LOGIKA HAPUS DATA AREA
if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];
    $cek_foto = mysqli_query($conn, "SELECT gambar FROM room WHERE id_room = '$id'");
    $data_foto = mysqli_fetch_assoc($cek_foto);
    
    if (!empty($data_foto['gambar']) && file_exists("../assets/img/room/" . $data_foto['gambar'])) {
        unlink("../assets/img/room/" . $data_foto['gambar']);
    }

    mysqli_query($conn, "DELETE FROM room WHERE id_room = '$id'");
    echo "<script>window.location.href='admin.php?page=room';</script>";
    exit;
}
?>

<style>
    .room-content { font-family: 'Plus Jakarta Sans', sans-serif; color: white; }
    .management-card { background-color: #111826; border: 1px solid #1f2937; border-radius: 12px; padding: 35px; margin-top: 30px; }
    .table { color: white; width: 100%; border-collapse: collapse;}
    .table thead th { color: #f89b1c; border-bottom: 2px solid #1f2937; padding: 15px 10px; background-color: transparent; text-align: left; }
    .table tbody td { padding: 20px 10px; border-bottom: 1px solid #1f2937; vertical-align: middle; background-color: transparent; }
    
    .badge-status { padding: 6px 14px; border-radius: 6px; font-size: 0.8rem; font-weight: 600; display: inline-block;}
    .bg-available { background-color: rgba(39, 174, 96, 0.15); color: #2ecc71; border: 1px solid rgba(39, 174, 96, 0.3); }
    .bg-booked { background-color: rgba(231, 76, 60, 0.15); color: #e74c3c; border: 1px solid rgba(231, 76, 60, 0.3); }

    .btn-jo { background-color: #f89b1c; color: #ffffff; font-weight: 600; border: none; border-radius: 6px; padding: 10px 24px; transition: all 0.2s; cursor: pointer; }
    .btn-jo:hover { background-color: #e08915; color: #ffffff; }

    .btn-action { font-weight: 500; font-size: 0.85rem; padding: 6px 12px; border-radius: 4px; text-decoration: none; border: none; cursor: pointer;}
    .btn-edit { color: #f89b1c; border: 1px solid rgba(248, 155, 28, 0.3); background: rgba(248, 155, 28, 0.05); }
    .btn-delete { color: #e74c3c; border: 1px solid rgba(231, 76, 60, 0.3); background: rgba(231, 76, 60, 0.05); margin-left: 8px; }

    .form-control, .form-select { width: 100%; background-color: #0f1520; border: 1px solid #1f2937; color: #fff; padding: 12px 15px; border-radius: 6px; box-sizing: border-box;}
    .form-control:focus, .form-select:focus { background-color: #0f1520; border-color: #f89b1c; color: #fff; box-shadow: none; outline: none; }
    
    .form-control::file-selector-button {
        background-color: #f89b1c; color: black; border: none; padding: 8px 16px; border-radius: 4px; font-weight: 600; margin-right: 15px; cursor: pointer; transition: 0.2s;
    }
    .form-control::file-selector-button:hover { background-color: white; }

    .modal-content { background-color: #111826; border: 1px solid #1f2937; border-radius: 12px; color: white;}
    .modal-header { border-bottom: 1px solid #1f2937; display: flex; justify-content: space-between; align-items: center;}
    .modal-footer { border-top: 1px solid #1f2937; display: flex;}
    .modal-title { font-weight: 700; color: #f89b1c; margin: 0; }
    .text-label { color: #8b95a5; font-size: 0.85rem; font-weight: 500; margin-bottom: 6px; display: block; margin-top: 10px;}
    .btn-close-white { background: transparent; border: 0; color: white; font-size: 1.2rem; cursor: pointer; }

    @media (max-width: 768px) {
        .header-title { font-size: 1.8rem; }
        .management-card, .testimoni-wrapper { padding: 15px; } /* Kurangi padding biar gak sempit */
        .table { min-width: 800px; } /* Paksa tabel bisa discroll pakai jari */
        .d-flex.justify-content-between { flex-direction: column; gap: 15px; text-align: center; }
        .btn-jo { width: 100%; }
    }
</style>

<div class="container room-content" style="max-width: 1050px; margin-top: 20px;">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h2 class="title fw-bold">Management <span style="color: #f89b1c;">Room</span></h2>
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
                    $res = mysqli_query($conn, "SELECT * FROM room ORDER BY id_room DESC");
                    while ($row = mysqli_fetch_assoc($res)) : 
                        $is_avail = ($row['status'] == 'Tersedia');
                        $badge_class = $is_avail ? 'bg-available' : 'bg-booked';
                        
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

                    <div class="modal fade" id="editModal<?= $row['id_room']; ?>" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <form action="" method="POST" enctype="multipart/form-data">
                                    <div class="modal-header p-4">
                                        <h5 class="modal-title">Edit Data Area</h5>
                                        <button type="button" class="btn-close-white" data-bs-dismiss="modal">×</button>
                                    </div>
                                    <div class="modal-body p-4 text-start">
                                        <input type="hidden" name="id_room" value="<?= $row['id_room']; ?>">
                                        <input type="hidden" name="gambar_lama" value="<?= $row['gambar']; ?>">
                                        
                                        <div class="mb-3">
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
                                                    <option value="Tersedia" <?= ($row['status'] == 'Tersedia') ? 'selected' : ''; ?>>Tersedia</option>
                                                    <option value="Dipesan" <?= ($row['status'] == 'Dipesan') ? 'selected' : ''; ?>>Dipesan</option>
                                                    <option value="Dibooking" <?= ($row['status'] == 'Dibooking') ? 'selected' : ''; ?>>Dibooking</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="mb-2">
                                            <label class="text-label">GANTI FOTO AREA (Opsional)</label>
                                            <input type="file" name="gambar_baru" accept="image/*" class="form-control">
                                        </div>
                                    </div>
                                    <div class="modal-footer p-4">
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

<div class="modal fade" id="addModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form action="" method="POST" enctype="multipart/form-data">
                <div class="modal-header p-4">
                    <h5 class="modal-title">Tambah Area Baru</h5>
                    <button type="button" class="btn-close-white" data-bs-dismiss="modal">×</button>
                </div>
                <div class="modal-body p-4 text-start">
                    <div class="mb-3">
                        <label class="text-label">NAMA AREA</label>
                        <input type="text" name="nama_area" class="form-control" placeholder="Contoh: Meja VIP 306" required>
                    </div>
                    <div class="mb-3">
                        <label class="text-label">KAPASITAS (ORANG)</label>
                        <input type="number" name="kapasitas" class="form-control" placeholder="Masukkan jumlah" required>
                    </div>
                    <div class="mb-2">
                        <label class="text-label">UPLOAD FOTO AREA</label>
                        <input type="file" name="gambar" accept="image/*" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer p-4">
                    <button type="submit" name="tambah" class="btn btn-jo w-100">Tambahkan Area</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>