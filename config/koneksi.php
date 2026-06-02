<?php
// Kunci waktu mutlak ke WIB (Asia/Jakarta)
date_default_timezone_set('Asia/Jakarta');

// $conn = mysqli_connect("mif.myhost.id", "mifmyho2_A2", "@MIF2025", "mifmyho2_A2");
$conn = mysqli_connect("localhost", "root", "", "jocafee");
if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}
?>