<?php
include "../config/koneksi.php";

if(isset($_GET['tgl'])) {
    $tgl = mysqli_real_escape_string($conn, $_GET['tgl']);
    
    // Hitung event di tanggal tersebut yang statusnya BUKAN 'selesai' dan BUKAN 'cancelled'
    $query = mysqli_query($conn, "SELECT id_event_res FROM reservasi_event 
                                  WHERE tanggal_event = '$tgl' 
                                  AND status_booking != 'selesai' 
                                  AND status_booking != 'cancelled'");
    
    echo mysqli_num_rows($query);
}
?>