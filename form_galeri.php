<?php
// Pastikan session sudah aktif
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

    // Resize (Lebar maks 1000px agar galeri tetap terlihat tajam tapi ringan)
    $width = $info[0];
    $height = $info[1];
    $new_width = 1000; 
    $new_height = ($height / $width) * $new_width;
    
    $tmp = imagecreatetruecolor($new_width, $new_height);
    imagecopyresampled($tmp, $image, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
    
    imagejpeg($tmp, $destination, $quality);
    imagedestroy($image);
    imagedestroy($tmp);
    return $destination;
}

// --- FITUR EDIT: AMBIL DATA LAMA ---
$is_edit = false;
$edit_data = ['id_gallery' => '', 'gambar' => '', 'kategori' => 'room', 'keterangan' => '', 'tanggal' => ''];

if (isset($_GET['edit'])) {
    $is_edit = true;
    $id_edit = $_GET['edit'];
    $result = mysqli_query($conn, "SELECT * FROM gallery WHERE id_gallery = '$id_edit'");
    $edit_data = mysqli_fetch_assoc($result);
}

// 2. SIMPAN / UPDATE DATA KE DATABASE
if (isset($_POST['simpan'])) {
    $kategori = $_POST['kategori'];
    $keterangan = mysqli_real_escape_string($conn, $_POST['keterangan']);
    $tanggal = $_POST['tanggal'];
    $id_target = $_POST['id_gallery']; 
    
    $id_admin = $_SESSION['id_admin'] ?? 'NULL';
    $nama_file = $_POST['gambar_lama']; 
    
    // Cek jika ada upload gambar baru
    if (!empty($_FILES['gambar']['tmp_name'])) {
        $nama_file = time() . "_" . basename($_FILES['gambar']['name']);
        $direktori = "../assets/img/gallery/"; 
        $path = $direktori . $nama_file;
        
        if (move_uploaded_file($_FILES['gambar']['tmp_name'], $path)) {
            // EKSEKUSI KOMPRESI (Kualitas 75 agar galeri tetap jernih)
            compressImage($path, $path, 75);
            
            // Hapus gambar lama jika ada
            if (!empty($_POST['gambar_lama']) && file_exists($direktori . $_POST['gambar_lama'])) {
                unlink($direktori . $_POST['gambar_lama']);
            }
        }
    }

    if ($id_target) {
        $query = "UPDATE gallery SET id_admin = $id_admin, kategori = '$kategori', keterangan = '$keterangan', tanggal = '$tanggal', gambar = '$nama_file' WHERE id_gallery = '$id_target'";
    } else {
        $query = "INSERT INTO gallery (id_admin, gambar, kategori, keterangan, tanggal) VALUES ($id_admin, '$nama_file', '$kategori', '$keterangan', '$tanggal')";
    }
    
    mysqli_query($conn, $query);
    echo "<script>window.location.href='admin.php?page=galeri';</script>";
    exit;
}

// 3. LOGIKA HAPUS (Tetap sama)
if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];
    $cek = mysqli_query($conn, "SELECT gambar FROM gallery WHERE id_gallery = '$id'");
    $data = mysqli_fetch_assoc($cek);
    
    if (!empty($data['gambar']) && file_exists("../assets/img/gallery/" . $data['gambar'])) {
        unlink("../assets/img/gallery/" . $data['gambar']);
    }
    mysqli_query($conn, "DELETE FROM gallery WHERE id_gallery = '$id'");
    echo "<script>window.location.href='admin.php?page=galeri';</script>";
    exit;
}
?>

<style>
    .content-gallery { padding:20px; max-width:1100px; margin:auto; color: white; font-family: 'Plus Jakarta Sans', sans-serif;}
    .title { margin-bottom:20px; font-weight: bold;}
    .stats { margin-bottom:20px; }
    .stat-box { background:#1c2128; padding:20px; border-radius:10px; border: 1px solid #444; }
    .grid { display:flex; gap:20px; align-items:flex-start; }
    .card { background:#1c2128; padding:20px; border-radius:10px; border: 1px solid #444; }
    .form { width:320px; }
    table { width:100%; border-collapse:collapse; margin-top:10px; color: white;}
    td, th { padding:10px; border-bottom:1px solid #444; text-align: left; }
    input, select, textarea { width:100%; padding:10px; margin:8px 0; background:#13171c; color:white; border:1px solid #444; border-radius:6px; box-sizing: border-box; }
    .btn-submit { width:100%; padding:10px; background:#f89d13; border:none; cursor:pointer; border-radius:6px; font-weight:bold; margin-top: 10px; color: black;}
    .img-gallery { width:60px; height:60px; object-fit:cover; border-radius:5px; background: #13171c; }
    .btn-del { background: crimson; color: white; padding: 5px 10px; border-radius: 5px; text-decoration: none; font-size: 12px; font-weight: bold; margin-left: 5px; }
    .btn-edit { background: orange; color: black; padding: 5px 10px; border-radius: 5px; text-decoration: none; font-size: 12px; font-weight: bold; }
    .cancel-edit { display: block; text-align: center; margin-top: 10px; color: #aaa; text-decoration: none; font-size: 12px; }
    @media (max-width: 768px) {
        .grid { flex-direction: column; } /* Bikin form dan tabel jadi atas-bawah */
        .form { width: 100%; position: relative; top: 0; } /* Form menuhi layar */
        .card { width: 100%; overflow-x: auto; box-sizing: border-box; } /* Tabel bisa digeser kiri-kanan */
        table { min-width: 650px; } /* Tabel gak gepeng */
    }
</style>

<div class="content-gallery">
    <h2 class="title fw-bold mb-4">Management <span style="color: #f89d13;">Gallery</span> </h2>

    <div class="stats">
        <div class="stat-box">
            <?php $count = mysqli_query($conn, "SELECT id_gallery FROM gallery"); ?>
            Total Foto Terdaftar: <strong><?php echo mysqli_num_rows($count); ?></strong> Item
        </div>
    </div>

    <div class="grid">
        <div class="card form">
            <h3 style="color: #f89d13; margin-top:0;"><?php echo $is_edit ? "Edit Foto" : "Tambah Foto"; ?></h3>
            <form action="admin.php?page=galeri" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="id_gallery" value="<?php echo $edit_data['id_gallery']; ?>">
                <input type="hidden" name="gambar_lama" value="<?php echo $edit_data['gambar']; ?>">

                <label style="font-size: 12px; color: #aaa;">FOTO PRODUK / MOMEN <?php if($is_edit) echo "(Kosongkan jika tidak ganti)"; ?></label>
                <input type="file" name="gambar" accept="image/*">

                <label style="font-size: 12px; color: #aaa;">KATEGORI FOTO</label>
                <select name="kategori" required>
                    <option value="room" <?php if($edit_data['kategori'] == 'room') echo 'selected'; ?>>Room</option>
                    <option value="event" <?php if($edit_data['kategori'] == 'event') echo 'selected'; ?>>Event</option>
                    <option value="menu" <?php if($edit_data['kategori'] == 'menu') echo 'selected'; ?>>Menu</option>
                </select>

                <label style="font-size: 12px; color: #aaa;">KETERANGAN</label>
                <textarea name="keterangan" placeholder="Tulis deskripsi singkat atau judul foto..." required><?php echo htmlspecialchars($edit_data['keterangan']); ?></textarea>

                <label style="font-size: 12px; color: #aaa;">TANGGAL</label>
                <input type="date" name="tanggal" value="<?php echo $edit_data['tanggal'] ? $edit_data['tanggal'] : date('Y-m-d'); ?>" required>

                <button type="submit" name="simpan" class="btn-submit"><?php echo $is_edit ? "Update Data" : "Simpan ke Galeri"; ?></button>
                
                <?php if($is_edit): ?>
                    <a href="admin.php?page=galeri" class="cancel-edit">Batal Edit / Tambah Baru</a>
                <?php endif; ?>
            </form>
        </div>

        <div class="card" style="flex:1;">
            <h3 style="margin-top:0;">Data Galeri</h3>
            <table>
                <thead>
                    <tr>
                        <th>Foto</th>
                        <th>Keterangan</th>
                        <th>Kategori</th>
                        <th>Tanggal</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $get_gallery = mysqli_query($conn, "SELECT * FROM gallery ORDER BY id_gallery DESC");
                    while ($g = mysqli_fetch_assoc($get_gallery)) { 
                        $path_foto = !empty($g['gambar']) ? "../assets/img/gallery/".$g['gambar'] : "https://via.placeholder.com/60x60?text=No+Img";
                    ?>
                    <tr>
                        <td><img src="<?php echo $path_foto; ?>" alt="Gallery" class="img-gallery"></td>
                        <td>
                            <strong><?php echo htmlspecialchars($g['keterangan']); ?></strong>
                        </td>
                        <td>
                            <span style="text-transform: uppercase; font-size: 11px; background: #232d38; padding: 4px 8px; border-radius: 4px; border: 1px solid #f89d13; color: #f89d13;">
                                <?php echo $g['kategori']; ?>
                            </span>
                        </td>
                        <td><?php echo $g['tanggal'] ? date('d M Y', strtotime($g['tanggal'])) : '-'; ?></td>
                        <td>
                            <a href="admin.php?page=galeri&edit=<?php echo $g['id_gallery']; ?>" class="btn-edit">Edit</a>
                            <a href="admin.php?page=galeri&hapus=<?php echo $g['id_gallery']; ?>" class="btn-del" onclick="return confirm('Hapus foto ini?')">Hapus</a>
                        </td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>