<?php
// Pastikan session sudah dimulai di admin.php, jika belum atau berdiri sendiri, aktifkan session_start()
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// KONEKSI DATABASE
include_once "../config/koneksi.php";

// AMBIL DATA EDIT
$editData = null;
if (isset($_GET['edit'])) {
    $id = $_GET['edit'];
    $q = mysqli_query($conn, "SELECT * FROM blog WHERE id_blog='$id'");
    $editData = mysqli_fetch_assoc($q);
}

// UPDATE ARTIKEL
if (isset($_POST['update_artikel'])) {
    $id = $_POST['id_blog'];
    $judul = mysqli_real_escape_string($conn, $_POST['judul']);
    $isi = mysqli_real_escape_string($conn, $_POST['isi']);
    $tanggal = date('Y-m-d H:i:s');
    
    // Log Aktivitas: Ambil ID Admin yang sedang login dari session
    $id_admin = $_SESSION['id_admin'] ?? 'NULL';

    if ($_FILES['gambar']['name'] != '') {
        $nama_file = time() . "_" . $_FILES['gambar']['name'];
        move_uploaded_file($_FILES['gambar']['tmp_name'], "../assets/img/blog/" . $nama_file);

        mysqli_query($conn, "UPDATE blog 
            SET id_admin=$id_admin, judul='$judul', isi='$isi', gambar='$nama_file', tanggal='$tanggal'
            WHERE id_blog='$id'");
    } else {
        mysqli_query($conn, "UPDATE blog 
            SET id_admin=$id_admin, judul='$judul', isi='$isi', tanggal='$tanggal'
            WHERE id_blog='$id'");
    }

    // FIX HEADERS: Menggunakan JavaScript Redirect agar sinkron dengan template admin
    echo "<script>window.location.href='admin.php?page=blog';</script>";
    exit;
}

// TAMBAH ARTIKEL
if (isset($_POST['tambah_artikel'])) {
    $judul = mysqli_real_escape_string($conn, $_POST['judul']);
    $isi = mysqli_real_escape_string($conn, $_POST['isi']);
    $tanggal = date('Y-m-d H:i:s');
    
    // Log Aktivitas: Ambil ID Admin yang sedang login dari session
    $id_admin = $_SESSION['id_admin'] ?? 'NULL';

    $nama_file = "";

    if ($_FILES['gambar']['name'] != '') {
        $nama_file = time() . "_" . basename($_FILES['gambar']['name']);
        move_uploaded_file($_FILES['gambar']['tmp_name'], "../assets/img/blog/" . $nama_file);
    }

    mysqli_query($conn, "INSERT INTO blog (id_admin, judul, isi, gambar, tanggal) 
                         VALUES ($id_admin, '$judul', '$isi', '$nama_file', '$tanggal')");

    // FIX HEADERS: Menggunakan JavaScript Redirect
    echo "<script>window.location.href='admin.php?page=blog';</script>";
    exit;
}

// HAPUS
if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];

    $cek = mysqli_query($conn, "SELECT gambar FROM blog WHERE id_blog='$id'");
    $data = mysqli_fetch_assoc($cek);

    if (!empty($data['gambar']) && file_exists("../assets/img/blog/".$data['gambar'])) {
        unlink("../assets/img/blog/".$data['gambar']);
    }

    mysqli_query($conn, "DELETE FROM blog WHERE id_blog='$id'");
    
    // FIX HEADERS: Menggunakan JavaScript Redirect
    echo "<script>window.location.href='admin.php?page=blog';</script>";
    exit;
}
?>

<style>
    .blog-content { color: white; padding: 20px; font-family: 'Plus Jakarta Sans', sans-serif;}
    .blog-grid { display: flex; gap: 20px; }
    
    .blog-form-box { background: #1c2128; padding: 20px; border-radius: 10px; width: 350px; border: 1px solid #1f2937; }
    .blog-form-box input, .blog-form-box textarea { width: 100%; margin-top: 10px; padding: 10px; border: 1px solid #1f2937; border-radius: 5px; background: #13171c; color: white; box-sizing: border-box;}
    .blog-form-box button { margin-top: 10px; width: 100%; padding: 12px; background: #f89d13; border: none; border-radius: 5px; font-weight: bold; cursor: pointer; color: black;}
    
    .blog-table-box { background: #1c2128; padding: 20px; border-radius: 10px; flex: 1; border: 1px solid #1f2937; }
    .blog-table { width: 100%; border-collapse: collapse; color: white; }
    .blog-table th, .blog-table td { padding: 12px; border-bottom: 1px solid rgba(255,255,255,0.1); text-align: left;}
    .blog-table th { color: #f89d13; }
    
    .blog-img { width: 60px; height: 60px; object-fit: cover; border-radius: 5px; background-color: #13171c;}
    .btn-delete { background: crimson; color: white; padding: 6px 12px; border-radius: 5px; text-decoration: none; font-size: 0.85rem;}
    .btn-edit { background: orange; color: black; padding: 6px 12px; border-radius: 5px; text-decoration: none; font-size: 0.85rem;}
    
    .cancel-edit { display: block; text-align: center; margin-top: 10px; color: #aaa; text-decoration: none; font-size: 12px; }
</style>

<div class="blog-content">
    <h2 style="font-weight: bold; margin-bottom: 20px;">Dashboard <span style="color: #f89d13;">Blog</span></h2>

    <div class="blog-grid">
        <div class="blog-form-box">
            <h4 style="color: #f89d13; margin-top: 0;"><?= $editData ? 'Edit Artikel' : 'Tulis Artikel' ?></h4>
            <form action="admin.php?page=blog" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="id_blog" value="<?= $editData['id_blog'] ?? '' ?>">
                
                <input type="text" name="judul" value="<?= $editData['judul'] ?? '' ?>" placeholder="Judul" required>
                
                <textarea name="isi" placeholder="Isi artikel..." rows="5" required><?= $editData['isi'] ?? '' ?></textarea>
                
                <label style="font-size: 12px; color: #aaa; margin-top: 10px; display: block;">Cover Image</label>
                <input type="file" name="gambar" accept="image/*">
                
                <button type="submit" name="<?= $editData ? 'update_artikel' : 'tambah_artikel' ?>">
                    <?= $editData ? 'Simpan Perubahan' : 'Post Artikel' ?>
                </button>

                <?php if($editData): ?>
                    <a href="admin.php?page=blog" class="cancel-edit">Batal Edit</a>
                <?php endif; ?>
            </form>
        </div>

        <div class="blog-table-box">
            <table class="blog-table">
                <thead>
                    <tr>
                        <th style="width: 15%;">Cover</th>
                        <th style="width: 25%;">Judul</th>
                        <th style="width: 30%;">Isi Singkat</th>
                        <th style="width: 15%;">Tanggal</th>
                        <th style="width: 15%;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $res = mysqli_query($conn, "SELECT * FROM blog ORDER BY id_blog DESC");
                    while ($row = mysqli_fetch_assoc($res)) :
                        $foto = $row['gambar'] ? "../assets/img/blog/".$row['gambar'] : "https://via.placeholder.com/60";
                    ?>
                    <tr>
                        <td><img src="<?= $foto ?>" class="blog-img"></td>
                        <td style="font-weight: bold;"><?= htmlspecialchars($row['judul']) ?></td>
                        <td><small style="color: #aaa;"><?= htmlspecialchars(substr($row['isi'], 0, 50)) ?>...</small></td>
                        <td><small><?= date('d M Y', strtotime($row['tanggal'])) ?></small></td>
                        <td>
                            <a href="admin.php?page=blog&edit=<?= $row['id_blog'] ?>" class="btn-edit">Edit</a>
                            <a href="admin.php?page=blog&hapus=<?= $row['id_blog'] ?>" class="btn-delete" onclick="return confirm('Hapus artikel ini?')">Hapus</a>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>