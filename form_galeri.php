<?php
// Pastikan session sudah aktif dari admin.php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 1. KONEKSI DATABASE
$conn = mysqli_connect("localhost", "root", "", "jocafee");
if (!$conn) { die("Koneksi Gagal: " . mysqli_connect_error()); }

// --- TENTUKAN URL INDUK ---
$url_kembali = "admin.php?page=galeri"; 

// --- FITUR EDIT: AMBIL DATA LAMA ---
$is_edit = false;
$edit_data = ['id_gallery' => '', 'keterangan' => '', 'gambar' => '', 'tanggal' => ''];

if (isset($_GET['edit'])) {
    $is_edit = true;
    $id_edit = $_GET['edit'];
    $result = mysqli_query($conn, "SELECT * FROM gallery WHERE id_gallery = '$id_edit'");
    
    // Pastikan data ditemukan sebelum diisi ke form
    if ($result && mysqli_num_rows($result) > 0) {
        $edit_data = mysqli_fetch_assoc($result);
    }
}

// ==========================================
// 2. SIMPAN / UPDATE DATA KE DATABASE
// ==========================================
if (isset($_POST['simpan'])) {
    // Sesuaikan dengan nama kolom baru di database: keterangan
    $keterangan = mysqli_real_escape_string($conn, $_POST['keterangan']);
    $tanggal = $_POST['tanggal'];
    $id_target = $_POST['id_gallery']; 
    
    // Ambil ID Admin yang sedang login untuk log aktivitas
    $id_admin = $_SESSION['id_admin'] ?? 'NULL';
    
    $nama_file = $_POST['gambar_lama']; 
    
    // Upload gambar baru
    if (!empty($_FILES['gambar']['tmp_name'])) {
        $nama_file = time() . "_" . basename($_FILES['gambar']['name']);
        
        // JALUR DIPERBAIKI: Menambahkan ../ untuk naik dari folder dashboard
        $direktori = "../assets/img/gallery/";
        
        if (!is_dir($direktori)) { mkdir($direktori, 0777, true); }

        if (move_uploaded_file($_FILES['gambar']['tmp_name'], $direktori . $nama_file)) {
            // Hapus gambar lama jika admin mengunggah yang baru
            if (!empty($_POST['gambar_lama']) && file_exists($direktori . $_POST['gambar_lama'])) {
                unlink($direktori . $_POST['gambar_lama']);
            }
        }
    }

    if ($id_target) {
        // Query Update (Pakai kolom keterangan & id_admin aktif)
        $query = "UPDATE gallery SET 
                    id_admin = $id_admin,
                    keterangan = '$keterangan', 
                    tanggal = '$tanggal',
                    gambar = '$nama_file' 
                  WHERE id_gallery = '$id_target'";
    } else {
        // Query Insert (Pakai kolom keterangan & id_admin aktif)
        $query = "INSERT INTO gallery (id_admin, gambar, keterangan, tanggal) 
                  VALUES ($id_admin, '$nama_file', '$keterangan', '$tanggal')";
    }
    
    mysqli_query($conn, $query);
    
    // REDIRECT FIX: Kembalikan ke halaman dashboard admin utama
    echo "<script>window.location.href='$url_kembali';</script>";
    exit;
}

// ==========================================
// 3. LOGIKA HAPUS
// ==========================================
if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];
    
    // Hapus file fisik gambar terlebih dahulu
    $cek = mysqli_query($conn, "SELECT gambar FROM gallery WHERE id_gallery = '$id'");
    $data = mysqli_fetch_assoc($cek);
    
    // JALUR DIPERBAIKI: Menambahkan ../
    if (!empty($data['gambar']) && file_exists("../assets/img/gallery/" . $data['gambar'])) {
        unlink("../assets/img/gallery/" . $data['gambar']);
    }
    
    // Hapus data dari tabel
    mysqli_query($conn, "DELETE FROM gallery WHERE id_gallery = '$id'");
    
    // REDIRECT FIX
    echo "<script>window.location.href='$url_kembali';</script>";
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Management Gallery - Jo Cafe</title>
    <style>
        body { margin:0; font-family: Arial, sans-serif; background:#13171c; color:white; }
        .content { padding:20px; max-width:1100px; margin:auto; }
        .title { font-size:24px; margin-bottom:20px; font-weight: bold; }
        .grid { display:flex; gap:20px; align-items:flex-start; }
        .card { background:#1c2128; padding:20px; border-radius:10px; border: 1px solid #1f2937; }
        .form { width:320px; }
        table { width:100%; border-collapse:collapse; margin-top:10px; }
        td, th { padding:12px; border-bottom:1px solid #1f2937; text-align: left; }
        th { color: #f89d13; }
        input, select, textarea { width:100%; padding:10px; margin:8px 0; background:#0f1520; color:white; border:1px solid #1f2937; border-radius:6px; box-sizing: border-box; }
        input:focus, textarea:focus { border-color: #f89d13; outline: none; }
        button { width:100%; padding:12px; background:#f89d13; border:none; cursor:pointer; border-radius:6px; font-weight:bold; margin-top: 10px; color: #000;}
        button:hover { background: #e08c0f; }
        img { width:80px; height:50px; object-fit:cover; border-radius:5px; background: #0f1520; }
        .btn-del { color: white; background-color: #dc3545; text-decoration: none; font-size: 12px; font-weight: bold; padding: 5px 10px; border-radius: 4px; margin-left: 5px; }
        .btn-edit { color: black; background-color: #ffc107; text-decoration: none; font-size: 12px; font-weight: bold; padding: 5px 10px; border-radius: 4px; }
    </style>
</head>
<body>

<div class="content">
    <div class="title">Management <span style="color:#f89d13;">Gallery</span></div>

    <div class="grid">
        <div class="card form">
            <h3 style="color: #f89d13; margin-top:0;"><?php echo $is_edit ? "Edit Foto" : "Tambah Foto"; ?></h3>
            
            <form action="" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="id_gallery" value="<?php echo $edit_data['id_gallery']; ?>">
                <input type="hidden" name="gambar_lama" value="<?php echo $edit_data['gambar']; ?>">

                <label style="font-size: 12px; color: #aaa; font-weight:bold;">KETERANGAN FOTO</label>
                <textarea name="keterangan" rows="3" placeholder="Tulis keterangan/judul foto..." required><?php echo htmlspecialchars($edit_data['keterangan']); ?></textarea>

                <label style="font-size: 12px; color: #aaa; font-weight:bold; margin-top: 10px; display:block;">TANGGAL PUBLIKASI</label>
                <input type="date" name="tanggal" value="<?php echo $edit_data['tanggal']; ?>" required>

                <label style="font-size: 12px; color: #aaa; font-weight:bold; margin-top: 10px; display:block;">FILE GAMBAR</label>
                <input type="file" name="gambar" accept="image/*" <?php echo $is_edit ? '' : 'required'; ?>>

                <button type="submit" name="simpan"><?php echo $is_edit ? "Simpan Perubahan" : "Unggah Foto"; ?></button>
                
                <?php if($is_edit): ?>
                    <a href="<?= $url_kembali; ?>" style="display:block; text-align:center; color:#aaa; font-size:12px; margin-top:15px; text-decoration:none;">Batal Edit</a>
                <?php endif; ?>
            </form>
        </div>

        <div class="card" style="flex:1;">
            <h3 style="margin-top:0; color:white;">Data Gallery Atmosphere</h3>
            <table>
                <thead>
                    <tr>
                        <th style="width:15%">Foto</th>
                        <th style="width:40%">Keterangan</th>
                        <th style="width:20%">Tanggal</th>
                        <th style="width:25%">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $get_galeri = mysqli_query($conn, "SELECT * FROM gallery ORDER BY id_gallery DESC");
                    if (mysqli_num_rows($get_galeri) == 0) {
                        echo "<tr><td colspan='4' style='text-align:center; color:#888;'>Belum ada foto yang diunggah.</td></tr>";
                    }
                    while ($g = mysqli_fetch_assoc($get_galeri)) { 
                        // JALUR FOTO DIPERBAIKI: Tambah ../
                        $path = "../assets/img/gallery/".$g['gambar'];
                        $img_src = (!empty($g['gambar']) && file_exists($path)) ? $path : 'https://via.placeholder.com/80x50?text=No+Img';
                    ?>
                    <tr>
                        <td><img src="<?php echo $img_src; ?>" alt="Galeri"></td>
                        <td class="text-white"><?php echo htmlspecialchars($g['keterangan']); ?></td>
                        <td class="text-white"><?php echo date('d M Y', strtotime($g['tanggal'])); ?></td>
                        <td>
                            <a href="<?= $url_kembali; ?>&edit=<?php echo $g['id_gallery']; ?>" class="btn-edit">Edit</a>
                            <a href="<?= $url_kembali; ?>&hapus=<?php echo $g['id_gallery']; ?>" class="btn-del" onclick="return confirm('Hapus foto ini secara permanen?')">Hapus</a>
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