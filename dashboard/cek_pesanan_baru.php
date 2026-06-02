<?php
require "../config/koneksi.php";

// Cari ID terbesar (terbaru) dari tabel room dan event
$q_room = mysqli_query($conn, "SELECT MAX(id_reservasi_room) as max_room FROM reservasi_room");
$d_room = mysqli_fetch_assoc($q_room);

$q_event = mysqli_query($conn, "SELECT MAX(id_event_res) as max_event FROM reservasi_event");
$d_event = mysqli_fetch_assoc($q_event);

// Kirim hasilnya ke JavaScript
echo json_encode([
    'max_room' => $d_room['max_room'] ? $d_room['max_room'] : 0,
    'max_event' => $d_event['max_event'] ? $d_event['max_event'] : 0
]);
?>