<?php
// 1. KONEKSI DATABASE (Disarankan pakai include_once agar tidak bentrok)
include_once "../config/koneksi.php";

// Jika file koneksi.php belum ada, pakai yang lama:
// $conn = mysqli_connect("localhost", "root", "", "jocafee");
// if (!$conn) { die("Koneksi Gagal: " . mysqli_connect_error()); }

// --- FITUR EDIT: AMBIL DATA LAMA ---
$is_edit = false;
$edit_data = ['id_menu' => '', 'nama_item' => '', 'kategori' => '', 'deskripsi' => '', 'harga' => '', 'gambar' => ''];

if (isset($_GET['edit'])) {
    $is_edit = true;
    $id_edit = $_GET['edit'];
    $result = mysqli_query($conn, "SELECT * FROM menu WHERE id_menu = '$id_edit'");
    $edit_data = mysqli_fetch_assoc($result);
}

// 2. SIMPAN / UPDATE DATA KE DATABASE
if (isset($_POST['simpan'])) {
    $nama = mysqli_real_escape_string($conn, $_POST['nama_item']);
    $kategori = $_POST['kategori'];
    $deskripsi = mysqli_real_escape_string($conn, $_POST['deskripsi']);
    $harga = str_replace(['.', ','], '', $_POST['harga']);
    $id_target = $_POST['id_menu']; // ID untuk update

    $nama_file = $_POST['gambar_lama']; // Default pake gambar lama
    
    // Cek jika ada upload gambar baru
    if (!empty($_FILES['gambar']['tmp_name'])) {
        $nama_file = time() . "_" . basename($_FILES['gambar']['name']);
        
        // JALUR DIPERBAIKI: Tambah ../ karena admin.php ada di dalam folder dashboard
        $direktori = "../assets/img/menu/"; 
        
        if (move_uploaded_file($_FILES['gambar']['tmp_name'], $direktori . $nama_file)) {
            // Hapus file lama jika ada dan bukan gambar default
            if (!empty($_POST['gambar_lama']) && file_exists($direktori . $_POST['gambar_lama'])) {
                unlink($direktori . $_POST['gambar_lama']);
            }
        }
    }

    if ($id_target) {
        // Query Update
        $query = "UPDATE menu SET 
                    nama_item = '$nama', 
                    kategori = '$kategori', 
                    deskripsi = '$deskripsi', 
                    harga = '$harga', 
                    gambar = '$nama_file' 
                  WHERE id_menu = '$id_target'";
    } else {
        // Query Insert
        $query = "INSERT INTO menu (nama_item, kategori, deskripsi, harga, gambar) 
                  VALUES ('$nama', '$kategori', '$deskripsi', '$harga', '$nama_file')";
    }
    
    mysqli_query($conn, $query);
    
    // REDIRECT DIPERBAIKI: Arahkan kembali ke admin.php?page=menu
    header("Location: admin.php?page=menu");
    exit;
}

// 3. LOGIKA HAPUS
if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];
    $cek = mysqli_query($conn, "SELECT gambar FROM menu WHERE id_menu = '$id'");
    $data = mysqli_fetch_assoc($cek);
    
    // JALUR DIPERBAIKI: Tambah ../ 
    if (!empty($data['gambar']) && file_exists("../assets/img/menu/" . $data['gambar'])) {
        unlink("../assets/img/menu/" . $data['gambar']);
    }
    mysqli_query($conn, "DELETE FROM menu WHERE id_menu = '$id'");
    
    // REDIRECT DIPERBAIKI
    header("Location: admin.php?page=menu");
    exit;
}
?>

<style>
    .content-menu { padding:20px; max-width:1100px; margin:auto; color: white; }
    .title { font-size:24px; margin-bottom:20px; font-weight: bold;}
    .stats { margin-bottom:20px; }
    .stat-box { background:#1c2128; padding:20px; border-radius:10px; border: 1px solid #444; }
    .grid { display:flex; gap:20px; align-items:flex-start; }
    .card { background:#1c2128; padding:20px; border-radius:10px; border: 1px solid #444; }
    .form { width:320px; }
    table { width:100%; border-collapse:collapse; margin-top:10px; color: white;}
    td, th { padding:10px; border-bottom:1px solid #444; text-align: left; }
    input, select, textarea { width:100%; padding:10px; margin:8px 0; background:#13171c; color:white; border:1px solid #444; border-radius:6px; box-sizing: border-box; }
    .btn-submit { width:100%; padding:10px; background:#f89d13; border:none; cursor:pointer; border-radius:6px; font-weight:bold; margin-top: 10px; color: black;}
    .img-menu { width:60px; height:60px; object-fit:cover; border-radius:5px; background: #13171c; }
    .btn-del { color: #ff4d4d; text-decoration: none; font-size: 13px; font-weight: bold; margin-left: 10px; }
    .btn-edit { color: #f89d13; text-decoration: none; font-size: 13px; font-weight: bold; }
    .cancel-edit { display: block; text-align: center; margin-top: 10px; color: #aaa; text-decoration: none; font-size: 12px; }
</style>

<div class="content-menu">
    <div class="title">Management Menu</div>

    <div class="stats">
        <div class="stat-box">
            <?php $count = mysqli_query($conn, "SELECT id_menu FROM menu"); ?>
            Total Menu Terdaftar: <strong><?php echo mysqli_num_rows($count); ?></strong> Item
        </div>
    </div>

    <div class="grid">
        <div class="card form">
            <h3 style="color: #f89d13; margin-top:0;"><?php echo $is_edit ? "Edit Menu" : "Tambah Menu"; ?></h3>
            <form action="admin.php?page=menu" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="id_menu" value="<?php echo $edit_data['id_menu']; ?>">
                <input type="hidden" name="gambar_lama" value="<?php echo $edit_data['gambar']; ?>">

                <label style="font-size: 12px; color: #aaa;">NAMA ITEM</label>
                <input type="text" name="nama_item" value="<?php echo htmlspecialchars($edit_data['nama_item']); ?>" placeholder="Contoh: Kopi Susu Gula Aren" required>

                <label style="font-size: 12px; color: #aaa;">KATEGORI</label>
                <select name="kategori">
                    <option value="makanan" <?php if($edit_data['kategori'] == 'makanan') echo 'selected'; ?>>Makanan</option>
                    <option value="minuman" <?php if($edit_data['kategori'] == 'minuman') echo 'selected'; ?>>Minuman</option>
                </select>

                <label style="font-size: 12px; color: #aaa;">DESKRIPSI</label>
                <textarea name="deskripsi" placeholder="Penjelasan singkat rasa/porsi..."><?php echo htmlspecialchars($edit_data['deskripsi']); ?></textarea>

                <label style="font-size: 12px; color: #aaa;">HARGA (RP)</label>
                <input type="text" name="harga" value="<?php echo $edit_data['harga']; ?>" placeholder="Contoh: 15000" required>

                <label style="font-size: 12px; color: #aaa;">FOTO PRODUK <?php if($is_edit) echo "(Kosongkan jika tidak ganti)"; ?></label>
                <input type="file" name="gambar" accept="image/*">

                <button type="submit" name="simpan" class="btn-submit"><?php echo $is_edit ? "Update Data" : "Simpan ke Menu"; ?></button>
                
                <?php if($is_edit): ?>
                    <a href="admin.php?page=menu" class="cancel-edit">Batal Edit / Tambah Baru</a>
                <?php endif; ?>
            </form>
        </div>

        <div class="card" style="flex:1;">
            <h3 style="margin-top:0;">Data Menu Jo Cafe</h3>
            <table>
                <thead>
                    <tr>
                        <th>Foto</th>
                        <th>Nama</th>
                        <th>Kategori</th>
                        <th>Harga</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $get_menu = mysqli_query($conn, "SELECT * FROM menu ORDER BY kategori ASC");
                    while ($m = mysqli_fetch_assoc($get_menu)) { 
                        // JALUR RENDER GAMBAR DIPERBAIKI: Tambah ../
                        $path_foto = !empty($m['gambar']) ? "../assets/img/menu/".$m['gambar'] : "https://via.placeholder.com/60x60?text=No+Img";
                    ?>
                    <tr>
                        <td><img src="<?php echo $path_foto; ?>" alt="Menu" class="img-menu"></td>
                        <td>
                            <strong><?php echo htmlspecialchars($m['nama_item']); ?></strong><br>
                            <small style="color: #888;"><?php echo htmlspecialchars($m['deskripsi']); ?></small>
                        </td>
                        <td><span style="text-transform: capitalize;"><?php echo $m['kategori']; ?></span></td>
                        <td>Rp <?php echo number_format($m['harga'], 0, ',', '.'); ?></td>
                        <td>
                            <a href="admin.php?page=menu&edit=<?php echo $m['id_menu']; ?>" class="btn-edit">Edit</a>
                            <a href="admin.php?page=menu&hapus=<?php echo $m['id_menu']; ?>" class="btn-del" onclick="return confirm('Hapus menu ini?')">Hapus</a>
                        </td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>