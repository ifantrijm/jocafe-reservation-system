<?php
// 1. KONEKSI DATABASE
$conn = mysqli_connect("localhost", "root", "", "jocafee");
if (!$conn) { die("Koneksi Gagal: " . mysqli_connect_error()); }
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $target = "../assets/img/room/" . basename($_FILES['foto']['name']);
    if (move_uploaded_file($_FILES['foto']['tmp_name'], $target)) {
        echo "Berhasil upload ke " . $target;
    } else {
        echo "Gagal, error code: " . $_FILES['foto']['error'];
    }
}
?>
<form method="post" enctype="multipart/form-data">
    <input type="file" name="foto" required>
    <button type="submit">Upload</button>
</form>