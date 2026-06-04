<?php
require "../config/koneksi.php";
header('Content-Type: application/json');

if(isset($_GET['id_room']) && isset($_GET['tgl'])) {
    $id = $_GET['id_room'];
    $tgl = $_GET['tgl'];

    // Cari jadwal yang belum selesai pada tanggal tersebut
    $query = mysqli_query($conn, "SELECT jam_mulai, jam_selesai FROM reservasi_room WHERE id_room = '$id' AND tanggal_reservasi = '$tgl' AND status_pesanan != 'Selesai' ORDER BY jam_mulai ASC");
    
    $jadwal = [];
    while($row = mysqli_fetch_assoc($query)) {
        // Format jam agar terlihat lebih rapi (misal dari 14:00:00 jadi 14:00)
        $mulai = date('H:i', strtotime($row['jam_mulai']));
        $selesai = date('H:i', strtotime($row['jam_selesai']));
        $jadwal[] = ['mulai' => $mulai, 'selesai' => $selesai];
    }
    
    echo json_encode($jadwal);
} else {
    echo json_encode([]);
}
?>