<?php
// 1. KONEKSI DATABASE
$conn = mysqli_connect("localhost", "root", "", "jocafee");
if (!$conn) { die("Koneksi Gagal: " . mysqli_connect_error()); }

// --- FITUR EDIT: AMBIL DATA LAMA ---
$is_edit = false;
$edit_data = ['id_gallery' => '', 'kategori' => '', 'gambar' => '', 'tanggal' => ''];

if (isset($_GET['edit'])) {
    $is_edit = true;
    $id_edit = $_GET['edit'];
    $result = mysqli_query($conn, "SELECT * FROM gallery WHERE id_gallery = '$id_edit'");
    $edit_data = mysqli_fetch_assoc($result);
}

// 2. SIMPAN / UPDATE DATA KE DATABASE
if (isset($_POST['simpan'])) {
    $kategori = $_POST['kategori'];
    $tanggal = $_POST['tanggal'];
    $id_target = $_POST['id_gallery']; 
    $nama_file = $_POST['gambar_lama']; 
    
    // Upload gambar baru
    if (!empty($_FILES['gambar']['tmp_name'])) {
        $nama_file = time() . "_" . basename($_FILES['gambar']['name']);
        $direktori = "assets/img/gallery/";
        
        if (!is_dir($direktori)) { mkdir($direktori, 0777, true); }

        if (move_uploaded_file($_FILES['gambar']['tmp_name'], $direktori . $nama_file)) {
            if (!empty($_POST['gambar_lama']) && file_exists($direktori . $_POST['gambar_lama'])) {
                unlink($direktori . $_POST['gambar_lama']);
            }
        }
    }

    if ($id_target) {
        // Query Update sesuai struktur SQL
        $query = "UPDATE gallery SET 
                    kategori = '$kategori', 
                    tanggal = '$tanggal',
                    gambar = '$nama_file' 
                  WHERE id_gallery = '$id_target'";
    } else {
        // Query Insert (id_admin diset NULL seperti di dump file)
        $query = "INSERT INTO gallery (id_admin, gambar, kategori, tanggal) 
                  VALUES (NULL, '$nama_file', '$kategori', '$tanggal')";
    }
    
    mysqli_query($conn, $query);
    header("Location: form_galeri.php");
    exit;
}

// 3. LOGIKA HAPUS
if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];
    $cek = mysqli_query($conn, "SELECT gambar FROM gallery WHERE id_gallery = '$id'");
    $data = mysqli_fetch_assoc($cek);
    if (!empty($data['gambar']) && file_exists("assets/img/gallery/" . $data['gambar'])) {
        unlink("assets/img/gallery/" . $data['gambar']);
    }
    mysqli_query($conn, "DELETE FROM gallery WHERE id_gallery = '$id'");
    header("Location: form_galeri.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Management Gallery - Jo Cafe</title>
    <style>
        body { margin:0; font-family: Arial; background:#13171c; color:white; }
        .content { padding:20px; max-width:1100px; margin:auto; }
        .back { display:inline-block; margin-bottom:15px; padding:8px 15px; background:#f89d13; color:black; text-decoration:none; border-radius:6px; font-weight:bold; }
        .title { font-size:24px; margin-bottom:20px; }
        .grid { display:flex; gap:20px; align-items:flex-start; }
        .card { background:#1c2128; padding:20px; border-radius:10px; border: 1px solid #444; }
        .form { width:320px; }
        table { width:100%; border-collapse:collapse; margin-top:10px; }
        td, th { padding:10px; border-bottom:1px solid #444; text-align: left; }
        input, select { width:100%; padding:10px; margin:8px 0; background:#13171c; color:white; border:1px solid #444; border-radius:6px; box-sizing: border-box; }
        button { width:100%; padding:10px; background:#f89d13; border:none; cursor:pointer; border-radius:6px; font-weight:bold; margin-top: 10px; }
        img { width:80px; height:50px; object-fit:cover; border-radius:5px; }
        .btn-del { color: #ff4d4d; text-decoration: none; font-size: 13px; font-weight: bold; margin-left: 10px; }
        .btn-edit { color: #f89d13; text-decoration: none; font-size: 13px; font-weight: bold; }
    </style>
</head>
<body>

<div class="content">
    <!-- <a href="../dashboard/admin.php" class="back">← Kembali</a> -->
    <div class="title">Management Gallery</div>

    <div class="grid">
        <div class="card form">
            <h3 style="color: #f89d13; margin-top:0;"><?php echo $is_edit ? "Edit Foto" : "Tambah Foto"; ?></h3>
            <form method="POST" enctype="multipart/form-data">
                <input type="hidden" name="id_gallery" value="<?php echo $edit_data['id_gallery']; ?>">
                <input type="hidden" name="gambar_lama" value="<?php echo $edit_data['gambar']; ?>">

                <label style="font-size: 12px; color: #aaa;">KATEGORI (SESUAI DATABASE)</label>
                <select name="kategori" required>
                    <option value="makanan" <?php if($edit_data['kategori'] == 'makanan') echo 'selected'; ?>>Makanan</option>
                    <option value="minuman" <?php if($edit_data['kategori'] == 'minuman') echo 'selected'; ?>>Minuman</option>
                </select>

                <label style="font-size: 12px; color: #aaa;">TANGGAL</label>
                <input type="date" name="tanggal" value="<?php echo $edit_data['tanggal']; ?>" required>

                <label style="font-size: 12px; color: #aaa;">FILE GAMBAR</label>
                <input type="file" name="gambar" accept="image/*">

                <button type="submit" name="simpan"><?php echo $is_edit ? "Update Foto" : "Unggah Foto"; ?></button>
                <?php if($is_edit): ?>
                    <a href="form_galeri.php" style="display:block; text-align:center; color:#aaa; font-size:12px; margin-top:10px; text-decoration:none;">Batal</a>
                <?php endif; ?>
            </form>
        </div>

        <div class="card" style="flex:1;">
            <h3 style="margin-top:0; color:white;">Data Gallery</h3>
            <table>
                <thead>
                    <tr class="text-white">
                        <th>Foto</th>
                        <th>Kategori</th>
                        <th>Tanggal</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $get_galeri = mysqli_query($conn, "SELECT * FROM gallery ORDER BY id_gallery DESC");
                    while ($g = mysqli_fetch_assoc($get_galeri)) { 
                        $path = "assets/img/gallery/".$g['gambar'];
                    ?>
                    <tr class="text-white">
                        <td><img src="<?php echo file_exists($path) ? $path : 'https://via.placeholder.com/80x50'; ?>"></td>
                        <td><span style="text-transform: capitalize;"><?php echo $g['kategori']; ?></span></td>
                        <td><?php echo $g['tanggal']; ?></td>
                        <td>
                            <a href="?edit=<?php echo $g['id_gallery']; ?>" class="btn-edit">Edit</a>
                            <a href="?hapus=<?php echo $g['id_gallery']; ?>" class="btn-del" onclick="return confirm('Hapus?')">Hapus</a>
                        </td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

</body>
</html>