<?php
// Pastikan session sudah aktif dari admin.php untuk mengambil id_admin
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 1. KONEKSI DATABASE
include_once "../config/koneksi.php";

// --- FITUR EDIT: AMBIL DATA LAMA ---
$is_edit = false;
$edit_data = ['id_menu' => '', 'nama_item' => '', 'kategori' => '', 'deskripsi' => '', 'harga' => '', 'gambar' => ''];

if (isset($_GET['edit'])) {
    $is_edit = true;
    $id_edit = $_GET['edit'];
    $result = mysqli_query($conn, "SELECT * FROM menu WHERE id_menu = '$id_edit'");
    if(mysqli_num_rows($result) > 0) {
        $edit_data = mysqli_fetch_assoc($result);
    }
}

// 2. SIMPAN / UPDATE DATA KE DATABASE
if (isset($_POST['simpan'])) {
    $nama = mysqli_real_escape_string($conn, $_POST['nama_item']);
    $kategori = $_POST['kategori'];
    $deskripsi = mysqli_real_escape_string($conn, $_POST['deskripsi']);
    
    // PERBAIKAN HARGA: Buang desimal .00 atau ,00 di akhir, lalu ambil angkanya saja
    $harga_mentah = $_POST['harga'];
    $harga_mentah = preg_replace('/[,.]00$/', '', $harga_mentah); 
    $harga = (int) preg_replace('/[^0-9]/', '', $harga_mentah);
    
    $id_target = $_POST['id_menu']; 
    
    // Ambil ID Admin yang sedang login (Pastikan jadi integer atau NULL untuk cegah SQL Error)
    $id_admin = isset($_SESSION['id_admin']) ? (int)$_SESSION['id_admin'] : 'NULL';

    // Set default nama file pakai gambar yang lama
    $nama_file = $_POST['gambar_lama']; 
    
    // PERBAIKAN UPLOAD GAMBAR: Cek error 0 (sukses masuk temp)
    if (isset($_FILES['gambar']) && $_FILES['gambar']['error'] === 0) {
        $nama_asli = preg_replace("/\s+/", "_", basename($_FILES['gambar']['name']));
        $nama_file_baru = time() . "_" . $nama_asli;
        $direktori = "../assets/img/menu/"; 
        
        if (move_uploaded_file($_FILES['gambar']['tmp_name'], $direktori . $nama_file_baru)) {
            // Hapus gambar lama JIKA ADA
            if (!empty($_POST['gambar_lama']) && file_exists($direktori . $_POST['gambar_lama'])) {
                unlink($direktori . $_POST['gambar_lama']);
            }
            $nama_file = $nama_file_baru;
        } else {
            echo "<script>alert('Gambar gagal dipindahkan! Cek permission folder.'); window.history.back();</script>";
            exit;
        }
    } else if (isset($_FILES['gambar']) && $_FILES['gambar']['error'] !== 4) {
        echo "<script>alert('Gagal upload! Gambar mungkin terlalu besar (Maksimal 2MB).'); window.history.back();</script>";
        exit;
    }

    if ($id_target) {
        // Query Update 
        $query = "UPDATE menu SET 
                    id_admin = $id_admin,
                    nama_item = '$nama', 
                    kategori = '$kategori', 
                    deskripsi = '$deskripsi', 
                    harga = '$harga', 
                    gambar = '$nama_file' 
                  WHERE id_menu = '$id_target'";
    } else {
        // Query Insert
        $query = "INSERT INTO menu (id_admin, nama_item, kategori, deskripsi, harga, gambar) 
                  VALUES ($id_admin, '$nama', '$kategori', '$deskripsi', '$harga', '$nama_file')";
    }
    
    if(!mysqli_query($conn, $query)) {
        echo "<script>alert('Gagal menyimpan ke database: " . mysqli_error($conn) . "'); window.history.back();</script>";
        exit;
    }
    
    echo "<script>window.location.href='admin.php?page=menu';</script>";
    exit;
}

// 3. LOGIKA HAPUS
if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];
    $cek = mysqli_query($conn, "SELECT gambar FROM menu WHERE id_menu = '$id'");
    $data = mysqli_fetch_assoc($cek);
    
    if (!empty($data['gambar']) && file_exists("../assets/img/menu/" . $data['gambar'])) {
        unlink("../assets/img/menu/" . $data['gambar']);
    }
    mysqli_query($conn, "DELETE FROM menu WHERE id_menu = '$id'");
    
    echo "<script>window.location.href='admin.php?page=menu';</script>";
    exit;
}

// 4. LOGIKA BEST SELLER
if (isset($_GET['toggle_bs'])) {
    $id = $_GET['toggle_bs'];
    $cek = mysqli_fetch_assoc(mysqli_query($conn, "SELECT is_bestseller FROM menu WHERE id_menu = '$id'"));
    
    if ($cek['is_bestseller'] == 1) {
        mysqli_query($conn, "UPDATE menu SET is_bestseller = 0 WHERE id_menu = '$id'");
    } else {
        $count = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM menu WHERE is_bestseller = 1"));
        if ($count['total'] < 3) {
            mysqli_query($conn, "UPDATE menu SET is_bestseller = 1 WHERE id_menu = '$id'");
        } else {
            echo "<script>alert('Maksimal hanya 3 menu Best Seller yang diizinkan!');</script>";
        }
    }
    echo "<script>window.location.href='admin.php?page=menu';</script>";
    exit;
}
?>

<style>
    .content-menu { padding:20px; max-width:1100px; margin:auto; color: white; font-family: 'Plus Jakarta Sans', sans-serif;}
    .title { font-size:24px; margin-bottom:20px; font-weight: bold;}
    .stats { margin-bottom:20px; }
    .stat-box { background:#1c2128; padding:20px; border-radius:10px; border: 1px solid #444; }
    .grid { display:flex; gap:20px; align-items:flex-start; }
    .card { background:#1c2128; padding:20px; border-radius:10px; border: 1px solid #444; }
    .form { width:320px; position: sticky; top: 20px; }
    table { width:100%; border-collapse:collapse; margin-top:10px; color: white;}
    td, th { padding:10px; border-bottom:1px solid #444; text-align: left; }
    input, select, textarea { width:100%; padding:10px; margin:8px 0; background:#13171c; color:white; border:1px solid #444; border-radius:6px; box-sizing: border-box; }
    .btn-submit { width:100%; padding:10px; background:#f89d13; border:none; cursor:pointer; border-radius:6px; font-weight:bold; margin-top: 10px; color: black;}
    .img-menu { width:60px; height:60px; object-fit:cover; border-radius:5px; background: #13171c; }
    .btn-del { background: crimson; color: white; padding: 5px 10px; border-radius: 5px; text-decoration: none; font-size: 12px; font-weight: bold; margin-left: 5px; }
    .btn-edit { background: orange; color: black; padding: 5px 10px; border-radius: 5px; text-decoration: none; font-size: 12px; font-weight: bold; }
    .cancel-edit { display: block; text-align: center; margin-top: 10px; color: #aaa; text-decoration: none; font-size: 12px; }
</style>

<div class="content-menu">
    <div class="title">Management Menu</div>

    <!-- <div class="stats">
        <div class="stat-box">
            <?php $count = mysqli_query($conn, "SELECT id_menu FROM menu"); ?>
            Total Menu Terdaftar: <strong><?php echo mysqli_num_rows($count); ?></strong> Item
        </div>
    </div> -->

    <div class="grid">
        <div class="card form">
            <h3 style="color: #f89d13; margin-top:0;"><?php echo $is_edit ? "Edit Menu" : "Tambah Menu"; ?></h3>
            <form action="admin.php?page=menu" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="id_menu" value="<?php echo $edit_data['id_menu']; ?>">
                <!-- <input type="hi dden" name="gambar_lama" value="<?php echo $edit_data['gambar']; ?>"> -->

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
                <input type="number" name="harga" value="<?php echo $edit_data['harga'] ? (int)$edit_data['harga'] : ''; ?>" placeholder="Contoh: 15000" required>

                <label style="font-size: 12px; color: #aaa;">FOTO PRODUK <?php if($is_edit) echo "(Kosongkan jika tidak ganti)"; ?></label>
                <input type="file" name="gambar" accept="image/*">

                <button type="submit" name="simpan" class="btn-submit"><?php echo $is_edit ? "Update Data" : "Simpan ke Menu"; ?></button>
                
                <?php if($is_edit): ?>
                    <a href="admin.php?page=menu" class="cancel-edit">Batal Edit / Tambah Baru</a>
                <?php endif; ?>
            </form>
        </div>

        <div class="card" style="flex:1;">
            <h3 style="margin-top:0;" class="text-white">Data Menu Jo Cafe</h3>
            <table>
                <thead>
                    <tr>
                        <th>Foto</th>
                        <th>Nama</th>
                        <th>Kategori</th>
                        <th>Harga</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $get_menu = mysqli_query($conn, "SELECT * FROM menu ORDER BY kategori ASC, id_menu DESC");
                    while ($m = mysqli_fetch_assoc($get_menu)) { 
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
                            <a href="admin.php?page=menu&toggle_bs=<?php echo $m['id_menu']; ?>" style="background: <?php echo isset($m['is_bestseller']) && $m['is_bestseller'] ? '#dc3545' : '#198754'; ?>; color: white; padding: 5px 10px; border-radius: 5px; text-decoration: none; font-size: 12px; font-weight: bold; margin-right: 5px;">
                                <?php echo isset($m['is_bestseller']) && $m['is_bestseller'] ? 'Batal' : 'Best Seller'; ?>
                            </a>
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