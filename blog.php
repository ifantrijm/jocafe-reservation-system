<?php
// KONEKSI DATABASE
$conn = mysqli_connect("localhost", "root", "", "jocafee");
if (!$conn) { die("Koneksi Gagal: " . mysqli_connect_error()); }

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

    if ($_FILES['gambar']['name'] != '') {
        $nama_file = time() . "_" . $_FILES['gambar']['name'];
        move_uploaded_file($_FILES['gambar']['tmp_name'], "assets/img/blog/" . $nama_file);

        mysqli_query($conn, "UPDATE blog 
            SET judul='$judul', isi='$isi', gambar='$nama_file', tanggal='$tanggal'
            WHERE id_blog='$id'");
    } else {
        mysqli_query($conn, "UPDATE blog 
            SET judul='$judul', isi='$isi', tanggal='$tanggal'
            WHERE id_blog='$id'");
    }

    header("Location: blog.php");
    exit;
}

// TAMBAH ARTIKEL
if (isset($_POST['tambah_artikel'])) {
    $judul = mysqli_real_escape_string($conn, $_POST['judul']);
    $isi = mysqli_real_escape_string($conn, $_POST['isi']);
    $tanggal = date('Y-m-d H:i:s');

    $nama_file = "";

    if ($_FILES['gambar']['name'] != '') {
        $nama_file = time() . "_" . basename($_FILES['gambar']['name']);
        move_uploaded_file($_FILES['gambar']['tmp_name'], "assets/img/blog/" . $nama_file);
    }

    mysqli_query($conn, "INSERT INTO blog (judul, isi, gambar, tanggal) 
                         VALUES ('$judul','$isi','$nama_file','$tanggal')");

    header("Location: blog.php");
    exit;
}

// HAPUS
if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];

    $cek = mysqli_query($conn, "SELECT gambar FROM blog WHERE id_blog='$id'");
    $data = mysqli_fetch_assoc($cek);

    if (!empty($data['gambar']) && file_exists("assets/img/blog/".$data['gambar'])) {
        unlink("assets/img/blog/".$data['gambar']);
    }

    mysqli_query($conn, "DELETE FROM blog WHERE id_blog='$id'");
    header("Location: blog.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Dashboard Blog - Jo Caffe</title>

<style>
:root {
    --bg-main: #13171c;
    --bg-card: #1c2128;
    --text-main: #ffffff;
    --text-muted: #a0aab5;
    --accent-gold: #f89d13;
}

body {
    margin: 0;
    font-family: Arial;
    background: var(--bg-main);
    color: var(--text-main);
    padding: 30px;
}

.grid {
    display: flex;
    gap: 20px;
}

.form-box {
    background: var(--bg-card);
    padding: 20px;
    border-radius: 10px;
    width: 350px;
}

.form-box input,
.form-box textarea {
    width: 100%;
    margin-top: 10px;
    padding: 10px;
    border: none;
    border-radius: 5px;
    background: var(--bg-main);
    color: white;
}

.form-box button {
    margin-top: 10px;
    width: 100%;
    padding: 12px;
    background: var(--accent-gold);
    border: none;
    border-radius: 5px;
    font-weight: bold;
    cursor: pointer;
}

.table-box {
    background: var(--bg-card);
    padding: 20px;
    border-radius: 10px;
    flex: 1;
}

table {
    width: 100%;
    border-collapse: collapse;
}

th, td {
    padding: 10px;
    border-bottom: 1px solid rgba(255,255,255,0.1);
}

img {
    width: 60px;
    height: 60px;
    object-fit: cover;
    border-radius: 5px;
}

.delete {
    background: crimson;
    color: white;
    padding: 5px 10px;
    border-radius: 5px;
    text-decoration: none;
}

.edit {
    background: orange;
    color: black;
    padding: 5px 10px;
    border-radius: 5px;
    text-decoration: none;
}

.back-home {
    position: fixed;
    bottom: 20px;
    left: 20px;
}

.back-home a {
    background: var(--accent-gold);
    color: black;
    padding: 12px 18px;
    border-radius: 20px;
    text-decoration: none;
}
</style>
</head>

<body>

<h1>Dashboard Blog</h1>

<div class="grid">

<!-- FORM -->
<div class="form-box">
<form method="POST" enctype="multipart/form-data">

<input type="hidden" name="id_blog" value="<?= $editData['id_blog'] ?? '' ?>">

<input type="text" name="judul"
value="<?= $editData['judul'] ?? '' ?>" placeholder="Judul" required>

<textarea name="isi" placeholder="Isi artikel..." required><?= $editData['isi'] ?? '' ?></textarea>

<input type="file" name="gambar">

<button type="submit" name="<?= $editData ? 'update_artikel' : 'tambah_artikel' ?>">
<?= $editData ? 'Update Artikel' : 'Post Artikel' ?>
</button>

</form>
</div>

<!-- TABLE -->
<div class="table-box">
<table>
<thead>
<tr>
<th>Cover</th>
<th>Judul</th>
<th>Isi</th>
<th>Tanggal</th>
<th>Aksi</th>
</tr>
</thead>

<tbody>
<?php
$res = mysqli_query($conn, "SELECT * FROM blog ORDER BY id_blog DESC");
while ($row = mysqli_fetch_assoc($res)) :
$foto = $row['gambar'] ? "assets/img/blog/".$row['gambar'] : "https://via.placeholder.com/60";
?>

<tr>
<td><img src="<?= $foto ?>"></td>
<td><?= $row['judul'] ?></td>
<td><?= substr($row['isi'],0,50) ?>...</td>
<td><?= $row['tanggal'] ?></td>
<td>
<a href="?edit=<?= $row['id_blog'] ?>" class="edit">Edit</a>
<a href="?hapus=<?= $row['id_blog'] ?>" class="delete" onclick="return confirm('Hapus?')">Hapus</a>
</td>
</tr>

<?php endwhile; ?>
</tbody>

</table>
</div>

</div>

<div class="back-home">
<a href="index.php">← Kembali ke Home</a>
</div>

</body>
</html>