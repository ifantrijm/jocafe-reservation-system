<?php
include 'koneksi.php';

$nama = $_POST['nama'];
$id   = $_POST['id_pelanggan'];
$telp = $_POST['no_telp'];
$pesan= $_POST['pesan'];
$rating=$_POST['rating'];
$tgl = date('Y-m-d');

mysqli_query($koneksi,"INSERT INTO testimoni
(id_pelanggan,nama,no_telp,pesan,rating,status,tanggal)
VALUES
('$id','$nama','$telp','$pesan','$rating','tampilkan','$tgl')");

header("Location: testimoni.php");
?>