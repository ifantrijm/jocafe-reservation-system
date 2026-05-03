<?php
$conn = mysqli_connect("localhost", "root", "", "jocafee");
if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}
?>